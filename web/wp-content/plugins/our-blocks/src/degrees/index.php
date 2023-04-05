<?php defined( 'ABSPATH' ) || exit;

/**
 * Register dynamic callback for block
 * 
 * @return null
 */
add_action('plugins_loaded', function() {
    register_block_type( 'wp-blocks/degrees', [
        'render_callback' => 'block_degrees'
    ]);
});

/**
 * Loads degree options from tools db
 * 
 * @return object   Degree options to select from
 */
add_action( 'wp_ajax_block_degree_options', 'block_degree_options' );
function block_degree_options(){

    $results = Database\Tools::query("
        SELECT de.id,dedn.name as degreeName,dedt.type 
        FROM homepage_tools.degree_explorer as de
        LEFT JOIN homepage_tools.degree_explorer_degree_types as dedt 
            ON de.degreeType = dedt.id
        LEFT JOIN homepage_tools.degree_explorer_names as dedn 
            ON de.name_id = dedn.id
        WHERE published = 1 
        ORDER BY degreeName, degreeType
    ");

    if( $results ){
        $options = [];
        foreach( $results as $degree ) {
            $options[] = [
                'value' => $degree->id,
                'label' => $degree->degreeName . ' - ' . $degree->type,
            ];
        }
        wp_send_json_success( $options );
    } else {
        wp_send_json_error();
    }
}

/**
 * Helper function for dynamic block back-end output
 * 
 * @param int $id   of the selected degree
 * 
 * @return string   The markup for the block
 */
add_action( 'wp_ajax_block_degree_info', 'block_degree_info' );
function block_degree_info() {

    // id of degree
    $id = absint( $_GET['degree'] );

    if( !$id ) wp_send_json_error( 'Degree not found');

    $content = block_degree_info_helper( $id );

    $content ? wp_send_json_success( $content ) : wp_send_json_error();
}


/**
 * Helper function for dynamic block front-end output
 * 
 * @param int $id   of the selected degree
 * 
 * @return string   The markup for the block
 */
function block_degrees( $attributes ) {
    $id = absint( $attributes['degree'] );

    if( !$id ) return;

    ob_start(); ?>
        <div class="wp-block-wp-blocks-degrees">
            <?php echo block_degree_info_helper( $id ); ?>
        </div>
    <?php 
    $output = ob_get_contents();
    ob_end_clean();
    
    return $output;
}

/**
 * Renders markup for block
 * 
 * @param int $id   id of the selected degree
 * 
 * @return string   The HTML for the block
 */
function block_degree_info_helper( $id ) {
    if( $content = get_site_transient( 'degree-' . $id ) ) return $content;

    $results = Database\Tools::query("
        SELECT dedn.name as degreeName,dedt.type,de.aboutMajor 
        FROM homepage_tools.degree_explorer as de
        LEFT JOIN homepage_tools.degree_explorer_degree_types as dedt 
            ON de.degreeType = dedt.id
        LEFT JOIN homepage_tools.degree_explorer_names as dedn 
            ON de.name_id = dedn.id
        WHERE de.id = ${id} and published = 1
    ");

    if( !$results ) return false;

    // Strip out nested paragraph tags
    $html = strip_tags( $results[0]->aboutMajor , '<p>');
    
    $content = "<h2>{$results[0]->degreeName}-{$results[0]->type}</h2>";

    foreach( explode( '</p>', $html ) as $p ){
        $cleaned = trim( strip_tags( $p ) );
        if( strlen( $cleaned ) > 0 ){
            $content .= "<p>$cleaned</p>";
        }
    }
    $url = 'http://www.ecu.edu/degrees/' . urlencode( $results[0]->type ) . '/' . urlencode( $results[0]->degreeName );
    $content .= "<a href=\"{$url}\">view full degree page</a>";
    
    set_site_transient( 'degree-' . $id, $content, HOUR_IN_SECONDS * 24 );

    return $content;
}