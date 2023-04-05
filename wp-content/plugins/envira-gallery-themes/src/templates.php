<?php
/**
 * Lightbox Template functions
 *
 * @package Envira Gallery Themes
 */

/**
 * Envirabox Infinity Template function.
 *
 * @since 1.8.0
 *
 * @access public
 * @param mixed $data Incoming gallery data.
 * @return html
 */
function envirabox_sidecar_template( $data ) {

	// Build out the lightbox template.
	$envirabox_wrap_css_classes = apply_filters( 'envirabox_wrap_css_classes', 'envirabox-wrap', $data );

	$lightbox_themes = envira_get_lightbox_themes();
	$key             = array_search( envira_get_config( 'lightbox_theme', $data ), array_column( $lightbox_themes, 'value' ), true );

	// if the theme could not be located - possible that this is a theme from gallery themes addon, and the addon is not activated/installed.
	$theme           = ( empty( $key ) ) ? 'base_dark' : envira_get_config( 'lightbox_theme', $data );
	$envirabox_theme = apply_filters( 'envirabox_theme', 'envirabox-theme-' . $theme, $data );

	$icons    = array(
		'audio'      => '    <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 512 512"><path d="M384 512c-8.848 0-16-7.152-16-16L208 352h-80c-8.832 0-16-7.152-16-16V208c0-8.848 7.168-16 16-16h80L368 16c0-8.848 7.152-16 16-16 8.848 0 16 7.152 16 16v480c0 8.848-7.152 16-16 16z"></path></svg>',
		'fullscreen' => '<svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 512 512"><path d="M512 0H304l80 80-96 96 48 48 96-96 80 80zM512 512V304l-80 80-96-96-48 48 96 96-80 80zM0 512h208l-80-80 96-96-48-48-96 96-80-80zM0 0v208l80-80 96 96 48-48-96-96 80-80z"></path></svg>',
	);
	$template = '<div id="envirabox-' . $data['id'] . '" data-envirabox-id="' . $data['id'] . '" class="envirabox-container ' . $envirabox_theme . ' ' . $envirabox_wrap_css_classes . '" role="dialog">';

		$template .= '<div class="envirabox-bg"></div>';
		$template .= '<div class="envirabox-outer"><div class="envirabox-inner">';

			$template  = apply_filters( 'envirabox_inner_above', $template, $data, $icons );
			$template .= '<div class="envirabox-title-wrap">';

				$template .= '<h2 class="envirabox-title"></h2>';

	if ( envira_get_config( 'image_counter', $data ) && ! envira_get_config( 'lightbox_woocommerce', $data ) ) {
						$template = apply_filters( 'envirabox_theme_image_counter', '<div class="envirabox-image-counter"><span data-envirabox-index></span> / <div class="envirabox-count"><span data-envirabox-count></span></div></div>', $theme, $data );
	}

			$template     .= '</div>';
				$template .= '<div class="envirabox-toolbar">';

					$template .= '<div class="envira-close-button"><a data-envirabox-close class="envirabox-item envirabox-close envirabox-button--close" title="' . __( 'Close', 'envira-gallery' ) . '" href="#">                   <svg
					xmlns="http://www.w3.org/2000/svg"
					width="30px"
					height="30px"
					viewBox="0 0 768 768"
				   >
					<path d="M607.5 205.5L429 384l178.5 178.5-45 45L384 429 205.5 607.5l-45-45L339 384 160.5 205.5l45-45L384 339l178.5-178.5z"></path>
				   </svg></a></div>';
				$template      = apply_filters( 'envirabox_actions', $template, $data, $icons );

				$template     .= '</div>';
					$template .= '<div class="envira-thumbs-button"><a data-envirabox-thumbs class="envirabox-item envira-thumbs-button envirabox-button--thumbs" title="' . __( 'Toggle Thumbnails', 'envira-gallery' ) . '" href="javascript:void(0)"></a></div>';

				$template     .= '<div class="envirabox-navigation">';
					$template .= '<a data-envirabox-prev title="' . __( 'Prev', 'envira-gallery' ) . '" class="envirabox-arrow envirabox-arrow--left envirabox-nav envirabox-prev" href="#"><span>                     <svg
					xmlns="http://www.w3.org/2000/svg"
					width="45px"
					height="45px"
					viewBox="0 0 768 768"
				   >
					<path d="M493.5 531l-45 45-192-192 192-192 45 45-147 147z"></path>
				   </svg></span></a>';
					$template .= '<a data-envirabox-next title="' . __( 'Next', 'envira-gallery' ) . '" class="envirabox-arrow envirabox-arrow--right envirabox-nav envirabox-next" href="#"><span>                         <svg
					xmlns="http://www.w3.org/2000/svg"
					width="45px"
					height="45px"
					viewBox="0 0 768 768"
				   >
					<path d="M274.5 531l147-147-147-147 45-45 192 192-192 192z"></path>
				   </svg></span></a>';
				$template     .= '</div>';

			$template .= '<div class="envirabox-stage"></div>';

			$template = apply_filters( 'envirabox_inner_below', $template, $data, $icons );

			$template .= '</div></div></div>';

			return str_replace( "\n", '', $template );

}

