<?php
/*
Plugin Name: Our CON Directory
Description: Dynamic faculty/staff directory system for College of Nursing website.
Author: Chuck Baldwin
Version: 1.0
License: GPL2
*/

if ( ! class_exists( 'Con_Directory' ) ) {
	/**
	 * Fetches, sorts, and processes data for CON directory pages, and passes it to javascript for display.
	 *
	 * All php functionality is handled by this class. Shortcode is setup, hooks registered,
	 * parameters processed, and required data from backend is loaded/processed.
	 * Necessary javascript and CSS are loaded.
	 */
	class Con_Directory
	{
		/**
		 * Configuration variables for plugin.
		 *
		 * @var string [ 'path' ] - URL of the backend adapter.
		 * @var string [ 'shortcode' ] - Name of the shortcode.
		 * @var array [ 'debug' ] - Debugging messages to pass to javascript.
		 */
		private $config = [
			'path' => 'https://service.nursing.ecu.edu/publicadapter/Default.aspx',
			'shortcode' => 'personnel',
			'debug' => [],
		];

		/**
		 * Register shortcode hooks and query string filter.
		 *
		 * @return none
		 */
		public function register() {
			add_filter( 'query_vars', [ $this, 'add_query_vars_filter' ] );
			add_shortcode( $this->config[ 'shortcode' ], [ $this, 'init' ] );
			require( 'libs/HTMLPurifier.standalone.php' );
		}

		/**
		 * Callback to register the personnel shortcode.
		 *
		 * @param array $atts Shortcode parameters.
		 * @return none
		 */
		public function init( $atts ) {
			$atts = array_change_key_case( ( array ) $atts, CASE_LOWER );
			$atts = shortcode_atts(
				array(
					'mode' => '0',
					'order' => '',
					'col' => '1',
				), $atts, 'personnel'
			);
			$data = [];
			$script = '';

			// Mode 6 means profile pages.
			if ( $atts[ 'mode' ] == 6 ) {
				$userid = get_query_var( 'pid' );
				// Enqueue javascript for profile pages.
				$script = 'js/profile.js';

				// Fetch full profile data for indicated employee.
				$path = $this->config[ 'path' ] . '?userid=' . $userid . '&detail=3';
				$data = json_decode( $this->get_directory_data( $path ) );

				// Sanitize tab data.
				$data = $data->Tabs ? $this->clean_data( $data ) : $data;

			} else {
				// Enqueue javascript for directory pages.
				$script = 'js/con_dir.js';

				// Fetch all personnel listing data.
				$data = json_decode( $this->get_directory_data( $this->config[ 'path' ] ), true );

				// Begin building administrator listing if indicated.
				if ( $atts[ 'mode' ] == 1 ) {
					$data = $this->build_admin_data( $data );
				}
			}
			$this->enqueue_custom_scripts( $script );

			// Pass data object to javascript
			wp_localize_script( 'dir-js', 'data', array(
				'mode' => $atts[ 'mode' ],
				'columns' => $atts[ 'col' ],
				'employees' => $data,
				'order' => $atts[ 'order' ],
				'debug' => $this->config[ 'debug' ],
			) );
		}

		/**
		 * Sanitize html string data.
		 *
		 * @param object $data Employee image and tabs data.
		 * @return object Employee image data and sanitized tabs data.
		 */
		public function clean_data( $data ) {

			$purifier = new HTMLPurifier();
			// Sanity check to make sure purifier is working.
			//$purifier->config->set('HTML.Allowed', 'span');

			$data->Tabs = json_decode( $data->Tabs );
			foreach ( $data->Tabs as $key => $value ) {
				if ( is_string( $value ) ) {
					// remove oddball microsoft characters
					$value = $this->replaceWordChars( $value );

					// purifier needs utf8 or odd things happen
					$data->Tabs->$key = $purifier->purify( utf8_encode( $value ) );

					//array_push( $this->config[ 'debug' ], $data->Tabs->$key );
				}
			}
			return $data;
		}

		/**
		 * Make the call to our adapter to fetch all initial directory data.
		 *
		 * @param string $path URL for the backend adapter.
		 * @return string Body of response.
		 */
		public function get_directory_data( $path ) {
			$response = wp_remote_get(
				$path,
				array(
					'body' => array()
				)
			);
			return wp_remote_retrieve_body( $response );
		}

		/**
		 * Add image to user data for administrators.
		 *
		 * @param array $directory_data Array of administrators with base profile data.
		 * @return array Array of administrators with base profile data and image data.
		 */
		public function build_admin_data( $directory_data ) {
			$admin = $this->get_admin( $directory_data );
			$admin_image = [];
			foreach ( $admin as $user ) {
				$image_data = json_decode( $this->get_thumbnails( $user[ 'UserID' ] ) );
				$user[ 'ImageURL' ] = $image_data->ImageURL;
				array_push( $admin_image, $user );
			}

			return $admin_image;
		}

		/**
		 * Find administrators in array of all personnel.
		 *
		 * @param array $directory_data Array of all CON personnel.
		 * @return array Array of administrators with base profile data.
		 */
		public function get_admin( $directory_data ) {
			$all_users = $directory_data;
			$admin_users = [];
			foreach ( $all_users as $user ) {
				if ( strpos( strtolower( $user[ 'Classification' ] ), 'administration' ) !== false ) {
					array_push( $admin_users, $user );
				}
			}
			return $admin_users;
		}

		/**
		 * Fetch profile image data.
		 *
		 * @param string $pid PirateID of employee.
		 * @return string Image data of employee.
		 */
		public function get_thumbnails( $pid ) {
			$response = wp_remote_get(
				$this->config[ 'path' ] . '?userid=' . $pid . '&detail=1',
				array(
					'body' => array()
				)
			);
			return wp_remote_retrieve_body( $response );
		}

		/**
		 * Enqueue javascript files.
		 *
		 * @param string $script - Filename of javascript to enqueue.
		 * @return none
		 */
		public function enqueue_custom_scripts( $script ) {
			wp_enqueue_style( 'con-dir-style', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/con-dir.css' );
			wp_enqueue_script( 'vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.min.js', [], '2.5.16' );

			wp_enqueue_script( 'dir-js', trailingslashit( plugin_dir_url( __FILE__ ) ). $script, [ 'jquery' ], '0.1', true );
		}

		/**
		 * Make custom query variable available to WP_Query hook in the 'query_vars' filter.
		 *
		 * @param array $vars Public query variables available to WP_Query hook.
		 * @return array Query variable array that now contains our custom variables.
		 */
		function add_query_vars_filter( $vars ) {
			array_push( $vars, 'pid' );
			return $vars;
		}

		/**
		 * Replace those troublesome microsoft characters.
		 *
		 * @param string $content - String to be searched.
		 * @return string - Copy of source string with special characters removed.
		 */
		function replaceWordChars( $content ) {
			// Convert microsoft special characters
			$replace = array(
				"‘" => "'",
				"’" => "'",
				"”" => '"',
				"“" => '"',
				"–" => "-",
				"—" => "-",
				"…" => "&#8230;"
			);

			foreach ($replace as $k => $v) {
				$content = str_replace($k, $v, $content);
			}

			// Remove any non-ascii character
			$content = preg_replace('/[^\x20-\x7E]*/', '', $content);

			return $content;
		}

	}
	$my_directory = new Con_Directory();
	$my_directory->register();
}