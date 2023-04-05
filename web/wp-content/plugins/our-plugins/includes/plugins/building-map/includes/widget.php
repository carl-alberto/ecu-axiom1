<?php
/**
 * BuildingMap widget.
 *
 * @package buildingmap
 */
namespace OUR_PLUGINS;

// Register the widget
add_action( 'widgets_init', function(){
	register_widget( __NAMESPACE__ . '\WP_BuildingMap_Widget' );
});

	/**
	 * Widget class.
	 *
	 * @since 1.0.0
	 *
	 * @author  atwebdev
	 */
	class WP_BuildingMap_Widget extends \WP_Widget {

		/**
		 * The DB handler for the homepage_tools database.  Use the get function
		 * to get a singleton instance.
		 *
		 * @link https://codex.wordpress.org/Class_Reference/wpdb WPDB API
		 *
		 * @var Object wpdb object connected to the tools db.
		 */
		// private static $tools_db;

		/**
		 * Constructor. Sets up and creates the widget with appropriate settings.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function __construct() {

			$widget_ops = array(
					'classname'   => 'wp-buildingmap',
					'description' => __( 'Place building map into a widgetized area.', 'wp-buildingmap' )
			);

			if ( !is_admin() ) {
				add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
			}

			parent::__construct( 'wp-buildingmap', 'ECU Building Map');
		}

		/**
	     * Enqueueues the necessary CSS and JS
		 */
		public function wp_enqueue_scripts() {
			wp_register_script('ecu-building-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDcdtCHhQ34IxoOlSBw_GnQwiG6JfcP2KM');
		}

		/**
		 * Outputs the widget within the widgetized area.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $args     The default widget arguments.
		 * @param array $instance The settings for the current widget instance.  See the form function for details.
		 */
		public function widget( $args, $instance ) {
			wp_enqueue_script('ecu-building-map');
			// Get Data
			$title = apply_filters( 'widget_title', esc_html( $instance['title'] ) );

			$options = array();
			$options['item'] = $instance['item'];
			$options['link'] = $instance['link'];
			if (!empty($instance['height'])) {
				$options['height'] = $instance['height'];
			} else {
				$options['height'] = '180px;';
			}

			$options['zoom'] = $instance['zoom'];
			$options['div_id'] = $this->id;

			// Output Widget
			echo $args['before_widget'];

			echo '<div class="ecu-buildingmap">';

			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			echo render_map($options);

			echo '</div>';

			echo $args['after_widget'];
		}

		/**
		 * Processing widget options on save
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 */
		public function update( $new_instance, $old_instance ) {

			// Set $instance to the old instance in case no new settings have been updated for a particular field.
			$instance = $old_instance;

			// Sanitize user inputs.
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
			$instance['item'] = sanitize_text_field( $new_instance['item'] );
			$instance['link'] = sanitize_text_field( $new_instance['link'] );
			$instance['height'] = sanitize_text_field( $new_instance['height'] );
			$instance['zoom'] = absint( $new_instance['zoom'] );

			return $instance;
		}

		/**
		 * Outputs the widget form where the user can specify settings.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $instance  {
		 *      Optional. The settings for the widget instance.
		 *
		 *      @type string  $image       	  The profile image ID
		 *      @type string  $title          The title of the widget
		 *      @type string  $pirate_id      The pirate ID to look up.
		 *      @type string  $hide_title     Should the job title be hidden
		 *      @type string  $hide_email     Should the email address be hidden
		 *      @type string  $hide_phone     Should the phone number be hidden
		 *      @type string  $hide_mailstop  Should the mailstop be hidden
		 *      @type string  $hide_bldg      Should the building be hidden
		 *      @type string  $hide_dept      Should the department be hidden
		 *      @type string  $alt_title      An alternate job title to be used
		 *      @type string  $notes	      Additional notes to be output
		 * }
		 */
		public function form( $instance ) {

			// Set form values
			if( isset( $instance['title'] ) ) {
				$title = $instance['title'];
			} else {
				$title = '';
			}
			if( isset( $instance['item'] ) ) {
				$item= $instance['item'];
			} else {
				$item = '';
			}
			if( isset( $instance['link'] ) ) {
				$link = $instance['link'];
			} else {
				$link = 'map';
			}
			if( isset( $instance['height'] ) ) {
				$height = $instance['height'];
			} else {
				$height = '';
			}
			if( isset( $instance['zoom'] ) ) {
				$zoom = $instance['zoom'];
			} else {
				$zoom = '17';
			}

			// Only get buildings with shapes
			$results = \Database\Tools::query( "
				SELECT university_buildings.name, maps_items.id
				FROM homepage_tools.university_buildings
				LEFT JOIN homepage_tools.maps_items
					ON maps_items.child_id = university_buildings.id
				LEFT JOIN homepage_tools.maps_shapes
					ON maps_shapes.item_id = maps_items.id
				LEFT JOIN homepage_tools.maps_points
					ON maps_points.shape_id = maps_shapes.id
				LEFT JOIN homepage_tools.maps_layers_items
					ON maps_layers_items.item_id = maps_items.id
				WHERE maps_layers_items.layer_id = 1
					AND maps_points.id IS NOT NULL
				GROUP BY university_buildings.id
				ORDER BY university_buildings.name
			");

			$options = array();
			if($results){
				foreach($results as $bldg) {
					$options[] = array(
							'value' => $bldg->id,
							'label' => $bldg->name,
					);
				}
			}

			// Get the list of pages on this site
			$pages = get_pages();

			// Start Form
			echo '<div class="ecu-buildingmap"><p class="description">Shows a google map centered on the selected building.   The map can link to the building on ECU\'s campus maps, the building information page, or not be linked at all.</p>';

			// Basic Options
			echo '<h4>Basic Options</h4>';

			// title
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'title' ) . '">Widget Title:</label>';
			echo '<input id="' . $this->get_field_id( 'title' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'title' ) . '" value="' . esc_attr($title) . '">';
			echo '</p>';

			// Building
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'item' ) . '">Building:</label>';
			echo '<div class="widefat ecu-buildingmap-building-div" >';
			echo '<select id="' . $this->get_field_id( 'item' ) . '" class="widefat ecu-buildingmap-building" name="' . $this->get_field_name( 'item' ) . '">';
			// Prepare the options for the select box
			foreach ($options as $option) {
				echo '<option value="' . $option['value'] . '" ';
				if ($item == $option['value']) {
					echo ' selected';
				}
				echo '>' . $option['label'] . '</option>';
			}
			echo '</select>';
			echo '</div>';
			echo '</p>';

			// Link Map
			echo '<p>';
			echo '<label for="' . $this->get_field_name( 'link' ) . '">Link Map:</label>';
			echo '<select id="' . $this->get_field_id( 'link' ) . '" class="widefat" name="' . $this->get_field_name( 'link' ) . '">';
			echo '<option value="map"';
			if (esc_attr($link) == 'map') {
				echo ' selected ';
			}
			echo '>Campus Map</option>';
			echo '<option value="directions"';
			if (esc_attr($link) == 'directions') {
				echo ' selected ';
			}
			echo '>Google Directions</option>';
			echo '<option value="building"';
			if (esc_attr($link) == 'building') {
				echo ' selected ';
			}
			echo '>Building Page</option>';
			echo '<option value="nothing"';
			if (esc_attr($link) == 'nothing') {
				echo ' selected ';
			}
			echo '>Do Not Link Map</option>';
			echo '</select>';
			echo '</p>';

			//hieght
            echo '<p>';
            echo '<label for="' . $this->get_field_name( 'height' ) . '">Height:</label>';
            echo '<input id="' . $this->get_field_id( 'height' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'height' ) . '" value="' . esc_attr( $height ) . '">';
            echo 'Optional.  Defaults to 180px.  Must be a valid css height: px, %, em, etc.';
            echo '</p>';

			// Zoom
            echo '<p>';
            echo '<label for="' . $this->get_field_name( 'zoom' ) . '">Zoom:</label>';
            echo '<input id="' . $this->get_field_id( 'zoom' ) . '" class="widefat" type="number" step="1" min="15" max="18" size="2" value="' . esc_attr( $zoom ) . '" name="' . $this->get_field_name( 'zoom' ) . '">';
            echo '<br />15-18';
            echo '</p>';
			echo '</div>';
		}
	}
