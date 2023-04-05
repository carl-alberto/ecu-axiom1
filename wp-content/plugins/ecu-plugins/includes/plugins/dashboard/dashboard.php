<?php
	namespace Ecu_Plugins;

	/**
	 * Shortcode class for the dashboard UI element.
	 */
	class Dashboard extends Abstract_Ecu_Shortcode {

	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "dashboard";
		}

		/**
		 * Initialize
		 */
		public function initialize(){

			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

			if ( is_admin() ) {
				add_editor_style( plugins_url('/ecu-plugins/includes/plugins/dashboard/css/style.css') );
			}

			parent::initialize();
		}

		/**
		 * Enqueueues the necessary CSS and JS
		 */
		public function enqueue_scripts() {
			wp_register_style( 'ecu-shortcode-dashboard', plugins_url('/ecu-plugins/includes/plugins/dashboard/css/style.css') );
		}

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	     */
		public function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode") && function_exists("get_field")){
				$url = get_home_url() . '/wp-admin/post-new.php?post_type=ui-elements';
				shortcode_ui_register_for_shortcode(
					$this->get_shortcode(), array(
						'label'         => esc_html__( 'Dashboard', $this->get_shortcode() ),
						'listItemImage' => $this->get_font_awesome_html('fa-th'),
						'attrs'         => array(
							array(
								'label'  => esc_html__( 'Dashboard', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => "The dashboard is a grid of linked icons.<br /><br /><a href='{$url}&ui-type=dashboard' class='btn btn-success' style='color:#FFFFFF;' target='_blank'>Create New Dashboard</a>"
							),
							array(
								'label'    => esc_html__( 'Select Dashboard', 'shortcode-ui-example', 'shortcode-ui' ),
								'attr'     => 'dashboard_id',
								'type'     => 'post_select',
								'query'    => array(
									'post_type' => 'ui-elements',
									'meta_query' => array(
										array(
											'key' => 'element_type',
											'value' => 'dashboard',
										)
									)
								),
								'multiple' => false,
							)
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
			wp_enqueue_style('ecu-shortcode-dashboard');
			//Get Values and Set any unset values.
			$attrs = shortcode_atts(array(
				'dashboard_id' => '',
			), $attrs, $shortcode_tag);
			if($dashboard = get_field('ui_dashboard', $attrs['dashboard_id'])){
				$col_size = get_bs_col(count($dashboard));
				$icon_size = get_icon_size(count($dashboard));
				$output = '<div class="ui_dashboard">
					<div class="row">';
				foreach($dashboard as $col){
					$output .= '<div class="'.$col_size.' ui_element_wrap">';
					if($col['link_element']){
						$link = $col['external_link'] ? $col['external_url'] : $col['internal_link'];

						$output .= "<a href='{$link}' ".($col['external_link'] ? 'target="_blank"' : '').">";
					}
					if($col['icon'] || $col['image']){
						$output .= '<div class="dashboard_icon">';
						if($col['custom_image']){
							$output .= '<div class="ecu-icon-wrap '.$col['background'].' '. $icon_size.' dash-no-bg" style="background-image:url('.$col['image'].');" >';
						} else {
							$output .= '<div class="ecu-icon-wrap '.$col['background'].' '. $icon_size.' " >';
						}
						if(!$col['custom_image']){
							$output .= '<span class="fa '. $col['icon'] . '" aria-hidden="true"></span>';
						}
						$output .= '</div>
						</div>';
					}
					if($col['title']){
						$output .= '<div class="dashboard_title" data-mh="dash_title-'.$attrs['dashboard_id'].'">' . $col['title'] . "</div>";
					}
					if($col['description']){
						$output .= '<div class="dashboard_description">' . $col['description'] . "</div>";
					}
					if($col['link_element']){
						$output .= "</a>";
					}
					$output .= '</div>';
				}
				$output .= '</div>
				</div>';

				return $output;
			}
		}
	}

	function get_icon_size($size){
		switch($size){
			case 1:
				return 'ui_icon-xl';
				break;
			case 2:
				return 'ui_icon-lg';
				break;
			case 3:
				return 'ui_icon-md';
				break;
			default:
				return 'ui_icon-sm';
				break;
		}
	}

	new Dashboard;
