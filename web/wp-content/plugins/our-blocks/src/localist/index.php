<?php defined( 'ABSPATH' ) || exit;

/**
 * Register dynamic callback for block
 * 
 * @return null
 */
add_action( 'plugins_loaded', function() {
    register_block_type( 'wp-blocks/localist', [
        'render_callback' => 'block_localist'
    ]);
});

/**
 * Include Localist API
 */
require_once( 'localist-api.php' );

/**
 * Renders markup for block
 * 
 * @return string The HTML for the block
 */
function block_localist( $attributes ) {

    // Initialize standard parameters
    $title = $attributes['title'] ? sanitize_text_field( $attributes['title'] ) : false;
    $column_count = $attributes['columns'] ? absint( $attributes['columns'] ) : 3;
    $hideDescriptions = wp_validate_boolean( $attributes['hideDescriptions'] ); 

    // Initialize query parameters
    $parameters = [
        'events'        =>  validate_param( $attributes['events'] ),
        'audiences'     =>  validate_param( $attributes['audiences'] ),
        'venue_id'      =>  validate_param( $attributes['venue_id'] ),
        'departments'   =>  validate_param( $attributes['departments'] ),
        'distinct'      =>  wp_validate_boolean( $attributes['distinct'] ),
        'group_id'      =>  $attributes['group_id'] ? absint( $attributes['group_id']) : false,
        'keyword'       =>  $attributes['keyword'] !== 'false' ? sanitize_text_field( urldecode( $attributes['keyword'] ) ) : false,
        'max_events'    =>  $attributes['max_events'] ? absint( $attributes['max_events'] ) : 5,
        'days'          =>  $attributes['days'] ? absint( $attributes['days'] ) : 31
    ];

    // Determine column size based on column count
    $col = [
        'col-12',
        'col-md-6',
        'col-md-4',
        'col-md-3'
    ];

    $column_class = $col[ $column_count - 1 ];

    // Chunk array if multicolumn
    $rows = array_chunk( fetch_localist_events( $parameters ), $column_count );

    if( !is_array( $rows ) ) return 'No events found';

    ob_start(); ?>

        <div class="wp-block-wp-blocks-localist <?php echo $attributes['className']; ?> alignwide">
            <div class="container">
                <?php if( $title ) :?>
                    <h2><?php echo $title; ?></h2>
                <?php endif;?>
                <?php foreach( $rows as $row ): ?>
                    <div class="row">
                        <?php foreach( $row as $post ): ?>
                            <div class="<?php echo $column_class; ?>">
                                <div class="event">
                                    <div class="date">
                                        <div class="datewrap">
                                            <abbr class="month" title="<?php echo $post['month']; ?>">
                                                <?php echo $post['monthabbr']; ?>
                                            </abbr>
                                            <span class="day">
                                                <?php echo $post['day']; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="details">
                                        <h3>
                                            <a href="<?php echo $post['url']; ?>" target="_blank"><?php echo $post['title']; ?></a>
                                        </h3>
                                        <?php if( $post['location'] ): ?>
                                            <span class="location">
                                                <?php echo $post['location']; ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="time">
                                            <time datetime="<?php echo $post['hours']['start']['time']; ?>">
                                                <?php echo $post['hours']['start']['label']; ?>
                                            </time>
                                            <?php if( $post['hours']['end'] ): ?>
                                                <time datetime="<?php echo $post['hours']['end']['time']; ?>">
                                                    <?php echo $post['hours']['end']['label']; ?>
                                                </time>
                                            <?php endif; ?>
                                        </span>
                                        <?php if( $post['description'] && !$hideDescriptions ): ?>
                                            <p><?php echo $post['description']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div> 
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    <?php $output = ob_get_contents();
    ob_end_clean();
    
    return $output;
}

/**
 * Fetches all options from localist API
 * 
 * @return object Block options loaded from API
 */
add_action( 'wp_ajax_block_localist_options', 'block_localist_options' );
function block_localist_options() {
    $api = new Localist_Block();

    $groups = [
        ['value' => null, 'label' => '']
    ];
    foreach ( $api->get_groups() as $item){
        $groups[] = [
            'value' =>  (string) $item->group->id,
            'label' =>  $item->group->name
        ];
    }

    $venues = [];
    foreach ( $api->get_places() as $item){
        $venues[] = [
            'value' =>  (string) $item->place->id,
            'label' =>  $item->place->name
        ];
    }

    $departments = $api->get_departments();
    $events = $api->get_event_types();
    $audiences = $api->get_target_audiences();

    wp_send_json_success( [
        'groupOptions'        =>  $groups,
        'venueOptions'        =>  $venues,
        'departmentOptions'   =>  get_nested_select_options( $departments ),
        'eventOptions'        =>  get_nested_select_options( $events ),
        'audienceOptions'     =>  get_nested_select_options( $audiences )
    ] );
}

/**
 * Fetches events based on supplied parameters for block via AJAX
 * 
 * @return object Events found
 */
add_action( 'wp_ajax_block_localist_get_events', 'block_localist_get_events' );
function block_localist_get_events() {
    $parameters = [
        'events'        =>  validate_param( $_GET['events'] ),
        'audiences'     =>  validate_param( $_GET['audiences'] ),
        'venue_id'      =>  validate_param( $_GET['venue_id'] ),
        'departments'   =>  validate_param( $_GET['departments'] ),
        'distinct'      =>  wp_validate_boolean( $_GET['distinct'] ),
        'group_id'      =>  $_GET['group_id'] ? absint( $_GET['group_id']) : false,
        'keyword'       =>  $_GET['keyword'] !== 'false' ? sanitize_text_field( urldecode( $_GET['keyword'] ) ) : false,
        'max_events'    =>  $_GET['max_events'] ? absint( $_GET['max_events'] ) : 5,
        'days'          =>  $_GET['days'] ? absint( $_GET['days'] ) : 31
    ];
    
    wp_send_json_success( fetch_localist_events( $parameters ) );
}

/**
 * Helper function for concatenated values
 * 
 * @return array Array of parameters
 */
function validate_param( $param ) {
    if( is_array( $param ) ) return $param;

    return $param == '' ? false : explode( '+', $param );
}

/**
 * Helper function for dynamic block events on back-end
 * 
 * @return object Calendar Events
 */
function fetch_localist_events( $parameters ) {
    $api = new Localist_Block();
    return $api->get_events( $parameters );
}

/**
 * Helper function to properly structures nested select options
 * 
 * @return array Associative array of labels and values
 */
function get_nested_select_options( $data ) {

    $options = array();
      
    if( !is_array( $data ) ) {
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