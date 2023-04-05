<?php
	namespace Ecu_Plugins;

	/**
	 */
	class Ecu_Connect extends Abstract_Ecu_Shortcode {

		/**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "ecu_connect";
		}

		/**
		 * Initialize
		 */
		public function initialize(){

			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

			if ( is_admin() ) {
				add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
			} else {
				add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
			}

			parent::initialize();
		}

		/**
		 * Enqueueues the necessary CSS and JS
		 */
		public function enqueue_scripts() {
			wp_register_style( 'ecu-shortcode-connect', plugins_url('/ecu-plugins/includes/plugins/connect/css/style.css') );
		}

		/**
	     * Enqueueues the necessary CSS and JS
		 */
		public function wp_enqueue_scripts() {
			wp_register_script('ecu-connect-scripts', plugins_url('ecu-plugins/includes/plugins/connect/js/script.js'));
		}

		/**
	     * Enqueueues the necessary CSS and JS
		 */
		public function admin_enqueue_scripts() {
			wp_enqueue_script('ecu-connect-scripts', plugins_url('ecu-plugins/includes/plugins/connect/js/script.js'));
		}

		public function callback($attrs, $content = '', $shortcode_tag){
			wp_enqueue_style('ecu-shortcode-connect');
			wp_enqueue_script('ecu-connect-scripts');
  			return render_connect();
		}

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	     */
		function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode")){

				shortcode_ui_register_for_shortcode(
				    $this->get_shortcode(),
				    array(
				        'label'         => 'ECU Connect',
				        'listItemImage' => $this->get_font_awesome_html('fa-plug'),
				        'attrs'         => array(
				        	array(
								'label'  => esc_html__( 'ECU Connect', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => 'Shows a searchable and paginated table of all registered social media accounts with ECU.'
							),
				        )
				    )
				);
			}
		}
	}

	new Ecu_Connect;
