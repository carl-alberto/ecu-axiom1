<?php
/*
Plugin Name: Our CON Admission Inquiry
Description: Form to gather admissions inquiry data.
Author: Chuck Baldwin
Version: 1.0
License: GPL2
*/

if ( ! class_exists( 'Admission_Inquiry' ) ) {
	/**
	 * Collect admission inquiry data and forward to CON backend servers.
	 *
	 */
	class Admission_Inquiry
	{
		/**
		 * Configuration variables for plugin.
		 * @var string [ 'shortcode' ] - Name of the shortcode.
		 * @var string [ 'vuelidator' ] - Vue validation script.
		 * @var string [ 'validators' ] - Validation config for Vuelidator.
		 * @var string [ 'app-js' ] - Main javascript for plugin front-end.
		 * @var string [ 'vue' ] - Hosted version of Vue.js.
		 * @var string [ 'style' ] - CSS to enqueue.
		 * @var string [ 'adapter' ] - Adapter on production server. Sends email to recruiters.
		 * @var string [ 'testAdapter' ] - Adapter for testing purposes. Does not send email. Writes to test db.
		 * @var string [ 'sanitizer' ] - Javascript to sanitize user input.
		 * @var array [ 'debug' ] - Debugging messages to pass to javascript.
		 */
		private $config = [
			'shortcode' => 'admission',
			'vuelidator' => 'js/vuelidate.min.js',
			'validators' => 'js/validators.min.js',
			'app-js' => 'js/app.js',
			'vue' => 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.min.js',
			'style' => 'css/admission.css',
			'adapter' => 'https://develop.nursing.ecu.edu/RecruitmentWeb/Adapters/SaveRecruitmentWebAdapter.aspx',
			'testAdapter' => 'https://develop.nursing.ecu.edu/RecruitmentWeb%20-%20Test/Adapters/SaveRecruitmentWebAdapter.aspx',
			'sanitizer' => 'js/sanitize-html.min.js',
			'debug' => [],
		];

		/**
		 * Register hooks and query string filter.
		 *
		 * @return none
		 */
		public function register() {
			add_shortcode( $this->config[ 'shortcode' ], [ $this, 'init' ] );
			add_action( 'wp_ajax_nopriv_form_submit', array( $this, 'ajax_handler' ), 10, 0 );
			add_action( 'wp_ajax_form_submit', array( $this, 'ajax_handler' ), 10, 0 );
		}

		/**
		 * Receive ajax from form submissions, relay to remote adapter, send results back to javascript.
		 *
		 * @return none
		 */
		public function ajax_handler() {
			// Confirm nonce.
			check_ajax_referer( 'admission_form' );
			// Send results to javascript.
			print_r( $this->post_form_data( $_POST[ 'serialStudent' ], $_POST[ 'mode' ] ) );

			wp_die();
		}

		/**
		 * Format and post form data to remote adapter.
		 *
		 * @param string $serialStudent - base64 encoded form data.
		 * @param string $mode - Mode, as passed from app.js with form data.
		 * @return string - Success or error message from remote adapter.
		 */
		public function post_form_data( $serialStudent, $mode ) {
			try {
				$adapter = ( $mode === 'test' ? $this->config[ 'testAdapter' ] : $this->config[ 'adapter' ] );
				$response = wp_remote_post( $adapter, array(
					'method' => 'POST',
					'headers' => array(
						'serialStudent' => $serialStudent
					)
				) );

				if ( is_wp_error( $response ) ) {
					return $response->get_error_message();
				} else {
					return $response[ 'body' ];
				}
			} catch( Exception $error ) {
				return 'Error posting form data. ' . $error->getMessage();
			}
		}

		/**
		 * Callback to register the admission shortcode.
		 * Include form html, enqueue javascript/css, and pass ajax params to javascript.
		 *
		 * @param array $atts {
		 * 	Optional array of shortcode arguments.
		 *
		 * 	@type string $mode - Default value is 'test', which directs form submissions to test server.
		 * 						 Any other explicit value submits to production server.
		 * 						 Pass this to app.js so that it can be used in post_form_data.
		 * }
		 * @return none
		 */
		public function init( $atts ) {
			$atts = array_change_key_case( ( array ) $atts, CASE_LOWER );
			$atts = shortcode_atts(
				array(
					'mode' => 'test',
				), $atts, 'admission'
			);

			array_push( $this->config[ 'debug' ], $atts[ 'mode' ] );

			include( 'form-template.html' );
			$this->enqueue_custom_scripts();
			// Pass array of vars to app.js
			wp_localize_script( 'app', 'ajax_nfo',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'admission_form' ),
				'mode' => $atts[ 'mode' ],
				'debug' => $this->config[ 'debug' ],
			) );
		}

		/**
		 * Enqueue javascript and css files.
		 *
		 * @param string $script - Filename of javascript to enqueue.
		 * @return none
		 */
		public function enqueue_custom_scripts() {

			wp_enqueue_style( 'admission-style', trailingslashit( plugin_dir_url( __FILE__ ) ) . $this->config[ 'style' ] );

			wp_enqueue_script( 'vue', $this->config[ 'vue' ], [], '2.5.16' );

			wp_enqueue_script( 'vuelidate', trailingslashit( plugin_dir_url( __FILE__ ) ). $this->config[ 'vuelidator' ], ['vue'], null );

			wp_enqueue_script( 'validators', trailingslashit( plugin_dir_url( __FILE__ ) ). $this->config[ 'validators' ], ['vue'], null );

			wp_enqueue_script( 'sanitizer', trailingslashit( plugin_dir_url( __FILE__ ) ). $this->config[ 'sanitizer' ], ['vue', 'jquery' ], null );

			wp_enqueue_script( 'app', trailingslashit( plugin_dir_url( __FILE__ ) ). $this->config[ 'app-js' ], ['vue', 'jquery', 'sanitizer' ], null );

		}
	}
	$con_admissions = new Admission_Inquiry();
	$con_admissions->register();
}