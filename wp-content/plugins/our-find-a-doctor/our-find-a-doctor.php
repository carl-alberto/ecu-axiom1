<?php
/*
    Plugin Name: Our Find a Doctor
    Description: Custom functionality for ECU physicians [physician_search /]
    Version:     1.0.0
    Text Domain: find-a-doctor
*/
namespace OUR\FINDADOCTOR;

include_once('physician-search/physician-search.php');

/*
 *  Registers post types, adds terms and flushes permalinks on plugin initialization
 *  Flushes permalinks on plugin deactivation
 */
register_activation_hook( __FILE__, __NAMESPACE__ . '\init_physician_cpt' );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
function init_physician_cpt() {
    \OUR\FINDADOCTOR\register_physicians();
    flush_rewrite_rules();
}
