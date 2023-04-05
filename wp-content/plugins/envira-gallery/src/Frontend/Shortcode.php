<?php
/**
 * Shortcode class.
 *
 * @since 1.7.0
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

namespace Envira\Frontend;

use Envira\Frontend\Gallery_Markup\Layouts\Automatic;
use Envira\Frontend\Gallery_Markup\Layouts\Blogroll;
use Envira\Frontend\Gallery_Markup\Layouts\Grid;
use Envira\Frontend\Gallery_Markup\Layouts\Mason;
use Envira\Frontend\Gallery_Markup\Layouts\Square;
use Envira\Frontend\Gallery_Markup\Vars;
use Envira\Utils\Shortcode_Utils;

/**
 * Envira Gallery Shortcode Class.
 *
 * @since 1.7.0
 */
final class Shortcode {
	use Vars;
	/**
	 * Un parsed shortcode attributes.
	 *
	 * For backwards compatibility, usage of parsed_attrs is preferred.
	 *
	 * @var array
	 */
	private $attrs;

	/**
	 * Parsed shortcode attributes.
	 *
	 * @var array
	 */
	private $parsed_attrs;

	/**
	 * Holds image URLs for indexing.
	 *
	 * @since 1.7.0
	 *
	 * @var array
	 */
	private $index = [];

	/**
	 * Array of id's from of galleries on the page. Used to obtain unique_id
	 *
	 * @var string[]
	 */
	private $galleries_on_page = [];

	/**
	 * Current gallery should_cache flag.
	 *
	 * @var bool
	 */
	private $should_cache = true;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.7.0
	 */
	public function __construct() {
		$this->is_mobile = envira_mobile_detect()->isMobile();
		$this->init();
	}

	/**
	 * Load hooks and filters.
	 *
	 * @since 1.7.0
	 */
	public function init() {
		$this->register_scripts();

		add_shortcode( 'envira-gallery', [ $this, 'shortcode' ] );
	}

	/**
	 * Register scripts
	 *
	 * @since 1.7.0
	 *
	 * @access public
	 * @return void
	 */
	private function register_scripts() {
		$version = is_envira_debug_on() ? time() . '-' . ENVIRA_VERSION : ENVIRA_VERSION;

		// Register main gallery style, justified gallery style and main gallery script.
		wp_register_style( ENVIRA_SLUG . '-style', plugins_url( 'assets/css/envira.css', ENVIRA_FILE ), [], $version );
		wp_register_style( ENVIRA_SLUG . '-jgallery', plugins_url( 'assets/css/justifiedGallery.css', ENVIRA_FILE ), [], $version );
		wp_register_script( ENVIRA_SLUG . '-script', envira_script( 'assets/js/min/envira-min.js' ), [ 'jquery' ], $version, true );

		$loader_color = esc_attr( envira_get_setting( 'loader_color', '#000000' ) );

		if ( '#000000' !== $loader_color ) {
			wp_add_inline_style(
				ENVIRA_SLUG . '-style',
				".envira-loader div { background-color: $loader_color !important; }"
			);
		}

		// Run a hook so that third party plugins can add additional JS scripts only for Envira.
		do_action( 'envira_gallery_after_register_scripts', ENVIRA_VERSION );
	}

	/**
	 * Creates the shortcode for the plugin.
	 *
	 * @since 1.7.0
	 *
	 * @param array $attrs Array of shortcode attributes.
	 *
	 * @return string        The gallery output.
	 */
	public function shortcode( $attrs ) {
		if ( ! $this->setup( $attrs ) ) {
			return '';
		}

		// This filter detects if something needs to be displayed BEFORE a gallery is displayed, such as a password form.
		$pre_gallery_html = apply_filters( 'envira_abort_gallery_output', null, $this->data, $this->gallery_id, $attrs );

		if ( $pre_gallery_html ) {
			// If there is HTML, then we stop trying to display the gallery and return THAT HTML.
			return apply_filters( 'envira_gallery_output', $pre_gallery_html, $this->data );
		}

		// If this is a feed view, customize the output and return early.
		if ( is_feed() ) {
			return $this->render_feed_output();
		}

		return $this->render();
	}

