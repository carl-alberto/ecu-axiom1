<?php namespace la\core;

use Exception;
use flow\social\cache\LAFacebookCacheManager;
use flow\social\FFFeed;
use flow\social\FFRemoteFeed;
use flow\social\LAFeedWithComments;
use la\core\cache\LACacheAdapter;
use la\core\cache\LAImageSizeCacheManager;
use la\core\db\LADB;
use la\core\settings\LAGeneralSettings;
use la\core\settings\LASettingsUtils;
use la\core\settings\LAStreamSettings;
use ReflectionClass;
use ReflectionException;

if ( ! defined('FF_BY_DATE_ORDER'))   define('FF_BY_DATE_ORDER', 'compareByTime');
if ( ! defined('FF_RANDOM_ORDER'))    define('FF_RANDOM_ORDER',  'randomCompare');
if ( ! defined('FF_SMART_ORDER'))     define('FF_SMART_ORDER',   'smartCompare');

abstract class LABase {
	protected static $instance = [];
	
	/**
	 * @param $context
	 *
	 * @return LABase|null
	 */
	public static function get_instance($context = null) {
		$slug = is_null($context) ? 'flow-flow' : LAUtils::slug($context);
		if (!array_key_exists($slug, self::$instance)) {
			$slug_down = is_null($context) ? 'flow_flow' : LAUtils::slug_down($context);
			$class = get_called_class();
			self::$instance[$slug] = new $class($context, $slug, $slug_down);
		}
		return self::$instance[$slug];
	}
	
	public static function get_instance_by_slug($slug) {
		return (array_key_exists($slug, self::$instance)) ? self::$instance[$slug] : null;
	}
	
// 	public static function registry($slug, $instance){
// 		if (!array_key_exists($slug, self::$instance)) {
// 			self::$instance[$slug] = $instance;
// 		}
// 	}

	/**
	 * @deprecated 
	 * @var LAGeneralSettings
	 */
	protected $generalSettings;
	
	/** @var array */
	protected $context;
	protected $slug;
	protected $slug_down;

    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since     1.0.0
     *
     * @param array $context
     * @param $slug
     * @param $slug_down
     */
	protected function __construct($context, $slug, $slug_down) {
		$this->context = $context;
		$this->slug = $slug;
		$this->slug_down = $slug_down;
		
		/**
		 * Default filter for result before send response.
		 * Use wp filter engine because need to customize result from addons. 
		 */
		add_filter('ff_build_public_response', [ $this, 'buildResponse' ], 1, 8);
	}
	
	public final function register_shortcodes()
	{
		add_shortcode($this->getShortcodePrefix(), [ $this, 'renderShortCode' ] );
	}
	
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public final function load_plugin_textdomain() {
		$domain = $this->slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		$path = LAUtils::root($this->context) . 'languages/';
		load_textdomain( $domain,  $path . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, $path );
	}
	
	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 *
	 * @noinspection PhpUnused
     */
	public final function activate_new_site( $blog_id ) {
		if ( 1 !== did_action( 'wpmu_new_blog' ) )  return;
		switch_to_blog( $blog_id );
		restore_current_blog();
	}
	
	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public final function enqueue_styles() {
		$this->enqueueStyles();
	}
	
	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public final function enqueue_scripts() {
	    // Customization 16.08.18, JS opts added in public.php instead
//         $this->enqueueScripts();
        // make sure jQuery is always on page
        wp_enqueue_script('jquery');
	}

    /**
     * @throws Exception
     */
    public final function processAjaxRequest() {
		if (isset($_REQUEST['stream-id']) && $this->prepareProcess()) {
            $dbm = LAUtils::dbm($this->context);
			$boosted = (bool)(isset($_REQUEST['boosted']) && (int)$_REQUEST['boosted']);
			$dbm->dataInit(true, false, $boosted);
			$stream = $dbm->getStream($_REQUEST['stream-id']);
			if (isset($stream)) {
				$disableCache = isset($_REQUEST['disable-cache']) ? (bool)$_REQUEST['disable-cache'] : false;
                header('Content-Type: application/json');
				echo $this->process( [ $stream ], $disableCache);
			}
		}
		die();
	}

    /**
     * @throws Exception
     */
    public final function moderation_apply( ){
		if (isset($_REQUEST['stream']) && $this->prepareProcess()) {
            $dbm = LAUtils::dbm($this->context);
			$dbm->dataInit();
			$stream = $dbm->getStream($_REQUEST['stream']);
			if (isset($stream)) {
                $cache = new LACacheAdapter($this->context, false);
                $cache->setStream(new LAStreamSettings($stream), true);
                $cache->moderate();
			}
		}
	}

    /**
     * @param bool $only_enable
     * @param bool $remote
     * @throws Exception
     */
    public final function processAjaxRequestBackground($only_enable = true, $remote = true) {
		if ($this->prepareProcess()) {
			$dbm = LAUtils::dbm($this->context);
			$dbm->dataInit($only_enable, false, $remote);
			
			if (isset($_REQUEST['feed_id'])){
				$sources = $dbm->sources();
				if (isset($sources[$_REQUEST['feed_id']])){
                    $this->process4feeds( [ $sources[$_REQUEST['feed_id']] ], false, true);
				}
			}
			if (isset($_REQUEST['stream_id'])){
				$stream = $dbm->getStream($_REQUEST['stream_id']);
				if (isset($stream)) {
					$this->process4feeds( [ $stream ], false, true);
				}
			}
		}
	}


    /**
     * @return array|false|string
     * @throws Exception
     */
    public final function processRequest(){
		if (isset($_REQUEST['stream-id']) && $this->prepareProcess()) {
            $dbm = LAUtils::dbm($this->context);
            $dbm->dataInit(true);
			$stream = $dbm->getStream($_REQUEST['stream-id']);
			if (isset($stream)) {
				return $this->process( [ $stream ], isset($_REQUEST['disable-cache']));
			}
		}
		return '';
	}
	
	public final function refreshCache($streamId = null, $force = false, $withDisabled = false) {
		if ($this->prepareProcess()) {
            $dbm = LAUtils::dbm($this->context);
            $conn = $dbm->conn();
            $enabled = $withDisabled ? $conn->parse('`cach`.system_enabled = 0 AND `cach`.boosted != "yep"') : $conn->parse('`cach`.enabled = 1 AND `cach`.system_enabled = 1 AND `cach`.boosted != "yep"');
			if (empty($streamId)){
				$sql = $conn->parse('SELECT `cach`.`feed_id` FROM ?n `cach` WHERE ?p AND (`cach`.last_update + `cach`.cache_lifetime * 60) < UNIX_TIMESTAMP() ORDER BY `cach`.last_update', $dbm->cache_table_name, $enabled);
			}
			else{
				$sql = $conn->parse('SELECT `cach`.`feed_id` FROM ?n `cach` INNER JOIN ?n `ss` ON `ss`.feed_id = `cach`.feed_id WHERE ?p AND `ss`.stream_id = ?s AND (`cach`.last_update + `cach`.cache_lifetime * 60) < UNIX_TIMESTAMP() ORDER BY `cach`.last_update',
					$dbm->cache_table_name, $dbm->streams_sources_table_name, $enabled, $streamId);
			}
			try {
				if (false !== ($feeds = $conn->getCol($sql))){
                    $useIpv4 = $dbm->getGeneralSettings()->useIPv4();
                    $use = $dbm->getGeneralSettings()->useCurlFollowLocation();
					if (sizeof($feeds) < 4){
						for ( $i = 0; $i < sizeof($feeds); $i ++ ) {
							$feed_id = $feeds[$i];
							$_REQUEST['feed_id'] = $feed_id;
							$this->processAjaxRequestBackground(!$withDisabled, false);
						}
					}
					else {
						for ( $i = 0; $i < 8; $i ++ ) {
							if (isset($feeds[$i])){
								$feed_id = $feeds[$i];
								if (FF_USE_DIRECT_WP_CRON){
									$_REQUEST['feed_id'] = $feed_id;
									$this->processAjaxRequestBackground(!$withDisabled, false);
								}
								else {
									//$_COOKIE['XDEBUG_SESSION'] = 'PHPSTORM';
									$url = $dbm->getLoadCacheUrl( $feed_id, $force );
									LASettingsUtils::get( $url, 1, false, false, $use, $useIpv4);
								}
							}
						}
					}
			}
			}
			catch( Exception $e){
				error_log($e->getMessage());
				error_log($e->getTraceAsString());
			}
		}
	}
	
	public final function refreshCache4Disabled() {
		$this->refreshCache(null, false, true);
	}

	public final function emailNotification () {
		$dbm = LAUtils::dbm($this->context);
		$settings = $dbm->getGeneralSettings();
		if ($settings->enabledEmailNotification()){
			$dbm->email_notification();
		}
	}

    public final function checkFacebookToken() {
        $dbm = LAUtils::dbm($this->context);
        if ($dbm->getOption('boosts_email') != false){
            /** @var LAFacebookCacheManager $facebookCache */
            $facebookCache = $this->context['facebook_cache'];
            $facebookCache->getAccessToken();
        }
    }

    /**
     * @param $attr
     *
     * @return false|string|string[]
     * @throws Exception
     */
    public function renderShortCode ($attr) {
		if (isset($attr['id'])){
			if ($this->prepareProcess()) {
                $dbm = LAUtils::dbm($this->context);
				$dbm->dataInit(false, false, false);
				$stream = (object)$dbm->getStream($attr['id']);
				if (isset($stream)) {
					$stream->preview = (isset($attr['preview']) && $attr['preview']);
					$stream->gallery = $stream->preview ? LASettingsUtils::NOPE : ( isset($stream->gallery) ? $stream->gallery : LASettingsUtils::NOPE );
					$output = $this->renderStream($stream, $this->getPublicContext($stream, $this->context));

					/* workaround for extra P tags issue and possibly &&, set to true */
					if (LASettingsUtils::notYepNope2ClassicStyleSafe(LAGeneralSettings::get()->original(), 'general-render-alt')){
						/* added 8.06.20 */
						remove_filter('the_content', 'wptexturize');
						remove_filter('the_content', 'wpautop');
						/* */
						return $output;
					}
					else {
						echo $output;
					}
				}
			} else {
				echo '<p>Flow-Flow message: Stream with specified ID not found or no feeds were added to stream</p>';
			}
		}
		return '';
	}
	
	/**
	 * @param $result
	 * @param $all
	 * @param $context
	 * @param $errors
	 * @param $oldHash
	 * @param $page
	 * @param $status
	 * @param LAStreamSettings $stream
	 *
	 * @return array
     * @noinspection PhpUnusedParameterInspection
     */
	public function buildResponse ($result, $all, $context, $errors, $oldHash, $page, $status, $stream) {
		$streamId = (int) $stream->getId();
		$countOfPages = isset($_REQUEST['countOfPages']) ? $_REQUEST['countOfPages'] : 0;
		$result = [
            'id'   => $streamId, 'items' => $all, 'errors' => $errors,
            'hash' => $oldHash, 'page' => $page, 'countOfPages' => $countOfPages, 'status' => $status
        ];
		return $result;
	}

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function loadCommentsAndCarousel(){
		$result = [];

        $post_id = $_REQUEST['post_id'];
        $feed_id = $_REQUEST['feed_id4post'];

        if ($this->prepareProcess()) {
            $dbm = LAUtils::dbm($this->context);
            $dbm->dataInit(true);

            $result['comments'] = $this->process4comments($post_id, $feed_id);
            $result['carousel'] = $dbm->getCarousel($feed_id, $post_id);

            wp_send_json($result);
        }
	}

