<?php
	namespace OUR_PLUGINS;

	/**
	 * Shortcode class for the content grid.
	 *
	 * Allows the user to select pages/posts and corresponding images from the library.  It creates a grid
	 * of the images linked to the corresponding pages/posts.
	 */
	class Tabbed_Grid extends Abstract_Ecu_Shortcode {

	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "tabbed_grid";
		}
		/**
		 * Initialize
		 */
		public function initialize(){

			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

			if ( is_admin() ) {
				add_editor_style( plugins_url('/our-plugins/includes/plugins/tabbed-grid/css/style.css') );
			}

			parent::initialize();
		}

		/**
		 * Enqueueues the necessary CSS and JS
		 */
		public function enqueue_scripts() {
			wp_register_style( 'ecu-shortcode-tabbed_grid', plugins_url('/our-plugins/includes/plugins/tabbed-grid/css/style.css') );
		}


		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	     */
		function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode")){
				$url = get_home_url() . '/wp-admin/post-new.php?post_type=ui-elements';
				shortcode_ui_register_for_shortcode(
					$this->get_shortcode(),
					array(
				  	'label'         => 'Post Grid',
				  	'listItemImage' => $this->get_font_awesome_html('fa-th-large '),
				   	'attrs'         => array(
							array(
								'label'  => esc_html__( 'Post Grid', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => "The post grid is a tabbed collection of posts.<br /><br /><a href='{$url}&ui-type=tabbed_grid' class='btn btn-success' style='color:#FFFFFF;' target='_blank'>Create New Post Grid</a>"
							),
							array(
								'label'    => esc_html__( 'Select Post Grid', 'shortcode-ui-example', 'shortcode-ui' ),
								'attr'     => 'tabbed_id',
								'type'     => 'post_select',
								'query'    => array(
									'post_type' => 'ui-elements',
									'meta_query' => array(
										array(
											'key' => 'element_type',
											'value' => 'tabbed_grid',
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
		 *      @type string  $gallery  Comma seperated list of attachment ids.
		 *      @type string  $pages  Comma seperated list of page ids.
		 *	    @type string  $columns The bootstrap 3 column class to use.
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
			wp_enqueue_style('ecu-shortcode-tabbed_grid');
			//Get Values and Set any unset values.
			$attrs = shortcode_atts(array(
				'tabbed_id' => '',
			),array_map('rawurldecode',$attrs), $shortcode_tag);
			if($tabbed_grid = get_field('ui_tabbed_grid', $attrs['tabbed_id'])){
				$id = $attrs['tabbed_id'];
				$nav = ""; $tabs = ""; $counter = 0;
				foreach($tabbed_grid as $tab){
					if($tab['alt_category_name']){
						$name = $tab['alt_category_name'];
						$slug = strtolower(str_replace(' ', '_', $tab['alt_category_name']));
					} else {
						$name = $tab['category']->name;
						$slug = $tab['category']->slug;
					}

					$active = $counter == 0 ? 'active' : '';
					$nav .= "<li role='presentation' class='{$active}'><a href='#{$slug}' aria-controls='{$slug}' role='tab' data-toggle='tab'>{$name}</a></li>";

					$col_size = $tab['posts_per_row'] === 'col-md-3' ? 'col-md-4' : str_replace('sm', 'md', $tab['posts_per_row']);
					$post_size = $tab['post_size'];
					if($tab['post_type']){ // if latest posts
						$tabs .= "<div role='tabpanel' class='tab-pane {$active}' id='$slug'><div class='row'>";
						$args = array(
							'posts_per_page' => $tab['post_count'],
							'category' => $tab['category']->term_id,
							'orderby' => 'date',
							'order' => 'DESC'
						);
						$posts_array = get_posts($args);
						foreach($posts_array as $post_obj){
							$post_title = get_field('h1_title', $post_obj->ID) ? get_field('h1_title', $post_obj->ID) : get_the_title($post_obj->ID);

							$post_bg_obj = get_field('banner_image', $post_obj->ID) ? get_field('banner_image', $post_obj->ID) : '';
							$post_bg = $post_bg_obj ? $post_bg_obj['url'] : '';
							$post_link = filter_var(get_field('external_post', $post_obj->ID), FILTER_VALIDATE_URL) ? filter_var(get_field('external_post', $post_obj->ID), FILTER_VALIDATE_URL) : get_the_permalink($post_obj->ID);
							if($post_bg || is_user_logged_in()){
								$tabs .= "<a href='{$post_link}' class='{$col_size}'><div class='tab-container'><div class='grid_post $post_size' style='background-image:url({$post_bg});'>";
								if(!$post_bg){
									$tabs .= '<strong class="error">Please add a banner image to this post<br />Only visible to logged in users</strong>';
								}
								$tabs .= "<span data-mh='tabbed-grid-{$id}' class='ecu-tabbed-grid-title'>{$post_title}</span></div></div></a>";
							}
						}
						$tabs .= '</div></div>';
					} else {
						$tabs .= "<div role='tabpanel' class='tab-pane {$active}' id='$slug'><div class='row'>";
						if($custom_posts = $tab['custom_posts']){
							foreach($custom_posts as $post_id){
								$post_title = get_field('h1_title', $post_id) ? get_field('h1_title', $post_id) : get_the_title($post_id);
								$post_bg_obj = get_field('banner_image', $post_id) ? get_field('banner_image', $post_id) : '';
								$post_bg = $post_bg_obj ? $post_bg_obj['url'] : '';
								$post_link = filter_var(get_field('external_post', $post_id), FILTER_VALIDATE_URL) ? filter_var(get_field('external_post', $post_id), FILTER_VALIDATE_URL) : get_the_permalink($post_id);
								if($post_bg || is_user_logged_in()){
									$tabs .= "<a href='{$post_link}' class='{$col_size}'><div class='tab-container'><div class='grid_post $post_size' style='background-image:url({$post_bg});'>";
									if(!$post_bg){
										$tabs .= '<strong class="error">Please add a banner image to this post<br />Only visible to logged in users</strong>';
									}
									$tabs .= "<span data-mh='tabbed-grid-{$id}' class='ecu-tabbed-grid-title'>{$post_title}</span></div></div></a>";
								}
							}
						}
						$tabs .= '</div></div>';
					}
					$counter++;
				}
				$output = "<div class='ui_tabbed_grid'>";
				if(count($tabbed_grid) > 1){
					$output .= "<ul class='ui_tabbed_grid_nav nav nav-tabs' role='tablist'>{$nav}</ul>";
				}
				$output .= "<div class='clearfix'></div><div class='tab-content ui_tabbed_grid_tabs'>{$tabs}</div></div>";
				return $output;
			}
		}
	}

	new Tabbed_Grid;
