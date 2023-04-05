<?php
	namespace Ecu_Plugins;

	/**
	 * Fancy Quote is a shortcode that adds a styled quote and citation
	 */
	class Iframe extends Abstract_Ecu_Shortcode {
		public function initialize(){
			parent::initialize();
		}
	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "iframe";
		}

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
		*/
		function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode")){
				shortcode_ui_register_for_shortcode(
					$this->get_shortcode(),
					array(
						'label'         => 'iFrame',
						'listItemImage' => $this->get_font_awesome_html('fa-window-maximize'),
						'attrs'         => array(
							array(
								'label'  => esc_html__( 'iFrame', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => 'Allows the embedding of an iframe for a pre-approved domains.<br />
								Currently only supports:
								<ul>
									<li>- <strong>' . str_replace(",", "</strong></li><li>- <strong>", WP_IFRAME_HOSTS) . '</strong></li>
								</ul>'
							),
							array(
								'label'  => esc_html__( 'URL', $this->get_shortcode() ),
								'description' => esc_html__( 'Please enter iFrame source URL' ),
								'attr'   => 'src',
								'type'   => 'text',
								'encode' => true,
								'meta' => array(
									'placeholder' => esc_html__('https://app.powerbi.com/view?r=...'),
								)
							),
							array(
								'label'  => esc_html__( 'Height', $this->get_shortcode() ),
								'description' => esc_html__( 'Must use pixel value. Defaults to 600px.' ),
								'attr'   => 'height',
								'type'   => 'text',
								'encode' => true,
								'meta' => array(
									'placeholder' => esc_html__('600px'),
								),
							),
							array(
								'label'  => esc_html__( 'Width', $this->get_shortcode() ),
								'description' => esc_html__( 'Must use percent value. Defaults to 100%.' ),
								'attr'   => 'width',
								'type'   => 'text',
								'encode' => true,
								'meta' => array(
									'placeholder' => esc_html__('100%'),
								),
							),
							array(
								'label'  => esc_html__( 'Do not allow a Scrollbar', $this->get_shortcode() ),
								'attr'   => 'scrolling',
								'type'   => 'checkbox',
								'encode' => false,
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
		 *      @type string  $fancyquote-body      The qoute.
		 *      @type string  $fancyquote-citation  The source of the qoute.
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
			//Get Values and Set any unset values.
			$str = '';
			$attrs = shortcode_atts(array( //creates varaibales from your attrs
				'src' => '',
				'height' => '',
				'width' => '',
				'scrolling' => '',

			), $attrs, $shortcode_tag);
			$src = $attrs['src'];
			$height = $attrs['height'] ? preg_replace('/\D/', '', $attrs['height']) . 'px' : '600px';
			$width = $attrs['width'] ? preg_replace('/\D/', '', $attrs['width']) . '%' : '100%';
			$scrolling = ($attrs['scrolling']) ? 'scrolling="no"' : '';


			$output = '';
			$domain = parse_url($src);

			if (false === strpos(WP_IFRAME_HOSTS, $domain['host'])) {
				$output .= $domain['host'] . ' is not an approved domain or the URL was not a valid URL.';
			} else {
				if($domain['host'] == 'public.tableau.com'){
					$src = str_replace('http:', 'https:', $src);
					
					if(strpos($domain['path'], 'profile') !== false){
						$src = 'https://public.tableau.com/views/' . str_replace('!/vizhome/', '', $domain['fragment']);
					}
					
					$explode = explode('?', $src);
					
					$src = $explode[0] . '?:embed=y&:showVizHome=no&:host_url=https&#37;3A&#37;2F&#37;2Fpublic.tableau.com&#37;2F&:embed_code_version=2&:tabs=yes&:toolbar=yes&:animate_transition=yes&:display_static_image=no&:display_spinner=no&:display_overlay=yes&:display_count=yes&:loadOrderID=0';
				}

				if($domain['host'] == 'www.imleagues.com'){
					$src = str_replace('WidgetLoader.ashx', 'IntramuralWidget.aspx', $src);
				}

				$output .= "<iframe width='{$width}' height='{$height}' src='{$src}' frameborder='0' allowFullScreen='true' {$scrolling}></iframe>";
			}

			return $output;

		}
	}

	new Iframe;
