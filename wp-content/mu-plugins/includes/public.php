<?php

namespace Mu_Plugins;

defined( 'ABSPATH' ) || exit;

/**
 * Adds additional MIME / Content types for upload in media library
 * Note: Executable file types must be defined here otherwise WP UI throws security error
 *
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/upload_mimes
 */
add_filter( 'upload_mimes', __NAMESPACE__ . '\mime_types' );
function mime_types( $mimes ) {
    //Images
    $mimes['svg'] = 'image/svg+xml';
    //Documents
    $mimes['ics'] = 'text/calendar';
    $mimes['eps'] = 'application/postscript';
    $mimes['xlsm'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    $mimes['potx'] = 'application/vnd.openxmlformats-officedocument.presentationml.template';
    $mimes['ibooks'] = 'application/x-ibooks+zip';
    return $mimes;
}

/**
 * We allow multiple subdomain to be used in our multisite.   This causes issues with cookies that end up allowing
 * intranet site to be auto logged in to other intranet sites on the same subdomain.
 *
 * This prevents that by taking into account the path of the site url. The MS default constants did not do this.
 */
add_action('muplugins_loaded', __NAMESPACE__ . '\set_cookies_multiple_domains', 10, 0);
function set_cookies_multiple_domains() {
  $siteurl = parse_url( get_option( 'siteurl' ) );
  if(!isset($siteurl['path'])) {
    $siteurl['path'] = '/';
  }

  define( 'COOKIE_DOMAIN', $siteurl['host']);
  define( 'COOKIEHASH', md5( $siteurl['host'] . $siteurl['path'] ) );
  define( 'COOKIEPATH',  $siteurl['path'] );
  define( 'SITECOOKIEPATH', $siteurl['path']);
}

/**
 * Forces HTTPS on items coming out of the media library
 *
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/wp_get_attachment_url
 */
add_filter( 'wp_get_attachment_url', __NAMESPACE__ . '\force_https_for_media' );
function force_https_for_media( $url ) {
    if ( is_ssl() )
        $url = str_replace( 'http://', 'https://', $url );
    return $url;
}

/**
 * Configure EMail Relay
 *
 * @see https://developer.wordpress.org/reference/hooks/phpmailer_init/
 */
add_action( 'phpmailer_init', __NAMESPACE__ . '\wp_smtp_relay' );
function wp_smtp_relay( $phpmailer ) {
    $phpmailer->IsSMTP();
    $phpmailer->SMTPAuth    = false;
    $phpmailer->Host        = 'hdmail.ecu.edu';
    $phpmailer->Port        = 25;
}

/**
 * Removes self pingback
 * Disable pingback.ping xmlrpc method to prevent Wordpress from participating in DDoS attacks.
 *
 * @see https://developer.wordpress.org/reference/hooks/wp_headers/
 */
add_filter('wp_headers', function ($headers) {
  unset($headers['X-Pingback']);
  return $headers;
});

/**
* Removes pingback from headers
* Disable pingback.ping xmlrpc method to prevent Wordpress from participating in DDoS attacks.
*
* @see https://developer.wordpress.org/reference/hooks/xmlrpc_methods/
*/
add_filter( 'xmlrpc_methods', function( $methods ) {
  unset( $methods['pingback.ping'] );
  return $methods;
} );

/**
 * Stop XMLRPC
 *
 * @see https://developer.wordpress.org/reference/hooks/xmlrpc_enabled/
 */
add_filter('xmlrpc_enabled', '__return_false', 10, 0 );

/**
 * Removes shortlink in header
 *
 * @see https://developer.wordpress.org/reference/functions/wp_shortlink_header/
 */
remove_action( 'template_redirect', 'wp_shortlink_header', 11 );

/**
 * Removes Really Simple Discovery service endpoint
 *
 * @see https://developer.wordpress.org/reference/functions/rsd_link/
 */
remove_action( 'wp_head', 'rsd_link' );

/**
 * Removes XTHML generator from wp_head
 *
 * @see https://developer.wordpress.org/reference/functions/wp_generator/
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Removes links to the general feeds
 *
 * @see https://developer.wordpress.org/reference/functions/feed_links/
 */
remove_action( 'wp_head', 'feed_links', 2 );

/**
 * Removes links to Windows Live Writer manifest
 *
 * @see https://developer.wordpress.org/reference/functions/wlwmanifest_link/
 */
remove_action( 'wp_head', 'wlwmanifest_link' );

/**
 * Removes extra feed links
 *
 * @see https://developer.wordpress.org/reference/functions/feed_links_extra/
 */
remove_action( 'wp_head', 'feed_links_extra', 3 );

/**
 * Removes relational links for single posts
 *
 * @see https://developer.wordpress.org/reference/functions/adjacent_posts_rel_link_wp_head/
 */
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

/**
 * Removes emoji detection script
 *
 * @see https://developer.wordpress.org/reference/functions/print_emoji_detection_script/
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

/**
 * Removes emoji stylesheet
 *
 * @see https://developer.wordpress.org/reference/functions/print_emoji_styles/
 */
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Removes oEmbed discovery links
 *
 * @see https://developer.wordpress.org/reference/functions/wp_oembed_add_discovery_links/
 */
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

/**
 * Change the lost password link since we do not do local accounts.
 *
 * @see https://developer.wordpress.org/reference/hooks/lostpassword_url/
 */
add_filter('lostpassword_url', __NAMESPACE__ . '\custom_lostpassword_url', 10, 0);
function custom_lostpassword_url() {
    return 'https://pirateid.ecu.edu';
}

/*
 * Modify URL on login page logo
 *
 * @see https://developer.wordpress.org/reference/hooks/login_headerurl/
 */
add_filter('login_headerurl', __NAMESPACE__ . '\custom_header_url', 10, 0);
function custom_header_url() {
  return getenv('TOPSITE_ENV');
}


/**
 * Change the robots.txt file to try and eliminate error for TD Ticket 7807768
 *
 * @see https://developer.wordpress.org/reference/hooks/robots_txt/
 * @see https://kinsta.com/blog/wordpress-robots-txt/
 */
add_filter( 'robots_txt', function( $output, $public ) {

  if ($public) {
    $output .= "\nUser-agent: SiteimproveBot-Crawler\nAllow: /\n";
  }

  return $output;

}, 99, 2 );  // Priority 99, Number of Arguments 2.