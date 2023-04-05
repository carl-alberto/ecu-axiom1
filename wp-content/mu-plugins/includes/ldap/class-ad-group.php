<?php

namespace Ldap;

defined( 'ABSPATH' ) OR exit;

/**
 * Class ECU Active Directory Group
 *
 * Represents an ECU Group account and provides functionality for managing users in WordPress based on group membership.
 */
class Ad_Group extends Ad
{
	/**
     * The ADLDAP2 Group object.
     *
     * If false then the group hasn't been init or there was a problem
     * with init the object.
     *
     * @see https://adldap2.github.io/Adldap2/#/models/group
     *
     * @var bool|int
     */
	protected $group = false;

	/**
	 * Used to keep track of what groups have already been seen in recursive functions.
	 *
	 * @var array
	 */
	protected $groups;

	/**
	 * Used to keep track of what users have already been seen in recursive functions.
	 *
	 * @var array
	 */
	protected $users; 

	/**
	 * Used to keep track of results for operations in recursive functions.
	 *
	 * @var array
	 */
	protected $results;

	/**
	 * Sets the default AD provider and the group object if a common name is provided.
	 *
	 * @param  string $cn   The common name for the group.   ie. ATWEB
	 */
	public function __construct($cn = null) {
	
		parent::__construct();
		
		if($cn) {
			$this->init($cn);
		}
	}

	/**
	 * Initilaizes the ADLDAP group object.
	 *
	 * @param  string $cn   Valid group common name
	 */
	public function init($cn) {
		// Get only the attributes that are needed for the class.  Please keep this updated
		// if you add additional functionality. 
		// Use the group set function to set a adldap user object with all or custom attributes.
		$this->set_group($cn,['samaccountname', 'distinguishedname', 'member']);
	}

	/**
  	 * Returns if the group model is valid.
	 *
	 * @return boolean
	 */
	public function is_valid() {
		if(false === $this->group) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Sets the ADLDAP group object.  Defaults to retrieve all AD attributes.   This will result
	 * in a slower query.   Limit the attributes to only those you need to be quicker.  Will
	 * be set to false if the group lookup fails.
	 *
	 * @see https://adldap2.github.io/Adldap2/#/searching
	 *
	 * @param  	string $cn 	  Valid group common name
	 * @param 	string|array  $select   	 Array of attributes to retrieve.  Defaults to all.
	 */	  
	public function set_group($cn, $select = '*') {
		$this->group = $this->ad->search()->groups() // Only want groups
			->select($select)
			->rawFilter($this->ad->getSchema()->filterEnabled()) // Only search for enabled ad accounts
			->find($cn);
	}

	/**
	 * Returns the ADLDAP2 group object of the AD account.
	 *
     * @see https://adldap2.github.io/Adldap2/#/models/group
     *
	 * @return bool|object FALSE if not set or ADLDAP2 User object 
	 */
	public function get_group() {
		return $this->group;
	}

	/**
	 * Adds group members to the blog with the specified role.
	 *
	 * @see https://adldap2.github.io/Adldap2/#/models/group?id=getting-a-groups-members
	 *
	 * @param 	int 	$blog_id 	The blog_id to add the users to.
	 * @param 	string 	$role 		The role to set the user too.
	 * @param 	bool 	$recursive 	Weather to do a recursive add.
	 *
	 * @return 	array 	The results of each add operation.
	 */
	public function add_group_to_blog($blog_id, $role, $recursive = true) {

		$this->results = [];

		// If not a valid group then return
		if(!$this->group) {
			return $this->results;
		}

		$this->users = [];
		$this->groups[] = $this->group->getDN(); //keep track of groups so we don't get caught in an infinte loop. 
		$this->add_group_to_blog_recursive($this->group, $blog_id, $role, $recursive);
		return $this->results;
	}

	/**
	 * Recursive function for adding users to blogs.
	 *
	 * @see https://adldap2.github.io/Adldap2/#/models/group?id=getting-a-groups-members
	 *
	 * @param 	int 	$blog_id 	The blog_id to add the users to.
	 * @param 	string 	$role 		The role to set the user too.
	 * @param 	bool 	$recursive 	Weather to do a recursive add.
	 */
	private function add_group_to_blog_recursive($group, $blog_id, $role, $recursive) {
		
		foreach($group->member as $dn) {

			$member = $this->ad->search(['samaccountname', 'distinguishedname', 'member'])->findByDn($dn);

			if(is_a($member, 'Adldap\Models\Group') && $recursive ) {
				if(false === in_array($dn, $this->groups)) {
					$this->groups[] = $dn; //keep track of groups so we don't get caught in an infinte loop. 
					$this->add_group_to_blog_recursive($member, $blog_id, $role, $recursive);
				}
			} elseif (is_a($member, 'Adldap\Models\User')) {
				$pirate_id = $member->getAccountName();
				if(false === in_array($pirate_id, $this->users)) {
					$this->users[] = $pirate_id;
					$ad_user = new Ad_User($pirate_id);
					$result = $ad_user->add_user_to_blog($blog_id, $role);
					if(is_wp_error($result)) {
						$this->results[] = $result->get_error_message();
					} else {
						$this->results[] = $result;
					}
				}
			}
		}
	}

	/**
	 * Returns all the user pirate ids of a group.
	 *
	 * @see https://adldap2.github.io/Adldap2/#/models/group?id=getting-a-groups-members
	 *
	 * @param bool $recursive Weather to do a recursive lookup.
	 *
	 * @return array The pirate id of group memebers
	 */
	public function get_member_pirate_ids($recursive = true) {
		
		// If not a valid group then return
		if(!$this->group) {
			return $this->group;
		}

		$this->users =[];
		$this->groups[] = $this->group->getDN(); //keep track of groups so we don't get caught in an infinte loop. 
		$this->get_member_pirate_ids_recursive($this->group, $recursive);
		return $this->users;
	}

	/**
	 * Recursive function for member lookup.
	 *
	 * @see https://adldap2.github.io/Adldap2/#/models/group?id=getting-a-groups-members
	 *
	 * @param Object $group ADLDAP2 Group object.
	 * @param bool $recursive Weather to do a recursive lookup.
	 */
	private function get_member_pirate_ids_recursive($group, $recursive) {
		
		foreach($group->member as $dn) {

			$member = $this->ad->search(['samaccountname', 'distinguishedname', 'member'])->findByDn($dn);

			if(is_a($member, 'Adldap\Models\Group') && $recursive) {
				if(false === in_array($dn, $this->groups)) {
					$this->groups[] = $dn; //keep track of groups so we don't get caught in an infinte loop. 
					$this->get_member_pirate_ids_recursive($member, $recursive);
				}
			} elseif (is_a($member, 'Adldap\Models\User')) {
				$pirate_id = $member->getAccountName();
				if(false === in_array($pirate_id, $this->users)) {
					$this->users[] = $pirate_id;
				}
			}
		}
	}
}