<?php

/**
 *  Plugin Name: Our News Feed Page
 *  Description: Create news feeds archive page.
 *  Version:     1.0.0
 *  Author:      http://www.ecu.edu
 *  Text Domain: our-news-feed
 */

namespace OUR\NEWS\FEED;

// Exit if accessed directly.
defined( 'ABSPATH' ) OR exit;

/**
 * This plugin adds a custom url to capture a news feed id and then displays a custom template that displays
 * the news feeds in an archive page. Since this is not a CPT this is the preferred method over template_redirect.
 * Really good article about WP rewrites https://brightminded.com/updates/mastering-wordpress-rewrite-rules/
 */

/**
 * Add the custom rewrite and flush the rewrites. The flush_rewrite_rules function is
 * expensive to run so only use this fucntion when needed.
 *
 * @link  https://codex.wordpress.org/Rewrite_API/add_rewrite_rule
 */
function add_plugin_rewrite_rules() {
  add_rewrite_rule(
        'news-feed/([0-9]+)/?$',
        'index.php?news_feed_id=$matches[1]', 'top' );
  add_rewrite_rule(
        'news-feed/([0-9]+)/page/([0-9]+)/?$',
        'index.php?news_feed_id=$matches[1]&page=$matches[2]', 'top' );
  flush_rewrite_rules();
}

/**
 * Add the custom rewrite if it no longer exists.   The can happen if a user goes to the
 * permalink page in settings after the plugin has been activateion.   So instead of adding the
 * rewrites on plugin activation I just check if they exist and add them if not found.
 * Just going to that permalink page flushes the rewrite cache.
 *
 * Add the feed_id query var to the vars returned by get_query_var function.
 *
 * @link https://codex.wordpress.org/Rewrite_API/add_rewrite_tag
 */
add_action( 'init', __NAMESPACE__ . '\init', 10, 0 );
function init() {

  $rules = get_option( 'rewrite_rules' );

  // You would think that there would be a rewrite_exists function but not at time I had to do this.
  if ( !isset( $rules['news-feed/([0-9]+)/?$'] ) ) {
    add_plugin_rewrite_rules();
  }

  add_rewrite_tag( '%news_feed_id%', '([0-9]+)');
}

/**
 * Remove the rewrite in the plugin deactivation
 */
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\deactivate' );
function deactivate() {
    //Have to remove the init hook so the rules will not just be added back after deactivation hook is fired.
    remove_action( 'init', __NAMESPACE__ . '\init' );
    flush_rewrite_rules();
}

/**
 * Check for the feed_id query var and return the archive temeplate if a feed id is found.
 *
 * @link https://developer.wordpress.org/reference/hooks/template_include/
 */
add_filter( 'template_include', __NAMESPACE__ . '\template' );
function template( $template ) {
    if ( get_query_var('news_feed_id', false) ) {
      $template = __DIR__ . '/news-feed-template.php';
    }
    return $template;
}

/**
 * Return the pagination links as a string with Bootstrap 4 styling.
 *
 * @link https://developer.wordpress.org/reference/functions/paginate_links/
 */
function pagination( $id = 100, $num_pages = 1, $page = 1 ) {

    if($num_pages < 2) {
      return '';
    }

    $pages = paginate_links( array(
            'base' => home_url('/news-feed/' . $id . '/page/%#%'),
            'format' => '%#%',
            'current' => $page,
            'total' => $num_pages,
            'type'  => 'array',
            'prev_next'   => true,
            'prev_text'    => '<span class="fa fa-chevron-left" aria-hidden="true"></span> Prev',
            'next_text'    => 'Next <span class="fa fa-chevron-right" aria-hidden="true"></span>',
        )
    );

    $pagination = '';
    if( is_array( $pages ) ) {
        $pagination = '<div class="pagination-wrap"><ul class="pagination">';
        foreach ( $pages as $page ) {
            $pagination .= "<li>$page</li>";
        }
        $pagination .= '</ul></div>';
    }
    return $pagination;
}