	protected function enqueueStyles() {}
	protected function enqueueScripts() {}
	protected abstract function getShortcodePrefix();
	protected abstract function getNameJSOptions();

    protected function getPublicContext($stream, $context){
        $context['moderation'] = false;
        if (isset($stream->feeds) && !empty($stream->feeds)){
            foreach ( $stream->feeds as $source ) {
                if (LASettingsUtils::YepNope2ClassicStyleSafe($source, 'mod', false)){
                    $context['moderation'] = true;
                }
            }
        }

        $cache = new LACacheAdapter($context);
        $cache->setStream(new LAStreamSettings($stream), $context['moderation']);
        $context['stream'] = $stream;
        $context['hashOfStream'] = $cache->transientHash($stream->id);
        $context['seo'] = false;////$this->generalSettings->isSEOMode();
        $context['can_moderate'] = FF_USE_WP ? $this->generalSettings->canModerate() : ff_user_can_moderate();
        return $context;
    }

	protected function prepareProcess() {
		if (isset($_REQUEST['stream-id'])) $_REQUEST['stream-id'] = @filter_var( trim( $_REQUEST['stream-id'] ), FILTER_SANITIZE_NUMBER_INT);
		if (isset($_REQUEST['feed_id'])) $_REQUEST['feed_id'] = @filter_var( trim( $_REQUEST['feed_id'] ), FILTER_SANITIZE_STRING );
		if (isset($_REQUEST['action'])) $_REQUEST['action'] = @filter_var( trim( $_REQUEST['action'] ), FILTER_SANITIZE_STRING );
		if (isset($_REQUEST['page'])) $_REQUEST['page'] = filter_var( trim( $_REQUEST['page'] ), FILTER_SANITIZE_NUMBER_INT);
		if (isset($_REQUEST['countOfPages'])) $_REQUEST['countOfPages'] = filter_var( trim( $_REQUEST['countOfPages'] ), FILTER_SANITIZE_NUMBER_INT);
		if (isset($_REQUEST['hash']) && !empty($_REQUEST['hash'])){
			$hash = filter_var( $_REQUEST['hash'], FILTER_VALIDATE_REGEXP, [ "options" => [ 'regexp' => '/^\d{10}[.]\w{96}$/' ] ] );
			if (false === $hash){
				status_header(400);
				exit;
			}
		}
		if (isset($_REQUEST['disable-cache']) && !empty($_REQUEST['disable-cache'])){
			$_REQUEST['disable-cache'] = filter_var( trim( $_REQUEST['disable-cache'] ), FILTER_SANITIZE_NUMBER_INT);
		}
		if (isset($_REQUEST['preview']) && !empty($_REQUEST['preview'])){
			$_REQUEST['preview'] = filter_var( trim( $_REQUEST['preview'] ), FILTER_SANITIZE_NUMBER_INT);
		}

        $dbm = LAUtils::dbm($this->context);
		if ($dbm->countFeeds() > 0) {
			$this->generalSettings = $dbm->getGeneralSettings();
			return true;
		}
		return false;
	}

	protected function renderStream($stream, $context){
		$settings = new LAStreamSettings($stream);
		if ($settings->isPossibleToShow()){
			if ( ! in_array( 'curl', get_loaded_extensions() ) ) {
				echo "<p style='background: indianred;padding: 15px;color: white;'>Flow-Flow admin info: Your server doesn't have cURL module installed. Please ask your hosting to check this.</p>";
				return '';
			}
			
			if (!isset($stream->layout) || empty($stream->layout)) {
				echo "<p style='background: indianred;padding: 15px;color: white;'>Flow-Flow admin info: Please choose stream layout on options page.</p>";
				return '';
			}
			
			ob_start();
			$css_version = isset($stream->last_changes) ? $stream->last_changes : '1.0';
			$url = content_url() . '/resources/' . LAUtils::slug($context) . '/css/stream-id' . $stream->id . '.css';
			if (!is_main_site()){
				$url = content_url() . '/resources/' . LAUtils::slug($context) . '/css/stream-id' . $stream->id . '-'. get_current_blog_id() . '.css';
			}
			echo "<link rel='stylesheet' id='ff-dynamic-css" . $stream->id . "' type='text/css' href='{$url}?ver={$css_version}'/>";

			/** @noinspection PhpIncludeInspection */
			include(LAUtils::root($context)  . 'views/public.php');
			$output = ob_get_clean();
			$output = str_replace("\r\n", '', $output);

			return $output;
		}
		else
			return '';
	}
	
