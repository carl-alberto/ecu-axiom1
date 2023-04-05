<?php
	namespace OUR_PLUGINS;

	/**
	 * Fancy Quote is a shortcode that adds a styled quote and citation
	 */
	class Ecu_Icon extends Abstract_Ecu_Shortcode {

	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "ecu_icon";
		}

		/**
		 * Shortcode Function.  Wraps the site origin widget.
		 *
		 * @link https://codex.wordpress.org/Shortcode_API Shortcode UI
		 * @link https://codex.wordpress.org/Widgets_API Widget UI
		 * @link https://wordpress.org/plugins/so-widgets-bundle/ Site Origin Widgets
		 *
		 * @param array $atts  {
		 *      Optional. The settings for the shortcode instance.
		 *
		 *      @type string  $icon  The icon to use.
		 *      @type string  $color   The color of the icon
		 *      @type string  $alignment  center, left, or right.
		 *      @type string  $size   The size of the icon.
		 * }
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
		 */
		public function callback($attrs, $content = '', $shortcode_tag){
			wp_enqueue_style('ecu-fontello-fonts');
			//Get Values and Set any unset values.
			$attrs = shortcode_atts(array( //creates varaibales from your attrs
				'icon' => 'icon-alert-circled',
				'color'    => '#000',
				'alignment'  => 'center',
				'size'     => '1em',
			),array_map('rawurldecode',$attrs), $shortcode_tag);

			$str = '<div class="ecu-icon-container ecu-icon" style="font-size:' . esc_attr( $attrs['size'] ) . ';color:' . esc_attr( $attrs['color'] ) . ';text-align:' . esc_attr( $attrs['alignment'] ) .';">';
				$str .= '<span class="' . esc_attr( $attrs['icon'] ) . '""  aria-hidden="true"></span>';
			$str .= '</div>';

			return $str;
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
				        'label'         => 'Icon',
				        'description' => 'Test description',
				        'listItemImage' => $this->get_font_awesome_html('fa-genderless'),
				        'attrs'         => array(
				        	array(
								'label'  => esc_html__( 'Icon', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => 'You can select any icon from a number of icon font families.  Usefull if you are not satisfied with the other shortcodes that use icons layouts to build your own.'
							),
				        	array(
								'label'  => esc_html__( 'Select Icon', $this->get_shortcode() ),
								'attr'   => 'icon',
								'type'   => 'ecu-icon-select',
								'empty_icon' => 'false',
							),
							array(
								'label'  => esc_html__( 'Color', $this->get_shortcode() ),
								'attr'   => 'color',
								'value' => '#000',
								'type'   => 'color',
							),
							array(
								'label'  => esc_html__( 'Alignment', $this->get_shortcode() ),
		                        'attr'      => 'alignment',
		                        'type'      => 'select',
		                        'options'   => array(
		                           	array(
		                        		'value' => 'center',
		                        		'label' => 'Center',
		                        	),
		                        	array(
		                        		'value' => 'left',
		                        		'label' => 'Left',
		                        	),
		                        	array(
		                        		'value' => 'right',
		                        		'label' => 'Right',
		                        	),
		                        ),
		                    ),
							array(
								'label'  => esc_html__( 'Size', $this->get_shortcode() ),
								'attr'   => 'size',
								'description' => esc_html__( 'Optional.  Defaults to 1em.  Must be a valid css font size: px, %, em, etc.' ),
								'type'   => 'text',
							),

				        )
				    )
				);
			}
		}
	}

	new Ecu_Icon;
