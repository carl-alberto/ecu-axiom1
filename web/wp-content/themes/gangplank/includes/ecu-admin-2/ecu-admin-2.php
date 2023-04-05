<?php

defined( 'ABSPATH' ) OR exit;

// Customizes the Admin Bar
require_once('includes/admin-bar.php');

// Various changes to default wordpress / plugin behaviour.
require_once('includes/admin.php');

// Add taxonomies
require_once('includes/taxonomies.php');

add_action('after_switch_theme', 'cpt_flush_rewrites');
function cpt_flush_rewrites() {
	featured_author_taxonomy();
	flush_rewrite_rules();
}