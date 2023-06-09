<?php
/**
 * Standalone class.
 *
 * @since 1.7.0
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

namespace Envira\Frontend;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

/**
 * Standalone class.
 *
 * @since 1.7.0
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */
class Standalone {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		add_action( 'pre_get_posts', [ $this, 'standalone_pre_get_posts' ] );
		add_action( 'wp_head', [ $this, 'standalone_maybe_insert_shortcode' ] );
		add_action( 'wp', [ $this, 'standalone_maybe_redirect' ] );

		add_filter( 'render_block_core/post-content', [ $this, 'insert_shortcode_into_post_block' ], 10, 2 ); // for W 5.9 + Block Themes.
		add_filter( 'single_template_hierarchy', [ $this, 'standalone_template_hierachy' ], 10, 1 ); // for W 5.9 + Block Themes.

		if ( class_exists( 'Envira_Albums' ) && version_compare( \Envira_Albums::get_instance()->version, '1.3.1', '<' ) ) {
			// this is for old versions of albums.
			add_action( 'pre_get_posts', [ $this, 'envira_albums_standalone_pre_get_posts' ] );
			add_action( 'wp_head', [ $this, 'envira_albums_standalone_maybe_insert_shortcode' ] );
			add_filter( 'envira_albums_post_type_args', [ $this, 'envira_albums_post_type' ] );
			add_filter( 'envira_albums_metabox_ids', [ $this, 'envira_standalone_slug_box' ] );
		}
			$standalone = get_option( 'envira_gallery_standalone_enabled' );

			// Make sure standalone has an option set.
		if ( ! isset( $standalone ) ) {

			update_option( 'envira_gallery_standalone_enabled', true );

		}

		if ( get_option( 'envira_gallery_standalone_enabled' ) ) {
			if ( ! get_option( 'envira-standalone-flushed' ) ) {
				// Flush rewrite rules.
				flush_rewrite_rules();
				// Set flag = true in options.
				update_option( 'envira-standalone-flushed', true );
			}
		}

