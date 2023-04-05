<?php namespace flow;

use flow\db\FFDB;
use flow\db\FFDBManager;
use flow\db\FFDBMigrationManager;
use la\core\cache\LAFacebookCacheAdapter;
use la\core\db\LADB;
use la\core\db\LADDLUtils;
use la\core\LAActivatorBase;
use la\core\LAUtils;
use la\core\snapshots\LASnapshotManager;
use ReflectionException;
use wpdb;

class FlowFlowActivator extends LAActivatorBase{

    /**
     * @throws ReflectionException
     */
    protected function checkPlugin(){
        $mm = new FFDBMigrationManager($this->context);
        $mm->migrate();
        unset($mm);
    }

    /**
     * @param $file
     *
     * @return array
     */
	protected function initContext($file){
		/** @var wpdb $wpdb */
		$wpdb = $GLOBALS['wpdb'];
		
		$context = [
				'root'              => plugin_dir_path($file),
				'slug'              => 'flow-flow',
				'slug_down'         => 'flow_flow',
				'plugin_url'        => plugin_dir_url(dirname($file).'/'),
				'plugin_dir_name'   => basename(dirname($file)),
				'admin_url'         => admin_url('admin-ajax.php'),
				'table_name_prefix' => $wpdb->prefix . 'ff_',
				'version' 			=> '4.9.4',
				'faq_url' 			=> 'https://docs.social-streams.com/',
				'count_posts_4init'	=> 30
        ];
		$adapter = new LAFacebookCacheAdapter();
		$context['facebook_cache'] = $adapter;
		$context['db_manager'] = new FFDBManager($context);
		$adapter->setContext($context);
		return $context;
	}
	
	protected function checkEnvironment(){
		if(version_compare(PHP_VERSION, '5.6.0') == -1){
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( '<b>Flow-Flow Social Stream</b> plugin requires PHP version 5.6.0 or higher. Pls update your PHP version or ask hosting support to do this for you, you are using old and unsecure one' );
		}
		
		if(!function_exists('curl_version')){
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( '<b>Flow-Flow Social Stream</b> plugin requires curl extension for php. Please install/enable this extension or ask your hosting to help you with this.' );
		}
		
		if(!function_exists('mysqli_connect')){
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( '<b>Flow-Flow Social Stream</b> plugin requires mysqli extension for MySQL. Please install/enable this extension on your server or ask your hosting to help you with this. <a href="http://php.net/manual/en/mysqli.installation.php">Installation guide</a>' );
		}
	}
	
	protected function singleSiteDeactivate(){
		wp_clear_scheduled_hook( 'flow_flow_load_cache' );
		wp_clear_scheduled_hook( 'flow_flow_load_cache_4disabled' );
		wp_clear_scheduled_hook( 'flow_flow_email_notification' );
		wp_clear_scheduled_hook( 'flow_flow_check_facebook_token' );
	}
	
	protected function beforePluginLoad(){
		parent::beforePluginLoad();

        try {
            do_action('ff_addon_loaded', $this->context);
        } catch (\Error $e){
            error_log($e->getMessage());
        }

		if (! defined('FF_AJAX_URL')) {
			$admin = function_exists('current_user_can') && current_user_can('manage_options');
			if (!$admin && defined('FF_ALTERNATE_GET_DATA') && FF_ALTERNATE_GET_DATA){
				$this->setContextValue('ajax_url', plugins_url( 'ff.php', __FILE__ ));
			}
			else {
				$this->setContextValue('ajax_url', admin_url('admin-ajax.php'));
			}

			if (defined('FF_BOOST_SERVER') && !empty(FF_BOOST_SERVER)){
				$this->setContextValue('public_url', FF_BOOST_SERVER . 'flow-flow/ff');
			}
		}

        /** @noinspection PhpExpressionResultUnusedInspection */
        new FlowFlowUpdater($this->context);
	}
	
