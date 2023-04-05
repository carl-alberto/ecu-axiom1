<?php

namespace Mu_Plugins;

defined( 'ABSPATH' ) OR exit;

/**
 * Abstract Class ECU Form 
 * 
 * Provides a starting point to encapsulate forms operations ( data validation/sanitation, submission process, error/result reporting).
 * This is not meant to be form generator.   Also provides some data retrieval functions.  
 */
abstract class Form
{
	/**
	 * Used to track errors during processing. Basically if a wp error then
	 * get the error message and store here for output later.
	 *
	 * @var array
	 */
	public $errors = [];

	/**
	 * Used to track results during processing. Basically things like "User added"
	 * or "User already on the site"  
	 *
	 * @var array
	 */
	public $results = [];

	/**
	 * Can be used as a type of flash message.  I recommend setting it with errors
	 * after completing processing and then displaying it at the top of the form.  
	 *
	 * @var string
	 */
	public $message = '';

	/**
	 * Process the form submission and set any results/errors/message for 
	 * output in the form template.
	 */
	abstract public function process();

	/**
	 * Returns an array containing all sites. Useful for building a
	 * select dropdown. The array is ordered by the domain and path
	 * and excludes the root site.
	 *
	 * @see https://developer.wordpress.org/reference/functions/get_sites/ 
	 *
	 * @return array
	 */
	public function get_all_blogs() {
		if(BLOG_ID_CURRENT_SITE == get_current_blog_id() ) {
			return get_sites(array('number'=>0,'orderby' => array('domain', 'path')));
		} else {
			return get_sites(array('number'=>0,'orderby' => array('domain', 'path'), 'site__not_in'=> array(BLOG_ID_CURRENT_SITE)));
		}	
	}

	/**
	 * Gets all the blog ids directly from the database.   Useful for 
	 * processing on operation that is to be applied to all 
	 * sites.  Does not have the overhead of get_sites and excludes the root site.
	 *  
	 * @return array
	 */
	public function get_all_blog_ids() {
		global $wpdb;
		if(BLOG_ID_CURRENT_SITE == get_current_blog_id() ) {
			return $wpdb->get_col( 'SELECT blog_id FROM ' .  getenv('WORDPRESS_DB_NAME') . '.' . getenv('WORDPRESS_TABLE_PREFIX') . 'blogs' );
		} else {
			return $wpdb->get_col( 'SELECT blog_id FROM ' .  getenv('WORDPRESS_DB_NAME') . '.' . getenv('WORDPRESS_TABLE_PREFIX') . 'blogs WHERE blog_id != ' . BLOG_ID_CURRENT_SITE );
		}
	}

	/**
	 * Returns an array with all the sites role names, except for the administrator role.   Useful to make form controls to select roles.
	 *
	 * @see https://codex.wordpress.org/Function_Reference/get_editable_roles
	 *
	 * @return array
	 */
	public function get_all_roles($allow_admin = false) {
		$roles = get_editable_roles();
		if(!$allow_admin) {
			unset($roles['administrator']);
		}
		$wp_roles = array_keys($roles);
		return $wp_roles;
	}

	/**
	 * Returs true if their are errors, false otherwise.
	 *
	 * @return bool
	 */
	public function has_errors() {
		return !empty($this->errors);
	}

	/**
	 * Returs true if their are results, false otherwise.
	 *
	 * @return bool
	 */
	public function has_results() {
		return !empty($this->results);
	}

	/**
	 * Get function for the results.
	 *
	 * @return array
	 */
	public function get_results() {
		return $this->results;
	}

	/**
	 * Get function for the errors.
	 *
	 * @return array
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Adds the errors to the error array.
	 *
	 * @param array 	    $errs   	The array of error messages to add to the form.
	 */
	public function add_errors($errs) {
		$this->errors = array_merge($this->errors, $errs);
	}

	/**
	 * Get function for the message.
	 *
	 * @return string
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * Set function for the message.
	 *
	 * @param string 	    $msg   	The flash message of the form.
	 */
	public function set_message($msg) {
		return $this->message = $msg;
	}

	/**
	 * Loops through the results and creates a list with a h2 header.
	 *
	 * @return string
	 */
	public function render_results() {
		// use ob_start to save on memory usage
		ob_start();
		if($this->has_results()) {
			echo '<br /><hr />';
			echo '<h2>Results:</h2>';
			foreach($this->results as $result) {
				echo '<p>' . $result . '</p>';
			}
		} 
		return ob_get_clean();
	}

	/**
	 * Loops through the errors and creates a list with a h2 header.
	 *
	 * @return string
	 */
	public function render_errors() {
		// use ob_start to save on memory usage
		ob_start();
		if($this->has_errors()) {
			echo '<br /><hr />';
			echo '<h2>Errors:</h2>';
			foreach($this->errors as $error) {
				echo '<p>' . $error . '</p>';
			}
		} 
		return ob_get_clean();
	}

	/**
	 * Gets the form stored in memory and returns it.   Useful for persisting the state of the submission between requests.
	 *
	 * @return object
	 */
	public static function get_state() {
		$user = wp_get_current_user();
		$state = get_transient($user->ID . '_wp_user_management_form');
		delete_transient($user->ID . '_wp_user_management_form');
		return $state;  
	}

	/**
	 * Saves the form object to memory.   Useful for persisting the state of the submission between requests.
	 */
	public function set_state() {
		$user = wp_get_current_user();
		set_transient( $user->ID . '_wp_user_management_form', $this, 20 );
	}
}