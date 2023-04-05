<?php

// Flushes rewrite rules on theme activation for CPTs and taxonomies
add_action( 'after_switch_theme', 'flush_rewrite_rules' );

// Administrative functions for ITCS theme
include('includes/theme-admin.php');

// Includes custom post types for theme
include('includes/theme-cpt.php');

// Theme includes
include('includes/theme-includes.php');

// Theme Ajax
include('includes/theme-ajax.php');

// Helper
include('includes/theme-helper.php');

// Shortcodes
include('includes/shortcodes/shortcodes.php');

// API endpoints
include('includes/theme-api.php');
