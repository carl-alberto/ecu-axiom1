<?php
	/*
	Plugin Name: Our Plugins
	Plugin URI:  https://github.ecu.edu/Wordpress/plugins
	Description: Mainly legacy shortcodes.    Should be removed/cleaned up after gutenberg theme migration complete.
	Version:     20170222
	Author:      http://www.ecu.edu
	Author URI:  http://www.ecu.edu
	Text Domain: ecu-plugins
	Domain Path: /languages
	*/

	/**
	 * ECU Database class that provides access to ecu databases.
	 */
	require_once (dirname(__FILE__) . '/includes/ecu-database.php');

	/**
	 * ECU paginator class that can be used to paginate the calls to the db.
	 */
	require_once (dirname(__FILE__) . '/includes/ecu-paginator.php');

	/**
	 * Abstract Class that defines the ecu shortcode and provides basic functionality.
	 */
	require_once (dirname(__FILE__) . '/includes/abstract-ecu-shortcode.php');

	/**
	 * Includes all shortcode fields and plugins from the inlcudes directory.  The file structure for an ecu plugin should
	 * adhere to worpdress standards for plugins.
	 *
	 * All short codes must be defined in a class that extends the ECU shortcode abstract class.
	 *
	 * Create a directory with the name of your plugin in our-plugins/includes/plugins.
	 *    example plugin:   parallax-images
	 *    file path:  our-plugins/includes/plugins/parallax-images/parallax-images.php
	 *
	 * IMPORTANT: The plugin should only have css required for structure.  Any CSS that is for look and feel should be in the ecu default theme css.
	 */
	class OUR_PLUGINS {
		/**
		 * Constructor
		 *
		 * Enqueues the scripts and includes the shortcode classes.
		 */
		public function __construct(){

			$path = dirname(__FILE__). "/includes/plugins";
			foreach(scandir($path) as $dir){
				if(!strpos($dir, '.') && $dir != '.' & $dir != '..'){
					if(file_exists("{$path}/{$dir}/{$dir}.php")){
						require_once("{$path}/{$dir}/{$dir}.php");
					}
				}
			}
			wp_register_style( 'ecu-fontello-fonts', plugin_dir_url( __FILE__ ) . 'includes/shortcake_fields/icon-select/fontello/css/fontello.css' );

			add_action('admin_enqueue_scripts', array($this, 'plugins_styles'));
		}

		/**
		 * Add styles to ecu shortcodes
		 */
		public static function plugins_styles(){
			wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
			add_editor_style('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
			wp_enqueue_style('ecu-plugins-style', plugins_url('our-plugins/includes/css/ecu-plugins.css'));
			add_editor_style( plugin_dir_url( __FILE__ ) . 'includes/shortcake_fields/icon-select/fontello/css/fontello.css' );
			wp_enqueue_style( 'ecu-icon-select-fontello', plugin_dir_url( __FILE__ ) . 'includes/shortcake_fields/icon-select/fontello/css/fontello.css' );
		}

	}

	new OUR_PLUGINS;