if ( ! function_exists( 'envirabox_infinity_template' ) ) :
	/**
	 * Envirabox Infinity Template function.
	 *
	 * @since 1.8.0
	 *
	 * @access public
	 * @param mixed $data Incoming gallery data.
	 * @return html
	 */
	function envirabox_infinity_template( $data ) {

		// Build out the lightbox template.
		$envirabox_wrap_css_classes = apply_filters( 'envirabox_wrap_css_classes', 'envirabox-wrap', $data );

		$lightbox_themes = envira_get_lightbox_themes();
		$key             = array_search( envira_get_config( 'lightbox_theme', $data ), array_column( $lightbox_themes, 'value' ), true );

		// if the theme could not be located - possible that this is a theme from gallery themes addon, and the addon is not activated/installed.
		$theme           = ( empty( $key ) ) ? 'base_dark' : envira_get_config( 'lightbox_theme', $data );
		$envirabox_theme = apply_filters( 'envirabox_theme', 'envirabox-theme-' . $theme, $data );

		$icons = array();

		$template = '<div id="envirabox-' . $data['id'] . '" data-envirabox-id="' . $data['id'] . '" class="envirabox-container ' . $envirabox_theme . ' ' . $envirabox_wrap_css_classes . '" role="dialog">';

			$template .= '<div class="envirabox-bg"></div>';
			$template .= '<div class="envirabox-outer"><div class="envirabox-inner">';

			$template  = apply_filters( 'envirabox_inner_above', $template, $data, $icons );
			$template .= '<div class="envirabox-title-wrap"><div class="envirabox-title-inner">';

		if ( 'title' === envira_get_config( 'lightbox_title_caption', $data ) || 'title_caption' === envira_get_config( 'lightbox_title_caption', $data ) ) {
			$template .= '<div class="envirabox-title"></div>';
		}
		if ( 'caption' === envira_get_config( 'lightbox_title_caption', $data ) || 'title_caption' === envira_get_config( 'lightbox_title_caption', $data ) ) {
			$template .= '<div class="envirabox-caption"></div>';
		}

		if ( envira_get_config( 'image_counter', $data ) ) {
					$template .= apply_filters( 'envirabox_theme_image_counter', '<div class="envirabox-image-counter">' . __( 'Image', 'envira-gallery' ) . ' <span data-envirabox-index></span> ' . __( 'of', 'envira-gallery' ) . ' <span data-envirabox-count></span></div>', $theme, $data );
		}
					$template .= '</div>';
					$template .= '<div class="envirabox-woocommerce-container"></div>';

			$template     .= '</div>';
				$template .= '<div class="envirabox-toolbar">';

					$template .= '<div class="envira-close-button"><a data-envirabox-close class="envirabox-item envirabox-close envirabox-button--close" title="' . __( 'Close', 'envira-gallery' ) . '" href="#">                   <svg
					xmlns="http://www.w3.org/2000/svg"
					width="30px"
					height="30px"
					viewBox="0 0 768 768"
				   >
					<path d="M607.5 205.5L429 384l178.5 178.5-45 45L384 429 205.5 607.5l-45-45L339 384 160.5 205.5l45-45L384 339l178.5-178.5z"></path>
				   </svg></a></div>';
		$template             .= '<div class="envirabox-icons">';
					$template  = apply_filters( 'envirabox_actions', $template, $data, $icons );
					$template .= '</div>';

				$template     .= '</div>';
					$template .= '<div class="envira-thumbs-button"><a data-envirabox-thumbs class="envirabox-item envira-thumbs-button envirabox-button--thumbs" title="' . __( 'Toggle Thumbnails', 'envira-gallery' ) . '" href="javascript:void(0)"></a></div>';

				$thumbs = envira_get_config( 'thumbnails', $data ) ? ' has-thumbs' : '';

		if ( envira_get_config( 'arrows', $data ) ) {

					$template .= '<div class="envirabox-navigation' . $thumbs . '">';
					$template .= '<a data-envirabox-prev title="' . __( 'Prev', 'envira-gallery' ) . '" class="envirabox-arrow envirabox-arrow--left envirabox-nav envirabox-prev" href="#"><span>                     <svg
					xmlns="http://www.w3.org/2000/svg"
					width="45px"
					height="45px"
					viewBox="0 0 768 768"
				   >
					<path d="M493.5 531l-45 45-192-192 192-192 45 45-147 147z"></path>
				   </svg></span></a>';
					$template .= '<a data-envirabox-next title="' . __( 'Next', 'envira-gallery' ) . '" class="envirabox-arrow envirabox-arrow--right envirabox-nav envirabox-next" href="#"><span>                         <svg
					xmlns="http://www.w3.org/2000/svg"
					width="45px"
					height="45px"
					viewBox="0 0 768 768"
				   >
					<path d="M274.5 531l147-147-147-147 45-45 192 192-192 192z"></path>
				   </svg></span></a>';
				$template     .= '</div>';

		}

			$template .= '<div class="envirabox-stage"></div>';

			$template = apply_filters( 'envirabox_inner_below', $template, $data, $icons );

			$template .= '</div></div></div>';

			return str_replace( "\n", '', $template );

	}

