<?php
    /**
     * Contains functions used by both the plugin, shortcode, and widget.
     *
     * @package WPLocalist
     * @since 1.0.0
     */
    namespace OUR\LOCALIST;

    /**
     * Enqueue Styles.
     *
     * @since 1.0.0
     */
    function enqueue_global_styles(){
        wp_register_style( 'wp-localist-shortcode-card', plugins_url('our-localist/css/list.css') );
        wp_register_style( 'wp-localist-widget-card', plugins_url('our-localist/css/grid.css') );
    }

    /**
     * Returns an array containing all plugin options for the site/multisite.
     *
     * @since 1.0.0
     *
     * @return array {
     *      @type string     $url   The calendar url for the localist calendar.
     * }
     */
    function get_plugin_options() {
        $options = array();

        $options['url'] = 'http://calendar.ecu.edu';

        return $options;
    }

function render_calendar($instance) {
        wp_enqueue_style('wp-localist-shortcode-card');
        wp_enqueue_style('wp-localist-widget-card');

        $options = get_plugin_options();

        $api = new Localist_Api( $options['url'] );

        $events = $api->get_events($instance);

        switch($instance['style']) {

            default:
            case 'list':
                return render_list_style($events, $instance);
                break;

            case 'grid':
                return render_grid_style($events, $instance);
                break;

        }
    }

    function render_list_style($events, $instance) {

        if (empty($events)) {
            $message = 'No events scheduled.';
            if(is_admin()) {
                $message .= '  Try increasing the number of days ahead to return events for.';
            }
            return $message;
        } else {

            $str = ' <ul id="wp-localist-list">';

            foreach ($events as $i => $data){
                $hours = '';

                if ( $data->event->event_instances[0]->event_instance->all_day ) {
                    $hours = '<time datetime="'. $data->event->event_instances[0]->event_instance->start .'">All Day</time>';
                } else {
                    if( ! empty( $data->event->event_instances[0]->event_instance->start ) ) {

                        $hours = '<time datetime="'. $data->event->event_instances[0]->event_instance->start .'">' . date( 'g:i a', strtotime( substr($data->event->event_instances[0]->event_instance->start, 0, -6) ) ) . '</time>';

                        if ( ! empty( $data->event->event_instances[0]->event_instance->end ) ) {
                            $hours .= ' to <time datetime="'. $data->event->event_instances[0]->event_instance->end .'">' . date( 'g:i a', strtotime( substr($data->event->event_instances[0]->event_instance->end, 0, -6) ) ) . '</time>';
                        }
                    }
                }
                $date = strtotime( substr($data->event->event_instances[0]->event_instance->start, 0, -6) );
                $monthabbr = date( 'M', $date );
                $month = date( 'F', $date );
                $day = date( 'j', $date );

                $str .= '
                    <li>
                        <a href="' . $data->event->localist_url . '">
                        <div class="wp-localist-event">
                          <div id="wp-localist-no-wrap" class="row">
                            <div class="wp-localist-calendar col-xs-3">
                                <div class="wp-localist-month">
                                    <abbr title="' . $month . '">'
                                        . $monthabbr .
                                    '</abbr>
                                </div>
                                <div class="wp-localist-day">' . esc_html($day) . '</div>
                            </div>
                            <div class="wp-localist-event-details col-xs-9">
                                <div class="wp-localist-event-title">' . esc_html($data->event->title) . '</div>';
                                if(!empty($data->event->location)) {
                                    $str .= '<div class="wp-localist-event-location">' . esc_html($data->event->location) . '</div>';
                                }
                            $str .= '
                                <div class="wp-localist-event-time">' . $hours . '</div>
                            </div>
                        </div>
                        </div>
                        </a>
                    </li>
                ';
            }

            return $str . '</ul>';
        }

    }

    /**
     * Outputs the data in the card style.
     *
     * @since 1.0.0
     *
     * @link http://www.localist.com/doc/api#event-json Event Object
     *
     * @param array $events     The events. See Event Object to see data.
     * @param array $instance   The settings from the widget/shortcode.
     * @param string $type      The type of render.  Default is widget.  Accepts either widget or shortcode.
     * @return string The html for the event hours.
     */
    function render_grid_style( $events, $instance ) {

        switch ($instance['columns']) {

            case 1:
                $col = 'col-sm-12 ';
                break;

            case 2:
                $col = 'col-sm-6 ';
                break;

            default:
            case 3:
                $col = 'col-sm-4 ';
                break;

            case 4:
                $col = 'col-sm-3 ';
                break;

        }

        $str = '
        <div id="wp-localist-grid">
            <div class="row">';

        foreach( $events as $i => $data ) {

            $hours = '';
            if ( $data->event->event_instances[0]->event_instance->all_day ) {
                $hours = '<time datetime="'. $data->event->event_instances[0]->event_instance->start .'">All Day</time>';
            } else {
                if( ! empty( $data->event->event_instances[0]->event_instance->start ) ) {
                    $hours = '<time datetime="'. $data->event->event_instances[0]->event_instance->start .'">' . date( 'g:i a', strtotime( substr($data->event->event_instances[0]->event_instance->start, 0, -6) ) ) . '</time>';
                    if ( ! empty( $data->event->event_instances[0]->event_instance->end ) ) {
                        $hours .= ' to <time datetime="'. $data->event->event_instances[0]->event_instance->end .'">' . date( 'g:i a', strtotime( substr($data->event->event_instances[0]->event_instance->end, 0, -6) ) ) . '</time>';
                    }
                }
            }
            $date = strtotime( substr($data->event->event_instances[0]->event_instance->start, 0, -6) );
            $monthabbr = date( 'M', $date );
            $month = date( 'F', $date );
            $day = date( 'j', $date );

            if( $i % $instance['columns'] == 0 && $i != 0 ) {
                $str .= '
                </div>
                <div class="row">';
            }

            //don't show location if event is virtual because localist doesn't clear the field out
            $exp = $data->event->experience;
            if (strtolower($exp) == 'virtual') {
                $location = 'Virtual';
            } else {
                $location = esc_html($data->event->location);
            }

            $str .= '<div class="' . $col . '">
               <a class="wp-localist-url" href="' . esc_url( $data->event->localist_url ) . '" >
                   <article class="wp-localist-event-card">
                        <div class="wp-localist-calendar">
                            <div class="wp-localist-month">
                                <abbr title="' . $month . '">'
                                    . $monthabbr .
                                '</abbr>
                            </div>
                            <div class="wp-localist-day">' . esc_html($day) . '</div>
                        </div>
                        <div class="wp-localist-event-details">
                            <h1 class="wp-localist-event-title">' . esc_html( $data->event->title ) . '</h1>
                            <div class="wp-localist-event-location">' . $location . '</div>
                            <div class="wp-localist-event-time">' . $hours . '</div>
                            <div class="wp-localist-event-description"><p>' .  esc_html( $data->event->description_text ) . '</p></div>
                        </div>
                    </article>
                </a>
            </div>';
        }

        $str .= '
            </div>
        </div>';

        return $str;
    }