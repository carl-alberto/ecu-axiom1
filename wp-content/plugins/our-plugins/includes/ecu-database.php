<?php
	namespace OUR_PLUGINS;

	/**
	 * Class provides wpdb objects to ecu databases.
	 */
	class Ecu_Database {
		/**
		 * The DB handler for hompage_tools_dr database
		 *
		 * @link https://codex.wordpress.org/Class_Reference/wpdb WPDB API
		 *
		 * @var Object wpdb object connected to the homepage db.  Use the get function
		 * to get a singleton instance.
		 */
		private static $homepage_db;

		/**
		 * The DB handler for the homepage_tools database.  Use the get function
		 * to get a singleton instance.
		 *
		 * @link https://codex.wordpress.org/Class_Reference/wpdb WPDB API
		 *
		 * @var Object wpdb object connected to the tools db.
		 */
		private static $tools_db;

		/**
		 * Gets a singleton instance for the db calls homepage db.
		 *
	 	 * @link https://codex.wordpress.org/Class_Reference/wpdb WPDB API
	 	 *
		 * @author Ryan Cowan <cowanr@ecu.edu>
		 *
		 * @return object the instance of the wpdb pointing to homepage db.
		 */
		public function get_homepage_db() {
			try {
				if(!self::$homepage_db) {
					self::$homepage_db = new \wpdb(getenv('HOMEPAGE_DB_USER'),getenv('HOMEPAGE_DB_PASSWORD'),getenv('HOMEPAGE_DB_NAME'),getenv('HOMEPAGE_DB_HOST'));
				}
				//echo 'homepage<br /><br />';

				//var_dump(getenv('HOMEPAGE_DB_USER').', '.getenv('HOMEPAGE_DB_PASSWORD').', '.getenv('HOMEPAGE_DB_NAME').', '.getenv('HOMEPAGE_DB_HOST'));
				return self::$homepage_db;
			} catch (Exception $e) {

			    echo 'Caught Exception: '.  $e->getMessage(). "\n";

			}
		}

		/**
		 * Gets a singleton instance for the db calls tools db.
		 *
	 	 * @link https://codex.wordpress.org/Class_Reference/wpdb WPDB API
	 	 *
		 * @author Ryan Cowan <cowanr@ecu.edu>
		 *
		 * @return object the instance of the wpdb pointing to tools db.
		 */
		public function get_tools_db() {
			try {
				if(!self::$tools_db) {
					self::$tools_db = new \wpdb(getenv('TOOLS_DB_USER'),getenv('TOOLS_DB_PASSWORD'),getenv('TOOLS_DB_NAME'),getenv('TOOLS_DB_HOST'));
				}
				//echo 'tools<br /><br />';
				//var_dump(getenv('TOOLS_DB_USER').', '.getenv('TOOLS_DB_PASSWORD').', '.getenv('TOOLS_DB_NAME').', '.getenv('TOOLS_DB_HOST'));
				return self::$tools_db;
			} catch (Exception $e) {

			    echo 'Caught Exception: '.  $e->getMessage(). "\n";

			}
		}
	}
