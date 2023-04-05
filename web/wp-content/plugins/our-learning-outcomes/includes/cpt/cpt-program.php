<?php
namespace OUR\LEARNINGOUTCOMES;

function custom_post_program() {
    $labels = array(
        'name'               => _x( 'Programs', 'post type general name' ),
        'singular_name'      => _x( 'Program', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'Program' ),
        'add_new_item'       => __( 'Add New Program' ),
        'edit_item'          => __( 'Edit Program' ),
        'new_item'           => __( 'New Program' ),
        'all_items'          => __( 'All Programs' ),
        'view_item'          => __( 'View Program' ),
        'search_items'       => __( 'Search Programs' ),
        'not_found'          => __( 'No Programs found' ),
        'not_found_in_trash' => __( 'No Programs found in the Trash' ),
        //'parent_item_colon'  => 'College',
        'menu_name'          => 'Program'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Defines the different Programs',
        'public'        => true,
        //'menu_position' => 5,
        'supports'      => array( 'title'),
        'has_archive'   => true,
       // 'hierarchical'  => true,
    );
    register_post_type( 'program', $args );
}
add_action( 'init', __NAMESPACE__ . '\custom_post_program' );

//register the custom meta boxes for creating a Program
function lo_editor_meta_boxes() {
  add_meta_box (
        'college-selector',
        __('College', 'college-selector') ,
        'college_selector',
        'program'
    );
    add_meta_box (
        'program-purpose-editor',
        __('Program Purpose', 'program-purpose-editor') ,
        'pp_editor',
        'program'
    );
    add_meta_box (
        'learning-outcome-editor',
        __('Learning Outcomes', 'learning-outcome-editor') ,
        'lo_editor',
        'program'
    );
}
add_action('add_meta_boxes', __NAMESPACE__ . '\lo_editor_meta_boxes');

//define the college selector
function college_selector($post) {
    $content = get_post_meta($post->ID, 'college_selector', true);

    wp_dropdown_pages(array('post_type'=>'college', 'selected' => $content, 'name'=>'college_selector'));
}

//define the program purpose box
function pp_editor($post) {
    $content = get_post_meta($post->ID, 'pp_editor', true);

    wp_editor(
        $content,
        'pp_editor',
        array( 'media_buttons'=>true)
    );
}

//define the learning outcome box
function lo_editor($post) {
    $content = get_post_meta($post->ID, 'lo_editor', true);

    wp_editor(
        $content,
        'lo_editor',
        array( 'media_buttons'=>true)
    );
}


//save custom meta boxes (College)
function lo_program_save_postdata($post_id)  {
    if (get_post_type($post_id) == 'program') {
        if (!empty($_POST['college_selector'])) {
            update_post_meta($post_id, 'college_selector', wp_kses_post($_POST['college_selector']));
        }
        if (!empty($_POST['pp_editor'])) {
              update_post_meta($post_id, 'pp_editor', wp_kses_post($_POST['pp_editor']));
        }
        if (!empty($_POST['lo_editor'])) {
            update_post_meta($post_id, 'lo_editor', wp_kses_post($_POST['lo_editor']));
        }
    }
}
add_action('save_post', __NAMESPACE__ . '\lo_program_save_postdata');






?>