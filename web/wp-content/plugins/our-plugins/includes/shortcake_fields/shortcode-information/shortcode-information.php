<?php
	namespace Ecu_Plugins;

	/**
	 * Icon Font Picker.   Allows for categories and searching.
	 */
	class Ecu_Shortcode_Information extends Abstract_Ecu_Field  {

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
			$fields['ecu-shortcode-information'] = array(
				'template' => 'ecu-shortcode-ui-field-shortcode-information',
			);
			return $fields;
		}

		/**
		 * Output template used by post select field.
	     */
		public function shortcode_ui_loaded_editor() {
			//@formatter:off
			?>
			<script type="text/html" id="tmpl-ecu-shortcode-ui-field-shortcode-information">
				<div class="field-block shortcode-ui-field-text shortcode-ui-attribute-{{ data.attr }}">
					<h2 for="{{ data.id }}">{{{ data.label }}}</h2>
					<# if ( typeof data.description == 'string' && data.description.length ) { #>
						<p class="description">{{{ data.description }}}</p>
					<# } #>
				</div>
			</script>
			<?php
			//@formatter:on
		}

	}

	Ecu_Shortcode_Information::get_instance();
