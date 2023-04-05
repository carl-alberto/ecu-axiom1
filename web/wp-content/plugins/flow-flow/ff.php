<?php
/**
 * Flow-Flow
 *
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `FlowFlowAdmin.php`
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>

 * @link      http://looks-awesome.com
 * @copyright 2015 Looks Awesome
 */
session_start();

/** @noinspection PhpIncludeInspection */
require_once( __DIR__ . '/ff-config.php');
/** @noinspection PhpIncludeInspection */
require_once( __DIR__ . '/ff-init.php');
require_once( __DIR__ . '/libs/autoload.php' );

use la\core\db\LADBManager;
use la\core\LAUtils;

/**
 * Get stream settings by id endpoint
 *
 * @param LADBManager $db
 *
 * @throws Exception
 */
function get_stream_settings($db){
    if (FF_USE_WP) {
        if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
            die( json_encode( [ 'error' => 'not_allowed' ] ) );
        }
    }

    $id = $_GET['stream-id'];
    $db->dataInit(false, false);

    $stream = $db->getStream($id);

    // cleaning if error was saved in database stream model, can be removed in future, now it's needed for affected users
    if ( isset( $stream['error'] ) ) unset( $stream['error'] );

    die( json_encode( $stream ) );
}

if (isset($_REQUEST['action'])){
	$context = ff_get_context();
	$db = LAUtils::dbm($context);

	global $facebookCache;
	$facebookCache = new la\core\cache\LAFacebookCacheManager($context);

	$ff = flow\FlowFlow::get_instance($context);

	switch ($_REQUEST['action']) {
		case 'fetch_posts':
			$ff->processAjaxRequest();
			break;
		case 'load_cache':
			$ff->processAjaxRequestBackground();
			break;
		case 'refresh_cache':
			if (false !== ($time = $db->getOption('bg_task_time'))){
				if (time() > $time + 60){
					$ff->refreshCache();
					$time = time();
					$db->setOption('bg_task_time', $time);
					echo 'new cache time: ' . $time;
				}
			} else  {
			    $db->setOption('bg_task_time', time());
            }
			break;
		case 'flow_flow_save_stream_settings':
			$db->save_stream_settings();
			break;
		case 'flow_flow_get_stream_settings':
			get_stream_settings($db);
			break;
		case 'flow_flow_ff_save_settings':
			$db->ff_save_settings_fn();
			break;
		case 'flow_flow_create_stream':
			$db->create_stream();
			break;
		case 'flow_flow_clone_stream':
			$db->clone_stream();
			break;
		case 'flow_flow_delete_stream':
			$db->delete_stream();
			break;
		case 'moderation_apply_action':
			$ff->moderation_apply();
			break;
		case 'flow_flow_social_auth':
			$db->social_auth();
			break;
		default:
			if (strpos($_REQUEST['action'], "backup") !== false) {
				$snapshotManager = new la\core\snapshots\LASnapshotManager($context);
				$snapshotManager->processAjaxRequest();
			}
			break;
	}
}
die;