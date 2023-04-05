<?php
	namespace Ecu_Plugins;

	/**
	 * Fancy Quote is a shortcode that adds a styled quote and citation
	 */
	class Fancy_Quote extends Abstract_Ecu_Shortcode {
		public function initialize(){

			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

			if ( is_admin() ) {
				add_editor_style( plugins_url('/ecu-plugins/includes/plugins/fancy-quote/css/style.css') );
			}

			parent::initialize();
		}
		/**
		 * Enqueueues the necessary CSS and JS
		 */
		public function enqueue_scripts() {
			wp_register_style( 'ecu-shortcode-fancy-quote', plugins_url('/ecu-plugins/includes/plugins/fancy-quote/css/style.css') );
		}
	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "ecu_fancy_quote";
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
			wp_enqueue_style('ecu-shortcode-fancy-quote');
			//Get Values and Set any unset values.
			$str = '';
			$attrs = shortcode_atts(array( //creates varaibales from your attrs
				'fancyquote-body' => '',
				'fancyquote-citation' => '',

			), $attrs, $shortcode_tag);

			$citationdiv = '';
			if( $attrs['fancyquote-citation'] !== '' ) {
				$citationdiv .= '<div class="quote"> - ' . esc_html( $attrs['fancyquote-citation'] ) . '</div>';
			}

			$str .= '<div class="blockquotes">';
				$str .= '<div class="fancy">';
					$str .= '<hr class="left">';
					$str .= '<div class="doublequotes topquote"><span class="fa fa-quote-left" aria-hidden="true"></span></div>';
					$str .= '<hr class="right">';
				$str .= '</div>';
				$str .= '<div class="clear"></div>';
				$str .= '<div class="blockcontent ">';
					$str .= '<div class="mainquote">' . esc_html( $attrs['fancyquote-body'] ) . '</div>';
					$str .=	 $citationdiv;
				$str .= '</div>';
				$str .= '<div class="fancy">';
					$str .= '<hr class="left">';
					$str .= '<div class="doublequotes"><span class="fa fa-quote-right" aria-hidden="true"></span></div>';
					$str .= '<hr class="right">';
				$str .= '</div>';
				$str .= '<div class="clear"></div>';
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
				shortcode_ui_register_for_shortcode(
				    $this->get_shortcode(),
				    array(
				        'label'         => 'Fancy Quote',
				        'listItemImage' => $this->get_font_awesome_html('fa-quote-right'),
				        'attrs'         => array(
				        	array(
								'label'  => esc_html__( 'Fancy Quote', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => 'Shows a qoute with some fancy styling!'
							),
				        	array(
								'label'  => esc_html__( 'Quote', $this->get_shortcode() ),
								'attr'   => 'fancyquote-body',
								'type'   => 'textarea',
								'encode' => true,
							),
							array(
								'label'  => esc_html__( 'Citation', $this->get_shortcode() ),
								'attr'   => 'fancyquote-citation',
								'type'   => 'text',
								'encode' => true,
							),
				        )
				    )
				);
			}
		}
	}

	new Fancy_Quote;
