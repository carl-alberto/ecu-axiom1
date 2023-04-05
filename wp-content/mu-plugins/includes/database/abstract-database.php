<?php

namespace Database;
use \PDO as PDO;
use \Exception as Exception;

/**
 * Class provides mysql PDO objects to databases. Implements the Singleton (?anti-?)pattern.
 *
 * Provides a useful static function to query the database/cache.  To use create a class that implements an init function.
 * The init function should set the database infomation and credentials.   Then you can use the query function like so:   Foo::query($sql);
 */
abstract class Database {

	/**
	 * How long the data should be cached for in seconds.  Defaults to 5 mins.
	 */
	const CACHE_DURATION = 300;

	/**
	 * The DB handler for database
	 *
	 * @link http://php.net/manual/en/book.pdo.php PDO
	 *
	 * @var Object PDO object connected to the db.
	 */
	private static $instance;

	/**
 	 * The database host for the PDO connection.
 	 *
	 * @var string
	 */
	protected $host = '';

	/**
 	 * The database name for the PDO connection.
 	 *
	 * @var string
	 */
	protected $dbname = '';

	/**
 	 * The database user for the PDO connection.
 	 *
	 * @var string
	 */
	protected $user = '';

	/**
 	 * The database user password for the PDO connection.
 	 *
	 * @var string
	 */
	protected $password = '';

	private function __construct() {
		try {

			$this->init();

			self::$instance = new PDO (
                'mysql:host='  . $this->host . ';
                dbname=' .  $this->dbname . ';',
                $this->user,
                $this->password
            );

			// Lets LIMIT work with Prepared statments
			self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);

			// Throw exceptions for any errors
			self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Set default fetch mode, return results as obj
			// Note that fetch all will return an array of results regardless of this setting.
			self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

		} catch (Exception $e) {
		    //echo 'Caught Exception: '.  $e->getMessage(). "\n";
		    self::$instance = null;
		}

	}

	/**
	 * Set the host, dbname, user, and password to connect to the db..
	 */
	abstract protected function init();

	/**
	 * Gets a singleton instance for the db calls homepage db.
	 *
 	 * @link http://php.net/manual/en/book.pdo.php PDO
 	 *
	 * @return object Returns a PDO object on success, null on failure.
	 */
	public static function get_instance() {
		if (self::$instance == null)
    	{
      		new static();
    	}

    	return self::$instance;
	}

	/**
	 * This function allows you to execute SQL queries against the database.   If you wish to bypass the cache be sure to set
	 * the third arguument to false.  This will work with any valid SQL queries such as Select, Insert, Delete, Update, etc.
	 * If the query has a result set then that is returned as an array.   Otherwise an empty array is returned.
	 *
 	 * @link http://www.php.net/manual/en/function.crc32.php Hash Function
	 * @link https://codex.wordpress.org/Function_Reference/set_site_transient Set Transient Cache
	 * @link https://codex.wordpress.org/Function_Reference/get_site_transient Get the transient Cache
	 * @link http://php.net/manual/en/pdo.query.php PDO Query
	 *
	 * @param string 	    $sql   			The SQL statement to prepare and execute. Data inside the query should be properly escaped.
 	 * @param int           $expiration   	Time until expiration in seconds from now, 0 for never expires, or false for skip the cache.
	 * @param array			$params			An array of parameters to bind in the sql.
	 *
	 * @return array An array of Objects.   Empty array will be returned if any errors happen.
	 */
	public static function query($sql, $params = null, $expiration = self::CACHE_DURATION) {

		$cache = array();

		if(false !== $expiration) {
			$hash = $sql;
			if(is_array($params))
				$hash .= implode('_', $params);
			$hash = 'query_' . hash("crc32b", $hash);
			$cache = get_site_transient($hash);
		}

		if ( (false === $cache) || (false === $expiration) ){
			try {

				$dbh = static::get_instance();

				// return an empty array if there was an issue connecting to the database.
				if(empty($dbh)) {
					return array();
				}

				// execute the query and store results in cache
       			$query = $dbh->prepare($sql);
        		if($query->execute($params)) {
					// Get the result set if there is any to return.
            		$cache = $query->fetchAll();
				}

				if(false !== $expiration) {
        			set_site_transient($hash, $cache, $expiration);
				}

	    		return $cache;
	    	} catch (Exception $e) {
		    	// Return an empty array on any exception.
		    	return array();
			}
		} else {
	   		return $cache;
		}
	}
}