	protected function registerCronActions(){
        parent::registerCronActions();

		$time = time();
		$ff = FlowFlow::get_instance($this->context);
		
		add_action('flow_flow_load_cache', [ $ff, 'refreshCache' ] );
		if(false == wp_next_scheduled('flow_flow_load_cache')){
			wp_schedule_event($time, 'minute', 'flow_flow_load_cache');
		}
		
		add_action('flow_flow_load_cache_4disabled', [ $ff, 'refreshCache4Disabled' ] );
		if(false == wp_next_scheduled('flow_flow_load_cache_4disabled')){
			wp_schedule_event($time, 'six_hours', 'flow_flow_load_cache_4disabled');
		}

		add_action('flow_flow_email_notification', [ $ff, 'emailNotification' ] );
		if(false == wp_next_scheduled('flow_flow_email_notification')){
			wp_schedule_event($time, 'daily', 'flow_flow_email_notification');
		}

        add_action('flow_flow_check_facebook_token', [$ff, 'checkFacebookToken']);
        if (false == wp_next_scheduled('flow_flow_check_facebook_token')){
            wp_schedule_event($time, 'daily', 'flow_flow_check_facebook_token');
        }
	}

    protected function registerShutdownActions() {
        add_action( 'shutdown',  [ $this, 'shutdownAction' ] );
    }

    /** @noinspection DuplicatedCode */
    protected function registerAjaxActions(){
        $dbm = LAUtils::dbm($this->context);
        $slug_down = LAUtils::slug_down($this->context);
        $ff = FlowFlow::get_instance($this->context);

		// public endpoints
		add_action('wp_ajax_fetch_posts', [ $ff, 'processAjaxRequest' ] );
		add_action('wp_ajax_nopriv_fetch_posts', [ $ff, 'processAjaxRequest' ] );
        add_action('wp_ajax_load_cache', [ $ff, 'processAjaxRequestBackground' ] );
        add_action('wp_ajax_nopriv_load_cache', [ $ff, 'processAjaxRequestBackground' ] );
        add_action('wp_ajax_' . $slug_down . '_load_comments_and_carousel', [ $ff, 'loadCommentsAndCarousel' ] );
        add_action('wp_ajax_nopriv_' . $slug_down . '_load_comments_and_carousel', [ $ff, 'loadCommentsAndCarousel' ] );

        // roles detect
		add_action('wp_ajax_' . $slug_down . '_moderation_apply_action', [ $ff, 'moderation_apply' ] );

		// secured endpoints
		add_action('wp_ajax_' . $slug_down . '_sources',			    [ $dbm, 'get_sources' ] );
		add_action('wp_ajax_' . $slug_down . '_social_auth',			[ $dbm, 'social_auth' ] );
		add_action('wp_ajax_' . $slug_down . '_save_sources_settings',	[ $dbm, 'save_sources_settings' ] );
		add_action('wp_ajax_' . $slug_down . '_get_stream_settings',	[ $dbm, 'get_stream_settings' ] );
		add_action('wp_ajax_' . $slug_down . '_get_shortcode_pages',	[ $dbm, 'get_shortcode_pages' ] );
		add_action('wp_ajax_' . $slug_down . '_ff_save_settings',		[ $dbm, 'ff_save_settings_fn' ] );
		add_action('wp_ajax_' . $slug_down . '_save_stream_settings',	[ $dbm, 'save_stream_settings' ] );
		add_action('wp_ajax_' . $slug_down . '_create_stream',			[ $dbm, 'create_stream' ] );
		add_action('wp_ajax_' . $slug_down . '_clone_stream',			[ $dbm, 'clone_stream' ] );
		add_action('wp_ajax_' . $slug_down . '_delete_stream',			[ $dbm, 'delete_stream' ] );

        //boosts
        add_action('wp_ajax_' . $slug_down . '_get_boosts',             [ $dbm, 'get_boosts' ] );
        add_action('wp_ajax_' . $slug_down . '_payment_success',        [ $dbm, 'paymentSuccess' ] );
        add_action('wp_ajax_' . $slug_down . '_upgrade_subscription',   [ $dbm, 'upgradeSubscription' ] );
        add_action('wp_ajax_' . $slug_down . '_cancel_subscription',    [ $dbm, 'cancelSubscription' ] );
        add_action('wp_ajax_' . $slug_down . '_clear_subscription',     [ $dbm, 'clearSubscriptionCache' ] );

		new LASnapshotManager($this->context);
		
		if (!FF_USE_WP_CRON){
			add_action('wp_ajax_' . $slug_down . '_refresh_cache', [ $ff, 'refreshCache' ] );
			add_action('wp_ajax_nopriv_' . $slug_down . '_refresh_cache', [ $ff, 'refreshCache' ] );
		}
	}
	
