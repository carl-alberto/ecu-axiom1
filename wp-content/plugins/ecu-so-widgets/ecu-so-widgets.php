<?php
/*
Plugin Name: ECU SiteOrigin Widgets (Extended From SiteOrigin)
Description: A collection of ECU specific widgets. Requires SiteOrigin Widgets Bundle.
Version: 1.0.0
Text Domain: ecu-widgets-bundle
Domain Path: /languages
Author: atwebdev
Author URI: mailto:atwebdev@ecu.edu
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
*/

//Add the ECU Site Origin Bundle
add_filter('siteorigin_widgets_widget_folders', 'add_ecu_widgets_collection');

//Set Thumbnail Banner for ECU Alert Widget. Image size is to be 240x240px and the icon inside should be 128x128px.
add_filter('siteorigin_widgets_widget_banner', 'ecu_alert_banner_image', 10, 2);

/** BEGIN - ECU Custom Widget Fields **/
// Custom Fields Path
add_filter('siteorigin_widgets_field_class_paths', 'ecu_custom_fields_class_paths');
//Custom Fields Prefix
add_filter('siteorigin_widgets_field_class_prefixes', 'ecu_custom_fields_class_prefixes');


function ecu_custom_fields_class_paths( $class_paths ) {
    $class_paths[] = plugin_dir_path( __FILE__ ) . 'custom-fields/';
    return $class_paths;
}

function ecu_custom_fields_class_prefixes( $class_prefixes ) {
    $class_prefixes[] = 'Ecu_Widget_Field_';
    return $class_prefixes;
}

/*
 * Add in ECU widgets to dashboard with the rest of the widgets
 *
 */
function add_ecu_widgets_collection($folders){
    $folders[] = plugin_dir_path(__FILE__).'widgets/';
    return $folders;
}

/*
 * Banner image for ECU Alert
 *
 */
function ecu_alert_banner_image( $banner_url, $widget_meta ) {
    if( $widget_meta['ID'] == 'ecu-alert') {
        $banner_url = plugin_dir_url(__FILE__) . 'widgets/ecu-alert/assets/banner.png';
    }
    return $banner_url;
}

