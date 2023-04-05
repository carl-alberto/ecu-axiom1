<?php namespace la\core\db;

use Exception;
use la\core\LAUtils;
use la\core\settings\LAGeneralSettings;
use la\core\settings\LASettingsUtils;
use mysqli;
use Unirest\Request;
use Unirest\Response;

if ( ! defined( 'WPINC' ) ) die;
/**
 * FlowFlow.
 *
 * @property LASafeMySQL $conn
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      https://looks-awesome.com
 * @copyright Looks Awesome
 */
abstract class LADBManager {
    public $table_prefix;
    public $option_table_name;
    public $posts_table_name;
    public $cache_table_name;
    public $streams_table_name;
    public $image_cache_table_name;
    public $streams_sources_table_name;
    public $snapshot_table_name;
    public $comments_table_name;
    public $post_media_table_name;

    protected $context;
    protected $plugin_slug;
    protected $plugin_slug_down;
    protected $init = false;
    protected $sources = null;
    protected $streams = null;

    private $conn = null;

    /**
     * LADBManager constructor.
     *
     * @param array $context
     */
    function __construct($context) {
        $this->context = $context;
        $this->table_prefix = $context['table_name_prefix'];
        $this->plugin_slug = LAUtils::slug($context);
        $this->plugin_slug_down = LAUtils::slug_down($context);

        $this->option_table_name = $this->table_prefix . 'options';
        $this->posts_table_name = $this->table_prefix . 'posts';
        $this->cache_table_name = $this->table_prefix . 'cache';
        $this->streams_table_name = $this->table_prefix . 'streams';
        $this->image_cache_table_name = $this->table_prefix . 'image_cache';
        $this->streams_sources_table_name = $this->table_prefix . 'streams_sources';
        $this->snapshot_table_name= $this->table_prefix . 'snapshots';
        $this->comments_table_name= $this->table_prefix . 'comments';
        $this->post_media_table_name = $this->table_prefix . 'post_media';
    }

    public function conn($reopen = false) {
        if ($reopen || $this->conn == null){
            $this->conn = LADB::create();
            $this->conn->autocommit(true);
        }
        return $this->conn;
    }

    /**
     * @param false $only_enable
     * @param false $safe
     * @param bool $remote
     *
     * @throws Exception
     */
    public final function dataInit($only_enable = false, $safe = false, $remote = true){
        $this->init = true;

        $conn = $this->conn();
        if ($safe && !LADDLUtils::existTable($conn, $this->streams_sources_table_name)) {
            $this->sources = [];
            $this->streams = [];
            return;
        }

        $boosted = [];
        $load_boosted = false;
        $sources = LADB::sources($conn, $this->cache_table_name, $this->streams_sources_table_name, null, $only_enable);
        if ($remote) {
            foreach ( $sources as $id => &$tmp_source ) {
                if ($tmp_source['boosted'] == LASettingsUtils::YEP){
                    if (!$load_boosted){
                        $boosted = $this->getBoostSources();
                        $load_boosted = true;
                    }
                    if (isset($boosted[$id])){
                        $tmp_source = $boosted[$id];
                    }
                }
            }
        }
        $this->sources = $sources;
        $this->streams = LADB::streams($conn, $this->streams_table_name);
        $connections = $conn->getIndMultiRow('stream_id', 'select `stream_id`, `feed_id` from ?n order by `stream_id`', $this->streams_sources_table_name);
        foreach ( $this->streams as &$stream ) {
            $stream = (array)LADB::unserializeStream($stream);
            if (!isset($stream['feeds'])) $stream['feeds'] = [];
            $stream['status'] = '1';
            if (isset($connections[$stream['id']])){
                foreach ($connections[$stream['id']] as $source){
                    if (isset($this->sources[$source['feed_id']])){
                        $full_source = $this->sources[$source['feed_id']];
                        $stream['feeds'][] = $full_source;
                        if (isset($full_source['status']) && $full_source['status'] == 0) $stream['status'] = '0';
                    }
                }
            }
        }
    }

    /**
     * Get stream settings by id endpoint
     * @throws Exception
     */
    public final function get_stream_settings(){

        if (FF_USE_WP) {
            if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                die( json_encode( [ 'error' => 'not_allowed' ] ) );
            }
        }

        $id = $_GET['stream-id'];
        $this->dataInit(false, false);

        $stream = $this->streams[$id];

        // cleaning if error was saved in database stream model, can be removed in future, now it's needed for affected users
        if ( isset( $stream['error'] ) ) unset( $stream['error'] );

