<?php defined( 'ABSPATH' ) || exit;

/**
 *
 * Functionality executed on wp-admin
 *
 */

/**
 * Includes additional admin resources
 */
include_once( 'process-ajax.php' );
include_once( 'process-post.php' );

/**
 * Change the fav icon on wp-admin pages
 *
 * @see https://developer.wordpress.org/reference/hooks/login_head/
 */
function wpadmin_favicon()
{
    echo '<link rel="shortcut icon" href="' .  get_template_directory_uri() . '/images/favicon.png">';
}

add_action( 'admin_head', 'wpadmin_favicon', 10, 0 );

/**
function site_settings_load() {
    $posts = get_posts( [
        'post_type'         =>  [ 'page', 'sidebar' ],
        'posts_per_page'    =>  -1,
        'post_status'       =>  'publish',
        'orderby'           =>  'title',
        'order'             =>  'asc'
    ] );
    return wp_send_json_success( [ 'posts' => $posts, 'options' => get_site_options() ] );
}

add_action( 'wp_ajax_site_settings_load', 'site_settings_load', 10, 0 );
*/

/**
 * Loads necessary scripts and styles for wp-admin
 *
 * @see https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
 */
function theme_admin_enqueue_scripts() {
    global $site_options;
    global $post;

    /**
     * Load assets in specific places
     */

    $current_screen = get_current_screen();

    if ( in_array( $current_screen->id, [ 'page', 'post'] ) ) {
        wp_enqueue_script( 'theme-scripts', DIST_URL . '/admin.js', [ 'wp-blocks', 'wp-components', 'wp-element', 'wp-editor' ], filemtime( DIST_PATH . '/admin.js' ), true );
        wp_enqueue_style( 'theme-styles', DIST_URL . '/admin.css', [], filemtime( DIST_PATH . '/admin.css' ) );
        wp_localize_script(
            'theme-scripts',
            '_wp_',
            [
                'ajax'		=>	admin_url( 'admin-ajax.php' ),
                'api'		=>	get_rest_url( null, '/wp/v2' ),
                'admin'		=>  admin_url(),
            ]
        );
        // Enqueue scripts
        wp_enqueue_script( 'theme-scripts' );
    }

    if( in_array( $current_screen->id, [ 'widgets' ] ) ){

        wp_enqueue_style( 'theme-options-styles', DIST_URL . '/theme-options.css', [], filemtime( DIST_PATH . '/theme-options.css' ) );

        wp_enqueue_style( 'theme-fonts', 'https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700|Quattrocento:400,700', false );
        wp_enqueue_script( 'fa', 'https://kit.fontawesome.com/ad7c4c4cb5.js', [], '5.15.1', true );

        wp_register_script( 'galleon-widget-sidebars', get_stylesheet_directory_uri() . '/js/widget-sidebars.js', [], filemtime( get_theme_file_path() . '/js/widget-sidebars.js' ), true );
        wp_localize_script(
            'galleon-widget-sidebars',
            '_wp_',
            [
                'admin'             =>  admin_url( 'admin-post.php' )
            ]
        );
        wp_enqueue_script( 'galleon-widget-sidebars' );
    }

    if( in_array( $current_screen->id, [ 'toplevel_page_theme_options' ] ) ){

        $user = get_userdata( get_current_user_id() );
        $current_user_caps = $user->allcaps;

        wp_enqueue_style( 'theme-options-styles', DIST_URL . '/theme-options.css', [], filemtime( DIST_PATH . '/theme-options.css' ) );
        wp_register_script( 'theme-options', DIST_URL . '/theme-options.js', [ 'wp-components' ], filemtime( DIST_PATH . '/theme-options.js' ), true );
        wp_localize_script(
            'theme-options',
            '_wp_',
            [
                'admin'             =>  admin_url(),
                'url'               =>  get_bloginfo( 'wpurl' ),
                'ajax'		        =>	admin_url( 'admin-ajax.php' ),
                'user'              =>  $current_user_caps
            ]
        );
        wp_enqueue_script( 'theme-options' );
        wp_enqueue_style( 'theme-fonts', 'https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700|Quattrocento:400,700', false );
        wp_enqueue_script( 'fa', 'https://kit.fontawesome.com/ad7c4c4cb5.js', [], '5.15.1', true );
    }
}

add_action( 'admin_enqueue_scripts', 'theme_admin_enqueue_scripts', 10, 0 );

/**
 * Registers menu page for theme settings
 *
 * @see https://developer.wordpress.org/reference/hooks/admin_menu/
 */
