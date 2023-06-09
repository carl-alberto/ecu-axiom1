<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://mythemeshop.com
 * @since      1.0
 *
 * @package    MTSNBF
 * @subpackage MTSNBF/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    MTSNBF
 * @subpackage MTSNBF/public
 * @author     MyThemeShop
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'MTSNBF_Shared' ) ) {
	class MTSNBF_Shared {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * Notification bar id
		 *
		 * @since    1.0
		 *
		 * @var      boolean
		 */
		private $bar_id = false;

		/**
		 * Bar settings.
		 *
		 * @since    1.0
		 *
		 * @var      boolean
		 */
		private $bar_data = false;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0
		 * @param      string $plugin_name       The name of the plugin.
		 * @param      string $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

		}

		/**
		 * Check if Notification Bar should be displayed on front end
		 *
		 * @since    1.0
		 */
		public function get_notification_bar_data() {

			if ( is_admin() ) {
				return;
			}

			$bar_id   = false;
			$bar_data = false;

			if ( is_singular() && in_array( get_post_type(), apply_filters( 'mtsnb_force_bar_post_types', array( 'post', 'page' ) ) ) ) {

				global $post;
				$bar = get_post_meta( $post->ID, '_mtsnb_override_bar', true );

				if ( $bar && ! empty( $bar ) && 'publish' == get_post_status ( $bar_id ) ) {

					$bar_id = isset( $bar[0] ) ? $bar[0] : false;

					if ( $bar_id && ! empty( $bar_id ) ) {

						$meta_values = get_post_meta( $bar_id, '_mtsnb_data', true );

						$this->bar_id   = $bar_id;
						$this->bar_data = $meta_values;

						return;
					}
				}
			}

			$args = array(
				'post_type'      => 'mts_notification_bar',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			);

			$all_bars = get_posts( $args );
			foreach ( $all_bars as $bar ) :
				setup_postdata( $bar );

				$bar_id      = $bar->ID;
				$meta_values = get_post_meta( $bar_id, '_mtsnb_data', true );

				$passed_location_conditions = $this->test_location( $meta_values );
				$passed_referrer_conditions = $this->test_referrer( $meta_values );

				if ( $passed_location_conditions && $passed_referrer_conditions ) {

					$this->bar_id   = $bar_id;
					$this->bar_data = $meta_values;

					break;
				}

			endforeach;
			wp_reset_postdata();
		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0
		 */
		public function enqueue_styles() {

			if ( is_admin() ) {// Needed for Notification Bar preview on admin side

				$screen    = get_current_screen();
				$screen_id = $screen->id;

				if ( 'mts_notification_bar' === $screen_id ) {

					wp_enqueue_style( $this->plugin_name . 'admin', MTSNBF_PLUGIN_URL . 'public/css/wp-notification-bars-public.css', array(), $this->version, 'all' );
				}
			} else {

				if ( $this->bar_id && $this->bar_data ) {

					wp_enqueue_style( $this->plugin_name, MTSNBF_PLUGIN_URL . 'public/css/wp-notification-bars-public.css', array(), $this->version, 'all' );
				}
			}
		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0
		 */
		public function enqueue_scripts() {

			if ( ! is_admin() && $this->bar_id && $this->bar_data ) {

				wp_enqueue_script( $this->plugin_name, MTSNBF_PLUGIN_URL . 'public/js/wp-notification-bars-public.js', array( 'jquery' ), $this->version, false );
			}
		}

		/**
		 * Display Notification Bar on front end
		 *
		 * @since    1.0
		 */
		public function display_bar() {

			if ( $this->bar_id && $this->bar_data ) {

				$this->bar_output( $this->bar_id, $this->bar_data );
			}
		}

		/**
		 * Sanitize meta fields recursively.
		 *
		 * @param mixed $value Original value.
		 *
		 * @return mixed Sanitized value.
		 */
		public static function sanitize_data( $data ) {
			if ( defined( 'MTSNBF_UNFILTERED_HTML' ) && MTSNBF_UNFILTERED_HTML ) {
				return $data;
			}

			$sanitized_data = array();

			$default_sanitize = 'sanitize_text_field';
			$sanitize_map     = array(
				'active_tab'       => 'sanitize_text_field',
				'button'           => 'sanitize_text_field',
				'content_width'    => 'absint',
				'css_position'     => 'sanitize_text_field',
				'content_type'     => 'sanitize_text_field',
				'basic_link_style' => 'sanitize_text_field',
				'basic_text'       => 'wp_kses_post',
				'basic_link_url'   => 'esc_url',
				'custom_content'   => 'wp_kses_post',
				'bg_color'         => 'sanitize_hex_color',
				'txt_color'        => 'sanitize_hex_color',
				'link_color'       => 'sanitize_hex_color',
				'font_size'        => 'absint',
			);

			$data = stripslashes_deep( $data );
			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					$sanitized_data[ $key ] = self::sanitize_data( $value );
				} elseif ( isset( $sanitize_map[ $key ] ) ) {
					$sanitized_data[ $key ] = call_user_func( $sanitize_map[ $key ], $value );
				} else {
					$sanitized_data[ $key ] = call_user_func( $default_sanitize, $value );
				}
			}

			return $sanitized_data;
		}

		/**
		 * Notification bar output.
		 *
		 * @since    1.0
		 */
		public function bar_output( $bar_id, $meta_values ) {

			$button_type       = $meta_values['button'];
			$button_close_icon = '<span>+</span>';
			$button_open_icon  = '<span>+</span>';

			$style     = 'background-color:' . $meta_values['bg_color'] . ';color:' . $meta_values['txt_color'] . ';';
			$btn_style = 'background-color:' . $meta_values['bg_color'] . ';color:' . $meta_values['txt_color'] . ';';

			$shadow = '-webkit-box-shadow: 0 3px 4px rgba(0, 0, 0, 0.05);box-shadow: 0 3px 4px rgba(0, 0, 0, 0.05);';

			$width = ( isset( $meta_values['content_width'] ) && ! empty( $meta_values['content_width'] ) ) ? $meta_values['content_width'] : '960';
			$width = (int) $width + 120;

			$screen_position_class = 'mtsnb-top';
			$css_position_class    = isset( $meta_values['css_position'] ) ? 'mtsnb-' . sanitize_html_class( $meta_values['css_position'] ) : 'mtsnb-fixed';
			?>
			<div class="mtsnb mtsnb-shown <?php echo esc_attr( $screen_position_class . ' ' . $css_position_class ); ?>" id="mtsnb-<?php echo esc_attr( $bar_id ); ?>" data-mtsnb-id="<?php echo esc_attr( $bar_id ); ?>" style="<?php echo esc_attr( $style ); ?>">
				<style type="text/css">
					.mtsnb { position: <?php echo esc_html( $meta_values['css_position'] ); ?>; <?php echo esc_html( $shadow ); ?>}
					.mtsnb .mtsnb-container { width: <?php echo esc_html( $width ); ?>px; font-size: <?php echo esc_html( $meta_values['font_size'] ); ?>px;}
					.mtsnb a { color: <?php echo esc_html( $meta_values['link_color'] ); ?>;}
					.mtsnb .mtsnb-button { background-color: <?php echo esc_html( $meta_values['link_color'] ); ?>;}
				</style>
				<div class="mtsnb-container-outer">
					<div class="mtsnb-container mtsnb-clearfix">
						<?php do_action( 'before_mtsnb_content' ); ?>
						<?php $this->bar_content( $meta_values ); ?>
						<?php do_action( 'after_mtsnb_content' ); ?>
					</div>
					<?php if ( 'no_button' !== $button_type ) { ?>
						<?php
						if ( 'toggle_button' === $button_type ) {
							?>
							<a href="#" class="mtsnb-show" style="<?php echo esc_attr( $btn_style ); ?>"><?php echo wp_kses_post( $button_open_icon ); ?></a><?php } ?>
							<a href="#" class="mtsnb-hide" style="<?php echo esc_attr( $style ); ?>"><?php echo wp_kses_post( $button_close_icon ); ?></a>
						<?php } ?>
				</div>
			</div>
			<?php
		}

		/**
		 * Notification bar content.
		 *
		 * @since    1.0
		 */
		public function bar_content( $options ) {
			$class = 'mtsnb-' . sanitize_html_class( $options['content_type'] ) . '-type mtsnb-content';

			// Output
			echo '<div class="' . esc_attr( $class ) . '">';

			switch ( $options['content_type'] ) {

				case 'button':
					echo '<span class="mtsnb-text">' . wp_kses_post( $options['basic_text'] ) . '</span><a href="' . esc_url( $options['basic_link_url'] ) . '" class="mtsnb-' . esc_attr( $options['basic_link_style'] ) . '">' . wp_kses_post( $options['basic_link_text'] ) . '</a>';

					break;
				case 'custom':
					echo '<div class="mtsnb-custom-content">';
					if ( defined( 'MTSNBF_UNFILTERED_HTML' ) && MTSNBF_UNFILTERED_HTML ) {
						echo do_shortcode( html_entity_decode( $options['custom_content'] ) );
					} else {
						echo do_shortcode( wp_kses_post( html_entity_decode( $options['custom_content'] ) ) );
					}
					echo '</div>';

					break;
			}

			echo '</div>';
		}

		/**
		 * Notification bar admin preview.
		 *
		 * @since    1.0
		 */
		public function preview_bar() {

			check_ajax_referer( 'mtsnb_meta_box', 'mtsnb_meta_box_nonce' );

			$data = $_POST['form_data'];

			parse_str( $data, $options );

			$id          = $options['post_ID'];
			$meta_values = $options['mtsnb_fields'];

			if ( ! current_user_can( 'edit_post', $id ) ) {
				die( '0' );
			}

			$meta_values = self::sanitize_data( $meta_values );

			$this->bar_output( $id, $meta_values );

			die();
		}


		/**
		 * Tests if bar can be displayed based on referrer settings
		 *
		 * @since  1.0
		 */
		public function test_referrer( $meta_values ) {

			$no_condition = (bool) ( empty( $meta_values['conditions']['google']['state'] ) && empty( $meta_values['conditions']['notgoogle']['state'] ) && empty( $meta_values['conditions']['facebook']['state'] ) && empty( $meta_values['conditions']['notfacebook']['state'] ) );

			if ( $no_condition ) {
				return true; // not set, can be displayed
			}

			$referer = $this->get_referrer();

			// Show for google
			if ( ! empty( $meta_values['conditions']['google']['state'] ) ) {

				if ( empty( $referer ) ) {
					return false; // not set, don't display
				}
				$is_search_engine = $this->test_searchengine( $referer );

				if ( $is_search_engine ) {

					return true;// referrer is google search engine, display
				}

				return false;
			}

			// Don't show for google
			if ( ! empty( $meta_values['conditions']['notgoogle']['state'] ) ) {

				if ( empty( $referer ) ) {
					return true; // not set, display
				}
				$is_search_engine = $this->test_searchengine( $referer );

				if ( $is_search_engine ) {

					return false;// referrer is google search engine, don't display
				}

				return true;
			}

			// Show for facebook
			if ( ! empty( $meta_values['conditions']['facebook']['state'] ) ) {

				if ( empty( $referer ) ) {
					return false; // not set, don't display
				}

				if ( false !== strpos( $referer, 'facebook.' ) ) {

					return true;// refetrer is facebook, display
				}

				return false;
			}

			// Don't show for facebook
			if ( ! empty( $meta_values['conditions']['notfacebook']['state'] ) ) {

				if ( empty( $referer ) ) {
					return true; // not set, display
				}

				if ( false !== strpos( $referer, 'facebook.' ) ) {

					return false;// refetrer is facebook, don't display
				}

				return true;
			}
		}

		/**
		 * Tests if bar can be displayed based on location settings
		 *
		 * @since  1.0
		 */
		public function test_location( $meta_values ) {

			$no_condition = (bool) ( empty( $meta_values['conditions']['location']['state'] ) && empty( $meta_values['conditions']['notlocation']['state'] ) );

			if ( $no_condition ) {
				return true; // not set, can be displayed
			}

			// Enable on locations
			if ( ! empty( $meta_values['conditions']['location']['state'] ) ) {

				if (
					'page' === get_option( 'show_on_front' ) &&
					'0' !== get_option( 'page_for_posts' ) &&
					'0' !== get_option( 'page_on_front' ) &&
					( ( is_front_page() && isset( $meta_values['conditions']['location']['home'] ) ) || ( is_home() && isset( $meta_values['conditions']['location']['blog_home'] ) ) )
				) {

					return true;

				} elseif ( is_front_page() && isset( $meta_values['conditions']['location']['home'] ) ) {

					return true;

				} elseif ( is_single() && isset( $meta_values['conditions']['location']['posts'] ) ) {

					return true;

				} elseif ( is_page() && isset( $meta_values['conditions']['location']['pages'] ) ) {

					return true;
				}

				return false;
			}

			// Disable on locations
			if ( ! empty( $meta_values['conditions']['notlocation']['state'] ) ) {

				if (
					'page' === get_option( 'show_on_front' ) &&
					'0' !== get_option( 'page_for_posts' ) &&
					'0' !== get_option( 'page_on_front' ) &&
					( ( is_front_page() && isset( $meta_values['conditions']['notlocation']['home'] ) ) || ( is_home() && isset( $meta_values['conditions']['notlocation']['blog_home'] ) ) )
				) {

					return false;

				} elseif ( is_front_page() && isset( $meta_values['conditions']['notlocation']['home'] ) ) {

					return false;

				} elseif ( is_single() && isset( $meta_values['conditions']['notlocation']['posts'] ) ) {

					return false;

				} elseif ( is_page() && isset( $meta_values['conditions']['notlocation']['pages'] ) ) {

					return false;
				}

				return true;
			}
		}

		/**
		 * Tests if the current referrer is a search engine.
		 *
		 * @since  1.0
		 */
		public function test_searchengine( $referrer ) {
			$response = false;

			$patterns = array(
				'.google.',
			);

			foreach ( $patterns as $url ) {
				if ( false !== stripos( $referrer, $url ) ) {
					if ( '.google.' === $url ) {
						if ( $this->is_googlesearch( $referrer ) ) {
							$response = true;
						} else {
							$response = false;
						}
					} else {
						$response = true;
					}
					break;
				}
			}
			return $response;
		}

		/**
		 * Checks if the referrer is a google web-source.
		 *
		 * @since  1.0
		 */
		public function is_googlesearch( $referrer = '' ) {
			$response = true;

			// Get the query strings and check its a web source.
			$qs   = wp_parse_url( $referrer, PHP_URL_QUERY );
			$qget = array();

			foreach ( explode( '&', $qs ) as $keyval ) {
				$kv = explode( '=', $keyval );
				if ( count( $kv ) === 2 ) {
					$qget[ trim( $kv[0] ) ] = trim( $kv[1] );
				}
			}

			if ( isset( $qget['source'] ) ) {
				$response = ( 'web' === $qget['source'] );
			}

			return $response;
		}

		/**
		 * Get referrer
		 *
		 * @since    1.0
		 */
		public function get_referrer() {

			$referer = wp_unslash( $_SERVER['HTTP_REFERER'] );

			if ( $referer && ! empty( $referer ) ) {

				$secure = ( 'https' === wp_parse_url( home_url(), PHP_URL_SCHEME ) );// maybe not needed
				setcookie( 'mtsnb_referrer', esc_url( $referer ), 0, COOKIEPATH, COOKIE_DOMAIN, $secure ); // session

			} else {

				if ( isset( $_COOKIE['mtsnb_referrer'] ) ) {

					// Store referrer url.
					$referer = esc_url_raw( $_COOKIE['mtsnb_referrer'] );
				}
			}

			return $referer;
		}
	}
}