	/**
	 * Setup initial object properties.
	 *
	 * @param array $attrs Shortcode attributes.
	 *
	 * @return bool
	 */
	private function setup( $attrs ) {
		// hook that would allow any initial checks and bails (such as yoast snippet previews).
		$shortcode_start = apply_filters( 'envira_gallery_shortcode_start', true, $attrs );

		// for warning or bails, we add a note in server logs if ENVIRA_DEBUG is true.
		if ( ! empty( $shortcode_start['action'] ) && in_array( $shortcode_start['action'], [ 'bail', 'warning' ], true ) ) {
			if ( is_envira_debug_on() ) {
				error_log( "shortcode_start - {$shortcode_start['action']}" );
				error_log( print_r( $shortcode_start, true ) );
			}

			// for bails, we bail.
			if ( 'bail' === $shortcode_start['action'] ) {
				return false;
			}
		}

		if ( is_admin() && ! wp_doing_ajax() && ( empty( $shortcode_start['action'] ) || 'nobail' !== $shortcode_start['action'] ) ) {
			// if it's the admin BUT it's not admin ajax from the frontend, then return.
			return false;
		}

		// Run a hook before the gallery output begins but after scripts and inits have been set.
		do_action( 'envira_gallery_start_shortcode', $attrs );

		$data_and_id = Shortcode_Utils::get_data_and_id( $attrs );

		if ( ! $data_and_id ) {
			return false;
		}

		$gallery_id = $data_and_id['id'];

		$statuses         = current_user_can( 'edit_posts' ) ? [ 'publish', 'draft', 'private' ] : [ 'publish', 'private' ];
		$allowed_statuses = apply_filters( 'envira_allowed_publish_statuses', $statuses );
		$post_status      = is_numeric( $gallery_id ) ? get_post_status( absint( $gallery_id ) ) : false;

		if ( $post_status && ! in_array( $post_status, $allowed_statuses, true ) ) {
			return false;
		}

		$options_id   = $data_and_id['options_id'];
		$data         = $data_and_id['data'];
		$parsed_attrs = $data_and_id['parsed_attrs'];
		$limit        = $parsed_attrs['limit'];

		// TODO below in_array check is probably not needed since now all galleries have a unique id. Therefor cache the same gallery is now possible.
		// Don't cache is we have processed this gallery already, due to unique <div id="X">.
		$cache = $parsed_attrs['cache'] && ! $limit && ! in_array( $gallery_id, $this->galleries_on_page, true );

		// Give the shortcode a unique id in case it was rendered before on the page.
		$unique_id = $gallery_id;
		if ( in_array( "$gallery_id", $this->galleries_on_page, true ) ) {
			$suffix = array_count_values( $this->galleries_on_page )[ "$gallery_id" ];

			if ( $suffix > 0 ) {
				$unique_id = "$gallery_id-$suffix";
			}
		}

		// Allow the data to be filtered before it is stored and used to create the gallery output.
		$data = apply_filters( 'envira_gallery_pre_data', $data, $gallery_id, ! $this->should_cache );

		// If there is no data to output or the gallery is inactive, do nothing.
		if ( ! $data || empty( $data['gallery'] ) || isset( $data['status'] ) && 'inactive' === $data['status'] && ! is_preview() ) {
			// Add a hook in case a message or something should be displayed, depending on the circumstances.
			do_action( 'envira_gallery_no_gallery_images', $data );

			return false;
		}

		// Ensure correct values in $data var after being filtered.
		$data['gallery_id'] = "$gallery_id";
		$data['id']         = "$unique_id";

		if ( 'true' === $parsed_attrs['dynamic'] || ( 0 === strpos( $parsed_attrs['dynamic'], 'folder-' ) ) ) {
			$data['config']['type'] = 'dynamic';
			$data['dynamic_id']     = $options_id;
			$cache                  = false;
		}

		// Forcefully turn off slideshow if addon not installed.
		if (
				isset( $data['config']['slideshow'] )
				&& 1 === intval( $data['config']['slideshow'] )
				&& ! class_exists( 'Envira_Slideshow' )
		) {
			$data['config']['slideshow'] = false;
			// Save this change to gallery data.
			update_post_meta( $options_id, '_eg_gallery_data', $data );
			// flush cache for this particular gallery.
			envira_flush_gallery_caches( $data['id'] );
		}

		// Remove items with pending status.
		$items = [];
		foreach ( $data['gallery'] as $item_id => $item ) {
			if ( isset( $item['status'] ) && 'pending' === $item['status'] && ! is_preview() ) {
				continue;
			}
			$items[ $item_id ] = $item;
		}

		$data['gallery'] = $items;

		// Limit the number of images returned, if specified.
		// [envira-gallery id="123" limit="10"] would only display 10 images.
		if ( $limit > 0 ) {
			$data['gallery'] = array_slice( $data['gallery'], 0, $limit, true );
		}

		$data = ! isset( $data['config']['layout'] ) || ! $data['config']['layout']
				? envira_convert_columns_to_layouts( $data, $unique_id )
				: envira_override_layout_settings( $data );

		// Used on random gallery widget.
		if ( $parsed_attrs['gallery_images_raw'] ) {
			$data['gallery'] = json_decode( urldecode( $parsed_attrs['gallery_images_raw'] ), ARRAY_A );
		}

		// Only set object properties below and keep alphabetized.
		$this->attrs               = $attrs;
		$this->galleries_on_page[] = "$gallery_id";
		$this->data                = $data;
		$this->index[ $unique_id ] = array_values( $data['gallery'] );
		$this->parsed_attrs        = $parsed_attrs;
		$this->should_cache        = apply_filters( 'envira_gallery_should_cache', $cache, $this->data ) && ! is_envira_debug_on();

		// To avoid confusion with ids.
		$this->gallery_id = $gallery_id; // Also in data['gallery_id'].
		$this->options_id = $options_id; // Value of dynamic_id if present or else gallery_id.
		$this->unique_id  = $unique_id; // Also in data['id'].

		return true;
	}

