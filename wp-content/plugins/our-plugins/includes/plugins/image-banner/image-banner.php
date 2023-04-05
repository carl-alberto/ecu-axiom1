<?php
	namespace OUR_PLUGINS;

	/**
	 * Shortcode class for the image banner.  Optionally uses a parallax library to
	 * add a parallax effect to the image.
	 *
	 * @link http://pixelcog.github.io/parallax.js/ Parallax JS Documentation
	 */
	class Image_Banner extends Abstract_Ecu_Shortcode {

	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "ecu_image_banner";
		}
		/**
		 * Initialize
		 */
		public function initialize(){

			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

			if ( is_admin() ) {
				add_editor_style( plugins_url('/our-plugins/includes/plugins/image-banner/css/style.css') );
			}

			parent::initialize();
		}

		/**
		 * Enqueueues the necessary CSS and JS
		 */
		public function enqueue_scripts() {
			wp_register_style( 'ecu-shortcode-image-banner', plugins_url('/our-plugins/includes/plugins/image-banner/css/style.css') );
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
				        'label'         => 'Image Banner',
				        'listItemImage' => 'dashicons-align-center',
				        'attrs'         => array(
				        	array(
								'label'  => esc_html__( 'Image Banner', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => 'Select an image and add some text to create 100% width banner to highlight important information or section.  The height of the banner is set to the image height.'
							),
				        	array(
								'label'       => esc_html__( 'Select Image', $this->get_shortcode() ),
								'attr'        => 'image',
								'type'        => 'attachment',
								'libraryType' => array('image'),
								'addButton'   => 'Select Image',
								'frameTitle'  => 'Select Image',
							),
							array(
								'label'  => esc_html__( 'Text', $this->get_shortcode() ),
								'attr'   => 'textoverlay',
								'description' => esc_html__( 'Optional.  This text will be centered on top of the image. No HTML!' ),
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
								'attr'   => 'color',
								'description' => esc_html__( 'Optional.  Defaults to white.' ),
								'value' => '#fff',
								'type'   => 'color',
							),
							array(
								'label'  => esc_html__( 'Disable text shadow', $this->get_shortcode() ),
								'attr'   => 'disable-shadow',
								'description' => esc_html__( 'Optional.  Disable the text shadow on text.' ),
								'type'   => 'checkbox',
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
		 *      @type string  $image The image from the media library.
		 *      @type string  $textoverlay  Optional text that will be overlaid the image.
		 *      @type string  $size  Optional.  Defaults to 4em.  Must be a valid css font size.
		 *      @type string  $color  Optional.  Defaults to white.
		 *      @type string  $height  Optional.  If not set and there is no text, then it will
		 *                            be set to 25% the height of the image.  Must be a valid css height.
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
			wp_enqueue_style('ecu-shortcode-image-banner');
			//Get Values and Set any unset values.
			$attrs = shortcode_atts(array(
				'image' => '',
				'textoverlay' => '',
				'height'=>'',
				'color'=>'#fff',
				'size'=>'4em',
				'disable-shadow' => false
			),array_map('rawurldecode',$attrs), $shortcode_tag);

			if(empty($attrs['image'])) {
				if(is_admin()) {
					return 'Error loading the image';
				} else {
					return;
				}
			}

			if(empty($attrs['height'])) {
				$data = wp_get_attachment_image_src($attrs['image'], 'full');

				if( is_array($data)) {
					$attrs['height'] = $data[2] . 'px';
				}
			}

			$output .= '<div class="ecu-image-banner-container">';
			$output .= '<div class="ecu-image-banner">';
			$output .= '<img src="' . wp_get_attachment_url(  $attrs['image'] ) . '" style="width:100%;height:' . esc_attr($attrs['height']) .';">';
			$output .= '<span class="ecu-image-banner-text';
			if($attrs['disable-shadow']) {
				$output .= ' disable-shadow';
			}

			$output .= '" style="width: 100%;line-height:1em;color:' . esc_attr($attrs['color']) .';font-size:' . esc_attr($attrs['size']) .';">';
			$output .= esc_html($attrs['textoverlay']) .'</span>';
			$output .= '</div>';
			$output .= '</div>';

			return $output;
		}
	}

	new Image_Banner;
