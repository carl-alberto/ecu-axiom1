<?php
	namespace Ecu_Plugins;

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
			), $attrs, $shortcode_tag);

			static $instance = 0;
			$instance++;
			$attrs['div_id'] = $attrs['item'] . '-' . $instance;

  			return render_map($attrs);
		}

		/**
		 * Registers the UI of the shortcode with shortcake
		 *
		 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	     */
		function register_with_shortcake(){
			if (function_exists("shortcode_ui_register_for_shortcode")){

				if(is_admin()){
					$results = \Database\Tools::query("
						SELECT university_buildings.name, maps_items.id
						FROM homepage_tools.university_buildings
						LEFT JOIN homepage_tools.maps_items ON maps_items.child_id = university_buildings.id
						LEFT JOIN homepage_tools.maps_shapes ON maps_shapes.item_id = maps_items.id
						LEFT JOIN homepage_tools.maps_points ON maps_points.shape_id = maps_shapes.id
						LEFT JOIN homepage_tools.maps_layers_items ON maps_layers_items.item_id = maps_items.id
						WHERE maps_layers_items.layer_id = 1 AND maps_points.id IS NOT NULL AND university_buildings.id IS NOT NULL AND university_buildings.name IS NOT NULL
						GROUP BY university_buildings.id
						ORDER BY university_buildings.name
					");

					$options = array();
					if($results){
						foreach($results as $building) {
							$options[] = array(
								'value' => (string)$building->id,
								'label' => $building->name,
							);
						}
					} else {
						$options[] = array(
							'value' => '',
							'label' => '',
						);
					}
				} else {
					$options = [];
				}
				shortcode_ui_register_for_shortcode(
				    $this->get_shortcode(),
				    array(
				        'label'         => 'ECU Map',
				        'listItemImage' => $this->get_font_awesome_html('fa-map'),
				        'attrs'         => array(
				        	array(
								'label'  => esc_html__( 'ECU Map', $this->get_shortcode() ),
								'attr'   => 'header',
								'type'   => 'ecu-shortcode-information',
								'description' => 'Shows a google map centered on the selected building.   The map can link to the building on ECU\'s campus maps, the building information page, or not be linked at all.'
							),
 							array(
								'label'  => esc_html__( 'Building', $this->get_shortcode() ),
		                        'attr'      => 'item',
		                        'type'      => 'select',
		                        'options'   => $options,
		                    ),
		                    array(
								'label'  => esc_html__( 'Link Map', $this->get_shortcode() ),
		                        'attr'      => 'link',
		                        'type'      => 'select',
		                        'options'   => array(
		                           	array(
		                        		'value' => 'map',
		                        		'label' => 'Campus Map',
		                        	),
		                        	array(
		                        		'value' => 'directions',
		                        		'label' => 'Google Directions',
		                        	),
		                        	array(
		                        		'value' => 'building',
		                        		'label' => 'Building Page',
		                        	),
		                           	array(
		                        		'value' => 'nothing',
		                        		'label' => 'Do Not Link Map',
		                        	),
		                        ),
		                    ),
		                    array(
								'label'  => esc_html__( 'Height', $this->get_shortcode() ),
								'attr'   => 'height',
								'description' => esc_html__( 'Optional.  Defaults to 180px.  Must be a valid css height: px, %, em, etc.' ),
								'type'   => 'text',
							),
						    array(
	                            'label'  => esc_html__( 'Zoom', $this->get_shortcode() ),
	                            'description' => '15-18',
	                            'attr'   => 'zoom',
	                            'type'   => 'number',
	                            'value' => '17',
	                            'meta'   => array(
	                                'step'  => '1',
	                                'min'   => '15',
	                                'max'   => '18',
	                                'size'  => '2',
	                            ),
                        ),
				        )
				    )
				);
			}
		}
	}

	new Building_Map;
