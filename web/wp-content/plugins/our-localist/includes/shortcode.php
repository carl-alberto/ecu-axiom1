<?php
    /**
     * The shortcode function as well as registering CSS and shortcode-ui if available.
     *
     * @package WPLocalist
     * @since 1.0.0
     */

    namespace OUR\LOCALIST;

    require_once(plugin_dir_path( __FILE__ ) . 'localist-api.php');
    require_once(plugin_dir_path( __FILE__ ) . 'global.php');

    // Register the shortcode
    add_shortcode('wp_localist', __NAMESPACE__ . '\shortcode');

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