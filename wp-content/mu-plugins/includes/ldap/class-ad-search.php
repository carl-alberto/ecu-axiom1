<?php

namespace Ldap;
use \WP_Error as WP_Error;
use \Exception as Exception;

defined( 'ABSPATH' ) OR exit;

/*
 *	This class is to search all AD Users.
 *	Returns array of Ad_User
 *
 *	Utility Methods:
 *		-show_staff()
 *		-show_students()
 *		-show_hidden()
 *		-set_sort($sort)	-array()
 *		-set_limit($limit) 	- int
 *
 *** You MUST include show_staff() or show_students() or both to specify which filter to apply ***
 *
 * 	Search Methods:
 * 		-find_by_phone()
 * 		-find_by_first_name()
 * 		-find_by_last_name()
 * 		-find_by_full_name()
 * 		-find_by_pirate_id()
 * 		-find_by_filter() 	- specify your own filter - automatically enforces security filter unless you specify otherwise using chaining method
 *
 *	Example Usage:
 *		$search = new Ad_Search();
 *		$users = $search->show_staff()->limit(250)->find_by_first_name('Daniel'); //returns people with first name Daniel, limit of 250
 *
 *		$search = new Ad_Search();
 *		$users = $search->show_staff()->find_by_phone('7371515'); // or $search->show_staff()->find_by_phone('252-737-1515');
 *		//strips non numeric characters and ignores first 3 digits if 10 digit number is specified
 *
 *		$connection = AD Connection of your choosing
 *		$search = new Ad_Search($connection); //defaults to S2Ldap if $connection isn't passed
 *		$users = $search->show_hidden()->show_staff()->search_by_filter('STRING OF YOUR OWN FILTER HERE');
 *
 *		$search = new Ad_Search();
 *		$user = $search->show_staff()->show_students()->show_hidden()->find_by_full_name('Daniel',  'Krochmalny');
 *
 *
 *
 *	@author atwebdev
 *	May 2020
 */
class Ad_Search extends Ad
{
    private $show_staff = false;			//Show only staff
    private $show_students = false;			//Show only students
	private $show_itcs = false;			//Show only students
    private $show_hidden = false;			//Show users who are hidden from addess book
    private $sort = 'displayname';			//how to sort the requested records.
    private $limit = 0;						//max number of results to bring back from AD query

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

	/*
     * Tells class to include staff
     */
    public function show_staff()	{
		$this->show_staff = true;
		return $this;
	}
	/*
     * Tells class to include students
     */
	public function show_students()	{
		$this->show_students = true;
		return $this;
	}

	/*
     * Tells class to include cotanche employees
     */
	public function show_itcs()	{
		$this->show_itcs = true;
		return $this;
	}
	/*
     * Include people who are hidden from the address book
     */
	public function show_hidden()	{
		$this->show_hidden = true;
		return $this;
	}
	/*
     * Set limit for number of results
     */
	public function set_limit($limit)	{
		if ($limit > 1000) {
			$limit = 999;
		}
		$this->limit = $limit;
		return $this;
	}
	/*
     * Change the default sort of displayname
     */
	public function set_sort($sort)	{
		$this->sort = $sort;
		return $this;
	}
	/*
     * Security filter that tells AD not to return users that are hidden from address book
     */
	public function get_security_filter()	{
		return '(!(personaltitle=Y))(!(msExchHideFromAddressLists=TRUE))';
	}
	/*
     * Filter to only show staff
     */
	public function get_staff_filter()	{
		return '(memberOf=CN=ECU Official Staff,OU=MainCampus,DC=intra,DC=ecu,DC=edu)(memberOf=CN=ECU Official Faculty,OU=MainCampus,DC=intra,DC=ecu,DC=edu)';
	}
	/*
     * Filter to only show students
     */
	public function get_student_filter()	{
		return '(memberOf=CN=Students_Enrolled,OU=Students,DC=intra,DC=ecu,DC=edu)';
	}