endif;

/**
 * Helper method to retrieve the gallery lightbox template
 *
 * @since 1.8.0
 *
 * @param array $data Array of gallery data.
 * @return string String template for the gallery lightbox
 */
function envirabox_classical_template( $data ) {

	$icons = array();

	// Build out the lightbox template.
	$envirabox_wrap_css_classes = apply_filters( 'envirabox_wrap_css_classes', 'envirabox-wrap', $data );

	$lightbox_themes = envira_get_lightbox_themes();
	$key             = array_search( envira_get_config( 'lightbox_theme', $data ), array_column( $lightbox_themes, 'value' ), true );
	// if the theme could not be located - possible that this is a theme from gallery themes addon, and the addon is not activated/installed.
	$theme           = ( empty( $key ) ) ? 'base_dark' : envira_get_config( 'lightbox_theme', $data );
	$envirabox_theme = apply_filters( 'envirabox_theme', 'envirabox-theme-' . $theme, $data );

	$template = '<div id="envirabox-' . $data['id'] . '" data-envirabox-id="' . $data['id'] . '" class="envirabox-container ' . $envirabox_theme . ' ' . $envirabox_wrap_css_classes . '" role="dialog">';

	$template .= '<div class="envirabox-bg"></div>';
	$template .= '<div class="envirabox-outer"><div class="envirabox-inner">';

	$template = apply_filters( 'envirabox_inner_above', $template, $data, $icons );

	$template .= '<div class="envirabox-top-bar-container"><div class="envirabox-top-bar">';

	$template .= '<div class="envirabox-back-to-gallery"><a data-envirabox-close class="envirabox-item envirabox-close envirabox-button--close" title="' . __( 'Close', 'envira-gallery' ) . '" href="#">' . apply_filters( 'envirabox_classical_close_text', 'Back to Gallery' ) . '</a></div>';

	$template .= '<div class="envirabox-title-wrap">';
	if ( 'title' === envira_get_config( 'lightbox_title_caption', $data ) || 'title_caption' === envira_get_config( 'lightbox_title_caption', $data ) ) {
		$template .= '<div class="envirabox-title"></div>';
	}
	if ( 'caption' === envira_get_config( 'lightbox_title_caption', $data ) || 'title_caption' === envira_get_config( 'lightbox_title_caption', $data ) ) {
		$template .= '<div class="envirabox-caption"></div>';
	}

	$template     .= '</div>';
	$template     .= '<div class="envirabox-actions">';
		$template .= '<div class="envirabox-woocommerce-container"></div>';
		$template .= '<div class="envirabox-icons">';
		$template  = apply_filters( 'envirabox_actions', $template, $data, $icons );
		$template .= '</div>';

	if ( envira_get_config( 'image_counter', $data ) ) {
			$template .= apply_filters( 'envirabox_theme_image_counter', '<div class="envirabox-image-counter"><span data-envirabox-index></span>/<div class="envirabox-count"><span data-envirabox-count></span></div></div>', $theme, $data );
	}
	$template .= '</div>';
	$template .= '</div></div>';

	if ( envira_get_config( 'arrows', $data ) ) {

				$template     .= '<div class="envirabox-navigation">';
					$template .= '<a data-envirabox-prev title="' . __( 'Prev', 'envira-gallery' ) . '" class="envirabox-arrow envirabox-arrow--left envirabox-nav envirabox-prev" href="#"><span></span></a>';
					$template .= '<a data-envirabox-next title="' . __( 'Next', 'envira-gallery' ) . '" class="envirabox-arrow envirabox-arrow--right envirabox-nav envirabox-next" href="#"><span></span></a>';
				$template     .= '</div>';

	}

			$template .= '<div class="envirabox-stage"></div>';
			$template  = apply_filters( 'envirabox_inner_below', $template, $data, $icons );

			$template .= '</div></div></div>';

	return str_replace( "\n", '', $template );

}