	protected function process($streams, $disableCache = false, $background = false) {
		foreach ($streams as $stream) {
			try {
				$moderation = false;
				foreach ( LAUtils::dbm($this->context)->sources() as $source ) {
					$moderation = LASettingsUtils::YepNope2ClassicStyleSafe($source, 'mod', false);
					if ($moderation){
						break;
					}
				}

                $settings = new LAStreamSettings($stream);
				$cache = new LACacheAdapter($this->context);
				$cache->setStream($settings, $moderation);
				$instances = $this->createFeedInstances($settings->getAllFeeds());
				$result = $cache->posts($instances, $disableCache);
				unset($instances);
				if ($background) return $result;
				$errors = $cache->errors();
				$hash = $cache->hash();
				return $this->prepareResult($result, $errors, $hash, $settings);
			} catch ( Exception $e) {
				error_log($e->getMessage());
				error_log($e->getTraceAsString());
			}
		}
		return '';
	}

    protected function initContextBeforeCreateFeedInstances(){
        $this->context['image_size_cache'] = new LAImageSizeCacheManager($this->context);
    }

	private function process4feeds($feeds, $disableCache = false, $background = false) {
		try {
			$instances = $this->createFeedInstances($feeds);
			$cache = new LACacheAdapter($this->context, true);
			$result = $cache->posts($instances, $disableCache);
			unset($instances);
			if ($background) return $result;
			$errors = $cache->errors();
			$hash = $cache->hash();
			return $this->prepareResult($result, $errors, $hash);
		} catch ( Exception $e) {
			error_log($e->getMessage());
			error_log($e->getTraceAsString());
		}
		return '';
	}

    /**
     * Rework code, delete the reference to the database and the logic of expiration life time
     *
     * @param $post_id
     * @param $feed_id
     *
     * @return array
     * @throws ReflectionException
     * @throws Exception
     */
	private function process4comments($post_id, $feed_id){
        $dbm = LAUtils::dbm($this->context);
        $conn = $dbm->conn();
		$time = time();
		$comments = $conn->getAll('SELECT * FROM ?n WHERE `post_id` = ?s', $dbm->comments_table_name, $post_id);
		$expiration = $time - 3600; // 1 hour
		
		// if no comments or comments are outdated
		if(count($comments) === 0 || ($comments[0]["updated_time"] < $expiration) ){
			$sources = $dbm->sources();
			$this->initContextBeforeCreateFeedInstances();
			/** @var LAFeedWithComments $instance */
			$instance = $this->createFeedInstance($sources[$feed_id]);
			if ($instance instanceof LAFeedWithComments){
				try {
					$comments = $instance->getComments($post_id);
					
					// Save comments to DB
					if (sizeof($comments) > 0 && $conn->beginTransaction()){
                        $dbm->removeComments($post_id);
						foreach ( $comments as $comment ) {
							$comment->updated_time = $time;
							if (is_object($comment->from)) $comment->from = json_encode($comment->from);
                            $dbm->addComments($post_id, $comment);
						}
                        $conn->commit();
					}
				} catch ( Exception $e) {
                    $conn->rollbackAndClose();
					error_log($e->getMessage());
					error_log($e);
				}
			}
		}
		return $comments;
	}

