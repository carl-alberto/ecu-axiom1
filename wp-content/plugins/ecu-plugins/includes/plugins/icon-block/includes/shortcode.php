<?php
	namespace Ecu_Plugins;

	// Setup the widget
	require_once(plugin_dir_path( __FILE__ ) . 'widget.php');

	/**
	 * Shortcode class for the featured block.
	 */
	class Icon_Block extends Abstract_Ecu_Shortcode {
		public function initialize(){

		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

		if ( is_admin() ) {
			add_editor_style( plugins_url('/ecu-plugins/includes/plugins/icon-block/css/style.css') );
		}

		parent::initialize();
	}

	/**
	 * Enqueueues the necessary CSS and JS
	 */
	public function enqueue_scripts() {
		wp_register_style( 'ecu-shortcode-icon-block', plugins_url('/ecu-plugins/includes/plugins/icon-block/css/style.css') );
	}
	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "ecu_icon_block";
		}

		/**
		 * Shortcode Function.
		 *
		 * @link https://codex.wordpress.org/Shortcode_API Shortcode UI
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
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
			wp_enqueue_style('ecu-shortcode-icon-block');
			wp_enqueue_style('ecu-fontello-fonts');
			//Get Values and Set any unset values.
			$attrs = shortcode_atts(array( //creates varaibales from your attrs
				'icon' => '',
				'title' => '',
				'text' => '',
				'color'    => '#000',
				'size' => '4em',
				'url'=>'',
				'page' => '',
				'btn-text' => 'Learn More'
			), $attrs, $shortcode_tag);


			$str = '<div class="ecu-icon-container ecu-icon" style="text-align: center;">';

			$str .= '<p style="font-size:' . esc_attr( $attrs['size'] ) . ';color:' . esc_attr( $attrs['color'] ) . ';" class="' . esc_attr( $attrs['icon'] ) . '""  aria-hidden="true"></p>';

			if ($attrs['title']) {
				$str .= '<p class="ecu-icon-block-title">' . esc_html( $attrs['title'] ) . '</p>';
			}

			if ($attrs['text']) {
				$str .= '<p>' . wp_kses_post($attrs['text']) . '</p>';
			}


			if(!empty($attrs['url']) || !empty($attrs['page'])){
				if (!empty($attrs['url'])) {
					$str .=	'<p><a href="' . esc_url($attrs['url']) . '" class="featuredblock-button btn-ribbon">' . esc_html($attrs['btn-text']) . '</a></p>';
				} else {
					if (!empty($attrs['page'])) {
						$page_url = esc_url(get_page_link($attrs['page']));
						$str .=	'<p><a href="' . $page_url . '" class="featuredblock-button btn btn-ribbon">' . esc_html($attrs['btn-text']) . '</a></p>';
					}
				}
			}

			$str .= '</div>';

			return do_shortcode($str);
		}

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	     */
		function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode")){

				// Get the list of pages on this site
				$pages = get_pages();
				$page_array[] = array('value'=>'', 'label'=>'Do not link to a page');
				foreach ($pages as $pageindex) {
					if ($pageindex->post_title != '') {
						$page_array[] = array('value' => $pageindex->ID, 'label' => $pageindex->post_title);
					}
				}

				shortcode_ui_register_for_shortcode(
				    $this->get_shortcode(),
				    array(
				        'label'         => 'Icon Block',
				        'listItemImage' => $this->get_font_awesome_html('fa-square'),
				        'attrs'         => array(
				        	array(
								'label'  => esc_html__( 'Icon Block', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => 'A block of content with an icon centered above the title, text, and button!'
							),
				        	array(
								'label'  => esc_html__( 'Select Icon', $this->get_shortcode() ),
								'attr'   => 'icon',
								'type'   => 'ecu-icon-select',
								'empty_icon' => 'true',
							),
							array(
								'label'  => esc_html__( 'Title', $this->get_shortcode() ),
								'attr'   => 'title',
								'type'   => 'text',
								'description' => 'The focus of the block.',
								'encode' => true,
							),
							array(
								'label'  => esc_html__( 'Text', $this->get_shortcode() ),
								'attr'   => 'text',
								'type'   => 'textarea',
								'description' => 'Consise statement about the focus of the block.  Some HTML, such as links, is allowed.',
								'encode' => true,
							),
							array(
								'label'  => esc_html__( 'Button Text', $this->get_shortcode() ),
								'attr'   => 'btn-text',
								'type'   => 'text',
								'description' => esc_html__( 'If you leave this blank, the button text will default to "Learn More".' ),
								'encode' => true,
							),
			        		array(
			        				'label'    => esc_html__( 'Select a page for this button to link to.', $this->get_shortcode() ),
			        				'attr'     => 'page',
			        				'description' => esc_html__( 'You can select one page.' ),
			        				'type'     => 'select',
			        				'options'  => $page_array,
			        				'multiple' => false,
			        		),
							array(
								'label'  => esc_html__( 'Button URL (Overrides the "Select a page" option above)', $this->get_shortcode() ),
								'attr'   => 'url',
								'description' => esc_html__( 'If you leave this blank, the button will be hidden.' ),
								'type'   => 'url',
								'encode' => true,
							),
							array(
								'label'  => esc_html__( 'Icon Color', $this->get_shortcode() ),
								'attr'   => 'color',
								'value' => '#000',
								'type'   => 'color',
							),
							array(
								'label'  => esc_html__( 'Icon Size', $this->get_shortcode() ),
		                        'attr'      => 'size',
		                        'type'      => 'select',
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
				        )
				    )
				);
			}
		}
	}
	new Icon_Block;
