<?php
add_action('header_meta', 'breadcrumbs');

add_action( 'wp_enqueue_scripts', 'child_resources', 12,0);
function child_resources(){
    wp_register_script('isotope', get_stylesheet_directory_uri() . '/js/isotope.min.js', 'jquery', null, true);
    wp_enqueue_style('child-styles', get_stylesheet_directory_uri() . '/css/child.css');
    wp_enqueue_script('child-scripts', get_stylesheet_directory_uri() . '/js/child-scripts.js', array('jquery'), null, true);
    wp_enqueue_script('chat-window', get_stylesheet_directory_uri() . '/js/chat-window.js', array('jquery'), null, true);

    if(get_page_template_slug() == 'page-services.php'){
        wp_register_script('service-search', get_stylesheet_directory_uri() . '/js/service-search.js', array('jquery', 'isotope'), null, true);
        wp_localize_script('service-search', 'wpajax', admin_url( 'admin-ajax.php' ));
    	wp_enqueue_script('service-search');
    }

    if(is_singular('service')){
        wp_enqueue_script('clipboard', 'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js', array('jquery'), null, true);
        wp_enqueue_script('single-service', get_stylesheet_directory_uri() . '/js/single-service.js', array('jquery'), null, true);
    }
}


add_action('get_header', 'redirect_non_service',10,0);
function redirect_non_service(){
    global $wp_query;
    if(is_tax('service-category') || is_tax('audience')){
        wp_redirect(get_bloginfo('url') . '/services', 301);
        exit;
    }
    if(!is_singular('service') && (isset($wp_query->query['tutorials']) || isset($wp_query->query['faqs']))){
        wp_redirect(get_the_permalink(), 301);
        exit;
    }
}
add_action('wp_head', 'remove_wysiwyg_format');
function remove_wysiwyg_format(){
    if(is_front_page()){
        remove_filter('the_content', 'wpautop');
        remove_filter('the_excerpt', 'wpautop');
        remove_filter ('acf_the_content', 'wpautop');

        add_filter( 'the_content', 'wpse_wpautop_nobr' );
        add_filter( 'the_excerpt', 'wpse_wpautop_nobr' );
        add_filter( 'acf_the_content', 'wpse_wpautop_nobr' );
    }
}
function wpse_wpautop_nobr( $content ) {
    return wpautop( $content, false );
}

//creates shortcode for itcs get help bar
add_shortcode( 'itcs_help_bar', 'itcs_help_bar_output' );
function itcs_help_bar_output() {
  if($help = get_field('itcs_help_links', 'option')) {
      return $help;
  }
}

