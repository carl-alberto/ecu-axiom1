<?php defined( 'ABSPATH' ) || exit;

/**
 *
 * Defines logic for wp-admin post requests
 *
 */

 /**
 * Ajax request for theme options page  /wp-admin/admin.php?page=theme_options
 * Fetches values for select2 fields
 * - or -
 * Saves values to db
 */
add_action( 'wp_ajax_ecu_theme_options', 'ecu_theme_options', 10, 0 );
function ecu_theme_options(){
    // save
    $save = isset( $_POST['save'] ) ? true : false;

    if( $save ){
        $posted_options = json_decode( wp_unslash( $_POST['options'] ) );
        foreach( $posted_options as $option => $value ){
            if( $option === 'ecu_address' ){
                update_option( $option, json_encode( $value ) );
            } else {
                update_option( $option, $value );
            }
        }
        wp_send_json_success( $posted_options );
    }

    $options = get_site_options();

    $posts = get_posts( [
        'post_type' => [ 'page', 'sidebar' ],
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ] );

    wp_send_json_success( [
        'options' => $options,
        'posts' => $posts
    ] );
}

/**
 * Process sidebar add form
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/admin_post_(action)
 */
add_action( 'admin_post_widget_crud', 'prefix_admin_widget_crud' );
function prefix_admin_widget_crud() {
    $method = sanitize_text_field( $_POST["method"] );

    if($method === "create"){
        if(!empty($_POST['title'])) {
            wp_insert_post([
                "post_title"    =>  sanitize_text_field( $_POST["title"]),
                "post_type"     =>  "sidebar",
                "post_status"   =>  "publish"
            ]);
            update_option('ecu_widget_message',
            [
                'message' => 'The sidebar was successfully created!',
                'class' => 'success'
            ]);
        } else {
            update_option('ecu_widget_message',
            [
                'message' => 'The sidebar must have a title!',
                'class' => 'error'
            ]);
        }
    } else {
        $post_id = (int) sanitize_text_field($_POST["post_id"]);
        if( get_post_type($post_id) === "sidebar" ){
            wp_delete_post( $post_id, true ) ;
            update_option('ecu_widget_message',
            [
                'message' => 'The sidebar was successfully deleted!',
                'class' => 'success'
            ]);
        } else {
            update_option('ecu_widget_message',
            [
                'message' => 'The sidebar was not deleted!',
                'class' => 'error'
            ]);
        }
    }
    wp_redirect( admin_url('widgets.php') );
}

add_action('admin_notices', 'widget_admin_notice');
function widget_admin_notice(){
    if($option = get_option('ecu_widget_message')) {
        delete_option('ecu_widget_message');
    }

    if(!empty($option) && is_array($option)) {
         echo '<div class="notice notice-' . $option['class'] . ' is-dismissible">
             <p>' . $option['message'] . '</p>
         </div>';
    }
}
