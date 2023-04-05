<?php if ( ! defined( 'WPINC' ) ) die;

/**
 * @package   Flow_Flow
 * @author    Looks Awesome <hello@looks-awesome.com>

 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 *
 * @wordpress-plugin
 * Plugin Name:       Flow-Flow
 * Plugin URI:        social-streams.com
 * Description:       Awesome social streams on your website
 * Version:           4.9.4
 * Author:            Looks Awesome
 * Author URI:        looks-awesome.com
 * Text Domain:       flow-flow
 * Domain Path:       /languages
 */
if ( ! defined( 'FF_USE_WP' ) )  define( 'FF_USE_WP', true );
if ( ! defined( 'FF_USE_WPDB' ) )  define( 'FF_USE_WPDB', false );
if ( ! defined( 'FF_USE_WP_CRON' ) ) define('FF_USE_WP_CRON', true);
if ( ! defined( 'FF_USE_DIRECT_WP_CRON' ) ) define('FF_USE_DIRECT_WP_CRON', false);
if ( ! defined( 'FF_FORCE_FIT_MEDIA' ) ) define('FF_FORCE_FIT_MEDIA', false);
if ( ! defined( 'FF_FEED_POSTS_COUNT' ) ) define('FF_FEED_POSTS_COUNT', 100);
if ( ! defined( 'FF_FEED_INIT_COUNT_POSTS' ) ) define('FF_FEED_INIT_COUNT_POSTS', 20);
if ( ! defined( 'FF_LOCALE'))  define('FF_LOCALE', get_locale());//TODO add a slash to the end
if ( ! defined( 'FF_DB_CHARSET')) define('FF_DB_CHARSET', defined( 'DB_CHARSET' ) ? DB_CHARSET : 'utf8mb4');
if ( ! defined( 'FF_REMOVE_EMOJI' ) ) define('FF_REMOVE_EMOJI', true);
if ( ! defined( 'FF_ALTERNATIVE_POST_STORAGE' ) ) define('FF_ALTERNATIVE_POST_STORAGE', false);
if ( ! defined( 'FF_LOG_FILE_DEST' ) ) define('FF_LOG_FILE_DEST', plugin_dir_path(__FILE__) . 'flow-flow-debug.log');
if ( ! defined( 'FF_BOOST_SERVER' ) ) define('FF_BOOST_SERVER', 'https://api.flowflowapp.com/api/v1/');

if (!isset($_REQUEST['action']) || $_REQUEST['action'] != 'heartbeat') {
    require_once( __DIR__ . '/libs/autoload.php' );

    $facade = la\core\LAActivatorFacade::get();
    $facade->registry_activator(new flow\FlowFlowActivator(__FILE__));
}