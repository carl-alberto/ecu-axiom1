<?php
	namespace Ecu_Plugins;

	/**
	 * Shortcode class for the banner.
	 */
	class Social_Media extends Abstract_Ecu_Shortcode {

		/**
		 * These social media icons are maintained via the Social Media tool in tools.ecu.edu.
		 * @link https://tools.ecu.edu/socialMediaSites Social Media Sites Tool
		 */
		const IMAGE_URL = 'https://cdn.ecu.edu/images/connect/';

	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "ecu_social_media";
		}

		/**
		 * Returns the organizations for ecu social media in the array options format for the shortcode ui select field.
		 *
		 * array(
		 * 	array(
		 * 		'label' => 'foo',
		 * 	 'value' => 'bar'
		 * 	 ),
		 * 	 ....
		 * )
		 *
		 * @author Ryan Cowan <cowanr@ecu.edu>
		 * @return  array The options for the select field.
		 */
		private function get_organizations_options() {

			// $mydb = $this->get_homepage_db();

			// $results = $mydb->get_results( 'SELECT id, title FROM homepage_tools.homepage_connect_organizations' );

			$results = \Database\Homepage::query("
				SELECT id, title
				FROM homepage_tools.homepage_connect_organizations
			");

			$options = array();
			if($results){
				foreach($results as $org) {
					$options[] = array(
			            'value' => (string)$org->id,
			            'label' => $org->title,
			        );
				}
			}

			return $options;
		}

		/**
		 * Returns an array with the organizations social media accounts.
		 *
		 * array(
		 * 	std_class [
		 * 		'url' => 'The url to the social media',
		 * 	 	'title' => 'The title of the organization',
		 * 	 	'name' => 'The name of the social media',
		 * 	 	'logo_32' => 'The file name for the 32x32 px image',
		 * 	 	'logo_40' => 'The file name for the 40x40 px image',
		 * 	 	'logo_60' => 'The file name for the 60x60 px image',
		 * 	 ],
		 * 	 ....
		 * )
		 * @author Ryan Cowan <cowanr@ecu.edu>
		 * @param  int $id The id of the organization
		 * @return array  An array of objects containing the social media information.
		 */
		private function get_organizations_social_media($id) {

			// $mydb = $this->get_homepage_db();

			// $results = $mydb->get_results( "SELECT homepage_connect_organizations_sites.url, homepage_connect_organizations.title, social_media_sites.name, social_media_sites.logo_32, social_media_sites.logo_40, social_media_sites.logo_60 FROM homepage_tools.homepage_connect_organizations_sites RIGHT OUTER JOIN social_media_sites ON homepage_connect_organizations_sites.site_id = social_media_sites.id RIGHT OUTER JOIN homepage_connect_organizations ON homepage_connect_organizations_sites.org_id = homepage_connect_organizations.id WHERE homepage_connect_organizations.id = '" . absint($id) . "' ORDER BY sort_order;");

			$results = \Database\Homepage::query("
				SELECT homepage_connect_organizations_sites.url, homepage_connect_organizations.title, social_media_sites.name, social_media_sites.logo_32, social_media_sites.logo_40, social_media_sites.logo_60
				FROM homepage_tools.homepage_connect_organizations_sites
				RIGHT OUTER JOIN social_media_sites
					ON homepage_connect_organizations_sites.site_id = social_media_sites.id
				RIGHT OUTER JOIN homepage_connect_organizations
					ON homepage_connect_organizations_sites.org_id = homepage_connect_organizations.id
				WHERE homepage_connect_organizations.id = ?
				ORDER BY sort_order;
			", array(absint($id)));
			return $results;
		}

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
			add_editor_style(plugins_url('/ecu-plugins/includes/plugins/social-media/css/style.css') );
		}

		/**
	     * Enqueueues the necessary CSS and JS
		 */
		public function wp_enqueue_scripts() {
			wp_register_style( 'ecu-shortcode-social-media', plugins_url('/ecu-plugins/includes/plugins/social-media/css/style.css') );
		}

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	     */
		public function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode")){
				$options = is_admin() ? $this->get_organizations_options() : [];

				shortcode_ui_register_for_shortcode(
				    $this->get_shortcode(),
				    array(
				        'label'         => 'Social Media',
				        'listItemImage' => $this->get_font_awesome_html('fa-thumbs-up'),
				        'attrs'         => array(
				           	array(
								'label'  => esc_html__( 'Social Media', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => 'Show an ECU organization\'s social media accounts <a target="_blank" href="http://'. getenv('TOPSITE_ENV').'/connect">registered with the university</a>.'
							),
							array(
								'label'  => esc_html__( 'Organization', $this->get_shortcode() ),
		                        'attr'      => 'id',
		                        'type'      => 'select',
		                        'options'   => $options
		                    ),
           					array(
								'label'  => esc_html__( 'Icon Size', $this->get_shortcode() ),
		                        'attr'      => 'size',
		                        'type'      => 'select',
		                        'options'   => array(
		                           	array(
		                        		'value' => 'small',
		                        		'label' => 'Small',
		                        	),
		                           	array(
		                        		'value' => 'medium',
		                        		'label' => 'Medium',
		                        	),
		                        	array(
		                        		'value' => 'large',
		                        		'label' => 'Large',
		                        	),
		                        ),
		                    ),
		                    array(
								'label'  => esc_html__( 'Display', $this->get_shortcode() ),
		                        'attr'      => 'display',
		                        'type'      => 'select',
		                        'options'   => array(
		                           	array(
		                        		'value' => 'horizontal',
		                        		'label' => 'Horizontal',
		                        	),
		                           	array(
		                        		'value' => 'vertical',
		                        		'label' => 'Vertical',
		                        	),
		                        ),
		                    ),
		                    array(
								'label'  => esc_html__( 'Center Icons in Container', $this->get_shortcode() ),
		                        'attr'      => 'center',
		                        'type'      => 'checkbox',
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
		 *      @type string  $id  Text that will be centered in the banner.
		 *      @type string  $size  Optional.  Defaults to small.  The icon size to display.
		 *      @type string  $display  Optional.  The direction to list the icons.  Defaults to horizontal.
		 *      @type string  $center  Optional. Centers the text in the container if true.  Defaults to false.
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
			wp_enqueue_style('ecu-shortcode-social-media');
			//Get Values and Set any unset values.
			$attrs = shortcode_atts(array(
				'id' => '',
				'size'=>'small',
				'display'=>'horizontal',
				'center'=>false,
			), $attrs, $shortcode_tag);

			$accounts = $this->get_organizations_social_media($attrs['id']);

			$output = "<div id='socialnetworks-" . esc_attr($attrs['size']) . "' class='socialnetworks'";
			if($attrs['center']) {
				$output .= " style='width:100%;text-align:center;'";
			}
			$output .= '>';

			foreach($accounts as $account) {

				switch($attr['size']) {

					default:
					case 'small':
						$icon = self::IMAGE_URL . $account->logo_32;
						break;

					case 'medium':
						$icon = self::IMAGE_URL . $account->logo_40;
						break;

					case 'large':
						$icon = self::IMAGE_URL . $account->logo_60;
						break;
				}

				$output .= "<a href='" . esc_url($account->url) . "' class='ecu-social-link'>";
					$output .= '<img src="' . esc_url($icon) . '" class="socialnetwork" alt="' . get_bloginfo('name') . ' '. esc_attr($account->title) . ' ' . esc_attr($account->name) .'">';
				$output .=  "</a>";
				if($attrs['display'] == 'vertical') {
					$output .= '<br />';
				}
			}
			$output .=  "</div>";

			return $output;
		}
	}

	new Social_Media;
