<?php
/**
 * This config file is yours to hack on. It will work out of the box on Pantheon
 * but you may find there are a lot of neat tricks to be used here.
 *
 * See our documentation for more details:
 *
 * https://pantheon.io/docs
 */








// ** Plugin Settings ** //

//https://wordpress.org/plugins/redis-cache/#other_notes
define( 'WP_REDIS_DISABLE_BANNERS', true );
define( 'WP_REDIS_HOST', getenv('REDIS_HOST') );
define( 'WP_REDIS_PASSWORD', getenv('REDIS_PASSWORD') );

/** Disable Redis If true ( set to true if redis is not installed ) */
define('WP_REDIS_DISABLED', filter_var(getenv('WP_REDIS_DISABLED'), FILTER_VALIDATE_BOOLEAN));

// Avoids conflicts with plugins including older versions of select 2 for the shortcake ui.
define( 'SELECT2_NOCONFLICT', true );


/** This provides PHP information from load balancer and allows https requests */
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
    $_SERVER['HTTPS']='on';

/** Turn off/on Analytics. This is not just Google but all scripts that we have added to collect data. */
define('DISABLE_WP_ANALYTICS', filter_var(getenv('DISABLE_WP_ANALYTICS'), FILTER_VALIDATE_BOOLEAN) );

/** Turn off/on Email. */
define('DISABLE_WP_EMAIL', filter_var(getenv('DISABLE_WP_EMAIL'), FILTER_VALIDATE_BOOLEAN) );

/** The uploads directory location. */
// is this relevant??
#define('UPLOADS', 'wp-content/pv-uploads');

/** Disable API Calls */
define('WP_HTTP_BLOCK_EXTERNAL', filter_var(getenv('WP_HTTP_BLOCK_EXTERNAL'), FILTER_VALIDATE_BOOLEAN));

/**
 * WP_ACCESSIBLE_HOSTS constant is a comma separated list of hostnames to allow, wildcard domains
 * are supported. No spaces.
 */
define('WP_ACCESSIBLE_HOSTS', getenv('WP_ACCESSIBLE_HOSTS'));

/**
 * WP_IFRAME_ACCESSIBLE_HOSTS constant is a comma separated list of hostnames to allow. No spaces.
 */
define('WP_IFRAME_HOSTS', getenv('WP_IFRAME_HOSTS'));

/** Disable all automatic updates: */
define( 'AUTOMATIC_UPDATER_DISABLED', true );





/** Disable WordPress Cron */
define('DISABLE_WP_CRON', filter_var(getenv('DISABLE_WP_CRON'), FILTER_VALIDATE_BOOLEAN));

/** Make sure a cron process cannot run more than once every WP_CRON_LOCK_TIMEOUT seconds. **/
define( 'WP_CRON_LOCK_TIMEOUT', 180 );

/** Default Theme **/
define( 'WP_DEFAULT_THEME', 'gangplank' );

/** ECU CDN URLs **/
define('CDN_IMAGE_URL', getenv('CDN_IMAGE_URL'));
define('CDN_VIDEO_URL', getenv('CDN_VIDEO_URL'));

/** ECU LDAP Settings **/
define('LDAP_DN','dc=intra,dc=ecu,dc=edu');
define('LDAP_HOST','shipahoy.ecu.edu');
define('LDAP_PASSWORD',getenv('LDAP_PASSWORD'));
define('LDAP_USERNAME',getenv('LDAP_USERNAME'));
define('LDAP_SERVER_PORT','389');
define('LDAP_SUFFIX','@ecu.edu');
define('LDAP_STUDENT_SUFFIX','@students.ecu.edu');
// 0 = none, 1 = ssl, 2 = tls
define('LDAP_ENCRYPTION',0);

/* Multisite  */
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', $_SERVER['HTTP_HOST']);
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', getenv('SITE_ID_CURRENT_SITE'));
define('BLOG_ID_CURRENT_SITE', getenv('BLOG_ID_CURRENT_SITE'));


/**
 * Store secrets in a private folder.
 */
if (file_exists(dirname(__FILE__) . '/wp-content/uploads/private/wp-config-secrets.php') && isset($_ENV['PANTHEON_ENVIRONMENT'])) {
	require_once(dirname(__FILE__) . '/wp-content/uploads/private/wp-config-secrets.php');



/**
 * Pantheon platform settings. Everything you need should already be set.
 */
if (file_exists(dirname(__FILE__) . '/wp-config-pantheon.php') && isset($_ENV['PANTHEON_ENVIRONMENT'])) {
	require_once(dirname(__FILE__) . '/wp-config-pantheon.php');

/**
 * Local configuration information.
 *
 * If you are working in a local/desktop development environment and want to
 * keep your config separate, we recommend using a 'wp-config-local.php' file,
 * which you should also make sure you .gitignore.
 */
} elseif (file_exists(dirname(__FILE__) . '/wp-config-local.php') && !isset($_ENV['PANTHEON_ENVIRONMENT'])){
	# IMPORTANT: ensure your local config does not include wp-settings.php
	require_once(dirname(__FILE__) . '/wp-config-local.php');

/**
 * This block will be executed if you are NOT running on Pantheon and have NO
 * wp-config-local.php. Insert alternate config here if necessary.
 *
 * If you are only running on Pantheon, you can ignore this block.
 */
} else {
	define('DB_NAME',          'database_name');
	define('DB_USER',          'database_username');
	define('DB_PASSWORD',      'database_password');
	define('DB_HOST',          'database_host');
	define('DB_CHARSET',       'utf8');
	define('DB_COLLATE',       '');
	define('AUTH_KEY',         'put your unique phrase here');
	define('SECURE_AUTH_KEY',  'put your unique phrase here');
	define('LOGGED_IN_KEY',    'put your unique phrase here');
	define('NONCE_KEY',        'put your unique phrase here');
	define('AUTH_SALT',        'put your unique phrase here');
	define('SECURE_AUTH_SALT', 'put your unique phrase here');
	define('LOGGED_IN_SALT',   'put your unique phrase here');
	define('NONCE_SALT',       'put your unique phrase here');
}


/** Standard wp-config.php stuff from here on down. **/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * Site specific settings if you will be using custom upstreams that will have varying  wp-configs
 */
if (file_exists(dirname(__FILE__) . '/wp-config-unique.php') && isset($_ENV['PANTHEON_ENVIRONMENT'])) {
	require_once(dirname(__FILE__) . '/wp-config-unique.php');
}

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * You may want to examine $_ENV['PANTHEON_ENVIRONMENT'] to set this to be
 * "true" in dev, but false in test and live.
 */

if (!defined('WP_DEBUG')) {
	define( 'WP_DEBUG', true );
}
/* That's all, stop editing! Happy Pressing. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