    /**
     * @param $feeds
     *
     * @return array
     * @throws ReflectionException
     */
    private function createFeedInstances($feeds) {
		$this->initContextBeforeCreateFeedInstances();
		$result = [];
		if (is_array($feeds)) {
			foreach ($feeds as $feed) {
				$feed = (object)$feed;
				$result[$feed->id] = $this->createFeedInstance($feed);
			}
		}
		return $result;
	}

	/**
	 * @param $feed
	 *
	 * @return object
	 * @throws ReflectionException
	 */
	private function createFeedInstance($feed) {
		$feed = (object)$feed;
		$wpt = 'type';
		if ($feed->type == 'linkedin') {
			$feed->type = 'linkedIn';
		}
		if (FF_USE_WP && $feed->type == 'wordpress'){
			$wpt = 'wordpress-type';
		}
		
		$clazz = new ReflectionClass( 'flow\\social\\FF' . ucfirst($feed->$wpt) );//don`t change this line
        /** @var FFFeed $instance */
		$instance = $clazz->newInstance();
		$feed = $this->prepareFeed($feed, $this->generalSettings);

		if (LASettingsUtils::YepNope2ClassicStyle($feed->boosted, false)){
			$instance = new FFRemoteFeed($instance);
		}

		$instance->init($this->context, $feed);
		return $instance;
	}

	/**
	 * @param $feed
	 * @param $options LAGeneralSettings
	 *
	 * @return mixed
	 */
	protected function prepareFeed($feed, $options){
		$feed->{'use-excerpt'} = LASettingsUtils::YepNope2ClassicStyleSafe($feed, 'use-excerpt');
		$feed->{'include-post-title'} = LASettingsUtils::YepNope2ClassicStyleSafe($feed, 'include-post-title');
		$feed->{'only-text'} = LASettingsUtils::YepNope2ClassicStyleSafe($feed, 'only-text');
		$feed->{'rich-text'} = LASettingsUtils::YepNope2ClassicStyleSafe($feed, 'rich-text');
		$feed->{'hide-caption'} = LASettingsUtils::YepNope2ClassicStyleSafe($feed, 'hide-caption');
		$feed->{'playlist-order'} = LASettingsUtils::YepNope2ClassicStyleSafe($feed, 'playlist-order');
		$feed->replies = LASettingsUtils::notYepNope2ClassicStyleSafe($feed, 'replies');
		$feed->retweets = LASettingsUtils::YepNope2ClassicStyleSafe($feed, 'retweets');
		$feed->{'use-geo'} = LASettingsUtils::YepNope2ClassicStyleSafe($feed, 'use-geo');
		//$feed->boosted = FFSettingsUtils::YepNope2ClassicStyleSafe($feed, 'boosted');

		$original = $options->original();
		$feed->linkedin_access_token    = @$original['linkedin_access_token'];
		$feed->dribbble_access_token    = @$original['dribbble_access_token'];
		$feed->foursquare_access_token  = @$original['foursquare_access_token'];
		$feed->foursquare_client_id     = @$original['foursquare_client_id'];
		$feed->foursquare_client_secret = @$original['foursquare_client_secret'];
		$feed->google_api_key           = @$original['google_api_key'];
		$feed->instagram_access_token   = @$original['instagram_access_token'];
		$feed->instagram_login          = @$original['instagram_login'];
		$feed->instagram_password       = @$original['instagram_pass'];
		$feed->soundcloud_api_key       = @$original['soundcloud_api_key'];
		$feed->twitter_access_settings = [
			'oauth_access_token' => @$original['oauth_access_token'],
			'oauth_access_token_secret' => @$original['oauth_access_token_secret'],
			'consumer_key' => @$original['consumer_key'],
			'consumer_secret' => @$original['consumer_secret']
        ];

		$feed->use_curl_follow_location = $options->useCurlFollowLocation();
		$feed->use_ipv4 = $options->useIPv4();
		return $feed;
	}

