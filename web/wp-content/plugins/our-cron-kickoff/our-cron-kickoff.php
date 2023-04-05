<?php

namespace OUR\CRON\KICKOFF;

/**
 * Plugin Name: Our Cron Kickoff
 * Description: Kicks off cron for all sites in the multi site.  Should only be enabled on root site.
 * Version: 1.0.0
 * Text Domain: our-cron
 */

defined( 'ABSPATH' ) OR exit;

// Add cron interval for running every minute
add_filter( 'cron_schedules', __NAMESPACE__ . '\interval');
function interval( $schedules ) {
   	$schedules['wp-cron-interval'] = array(
       	'interval' => \WP_CRON_LOCK_TIMEOUT,
       	'display'  => esc_html__( 'Every Minute' ),
	);

    return $schedules;
}

// Register the cron hook on plugin activation
register_activation_hook(__FILE__,  __NAMESPACE__ . '\activation', 10, 0);
function activation() {

    if (! wp_next_scheduled ( 'multisite_kickoff_cron' )) {
		wp_schedule_event(time(), 'wp-cron-interval',  'multisite_kickoff_cron');
    }
}

// Unregister the wp cron hook on plugin deactivation
register_deactivation_hook(__FILE__,  __NAMESPACE__ . '\deactivation', 10, 0);
function deactivation() {
	wp_clear_scheduled_hook( 'multisite_kickoff_cron');
}

// Create the hook that wp cron will execute
add_action( 'multisite_kickoff_cron',  __NAMESPACE__ . '\kickoff', 10, 0);
function kickoff() {

	try {
		$dbh = new \PDO ('mysql:host=' .  getenv('WORDPRESS_DB_HOST') . ';dbname=' .  getenv('WORDPRESS_DB_NAME') . ';', getenv('WORDPRESS_DB_USER'), getenv('WORDPRESS_DB_PASSWORD'));

		// Throw exceptions for any errors
		$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		$select = $dbh->prepare("
			SELECT domain, path
			FROM " . getenv('WORDPRESS_TABLE_PREFIX') . "blogs
			WHERE deleted = 0 AND archived = 0 AND blog_id != " . BLOG_ID_CURRENT_SITE . "
			ORDER BY `domain` ASC;
		");

		if($select->execute()) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36');
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Set timeout to 10 secs.

			while($row = $select->fetch()) {
				usleep(10000);
				$url = "https://" . $row['domain'] . $row['path'] . 'wp-cron.php?doing_wp_cron';
				echo '<p>' . $url . '</p>';

				curl_setopt($ch, CURLOPT_URL, $url);
				if(curl_exec($ch) === false)
				{
				    echo '<p>Curl error: ' . curl_error($ch). '</p>';
				}
			}
			curl_close($ch);
		}

	} catch(Exception $e) {
		echo 'Caught exception: ' .  $e->getMessage();
	}
}
