<?php

// Registering post meta applies certain validation to values when adding or updating in db

add_action( 'admin_init', 'posts_order' );

function posts_order()
{
    add_post_type_support( 'post', 'page-attributes' );
}

add_action( 'init', 'ecu_register_post_meta' );

function ecu_register_post_meta(){

    // posts
    // pages
    register_post_meta( 'post', 'ecu_banner', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'default' => '[]'
    ] );
    register_post_meta( 'page', 'ecu_banner', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'default' => '[]'
    ] );
    register_post_meta( 'post', 'ecu_alt_title', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'default' => ''
    ] );
    register_post_meta( 'page', 'ecu_alt_title', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'default' => ''
    ] );

    register_post_meta( 'post', 'ecu_banner_full', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'boolean',
        'default' => true
    ] );
    register_post_meta( 'page', 'ecu_banner_full', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'boolean',
        'default' => true
    ] );

    // posts
    register_post_meta( 'post', 'ecu_subtitle', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'default' => ''
    ] );

    register_post_meta( 'post', 'ecu_spark', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'default' => ''
    ] );

    //All
    register_post_meta( '', 'ecu_sidebar_position', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'boolean',
        'default' => true
    ] );
    register_post_meta( '', 'ecu_sidebar', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'integer',
        'default' => 0
    ] );
    register_post_meta( '', 'ecu_to_top', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'boolean',
        'default' => true
    ] );
    register_post_meta( '', 'ecu_hide_h1', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'boolean',
        'default' => false
    ] );
}

add_action( 'wp_ajax_ecu_meta_sidebar', 'ecu_meta_sidebar' );
function ecu_meta_sidebar(){
    $all_posts = get_posts([
        'post_type' => [ 'post', 'sidebar'],
        'post_status' => 'publish',
        'posts_per_page' => -1
    ]);
    $sidebars = [
        [
            'label' => 'Default',
            'value' => 0
        ],
        [
            'label' => 'None',
            'value' => -1
        ]
    ];
    $posts = [];
    foreach( $all_posts as $post ){
        if( $post->post_type === 'sidebar' ){
            $sidebars[] = [
                'label' => $post->post_title,
                'value' => $post->ID
            ];
        } elseif( $post->post_type === 'post' ) {
            $posts[] = [
                'label' => $post->post_title,
                'value' => $post->ID
            ];
        }

    }

    $all_categories = get_categories();
    $categories = [];
    foreach( $all_categories as $cat ){
        $categories[] = [
            'label' => $cat->cat_name,
            'value' => $cat->cat_ID
        ];
    }
    wp_send_json_success( [
        'sidebars' => $sidebars,
        'posts' => $posts,
        'categories' => $categories
    ] );
}

add_action( 'wp_ajax_ecu_meta_sidebar_update', 'ecu_meta_sidebar_update' );
function ecu_meta_sidebar_update(){
    $post = json_decode( wp_unslash( $_POST['post'] ) );
    $meta = json_decode( wp_unslash( $_POST['meta'] ) );

    $fields = [
        'post' => [ 'ecu_subtitle', 'ecu_spark' ],
        'page' => [],
        'default' => [ 'ecu_sidebar', 'ecu_sidebar_position', 'ecu_hide_h1', 'ecu_alt_title', 'ecu_banner_full', 'ecu_to_top', 'ecu_banner' ]
    ];
    $type = $fields[ $post->type ] ? $fields[ $post->type ] : [];
    $output = [];
    foreach( array_merge( $type , $fields['default'] ) as $field ){
        $output[$field] = $meta->{$field};

        if('ecu_sidebar' === $field) {
            $current = $post->meta->ecu_sidebar;
            $updated = $meta->ecu_sidebar;

            if($current !== $updated) {
                $template = '';
                if($meta->{$field} == '-1') {
                    if($post->type === 'post') {
                        $template = 'single-post.php';
                    } elseif($post->type === 'page') {
                        $template = 'page.php';
                    }
                } elseif($meta->{$field} > 0) {
                    if($post->type === 'post') {
                        $template = 'single-post-sidebar.php';
                    } elseif($post->type === 'page') {
                        $template = 'page-sidebar.php';
                    }
                }
                if(!empty($template)) {
                    update_post_meta( $post->id, '_wp_page_template', $template );
                }
            }
        }

        update_post_meta( $post->id, $field, $meta->{$field} );
    }
    wp_send_json_success( [$output, get_post_meta( $post->id )] ) ;
}