<?php

use NinjaForms\Includes\Abstracts\SubmissionHandler as AbstractSubmissionHandler;

/**
 * Plugin Name: Ninja Forms - PDF Form Submissions
 * Plugin URI: https://ninjaforms.com/extensions/pdf-form-submissions/
 * Description: Automatically convert form submissions into PDFs. View PDFs in backend or attach to form email.
 * Version: 3.2.1
 * Author: Ninja Forms
 * Author URI: http://ninjaforms.com
 * License: GPLv2
 * 
 * Release Description: Release 3.2.1
 */

final class NF_Pdf_Submissions
{
    const VERSION = '3.2.1';
    const SLUG    = 'pdf_submission';
    const NAME    = 'PDF Form Submission';
    const AUTHOR  = 'The WP Ninjas';
    const PREFIX  = 'NF_Pdf_Submissions';

    /**
     * @var NF_Pdf_Submissions
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
     * @return NF_Pdf_Submissions Highlander Instance
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof NF_Pdf_Submission)) {
            self::$instance = new NF_Pdf_Submissions();

            self::$dir = plugin_dir_path(__FILE__);

            self::$url = plugin_dir_url(__FILE__);

            /*
            * Register our autoloader
            */
            spl_autoload_register(array(self::$instance, 'autoloader'));
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action('admin_init', array( $this, 'setup_license'));
        
        add_action('admin_init', [$this, 'checkPhpVersion']);

        add_filter('ninja_forms_from_settings_types', [$this, 'formSettingsTypes'], 10, 1);
        add_filter('ninja_forms_localize_forms_settings', [$this, 'formSettings'], 10, 1);

        add_filter('ninja_forms_action_email_settings', [$this, 'emailSettings'], 10, 1);

        add_filter('ninja_forms_action_email_attachments', [$this, 'attachFiles'], 10, 3);

        add_filter('post_row_actions', [$this, 'downloadLink'], 10, 2);

        // handle exporting PDFs from view submission page
        if (isset($_REQUEST['ninja_forms_export_subs_to_pdf']) && $_REQUEST['ninja_forms_export_subs_to_pdf'] != '') {
            add_action('admin_init', [$this, 'bulkExportPdf']);
        }

        add_action('admin_init', [$this, 'checkCustomTemplates']);
        add_action('init', [$this, 'registerSubmissionHandler']);
    }

    /*
    |--------------------------------------------------------------------------
    | Admin Notices
    |--------------------------------------------------------------------------
    */
    
    public function registerSubmissionHandler( ): void
    {
        if(!class_exists(AbstractSubmissionHandler::class)){
            return;
        }
        
        new NF_Pdf_Submissions_Admin_SingleSubmissionExport();
    }

    public function checkPhpVersion()
    {
        if (!class_exists('Ninja_Forms', false)) {
            return;
        }
        // If we load 2.9x.
        if ( version_compare( get_option( 'ninja_forms_version', '0.0.0' ), '3', '<' ) || get_option( 'ninja_forms_load_deprecated', FALSE ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( 'This version of PDF Form Submissions requires Ninja Forms THREE to function.' );
        }
        $TARGET_VERSION = '5.6.0';
        $php_ver = phpversion();
        // If we have a php version lower than 5.6.
        if (version_compare($php_ver, $TARGET_VERSION, '<')) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( 'This version of PDF Form Submissions requires PHP 5.6 or higher to function.' );
        }
    }

    /**
     * Conditionally add admin banner for any custom template notifications
     *
     * @return void
     */
    public function checkCustomTemplates()
    {
        // Exit early if they're not using custom templates.
        if (!get_transient('ninja_forms_using_custom_pdf_template')) {
            return;
        }

        // Disabled when no notices are required.  Uncomment to re-enable and add notices when required
        // add_filter('nf_admin_notices', [$this, 'customPdfTemplateNotice']);
    }

    /**
     * Add admin notices as required for this plugin
     *
     * @param [type] $notices
     * @return void
     */
    public function customPdfTemplateNotice($notices)
    {

        return $notices;
    }


    /**
     * Load Config File
     *
     * @param $file_name
     * @return array
     */
    public static function config($file_name)
    {
        return include self::$dir . 'includes/Config/' . $file_name . '.php';
    }

    public function formSettingsTypes($types)
    {
        if (!is_admin()) {
            return;
        }

        return array_merge($types, NF_Pdf_Submissions::config('AdvancedSettingsTypes'));
    }

    public function formSettings($settings)
    {
        if (!is_admin()) {
            return;
        }

        return array_merge($settings, NF_Pdf_Submissions::config('Settings'));
    }

    /**
     * Add advanced email setting to attach a PDF.
     *
     * @param array $settings
     *
     * @return array $settings
     */
    public function emailSettings($settings)
    {
        $settings['attach_pdf'] = array(
            'name'        => 'attach_pdf',
            'type'        => 'toggle',
            'label'       => __('Attach PDF', 'ninja-forms-pdf'),
            'width'       => 'one-half',
            'group'       => 'advanced',
        );

        return $settings;
    }

    /**
     * Attach the PDF to the email.
     *
     * @param array $attachments
     * @param array $data
     * @param array $settings
     *
     * @return array
     */
    public function attachFiles($attachments, $data, $settings)
    {
        if (isset($settings['attach_pdf']) && 1 == $settings['attach_pdf']) {
            $email_attachment = new NF_Pdf_Submissions_Actions_Integrations_Email_Attachment($data);

            // Append file path.
            $attachments[] = $email_attachment->attachPdf();
        }

        return $attachments;
    }

    /**
     * Add PDF download link on the view form submission page
     */
    public function downloadLink($actions, $post)
    {
        if ('nf_sub' != $post->post_type) {
            return $actions;
        }

        // create download link
        $args = [
            'ninja_forms_export_subs_to_pdf' => 1,
            'sub_id' => $post->ID
        ];

        $pdf_download_link = add_query_arg($args, admin_url());

        // turn on the output buffer
        ob_start();
        ?>
        <span class="export"><a href="<?php echo $pdf_download_link;?>" class="ninja-forms-export-sub-pdf"><?php _e('Export to PDF', 'nf-pdf'); ?></a></span>
        <?php
        $action = ob_get_clean();

        // return the new html with the rest of the $row_actions array
        $actions['export_pdf'] = $action;

        return $actions;
    }

    /**
     * Bulk export the PDFs
     */
    public function bulkExportPdf()
    {
        // make sure we have the right data
        if (!isset($_REQUEST['sub_id']) || !$_REQUEST['sub_id']) {
            return;
        }

        $sub_id = absint($_REQUEST['sub_id']);

        $sub_pdf = new NF_Pdf_Submissions_Admin_Submission($sub_id);

        $sub_pdf->bulkExportPdf();
    }

    public function setup_license()
    {
        if ( class_exists( 'NF_Extension_Updater' ) ) {
            $this->updated = new NF_Extension_Updater( self::NAME, self::VERSION, self::AUTHOR, __FILE__, self::SLUG );
        }
    }

    /**
     * Autoloader
     *
     * Loads files using the class name to mimic the folder structure.
     *
     * @param $class_name
     */
    public function autoloader($class_name)
    {
        if (class_exists($class_name)) {
            return;
        }

        if (false === strpos($class_name, self::PREFIX)) {
            return;
        }

        $class_name = str_replace(self::PREFIX, '', $class_name);
        $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
        $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

        if (file_exists($classes_dir . $class_file)) {
            require_once $classes_dir . $class_file;
        }
    }
}

function NfPdfSubmissions()
{
    return NF_Pdf_Submissions::instance();
}

NfPdfSubmissions();
