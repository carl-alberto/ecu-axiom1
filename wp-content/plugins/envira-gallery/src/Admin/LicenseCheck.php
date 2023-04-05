<?php
/**
 * License Check class.
 *
 * @since 1.9.0
 *
 * @package Envira Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

namespace Envira\Admin;

use Envira\Admin\License;

/**
 * Undocumented class
 */
class LicenseCheck {

	/**
	 * Class Constrtuctor
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function init() {

		$key    = envira_get_license_key();
		$option = get_option( 'envira_gallery' );

		if ( defined( 'X_VERSION' ) ) {
			return;
		}

		if ( ! $key || ( isset( $option['is_expired'] ) && $option['is_expired'] ) ) {
			add_action( 'admin_init', [ $this, 'enqueue_styles' ] );

			add_filter( 'replace_editor', [ $this, 'output' ], 10, 2 );
			add_filter( 'admin_footer_text', [ $this, 'admin_footer' ], 11, 1 );

		}

	}

	/**
	 * Helper Method to load styles
	 *
	 * @since 1.9.0
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		// Load necessary metabox styles.
		wp_register_style( ENVIRA_SLUG . '-license-style', plugins_url( 'assets/css/license.css', ENVIRA_FILE ), [], ENVIRA_VERSION );
		wp_enqueue_style( ENVIRA_SLUG . '-license-style' );

	}

	/**
	 * Undocumented function
	 *
	 * @param bool   $false false.
	 * @param object $post post object.
	 * @return bool
	 */
	public function output( $false, $post ) {

		if ( ! is_envira_page() ) {
			return false;
		}

		if ( 'auto-draft' !== $post->post_status ) {
			return false;
		}
		?>
			<div id="envira-license-check">
				<a class="close" href="<?php echo esc_url( admin_url( 'edit.php?post_type=envira' ) ); ?>">
					<svg
						xmlns="http://www.w3.org/2000/svg"
						width="30"
						height="30"
						viewBox="0 0 18 18"
					>
						<path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
					</svg>
				</a>
				<div>
				<h1 class="envira-logo" id="envira-logo">
					<img src="<?php echo esc_url( plugins_url( 'assets/images/envira-logo-color.png', ENVIRA_FILE ) ); ?>" alt="<?php esc_html_e( 'Envira Gallery', 'envira-gallery' ); ?>" width="339" height="26" />
				</h1>
					<h3>Heads up! An Envira Gallery License is required.</h3>
					<p>To create more galleries, please verify your Envira Gallery License is active.</p>

					<div class="button-group">
						<a class="button button-primary" href="<?php echo esc_url( admin_url( 'edit.php?post_type=envira&page=envira-gallery-settings' ) ); ?>">Enter License Key</a>
						<a class="button button-outline" href="https://enviragallery.com/pricing/?utm_source=proplugin&utm_medium=expired&utm_campaign=licensecheck">Get Envira Gallery</a>
					</div>
				</div>

			</div>
		<?php

		return true;
	}

	/**
	 * Removes Admin Footer from Envira Pages.
	 *
	 * @since 1.9.0
	 *
	 * @param string $content Footer Content.
	 * @return string Footer Content.
	 */
	public function admin_footer( $content ) {

		if ( is_envira_page() ) {
			return '';
		}

		return $content;

	}
}
