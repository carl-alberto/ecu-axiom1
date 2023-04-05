<?php
/*
Plugin Name: ECU Admin 2
Plugin URI: https://www.ecu.edu
Description: Required to be turned off for crowsnest theme
Version: 2.0.0
Author: atwebdev
Author URI: http://www.ecu.edu
Text Domain: ecu-admin
Domain Path: /languages
License: To ill
*/
defined( 'ABSPATH' ) OR exit;

// Load ACF addons
require_once plugin_dir_path( __FILE__ ) . '/acf-addons/acf-image-crop-add-on/acf-image-crop.php';
require_once plugin_dir_path( __FILE__ ) . '/acf-addons/advanced-custom-fields-font-awesome/acf-font-awesome.php';
require_once plugin_dir_path( __FILE__ ) . '/acf-addons/acf-code-field/acf-code-field.php';
require_once plugin_dir_path( __FILE__ ) . '/acf-addons/acf-to-rest-api/class-acf-to-rest-api.php';

// Post Types
require_once plugin_dir_path( __FILE__ ) . '/includes/post-types/sidebars.php';
require_once plugin_dir_path( __FILE__ ) . '/includes/post-types/ui-elements.php';
require_once plugin_dir_path( __FILE__ ) . '/includes/post-types/pages.php';

