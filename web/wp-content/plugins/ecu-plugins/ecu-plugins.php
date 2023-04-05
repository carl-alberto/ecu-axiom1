<?php
	/*
	Plugin Name: ECU Plugins
	Plugin URI:  https://github.ecu.edu/Wordpress/plugins
	Description: Creates all the ECU shortcodes needed for shortcake
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
	require_once (dirname(__FILE__) . '/includes/plugins/abstract-ecu-shortcode.php');

	/**
	 * Abstract Class that defines a field for the ecu shortcode ui and provides basic functionality.
	 */
	require_once (dirname(__FILE__) . '/includes/shortcake_fields/abstract-ecu-field.php');

	/**
	 * Includes standalone shortcodes that do not utilize shortcake UI
	 */
	require_once (dirname(__FILE__) . '/standalone/standalone.php');

	/**
	 * Includes all shortcode fields and plugins from the inlcudes directory.  The file structure for an ecu plugin should
	 * adhere to worpdress standards for plugins.
	 *
	 * All short codes must be defined in a class that extends the ECU shortcode abstract class.
	 *
	 * Create a directory with the name of your plugin in ecu-plugins/includes/plugins.
	 *    example plugin:   parall_images
	 *    file path:  ecu-plugins/includes/plugins/parallax-images/parallax-images.php
	 *
	 * IMPORTANT: The plugin should only have css required for structure.  Any CSS that is for look and feel should be in the ecu default theme css.
	 */
	class Ecu_Plugins {
		/**
		 * Constructor
		 *
		 * Enqueues the scripts and includes the shortcode classes.
		 */
		public function __construct(){

			$directories = array(
				'plugins',
				'shortcake_fields'
			);

			foreach($directories as $directory){
				$path = dirname(__FILE__). "/includes/{$directory}";
				foreach(scandir($path) as $dir){
						if(!strpos($dir, '.') && $dir != '.' & $dir != '..'){
							if(file_exists("{$path}/{$dir}/{$dir}.php")){
								require_once("{$path}/{$dir}/{$dir}.php");
							}
						}
					}
			}

			if(class_exists('acf')){
				$path = dirname(__FILE__). "/includes/plugins_departmental";
				$enabled = get_field('departmental_shortcodes', 'option');
				if(is_array($enabled)){
					global $dptshortcodes;
					$dptshortcodes = array();
					foreach(scandir($path) as $dir){
						if(!strpos($dir, '.') && $dir != '.' & $dir != '..'){
							if(file_exists("{$path}/{$dir}/{$dir}.php")){
								if(in_array("{$dir}.php", $enabled)){
									$dptshortcodes[] = $dir;
									require_once("{$path}/{$dir}/{$dir}.php");
								}
							}
						}
					}
				}
			}

			add_action('admin_enqueue_scripts', array($this, 'ecu_plugins_styles'));
		}
		/**
		 * Add styles to ecu shortcodes
		 */
		public static function ecu_plugins_styles(){
			wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
			add_editor_style('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
			wp_enqueue_style('ecu-plugins-style', plugins_url('ecu-plugins/css/ecu-plugins.css'));
		}

	}

	new Ecu_Plugins;
