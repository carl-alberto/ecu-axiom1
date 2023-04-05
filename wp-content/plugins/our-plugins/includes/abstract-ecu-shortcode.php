<?php
	namespace OUR_PLUGINS;

	/**
	 * Abstract class that defines the ecu shortcode.
	 */
	abstract class Abstract_Ecu_Shortcode extends Ecu_Database {

	    /**
	     * Returns the shortcode.
	     *
	     * @return string $shortcode The shortcode.
	     */
		abstract public function get_shortcode();

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	     */
		//abstract public function register_with_shortcake();

		/**
		 * The shortcode handler
		 *
		 * @link https://codex.wordpress.org/Shortcode_API Shortcode UI
		 *
		 * @param array  $attrs  		An associative array of attributes, or an empty string if no attributes are given
		 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
		 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
		 * @return string The output for the shortcode.
	     */
		abstract public function callback($attrs, $content = '', $shortcode_tag);

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct(){
			$this->initialize();
		}

		/**
		 * Initialize
		 *
		 * Register the shortcode and the shortcake functions.  If any other setup is required,
		 * such as enqueuing scripts, then you will need to have your own and invoke this parent
		 * function in your initialization.
		 */
		public function initialize(){
			add_shortcode($this->get_shortcode(), array($this, 'callback'));
			//add_action('init', array($this, 'register_with_shortcake'), 15);
		}

		/**
		 * This is needed so you can set true defaults for an option.   Can't do it for checkboxes
		 * because of the shortcode dev being obtuse.  See the links.
		 *
		 * @link  https://github.com/wp-shortcake/shortcake/pull/413 Obtuse Developers
		 *
		 * @author Ryan Cowan <cowanr@ecu.edu>
		 * @return array An array with yes / no options for a Shortcode UI select.
		 */
		public function get_yes_no_options() {
			return 	array(
            	array(
            		'value' => '1',
            		'label' => 'Yes'
            	),
            	array(
            		'value' => '0',
            		'label' => 'No'
            	),
            );
		}

		/**
		 * Function to check for protocol in URL
		 *
		 * @param string $url url to check
		*/
		public function get_file_url(){
				$query = $_SERVER['PHP_SELF'];
				$path = pathinfo( $query );
				$what_you_want = $path['basename'];
			return $path['basename'];
		}

		/**
		 * Function used by shortcodes to get font awesome icon html
		 *
		 * @link http://fontawesome.io/icons/ Font Awesome Accounts
		 *
		 * @param string $icon The font awesome class for the icon.
	     */
		public function get_font_awesome_html($icon){
			return "<span class='fa " . $icon . "' aria-hidden='true' style='position: absolute;top: 50%;left: 50%;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);'></span>";
		}

	}