		add_filter( 'single_template', [ $this, 'standalone_get_custom_template' ], 99 );

	}

	/**
	 * Does not allow gallery to be seen if in draft or non-publish post status.
	 *
	 * @since 1.5.7.3
	 */
	public function standalone_maybe_redirect() {

		global $post, $wp_query;

		$statuses                 = current_user_can( 'edit_posts' ) ? [ 'publish', 'draft', 'private' ] : [ 'publish', 'private' ];
		$allowed_publish_statuses = apply_filters( 'envira_allowed_publish_statuses', $statuses );

		if ( isset( $post->post_status ) ) {
			if ( ! in_array( $post->post_status, $allowed_publish_statuses, true ) && ( 'envira' === $post->post_type || 'album' === $post->post_type ) ) {
				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
			}
		} elseif ( isset( $wp_query->query['post_type'] ) ) {
			if ( ( ! isset( $wp_query->query_vars['p'] ) || 0 === intval( $wp_query->query_vars['p'] ) ) && ( 'envira' === $wp_query->query['post_type'] || 'album' === $wp_query->query['post_type'] ) ) {
				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
			}
		}

	}

	/**
	 * Run Gallery/Album Query if on an Envira Gallery or Album
	 *
	 * @since 1.5.7.3
	 *
	 * @param object $query The query object passed by reference.
	 * @return null      Return early if in admin or not the main query or not a single post.
	 */
	public function standalone_pre_get_posts( $query ) {

		// Return early if in the admin, not the main query or not a single post.
		if ( ! get_option( 'envira_gallery_standalone_enabled' ) || is_admin() || ! $query->is_main_query() || ! $query->is_single() ) {
			return;
		}

		// If not the proper post type (Envira), return early.
		$post_type = get_query_var( 'post_type' );

		if ( 'envira' === $post_type ) {
			do_action( 'envira_standalone_gallery_pre_get_posts', $query );
		}

	}

	/**
	 * Maybe inserts the Envira shortcode into the content for the page being viewed.
	 *
	 * @since 1.5.7.3
	 *
	 * @return null      Return early if in admin or not the main query or not a single post.
	 */
	public function standalone_maybe_insert_shortcode() {

		global $post;

		// Check we are on a single Post.
		if ( ! get_option( 'envira_gallery_standalone_enabled' ) || ! is_singular() ) {
			return;
		}

		// If not the proper post type (Envira), return early.
		$post_type = get_query_var( 'post_type' );

		if ( 'envira' === $post_type ) {
			add_filter( 'the_content', [ $this, 'envira_standalone_insert_gallery_shortcode' ] );
		}

	}

	/**
	 * With block based themes, will update template hierachy to reflect user's selection of a non-default template.
	 *
	 * @since 1.8.8
	 *
	 * @param  array $templates Array of templates in a priority order.
	 * @return array Return template array.
	 */
	public function standalone_template_hierachy( $templates ) {

		if ( ! function_exists( 'wp_is_block_theme' ) || false === wp_is_block_theme() ) {
			return $templates;
		}

		global $post;

		if ( ! isset( $post->post_type ) || 'envira' !== $post->post_type ) {
			return $templates;
		}

		// check settings, if the user hasn't selected a custom template to override single.php, then go no further.
		$data = get_post_meta( $post->ID, '_eg_gallery_data', true );

		if ( ! $data ) {
			return apply_filters( 'envira_standalone_get_custom_hierachy', $templates, $data, $post );
		}

		if ( ! empty( $data['config']['standalone_template'] ) && '' !== $data['config']['standalone_template'] ) {
			array_unshift( $templates, esc_html( $data['config']['standalone_template'] ) );
			return apply_filters( 'envira_standalone_get_custom_hierachy', $templates, $data, $post );
		}

		return $templates;

	}

	/**
	 * With block based themes, will update template hierachy to reflect user's selection of a non-default template.
	 *
	 * @since 1.8.8
	 *
	 * @param  string $block_content Block html.
	 * @param  array  $block Details about the block.
	 * @return null
	 */
	public function insert_shortcode_into_post_block( $block_content, $block ) {

		$shortcode = $this->envira_standalone_insert_gallery_shortcode( $block_content );

		ob_start();
		$shortcode = do_shortcode( $shortcode );
		echo $shortcode; // @codingStandardsIgnoreLine
		return ob_get_clean();
	}

	/**
	 * Overrides the template for the 'envira' custom post type if user has requested a different template in settings
	 *
	 * @since 1.7.0
	 *
	 * @param object $single_template Template.
	 */
	public function standalone_get_custom_template( $single_template ) {

		if ( ! get_option( 'envira_gallery_standalone_enabled' ) ) {
			return $single_template;
		}

		global $post;

		if ( ! isset( $post->post_type ) || 'envira' !== $post->post_type ) {
			return $single_template;
		}

		// check settings, if the user hasn't selected a custom template to override single.php, then go no further
		// $instance = Envira_Gallery_Metaboxes::get_instance();
		// $template = $instance->get_config( 'standalone_template', $instance->get_config_default( 'standalone_template' ) );.
		$data = get_post_meta( $post->ID, '_eg_gallery_data', true );

		if ( ! $data ) {
			return apply_filters( 'envira_standalone_get_custom_template', $single_template, $data, $post );
		}

		if ( ! empty( $data['config']['standalone_template'] ) ) {
			$user_template = $data['config']['standalone_template'];
			// get path to current folder.
			$new_template = locate_template( $user_template );
			if ( ! file_exists( $new_template ) ) :
				// if it does not exist, then let's keep the default.
				return apply_filters( 'envira_standalone_get_custom_template', $single_template, $data, $post );
			endif;
		} else {
			return apply_filters( 'envira_standalone_get_custom_template', $single_template, $data, $post );
		}

		return apply_filters( 'envira_standalone_get_custom_template', $new_template, $data, $post );

	}

	/**
	 * Run Album Query if on an Envira Gallery or Album
	 *
	 * @since 1.7.0
	 *
	 * @param object $query The query object passed by reference.
	 * @return null      Return early if in admin or not the main query or not a single post.
	 */
	public function envira_albums_standalone_pre_get_posts( $query ) {

		// Return early if in the admin, not the main query or not a single post.
		if ( ! get_option( 'envira_gallery_standalone_enabled' ) || is_admin() || ! $query->is_main_query() || ! $query->is_single() ) {
			return;
		}

		// If not the proper post type (Envira), return early.
		$post_type = get_query_var( 'post_type' );

		if ( 'envira_album' === $post_type ) {
			do_action( 'envira_standalone_album_pre_get_posts', $query );
		}

	}

	/**
	 * Maybe inserts the Envira shortcode into the content for the page being viewed.
	 *
	 * @since 1.7.0
	 *
	 * @return null      Return early if in admin or not the main query or not a single post.
	 */
	public function envira_albums_standalone_maybe_insert_shortcode() {

		// Check we are on a single Post.
		if ( ! get_option( 'envira_gallery_standalone_enabled' ) || ! is_singular() ) {
			return;
		}

		// If not the proper post type (Envira), return early.
		$post_type = get_query_var( 'post_type' );

		if ( 'envira_album' === $post_type ) {
			add_filter( 'the_content', [ $this, 'envira_standalone_insert_album_shortcode' ] );
		}

	}

	/**
	 * Modifies the Envira Albums post type so that it is visible to the public.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args   Default post type args.
	 * @return array $args Amended array of default post type args.
	 */
	public function envira_albums_post_type( $args ) {

		// Get slug.
		$slug = $this->envira_albums_standalone_get_slug( 'albums' );

		// Change the default post type args so that it can be publicly accessible.
		$args['rewrite']    = [
			'with_front' => false,
			'slug'       => $slug,
		];
		$args['query_var']  = true;
		$args['public']     = true;
		$args['supports'][] = 'slug';

		return apply_filters( 'envira_standalone_post_type_args', $args );

	}

	/**
	 * Gets the slug from the options table. If blank or does not exist, defaults
	 * to 'envira'
	 *
	 * @since 1.0.1
	 *
	 * @param string $type Type (gallery|albums) Type.
	 * @return string $slug Slug.
	 */
	public function envira_albums_standalone_get_slug( $type ) {

		// Get slug.
		switch ( $type ) {
			case 'gallery':
				$slug = get_option( 'envira-gallery-slug' );
				if ( ! $slug || empty( $slug ) ) {
					// Fallback to check for previous version option name.
					$slug = get_option( 'envira_standalone_slug' );
					if ( ! $slug || empty( $slug ) ) {
						$slug = 'envira';
					}
				}
				break;

			case 'albums':
				$slug = get_option( 'envira-albums-slug' );
				if ( ! $slug || empty( $slug ) ) {
					$slug = 'envira_album';
				}
				break;

			default:
				$slug = 'envira'; // Fallback.
				break;
		}

		return $slug;
	}


	/**
	 * Allows the following metaboxes to be output for managing gallery and album post names:
	 * - slugdiv
	 * - wpseo_meta
	 *
	 * @since 1.0.0
	 *
	 * @param array $ids  Default metabox IDs to allow.
	 * @return array $ids Amended metabox IDs to allow.
	 */
	public function envira_standalone_slug_box( $ids ) {

		$ids[] = 'slugdiv';
		$ids[] = 'authordiv';
		$ids[] = 'wpseo_meta';

		return $ids;

	}

	/**
	 * Inserts the Envira Gallery shortcode into the content for the page being viewed.
	 *
	 * @since 1.5.7.3
	 *
	 * @global object $wp_query The current query object.
	 * @param string $content    The content to be filtered.
	 * @return string $content   Amended content with our gallery shortcode prepended.
	 */
	public function envira_standalone_insert_gallery_shortcode( $content ) {

		// Display the gallery based on the query var available.
		$id = get_query_var( 'p' );
		if ( empty( $id ) ) {
			// _get_gallery_by_slug() performs a LIKE search, meaning if two or more
			// Envira Galleries contain the slug's word in *any* of the metadata, the first
			// is automatically assumed to be the 'correct' gallery
			// For standalone, we already know precisely which gallery to display, so
			// we can use its post ID.
			global $post;
			$id = $post->ID;
		}

		$shortcode = '[envira-gallery id="' . $id . '"]';

		return $shortcode . $content;

	}

	/**
	 * Inserts the Envira Album shortcode into the content for the page being viewed.
	 *
	 * @since 1.7.0
	 *
	 * @global object $wp_query The current query object.
	 * @param string $content    The content to be filtered.
	 * @return string $content   Amended content with our gallery shortcode prepended.
	 */
	public function envira_standalone_insert_album_shortcode( $content ) {

		// Display the album based on the query var available.
		$id = get_query_var( 'p' );
		if ( empty( $id ) ) {
			// _get_album_by_slug() performs a LIKE search, meaning if two or more
			// Envira Albums contain the slug's word in *any* of the metadata, the first
			// is automatically assumed to be the 'correct' album
			// For standalone, we already know precisely which album to display, so
			// we can use its post ID.
			global $post;
			$id = $post->ID;
		}

		$shortcode = '[envira-album id="' . $id . '"]';

		return $shortcode . $content;

	}

}
