<?php
/**
 * Outputs the Gallery Type Tab Selector and Panels
 *
 * @since   1.5.0
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

$gallery_data = envira_get_gallery( $data['post']->ID );
$type         = envira_get_config( 'type', $gallery_data ) !== '' ? envira_get_config( 'type', $gallery_data ) : 'default';

?>
<h2 id="envira-types-nav" class="nav-tab-wrapper envira-tabs-nav" data-container="#envira-types" data-update-hashbang="0">
	<label class="nav-tab nav-tab-native-envira-gallery<?php echo ( ( 'default' === $type ) ? ' envira-active' : '' ); ?>" for="envira-gallery-type-default" data-tab="#envira-gallery-native">
		<input id="envira-gallery-type-default" type="radio" name="_envira_gallery[type]" value="default" <?php checked( $type, 'default' ); ?> />
		<?php if ( apply_filters( 'envira_whitelabel', false ) ) { ?>
			<span><?php esc_html_e( 'Native Gallery', 'envira-gallery' ); ?></span>
		<?php } else { ?>
			<span><?php esc_html_e( 'Native Envira Gallery', 'envira-gallery' ); ?></span>
		<?php } ?>

	</label>

	<a href="#envira-gallery-external" title="<?php esc_attr_e( 'Galleries from Other Sources', 'envira-gallery' ); ?>" class="nav-tab nav-tab-external-gallery<?php echo 'default' !== $type ? ' envira-active' : ''; ?>">
		<span><?php esc_html_e( 'Galleries from Other Sources', 'envira-gallery' ); ?></span>
	</a>
</h2>

<!-- Types -->
<div id="envira-types" data-navigation="#envira-types-nav">
	<!-- Native Envira Gallery - Drag and Drop Uploader -->
	<div id="envira-gallery-native" class="envira-tab envira-clear<?php echo 'default' === $type ? ' envira-active' : ''; ?>">
		<!-- Errors -->
		<div id="envira-gallery-upload-error"></div>

		<!-- WP Media Upload Form -->
			<?php
			media_upload_form();
			?>
		<script type="text/javascript">
			var post_id = <?php echo esc_attr( $data['post']->ID ); ?>, shortform = 3;
		</script>
		<input type="hidden" name="post_id" id="post_id" value="<?php echo esc_attr( $data['post']->ID ); ?>" />
	</div>

	<!-- External Gallery -->
	<div id="envira-gallery-external" class="envira-tab envira-clear<?php echo 'default' !== $type ? ' envira-active' : ''; ?>">
		<?php
		// If one or more External Gallery Types are registered, display them now.
		if ( count( $data['types'] ) > 1 ) {
			?>
			<p class="envira-intro"><?php esc_html_e( 'Select Your Source', 'envira-gallery' ); ?></p>
			<ul id="envira-gallery-types-nav">
				<?php
				foreach ( $data['types'] as $id => $title ) {
					// Don't output the default type as an option here.
					if ( 'default' === $id ) {
						continue;
					}

					// Output the type as a radio option.
					?>
					<li id="envira-gallery-type-<?php echo sanitize_html_class( $id ); ?>"<?php echo 'default' !== $type ? ' envira-active' : ''; ?>>
						<label for="envira-gallery-type-<?php echo esc_attr( $id ); ?>">
							<input id="envira-gallery-type-<?php echo sanitize_html_class( $id ); ?>" type="radio" name="_envira_gallery[type]" value="<?php echo esc_attr( $id ); ?>" <?php checked( $type, $id ); ?> />
							<div class="icon"></div>
							<div class="title"><?php echo esc_html( $title ); ?></div>
						</label>
					</li>
					<?php
				}
				?>
			</ul>
			<?php
		} else {
			// No External Gallery Types are registered.
			// If we're on the Lite version, show a notice.
			$option = get_option( 'envira_gallery' );

			if ( ! empty( $option['type'] ) ) {

				/*Get License */
				$license_type      = strtolower( $option['type'] );
				$installed_plugins = array_keys( get_plugins() );
				$activated_plugins = get_option( 'active_plugins' );
				$needs_update      = false;
				$is_installed      = [];

				if ( in_array( 'envira-instagram/envira-instagram.php', $installed_plugins, true ) && ! in_array( 'envira-instagram/envira-instagram.php', $activated_plugins, true ) ) {
					$is_installed['instagram'] = true;
				}
				if ( in_array( 'envira-featured-content/envira-featured-content.php', $installed_plugins, true ) && ! in_array( 'envira-featured-content/envira-featured-content.php', $activated_plugins, true ) ) {
					$is_installed['featured-content'] = true;
				}

				switch ( $license_type ) {
					case 'platinum':
					case 'silver':
					case 'bronze':
					case 'pro':
					case 'agency':
						break;

					case 'basic':
					case '':
					default:
						$needs_update = true;
						$is_installed = [];
						break;
				}

				if ( $needs_update || ! empty( $is_installed ) ) {

					$plugin_link  = admin_url( 'edit.php?post_type=envira&page=envira-gallery-addons' );
					$upgrade_link = $needs_update ? envira_get_upgrade_link() : $plugin_link;
					?>

					<?php if ( ! empty( $is_installed ) ) { ?>

					<p class="envira-intro"><?php esc_html_e( 'It looks like you have addons installed but NOT activated which import images from external sources.', 'envira-gallery' ); ?></p>

					<?php } elseif ( $needs_update ) { ?>

					<p class="envira-intro"><?php esc_html_e( 'If you upgrade your basic Pro account, you can create dynamic galleries with Envira addons.', 'envira-gallery' ); ?></p>

					<?php } ?>

					<ul id="envira-gallery-types-nav">
						<?php if ( $needs_update || in_array( 'instagram', $is_installed, true ) ) { ?>
						<li id="envira-gallery-type-instagram">
							<a href="<?php echo esc_url( $upgrade_link ); ?>" title="<?php esc_attr_e( 'Build Galleries from Instagram images.', 'envira-gallery' ); ?>" target="_blank">
								<div class="icon"></div>
								<div class="title"><?php esc_html_e( 'Instagram', 'envira-gallery' ); ?></div>
							</a>
						</li>
						<?php } ?>
						<?php if ( $needs_update || in_array( 'featured-content', $is_installed, true ) ) { ?>
						<li id="envira-gallery-type-fc">
							<a href="<?php echo esc_url( $upgrade_link ); ?>" title="<?php esc_attr_e( 'Build Galleries from Featured Content.', 'envira-gallery' ); ?>" target="_blank">
								<div class="icon"></div>
								<div class="title"><?php esc_html_e( 'Featured Content', 'envira-gallery' ); ?></div>
							</a>
						</li>
						<?php } ?>
					</ul>
					<p>
						<?php esc_html_e( 'Envira Pro allows you to build galleries from Instagram photos, images from your posts, and more.', 'envira-gallery' ); ?>
					</p>

					<?php if ( ! empty( $is_installed ) ) { ?>

					<p>
						<a href="<?php echo esc_url( $plugin_link ); ?>" class="button button-primary button-x-large" title="<?php esc_attr_e( 'Click Here to Activate Addons', 'envira-gallery' ); ?>" target="_blank">
							<?php esc_html_e( 'Click Here to Activate Addons', 'envira-gallery' ); ?>
						</a>
					</p>

					<?php } elseif ( $needs_update ) { ?>

					<p>
						<a href="<?php echo esc_url( $upgrade_link ); ?>" class="button button-primary button-x-large" title="<?php esc_attr_e( 'Click Here to Upgrade', 'envira-gallery' ); ?>" target="_blank">
							<?php esc_html_e( 'Click Here to Upgrade', 'envira-gallery' ); ?>
						</a>
					</p>

					<?php } ?>



					<?php

				}
			} else {

				?>

					<p><?php esc_html_e( 'It doesn\'t look like you have any Addons activated which import images from external sources.', 'envira-gallery' ); ?></p>

				<?php

			}
		}
		?>
	</div>
</div>
