<?php
	/**
	 * Contains admin functions used by both the plugin.
	 *
	 * @package WPLocalist
	 * @since 1.0.0
	 */
	namespace OUR\LOCALIST;

	/**
	 * Enqueue Styles and add styles to the editor for shortcake.
	 *
	 * @since 1.0.0
	 */
	function enqueue_admin_styles() {
	    add_editor_style( plugins_url( 'wp-localist/css/list.css' ) );
	    add_editor_style( plugins_url( 'wp-localist/css/grid.css' ) );
	}