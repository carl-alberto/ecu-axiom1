<?php
    /**
     * Localist API wrapper for getting events from a localist calendar based on a provided URL.
     *
     * Used internally by the widget and shortcode, but is designed to be generic.
     *
     * @link http://www.localist.com/doc/api Localist API Documentation
     * @link http://support.localist.com/article/localist-api/ About Localist API
     *
     * @package WPLocalist
     */
    namespace WPLocalist;

    /**
     * Localist API Wrapper.
     *
     * The Localist API is a simple HTTP-based interface. Standard HTTP calls will return JSON.
     * The wrapper will return decoded json your application can use. Caches all api calls for
     * a specified time.  Defaults to 16 mins.  If WP_DEBUG is true then live data will always
     * be returned.
     *
     * @since 1.0.0
     * @author  atwebdev
     */
    class Localist_Api {

        /**
         * The url for the api calls.
         *
         * @since 1.0.0
         * @access protected
         * @var string $url The url for the api calls.
         */
        protected $url = '';

        /**
         * The maximum time in seconds that transients will live.
         *
         * @since 1.0.0
         * @access protected
         * @var string expiration The maximum time in seconds.
         */
        protected $expiration;

        /**
         * Constructor. Defaults maximum time for transients to 16 mins.
         *
         * @since 1.0.0
         * @access public
         *
         * @param string $calendar_url Optional. The calendar URL for the API requests.
         */
        public function __construct( $calendar_url = NULL ) {

            if( isset( $calendar_url ) ) {
                $this->set_api_url( $calendar_url );
            }

            $this->expiration = 16 * MINUTE_IN_SECONDS; // 16 mins cache
    	}

        /**
         * Returns the photo metadata information specified by the id.
         *
         * @since 1.0.0
         * @access public
         *
         * @link http://www.localist.com/doc/api#photo-get Get Photo
         *
         * @param int $id The photo id to return information for.
         * @return false|Object  The photo metadata
         */
        public function get_photo( $id ) {

            $transient = 'wp-localist-photo-' . absint( $id );
            $data = $this->get_transient( $transient );
            $api_url = $this->url . 'photos/' . absint( $id );

            if( WP_DEBUG || false === $data ) {
                if( $result = @file_get_contents( $api_url ) ) {
                    if( $this->api_call_is_successfull( $http_response_header ) ) {
                        $data = json_decode( $result );
                        $this->set_transient( $transient, $data, $this->expiration );
                    }
                } else {
                    $data = false;
                }
            }

            return $data;
        }

        /**
         * Returns an array of event objects based on the parameters.
         *
         * Not all paramaters from the API have been implemented.  Without any parameters, only events occurring today are returned.
         *
         * @since 1.0.0
         * @access public
         *
         * @link http://www.localist.com/doc/api#event-list Event List API
         * @link http://www.localist.com/doc/api#event-json Event Object
         *
         * @param array $parameters {
         *      Optional. An array of paramaters to restrict events on.
         *
         *      @type int     $max_events   Maximum number of events to return. Default is to return all events. Accepts any
         *                                  positive integer.
         *      @type int     $pp           Number of events to return per page. Default 10. Accepts 1-100.
         *      @type int     $days         Return events within this many days after today.  Default 1.
         *                                  Accepts 1-365
         *      @type boolean $distinct     Only return the first matching instance of an event when true.
         *                                  Default False. Accepts Boolean.
         *      @type int     $group_id     The group id or department group id to return events for.  Will return
         *                                  all groups if not specified.  Default NULL.  Accepts integer.
         *      @type array   $venue_id     The venue id(s) to return events for. Will return all places if not specified.
         *                                  Default NULL.  Accepts array of integers.
         *      @type array   $type         The type id(s) to return events for. Will return all types if not specified.
         *                                  Default NULL.  Accepts array of integers.
         *      @type string  $keyword      Limit to events with specified keywords or tags. Default NULL.  Accepts a comma
         *                                  seperated string.
         *      @type string  $match        Control matching requirements for venue_id, group_id, type and keyword. Default
         *                                  is to match events that have at least one type, one keyword, one venue_id,
         *                                  and one group_id.  Accepts 'any', all', 'or', or 'at_least_one'.
         * }
         * @return array  The array of event objects.
         */
        public function get_events( $parameters ) {

            $api_url = $this->url . 'events';

            if( ! empty( $parameters['max_events'] ) ) {
                $max_events = absint($parameters['max_events']);
                /*
                 * If maximum number of events is 100 or less then set events per page to max_events so only
                 * first page needs to be returned.
                 */
                if($max_events <= 100) {
                    $parameters['pp'] = $max_events;
                }
            }

            $query = array();
            if( ! empty( $parameters['pp'] ) ) {
                $query[] = 'pp=' . absint( $parameters['pp'] );
            }

            if( ! empty( $parameters['days'] ) ) {
                $query[] = 'days=' . absint( $parameters['days'] );
            }

            if( isset( $parameters['distinct'] ) ) {
                $bool = ( ! $parameters['distinct'] ) ? 'true' : 'false';
                $query[] = 'distinct=' . $bool;
            }

            if( ! empty( $parameters['group_id'] ) ) {
                $query[] = 'group_id=' . absint( $parameters['group_id'] );
            }

            if( ! empty( $parameters['venue_id'] ) ) {
                foreach( $parameters['venue_id'] as $filter ) {
                    $query[] = 'venue_id[]=' . absint( $filter );
                }
            }

            if( ! empty( $parameters['type'] ) ) {
                if(!is_array($parameters['type'])) {
                    $parameters['type'] = explode( ',' , $parameters['type'] );
                }
                foreach( $parameters['type'] as $filter ) {
                    $query[] = 'type[]=' . absint( $filter );
                }
            }

            if( ! empty( $parameters['keyword'] ) ){
                $keywords = explode( ',', $parameters['keyword'] );
                foreach( $keywords as $keyword ) {
                    $query[] = 'keyword[]=' . sanitize_text_field( trim( $keyword ) );
                }
            }

            if( ! empty( $parameters['match'] ) ) {
                switch( $parameters['match'] ) {

                    case 'any':
                        $query[] = 'match=any';
                        break;

                    case 'all':
                        $query[] = 'match=all';
                        break;

                    case 'or':
                        $query[] = 'match=or';
                        break;

                    default:
                    case 'at_least_one':
                        // default not specified
                        break;
                }
            }

            if( ! empty( $query ) ) {
                $api_url .= '?' . implode( '&', $query );
            }

            /*
             * Transients are limitied to 45 characters for non multisite and 40 characters for multisite.
             * So so have to create a hash of the url.  md5 returns a 32-character hexadecimal number.
             */
            $transient = 'wp-loc-' . md5( $api_url ); //40 characters

            if ( WP_DEBUG ) {
                var_dump( $api_url );
            }

            $events = $this->get_transient( $transient );

            if( WP_DEBUG || false === $events ) {

                $events = array();
                $i = 1;
                $count = 0;
                do {
                    $api_url .= '&page=' . $i;
                    if($result = @file_get_contents( $api_url ) ) {
                        if( $this->api_call_is_successfull( $http_response_header ) ) {
                            $page = json_decode( $result );
                            $events = array_merge( $events, $page->events );
                        }
                        // Keep track of how may events have been retrieved.
                        $count += $page->page->size;

                        // If the max events has been met then break out of loop.
                        if(isset($max_events) && ($count >= $max_events)) {
                            break;
                        }
                        $i++;
                    } else {
                        break;
                    }
                } while( $page->page->total != $page->page->current );

                // size events to return to max events.
                if(isset($max_events) && (count($events) > $max_events)) {
                    $events = array_slice($events, 0 , $max_events);
                }

                if( ! empty( $events ) ) {
                    $this->set_transient( $transient, $events, $this->expiration );
                }
            }

            return $events;
        }

        /**
         * Returns an array of place objects available on a calendar.
         *
         * The API parameters to limit what is returned has not been implemented yet.
         *
         * @since 1.0.0
         * @access public
         *
         * @link http://www.localist.com/doc/api#place-list Places List Call
         * @link http://www.localist.com/doc/api#place-json Place Object
         * @return array  The array of place objects.
         */
        public function get_places() {

            $places = $this->get_transient( 'wp-localist-places' );

            if( WP_DEBUG || false === $places ) {

                $places = array();
                $i = 1;

                do {
                    $api_url = $this->url . 'places?pp=100&page=' . $i;
                    if( $result = @file_get_contents( $api_url ) ) {
                        if( $this->api_call_is_successfull( $http_response_header ) ) {
                            $page = json_decode( $result );
                            $places = array_merge( $places, $page->places );
                        }
                        $i++;
                    } else {
                        break;
                    }
                } while( $page->page->total != $page->page->current );

                if( ! empty( $places ) ) {
                    $this->set_transient( 'wp-localist-places', $places, $this->expiration );
                }
            }

            return $places;
        }

        /**
         * Returns an array of all group objects available on a calendar.
         *
         * The API parameters to limit what is returned has not been implemented yet.
         *
         * @since 1.0.0
         * @access public
         *
         * @link http://www.localist.com/doc/api#group-list Groups List Call
         * @link http://www.localist.com/doc/api#group-json Group Object
         * @return array  The array of group objects.
         */
        public function get_groups() {

            $groups = $this->get_transient( 'wp-localist-groups' );

            if( WP_DEBUG || false === $groups ) {

                $groups = array();
                $i = 1;

                do {
                    $api_url = $this->url . 'groups?pp=100&page=' . $i;
                    if( $result = @file_get_contents( $api_url ) ) {

                        if( $this->api_call_is_successfull( $http_response_header ) ) {
                            $page = json_decode( $result );
                            $groups = array_merge( $groups, $page->groups );
                        }
                        $i++;
                    } else {
                        break;
                    }
                } while( $page->page->total != $page->page->current );

                if( ! empty( $groups ) ) {
                    $this->set_transient( 'wp-localist-groups', $groups, $this->expiration );
                }
            }

            return $groups;
        }

        /**
         * Returns an array of event filter objects. The ID can be used in the type parameter to /events.
         *
         * The parent_id attribute represents the parent filter item, if a child filter.
         *
         * @since 1.0.0
         * @access public
         *
         * @link http://www.localist.com/doc/api#event-filters Event Filter Call
         * @return array  The array of filter objects.
         */
        public function get_event_filters() {

            $event_filters = $this->get_transient( 'wp-localist-event-filters' );

            if( WP_DEBUG || false === $event_filters ) {
                $api_url = $this->url . 'events/filters';
                if( $result = @file_get_contents( $api_url ) ) {
                    if( $this->api_call_is_successfull( $http_response_header ) ) {
                        $event_filters = json_decode( $result );
                        if( ! empty( $event_filters ) ) {
                            $this->set_transient( 'wp-localist-event-filters', $event_filters, $this->expiration );
                        }
                    }
                } else {
                   $event_filters = false;
                }
            }

            return $event_filters;
        }

        /**
         * Returns an array of event types objects from the event filter items.
         *
         * @since 1.0.0
         * @access public
         *
         * @link http://www.localist.com/doc/api#event-filters Event Filter Call
         * @return array  The array of event type objects.
         */
        public function get_event_types( $short_by_name = true ) {

            $event_types = $this->get_transient( 'wp-localist-event-types' );

            if( WP_DEBUG || false === $event_types ) {
                if( $event_filters = $this->get_event_filters() ) {
                    if ( ! empty( $event_filters->event_types ) ) {
                        $event_types = $event_filters->event_types;
                        $this->set_transient( 'wp-localist-event-types', $event_types, $this->expiration );
                    }
                }
            }

            if( ! is_array( $event_types ) ) {
                $event_types = array();
            }

            if( $short_by_name ) {
                usort( $event_types, array( $this, 'sort_by_name' ) );
            }

            return $event_types;
        }

        /**
         * Returns an array of department objects from the event filter items.
         *
         * @since 1.0.0
         * @access public
         *
         * @link http://www.localist.com/doc/api#event-filters Event Filter Call
         * @return array  The array of department objects.
         */
        public function get_departments( $short_by_name = true ) {

            $departments = $this->get_transient( 'wp-localist-departments' );

            if( WP_DEBUG || false === $departments ) {
                if( $event_filters = $this->get_event_filters() ) {
                    if( ! empty( $event_filters->departments ) ) {
                        $departments = $event_filters->departments;
                        $this->set_transient( 'wp-localist-departments', $departments, $this->expiration );
                    }
                }
            }

            if ( ! is_array( $departments ) ) {
                $departments = array();
            }

            if( $short_by_name ) {
                usort( $departments, array( $this, 'sort_by_name' ) );
            }
          
            return $departments;
        }

        /**
         * Returns an array of target audience objects from the event filter items.
         *
         * @since 1.0.0
         * @access public
         *
         * @link http://www.localist.com/doc/api#event-filters Event Filter Call
         * @return array  The array of department objects.
         */
        public function get_target_audiences( $short_by_name = true ) {

            $target_audiences = $this->get_transient( 'wp-localist-target-audiences' );

            if( WP_DEBUG || false === $target_audiences ) {
                if( $event_filters = $this->get_event_filters() ) {
                    if( ! empty( $event_filters->target_audience ) ) {
                        $target_audiences = $event_filters->target_audience;
                        $this->set_transient( 'wp-localist-target-audiences', $target_audiences, $this->expiration );
                    }
                }
            }

            if ( ! is_array( $target_audiences ) ) {
                $target_audiences = array();
            }

            if( $short_by_name ) {
                usort( $target_audiences, array( $this, 'sort_by_name' ) );
            }

            return $target_audiences;
        }

        /**
         * This is the callback function used to sort Localist event filters by name.
         *
         * @since 1.0.0
         * @access protected
         *
         * @link http://www.localist.com/doc/api#event-filters
         *
         * @param Object $a Localist Event Filter Object
         * @param Object $b Localist Event Filter Object
         * @return Returns < 0 if $a->name is less than $b->name; > 0 if $a->name is greater than
         * $b->name, and 0 if they are equal.
         */
        protected function sort_by_name( $a, $b ) {
            return strcmp( $a->name, $b->name );
        }

        /**
         * Sets the transient with the approriate set function based on if multisite is enabled.
         *
         * @since 1.0.0
         * @access protected
         *
         * @link https://codex.wordpress.org/Transients_API Transient API
         *
         * @param string    $transient  A 40 character unique identifier for your cached data.
         * @param mixed     $data       Data to save, either a regular variable or an array/object.
         * @param int       $seconds    The maximum of seconds to keep the data before refreshing.
         * @return boolean False if value was not set and true if value was set.
         */
        protected function set_transient( $transient, $data, $seconds ) {
            if( is_multisite() ) {
                return set_site_transient( $transient, $data, $seconds );
            } else {
                return set_transient( $transient, $data, $seconds );
            }
        }

        /**
         * Gets the transient with the approriate get function based on if multisite is enabled.
         *
         * @since 1.0.0
         * @access protected
         *
         * @link https://codex.wordpress.org/Transients_API Transient API
         *
         * @param string $transient  A 40 character unique identifier for your cached data.
         * @return mixed Value of transient. If the transient does not exist, does not have a value,
         * or has expired, then get_transient will return false.
         */
        protected function get_transient( $transient ) {
            if( is_multisite() ) {
                return get_site_transient( $transient );
            } else {
                return get_transient( $transient );
            }
        }

        /**
         * Checks if localist returned a successfull response code.
         *
         * @since 1.0.0
         * @access protected
         *
         * @link http://www.localist.com/doc/api#response-codes Response Codes
         *
         * @return boolean True if succssfull, false otherwise.
         */
        protected function api_call_is_successfull( $header ) {
            if( false !== strpos( $header[0], '200' )) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Returns the expiration in seconds for the transients.
         *
         * @since 1.0.0
         * @access public
         *
         * @return int The maximum time in seconds the transients will be live.
         */
        public function get_expiration() {
            return $this->expiration;
        }

        /**
         * Sets the the expiration for the transients.
         *
         * @param int $seconds The maximum time in seconds the transients will be kept.
         */
        public function set_expiration( $seconds ) {
            $this->expiration = absint( $seconds );
        }

        /**
         * Returns the url for the API requests.
         *
         * @since 1.0.0
         * @access public
         *
         * @return string The url used for the API requests.
         */
        public function get_url() {
            return $this->url;
        }

        /**
         * Sets the url for the API requests.
         *
         * Example:  http://calendar.example.edu/api/2/
         *
         * @since 1.0.0
         * @access public
         *
         * @link http://www.localist.com/doc/api#usage API URL
         *
         * @param string $api_url The calendar URL for the API requests.
         */
        public function set_url( $api_url ) {
            $this->url = esc_url_raw( $api_url );
        }

        /**
         * Sets the url for the API requests.  Will set the the url to be the current version
         * based on the calendar url.
         *
         * Example:  http://calendar.ecu.edu => http://calendar.ecu.edu/api/2/
         *
         * @since 1.0.0
         * @access public
         *
         * @link http://www.localist.com/doc/api#usage API URL
         *
         * @param string $calendar_url The calendar URL for the API requests.
         */
        public function set_api_url( $calendar_url ) {
            $this->url = rtrim( esc_url_raw( $calendar_url ), "/" ) . '/api/2/';
        }
    }