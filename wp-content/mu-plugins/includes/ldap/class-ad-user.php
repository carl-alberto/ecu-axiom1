<?php

namespace Ldap;
use \WP_Error as WP_Error;
use \Exception as Exception;

defined( 'ABSPATH' ) OR exit;

/**
 * Class ECU Active Directory User
 *
 * Represents an ECU AD account and corresponding WordPress user.  Provides functionality
 * for managing the user in WordPress.
 */
class Ad_User extends Ad
{
    /**
     * The ADLDAP2 User object.
     *
     * If false then the user hasn't been init or there was a problem
     * with init the user object.
     *
     * @see https://adldap2.github.io/Adldap2/#/models/user
     *
     * @var bool|int
     */
	protected $user = false;

    /**
     * The WordPress User ID for this user.
     *
     * Only keeping the ID and not the object because doing things like add roles requires the need to also provide
     * the blog id and getting a WP User object for that blog.   So no point in keeping a user object ready to use since
     * a lookup will likely have to be done every time something like that is required.  Things like add or removing users
     * from blogs only requires the WP user id and blog id.
     *
     * @var bool|int
     */
	protected $wp_id = false;

	/**
	 * Sets the default AD provider and the user object/wp user id if a pirate id is provided.
	 *
	 * @param  string $pirate_id   Valid Pirate Id
	 */
	public function __construct($pirate_id = null) {

		parent::__construct();

		if($pirate_id) {
			$this->init($pirate_id);
		}
	}

	/**
	 * Initilaizes the ADLDAP user and WP User ID for the Pirate ID.
	 *
	 * @see https://developer.wordpress.org/reference/functions/get_user_by/
	 *
	 * @param  string $pirate_id   Valid Pirate Id
	 */
	public function init($pirate_id) {
		// Get only the attributes that are needed for the class.  Please keep this updated
		// if you add additional functionality.
		// Use the user set function to set a adldap user object with all or custom attributes.
		$this->set_user($pirate_id, ['samaccountname','mail','givenname','sn','extensionattribute8','personaltitle','showinaddressbook','physicaldeliveryofficename','title','memberof', 'department', 'telephonenumber', 'company']);

		$wp_user = get_user_by('login', $pirate_id);
		if ($wp_user) {
			$this->wp_id = $wp_user->id;
		}
	}

	/**
	 * Sets the ADLDAP user object.  Defaults to retrieve all AD attributes.   This will result
	 * in a slower query.   Limit the attributes to only those you need to be quicker.  Will
	 * be set to false if the user lookup fails.
	 *
	 * @see https://adldap2.github.io/Adldap2/#/searching
	 *
	 * @param string 		$pirate_id   Valid Pirate Id
	 * @param string|array  $select   	 Array of attributes to retrieve.  Defaults to all.
	 */
	public function set_user($pirate_id, $select = '*') {
		$this->user = $this->ad->search()->users()
			->select($select) // will be faster if you specify only the attributes you need
			->rawFilter($this->ad->getSchema()->filterEnabled()) // Only search for enabled ad accounts
			->findBy('samaccountname', $pirate_id);
	}

	/**
	 * Returns false if there is not a WP User ID.
	 *
	 * @return bool|int FALSE if no WP User ID.
	 */
	public function get_wp_id() {
		return $this->wp_id;
	}

	/**
	 * Returns the ADLDAP2 User object of the AD account.
	 *
     * @see https://adldap2.github.io/Adldap2/#/models/user
     *
	 * return bool|object FALSE if not set or ADLDAP2 User object
	 */
	public function get_user() {
		return $this->user;
	}

	/**
  	 * Returns if the AD Account is a employee.
	 *
	 * @return boolean
	 */
	public function is_employee() {
		if($this->user->getAttribute('extensionattribute8')[0] == 'Employee') {
			return true;
		} else {
			return false;
		}
	}

	/**
  	 * Returns if the AD Account is a student.
	 *
	 * @return boolean
	 */
	public function is_student() {
		if (strpos($this->user->getEmail(), 'students') || strtolower($this->user->getPhysicalDeliveryOfficeName()) == 'student') {
			return true;
		} else {
			return false;
		}
	}

	/**
  	 * Returns if the AD Account is retired.
	 *
	 * @return boolean
	 */
	public function is_retired()	{
		if ($this->user->getAttribute('extensionattribute8')[0] == 'Retired' || $this->user->getTitle() == 'Retired') {
			return true;
		} else {
			return false;
		}
	}

	/**
  	 * Returns if the AD Account is hidden by ECU.
	 *
	 * @return boolean
	 */
	public function is_hidden()	{
		if (($this->user->getPersonalTitle() == 'Y') || $this->user->getShowInAddressBook()) {
			return true;
		} else {
			return false;
		}
	}