	/**
	 * Outputs only the first image of the gallery inside a regular <div> tag
	 * to avoid styling issues with feeds.
	 *
	 * @since 1.0.5
	 *
	 * @return string $gallery Custom gallery output for feeds.
	 */
	private function render_feed_output() {
		$item = reset( $this->data );
		$id   = key( $this->data );

		$src   = esc_url( envira_get_image_src( $id, $item, $this->data ) );
		$title = esc_attr( trim( $item['title'] ) );
		$alt   = esc_attr( trim( $item['alt'] ) );

		$image = "<img class=\"envira-gallery-feed-image\" tabindex=\"0\" src=\"$src\" title=\"$title\" alt=\"$alt\" />";

		$gallery = "<div class=\"envira-gallery-feed-output\">$image</div>";

		return apply_filters( 'envira_gallery_feed_output', $gallery, $this->data );
	}

	/**
	 * Render method.
	 *
	 * @return string The rendered shortcode.
	 */
	private function render() {
		// Prepare variables.
		$gallery_theme    = envira_get_config( 'gallery_theme', $this->data );
		$layout           = envira_get_config( 'layout', $this->data );
		$lightbox_enabled = envira_get_config( 'lightbox_enabled', $this->data );
		$lightbox_theme   = envira_get_config( 'lightbox_theme', $this->data );
		$transient_name   = '_eg_fragment_' . ( $this->is_mobile ? 'mobile_' : '' ) . $this->gallery_id;

		// Load scripts and styles.
		wp_enqueue_style( ENVIRA_SLUG . '-style' );
		wp_enqueue_style( ENVIRA_SLUG . '-jgallery' );
		Shortcode_Utils::enqueue_main_script( $this->data );

		// Load custom gallery themes if necessary.
		switch ( $layout ) {
			case 'automatic':
				break;
			default:
				if ( 'base' !== $gallery_theme ) {
					envira_load_gallery_theme( $gallery_theme );
				}
		}

		// Load custom lightbox themes if necessary, don't load if user hasn't enabled lightbox.
		if ( $lightbox_enabled ) {
			envira_load_lightbox_theme( $lightbox_theme );
		}

		// Run a hook before the gallery output begins but after scripts and inits have been set.
		do_action( 'envira_gallery_before_output', $this->data );

		$gallery_markup = '';

		if ( $this->should_cache ) {
			$gallery_markup = apply_filters( 'envira_gallery_get_transient_markup', get_transient( $transient_name ), $this->data );
		}

		if ( empty( $gallery_markup ) ) {
			$config = [
				'type'       => $this->parsed_attrs['type'],
				'limit'      => $this->parsed_attrs['limit'],
				'is_mobile'  => $this->is_mobile,
				'data'       => $this->data,
				'gallery_id' => $this->gallery_id,
				'options_id' => $this->options_id,
				'unique_id'  => $this->unique_id,
				'index'      => $this->index,
			];

			switch ( $layout ) {
				case 'automatic':
					$gallery_markup = ( new Automatic( $config ) )->markup();
					break;
				case 'blogroll':
					$gallery_markup = ( new Blogroll( $config ) )->markup();
					break;
				case 'grid':
					$gallery_markup = ( new Grid( $config ) )->markup();
					break;
				case 'mason':
					$gallery_markup = ( new Mason( $config ) )->markup();
					break;
				case 'square':
					$gallery_markup = ( new Square( $config ) )->markup();
					break;
				default:
					$gallery_markup     = '';
					$this->should_cache = false;
					if ( is_envira_debug_on() ) {
						error_log( "Layout not found: $layout" );
					}
					// no default.
			}

			if ( $this->should_cache ) {
				set_transient( $transient_name, $gallery_markup, DAY_IN_SECONDS );
			}
		}

		// add breadcrumbs and back to gallery buttons, do not add it before this or risk it being cached.
		/* album breadcrumbs & back to gallery */
		$prepend  = apply_filters( 'envira_gallery_get_album_breadcrumbs', '', $this->data );
		$prepend .= apply_filters( 'envira_gallery_get_back_to_gallery_preppend', '', $this->data );
		$append   = apply_filters( 'envira_gallery_get_back_to_gallery_append', '', $this->data );

		$gallery_markup = class_exists( 'Envira_Albums' )
				? $prepend . $gallery_markup . $append
				: $gallery_markup;

		do_action( 'envira_gallery_end_shortcode', $this->attrs, $this->data );

		// Return the gallery HTML.
		return apply_filters( 'envira_gallery_output', $gallery_markup, $this->data );
	}
}
