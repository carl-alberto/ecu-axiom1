<?php
	namespace Ecu_Plugins;

	/**
	 * Shortcode class for the dashboard UI element.
	 */
	class Dashboard2 extends Abstract_Ecu_Shortcode {
		public $output;

	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "dashboard_2";
		}

		/**
		 * Initialize
		 */
		public function initialize(){

			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

			if ( is_admin() ) {
				add_editor_style( plugins_url('/ecu-plugins/includes/plugins/dashboard2/css/style.css') );
			}

			parent::initialize();
		}

		/**
		 * Enqueueues the necessary CSS and JS
		 */
		public function enqueue_scripts() {
			wp_register_style( 'ecu-shortcode-dashboard2', plugins_url('/ecu-plugins/includes/plugins/dashboard2/css/style.css') );
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
						'label'         => esc_html__( 'Dashboard 2.0', $this->get_shortcode() ),
						'listItemImage' => $this->get_font_awesome_html('fa-th'),
						'attrs'         => array(
							array(
								'label'  => esc_html__( 'Dashboard 2.0', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => "The dashboard is a grid of icons, polaroids, or link groups.<br /><br /><a href='{$url}&ui-type=dashboard_2' class='btn btn-success' style='color:#FFFFFF;' target='_blank'>Create New Dashboard 2.0</a>"
							),
							array(
								'label'    => esc_html__( 'Select Dashboard 2.0', 'shortcode-ui-example', 'shortcode-ui' ),
								'attr'     => 'dashboard_id',
								'type'     => 'post_select',
								'query'    => array(
									'post_type' => 'ui-elements',
									'meta_query' => array(
										array(
											'key' => 'element_type',
											'value' => 'dashboard_2',
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
			wp_enqueue_style('ecu-shortcode-dashboard2');
			//Get Values and Set any unset values.
			$attrs = shortcode_atts(array(
				'dashboard_id' => '',
			), $attrs, $shortcode_tag);

			if($dashboard = get_field('ui_dashboard_2', $attrs['dashboard_id'])){
				$count = count($dashboard);
				if ($count != 2) {
					$col_class = 'col-12 col-sm-6 col-lg-4';
				} else {
					$col_class = 'col-12 col-sm-6';
				}

				$col_class = 'col-12 col-sm-6 col-lg-4';
				$this->output = '<div class="dashboard-wrapper">';
					$this->output .= '<div class="row item-row">';
						foreach ($dashboard as $item) {
							if (isset($item['uploaded_icon']['url'])) {
								$item['icon'] = '<img src="'.$item['uploaded_icon']['url'].'" />';
							}   else   {
								$item['icon'] = '<span class="fa fa-2x '.$item['fa_icon'].' aria-hidden="true"></span>';
							}

							$this->output .= '<div class="'.$col_class.' dashboard-item">';
								$this->render_item($item);
							$this->output .= '</div>';
						}
					$this->output .= '</div>';
				$this->output .= '</div>';
				return $this->output;
			}
		}



		public function render_item($item) {
			switch($item['item_type']) {
			 	case 'polaroid':
		        	$this->render_polaroid($item);
		        	break;
		        case 'horizontal_icon':
		        	$this->render_horizontal_icon($item);
		        	break;
		        case 'vertical_icon':
		        	$this->render_vertical_icon($item);
		        	break;
		        case 'icon_with_content':
		        	$this->render_icon_with_content($item);
		        	break;
		        case 'icon_with_links':
		        	$this->render_icon_with_links($item);
		        	break;
		    }
		}

		public function render_polaroid($item) {
			if(!isset($item['img']['description'])) { 
				$item['img']['description'] = ''; 
			}
			$this->output .= '<div class="polaroid-wrapper item '.$item['color'].'">';
				$this->output .= '<div class="row">';
					$this->output .= '<div class="col-12">';
						$this->output .= '<img src="'. ( $item['full_res'] ? $item['img']['original_image']['url'] : $item['img']['sizes']['medium'] ). '" alt="'.$item['img']['description'].'"></img>'; //medium_large
					$this->output .= '</div>';
				$this->output .= '</div>';
				if ($item['icon_link']) {
					$this->output .= '<a href="'.$item['icon_link']['url'].'" target="'.$item['icon_link']['target'].'">';
				}
					$this->output .= '<div class="polaroid-caption link-wrap">';
							$this->output .= $item['icon'];
							$this->output .= '<p class="ecu-h2">'.$item['title'].'</p>';
					$this->output .= '</div>';
				if ($item['icon_link']) {
					$this->output .= '</a>';
				}
			$this->output .= '</div>';
			return;

		}

		public function render_horizontal_icon($item) {
			if ($item['icon_link']) {
				$this->output .= '<a href="'.$item['icon_link']['url'].'" target="'.$item['icon_link']['target'].'">';
			}
			$this->output .= '<div class="ecu-icon-wrapper item '.$item['color'].' link-wrap ">';

						$this->output .= $item['icon'];

						$this->output .= '<p class="ecu-h2">'.$item['title'].'</p>';

			$this->output .= '</div>';
			if ($item['icon_link']) {
				$this->output .= '</a>';
			}
			return;
		}

		public function render_vertical_icon($item) {
			if ($item['icon_link']) {
				$this->output .= '<a href="'.$item['icon_link']['url'].'" target="'.$item['icon_link']['target'].'">';
			}
			$this->output .= '<div class="vertical-icon-wrapper item link-wrap '.$item['color'].'">';
				$this->output .= '<div class="row">';
					$this->output .= '<div class="col-12">';
						$this->output .= $item['icon'];
					$this->output .= '</div>';
				$this->output .= '</div>';
				$this->output .= '<div class="row">';
					$this->output .= '<div class="col-12 dashboard-title">';
						$this->output .= '<p class="ecu-h2">'.$item['title'].'</p>';
					$this->output .= '</div>';
				$this->output .= '</div>';
			$this->output .= '</div>';
			if ($item['icon_link']) {
				$this->output .= '</a>';
			}
			return;
		}

		public function render_icon_with_content($item) {
			$this->output .= '<div class="ecu-icon-content-wrapper item '.$item['color'].'">';
				if ($item['icon_link']) {
					$this->output .= '<a href="'.$item['icon_link']['url'].'" target="'.$item['icon_link']['target'].'">';
				}
				$this->output .= '<div class="link-wrap">';
					$this->output .= '<div class="col-12">';
						$this->output .= $item['icon'];
					$this->output .= '</div>';
					$this->output .= '<div class="col-12">';
						$this->output .= '<p class="ecu-h2">'.$item['title'].'</p>';
					$this->output .= '</div>';
				$this->output .= '</div>';

				if ($item['icon_link']) {
					$this->output .= '</a>';
				}
				$this->output .= '<div class="col-12 dash-content">';
					$this->output .= $item['content'];
				$this->output .= '</div>';
			$this->output .= '</div>';
			return;
		}

		public function render_icon_with_links($item) {
			$this->output .= '<div class="ecu-icon-links-wrapper item '.$item['color'].'">';
				if ($item['icon_link']) {
					$this->output .= '<a href="'.$item['icon_link']['url'].'" target="'.$item['icon_link']['target'].'">';
				}
				$this->output .= '<div class="link-wrap">';
					$this->output .= '<div class="col-12 ">';
						$this->output .= $item['icon'];
					$this->output .= '</div>';
					$this->output .= '<div class="col-12">';
						$this->output .= '<p class="ecu-h2">'.$item['title'].'</p>';
					$this->output .= '</div>';
				$this->output .= '</div>';
				if ($item['icon_link']) {
					$this->output .= '</a>';
				}
				$this->output .= '<div class="dash-content col-12">';
					$this->output .= '<ul class="link-list">';
						foreach ($item['links'] as $link) {
							$this->output .= '<li>';
								$this->output .= '<a href="'.$link['url']['url'].'" target="'.$link['url']['target'].'">'.$link['url']['title'].'</a>';
							$this->output .= '</li>';
						}
					$this->output .= '</ul>';
				$this->output .= '</div>';
			$this->output .= '</div>';
			return;
		}
	}

	new \Ecu_Plugins\Dashboard2;
