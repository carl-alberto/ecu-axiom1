<?php
    /**
     * Localist widget.
     *
     * @package WPLocalist
     */

    require_once( plugin_dir_path( __FILE__ ) . 'localist-api.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'global.php' );

    // Register the widget
    add_action( 'widgets_init', function(){
        register_widget( 'WP_Localist_Widget' );
    },10,0);

    /**
     * Widget class.
     *
     * @since 1.0.0
     *
     * @author  atwebdev
     */
    class WP_Localist_Widget extends WP_Widget {

        /**
         * Localist API that provides data for events.
         *
         * @since 1.0.0
         * @access private
         * @see WPLocalist\Localist_Api
         * @var object $api api object.
         */
        private $api;

        /**
         * Constructor. Sets up and creates the widget with appropriate settings.
         *
         * @since 1.0.0
         * @access public
         */
        public function __construct() {

           $widget_ops = array(
                'classname'   => 'wp-localist',
                'description' => __( 'Place an localist calendar into a widgetized area.', 'wp-localist' )
            );

            $options = WPLocalist\get_plugin_options();

            if(!WP_LOCALIST_BLOCK_HTTP){
                $this->api = new WPLocalist\Localist_Api( $options['url'] );
            }

            parent::__construct( 'wp-localist', 'WP Localist Widget', $widget_ops );
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

            // Get Data
            $title = apply_filters( 'widget_title', esc_html( $instance['title'] ) );

            // Output Widget
            echo $args['before_widget'];

            if ( ! empty( $title ) ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            if(!WP_LOCALIST_BLOCK_HTTP){
                $instance['style'] = 'list';
                echo WPLocalist\render_calendar($instance);
            } else {
                echo "<p>Localist is currently under maintenance and will be back up shortly.</p>";
            }
            

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
            $instance['keyword'] = sanitize_text_field( $new_instance['keyword'] );

            if( ( $new_instance['days'] > 0 ) && ( $new_instance['days'] <= 365 ) ) {
                $instance['days'] = absint( $new_instance['days'] );
            }

            if( ( $new_instance['max_events'] > 0 ) && ( $new_instance['max_events'] <= 100 ) ) {
                $instance['max_events'] = absint( $new_instance['max_events'] );
            }

            $instance['distinct'] = ( bool ) $new_instance['distinct'];

            $instance['group_id'] = absint( $new_instance['group_id'] );

            $instance['venue_id'] = array_map( 'absint', $new_instance['venue_id'] );
            $instance['type'] = array_map( 'absint', $new_instance['type'] );
            $instance['match'] = sanitize_text_field( $new_instance['match'] );

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
         *      @type int     $max_events       Number of events to return per page. Default 5. Accepts 1-100.
         *      @type int     $days             Return events within this many days after today.  Default 31.
         *                                      Accepts 1-365
         *      @type boolean $distinct         Only return the first matching instance of an event when true.
         *                                      Default False. Accepts boolean.
         *      @type int     $group_id         The group id or department group id to return events for.  Will return
         *                                      all groups if not specified.  Default NULL.  Accepts integer.
         *      @type array   $venue_id         The venue id(s) to return events for. Will return all places if non specified.
         *                                      Default empty array.  Accepts array of integers.
         *      @type array   $type             The type id(s) to return events for. Will return all types if non specified.
         *                                      Default empty array.  Accepts array of integers.
         *      @type string  $keyword          Limit to events with specified keywords or tags. Default empty string.  Accepts a comma
         *                                      seperated string.
         *      @type string  $match            Control matching requirements for venue_id, group_id, type and keyword. Default
         *                                      is to match events that have at least one type, one keyword, one venue_id,
         *                                      and one group_id.  Accepts 'any', all', 'or', or 'at_least_one'.
         * }
         */
        public function form( $instance ) {

            // Set form values
            if( isset( $instance['title'] ) ) {
                $title = $instance['title'];
            } else {
                $title = '';
            }

            if( isset( $instance['max_events'] ) ) {
                $max_events = $instance['max_events'];
            } else {
                $max_events = 5;
            }

            if( isset( $instance['days'] ) ) {
                $days = $instance['days'];
            } else {
                $days = 31;
            }

            if( isset( $instance['distinct'] ) ) {
                $distinct = ( bool ) $instance['distinct'];
            } else {
                $distinct = false;
            }

            if( isset( $instance['group_id'] ) ) {
                $group_id = $instance['group_id'];
            } else {
                $group_id = '';
            }

            if( isset( $instance['venue_id'] ) ) {
                $venue_id = $instance['venue_id'];
            } else {
                $venue_id = array();
            }

            if( isset( $instance['type'] ) ) {
                $type = $instance['type'];
            } else {
                $type = array();
            }


            if( isset( $instance['keyword'] ) ) {
                $keyword = $instance['keyword'];
            } else {
                $keyword = '';
            }

            if( isset( $instance['match'] ) ) {
                $match = $instance['match'];
            } else {
                $match = '';
            }

            // Start Form

            // Displays alert stating that Localist is under maintenance is API calls are down
            if(WP_LOCALIST_BLOCK_HTTP){
                echo '<div style="color: #721c24; background-color: #f8d7da; position: relative; padding: .75rem 1.25rem; margin: 1rem 0; border-radius: .25rem;" role="alert">
                Localist is currently under maintenance and will be back up shortly.
              </div>';
            }

            // Basic Options
            echo '<h4>Basic Options</h4>';

            // title
            echo '<p>';
            echo '<label for="' . $this->get_field_name( 'title' ) . '">Title:</label>';
            echo '<input id="' . $this->get_field_id( 'title' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'title' ) . '" value="' . esc_attr( $title ) . '" ">';
            echo '</p>';

            // Number of Results ( default 5 )
            // Maximum of 50
            echo '<p>';
            echo '<label for="' . $this->get_field_name( 'max_events' ) . '">Number of Results:</label>';
            echo '<input id="' . $this->get_field_id( 'max_events' ) . '" class="widefat" type="number" step="1" min="1" max="50" size="2" value="' . esc_attr( $max_events ) . '" name="' . $this->get_field_name( 'max_events' ) . '">';
            echo '<br />Maximum of 50';
            echo '</p>';

            // Days Ahead ( default 31, min 1, max 365)
            echo '<p>';
            echo '<label for="' . $this->get_field_name( 'days' ) . '">Days Ahead:</label>';
            echo '<input id="' . $this->get_field_id( 'days' ) . '" class="widefat" type="number" step="1" min="1" max="365" size="3" value="' . esc_attr( $days ) . '" name="' . $this->get_field_name( 'days' ) . '" >';
            echo '<br />Maximum of 365';
            echo '</p>';

            // Include All Matching Instances ( default unchecked )
            // Show all instances of recurring events (instead of only the next)
            echo '<p>';
            echo '<input id="' . $this->get_field_id( 'distinct' ) . '" value="1" type="checkbox" class="checkbox" name="' . $this->get_field_name( 'distinct' ) . '" ' . checked( $distinct, true, false ) . '>';
            echo '<label for="' . $this->get_field_name( 'distinct' ) . '">Include All Matching Instances</label>';
            echo '<br />If checked then all instances of recurring events are shown (instead of only the next).';
            echo '</p>';

            // Prevents API calls is DISABLE API CALLS is false
            if(!WP_LOCALIST_BLOCK_HTTP){
                // Filters
                echo '<h4>Filters</h4>';

                // Group ( single select )
            
                $groups_data = $this->api->get_groups();
                echo '<p>';
                echo '<label for="' . $this->get_field_name( 'group_id' ) . '">Groups:</label>';
                echo '<select id="' . $this->get_field_id( 'group_id' ) . '"  class="widefat" name="' . $this->get_field_name( 'group_id' ) . '">';
                echo '<option value=""></option>';
                foreach ( $groups_data as $data ) {
                    echo '<option value="' . $data->group->id . '" ' . selected( $group_id, $data->group->id, false ) . '>' . $data->group->name . '</option>';
                }
                echo '</select>';
                echo '</p>';

                // Places (  Multi-Select )
                $place_data = $this->api->get_places();
                echo '<p>';
                echo '<label for="' . $this->get_field_name( 'venue_id' ) . '">Places:</label>';
                echo '<select id="' . $this->get_field_id( 'venue_id' ) . '"  class="widefat" name="' . $this->get_field_name( 'venue_id' ) . '[]" multiple>';
                foreach ( $place_data as $data ) {
                    echo '<option value="' . $data->place->id . '"  ' . selected( true, in_array( $data->place->id , $venue_id ), false ) . '>' . $data->place->display_name . '</option>';
                }
                echo '</select>';
                echo '</p>';


                // Department ( Multi-Select )
                $department_data = $this->api->get_departments();
                echo '<p>';
                echo '<label for="' . $this->get_field_name( 'departments' ) . '">Departments:</label>';
                echo $this->multiSelectNested( 'departments', $department_data, $type );
                echo '</p>';


                // Event Type ( Multi-Select )
                $event_types_data = $this->api->get_event_types();
                echo '<p>';
                echo '<label for="' . $this->get_field_name( 'event-types' ) . '">Event Types:</label>';
                echo $this->multiSelectNested( 'event-types', $event_types_data, $type );
                echo '</p>';


                // Target Audience ( Multi-Select )
                $target_audiences_data = $this->api->get_target_audiences();
                echo '<p>';
                echo '<label for="' . $this->get_field_name( 'target-audiences' ) . '">Target Audiences:</label>';
                echo $this->multiSelectNested( 'target-audiences', $target_audiences_data, $type );
                echo '</p>';
            }

            // Tags & Keywords ( text Field)
            // Seperate Keywords with commas
            echo '<p>';
            echo '<label for="' . $this->get_field_name( 'keyword' ) . '">Tags & Keywords:</label>';
            echo '<input id="' . $this->get_field_id( 'keyword' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'keyword' ) . '" value="' . esc_attr( $keyword ) . '" ">';
            echo 'Seperate keywords with commas.';
            echo '</p>';

            // Content Must Match  ( Default )
            echo '<p>';
            echo '<label for="' . $this->get_field_name( 'match' ) . '">Content Must Match:</label>';
            echo '<select id="' . $this->get_field_id( 'match' ) . '"  class="widefat" name="' . $this->get_field_name( 'match' ) . '">';
            echo '<option value="at_least_one_keyword" ' . selected( $match, 'at_least_one_keyword', false ) . '>At least one place, group, keyword or tag, and one filter item</option>';
            echo '<option value="any_keyword" ' . selected( $match, 'any_keyword', false ) . '>Any place, group, keyword, tag, or filter item</option>';
            echo '<option value="all_keywords" ' . selected( $match, 'all_keywords', false ) . '>At least one place and group, and all keywords, keyword, and filter items</option>';
            echo '<option value="or_keywords" ' . selected( $match, 'or_keywords', false ) . '>Any place or group, and at least one keyword or tag, and one filter item</option>';
            echo '</select>';
            echo '</p>';
        }

        /**
         * Returns the html for a nested type select.
         *
         * @since 1.0.0
         * @access protected
         *
         * @link http://www.localist.com/doc/api#filter-parameters Filter Parameters
         * @return string The html for a nested type select.
         */
        protected function multiSelectNested( $identifier, $data, $values ) {
            $html = '<select id="' . $this->get_field_id( $identifier ) . '"  class="widefat" name="' . $this->get_field_name( 'type' ) . '[]" multiple>';
            foreach ( $data as $option ) {
                if (is_null( $option->parent_id ) ) {
                    $html .= '<option value="' . $option->id . '" ' . selected( true, in_array( $option->id, $values ), false ) . '>' . $option->name . '</option>';
                    foreach ( $data as $childoption ) {
                        if ( $childoption->parent_id === $option->id ) {
                           $html .= '<option value="' . $childoption->id . '" ' . selected( true, in_array( $option->id, $values ), false ) . '>&nbsp;&nbsp;&nbsp;&nbsp;' . $childoption->name . '</option>';
                           foreach($data as $t_item) {
                                if ($t_item->parent_id === $childoption->id) {
                                    $html .= '<option value="' . $t_item->id . '" ' . selected( true, in_array( $option->id, $values ), false ) . '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $t_item->name . '</option>';
                                }
                            }
                        }
                    }
                } elseif ( $option->parent_id == 0 ) {
                    $html .= '<option value="' . $option->id . '" ' . selected( true, in_array( $option->id, $values ), false ) . '>' . $option->name . '</option>';
                }
            }

            $html .= '</select>';

            return $html;
        }
    }