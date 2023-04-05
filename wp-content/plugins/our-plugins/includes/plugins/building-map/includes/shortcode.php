<?php
	namespace OUR_PLUGINS;

	/**
	 * Shows a google map centered on the selected building.
	 * The map can link to the building on ECU\'s campus maps, the building information page,
	 * or not be linked at all.
	 */
	class Building_Map extends Abstract_Ecu_Shortcode {

		/**
		 * Initialize
		 */
		public function initialize(){
			if ( !is_admin() ) {
				add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
			}

			parent::initialize();
		}


		/**
	     * Enqueueues the necessary CSS and JS
		 */
		public function wp_enqueue_scripts() {
			wp_register_script('ecu-building-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDcdtCHhQ34IxoOlSBw_GnQwiG6JfcP2KM');
		}

	    /**
	     * Returns the shortcode
	     *
	     * @return string $shortcode The shortcode.
	     */
		public function get_shortcode() {
			return "ecu_building_map";
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
			wp_enqueue_script('ecu-building-map');
			//Get Values and Set any unset values.
			$str = '';
			$attrs = shortcode_atts(array( //creates varaibales from your attrs
				'item' => '',
				'link' => '',
				'height' => '180px',
				'zoom'=> 17,
			),array_map('rawurldecode',$attrs), $shortcode_tag);

			static $instance = 0;
			$instance++;
			$attrs['div_id'] = $attrs['item'] . '-' . $instance;

  			return render_map($attrs);
		}
	}

	new Building_Map;
