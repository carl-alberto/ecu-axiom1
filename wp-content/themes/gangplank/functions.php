<?php

// Modify wordpress default settings
include('includes/theme-mods.php');

// Helper functions for theme
include('includes/theme-helper.php');

// Administrative
include('includes/theme-admin.php');

// Resources
include('includes/theme-includes.php');

// Menu
include('includes/theme-menu-walker.php');

// Ajax
include('includes/theme-ajax.php');

include_once( 'includes/ecu-admin-2/ecu-admin-2.php');

// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );

// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );