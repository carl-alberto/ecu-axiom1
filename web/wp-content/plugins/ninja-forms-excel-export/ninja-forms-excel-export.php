<?php if (!defined('ABSPATH')) exit;

use NinjaForms\ExcelExport\Factories\SpreadsheetFactory;
use NinjaForms\ExcelExport\Factories\SpreadsheetWriterFactory;
use NinjaForms\ExcelExport\Factories\NfDatabaseQueryFactory;

use NinjaForms\ExcelExport\Admin\ExtractPostData;
use NinjaForms\ExcelExport\Handlers\ExportFile;

/*
 * Plugin Name: Ninja Forms - Excel Export
 * Plugin URI: http://etzelstorfer.com/en/
 * Description: Export Ninja Forms submissions to Excel file
 * Version: 3.3.5
 * Author: Saturday Drive
 * Author URI: http://ninjaforms.com/?utm_source=Ninja+Forms+Plugin&utm_medium=Plugins+WP+Dashboard
 * Text Domain: ninja-forms-excel-export
 * 
 * Release Description: Release 3.3.5
 * Copyright 2018 WP Ninjas.
 */

/**
 * Class NF_ExcelExport
 */
final class NF_ExcelExport
{
    const VERSION = '3.3.5';
    const SLUG    = 'excel-export';
    const NAME    = 'Excel Export';
    const AUTHOR  = 'Hannes Etzelstorfer';
    const PREFIX  = 'NF_ExcelExport';

    /**
     * @var NF_ExcelExport
     * @since 3.0
     */
    private static $instance;

    /**
     * Plugin Directory
     *
     * @since 3.0
     * @var string $dir
     */
    public static $dir = '';

    /**
     * Plugin URL
     *
     * @since 3.0
     * @var string $url
     */
    public static $url = '';


    /**
     * Main Plugin Instance
     *
     * Insures that only one instance of a plugin class exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @since 3.0
     * @static
     * @static var array $instance
     * @return NF_ExcelExport Highlander Instance
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof NF_ExcelExport)) {
            self::$instance = new NF_ExcelExport();

            self::$dir = plugin_dir_path(__FILE__);

            self::$url = plugin_dir_url(__FILE__);

            /*
                 * Register our autoloader
                 */
            spl_autoload_register(array(self::$instance, 'autoloader'));
        }
    }

    public function __construct()
    {
        /*
             * Required for all Extensions.
             */
        add_action('admin_init', array($this, 'setup_license'));

        //require self::$dir . 'includes/class-ninjaformsspreadsheet.php';
        load_plugin_textdomain('ninja-forms-spreadsheet', false, self::$dir . '/translations');

        add_action('admin_menu', array($this, 'add_admin_page'));
        add_action('wp_ajax_nf_spreadsheet_export', array($this, 'export_file'));
        add_action('wp_ajax_nf_spreadsheet_save_field_settings', array($this, 'save_field_settings'));
        add_action('wp_ajax_nf_spreadsheet_save_filter', array($this, 'save_filter'));
        add_action('admin_init', array($this, 'output_export_file'));

        add_action('plugins_loaded', [$this, 'composerAutoloader']);
    }
    /**
     * Load an autoloader from vendor subdirectory
     *
     * This function can be copied and reused in other plugins using composer's
     * PSR-4 specification because it is namespaced within this file to avoid
     * collision.
     *
     * @return boolean
     */
    function composerAutoloader(): bool
    {
        $autoloader = dirname(__FILE__) . '/vendor/autoload.php';

        if (file_exists($autoloader)) {
            include_once $autoloader;
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }



    public function add_admin_page()
    {
        if (function_exists('Ninja_Forms'))
            Ninja_Forms()->menus['excel-export']         = new NF_ExcelExport_Admin_Menus_ExcelExport();
    }


    /*
         * Optional methods for convenience.
         */

    public function autoloader($class_name)
    {

        if (class_exists($class_name)) return;

        if (false === strpos($class_name, self::PREFIX)) return;

        $class_name = str_replace(self::PREFIX, '', $class_name);
        $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
        $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

        if (file_exists($classes_dir . $class_file)) {
            require_once $classes_dir . $class_file;
        }
    }




    public function save_field_settings()
    {
        $form_id = $_POST['form_id'];
        $field_settings = $_POST['field_settings'];
        $fields_associative = array();
        foreach ($field_settings as $field) {
            $fields_associative[$field['field_key']] = $field;
        }

        update_option('nf_excel_field_settings_' . $form_id, $fields_associative);
        wp_die();
    }




    public function save_filter()
    {
        if (array_key_exists('form_id', $_POST) && array_key_exists('filter', $_POST)) {
            $form_id = $_POST['form_id'];
            $filters = $_POST['filter'];

            update_option('nf_excel_filter_' . $form_id, $filters);
        }
        wp_die();
    }


    /**
     * 
     */
    public function export_file()
    {
        $exportFile = new ExportFile();

        $extractPostData = new ExtractPostData();
        $spreadsheetFactory = new SpreadsheetFactory();
        $spreadsheetWriterFactory = new SpreadsheetWriterFactory();
        $nfDatabaseQueryFactory = new NfDatabaseQueryFactory();

        $exportFile
            ->setExtractPostData($extractPostData)
            ->setSpreadsheetFactory($spreadsheetFactory)
            ->setSpreadsheetWriterFactory($spreadsheetWriterFactory)
            ->setNfDatabaseQueryFactory($nfDatabaseQueryFactory);

        $exportFile->handle();
    }


    public function output_export_file()
    {
        if (!current_user_can(apply_filters('ninja_forms_admin_excel_export_capabilities', 'manage_options'))) return;

        if (isset($_POST['spreadsheet_export_tmp_name'])) {
            $this->export_file();
            die;
        }
    }



    public static function template($file_name = '', array $data = array(), $return = FALSE)
    {
        if (!$file_name) return FALSE;

        extract($data);

        $path = self::$dir . 'includes/Templates/' . $file_name;

        if (!file_exists($path)) return FALSE;

        if ($return) return file_get_contents($path);

        include $path;
    }

    /*
         * Required methods for all extension.
         */

    public function setup_license()
    {
        if (!class_exists('NF_Extension_Updater')) return;

        new NF_Extension_Updater(self::NAME, self::VERSION, self::AUTHOR, __FILE__, self::SLUG);
    }
}

/**
 * The main function responsible for returning The Highlander Plugin
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @since 3.0
 * @return {class} Highlander Instance
 */
function NF_ExcelExport()
{
    return NF_ExcelExport::instance();
}

NF_ExcelExport();