	/*
     * Filter to only show cotanche employees
     */
	public function get_itcs_filter()	{
		return '(memberOf=CN=ITCS,OU=MainCampus,DC=intra,DC=ecu,DC=edu)';
	}
	/*
	 * This method creates the filters that show staff, show students and show hidden from address book
	 *
	 */
	public function get_base_filters()	{
		$filter = '';
		if ($this->show_staff && $this->show_students) {
			$filter .= '(|'. $this->get_staff_filter() . $this->get_student_filter() .')';
		}	elseif ($this->show_staff) {
			$filter .= '(|' . $this->get_staff_filter() . ')';
		}	elseif ($this->show_students) {
			$filter .= $this->get_student_filter();
		}   elseif ($this->show_itcs) {
			$filter .= $this->get_itcs_filter();
		}
		if (!$this->show_hidden) {
			$filter .= $this->get_security_filter();
		}
		return $filter;
	}
	/*
	 * This method returns an array of AD_User based on the filter that was built with the other methods and the base filters
	 *
	 */
	public function populate_users($entries)	{
		$users = array();
        foreach($entries as $e)	{
        	if ($e->getAccountName()) {
		        $user = new Ad_User($e->getAccountName());
		        $users[] = $user;
	    	}	else    {
				$users[] = new Ad_User($e->getAccountName());
			}
		}
		return $users;
	}
	/*
     * Find a user using a given pirate id
     */
	public function find_by_pirate_id($pirate_id)	{
		$filter = '(samaccountname=' . $pirate_id . ')';
		$this->limit = 1;
		return self::create_filter($filter);
	}
    /*
     * Provide your own filter
     * Use this method directly if you write to create your own custom filter
     * Automatically enforces security filter unless you specify otherwise using chaining method
     */
	public function find_by_filter($filter)	{
		$this->get_base_filters($filter);
		return self::search($filter);
	}
	/*
     * Find a user by a given phone number
     */
	public function find_by_phone($number)	{
		if    (isset($number) && $number != '')	{
			if (preg_match('/([0-9]{3})?[- .]?([0-9]{4})$/', $number, $matches)) {
				$telephone_number = $matches[1] . '-' . $matches[2];
				$filter = '(telephoneNumber=*' . $telephone_number . '*)';
			}	else    {
				return array();
			}
		}	else {
			return array();
		}
		return self::create_filter($filter);
	}
	/*
     * Find a user by providing a first name
     */
	public function find_by_first_name($firstName)	{
		//escape apostrophes
		$firstName = str_replace ("\'", "'", $firstName);
		if (isset($firstName) && $firstName != '') {
			$filter = "(givenName=" . $firstName . "*)";
		}	else {
			return array();
		}
		return self::create_filter($filter);
	}
	/*
     * Find a user by providing a last name
     */
	public function find_by_last_name($lastName)	{
		//escape apostrophes
		$lastName = str_replace ("\'", "'", $lastName);
		if (isset($lastName) && $lastName != '') {
			$filter = "(|(sn=" . $lastName . "*)(sn=*-" . $lastName . "*))";
		}	else {
			return array();
		}
		return self::create_filter($filter);
	}
	/*
     * Find a user by providing first name, last name
     *
     */
	public function find_by_full_name($firstName, $lastName)	{
		//escape apostrophes
		$firstName = str_replace ("\'", "'", $firstName);
		$lastName = str_replace ("\'", "'", $lastName);
		if (isset($firstName) && $firstName != '' && isset($lastName) && $lastName != '') {
			$filter = "(&(givenName=" . $firstName . "*)";
			$filter .= "(|(sn=" . $lastName . "*)(sn=*-" . $lastName . "*)))";
		}	else   {
			return array();
		}
		return self::create_filter($filter);
	}
	/*
     * Create the filter
     * This method combines all of the filter pieces that have been constructed through chaining and privdes the finished filter
     */
	private function create_filter($filterParts)	{
		$filter = '(&';
		$filter .= $this->get_base_filters();
		$filter .= $filterParts;
		$filter .= ')';
		return self::search($filter);
	}
	/*
	 * This function applies all options set up to this point, performs the search and returns an array of Ad_User
	 */
	private function search($filter)	{
        $fields = array(
        	'samaccountname',
        	'proxyaddresses',
			'sn',
			'department',
			'title',
			'givenname',
			'userprincipalname',
			'telephonenumber',
			'physicaldeliveryofficename',
			'extensionattribute8',
			'personaltitle',
			'msExchHideFromAddressLists',
			'useraccountcontrol',
			'company'
        );

        $search = $this->ad->search();
		$entries = $search->rawFilter($filter)->limit($this->limit)->sortBy($this->sort, 'asc')->get();
       	$users = $this->populate_users($entries);
        return $users;
	}
}
?>
