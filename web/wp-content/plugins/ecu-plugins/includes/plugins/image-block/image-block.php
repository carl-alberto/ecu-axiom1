<?php
	namespace Ecu_Plugins;

	/**
	 * Shortcode class for the featured block.
	 *
	 * @since 1.0.0
	 */
	class Image_Block extends Abstract_Ecu_Shortcode {

	    /**
	     * Returns the shortcode
	     *
	     * @since 1.0.0
	     * @var string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "ecu_image_block";
		}

		/**
		 * Initialize
		 */
		public function initialize(){

			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

			if ( is_admin() ) {
				add_editor_style( plugins_url('/ecu-plugins/includes/plugins/image-block/css/style.css') );
			}

			parent::initialize();
		}

		/**
		 * Enqueueues the necessary CSS and JS
		 */
		public function enqueue_scripts() {
			wp_register_style( 'ecu-shortcode-image-block', plugins_url('/ecu-plugins/includes/plugins/image-block/css/style.css') );
		}
		/**
		 * Shortcode Function.
		 *
		 * @link https://codex.wordpress.org/Shortcode_API Shortcode UI
		 *
		 * @since 1.0.0
		 *
		 * @param array $atts  {
		 *      Optional. The settings for the shortcode instance.
		 *
		 *      @type string  $featuredblock-graphic Images to be used as section backgrounds
		 *      @type string  $featuredblock-header  Header for the block
		 *      @type string  $featuredblock-text    Text for the block
		 *      @type string  $featuredblock-btn-text  The label for the button if a url is provided.
		 *      @type string  $featuredblock-btn-url   The url for the button.  No url then no button will be shown.
		 *      @type string  $img-placement  The image can be placed to the left of the text, right of the text, or slide up
		 *  									over the text when cursor is hovering over the text.
		 *  	@type string  $featuredblock-btn-page The page to link to, instead of a custom URL
		 *  	@type string  $featuredblock-color The background color of the block
		 *  	@type string  $featuredblock-style The style to apply to the block
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
			wp_enqueue_style('ecu-shortcode-image-block');
			wp_enqueue_style('image-block-style', plugins_url('/ecu-plugins/includes/plugins/image-block/css/style.css'));

			//Get Values and Set any unset values.
			$str = '';
			$attrs = shortcode_atts(array( //creates varaibales from your attrs
				'featuredblock-graphic' => '',
				'featuredblock-header' => '',
				'featuredblock-text' => '',
				'featuredblock-btn-text' => 'Learn More',
				'featuredblock-btn-url' => '',
				'featuredblock-btn-page' => '',
				'featuredblock-color' => '',
				'featuredblock-style' => '1',
				'img-placement' =>'',

			), $attrs, $shortcode_tag);

			$divblockGraphicClass = 'featuredblock-graphic';
			$divfeaturedblockClass = 'featuredblock';
			$divblockGraphicClass .= ' ' . $attrs['img-placement'];

			//*******************
			//Start Building HTML
			//*******************
			$str .= '<div class="' . $divfeaturedblockClass . ' ' . $divfeaturedblockClass . '-style' . $attrs['featuredblock-style'] . '" ';
			if ($attrs['featuredblock-color'] != '') {
				$str .= 'style="background-color: ' . $attrs['featuredblock-color'] . '"';
			}
			$str .= '>';

						$str .= '<div class="' . $divblockGraphicClass . '">';
			$str .= wp_get_attachment_image($attrs['featuredblock-graphic'], 'medium_large', false);
			$str .= '<div class="arrowMore"><span class="fa fa-arrow-circle-down fa-3x" aria-hidden="true"></span></div>';
			$str .= '</div>';

			$str .= '<div class="featuredblock-content-container">';
			$str .= '<div class="featuredblock-content">';

			if ($attrs['featuredblock-header'] != ''){
				$str .= '<p class="featuredblock-header">' . $attrs['featuredblock-header'] . '</p>';
			}

			$str .= '<p class="featuredblock-text">' . $attrs['featuredblock-text'] . '</p>';

			if(!empty($attrs['featuredblock-btn-url']) || !empty($attrs['featuredblock-btn-page'])){
				if ($attrs['featuredblock-btn-url'] != ''){
					$str .=	'<a href="' . esc_url($attrs['featuredblock-btn-url']) . '" class="featuredblock-button btn btn-primary">' . $attrs['featuredblock-btn-text'] . '</a>';
				} else {
					if ($attrs['featuredblock-btn-page'] != ''){
						$page_url = esc_url(get_page_link($attrs['featuredblock-btn-page']));
						$str .=	'<a href="' . $page_url . '" class="featuredblock-button btn btn-primary">' . $attrs['featuredblock-btn-text'] . '</a>';
					}
				}
			}

			$str .=	'</div>';
			$str .= '</div>';
			$str .= '<div class="clearboth"></div>';

			$str .=	'</div>';
			return do_shortcode($str);

		}

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
		 *
		 * @since 1.0.0
	     */
		function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode")){

				// Get the list of pages on this site
				$pages = get_pages();
				$page_array[] = array('value'=>'-1', 'label'=>'Do not link to a page');
				foreach ($pages as $pageindex) {
					if ($pageindex->post_title != '') {
						$page_array[] = array('value' => $pageindex->ID, 'label' => $pageindex->post_title);
					}
				}

				shortcode_ui_register_for_shortcode(
				    $this->get_shortcode(),
				    array(
				        'label'         => 'Image Block',
				        'listItemImage' => $this->get_font_awesome_html('fa-square'),
				        'attrs'         => array(
				        	array(
				       			'label'  => esc_html__( 'Image Block', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => 'A block of content with an image to the left or right of the title, text, button!'
							),
				        	array(
								'label'  => esc_html__( 'Header', $this->get_shortcode() ),
								'attr'   => 'featuredblock-header',
								'type'   => 'text',
								'encode' => true,
							),
							array(
								'label'  => esc_html__( 'Text', $this->get_shortcode() ),
								'attr'   => 'featuredblock-text',
								'description' => esc_html__( 'It is recommended to keep your text short, especially if your image height is smaller than your image width. Suggested image size is 375 x 215 pixels', 'shortcode-ui-example' ),
								'type'   => 'textarea',
								'encode' => true,
							),
			        		array(
		        				'label'  => esc_html__( 'Background Color', $this->get_shortcode() ),
		        				'attr'   => 'featuredblock-color',
		        				'value' => '#000',
		        				'type'   => 'color',
			        		),
							array(
								'label'  => esc_html__( 'Button Text', $this->get_shortcode() ),
								'attr'   => 'featuredblock-btn-text',
								'type'   => 'text',
								'description' => esc_html__( 'If you leave this blank, the button text will default to "Learn More".' ),
								'encode' => true,
							),
			        		array(
		        				'label'    => esc_html__( 'Select a page for this button to link to.', $this->get_shortcode() ),
		        				'attr'     => 'featuredblock-btn-page',
		        				'description' => esc_html__( 'You can select one page.' ),
		        				'type'     => 'select',
		        				'options'  => $page_array,
		        				'multiple' => false,
			        		),
				        	array(
								'label'  => esc_html__( 'Button URL (Overrides the "Select a page" option above)', $this->get_shortcode() ),
								'attr'   => 'featuredblock-btn-url',
								'description' => esc_html__( 'If you leave this blank, the button will be hidden.' ),
								'type'   => 'url',
								'encode' => true,
							),
							array(
								'label'       => esc_html__( 'Image placement', $this->get_shortcode() ),
								'description' => esc_html__( 'Choose your placement of image in relation to the text.', $this->get_shortcode() ),
								'attr'        => 'img-placement',
								'type'        => 'select',
								'options'     => array(
									array( 'value' => 'img-left', 'label' => esc_html__( 'Image left of text', $this->get_shortcode() ) ),
									array( 'value' => 'img-right', 'label' => esc_html__( 'Image right of text', $this->get_shortcode() ) ),
								),
							),
							array(
								'label'       => 'Select Images to be used as section backgrounds.',
								'attr'        => 'featuredblock-graphic',
								'description' => esc_html__( 'Select an image.' ),
								'type'        => 'attachment',
								'libraryType' => array('image'),
								'multiple'    => false,
								'addButton'   => 'Select Images',
								'frameTitle'  => 'Select Images',
							),
			        		array(
		        				'label'  => esc_html__( 'Style', $this->get_shortcode() ),
		        				'attr'      => 'featuredblock-style',
		        				'type'      => 'select',
		        				'options'   => array(
		        						array(
		        								'value' => '1',
		        								'label' => 'Style 1',
		        						),
		        						array(
		        								'value' => '2',
		        								'label' => 'Style 2',
		        						),
		        				),
			        		),
				        )
				    )
				);
			}
		}
	}

	new Image_Block;
