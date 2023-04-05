<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Plugin Name: Our Blocks
 * Description: Plugin providing wp custom blocks
 * Version: 1.0.0
 * Text Domain: wp-blocks
 *
 */

/**
 * Include files for dynamic blocks
 */
if ( $handle = opendir( ( $path = plugin_dir_path( __FILE__ ) . 'src/' ) ) ) {
	while ( ( $entry = readdir( $handle ) ) !== false ) {
		if( is_dir( $path . $entry ) && file_exists( ( $file = $path . $entry . '/index.php' ) ) ){
			include( $file );
		}
	}
	closedir($handle);
}

/**
 * Enqueue Gutenberg block assets for backend editor
 *
 * @see https://developer.wordpress.org/reference/hooks/enqueue_block_editor_assets/
 */
add_action( 'admin_enqueue_scripts', 'maybe_enqueue_assets' );

function maybe_enqueue_assets() {
	$screen = get_current_screen();
	if( $screen->id === 'toplevel_page_ecu_migration' || $screen->id === 'toplevel_page_wp_transcode' ) enqueue_assets();
}

add_action( 'enqueue_block_editor_assets', 'enqueue_assets' );
function enqueue_assets() {
	// Compiled js file
	wp_register_script(
		'wp-blocks-scripts',
		plugin_dir_url( __FILE__ ) . 'dist/wp-blocks.js',
		[ 'wp-i18n', 'wp-blocks', 'wp-components', 'wp-element', 'wp-editor' ],
		filemtime( plugin_dir_path( __FILE__ ) . 'dist/wp-blocks.js' ),
		true
	);

	// Inject admin ajax path
	$hosts = explode(',', WP_IFRAME_HOSTS);
	wp_localize_script(
		'wp-blocks-scripts',
		'__wp__',
		[
			'ajax'		=>	admin_url( 'admin-ajax.php' ),
			'api'		=>	get_rest_url( null, '/wp/v2' ),
			'assets'	=>	plugin_dir_url( __FILE__ ) . 'assets',
			'admin'		=>  admin_url(),
			'hosts'		=>	is_array( $hosts ) ?  $hosts : []
		]
	);

	// Enqueue scripts
	wp_enqueue_script( 'wp-blocks-scripts' );

	wp_enqueue_script( 'fa', 'https://kit.fontawesome.com/ad7c4c4cb5.js' );

	// Enqueue styles
	wp_enqueue_style(
		'wp-blocks',
		plugin_dir_url( __FILE__ ) . 'dist/wp-blocks.css',
		null,
		filemtime( plugin_dir_path( __FILE__ ) . 'dist/wp-blocks.css' )
	);
}

/**
 * Enqueue Gutenberg block assets for frontend
 *
 * @see https://developer.wordpress.org/reference/hooks/enqueue_block_assets/
 */
add_action( 'enqueue_block_assets', function() {
	wp_enqueue_style(
		'wp-blocks',
		plugin_dir_url( __FILE__ ) . 'dist/wp-blocks.css',
		null,
		filemtime( plugin_dir_path( __FILE__ ) . 'dist/wp-blocks.css' )
	);
});

/**
 * Register ECU block category
 */
add_filter( 'block_categories', function($categories) {
	$category_slugs = wp_list_pluck( $categories, 'slug' );

    return in_array( 'ecu', $category_slugs, true ) ? $categories : array_merge(
		$categories, [
			[
				'slug'  => 	'ecu',
				'title' => 	'ECU',
				'icon'  => 	null,
			]
		]
    );
} );

add_action( 'wp_ajax_wp_blocks_get_posts', 'wp_blocks_get_posts' );
function wp_blocks_get_posts() {

	// if( $posts = get_transient( 'wp_blocks_get_posts' ) ) wp_send_json_success( $posts );

    $posts = get_posts( [
		'post_type' 		=> [ 'page', 'post' ],
		'posts_per_page'	=>	-1,
		'orderby'			=>	'post_title',
		'order'				=>	'asc'
	] );

    /**
     * Return data object
     */

	$output = [
		[
			'label' => '-',
			'value' => ''
		]
	];
	foreach ( $posts as $post ){
		$output[] = [
			'label' => $post->post_title . " ({$post->post_type})",
			'value' => get_the_permalink( $post->ID )
		];
	}

	// Low cache time so new posts / pages show up
	// set_transient( 'wp_blocks_get_posts', $output, HOUR_IN_SECONDS );

    wp_send_json_success( $output );
};