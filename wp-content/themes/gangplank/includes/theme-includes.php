<?php

/**
 * Registers default WP navigation menus for use with theme
 *
 */
register_nav_menus(
    array(
        'primary' => 'Main Navigation Menu',
        'secondary' => 'Footer Menu',
    )
);

/**
 * Returns whether or not blog is second level
 * @return boolean
 */
function is_second_level() {
    return get_field('ecu_second_level_nav', 'option');
}

/**
 * Registers default WP sidebar for use with theme
 *
 */
add_action( 'widgets_init', 'default_sidebars',10,0 );
function default_sidebars() {
    register_sidebar(
        array(
            'name'          => 'Primary Sidebar',
            'id'            => 'default_sidebar',
            'description'   => 'This is the default sidebar if you are using the sidebar template and have not chosen a custom sidebar.',
            'before_widget' => '<section class="widget %1$s %2$s" aria-label="%1$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widgettitle widget-title">',
            'after_title'   => '</h3>',
        )
    );
}

/**
 * Loads all styles and scripts required by theme
 *
 */
add_action( 'wp_enqueue_scripts', 'load_resources', 11, 0 );
function load_resources(){
    global $post;

    wp_enqueue_style('theme-fonts', 'https://fonts.googleapis.com/css?family=Oswald:wght@300;400;500;600;700|Quattrocento:wght@400;700|Roboto+Slab:wght@300;700');
    wp_enqueue_style('theme-styles', cache_hash('/css/theme.css'));

    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', array(), null, true);
    wp_enqueue_script('match-height', get_template_directory_uri() . '/js/match-height.min.js', 'jquery', null, true);
    wp_enqueue_script('popper', get_template_directory_uri() . '/js/popper.min.js', 'jquery', null, true);
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', 'jquery', null, true);
    wp_enqueue_script('slick', get_template_directory_uri() . '/js/slick.min.js', 'jquery', null, true);
    wp_enqueue_script('theme-scripts', cache_hash('/js/theme.js'), 'jquery', null, true);

    if(false === DISABLE_WP_ANALYTICS){
        wp_enqueue_script('ecu-analytics', get_template_directory_uri() . '/js/ecu-ga.js', 'jquery', null, true);
    }
    if(get_page_template_slug( $post->ID ) == 'page-blank.php'){
        wp_enqueue_style('blank-template', get_template_directory_uri() . '/css/blank.css');
    }
    if(get_the_id() == get_option( 'page_on_front' ) || get_page_template_slug() == 'page-blank.php'){
        wp_enqueue_script('home-slider', get_template_directory_uri() . '/js/home-slider.js', 'jquery', null, true);
    }
}

/**
 * Appends timestamp of last modified date to assets
 * Prevents cached versions from being served if file has been updated
 * @param  boolean $file Resouce to be loaded
 * @return string        Relative URL of resource from theme directory
 */
function cache_hash($file = false){
    if(!$file){
        return;
    }
    $hash = filemtime(get_template_directory() . $file);
    return get_template_directory_uri() . $file . "?v=" . $hash;
}

/**
 * Loads required fields per theme
 *
 */
add_action( 'init', 'load_acf_fields');
function load_acf_fields() {
    if(class_exists('ACF')){
        $option = get_field('super_acf', 'option');
        $parent = get_template_directory() . '/fields/acf-fields.php';
        $child = get_stylesheet_directory() . '/fields/acf-fields.php';
        $super = get_template_directory() . '/fields/superadmin.php';
        switch($option){
            case 'parent':
                if(file_exists($parent))
                    include_once($parent);
                break;
        case 'child':
            if(file_exists($child))
                include_once($child);
            break;
        case 'both':
            if(file_exists($child) && file_exists($parent))
                include_once($child);
                include_once($parent);
            break;
        case 'none':
            break;
        default:
            if(file_exists($parent))
                include_once($parent);
            break;
        }
    }
}

/**
 * Injects styles / scripts into wp_head
 *
 */
add_action('wp_head', 'inject_header');
function inject_header() {
    if(is_prod()): ?>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WL4Q6PC');</script>
        <!-- End Google Tag Manager -->
    <?php endif;

    if(!is_admin()):
        if(($theme_css = get_field('blank_css', 'option')) && get_field('enable_theme_css', 'option')): ?>
            <style type="text/css">
                <?php echo strip_tags($theme_css); ?>
            </style>
        <?php endif;

        if(($page_css = get_field('blank_css')) && (get_page_template_slug() == 'page-blank.php') && get_field('enable_page_css')):?>
            <style type="text/css">
                <?php echo strip_tags($page_css); ?>
            </style>
        <?php endif;
    endif;
}

/**
 * Provides fallback functionality if ACF fields plugin is disabled
 * Prevents theme from erroring out due to ACF function usage
 *
 */
add_action( 'init', 'acf_fallback', 999,0);
function acf_fallback(){
    if(!class_exists('ACF') && !is_admin() ){
        function get_field($field = '', $id = false) {
            return false;
        }
        function the_field($field = '', $id = false) {
            return false;
        }
        function have_rows($field = '', $id = false) {
            return false;
        }
        function has_sub_field($field = '', $id = false) {
            return false;
        }
        function get_sub_field($field = '', $id = false) {
            return false;
        }
        function the_sub_field($field = '', $id = false) {
            return false;
        }
    }
}

/**
 * Inject GA into page body
 *
 */
add_action('wp_body', 'inject_body');
function inject_body() {
    if(is_prod()): ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WL4Q6PC"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    <?php endif;
}