    /**
     * @param array $all
     * @param $errors
     * @param $hash
     * @param LAStreamSettings|null $stream
     *
     * @return false|string
     * @throws Exception
     * @noinspection PhpUnusedParameterInspection
     */
    private function prepareResult(array $all, $errors, $hash, $stream = null) {
		$page = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 0;
		$oldHash = isset($_REQUEST['hash']) ? $_REQUEST['hash'] : $hash;
		if (isset($_REQUEST['recent']) && $hash != null){
			$oldHash = $hash;
		}
		list($status, $errors) = $this->status($stream);
		$result = FF_USE_WP ? apply_filters('ff_build_public_response', [], $all, $this->context, $errors, $oldHash, $page, $status, $stream) :
		$this->buildResponse( [], $all, $this->context, $errors, $oldHash, $page, $status, $stream);
		if (($result === false) && (JSON_ERROR_UTF8 === json_last_error())){
			foreach ( $all as $item ) {
				json_encode($item);
				if (JSON_ERROR_UTF8 === json_last_error()){
					$item->text = mb_convert_encoding($item->text, "UTF-8", "auto");
				}
			}
			$result = FF_USE_WP ? apply_filters('ff_build_public_response', $result, $all, $this->context, $errors, $oldHash, $page, $status, $stream) :
			$this->buildResponse($result, $all, $this->context, $errors, $oldHash, $page, $status, $stream);
		}

		$result['server_time'] = time();

		$json = json_encode($result);
		if ($json === false){
			$errors = [];
			switch (json_last_error()) {
				case JSON_ERROR_NONE:
					echo ' - No errors';
					break;
				case JSON_ERROR_DEPTH:
					$errors[] = 'Json encoding error: Maximum stack depth exceeded';
					break;
				case JSON_ERROR_STATE_MISMATCH:
					$errors[] = 'Json encoding error: Underflow or the modes mismatch';
					break;
				case JSON_ERROR_CTRL_CHAR:
					$errors[] = 'Json encoding error: Unexpected control character found';
					break;
				case JSON_ERROR_SYNTAX:
					$errors[] = 'Json encoding error: Syntax error, malformed JSON';
					break;
				case JSON_ERROR_UTF8:
					for ( $i = 0; sizeof( $result['items'] ) > $i; $i++ ) {
						if (function_exists('mb_convert_encoding'))
							$result['items'][$i]->text = mb_convert_encoding($result['items'][$i]->text, "UTF-8", "auto");
					}
					$json = json_encode($result);
					if ($json === false){
						$errors[] = 'Json encoding error:  Malformed UTF-8 characters, possibly incorrectly encoded';
					}
					else {
						return $json;
					}
					break;
				default:
					$errors[] = 'Json encoding error';
					break;
			}
			$result = FF_USE_WP ? apply_filters('ff_build_public_response', [], [], $this->context, $errors, $oldHash, $page, 'errors', $stream) :
			$this->buildResponse($result, $all, $this->context, $errors, $oldHash, $page, 'errors', $stream);
			$json = json_encode($result);
		}
		return $json;
	}

    /**
     * @param LAStreamSettings $stream
     *
     * @return array
     * @throws Exception
     */
    private function status($stream) {
        $dbm = LAUtils::dbm($this->context);
		$status_info = LADB::getStatusInfo($dbm->conn(), $dbm->cache_table_name, $dbm->streams_sources_table_name, (int)$stream->getId(), false);
		if ($status_info['status'] == '0'){
			return [ 'errors', isset($status_info['error']) ? $status_info['error'] : '' ];
		}
		if ($status_info['status'] == '1'){
			$feed_count = sizeof($stream->getAllFeeds());
			$status = ($feed_count == (int)$status_info['feeds_count']) ? 'get' : 'building';
			return [ $status, [] ];
		}
		throw new Exception('Was received the unknown status');
	}
}