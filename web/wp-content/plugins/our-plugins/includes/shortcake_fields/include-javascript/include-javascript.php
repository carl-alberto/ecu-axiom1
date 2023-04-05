<?php
	namespace Ecu_Plugins;

	/**
	 * Icon Font Picker.   Allows for categories and searching.
	 */
	class Ecu_Include_Javascript extends Abstract_Ecu_Field  {

	    /**
	     * Returns the instance
	     *
	     * @var string $instance The single instance for the field.
	     */
		protected static $instance;

		/**
		 * Return the instance.
		 *
		 * @return Ecu_Shortcode_Information
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
				self::$instance->initialize();
			}
			return self::$instance;
		}

		/**
		 * Add the field to the shortcode fields.
		 *
		 * @param $fields
		 * @return array
		 */
		public function filter_shortcode_ui_fields( $fields ) {
			$fields['ecu-include-javascript'] = array(
				'template' => 'ecu-shortcode-ui-field-include-javascript',
			);
			return $fields;
		}

		/**
		 * Output template used by post select field.
	     */
		public function shortcode_ui_loaded_editor() {
			//@formatter:off
			?>
			<script type="text/html" id="tmpl-ecu-shortcode-ui-field-include-javascript">
				<script type="text/javascript" src="{{{ data.url }}}"> </script>
			</script>
			<?php
			//@formatter:on
		}

	}

	Ecu_Include_Javascript::get_instance();
