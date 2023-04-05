<?php
	namespace Ecu_Plugins;

	/**
	 * Shortcode class for the banner.
	 */
	class Text_Banner extends Abstract_Ecu_Shortcode {

	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "ecu_text_banner";
		}
		/**
		 * Initialize
		 */
		public function initialize(){

			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

			if ( is_admin() ) {
				add_editor_style( plugins_url('/ecu-plugins/includes/plugins/text-banner/css/style.css') );
			}

			parent::initialize();
		}

		/**
		 * Enqueueues the necessary CSS and JS
		 */
		public function enqueue_scripts() {
			wp_register_style( 'ecu-shortcode-text-banner', plugins_url('/ecu-plugins/includes/plugins/text-banner/css/style.css') );
		}

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	     */
		public function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode")){
				shortcode_ui_register_for_shortcode(
				    $this->get_shortcode(),
				    array(
				        'label'         => 'Text Banner',
				        'listItemImage' => 'dashicons-align-center',
				        'attrs'         => array(
				        	array(
								'label'  => esc_html__( 'Text Banner', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => 'Select a background color with some text to create 100% banner to highlight important information or section.'
							),
							array(
								'label'  => esc_html__( 'Text', $this->get_shortcode() ),
								'attr'   => 'textoverlay',
								'description' => esc_html__( 'This text will be centered in the banner.  No HTML allowed.  Be sure to set the height of the banner to encompass the text.' ),
								'type'   => 'textarea',
							),
							array(
								'label'  => esc_html__( 'Font Size', $this->get_shortcode() ),
		                        'attr'      => 'size',
		                        'type'      => 'select',
		                        'value'		=> '4em',
		                        'options'   => array(
		                           	array(
		                        		'value' => '4em',
		                        		'label' => 'X-Large',
		                        	),
		                        	array(
		                        		'value' => '3em',
		                        		'label' => 'Large',
		                        	),
		                        	array(
		                        		'value' => '2em',
		                        		'label' => 'Medium',
		                        	),
		                        	array(
		                        		'value' => '1em',
		                        		'label' => 'Small',
		                        	),
		                        ),
		                    ),
		                    array(
								'label'  => esc_html__( 'Font Color', $this->get_shortcode() ),
								'attr'   => 'fcolor',
								'description' => esc_html__( 'Optional.  Defaults to white.' ),
								'value' => '#fff',
								'type'   => 'color',
							),
							array(
								'label'  => esc_html__( 'Background Color', $this->get_shortcode() ),
								'attr'   => 'bcolor',
								'description' => esc_html__( 'Optional.  Defaults to ECU purple.' ),
								'value' => '#592a8a',
								'type'   => 'color',
							),
							array(
								'label'  => esc_html__( 'Padding Around Text', $this->get_shortcode() ),
								'attr'   => 'padding',
								'description' => esc_html__( 'Optional.  Defaults to 40px.  Must be a valid css height: px, %, em, etc.' ),
								'type'   => 'text',
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
		 *      @type string  $textoverlay  Text that will be centered in the banner.
		 *      @type string  $size  Optional.  Defaults to 4em.  Must be a valid css font size.
		 *      @type string  $fcolor  Optional.  Font Color. Defaults to white.
		 *      @type string  $bcolor  Optional.  Background Color. Defaults to ECU purple.
		 *      @type string  $height  Optional.  If not set and there is no text, then it will
		 *                            be set to 25% the height of the image.  Must be a valid css height.
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
			wp_enqueue_style('ecu-shortcode-text-banner');
			//Get Values and Set any unset values.
			$attrs = shortcode_atts(array(
				'textoverlay' => '',
				'fcolor'=>'#fff',
				'bcolor'=>'#592a8a',
				'size'=>'4em',
				'padding'=> '40px',
			), $attrs, $shortcode_tag);
			if(empty($attrs['textoverlay'])) {
				if(is_admin()) {
					return 'Text is required or the banner will not be displayed.';
				} else {
					return;
				}
			}

			$output =  '<div class="ecu-text-banner" style="background-color:' . esc_attr($attrs['bcolor']) .';">';
			$output .= '<div class="ecu-text-banner-text" style="color:' . esc_attr($attrs['fcolor']) .';font-size:' . esc_attr($attrs['size']) .'; line-height:1em;padding:' . esc_attr($attrs['padding']) .';">' . esc_html($attrs['textoverlay']) .'</div>';
			$output .= '</div>';

			return $output;
		}
	}

	new Text_Banner;
