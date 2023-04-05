<?php
    /**
     * The shortcode function as well as registering CSS and shortcode-ui if available.
     *
     * @package WPLocalist
     * @since 1.0.0
     */

    namespace WPLocalist;

    require_once(plugin_dir_path( __FILE__ ) . 'localist-api.php');
    require_once(plugin_dir_path( __FILE__ ) . 'global.php');

    // Register the shortcode
    add_shortcode('wp_localist', 'WPLocalist\shortcode');

    // Register with shortcake(shortcode_ui) if plugin is activated.
    add_action('plugins_loaded', 'WPLocalist\register_with_shortcake', 15,0);

    /**
     * Shortcode Function.
     *
     * @since 1.0.0
     *
     * @param array $atts  {
     *      Optional. The settings for the shortcode instance.
     *
     *      @type int     $max_events       The maximum number of events to display. Default 5. Accepts 1-100.
     *      @type int     $days             Return events within this many days after today.  Default 31.
     *                                      Accepts 1-365
     *                                      boolean.
     *      @type boolean $do_not_show_description Weather to show the event description or not.  Default false.  Accepts
     *                                      boolean.
     *      @type boolean $distinct         Only return the first matching instance of an event when true.
     *                                      Default False. Accepts boolean.
     *      @type int     $group_id         The group id or department group id to return events for.  Will return
     *                                      all group_id if not specified.  Default NULL.  Accepts integer.
     *      @type string  $venue_id         The venue id(s) to return events for. Will return all venue_id if non specified.
     *                                      Default empty string.  Accepts array of integers.
     *      @type string  $events           The type id(s) for event filters to return events for. Will return all types if non specified.
     *                                      Default empty string.  Accepts comma seperated list of integers.
     *      @type string  $departments      The type id(s) for departments to return events for. Will return all types if non specified.
     *                                      Default empty string.  Accepts comma seperated list of integers.
     *      @type string  $audiences        The type id(s) for target audiences to return events for. Will return all types if non specified.
     *                                      Default empty string.  Accepts comma seperated list of integers.
     *      @type string  $keyword          Limit to events with specified keywords or tags. Default empty string.  Accepts a comma
     *                                      seperated string.
     *      @type string  $match            Control matching requirements for venue_id, group_id, type and keyword. Default
     *                                      is to match events that have at least one type, one keyword, one venue_id,
     *                                      and one group_id.  Accepts 'any', all', 'or', or 'at_least_one'.
     * }
     */
    function shortcode( $atts ) {

        $a = shortcode_atts( array(
            'max_events' => 5,
            'style' => 'list',
            'days' => 31,
            'distinct' => false,
            'group_id' => '',
            'venue_id ' => '',
            'events' => '',
            'departments' => '',
            'audiences' => '',
            'keyword' => '',
            'match' => '',
            'columns' => 3,
        ), $atts );

        if(!empty($a['venue_id']) && ($a['venue_id'] != 'all')) {
            $a['venue_id'] = explode(',', $a['venue_id']);
        }

        // The Localist API collaspes all event filters into one argument called type.
        // Doing the same here.
        $a['type'] = array();
        if(!empty($a['departments']) && ($a['departments'] != 'all')) {
            $a['departments'] = explode(',', $a['departments']);
            $a['type'] = array_merge($a['type'],$a['departments']);
        }

        if(!empty($a['audiences']) && ($a['audiences'] != 'all')) {
            $a['audiences'] = explode(',', $a['audiences']);
            $a['type'] = array_merge($a['type'],$a['audiences']);
        }

        if(!empty($a['events']) && ($a['events'] != 'all')) {
            $a['events'] = explode(',', $a['events']);
            $a['type'] = array_merge($a['type'],$a['events']);
        }

        return render_calendar($a);
    }

    /**
     * Shortcake Function.
     *
     * @since 1.0.0
     */
    function register_with_shortcake() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }

        if ( is_plugin_active( 'shortcode-ui/shortcode-ui.php' ) && !WP_LOCALIST_BLOCK_HTTP ) {

            $options = get_plugin_options();

            $api = new Localist_Api( $options['url'] );

            $group_id_data = $api->get_groups();
            $group_id_options = array();
            $group_id_options[] = array(
                'value' => '',
                'label' => '',
            );

            foreach ($group_id_data as $item){
                $option = array();
                $option['value'] = (string) $item->group->id;
                $option['label'] = $item->group->name;
                $group_id_options[] = $option;
            }

            $place_data = $api->get_places();
            $venue_id_options = array();

            foreach ($place_data as $item){
                $option = array();
                $option['value'] = (string) $item->place->id;
                $option['label'] = $item->place->name;
                $venue_id_options[] = $option;
            }

            $department_data = $api->get_departments();
            $event_types_data = $api->get_event_types();
            $target_audiences_data = $api->get_target_audiences();

            shortcode_ui_register_for_shortcode(
                'wp_localist',
                array(
                    'label'         => 'University Calendar',
                    'listItemImage' => 'dashicons-calendar-alt',
                    'attrs'         => array(
                        array(
                            'label'  => esc_html__( 'Number of Results', 'wp_localist' ),
                            'description' => 'Maximum of 100',
                            'attr'   => 'max_events',
                            'type'   => 'number',
                            'value' => '5',
                            'encode' => true,
                            'meta'   => array(
                                'step'  => '1',
                                'min'   => '1',
                                'max'   => '100',
                                'size'  => '2',
                            ),
                        ),
                        array(
                            'label'  => esc_html__( 'Days Ahead', 'wp_localist' ),
                            'description' => 'Maximum of 365',
                            'attr'   => 'days',
                            'type'   => 'number',
                            'value' => '31',
                            'encode' => true,
                            'meta'   => array(
                                'step'  => '1',
                                'min'   => '1',
                                'max'   => '365',
                                'size'  => '3',
                            ),
                        ),
                        array(
                            'label'     => esc_html__( 'Display Style', 'wp_localist' ),
                            'attr'      => 'style',
                            'type'      => 'select',
                            'options'   => array(
                                array( 'value' => 'list', 'label' => esc_html__( 'List', 'wp_localist' ) ),
                                array( 'value' => 'grid', 'label' => esc_html__( 'Grid - Arranged in a Grid', 'wp_localist' ) ),
                            ),
                        ),
                        array(
                            'label'  => esc_html__( 'Number of Columns for the Grid Display', 'wp_localist' ),
                            'attr'   => 'columns',
                            'description' => 'Maximum of 4.  Lists will not use this.',
                            'type'   => 'number',
                            'value' => '3',
                            'encode' => true,
                            'meta'   => array(
                                'step'  => '1',
                                'min'   => '1',
                                'max'   => '4',
                                'size'  => '3',
                            ),
                        ),
                        array(
                            'label'  => esc_html__( 'Include All Matching Instances', 'wp_localist' ),
                            'description' => 'If checked then all instances of recurring events are shown (instead of only the next).',
                            'attr'   => 'distinct',
                            'type'   => 'checkbox',
                        ),
                        array(
                            'label'     => esc_html__( 'Groups', 'wp_localist' ),
                            'attr'      => 'group_id',
                            'type'      => 'select',
                            'options'   => $group_id_options,
                        ),
                        array(
                            'label'     => esc_html__( 'Places', 'wp_localist' ),
                            'attr'      => 'venue_id',
                            'type'      => 'select',
                            'value'      => 'all',
                            'options'   => $venue_id_options,
                            'meta'   => array(
                                'multiple' => 'true',
                                'size' => '10',
                            ),
                        ),
                        array(
                            'label'     => esc_html__( 'Departments', 'wp_localist' ),
                            'attr'      => 'departments',
                            'type'      => 'select',
                            'value'      => 'all',
                            'options'   => get_nested_select_options($department_data),
                            'meta'   => array(
                                'multiple' => 'true',
                                'size' => '10',
                            ),
                        ),
                        array(
                            'label'     => esc_html__( 'Event Types', 'wp_localist' ),
                            'attr'      => 'events',
                            'type'      => 'select',
                            'value'      => 'all',
                            'options'   => get_nested_select_options($event_types_data),
                            'meta'   => array(
                                'multiple' => 'true',
                                'size' => '10',
                            ),
                        ),
                        array(
                            'label'     => esc_html__( 'Target Audiences', 'wp_localist' ),
                            'attr'      => 'audiences',
                            'type'      => 'select',
                            'value'      => 'all',
                            'options'   => get_nested_select_options($target_audiences_data),
                            'meta'   => array(
                                'multiple' => 'true',
                                'size' => '10',
                            ),
                        ),
                        array(
                            'label'  => esc_html__( 'Tags & Keywords', 'wp_localist' ),
                            'attr'   => 'keyword',
                            'type'   => 'text',
                            'encode' => true,
                            'description' => 'Seperate keywords with commas.',
                        ),
                        array(
                            'label'     => esc_html__( 'Content Must Match', 'wp_localist' ),
                            'attr'      => 'match',
                            'type'      => 'select',
                            'options'   => array(
                                array( 'value' => 'at_least_one_keyword', 'label' => esc_html__( 'At least one place, group, keyword or tag, and one filter item', 'wp_localist' ) ),
                                array( 'value' => 'any_keyword', 'label' => esc_html__( 'Any place, group, keyword, tag, or filter item', 'wp_localist' ) ),
                                array( 'value' => 'all_keywords', 'label' => esc_html__( 'At least one place and group, and all keywords, tags, and filter items', 'wp_localist' ) ),
                                array( 'value' => 'or_keywords', 'label' => esc_html__( 'Any place or group, and at least one keyword or tag, and one filter item', 'wp_localist' ) ),
                            ),
                        ),
                    )
                )
            );
        }
    }

    /**
     * Builds the options array for nested types from localist.
     *
     * @since 1.0.0
     *
     * @param array $data the event filters to nest.
     * @return array A multideminsonal array with the value and label to use in the select.
     */
    function get_nested_select_options($data) {

        $options = array();
          
        if(!is_array($data)) {
            $options[] = array(
                'value'=> '', 
                'label' => '' 
            );
            return $options;
        }

        foreach ($data as $item){
            
            if (is_null($item->parent_id)){

                $option = array();
                $option['value'] = (string) $item->id;
                $option['label'] = $item->name;
                $options[] = $option;

                foreach ($data as $child_item) {
                    if ($child_item->parent_id === $item->id){
                        $option = array();
                        $option['value'] = (string) $child_item->id;
                        $option['label'] = '- ' . $child_item->name;
                        $options[] = $option;

                        foreach($data as $t_item) {
                            if ($t_item->parent_id === $child_item->id) {
                                $option = array();
                                $option['value'] = (string) $t_item->id;
                                $option['label'] = '-  ' . $t_item->name;
                                $options[] = $option;
                            }
                        }
                    }
                }

            } elseif ($item->parent_id == 0) {
                $option = array();
                $option['value'] =  (string) $item->id;
                $option['label'] = $item->name;
                $options[] = $option;
            }
        }

        return $options;
    }