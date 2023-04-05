<?php
/**
 * Envira Compression Settings
 *
 * @since 1.9.2
 *
 * @package Envira Gallery
 */

namespace Envira\Compress;

/**
 * Compression Settings Class
 *
 * @since 1.9.2
 */
final class Settings {

	/**
	 * Class Constructor
	 *
	 * @since 1.9.2
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Settings Init
	 *
	 * @since 1.9.2
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'envira_gallery_settings_tab_nav', [ $this, 'tab_nav' ], 10, 1 );
		add_action( 'envira_gallery_tab_settings_compression', [ $this, 'tab_output' ] );

		add_action( 'init', [ &$this, 'save_settings' ] );
	}

	/**
	 * Helper method to get compression levels.
	 *
	 * @since 1.9.4
	 *
	 * @return array
	 */
	public function get_compression_levels() {

		$levels = [
			[
				'value' => 'lossy',
				'name'  => __( 'Lossy', 'envira-gallery' ),
			],
			[
				'value' => 'glossy',
				'name'  => __( 'Glossy', 'envira-gallery' ),
			],
			[
				'value' => 'lossless',
				'name'  => __( 'Lossless', 'envira-gallery' ),
			],
		];

		return apply_filters( 'envira_compression_levels', $levels );

	}

	/**
	 * Helper method to get compression enabled for.
	 *
	 * @since 1.9.4
	 *
	 * @return array
	 */
	public function get_compression_enabled() {

		$enabled = [
			[
				'value' => 'disabled',
				'name'  => __( 'Disabled', 'envira-gallery' ),
			],
			[
				'value' => 'envira',
				'name'  => __( 'Only Envira Gallery Images', 'envira-gallery' ),
			],
			[
				'value' => 'all_media',
				'name'  => __( 'All Media Library Images', 'envira-gallery' ),
			],
		];

		return $enabled;
	}
	/**
	 * Adds a Settings Tab
	 *
	 * @since 1.9.2
	 *
	 * @param array $tabs Settings Tabs.
	 * @return array Envira settings tabs.
	 */
	public function tab_nav( $tabs ) {

		$tabs['compression'] = __( 'Compression <span class="beta-feature">Beta</span>', 'envira-gallery' );

		return $tabs;

	}