	/**
  	 * Returns if the ADLDAP user model is valid.
	 *
	 * @return boolean
	 */
	public function is_valid() {
		if(false === $this->user) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Determine if the AD account is a member of the specified group(s).
	 *
	 * @see https://adldap2.github.io/Adldap2/#/models/traits/has-member-of?id=checking-if-the-model-is-apart-of-a-group
	 *
	 * @param  string|array $group 	The group or groups to check for
	 * @param  boolean 		$or 	Wether to do 'and' or 'or' conditional.
	 * @return boolean True if the user is in the group, false if not.
	 */
	public function in_group($group, $or = true) {
		if($or && is_array($group)) {
			foreach($group as $g) {
				if($this->user->inGroup($g, true)) {
					return true;
				}
			}
			return false;
		} else {
			// Only supports an AND conditional if $group is an array.
			return $this->user->inGroup($group, true);
		}
	}

	/**
	 * Adds a AD user to a blog with the specified role.   Note that this does not
	 * supplant WordPress functionality.  This action requires a valid AD account.
	 *
	 * @see https://adldap2.github.io/Adldap2/#/models/user
	 * @see https://codex.wordpress.org/Function_Reference/is_user_member_of_blog
	 * @see https://codex.wordpress.org/Function_Reference/add_user_to_blog
	 *
	 * @param 	int 	$blog_id 	The blog_id to add the users to.
	 * @param 	string 	$role 		The role to set the user too.
	 *
	 * @return WP_ERROR|string Returns a WP Error if found or the result of the add operation.
	 */
	public function add_user_to_blog($blog_id, $role) {

		if(!$this->wp_id) {
			$result = $this->create_wp_user();
		}

		if(!is_user_member_of_blog($this->wp_id, $blog_id)) {
   	       	$result = add_user_to_blog( $blog_id, $this->wp_id, $role);
   	    } else {
   	    	return $this->user->getAccountName() . ' is already on Blog ID: ' . $blog_id;
   	    }

        if(is_wp_error($result)) {
            return $result;
        } else {
       		return 'Added ' . $this->user->getAccountName() . ' on Blog ID: ' . $blog_id . ' and assigned ' . $role . ' role.';
       	}
	}

	/**
	 * Authenticates the credentials against AD.   No need to provide a username if
	 * this class has been initialized with a valid pirate id.
	 *
	 * @see https://adldap2.github.io/Adldap2/#/setup?id=authenticating
	 *
	 * @param  string $password 	The password
	 * @param  string $username 	The username
	 * @return boolean True if the user is authenticated|False if not
	 */
	public function authenticate($password, $username = NULL) {
		try {
			// this will allow passwords with special characters to still pass authentication
			$password = stripslashes_deep($password);

			if(empty($username) && $this->user) {
				$username = $this->user->getAccountName();
			}

			// Check for Employee
	   		if($this->ad->auth()->attempt($username, $password)) {
	   			return true;
	   		} else {
	   			// Check for Student
	   			return $this->ad->getProvider('student')->auth()->attempt($username, $password);
	   		}
	   	} catch (Exception $e) {
	   		return false;
	   	}
	}

	/**
	 * Sync the local wordpress account information, such as email, with
	 * information from AD if it exists.
	 *
	 * @return int|WP_Error The updated user's ID or a WP_Error object.
	 */
	public function sync_wp_user()  {

		/**
         * WARNING!!!!   By design if a user account doesn't exist then the WordPress functions that update an account information
	     * will CREATE an account. When an account is created WordPress automatically adds the account to the current blog
	     * with the default role.  We do not want this to happen when just sync a wp user account with ldap information.
	 	 */
		if(!$this->wp_id) {
			return new WP_error(
				'error',
				$this->user->getAccountName() . ' does not have a WordPress acccount!   Create their account by adding them to a blog first!'
			);
		}

		return $this->create_wp_user();
	}

	/**
	 * Return the WP user object for this user.
	 *
	 * @return WP_USER|false The WP user object or false.
	 */
	public function get_wp_user() {
		if(!$this->wp_id) {
			return false;
		}

		return get_user_by('id', $this->wp_id); // returns false on failure
	}

	/**
	 * Will create a user for the pirate_id.
	 *
	 * @see https://codex.wordpress.org/Function_Reference/wp_insert_user
	 *
	 * @return int|WP_Error The created/updated user's ID or a WP_Error object.
	 */
	public function create_wp_user()  {

		if($this->user) {

			$pirate_id = strtolower($this->user->getAccountName());
			$user_data = [];
			$user_data['user_login'] = $pirate_id;
			$user_data['user_email'] = strtolower($this->user->getEmail());
			$user_data['display_name'] = $user_data['nickname'] = $this->user->getFirstName() . ' ' . $this->user->getLastName();
			$user_data['first_name'] = $this->user->getFirstName();
			$user_data['last_name'] = $this->user->getLastName();

			// Generate a random password
			$chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
            '0123456789`-=~!@#$%^&*()_+,./<>?;:[]{}\|';

			$str = '';
			$max = strlen($chars) - 1;

			for ($i=0; $i < 75; $i++) {
				$str .= $chars[random_int(0, $max)];
			}

			$user_data['user_pass'] = $str;

			//check if there is a user in the WP
			if ($this->wp_id) {
				//update user
				$user_data['ID'] = $this->wp_id;
			}

			// Creates/Updates User
			$user_id = wp_insert_user($user_data);
			unset($user_data); // Free up memory for when called in loops.
			if ( is_wp_error( $user_id )  ) {
		    	return $user_id;
		    }


			if(!$this->wp_id) {
		    	// Wordpress automatically adds the user to the site when they
				// are created.   We do not want this for users that already existed,
				// so only do when a WP ID didn't exists.
		    	remove_user_from_blog($user_id, get_current_blog_id());
		    	$this->wp_id = $user_id;
		    	return $this->wp_id;
		    }

		} else {
			return new WP_error(
				'error',
				$pirate_id . ' in not a valid pirate id!'
			);
		}
	}


}