	protected function renderAdminSide(){
        /** @noinspection PhpExpressionResultUnusedInspection */
        new FlowFlowAdmin($this->context);
	}
	
	protected function renderPublicSide(){
		$ff = FlowFlow::get_instance($this->context);
		add_action('init',					[ $ff, 'register_shortcodes' ] );
		add_action('init',					[ $ff, 'load_plugin_textdomain' ] );
		add_action('wp_enqueue_scripts',	[ $ff, 'enqueue_scripts' ] );
		add_action('wpmu_new_blog',			[ $ff, 'activate_new_site' ] );
	}

    public function shutdownAction(){

        $error = error_get_last();

        if( is_null( $error ) ) {
            return;
        }

        $fatals = [
            E_USER_ERROR => 'Fatal Error',
            E_ERROR => 'Fatal Error',
            E_PARSE => 'Parse Error',
            E_CORE_ERROR => 'Core Error',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_ERROR => 'Compile Error',
            E_COMPILE_WARNING => 'Compile Warning'
        ];

        // check if error related to flow-flow
        if ( strpos( $error['file'], 'flow-flow' ) !== false  && isset( $fatals[ $error['type'] ] ) ) {

            // error_log(print_r(debug_backtrace(), true));

            $msg = $fatals[ $error['type'] ] . ': ' . $error['message'] . ' in ';
            $msg .= $error['file'] . ' on line ' . $error['line'] . PHP_EOL;

            if (!empty( $msg )) {

                error_log( $msg , 3, FF_LOG_FILE_DEST);

            }

        }

    }

    /**
     * Use this method fpr old php version
     * @deprecated
     */
    public function initWPWidget(){
        if (!defined('FF_ENABLE_WIDGET') || FF_ENABLE_WIDGET){
            $widget = new FlowFlowWPWidget();
            $widget->setContext($this->context);
            register_widget($widget);
        }
    }

    /**
     * Use this method fpr old php version
     * @deprecated
     */
    public function initVCIntegration(){
        $dbm = LAUtils::dbm($this->context);

        //Important!
        //It will be execute before migrations!
        //Need to check exist tables and fields!
        $streams = [];
        if (LADDLUtils::existTable($dbm->conn(), $dbm->streams_table_name)) {
            $streams = LADB::streams( $dbm->conn(), $dbm->streams_table_name );
        }

        $stream_options = [];
        if(sizeof($streams)){
            foreach($streams as $id => $stream){
                $stream_options['Stream #' . $id . ( $stream['name'] ? ' - ' . $stream['name'] : '')] = $id;
            }
        }

        /** @noinspection PhpUndefinedFunctionInspection */
        vc_map( [
            "name" => __("Social Stream"),
            'admin_enqueue_css' => [ LAUtils::plugin_url($this->context) . '/css/admin-icon.css' ],
            'front_enqueue_css' => [ LAUtils::plugin_url($this->context) . '/css/admin-icon.css' ],
            'icon' => 'streams-icon',
            "description" => __("Flow-Flow plugin social stream"),
            "base" => "ff",
            "category" => __('Social'),
            "weight" => 0,
            "params" => [
                [
                    'type' => 'dropdown',
                    'class' => '',
                    'admin_label' => true,
                    "holder" => "div",
                    "heading" => __("Choose stream to place on page:" ),
                    "description" => "Please create and edit stream on plugin's page in admin.",
                    "param_name" => "id",
                    "value" => $stream_options,
                    "std" => '--'
                ]
            ]
        ] );
    }
}