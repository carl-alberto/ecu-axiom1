<?php
	namespace OUR_PLUGINS;

	/**
	 * Fancy Quote is a shortcode that adds a styled quote and citation
	 */
	class Search extends Abstract_Ecu_Shortcode {
		public function initialize(){
			parent::initialize();
		}
        public function enqueue_scripts() {
			// wp_register_style( 'ecu-shortcode-dashboard', plugins_url('/our-plugins/includes/plugins/dashboard/css/style.css') );
		}
	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "search";
		}

		/**
		 * Shortcode Function.
		 *
		 * @link https://codex.wordpress.org/Shortcode_API Shortcode UI
		 *
		 * @param array $atts  {
		 *      Optional. The settings for the shortcode instance.
		 *
		 *      @type string  $fancyquote-body      The qoute.
		 *      @type string  $fancyquote-citation  The source of the qoute.
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
            $attrs = shortcode_atts(array(
				'search_id' => '',
			),array_map('rawurldecode',$attrs), $shortcode_tag);
            $output;
			if($search = get_field('ui_search', $attrs['search_id'])) {
                $btntext = $search['button_text'] ? $search['button_text'] : '<span class="fa fa-search" aria-hidden="true"></span>';
                $output = "<div class='ui_search'>";
				if($bg =  $search['background_image']){
					$output .= "<img src='{$bg['url']}' alt='{$bg['description']}'  data-mh='ui_search' class='img-responsive visible-lg visible-xl' />";
				}
				$output .= "<div class='ui_search_wrap' data-mh='ui_search'>";
                    if($bg){
                        $output .= "<div class='ui_search_form'>";
                    } else {
                        $output .= "<div class='ui_search_form full'>";
                    }
                    if($title = $search['title']){
                        $output .= "<h2>{$title}</h2>";
                    }
                    $output .= "<form action='{$search['form_action']}' method='{$search['form_method']}'>
                                    <div class='input-group'>
                                        <input type='text' name='{$search['input_name']}' placeholder='{$search['placeholder_text']}' aria-label='Search Parameter'>
                                        <div class='input-group-append'>
                                            <button class='btn' type='submit'>{$btntext}</button>
                                        </div>
                                    </div>
                                </form>";
                            if($search['links']){
                                $output .= "<ul class='ui_search_links'>";
                                foreach($search['links'] as $link){
                                    $output .= "<li><a href='{$link['link']['url']}' target='{$link['link']['target']}'>{$link['link']['title']}</a>";
                                }
                                $output .= "</ul>";
                            }
                            $output .= "</div>";
                $output .= "</div>";
				if($bg && $bg['caption']){
					$output .= "<div class='ui_search_desc'>{$bg['caption']}</div>";
				}
				$output .= "</div>";
            } else {
                $output = 'Invalid search ID.';
            };
            return $output;
		}

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	     */
		function register_with_shortcake(){
            shortcode_ui_register_for_shortcode(
                $this->get_shortcode(),
                array(
                    'label'         => 'Search',
                    'listItemImage' => $this->get_font_awesome_html('fa-search'),
                    'attrs'         => array(
                        array(
                            'label'  => esc_html__( 'Search', $this->get_shortcode() ),
                            'attr'   => 'header',
                            'type'   => 'ecu-shortcode-information',
                            'description' => "Displays customizable search form<br /><br /><a href='{$url}&ui-type=ui_search' class='btn btn-success' style='color:#FFFFFF;' target='_blank'>Create New Search Element</a>"
                        ),
                        array(
                            'label'    => esc_html__( 'Select Search Element', 'shortcode-ui-example', 'shortcode-ui' ),
                            'attr'     => 'search_id',
                            'type'     => 'post_select',
                            'query'    => array(
                                'post_type' => 'ui-elements',
                                'meta_query' => array(
                                    array(
                                        'key' => 'element_type',
                                        'value' => 'search',
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

	new Search;
