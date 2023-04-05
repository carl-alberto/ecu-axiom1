<?php
	namespace Ecu_Plugins;

	/**
	 * Shortcode class for the dashboard UI element.
	 */
	class Ninja_Forms_Shortcake extends Abstract_Ecu_Shortcode {

	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "ninja_forms";
		}

		/**
		 * Initialize
		 */
		public function initialize(){
			if ( is_admin() ) {
				add_editor_style( plugins_url('/ecu-plugins/includes/plugins/ninja-forms/css/style.css') );
			}

			parent::initialize();
		}

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	     */
		public function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode") && function_exists("get_field")){
				global $wpdb;
				$nf_table = $wpdb->prefix . 'nf3_forms';
				$forms = $wpdb->get_results( "SELECT id, title FROM `{$nf_table}`", OBJECT );
				$options = array();
				foreach($forms as $form){
					$options[] = array('value' => $form->id, 'label' => $form->title);
				}
				shortcode_ui_register_for_shortcode(
					$this->get_shortcode(), array(
						'label'         => esc_html__( 'Ninja Form', $this->get_shortcode() ),
						'listItemImage' => $this->get_font_awesome_html('fa-clipboard'),
						'attrs'         => array(
							array(
								'label'  => esc_html__( 'Ninja Form', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => "Inserts the selected form in the desired location."
							),
							array(
								'label'  => esc_html__( 'Form', $this->get_shortcode() ),
		            'attr'      => 'id',
		            'type'      => 'select',
		            'options'   => $options
		          ),
						)
					)
				);
			}
		}

		/**
		 * Shortcode Function.
		 *
		 * @link https://codex.wordpress.org/Shortcode_API Shortcode UI
		 *
		 * @param array $atts  {
		 *      Optional. The settings for the shortcode instance.
		 *
		 *      @type string  $dashboard_id The dashboard id to use for the dashboard.
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
			//Get Values and Set any unset values.
			$attrs = shortcode_atts(array(
				'id' => '',
				'hack' => 'null'
			), $attrs, $shortcode_tag);
			$id = esc_attr($attrs['id']);
			return '[ninja-forms-placeholder]';
		}
	}

	new Ninja_Forms_Shortcake;
