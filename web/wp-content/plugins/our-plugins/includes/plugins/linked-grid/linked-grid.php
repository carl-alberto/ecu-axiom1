<?php
	namespace OUR_PLUGINS;

	/**
	 * Shortcode class for the content grid.
	 *
	 * Allows the user to select pages/posts and corresponding images from the library.  It creates a grid
	 * of the images linked to the corresponding pages/posts.
	 */
	class Linked_Grid extends Abstract_Ecu_Shortcode {

	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "linked_grid";
		}
		/**
		 * Initialize
		 */
		public function initialize(){

			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

			if ( is_admin() ) {
				add_editor_style(plugins_url('/our-plugins/includes/plugins/tabbed-grid/css/style.css') );
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
				  	'label'         => 'Linked Grid',
				  	'listItemImage' => $this->get_font_awesome_html('fa-th-large '),
				   	'attrs'         => array(
							array(
								'label'  => esc_html__( 'Linked Grid', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => "The linked grid allows for a linked image grid of posts, pages and external links.<br /><br /><a href='{$url}&ui-type=linked_grid' class='btn btn-success' style='color:#FFFFFF;' target='_blank'>Create New Linked Grid</a>"
							),
							array(
								'label'    => esc_html__( 'Select Linked Grid', 'shortcode-ui-example', 'shortcode-ui' ),
								'attr'     => 'linked_id',
								'type'     => 'post_select',
								'query'    => array(
									'post_type' => 'ui-elements',
									'meta_query' => array(
										array(
											'key' => 'element_type',
											'value' => 'linked_grid',
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
				'linked_id' => '',
			),array_map('rawurldecode',$attrs), $shortcode_tag);
			if($linked_grid = get_field('ui_linked_grid', $attrs['linked_id'])){
				$links = '';
				$col_size = get_field('posts_per_row', $attrs['linked_id']) === 'col-md-3' ? 'col-md-4' : get_field('posts_per_row', $attrs['linked_id']);
				$post_size = get_field('post_size', $attrs['linked_id']);
				$id = $attrs['linked_id'];
				foreach($linked_grid as $post){
					$id = $post['post'];
					if($id && !$post['external']){
						$target = '_parent';
						if($post['link_text']){
							$post_title = $post['link_text'];
						} elseif(get_field('h1_title', $id)){
							$post_title = get_field('h1_title', $id);
						} else {
							$post_title = get_the_title($id);
						}
						$banner = get_field('banner_image', $id);
						$post_bg = $post['image']['sizes']['large'] ? $post['image']['sizes']['large'] : $banner['sizes']['large'];
						$post_link = filter_var(get_field('external_post', $id), FILTER_VALIDATE_URL) ? filter_var(get_field('external_post', $id), FILTER_VALIDATE_URL) : get_the_permalink($id);
					} else {
						$target = '_blank';
						$post_title = $post['link_text_required'];
						$post_bg = $post['image_required']['sizes']['large'];
						$post_link = filter_var(get_field('external_post', $id), FILTER_VALIDATE_URL) ? filter_var(get_field('external_post', $id), FILTER_VALIDATE_URL) : $post['url'];
					}
					if($post_bg || is_user_logged_in()){
						$links .= "<a href='{$post_link}' class='{$col_size} no-text-decoration' target='{$target}'><div class='tab-container'><div class='grid_post $post_size' style='background-image:url({$post_bg});'>";
						if(!$post_bg){
							$links .= '<strong class="error">Please add a banner image to this post<br />Only visible to logged in users</strong>';
						}
						$links .= "<span data-mh='{$id}'>{$post_title}</span></div></div></a>";
					}
				}
				$output = "<div class='ui_tabbed_grid ui_linked_grid'><div class='tab-content ui_tabbed_grid_tabs'><div class='tab-pane active'><div class='row'>{$links}</div></div></div></div>";
				return $output;
			}
		}
	}

	new Linked_Grid;
