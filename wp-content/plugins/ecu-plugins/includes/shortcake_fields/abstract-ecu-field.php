<?php
	namespace Ecu_Plugins;

	/**
	 * Class that defines the ecu shortcode field and provides basic functions to register it.
	 * This class should not be used directly.  Extend your field classes from this parent.
	 * Be sure that you init your field by calling get instance at the end of the file.
	 *
	 * Your_Field_Class::get_instance();
	 */
	abstract class Abstract_Ecu_Field extends Ecu_Database {

		/**
		 * Return the instance.
		 *
		 * Since an abstract class cannot initialize itself you will have to add the following
		 * function to your class and and the protected static $instance; property..
		 *
		 * 	function initialize(){
		 *	 	add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
		 *
		 *			if ( is_admin() ) {
		 *	  		add_action( 'enqueue_shortcode_ui', array( $this, 'enqueue_shortcode_ui' ) );
		 *	    		add_editor_style( plugins_url( 'ecu-plugins/includes/shortcake_fields/icon-select/fontello/css/fontello.css' ) );
	  	 *	      	}
		 *
		 *			parent::initialize();
         * 	}
		 *
		 * @return Shortcode_UI_Field_Term_Select
		 */
		abstract public static function get_instance();

		/**
		 * Add the field to the shortcode fields.
		 *
		 * @param $fields
		 * @return array
		 */
		abstract public function filter_shortcode_ui_fields($fields);

		/**
		 * Output template used by post select field.
		 *
		 *  Example:
		 *
		 * <script type="text/html" id="tmpl-shortcode-ui-field-term-select">
	 	 *	<div class="field-block shortcode-ui-field-term-select shortcode-ui-attribute-{{ data.attr }}">
	 	 *		<label for="{{ data.id }}">{{{ data.label }}}</label>
		 *		<select name="{{ data.attr }}" id="{{ data.id }}" class="shortcode-ui-term-select"></select>
		 *		<# if ( typeof data.description == 'string' && data.description.length ) { #>
		 *			<p class="description">{{{ data.description }}}</p>
		 * 		<# } #>
		 *	</div>
		 * </script>
	     */
		abstract public function shortcode_ui_loaded_editor();

		/**
		 * Setup the field.
		 *
		 * You may have need to add styles/js or make use of ajax in your field.
		 *
		 * Use the enqueue_shortcode_ui hook to enqueue styles/js for field.  See comment bloc below for example function.
		 * add_action( 'enqueue_shortcode_ui', array( $this, 'enqueue_shortcode_ui' ) );
		 *
		 * Example enqueue function.
		 *
		 * public function enqueue_shortcode_ui() {
		 *
		 *	 wp_enqueue_script( Shortcode_UI::$select2_handle );
		 *	 wp_enqueue_style( Shortcode_UI::$select2_handle );
 		 *
		 *	 wp_localize_script( 'shortcode-ui', 'shortcodeUiTermFieldData', array(
		 *	 	'nonce' => wp_create_nonce( 'shortcode_ui_field_term_select' ),
		 *	 ) );
		 * }
		 *
		 * Use the wp_ajax_shortcode_ui_term_field hook for ajax response.  See comment bloc below for example function.
		 * add_action( 'wp_ajax_shortcode_ui_term_field', array( $this, 'wp_ajax_shortcode_ui_term_field' ) );
		 *
		 *  Example AJAX function
		 *
		 * 	public function wp_ajax_shortcode_ui_term_field() {
		 *
		 *		$nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( $_GET['nonce'] ) : null;
		 *
		 *		$response = array(
		 *			'items'          => array(),
		 *			'found_items'    => 0,
		 *			'items_per_page' => 10,
		 *			'page'           => $page,
		 *		);
		 *
		 *		if ( ! wp_verify_nonce( $nonce, 'shortcode_ui_field_term_select' ) ) {
		 *			wp_send_json_error( $response );
		 *		}
 		 *
		 *		// Do what you need here to response.
		 *
		 *		// Return response
		 *		wp_send_json_success( $response );
		 *	}
		 *
		 * If you need to make use of these actions the override the initialize function, but be sure
		 * to call the parent.
		 */
		protected function initialize() {
			add_filter( 'shortcode_ui_fields',             array( $this, 'filter_shortcode_ui_fields' ) );
			add_action( 'shortcode_ui_loaded_editor',      array( $this, 'shortcode_ui_loaded_editor' ) );
		}
	}
