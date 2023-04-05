<?php defined( 'ABSPATH' ) || exit;

/**
 *
 * Defines custom functionality that is executed on actions
 *
 */

define( 'DIST_URL', get_template_directory_uri() . '/dist' );
define( 'DIST_PATH', get_template_directory() . '/dist' );

/**
 * Loads all necessary scripts and styles for theme
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
 */
add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts', 10, 0 );
function theme_enqueue_scripts(){
    wp_deregister_script( 'jquery' );
    wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.3.1.min.js', [], '3.3.1', true );

    wp_enqueue_style( 'theme-styles', DIST_URL . '/public.css', [], filemtime( DIST_PATH . '/public.css' ) );
    wp_enqueue_script( 'theme-scripts', DIST_URL . '/public.js', [], filemtime( DIST_PATH . '/public.js' ), true );
    wp_enqueue_style( 'theme-fonts', 'https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700|Quattrocento:400,700', false );
    wp_enqueue_script( 'fa', 'https://kit.fontawesome.com/ad7c4c4cb5.js', [], '5.15.1', true );
}

/**
 * Registers default theme sidebar
 *
 * @see https://developer.wordpress.org/reference/hooks/widgets_init/
 */
add_action( 'widgets_init', 'default_sidebars', 10, 0 );
function default_sidebars() {
    register_sidebar([
        'name'          => 'Primary Sidebar',
        'id'            => 'default_sidebar',
        'description'   => 'Default sidebar rendered on sidebar templates',
        'before_widget' => '<section class="widget %1$s %2$s" aria-label="%1$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widgettitle widget-title">',
        'after_title'   => '</h2>'
    ]);
}

/**
 * Registers taxonomy for featured author
 *
 * @see https://developer.wordpress.org/reference/hooks/init/
 */
add_action( 'init', 'register_featured_author_tax', 10, 0  );
function register_featured_author_tax() {
	$args = [
		'hierarchical'      => true,
		'labels'            => [
            'name'                      => 'Featured Authors',
            'singular_name'             => 'Author',
            'search_items'              => 'Search Authors',
            'all_items'                 => 'All Authors',
            'edit_item'                 => 'Edit Author',
            'update_item'               => 'Update Author',
            'add_new_item'              => 'Add New Author',
            'new_item_name'             => 'New Author Name',
            'menu_name'                 => 'Authors',
            'add_or_remove_items'       => 'Add or remove authors',
            'choose_from_most_used'     => 'Choose from the most used authors',
            'not_found'                 => 'No authors found.',
        ],
		'show_ui'           => true,
		'show_admin_column' => true,
        'query_var'         => 'featured-author',
        'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'author' ),
    ];

    register_taxonomy( 'featured-author', ['post'], $args );
}

/**
 * Adds support for featured authors to display via the author archive pages.
 *
 * Several sites have created a featured author for the department and display that as the author
 * for posts instead of displaying faculty/staff member names. For example, Biology uses "ECU Biology"
 * as the featured author for most of their posts. https://biology.ecu.edu/author/ecu-biology/ should show all
 * the posts by this author.
 *
 * Switch the display name with the username so that we can populate the posts properly
 * Redirect the username author page to the display name page.
 *
 * @see https://developer.wordpress.org/reference/hooks/pre_get_posts/
 */
add_action('pre_get_posts', 'ecu_author_archive');
function ecu_author_archive( $query ) {

	if ( ! is_author() || is_admin())
		return;

	$slug = $query->get('author_name');
	$author_name = str_replace('-', ' ', $query->get('author_name'));

	if ( $author = get_user_by('login', $author_name )) {
		// Redirect to the author display name
		global $wp;
		$link = home_url( $wp->request );
		$link = str_replace($author->user_login, sanitize_title($author->display_name), $link);
		wp_safe_redirect( $link, 301 );
		die();
	} else {

		// Configure the query to only show posts by the user display name
		$args= array(
			'search' => $author_name,
			'search_fields' => array('display_name')
		);
		$q = new WP_User_Query($args);

		if($author = $q->get_results()[0]) {

			if( $author->user_login !== $author_name ) {
				set_query_var('author', $author->ID);
				set_query_var('author_name', $author->user_login);
			}
		} else {

			// Configure the query to only show posts by the featured author tax
			$args = array(
				'featured-author' => $slug,
				'post_type' => 'post',
				'tax_query' => array(
					array(
						'taxonomy' => 'featured-author',
						'field'    => 'slug',
						'terms'    => $slug,
						'include_children' => false
					),
				),
			);

			// For some reason the $query ( that is supposed to be passed in reference )
			// wasn't actually updating the global wp query object in production.
			global $wp_query;
			$wp_query = new WP_Query( $args );

			// Load 404 if there are no posts
			if(!$wp_query->have_posts()) {
            	$wp_query->set_404();
				include(get_404_template());
				die();
			}
		}
	}

}

