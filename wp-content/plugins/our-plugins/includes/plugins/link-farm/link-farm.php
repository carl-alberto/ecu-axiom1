<?php
	namespace OUR_PLUGINS;

	/**
	 * Shortcode class for the link farm.
	 */
	class Link_Farm extends Abstract_Ecu_Shortcode {

	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "link_farm";
		}

		/**
		 * Initialize
		 */
		public function initialize(){

			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

			if ( is_admin() ) {
				add_editor_style( plugins_url('/our-plugins/includes/plugins/link-farm/css/style.css'));
			}

			parent::initialize();
		}

		/**
		 * Enqueueues the necessary CSS
		 */
		public function enqueue_scripts() {
			wp_register_style( 'ecu-shortcode-link-farm', plugins_url('/our-plugins/includes/plugins/link-farm/css/style.css' ));
		}

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	     */
		public function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode")){
				$url = get_home_url() . '/wp-admin/post-new.php?post_type=ui-elements';
				shortcode_ui_register_for_shortcode(
					$this->get_shortcode(), array(
						'label'         => 'Link Farm',
						'listItemImage' => $this->get_font_awesome_html('fa-link'),
						'attrs'         => array(
							array(
								'label'  => esc_html__( 'Link Farm', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => "Create attractive and informative link farms with icons and descriptive text!<br /><br /><a href='{$url}&ui-type=link_farm' class='btn btn-success' style='color:#FFFFFF;' target='_blank'>Create New Link Farm</a>"
							),
							array(
								'label'    => esc_html__( 'Select Link Farm', 'shortcode-ui-example', 'shortcode-ui' ),
								'attr'     => 'link_farm_id',
								'type'     => 'post_select',
								'query'    => array(
									'post_type' => 'ui-elements',
									'meta_query' => array(
										array(
											'key' => 'element_type',
											'value' => 'link_farm',
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
		 *      @type string  $link_farm_id The link farm id to use for the link farm.
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
			wp_enqueue_style('ecu-shortcode-link-farm');
			//Get Values and Set any unset values.
			$attrs = shortcode_atts(array(
				'link_farm_id' => '',
			),array_map('rawurldecode',$attrs), $shortcode_tag);

			if($farm = get_field('ui_link_farm', $attrs['link_farm_id'])){

				$cols = get_bs_col(count($farm));
				$output = '<div class="ui_link_farm">
					<div class="row">';
				foreach($farm as $column){
					$output .= '<div class="'.$cols.'">';

					if ($column['column_title']) {
						$output .= '<div class="ui_link_farm_header">
							<p data-mh="link-farm-'. $attrs['link_farm_id'] .'">'. esc_html($column['column_title']) .'</p>
						</div>';
					}

					$output .= '<div class="ui_link_farm_links">
							<ul style="padding:5px;">';
						foreach($column['links'] as $link){
							if(!isset($column['icon_size'])) {
								$link['icon_size'] = 'default';
							}
							$page_link = $link['external_link'] ? $link['external_url'] : $link['internal_link'];
							$output .= '<li><a class="no-text-decoration" href="'.esc_url($page_link).'"';
							$output .= $link['external_link'] ? 'target="_blank">' : '>';
							if($link['icon']){
								$classes = array('fa', $link['icon'], 'fa-fw');
								$classes[] = $link['icon_size'] != 'default' ? $column['icon_size'] : '';
								$output .= '<span class="'. esc_attr(implode($classes, " ")) . '" aria-hidden="true"></span>';
									//$output .= '<i class="fa '.$link[icon].'"></i>';
							};
							//$output .= $link['icon'] ? '<'$link['icon'] : '';
							$output .= '<span class="ui_link_farm_text">' . esc_html($link['link_text']) . '</span></a></li>';
						}
					$output .= '</ul>
						</div>
					</div>';
				}
				$output .= '</div>
				</div>';
				return $output;
			}
		}
	}

	/**
	 * Gets the Bootstrap 3 column class to use.
	 *
	 * @param  string The number of columns the link farm has.
	 * @return string the boostrap 3 column class
	 */
	function get_bs_col($cols){
		switch ($cols){
			case '1': return 'col-sm-12'; break;
			case '2': return 'col-sm-6'; break;
			case '3': return 'col-sm-4'; break;
			default: return 'col-sm-3'; break;
		}
	}

	new Link_Farm;