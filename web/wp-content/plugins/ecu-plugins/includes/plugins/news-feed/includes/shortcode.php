<?php
	namespace Ecu_Plugins;

	/**
	 * Shortcode class for Acalog.
	 */
	class News_Feed extends Abstract_Ecu_Shortcode {

		/**
		 * Initialize
		 */
		public function initialize(){

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
		public function admin_enqueue_scripts() {
			add_editor_style( plugins_url('/ecu-plugins/includes/plugins/news-feed/css/style.css') );
		}

		/**
	     * Enqueueues the necessary CSS and JS
		 */
		public function wp_enqueue_scripts() {
			wp_register_style( 'ecu-shortcode-news-feed', plugins_url('/ecu-plugins/includes/plugins/news-feed/css/style.css') );
		}

		/**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "ecu_news_feed";
		}

		private function get_feed_options() {
			// $mydb = $this->get_homepage_db();

			// $results = $mydb->get_results( "SELECT * FROM homepage_tools.news_feeds WHERE show_commonspot = 1");

			$results = \Database\Homepage::query("
				SELECT *
				FROM homepage_tools.news_feeds
				WHERE show_commonspot = 1
			");

			$options = array();
			if(is_array($results)) {
				foreach($results as $feed) {
					$options[] = array(
			            'value' => (string)$feed->id,
			            'label' => $feed->name,
			        );
				}
			}

			return $options;
		}

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	     */
		public function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode")){

				$options = is_admin() ? $this->get_feed_options() : array();

				shortcode_ui_register_for_shortcode(
				    $this->get_shortcode(),
				    array(
				        'label'         => 'News Feed',
				        'listItemImage' => $this->get_font_awesome_html('fa-rss'),
				        'attrs'         => array(
							array(
								'label'  => esc_html__( 'News Feeds', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => 'Display news feeds in your site!  Requires access to the News Feeds and ECU Stories tools.   To request access <a href="https://ecu.teamdynamix.com/TDClient/Requests/ServiceDet?ID=12142" target="_blank">submit a ticket</a>.'
							),
							array(
								'label'  => esc_html__( 'Select a News Feed', $this->get_shortcode() ),
		                        'attr'      => 'feed_id',
		                        'type'      => 'select',
		                        'options'   => $options
		                    ),
		            		array(
								'label'  => esc_html__( 'Title', $this->get_shortcode() ),
								'attr'   => 'title',
								'type'   => 'text',
								'description' => 'Optional. The focus of the News Feed.  Will be a h2 header.',
								'encode' => true,
							),
							array(
								'label'  => esc_html__( 'Display', $this->get_shortcode() ),
		                        'attr'      => 'list_display',
		                        'type'      => 'select',
		                        'options'   => array(
		                        	array(
		                        		'value' => 'vertical_list',
		                        		'label' => 'Vertical List'
		                        	),
		                        	array(
		                        		'value' => 'horizontal_list',
		                        		'label' => 'Horizontal List'
		                        	),
		                        ),
		                    ),
							array(
								'label'  => esc_html__( 'Center the List in the Container', $this->get_shortcode() ),
		                        'attr'      => 'center_list',
		                        'type'      => 'select',
		                        'value' => '1',
								'options' => $this->get_yes_no_options(),
		                    ),
		                    array(
								'label'  => esc_html__( 'Center the Text in the News Sections', $this->get_shortcode() ),
		                        'attr'      => 'center_text',
		                        'type'      => 'select',
		                        'value' => '0',
		                        'options' => $this->get_yes_no_options(),
		                    ),
		                    array(
								'label'  => esc_html__( 'Show the Image or Video', $this->get_shortcode() ),
		                        'attr'      => 'show_image_video',
		                        'type'      => 'select',
		                        'value' => '1',
		                        'options' => $this->get_yes_no_options(),
		                    ),

		                    /**array(
								'label'  => esc_html__( 'Image Location', $this->get_shortcode() ),
		                        'attr'      => 'image_location',
		                        'type'      => 'select',
		                        'options'   => array(
		                        	array(
		                        		'value' => 'default',
		                        		'label' => 'Default'
		                        	),
		                        	array(
		                        		'value' => 'top',
		                        		'label' => 'Top'
		                        	),
		                        	array(
		                        		'value' => 'right',
		                        		'label' => 'Right'
		                        	),
		                        	array(
		                        		'value' => 'left',
		                        		'label' => 'Left'
		                        	),
		                        ),
		                    ),*/
		                    array(
								'label'  => esc_html__( 'Number of Stories to Show', $this->get_shortcode() ),
		                        'attr'      => 'show',
		                        'type'      => 'number',
		                        'value' => '3',
                    			'meta'   => array(
									'step' => '1',
									'min' => '1',
									'max' => '12',
								),
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
		 * @link https://ecu.acalogadmin.com/login.php Acalog API information
		 *
		 * @param array $atts  {
		 *      Optional. The settings for the shortcode instance.
		 *
		 *      @type string  $cip_code  Text that will be centered in the banner.
		 *      @type string  $size  Optional.  Defaults to small.  The icon size to display.
		 *      @type string  $display  Optional.  The direction to list the icons.  Defaults to horizontal.
		 *      @type string  $center  Optional. Centers the text in the container if true.  Defaults to false.
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
			wp_enqueue_style('ecu-shortcode-news-feed');
			//Get Values and Set any unset values.
			$attrs = shortcode_atts(array(
				'feed_id' => '',
				'list_display'=>'horizontal_list',
				'show'=>3,
				'title'=>'',
				'center_text' => false,
				'center_list' => false,
				'show_image_video' => false,
				'image_location' => 'default',
			), $attrs, $shortcode_tag);

			if(empty($attrs['feed_id'])) {
				if(is_admin()) {
					return 'Error loading feed.';
				} else {
					return;
				}
			}

			$feed = new Feed();

			$feed->init($attrs);

			return $feed->run();
		}
	}

	new News_Feed;