	/**
	 * Settings Output
	 *
	 * @since 1.9.2
	 *
	 * @return void
	 */
	public function tab_output() {

		$compression_enabled  = envira_get_setting( 'compression_setting', 'disabled' );
		$compression_metadata = envira_get_setting( 'compression_preserve_metadata' );
		$compression_level    = envira_get_setting( 'compression_level', 'lossless' );
		$compression_sizes    = envira_get_setting( 'compression_sizes', [] );

		ob_start(); ?>

		<div id="envira-settings-compression">

			<!-- Settings Form -->
			<form id="envira-media-delete" method="post">
				<table class="form-table">
					<tbody>

						<!-- Title -->
						<tr id="envira-image-gallery-compression-title" class="title">
							<th scope="row" colspan="2">
								<label for="envira-image-compression"><?php esc_html_e( 'Compression', 'envira-gallery' ); ?></label>
							</th>
						</tr>

						<tr id="nvira-enable-compression-box">
							<th scope="row">
								<label for="envira-enable-compression"><?php esc_html_e( 'Enable Image Compression', 'envira-gallery' ); ?></label>
							</th>
							<td>
							<select id="envira-config-image-sort" name="envira_compression_setting" class="envira-chosen" data-envira-chosen-options='{ "disable_search":"true", "width": "100%" }'>
								<?php foreach ( (array) $this->get_compression_enabled() as $i => $data ) : ?>
									<option value="<?php echo esc_html( $data['value'] ); ?>" <?php selected( $data['value'], $compression_enabled ); ?>><?php echo esc_html( $data['name'] ); ?></option>
								<?php endforeach; ?>
							</select>
							</td>
						</tr>

						<tr id="envira-compression-level-box">
							<th scope="row">
								<label for="envira-enable-beta"><?php esc_html_e( 'Compression Levels', 'envira-gallery' ); ?></label>
							</th>
							<td>
								<select id="envira-config-image-sort" name="envira_compression_levels" class="envira-chosen" data-envira-chosen-options='{ "disable_search":"true", "width": "100%" }'>
									<?php foreach ( (array) $this->get_compression_levels() as $i => $data ) : ?>
										<option value="<?php echo esc_html( $data['value'] ); ?>" <?php selected( $data['value'], $compression_level ); ?>><?php echo esc_html( $data['name'] ); ?></option>
									<?php endforeach; ?>
								</select>
								<ul>
									<li><strong>Lossy compression:</strong> Offers the highest level of compression.</li>
									<li><strong>Glossy compression:</strong> Offers an even balance of compression and image quality.</li>
									<li><strong>Lossless compression:</strong> Offers minimal compression for the best image quality.</li>
								</ul>
							</td>
						</tr>
						<tr id="envira-enable-beta-box">
							<th scope="row">
								<label for="envira-enable-compression"><?php esc_html_e( 'Preserve Metadata', 'envira-gallery' ); ?></label>
							</th>
							<td>
								<input name="compression_preserve_metadata" label="envira-enable-compression" type="checkbox" value="1" <?php checked( true, $compression_metadata ); ?> ><label><p class="description"><?php esc_html_e( 'This refers to EXIF/ITPC in your image files, like copyright, camera and lens model, etc.', 'envira-gallery' ); ?></p></label>
							</td>
						</tr>
						<tr id="envira-enable-beta-box">
							<th scope="row">
								<label for="envira-compression-image-sizes"><?php esc_html_e( 'Image Sizes', 'envira-gallery' ); ?></label>
							</th>
							<td>
								<?php
								foreach ( (array) envira_get_image_sizes() as $i => $data ) {

									// Default is a value for dyanmic sizes.
									if ( 'default' === $data['value'] ) {
										continue;
									}

									?>

									<input type="checkbox" name="envira_compression_size[<?php echo esc_html( $data['value'] ); ?>]" <?php checked( true, array_key_exists( $data['value'], $compression_sizes ) ); ?> /><label><?php echo esc_html( $data['name'] ); ?></label>
								<?php } ?>

							</td>
						</tr>

						<?php do_action( 'envira_gallery_settings_compression_box' ); ?>

					</tbody>
				</table>

				<?php wp_nonce_field( 'envira-gallery-compression-nonce', 'envira-gallery-compression-nonce' ); ?>
				<?php submit_button( __( 'Save Settings', 'envira-gallery' ), 'primary', 'envira-gallery-compression-submit', false ); ?>
			</form>
		</div>
		<?php

		echo ob_get_clean(); // @codingStandardsIgnoreLine
	}

	/**
	 * Save Compression Settings
	 *
	 * @since 1.9.2
	 *
	 * @return void
	 */
	public function save_settings() {

		if ( ! $_POST ) { // @codingStandardsIgnoreLine
			return;
		}

		// Check nonce is valid.
		if ( ! isset( $_POST['envira-gallery-compression-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['envira-gallery-compression-nonce'] ) ), 'envira-gallery-compression-nonce' ) ) { // @codingStandardsIgnoreLine
			return;
		}

		envira_update_setting( 'compression_setting', isset( $_POST['envira_compression_setting'] ) ?  wp_unslash( $_POST['envira_compression_setting'] ) : 'disabled'  );// @codingStandardsIgnoreLine
		envira_update_setting( 'compression_level', isset( $_POST['envira_compression_levels'] ) ?  wp_unslash( $_POST['envira_compression_levels'] ) : 'lossless' );// @codingStandardsIgnoreLine
		envira_update_setting( 'compression_preserve_metadata', isset( $_POST['compression_preserve_metadata'] ) ? 1 : 0 );
		envira_update_setting( 'compression_sizes', isset( $_POST['envira_compression_size'] ) ? wp_unslash( $_POST['envira_compression_size'] ) : array() );// @codingStandardsIgnoreLine

	}
}
