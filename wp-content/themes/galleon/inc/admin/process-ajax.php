<?php defined( 'ABSPATH' ) || exit;

/**
 * 
 * Defines AJAX endpoints for wp-ajax requests
 * 
 */

/**
 * Returns array of pages whose title matches query string
 * 
 * @param   string  q           Query string
 * @param   string  post_type   Filters results by specified post type, defaults to page
 * 
 * @return  array
 * 
 * @see     https://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action)
 */
add_action('wp_ajax_wp_theme_get_pages', 'wp_theme_get_pages');
function wp_theme_get_pages(){
    global $wpdb;

    $q = sanitize_text_field( $_GET['q'] );

    if(isset($_GET['post_type'])){
        $post_type = sanitize_text_field($_GET['post_type']);
        $query .= $post_type;
    } else {
        $post_type .= 'page';
    }

    $query = "
        SELECT ID, post_title 
        FROM {$wpdb->prefix}posts 
        WHERE post_title LIKE '%{$q}%' 
        AND post_status = 'publish' 
        AND post_type = '{$post_type}'
    ";

    $results = $wpdb->get_results( $query, OBJECT );

    $options = [];
    if(!empty($results)){
        foreach($results as $result){
            $options[] = [
                "id"    =>  $result->ID,
                "text"  =>  $result->post_title
            ];
        }
    }
    wp_send_json_success( $options, 200 );
}