function add_site_settings(){
    add_menu_page( 'Theme Options', 'Theme Options', 'theme_settings', 'theme_options', 'register_theme_options_page' );
}

add_action( 'admin_menu', 'add_site_settings', 10, 0 );

/**
 * Callback for site settings; renders theme settings form
 */
function register_theme_options_page(){
	echo "<div id='galleon-theme-options'></div>";
}

/**
 * Adds form to create sidebars on widgets page
 *
 * @see https://developer.wordpress.org/reference/hooks/widgets_admin_page/
 */
function sidebar_crud() {
    include_once( 'views/widget-crud.php' );
}

add_action( 'widgets_admin_page', 'sidebar_crud', 10, 0 );

/**
 * Loads default values for site options
 * Options are autoloaded by default so get_option does not query DB directly
 */
function get_site_options() {
    return [
        'ecu_page_template' =>  get_option( 'ecu_page_template', 'full-width'),
        'ecu_post_template' => get_option( 'ecu_post_template', 'sidebar-right'),
        'ecu_address' => json_decode(get_option('ecu_address', '{"location":"East Carolina University","address":"East 5th Street","city":"Greenville","state":"NC","zip":"27858"}')),
        'ecu_contact' => get_option('ecu_contact', 0),
        'ecu_second_level' => get_option('ecu_second_level', false),
        'ecu_expand_posts' => get_option('ecu_expand_posts', false),
        'ecu_hide_meta' => get_option('ecu_hide_meta', false),
        'ecu_blog_sidebar' => get_option('ecu_blog_sidebar', 0),
        'ecu_category_sidebar' => get_option('ecu_category_sidebar', 0),
        'ecu_author_sidebar' => get_option('ecu_author_sidebar', 0),
        'ecu_date_sidebar' => get_option('ecu_date_sidebar', 0),
        'ecu_phone' => get_option('ecu_phone', ''),
    ];
}

/**
 * Removes comments from menu
 * Renames widgets to Sidebars / Widgets
 */
function custom_menu_page_removing() {
    global $submenu;

    remove_menu_page( 'edit-comments.php' );

    $submenu['themes.php'][7][0] = 'Sidebars / Widgets';
}

add_action( 'admin_menu', 'custom_menu_page_removing', 10, 0 );

/**
 * Display a message to add a contact information if it is missing
 *
 * @see https://developer.wordpress.org/reference/hooks/admin_notices/
 */
function generate_admin_notices(){
    $current_screen = get_current_screen();

    if (( !get_option( 'ecu_phone' ) || !get_option( 'ecu_contact' )) && !in_array( $current_screen->id, ['toplevel_page_theme_options', 'widgets' ] )) {
        $class = 'notice notice-error';
        $message = "Contact phone number or contact page not set. <a href='" . admin_url('admin.php?page=theme_options') . "'>Click here to enter your site settings.</a>";

	   printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

        if (in_array( $current_screen->id, ['toplevel_page_theme_options', 'widgets' ] ) )
        {
            $class = 'notice notice-error';
            $message = "Please ensure that a valid contact phone number or contact page has been set!";

            printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
        }
    }
}

add_action( 'admin_notices' , 'generate_admin_notices', 10, 0 );

/**
 * Add custom colors to color palette
 */
function add_custom_palette_colors() {
	$custom_palette_colors = [
		[
			'name'  => esc_html__( 'Purple', 'galleon' ),
			'slug'  => 'purple',
			'color' => '#592A8A',
		],
		[
			'name'  => esc_html__( 'Dark Purple', 'galleon' ),
			'slug'  => 'dark-purple',
			'color' => '#41215E',
		],
		[
			'name'  => esc_html__( 'Gold', 'galleon' ),
			'slug'  => 'gold',
			'color' => '#FEC923',
		],
		[
			'name'  => esc_html__( 'Grey', 'galleon' ),
			'slug'  => 'grey',
			'color' => '#6C6D68',
		],
		[
			'name'  => esc_html__( 'Dark Magenta', 'galleon' ),
			'slug'  => 'dark-magenta',
			'color' => '#77216F',
		],
		[
			'name'  => esc_html__( 'Teal', 'galleon' ),
			'slug'  => 'teal',
			'color' => '#00818F',
		],
		[
			'name'  => esc_html__( 'Black', 'galleon' ),
			'slug'  => 'black',
			'color' => '#000000',
		],
		[
			'name'  => esc_html__( 'White', 'galleon' ),
			'slug'  => 'white',
			'color' => '#FFFFFF',
		],
	];

	add_theme_support('editor-color-palette', $custom_palette_colors);
}

add_action( 'after_setup_theme' , 'add_custom_palette_colors', 10, 0 );