/**
 * Registers default theme menus
 *
 * @see https://developer.wordpress.org/reference/hooks/after_setup_theme/
 * @see https://codex.wordpress.org/Function_Reference/register_nav_menus
 * @see https://developer.wordpress.org/reference/functions/add_theme_support/
 */
add_action( 'after_setup_theme', 'register_theme_features', 10, 0  );
function register_theme_features() {
    register_nav_menus(
        array(
            'primary' => 'Main Navigation',
            'secondary' => 'Footer Navigation',
        )
    );

    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
}

/**
 * Adds stylesheet to login page
 *
 * @see https://developer.wordpress.org/reference/hooks/login_enqueue_scripts/
 */
add_action( 'login_enqueue_scripts', 'enqueue_login_scripts', 10, 0  );
function enqueue_login_scripts() {
    wp_enqueue_style( 'theme-styles', DIST_URL . '/public.css', [], filemtime( DIST_PATH . '/public.css' ) );
}

/**
 * Change the fav icon on the login page
 *
 * @see https://developer.wordpress.org/reference/hooks/login_head/
 */
add_action( 'login_head', 'login_favicon', 10, 0 );
function login_favicon()
{
    echo '<link rel="shortcut icon" href="' .  get_template_directory_uri() . '/images/favicon.png">';
}

/**
 * Defines sidebar custom post type
 *
 * @see https://developer.wordpress.org/reference/hooks/init/
 */
add_action( 'init', 'register_sidebar_cpt', 10, 0  );
function register_sidebar_cpt() {
	$args =  [
		'description' => 'Sidebar custom post type',
		'public' => false,
		'exclude_from_search' => false,
		'show_ui' => false,
		'supports' => ['title'],
		'has_archive' => false,
        'rewrite' => false,
        'show_in_rest' => true
	];
	register_post_type( 'sidebar', $args );
}

/**
 * Registers sidebars based on sidebar custom post type
 *
 * @see https://developer.wordpress.org/reference/hooks/widgets_init/
 */
add_action( 'widgets_init', 'register_custom_sidebars', 10, 0  );
function register_custom_sidebars() {
    $sidebars = get_posts( [
        'post_type' => 'sidebar',
        'posts_per_page' => -1
    ] );
    if( count( $sidebars ) > 0 ){
        foreach( $sidebars as $sidebar ){
            register_sidebar([
                'name'          => $sidebar->post_title,
                'id'            => 'custom-sidebar-' . $sidebar->ID,
                'before_widget' => '<section class="widget %1$s %2$s" aria-label="%1$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widgettitle widget-title">',
                'after_title'   => '</h2>',
            ]);
        }
    }
    wp_reset_postdata();
}

/**
 * Register Custom Block Styles
 */
if ( function_exists( 'register_block_style' ) ) {
    function block_styles_register_block_styles() {
        wp_register_style(
            'block-styles-stylesheet',
            '/wp-content/themes/galleon/dist/blocks.css',
            [],
            '1.1'
        );

		register_block_style(
			'core/paragraph',
			[
				'name'			=> 'paragraph-block-large',
				'label'			=> 'Large',
				'style_handle'	=> 'block-styles-stylesheet',
			]
		);

		register_block_style(
			'core/paragraph',
			[
				'name'			=> 'paragraph-block-larger',
				'label'			=> 'Larger',
				'style_handle'	=> 'block-styles-stylesheet',
			]
		);

		register_block_style(
			'core/paragraph',
			[
				'name'			=> 'paragraph-block-largest',
				'label'			=> 'Largest',
				'style_handle'	=> 'block-styles-stylesheet',
			]
		);
    }

    add_action( 'init', 'block_styles_register_block_styles' );
}