        die( json_encode( $stream ) );
    }

    public final function get_shortcode_pages() {
        global $wpdb;

        if (FF_USE_WP) {
            if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                die( json_encode( [ 'error' => 'not_allowed' ] ) );
            }
        }

        $stream = $_POST['stream'];

        $query = "SELECT ID, post_title FROM " . $wpdb->posts . " WHERE post_content LIKE '%s' AND post_status = 'publish'";

        $results = $wpdb->get_results ( $wpdb->prepare( $query, '%[ff id="' . $stream . '"%' ) );

        foreach ($results as $result) {
            $result->url = get_permalink( $result->ID );
        }

        die( json_encode( $results ) );
    }

    /**
     * Create stream endpoint
     */
    public final function create_stream(){
        $this->checkSecurity();

        $stream = $this->getStreamFromRequestWithoutErrors();
        $conn = $this->conn();
        try{
            $conn->beginTransaction();
            if (false !== ($max = LADB::maxIdOfStreams( $conn, $this->streams_table_name))){
                $newId = (string) ($max + 1);
                $stream->id = $newId;
                $stream->feeds = isset($stream->feeds) ? $stream->feeds : json_encode( [] );
                $stream->name = isset($stream->name) ? $stream->name : '';
                LADB::setStream($conn, $this->streams_table_name, $this->streams_sources_table_name, $newId, $stream);
                $response = json_encode(LADB::getStream($conn, $this->streams_table_name, $newId));
                $conn->commit();

                $this->refreshCache($newId);
                echo $response;
            }
            else echo false;
        }catch ( Exception $e){
            $conn->rollbackAndClose();
            echo 'Caught exception: ' .  $e->getMessage() . "\n";
        }
        $conn->close();
        die();
    }

    /**
     * Save sources endpoint
     * @throws Exception
     */
    public final function save_sources_settings(){
        if (FF_USE_WP) {
            if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                $dontChange = true;
            }
        }

        if (isset($_POST['model'])){
            $model = $_POST['model'];
            $model['id'] = 1; // DON'T DELETE, ID is always 1, this is needed to detect if model was saved

            if (isset( $dontChange ) && isset( $_POST['model']['feeds_changed'] )) {
                unset( $_POST['model']['feeds_changed'] );
            }

            $boosted = false;
            $original_status = 0;
            if (isset($_POST['model']['feeds_changed'])){
                foreach ( $_POST['model']['feeds_changed'] as $feed ) {
                    switch ($feed['state']) {
                        case 'changed':
                            $source = $_POST['model']['feeds'][ $feed['id'] ];
                            $original_status = $source['status'];
                            $sources = LADB::sources($this->conn(), $this->cache_table_name, $this->streams_sources_table_name);
                            $old = $sources[$source['id']];
                            $changed_content = $this->changedContent($source, $old);
                            if ($changed_content) {
                                $this->cleanFeed($feed['id']);
                                if (!$boosted){
                                    $boosted = LASettingsUtils::YepNope2ClassicStyle($source['boosted'], false);
                                    if (!$boosted && ($source['boosted'] != $old['boosted'])){
                                        $boosted = true;
                                    }
                                }
                            }
                            else if (!$boosted){
                                $boosted = LASettingsUtils::YepNope2ClassicStyle($source['boosted'], false);
                            }
                            $this->modifySource( $source, $changed_content );
                            if ($source['enabled'] == 'yep') {
                                $this->refreshCache4Source($feed['id'], false, $boosted);
                            }
                            break;
                        case 'created':
                            $source = $_POST['model']['feeds'][$feed['id']];
                            $this->modifySource($source);
                            if (!$boosted){
                                $boosted = LASettingsUtils::YepNope2ClassicStyle($source['boosted'], false);
                            }
                            $this->refreshCache4Source($feed['id'], true, $boosted);
                            break;
                        case 'reset_cache':
                            $source = $_POST['model']['feeds'][ $feed['id'] ];
                            $this->cleanFeed($feed['id']);
                            if (!$boosted){
                                $boosted = LASettingsUtils::YepNope2ClassicStyle($source['boosted'], false);
                            }
                            $this->refreshCache4Source($feed['id'], true, $boosted);
                            break;
                        case 'deleted':
                            $sources = LADB::sources($this->conn(), $this->cache_table_name, $this->streams_sources_table_name);
                            if (isset($sources[$feed['id']])){
                                $source = $sources[$feed['id']];
                                $this->deleteFeed($feed['id']);
                                if (!$boosted){
                                    $boosted = LASettingsUtils::YepNope2ClassicStyle($source['boosted'], false);
                                }
                            }
                            break;
                    }
                }
            }

            if ($boosted){
                $response = $this->proxyRequest($_POST);
                if ($response->code == 200){
                    $json = $response->body;
                    foreach ( $json['feeds'] as &$feed ) {
                        $enabled = LASettingsUtils::YepNope2ClassicStyle($feed['enabled'], false) ? 1 : 0;
                        $status = ['last_update' => $feed['last_update'], 'status' => $original_status, 'enabled' => $enabled];
                        if (isset($feed['errors']) && is_array($feed['errors'])) $status['errors'] = serialize($feed['errors']);
                        $this->saveSource($feed['id'], $status);
                        $feed['last_update'] = $feed['last_update'] == 0 ? 'N/A' : LASettingsUtils::classicStyleDate($feed['last_update']);
                    }
                    echo json_encode($json);
                }
                else if ($response->code == 504){
                    header('HTTP/1.1 504 Gateway Time-out');
                }
                else if ($response->code == 403){
                    header('HTTP/1.1 403 Forbidden');
                    echo $response->raw_body;
                }
            }
            else {
                if (isset($model['feeds'])){
                    $this->dataInit();
                    $sources = $this->sources();
                    foreach ( $model['feeds'] as &$source ) {
                        if (array_key_exists($source['id'], $sources)){
                            $source = $sources[$source['id']];
                        }
                    }
                }
                if (isset( $dontChange )) {
                    $model['error'] = 'Not allowed';
                }
                echo json_encode($model);
            }
            die();
        }
        die(1);
    }

    /**
     * Save stream endpoint
     */
    public final function save_stream_settings(){
        $this->checkSecurity();
        $stream = $this->getStreamFromRequestWithoutErrors();
        $conn = $this->conn();
        try{
            $conn->beginTransaction();
            $stream->last_changes = time();
            LADB::setStream($conn, $this->streams_table_name, $this->streams_sources_table_name, $stream->id, $stream);

            $this->generateCss($stream);

            echo json_encode($stream);
            $conn->commit();

            $this->proxyRequest($_POST);
        }catch (Exception $e){
            $conn->rollbackAndClose();
            error_log('save_stream_settings error:');
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
        }
        $conn->close();
        die();
    }

    /**
     * Save general settings endpoint
     */
    public final function ff_save_settings_fn() {

        if (FF_USE_WP) {
            if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                die( json_encode( [ 'error' => 'not_allowed' ] ) );
            }
        }

        $serialized_settings = $_POST['settings']; // param1=foo&param2=bar
        $settings = [];
        parse_str( $serialized_settings, $settings );

        $conn = $this->conn();
        try{
            $activated = $this->activate($settings);
            $force_load_cache = $this->clean_cache($settings);

            $conn->beginTransaction();

            $settings = $this->saveGeneralSettings($settings);

            $conn->commit();

            global $wp_locale;
            $_POST['wp_locale'] = json_encode($wp_locale);
            $_POST['wp_timezone_string'] = get_option( 'timezone_string' );
            $_POST['wp_date_format'] = get_option( 'date_format' );
            $_POST['wp_time_format'] = get_option( 'time_format' );
            if (false !== ($option = get_option( 'la_facebook_access_token', false )))
                $_POST['la_facebook_access_token'] = $option;
            if (false !== ($option = get_option( 'la_facebook_access_token_expires', false )))
                $_POST['la_facebook_access_token_expires'] = $option;

            $this->proxyRequest($_POST);

            if ($force_load_cache) {
                $this->refreshCache(null, $force_load_cache);
            }

            $response = [
                'settings' => $settings,
                'activated' => $activated
            ];
            $this->customizeResponse($response);

            echo json_encode( $response );
        }catch ( Exception $e){
            error_log('ff_save_settings_fn error:');
            $msg = $e->getMessage();

            if ( strpos( $msg, 'Connection timed out after') !== false ) {
                $msg .= '. Failed to connect to https://flow.looks-awesome.com which validates purchase code. Please ask help from your hosting support and tell them curl_exec exits with connection timeout error on line 889 of wp-content/plugins/flow-flow/includes/db/LADBManager.php';
            }

            error_log( $msg );
            error_log($e->getTraceAsString());
            $conn->rollbackAndClose();
            die($e->getMessage());
        }
        $conn->close();
        die();
    }

    /**
     * @param mixed $old_value
     * @param mixed $value
     * @param string $option
     *
     * @noinspection PhpUnusedParameterInspection
     * @throws Exception
     */
    public function update_wp_date_format_hook($old_value, $value, $option){
        if ($option === 'WPLANG'){
            switch_to_locale(empty($value) ? 'en_US' : $value);
        }
        global $wp_locale;
        $post['action'] = 'flow_flow_save_settings_date_format';
        $post['wp_locale'] = json_encode($wp_locale);
        $post['wp_timezone_string'] = get_option( 'timezone_string' );
        $post['wp_date_format'] = get_option( 'date_format' );
        $post['wp_time_format'] = get_option( 'time_format' );
        $this->proxyRequest($post);
    }

    /**
     * @throws Exception
     */
    public function update_options(){
        $data = [
            'action' => 'flow_flow_save_settings',
            'options' => $this->getOption('options', false, false, true),
            'fb_auth_options' => $this->getOption('fb_auth_options', false, false, true)
        ];
        if (false != ($la_facebook_access_token = get_option('la_facebook_access_token', false))){
            $data['la_facebook_access_token'] = $la_facebook_access_token;
        }
        if (false != ($la_facebook_access_token_expires = get_option('la_facebook_access_token_expires', false))) {
            $data['la_facebook_access_token_expires'] = $la_facebook_access_token_expires;
        }
        $this->proxyRequest($data);
    }

    /**
     * @throws Exception
     */
    public function get_sources(  ) {
        if (FF_USE_WP) {
            if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                die( json_encode( [ 'error' => 'not_allowed' ] ) );
            }
        }

        $this->dataInit(false, false);
        if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
            if (isset($this->sources[$_REQUEST['id']])){
                die( json_encode( $this->sources[$_REQUEST['id']] ) );
            }
            else {
                header("HTTP/1.0 404 Not Found");
                die();
            }
        }
        die( json_encode( $this->sources ) );
    }

    /**
     * @throws Exception
     */
    public final function get_boosts(){
        if ( FF_USE_WP ) {
            if ( !current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                die( json_encode( [ 'error' => 'not_allowed' ] ) );
            }
        }

        if ( isset($_POST['not_active']) ) {
            // return dummy
            $response = [
                'status' => 'never_used', // 'active', 'cancelled', 'paused'
                'plan' => 0,
                'available' => 0,
                'expire' => 0
            ];
            echo json_encode( $response );
            die();
        }

        if (null != ($token = $this->getToken())){
            $response = Request::post(FF_BOOST_SERVER . 'flow-flow/ff', [
                'Content-Type: application/x-www-form-urlencoded'
            ], http_build_query(['action' => 'get_subscription', 'token' => $token]));

            if ($response->code == 200 && !empty($response->raw_body) && is_object($response->body)) {
                $subscription = json_encode($response->body);
                if (JSON_ERROR_NONE == json_last_error()){
                    echo $subscription;
                }
                else {
                    error_log($response->raw_body);
                }
            }
            else if ($this->isExpiredToken($response)){
                $this->get_boosts();
            }
            else {
                error_log($response->raw_body);
            }
        }
        die;
    }

    /**
     * @throws Exception
     */
    public function paymentSuccess(){
        $email = $_REQUEST['email'];
        $checkout_id = $_REQUEST['checkout_id'];
        $this->setOption(   'boosts_email', $email);
        $this->setOption(   'boosts_checkout_id', $checkout_id);
        $this->deleteOption('boosts_token');
        $this->deleteOption('boosts_subscription');

        $domain = $_SERVER['HTTP_HOST'];
        $url = FF_BOOST_SERVER . 'registration?shop=' . $domain;

        $data = [
            'action' => 'domain_registration',
            'email' => $email,
            'checkout_id' => $checkout_id,
            'options' => $this->getOption('options', false, false, true),
            'fb_auth_options' => $this->getOption('fb_auth_options', false, false, true),
            'url' => get_site_url()
        ];
        global $wp_locale;
        $data['wp_locale'] = json_encode($wp_locale);
        $data['wp_timezone_string'] = get_option( 'timezone_string' );
        $data['wp_date_format'] = get_option( 'date_format' );
        $data['wp_time_format'] = get_option( 'time_format' );
        if (false != ($la_facebook_access_token = get_option('la_facebook_access_token', false))){
            $data['la_facebook_access_token'] = $la_facebook_access_token;
        }
        if (false != ($la_facebook_access_token_expires = get_option('la_facebook_access_token_expires', false))) {
            $data['la_facebook_access_token_expires'] = $la_facebook_access_token_expires;
        }
        Request::jsonOpts(true);
        Request::timeout(120);
        $response = Request::post($url, [
            'Content-Type: application/x-www-form-urlencoded'
        ], http_build_query($data));

        if ($response->code != 200) {
            error_log($response->raw_body);
            $this->deleteOption('boosts_email');
            $this->deleteOption('boosts_checkout_id');
            if ($response->code == 403 && isset($response->body['error']) && $response->body['error'] == 'Exceeded the limit on the number of domains') {
                header('Location: ' . admin_url('admin.php?page=flow-flow-admin&subscription=exceeded_domains'), true, 301);
                die();
            }
        }

        header('Location: ' . admin_url('admin.php?page=flow-flow-admin&subscription=1'), true, 301);
        die();
    }

    /**
     * @throws Exception
     */
    public function upgradeSubscription() {
        if (null != ($token = $this->getToken())){
            $plan_id = $_REQUEST['plan_id'];
            $response = Request::post(FF_BOOST_SERVER . 'flow-flow/ff', [
                'Content-Type: application/x-www-form-urlencoded'
            ], http_build_query(['action' => 'upgrade_subscription', 'token' => $token, 'plan_id' => $plan_id]));

            if ($response->code == 200) {
                $response->body = (array)$response->body;
                if ($response->body['success']){
                    header('Location: ' . admin_url('admin.php?page=flow-flow-admin'), true, 301);
                    die;
                }
            }
            else if ($this->isExpiredToken($response)){
                $this->upgradeSubscription();
            }
            else {
                error_log($response->raw_body);
            }
        }
        error_log('FLOW-FLOW DEBUG: no subscription token' );
        http_response_code(500);
        die;
    }

    /**
     * @throws Exception
     */
    public function cancelSubscription() {
        if (null != ($token = $this->getToken())){
            $response = Request::post(FF_BOOST_SERVER . 'flow-flow/ff', [
                'Content-Type: application/x-www-form-urlencoded'
            ], http_build_query(['action' => 'cancel_subscription', 'token' => $token]));

            if ($response->code == 200) {
                $response->body = (array)$response->body;
                if ($response->body['success']){
                    $this->deleteOption('boosts_email');
                    $this->deleteOption('boosts_token');
                    $this->deleteOption('boosts_checkout_id');
                    $this->deleteOption('boosts_subscription');
                    $this->deleteBoostedFeeds();
                    header('Location: ' . admin_url('admin.php?page=flow-flow-admin'), true, 301);
                    die;
                }
            }
            else if ($this->isExpiredToken($response)){
                $this->cancelSubscription();
            }
            else {
                error_log($response->raw_body);
            }
        }
        error_log('FLOW-FLOW DEBUG: no subscription token' );
        http_response_code(500);
        die;
    }

    /**
     * @throws Exception
     */
    public function clearSubscriptionCache() {
        if (isset($_REQUEST['email'])){
            $email = $_REQUEST['email'];
            $this->setOption(   'boosts_email', $email);
        }
        if (isset($_REQUEST['checkout_id'])){
            $checkout_id = $_REQUEST['checkout_id'];
            $this->setOption(   'boosts_checkout_id', $checkout_id);
        }
        $this->deleteOption('boosts_token');
        $this->deleteOption('boosts_subscription');
        die;
    }

    public final function email_notification(){
        $admin_email = get_option('admin_email');
        if (!empty($admin_email)){
            $conn = $this->conn();
            $disabled_feeds = $conn->getAll('SELECT * FROM ?n WHERE enabled = 1 AND system_enabled = 0 AND send_email = 0', $this->cache_table_name);
            if (!empty($disabled_feeds)){
                ob_start();
                /** @noinspection PhpIncludeInspection */
                include($this->context['root']  . 'views/email.php');
                $message = ob_get_clean();

                $headers = [];
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                $headers[] = 'X-Mailer: PHP/' . phpversion();
//				$headers[] = 'To: ' . $admin_email;
                $headers[] = 'From: Social Stream Apps <' . $admin_email . '>';
                $blog_name = htmlspecialchars_decode(get_bloginfo('name'));

                $success = mail($admin_email, "[Flow-Flow] Broken feeds detected on " . $blog_name, $message, implode("\r\n", $headers));
                if ($success) {
                    try {
                        $conn->beginTransaction();
                        foreach ( $disabled_feeds as $feed ) {
                            $success = $this->saveSource($feed['feed_id'], [ 'send_email' => 1 ] );
                            if (!$success){
                                throw new Exception('Save source problem');
                            }
                        }
                        $conn->commit();
                    }
                    catch ( Exception $e){
                        error_log('email_notification');
                        error_log($e->getMessage());
                        error_log($e->getTraceAsString());
                        $conn->rollbackAndClose();
                    }
                }
                else {
                    $errorMessage = error_get_last();
                    error_log($errorMessage['message']);
                }
            }
        }
    }

    public function modifySource($source, $changed_content = true, $with_errors = false){
        $errors = '';
        $id = $source['id'];
        $enabled = $source['enabled'];
        $cache_lifetime = $source['cache_lifetime'];
        $status = isset($source['status']) ? intval($source['status']) : 0;
        $boosted = $source['boosted'];
        unset($source['id']);
        unset($source['enabled']);
        unset($source['last_update']);
        unset($source['cache_lifetime']);
        unset($source['boosted']);
        if ($with_errors && isset($source['errors'])){
            $errors = serialize($source['errors']);
        }
        if (isset($source['errors'])) unset($source['errors']);
        if (isset($source['status'])) unset($source['status']);
        if (isset($source['system_enabled'])) unset($source['system_enabled']);

        $in = [
            'settings' => serialize((object)$source),
            'enabled' => (int)LASettingsUtils::YepNope2ClassicStyle($enabled, true),
            'system_enabled' => (int)LASettingsUtils::YepNope2ClassicStyle($enabled, true),
            'last_update' => 0,
            'changed_time' => time(),
            'cache_lifetime' => $cache_lifetime,
            'status' => $status,
            'boosted' => $boosted
        ];
        $up = [
            'settings' => serialize((object)$source),
            'enabled' => (int)LASettingsUtils::YepNope2ClassicStyle($enabled, true),
            'system_enabled' => (int)LASettingsUtils::YepNope2ClassicStyle($enabled, true),
            'cache_lifetime' => $cache_lifetime,
            'boosted' => $boosted
        ];
        if ($changed_content) $up['last_update'] =  '0';
        if ($with_errors && !empty($errors)) $up['errors'] = $errors;
        $conn = $this->conn();
        try {
            if ( false === $conn->query( 'INSERT INTO ?n SET `feed_id`=?s, ?u ON DUPLICATE KEY UPDATE ?u',
                    $this->cache_table_name, $id, $in, $up ) ) {
                throw new Exception();
            }
            $conn->commit();
        }
        catch ( Exception $e){
            $conn->rollback();
        }
    }

    private function changedContent( $source, $old ) {
        foreach ( $source as $key => $value ) {
            $old_value = $old[$key];
            if ($key == 'status' || $key == 'enabled' || $key == 'posts' || $key == 'errors' || $key == 'last_update' ||
                $key == 'cache_lifetime' || $key == 'mod' || $key == 'posts') continue;
            if ($old_value !== $value) {
                return true;
            }
        }
        return false;
    }

    public function getGeneralSettings(){
        return new LAGeneralSettings($this->getOption('options', true), $this->getOption('fb_auth_options', true));
    }


    public function getOption( $optionName, $serialized = false, $lock_row = false, $without_cache = false ) {
        $options = LADB::getOption($this->conn(), $this->option_table_name, $this->plugin_slug_down . '_' . $optionName, $serialized, $lock_row, $without_cache);
        if ($optionName == 'options' && is_array($options)) {
            $options['general-uninstall'] = get_option($this->plugin_slug_down . '_general_uninstall', LASettingsUtils::NOPE);
        }
        return $options;
    }

    /**
     * @param $optionName
     * @param $optionValue
     * @param false $serialized
     * @param bool $cached
     *
     * @throws Exception
     */
    public function setOption($optionName, $optionValue, $serialized = false, $cached = true){
        LADB::setOption($this->conn(), $this->option_table_name, $this->plugin_slug_down . '_' . $optionName, $optionValue, $serialized, $cached);
    }

    /**
     * @param $optionName
     *
     * @throws Exception
     */
    public function deleteOption($optionName){
        LADB::deleteOption($this->conn(), $this->option_table_name, $this->plugin_slug_down . '_' . $optionName);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function streams(){
        if ($this->init) return $this->streams;
        throw new Exception('Don`t init data manager');
    }

    public function countFeeds(){
        return LADB::countFeeds($this->conn(), $this->cache_table_name);
    }

    public function getStream($streamId){
        return $this->streams[$streamId];
    }

    public function delete_stream(){

        if (FF_USE_WP) {
            if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                die( json_encode( [ 'error' => 'not_allowed' ] ) );
            }
        }

        $conn = $this->conn();
        try {
            $conn->beginTransaction();
            $id = $_POST['stream-id'];
            LADB::deleteStream($conn, $this->streams_table_name, $this->streams_sources_table_name, $id);
            do_action('ff_after_delete_stream', $id);
            $conn->commit();

            $this->proxyRequest($_POST);
        } catch (Exception $e){
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            $conn->rollbackAndClose();
            die(false);
        }
        wp_send_json([], 200);
    }

    public function canCreateCssFolder(){
        $dir = WP_CONTENT_DIR . '/resources/' . $this->context['slug'] . '/css';
        if(!file_exists($dir)){
            return mkdir($dir, 0777, true);
        }
        return true;
    }

    public function generateCss($stream){
        $dir = WP_CONTENT_DIR . '/resources/' . $this->context['slug'] . '/css';
        if(!file_exists($dir)){
            mkdir($dir, 0777, true);
        }

        $filename = $dir . "/stream-id" . $stream->id . ".css";
        if (!is_main_site()){
            $filename = $dir . '/stream-id' . $stream->id . '-'. get_current_blog_id() . '.css';
        }
        ob_start();
        /** @noinspection PhpIncludeInspection */
        include($this->context['root']  . 'views/stream-template-css.php');
        $output = ob_get_clean();
        $a = fopen($filename, 'w');
        fwrite($a, $output);
        fclose($a);
        chmod($filename, 0644);
    }

    public function clone_stream(){

        if (FF_USE_WP) {
            if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                die( json_encode( [ 'error' => 'not_allowed' ] ) );
            }
        }

        $stream = $_REQUEST['stream'];

        // cleaning if error was saved in database stream model, can be removed in future, now it's needed for affected users
        if ( isset( $stream['error'] ) ) unset( $stream['error'] );

        $stream = (object)$stream;
        $conn = $this->conn();
        try{
            $conn->beginTransaction();
            if (false !== ($count = LADB::maxIdOfStreams( $conn, $this->streams_table_name))) {
                $newId = (string) ($count + 1);
                $stream->id = $newId;
                $stream->name = "{$stream->name} copy";
                $stream->last_changes = time();
                LADB::setStream($conn, $this->streams_table_name, $this->streams_sources_table_name, $newId, $stream);
                $this->generateCss($stream);

                $this->proxyRequest($_POST);

                $conn->commit();
                echo json_encode($stream);
            }
            else {
                throw new Exception('Can`t get a new id for the clone stream');
            }
        }catch (Exception $e){
            $conn->rollbackAndClose();
            error_log('clone_stream error:');
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
        }
        $conn->close();
        die();
    }

    /**
     * @param $settings
     *
     * @return mixed
     * @throws Exception
     */
    protected function saveGeneralSettings($settings){
        if (isset($settings['flow_flow_options']['general-uninstall'])){
            $general_uninstall_option_name = $this->plugin_slug_down . '_general_uninstall';
            $value = ($settings['flow_flow_options']['general-uninstall'] === LASettingsUtils::YEP) ? LASettingsUtils::YEP : LASettingsUtils::NOPE;
            if ( get_option( $general_uninstall_option_name) !== false ) {
                update_option( $general_uninstall_option_name, $value );
            }
            else {
                add_option( $general_uninstall_option_name, $value, '', 'no' );
            }
            unset($settings['flow_flow_options']['general-uninstall']);
        }

        $this->setOption('options', $settings['flow_flow_options'], true);
        return $settings;
    }

    protected abstract function customizeResponse(&$response);

    protected abstract function clean_cache($options);

    protected abstract function refreshCache($streamId, $force_load_cache = false);

    protected function refreshCache4Source($id, $force_load_cache = false, $boosted = false){
        if (!$boosted){
            $this->saveSource($id, ['status' => '2']);

            $useIpv4 = $this->getGeneralSettings()->useIPv4();
            $use = $this->getGeneralSettings()->useCurlFollowLocation();
            $url = $this->getLoadCacheUrl( $id, $force_load_cache );
            LASettingsUtils::get( $url, 1, false, false, $use, $useIpv4);
        }
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function streamsWithStatus(){
        if (false !== ($result = self::streams())){
            return $result;
        }
        return [];
    }

    /**
     * @return array | null
     * @throws Exception
     */
    public function sources(){
        if ($this->init)  return $this->sources;
        throw new Exception('Don`t init data manager');
    }

    //TODO: refactor posts table does not have field with name stream_id
    public function clean(array $streams = null){
        $conn = $this->conn();
        $partOfSql = $streams == null ? '' : $conn->parse('WHERE `stream_id` IN (?a)', $streams);
        try{
            if ($conn->beginTransaction()){
                $conn->query('DELETE FROM ?n ?p', $this->posts_table_name, $partOfSql);
                $conn->query('DELETE FROM ?n', $this->image_cache_table_name);
                $conn->commit();
            }
            $conn->rollback();
        }catch ( Exception $e){
            $conn->rollbackAndClose();
        }
    }


    public function deleteFeed($feedId){
        $conn = $this->conn();
        try{
            if ($conn->beginTransaction()){
                $partOfSql = $conn->parse('WHERE `feed_id` = ?s', $feedId);
                $conn->query('DELETE FROM ?n ?p', $this->posts_table_name, $partOfSql);
                $conn->query('DELETE FROM ?n ?p', $this->post_media_table_name, $partOfSql);
                $conn->query('DELETE FROM ?n ?p', $this->cache_table_name, $partOfSql);
                $conn->query('DELETE FROM ?n ?p', $this->streams_sources_table_name, $partOfSql);
                $conn->commit();
            }
            $conn->rollback();
        }catch ( Exception $e){
            $conn->rollbackAndClose();
        }
    }

    public function cleanFeed($feedId){
        $conn = $this->conn();
        try{
            if ($conn->beginTransaction()){
                $partOfSql = $conn->parse('WHERE `feed_id` = ?s', $feedId);
                $conn->query('DELETE FROM ?n ?p', $this->posts_table_name, $partOfSql);
                $conn->query('DELETE FROM ?n ?p', $this->post_media_table_name, $partOfSql);
                $this->setCacheInfo($feedId, [ 'last_update' => 0, 'status' => 0 ] );
                $conn->commit();
            }
            $conn->rollback();
        }catch (Exception $e){
            $conn->rollbackAndClose();
        }
    }

    public function cleanByFeedType($feedType){
        $conn = $this->conn();
        try{
            if ($conn->beginTransaction()){
                $feeds = $conn->getCol('SELECT DISTINCT `feed_id` FROM ?n WHERE `post_type` = ?s', $this->posts_table_name, $feedType);
                if (!empty($feeds)){
                    $conn->query("DELETE FROM ?n WHERE `feed_id` IN (?a)", $this->posts_table_name, $feeds);
                    $conn->query("DELETE FROM ?n WHERE `feed_id` IN (?a)", $this->post_media_table_name, $feeds);
                    $conn->commit();
                }
            }
            $conn->rollback();
        }catch (Exception $e){
            $conn->rollbackAndClose();
        }
    }

    /**
     * @param $only4insertPartOfSql
     * @param $imagePartOfSql
     * @param $mediaPartOfSql
     * @param $common
     *
     * @throws Exception
     */
    public function addOrUpdatePost($only4insertPartOfSql, $imagePartOfSql, $mediaPartOfSql, $common){
        $sql = "INSERT INTO ?n SET ?p, ?p ?p ?u ON DUPLICATE KEY UPDATE ?p ?p ?u";
        if (false == $this->conn()->query($sql, $this->posts_table_name, $only4insertPartOfSql, $imagePartOfSql, $mediaPartOfSql, $common, $imagePartOfSql, $mediaPartOfSql, $common)){
            throw new Exception($this->conn()->getError());
        }
    }

    /**
     * @param $posts
     *
     * @throws Exception
     */
    public function updateAdditionalInfo($posts){
        $conn = $this->conn();
        foreach ($posts as $post) {
            $sql = $conn->parse('UPDATE ?n SET `post_additional` = ?s WHERE `post_id` = ?s AND `feed_id` = ?s AND `post_type` = ?s',
                $this->posts_table_name, json_encode($post->additional), $post->id, $post->feed_id, $post->type);
            if (false == $conn->query($sql)){
                throw new Exception($conn->getError());
            }
        }
    }

    public function getCarousel($feed_id, $post_id){
        return $this->conn(true)->getAll('SELECT `feed_id`, `post_id`, `media_url`, `media_width`, `media_height`, `media_type` FROM ?n WHERE `feed_id`=?s AND `post_id`=?s',
            $this->post_media_table_name, $feed_id, $post_id);
    }

    /**
     * @param $feed_id
     * @param $post_id
     * @param $post_type
     * @param $mediaPartOfSql4carousel
     *
     * @throws Exception
     */
    public function addCarouselMedia($feed_id, $post_id, $post_type, $mediaPartOfSql4carousel){
        $common = [];
        $common['feed_id'] = $feed_id;
        $common['post_id'] = $post_id;
        $common['post_type'] = $post_type;

        $conn = $this->conn();
        //$sql  = $conn->parse('INSERT INTO ?n SET ?p ?u', $this->post_media_table_name, $mediaPartOfSql4carousel, $common);
        if (false == $conn->query('INSERT INTO ?n SET ?p ?u', $this->post_media_table_name, $mediaPartOfSql4carousel, $common)){
            throw new Exception($conn->getError());
        }
    }

    /**
     * @param $post_id
     * @param $comment
     *
     * @throws Exception
     */
    public function addComments($post_id, $comment){
        $time = time();
        $sql_insert = "INSERT INTO ?n SET `id` = ?s, `post_id` = ?s, `from` = ?s, `text` = ?s, `created_time` = ?s, `updated_time` = ?s ON DUPLICATE KEY UPDATE ?u";
        $result = $this->conn()->query($sql_insert, $this->comments_table_name, $comment->id, $post_id, is_string($comment->from) ? $comment->from : (is_object($comment->from) ?  $comment->from->name : 'Facebook user' ), $comment->text, $comment->created_time, $comment->created_time, [ 'updated_time' => $time, 'text' => $comment->text ] );
        if (false === $result){
            throw new Exception($this->conn()->getError());
        }
    }

    public function removeComments($post_id){
        $sql_delete = "DELETE FROM ?n WHERE `post_id` = ?s";
        $this->conn()->query($sql_delete, $this->comments_table_name, $post_id);
    }

    /**
     * @param $feed_id
     * @param $post_id
     *
     * @throws Exception
     */
    public function deleteCarousel4Post($feed_id, $post_id){
        $sql = "delete from ?n where feed_id = ?s and post_id = ?s";
        if (false == $this->conn()->query($sql, $this->post_media_table_name, $feed_id, $post_id)){
            throw new Exception($this->conn()->getError());
        }
    }

    /**
     * @param $feed_id
     *
     * @throws Exception
     */
    public function deleteCarousel4Feed($feed_id){
        $sql = "delete from ?n where feed_id = ?s";
        if (false == $this->conn()->query($sql, $this->post_media_table_name, $feed_id)){
            throw new Exception($this->conn()->getError());
        }
    }

    /**
     * @param string $feedId
     *
     * @return array|false
     */
    public function getIdPosts($feedId){
        return $this->conn(true)->getCol('SELECT `post_id` FROM ?n WHERE `feed_id`=?s', $this->posts_table_name, $feedId);
    }

    public function getPostsIf($fields, $condition, $order, $offset = null, $limit = null){
        $conn = $this->conn();
        $limitPart = ($offset !== null) ? $conn->parse("LIMIT ?i, ?i", $offset, $limit) : '';
        $sql = $conn->parse("SELECT ?p FROM ?n post INNER JOIN ?n stream ON stream.feed_id = post.feed_id INNER JOIN ?n cach ON post.feed_id = cach.feed_id WHERE ?p ORDER BY ?p ?p",
            $fields, $this->posts_table_name, $this->streams_sources_table_name, $this->cache_table_name, $condition, $order, $limitPart);
        return $conn->getAll($sql);
    }

    public function getPostsIf2($fields, $condition){
        return $this->conn()->getAll("SELECT ?p FROM ?n post INNER JOIN ?n stream ON stream.feed_id = post.feed_id INNER JOIN ?n cach ON post.feed_id = cach.feed_id WHERE ?p ORDER BY post.post_timestamp DESC, post.post_id",
            $fields, $this->posts_table_name, $this->streams_sources_table_name, $this->cache_table_name, $condition);
    }

    public function countPostsIf($condition){
        return $this->conn()->getOne('SELECT COUNT(*) FROM ?n post INNER JOIN ?n stream ON stream.feed_id = post.feed_id INNER JOIN ?n cach ON post.feed_id = cach.feed_id WHERE ?p',
            $this->posts_table_name, $this->streams_sources_table_name, $this->cache_table_name, $condition);
    }

    public function getLastUpdateHash($streamId){
        return $this->getHashIf($this->conn()->parse('stream.`stream_id` = ?s', $streamId));
    }

    public function getHashIf($condition){
        return $this->conn()->getOne("SELECT MAX(post.creation_index) FROM ?n post INNER JOIN ?n stream ON stream.feed_id = post.feed_id INNER JOIN ?n cach ON post.feed_id = cach.feed_id WHERE cach.boosted = 'nope' AND ?p",
            $this->posts_table_name, $this->streams_sources_table_name, $this->cache_table_name, $condition);
    }

    public function getLastUpdateTime($streamId){
        return $this->conn()->getOne('SELECT MAX(`last_update`) FROM ?n `cach` inner join ?n `st2src` on `st2src`.`feed_id` = `cach`.`feed_id` WHERE `stream_id` = ?s',  $this->cache_table_name, $this->streams_sources_table_name, $streamId);
    }

    public function getLastUpdateTimeAllStreams(){
        return $this->conn()->getIndCol('stream_id', 'SELECT MAX(`last_update`), `stream_id` FROM ?n `cach` inner join ?n `st2src` on `st2src`.`feed_id` = `cach`.`feed_id` GROUP BY `stream_id`',  $this->cache_table_name, $this->streams_sources_table_name);
    }

    public function deleteEmptyRecordsFromCacheInfo($streamId){
        //$this->conn()->query("DELETE FROM ?n where `stream_id`=?s", $this->cache_table_name, $streamId);
    }

    public function systemDisableSource($feedId, $enabled){
        $values = [ 'system_enabled' => $enabled ];
        if($enabled == 0){
            $values['send_email'] = 0;
        }
        return $this->saveSource($feedId, $values);
    }

    public function saveSource( $feedId, $values ) {
        return LADB::saveFeed($this->conn(), $this->cache_table_name, $feedId, $values);
    }

    /**
     * @param $feedId
     * @param $values
     *
     * @return FALSE|mysqli|resource
     * @deprecated
     * Use \flow\db\LADBManager::saveSource
     */
    public function setCacheInfo($feedId, $values){
        $sql = 'INSERT INTO ?n SET `feed_id`=?s, ?u ON DUPLICATE KEY UPDATE ?u';
        return $this->conn()->query( $sql, $this->cache_table_name, $feedId, $values, $values );
    }

    public function setOrders($feedId){
        $conn = $this->conn();
        $conn->query('SET @ROW = -1;');//test mysql_query("SELECT @ROW = -1");
        return $conn->query('UPDATE ?n SET `rand_order` = RAND(), `smart_order` = @ROW := @ROW+1 WHERE `feed_id`=?s ORDER BY post_timestamp DESC', $this->posts_table_name, $feedId);
    }

    public function removeOldRecords($c_count){
        $conn = $this->conn();
        $result = $conn->getAll('select count(*) as `count`, `feed_id` from ?n group by `feed_id` order by 1 desc', $this->posts_table_name);
        foreach ( $result as $row ) {
            $count = (int)$row['count'];
            if ($count > $c_count) {
                $feed = $row['feed_id'];
                $count = $count - $c_count;
                $sub_query = $conn->parse('select max(tmp.`post_timestamp`) from (select `post_timestamp` from ?n where `feed_id` = ?s order by `post_timestamp` limit 0, ?i) as tmp',$this->posts_table_name, $feed, $count);
                $sub_query2 = $conn->parse('select tmp2.post_id from ?n as tmp2 where tmp2.post_timestamp <= (?p)', $this->posts_table_name, $sub_query);
                $conn->query('delete from ?n where feed_id = ?s and post_id in (?p)', $this->post_media_table_name, $feed, $sub_query2);
                $conn->query('delete from ?n where feed_id = ?s and post_timestamp <= (?p)', $this->posts_table_name, $feed, $sub_query);
                continue;
            }
        }
    }

    /**
     * @param $status
     * @param $condition
     * @param null $creation_index
     *
     * @throws Exception
     */
    public function setPostStatus($status, $condition, $creation_index = null){
        $sql = "UPDATE ?n SET `post_status` = ?s";
        $sql .= ($creation_index != null) ? ", `creation_index` = " . $creation_index . " ?p" : " ?p";
        if (false == $this->conn()->query($sql, $this->posts_table_name, $status, $condition)){
            throw new Exception($this->conn()->getError());
        }
    }

    public abstract function getLoadCacheUrl($streamId = null, $force = false);

    /**
     * @return bool
     * @throws Exception
     */
    public function registrationCheck(){
        $activated = false;
        if (false !== ($registration_id = $this->getOption('registration_id'))){
            if ((false !== ($registration_date = $this->getOption('registration_date'))) &&
                (time() > $registration_date + 604800)){
                $ch = curl_init( 'https://flow.looks-awesome.com/wp-admin/admin-ajax.php?action=la_check&registration_id=' . $registration_id);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 5000);
                curl_setopt($ch, CURLOPT_POST, false);
                $result = curl_exec( $ch );
                curl_close( $ch );
                $result = json_decode($result);
                if (isset($result->registration_id) && $registration_id == $result->registration_id){
                    $settings = $this->getGeneralSettings();
                    $settings = $settings->original();
                    $current_subscription = $settings['news_subscription'];
                    $remote_subscription = $result->subscription == "1" ? LASettingsUtils::YEP : LASettingsUtils::NOPE;
                    if ($remote_subscription != $current_subscription){
                        $settings['news_subscription'] = $remote_subscription;
                        $this->setOption('options', $settings, true);
                    }
                    $this->setOption('registration_id', $result->registration_id);
                    $this->setOption('registration_date', time());
                    return true;
                }
                return false;
            }
            $activated = !empty($registration_id);
        }
        return $activated;
    }

    /**
     * @param $settings
     *
     * @return bool
     * @throws Exception
     */
    private function activate($settings){
        $activated = $this->registrationCheck();
        $option_name = 'flow_flow_options';
        if (!$activated
            && isset($settings[$option_name]['company_email']) && isset($settings[$option_name]['purchase_code'])
            && !empty($settings[$option_name]['company_email']) && !empty($settings[$option_name]['purchase_code'])){

            $name = isset($settings[$option_name]['company_name']) ? $settings[$option_name]['company_name'] : 'Unnamed';
            $subscription = 0;
            if (isset($settings[$option_name]['news_subscription']) && !empty($settings[$option_name]['news_subscription'])){
                $subscription = $settings[$option_name]['news_subscription'] == LASettingsUtils::YEP ? 1 : 0;
            }
            $post = [
                'action' => 'la_activation',
                'name' => $name,
                'email' => @$settings[$option_name]['company_email'],
                'purchase_code'   => @$settings[$option_name]['purchase_code'],
                'subscription' => $subscription,
                'plugin_name'	=>	$this->plugin_slug
            ];

            list($result, $error) = $this->sendRequest2lo($post);
            if (false !== $result){
                $result = json_decode($result);
                if (isset($result->registration_id)){
                    $this->setOption('registration_id', $result->registration_id);
                    $this->setOption('registration_date', time());
                    return true;
                }
                else if (isset($result->error)){
                    throw new Exception(is_string($result->error) ? $result->error : print_r($result->error, true));
                }
            }
            else {
                throw new Exception($error);
            }
        }
        if ($activated){
            $registration_id = $this->getOption('registration_id');
            $name = isset($settings[$option_name]['company_name']) ? $settings[$option_name]['company_name'] : 'Unnamed';
            $post = [
                'action' => 'la_activation',
                'registration_id' => $registration_id,
                'name' => $name,
                'email' => @$settings[$option_name]['company_email'],
                'purchase_code'   => @$settings[$option_name]['purchase_code'],
                'subscription' => 1,
                'plugin_name'	=>	$this->plugin_slug
            ];

            //subscribe
            if (isset($_POST['doSubcribe']) && $_POST['doSubcribe'] == 'true'){
                $result = $this->sendRequest2lo($post);
                $result = json_decode($result[0]);
                if (isset($result->registration_id)){
                    $this->setOption('registration_id', $result->registration_id);
                    $this->setOption('registration_date', time());
                    return true;
                }
                return false;
            }

            //remove registration
            if (!isset($settings[$option_name]['purchase_code']) || empty($settings[$option_name]['purchase_code'])){
                $post['purchase_code'] = '';
                $this->sendRequest2lo($post);
                $this->deleteOption('registration_id');
                $this->deleteOption('registration_date');
                return false;
            }
        }
        return true;
    }

    private function sendRequest2lo($data){
        $ch = curl_init( 'https://flow.looks-awesome.com/wp-admin/admin-ajax.php' );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 5000);
        $error = null;
        $result = curl_exec( $ch );
        if ($result === false){
            $error = curl_error($ch);
        }
        curl_close( $ch );
        return [ $result, $error ];
    }

    private function getStreamFromRequestWithoutErrors(){
        $stream = $_POST['stream'];

        // cleaning if error was saved in database stream model, can be removed in future, now it's needed for affected users
        if ( isset( $stream['error'] ) ) unset( $stream['error'] );

        // casting object
        return (object)$stream;
    }

    private function checkSecurity() {
        if (FF_USE_WP) {
            if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                die( json_encode( [ 'error' => 'not_allowed' ] ) );
            }
        }
    }

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getBoostSources(){
        $token = $this->getToken();
        if (!empty($token)){
            Request::jsonOpts(true);
            Request::timeout(120);
            $response = Request::post(FF_BOOST_SERVER . 'flow-flow/ff', [
                'Content-Type: application/x-www-form-urlencoded'
            ], http_build_query(['action' => 'get_sources', 'token' => $token]));

            if ($response->code == 200) {
                foreach ($response->body as &$source){
                    LADB::prepareSource($source);
                }
                return $response->body;
            }
            else if ($this->isExpiredToken($response)){
                return $this->getBoostSources();
            }
        }
        return [];
    }

    /**
     * @param array $data
     *
     * @return array|mixed|Response
     * @throws Exception
     */
    private function proxyRequest($data){
        $response = [];
        if (null != ($token = $this->getToken())){
            Request::jsonOpts(true);
            Request::timeout(120);
            $data['token'] = $token;
            $response = Request::post(FF_BOOST_SERVER . 'flow-flow/ff', [
                'Content-Type: application/x-www-form-urlencoded'
            ], http_build_query($data));
            if ($this->isExpiredToken($response)){
                $response = $this->proxyRequest($data);
            }
        }

        return $response;
    }

    /**
     * @param false $force
     *
     * @return array|false|mixed|string|null
     * @throws Exception
     */
    public function getToken( $force = false ) {
        $email = $this->getOption('boosts_email');
        if (!empty($email)){
            $domain = $_SERVER['HTTP_HOST'];

            $token = $this->getOption('boosts_token');
            if ($force || (false == $token)){
                Request::jsonOpts(true);
                Request::timeout(120);
                $response = Request::post(FF_BOOST_SERVER . 'token', [
                    'Content-Type: application/form-data'
                ], ['domain' => $domain, 'email' => $email]);
                if ($response->code == 200 && isset($response->body['token']) && is_string($response->body['token'])) {
                    $token = $response->body['token'];
                    $this->setOption('boosts_token', $token);
                    $this->conn()->commit();
                }
                else {
                    return null;
                }
            }
            return $token;
        }
        return null;
    }

    /**
     * @param $response
     *
     * @return bool
     * @throws Exception
     */
    private function isExpiredToken( $response ) {
        if ($response->code == 400 &&
            ((isset($response->body->error) && $response->body->error == 'Provided token is expired.') ||
             (isset($response->body['error']) && $response->body['error'] == 'Provided token is expired.'))
        ){
            $this->deleteOption('boosts_token');
            $this->deleteOption('boosts_subscription');
            $this->conn()->commit();
            return true;
        }
        return false;
    }

    private function deleteBoostedFeeds() {
        $conn = $this->conn();
        $feeds = $conn->getCol('SELECT `feed_id` FROM ?n WHERE `boosted` = ?s', $this->cache_table_name, LASettingsUtils::YEP);
        $conn->query('DELETE FROM ?n WHERE `feed_id` IN (?a)', $this->streams_sources_table_name, $feeds);
        $values = ['system_enabled' => 0, 'boosted' => LASettingsUtils::NOPE];
        $conn->query('UPDATE ?n SET ?u WHERE `boosted` = ?s', $this->cache_table_name, $values, LASettingsUtils::YEP);
    }
}
