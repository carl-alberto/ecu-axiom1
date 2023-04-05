<?php
/**
 * Outputs the green Envira Gallery Header
 *
 * @since 1.5.0
 *
 * @package Envira Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

$upgrade_link = function_exists( 'envira_get_upgrade_link' ) ? envira_get_upgrade_link( 'https://soliloquywp.com/pricing/', 'envirapro', 'topbar' ) : 'https://soliloquywp.com';

?>
<div id="envira-header-temp"></div>
<div id="envira-top-notification" class="envira-header-notification">
	<p><?php esc_html_e( 'Thanks for using Envira Gallery! To create beautiful, responsive sliders,', 'envira-gallery' ); ?> <a href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank"><strong><?php esc_html_e( 'get Soliloquy.', 'envira-gallery' ); ?></strong></a></p>
</div>
<div id="envira-header" class="envira-header">
	<?php if ( apply_filters( 'envira_whitelabel', false ) ) : ?>
		<?php do_action( 'envira_whitelabel_header_logo' ); ?>
	<?php else : ?>
	<h1 class="envira-logo" id="envira-logo">
		<img src="<?php echo esc_url( $data['logo'] ); ?>" alt="<?php esc_html_e( 'Envira Gallery', 'envira-gallery' ); ?>" />
	</h1>
	<?php endif; ?>
</div>
