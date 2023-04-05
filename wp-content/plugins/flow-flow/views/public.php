<?php use la\core\LAUtils;

if ( ! defined( 'WPINC' ) ) die;
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @var array $context
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
$moderation = $context['moderation'] && $context['can_moderate'];
$stream = $context['stream'];
if (FF_USE_WP)
	$admin = $moderation ? $moderation : function_exists('current_user_can') && current_user_can('manage_options');
else
	$admin = ff_user_can_moderate();
$id = $stream->id;
$domain = $context['domain'];
$public_url = ($context['boosted'] && isset($context['public_url']) && !empty($context['public_url'])) ? $context['public_url'] : $context['ajax_url'];
$hash = $context['hashOfStream'];
$seo = $context['seo'];
$disableCache = isset($_REQUEST['disable-cache']);
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '0';

if ( isset ( $context['ads_distrubution'] ) ) {
	$ads = $context['ads_distrubution'];
}

$version = LAUtils::version($this->context);
$opts = LAUtils::dbm($this->context)->getOption('options', true);
$plugin_directory = $this->context['plugin_url'] . $this->context['plugin_dir_name'];
$js_opts = [
    'streams' => new stdClass(),
    'open_in_new' => isset($opts['general-settings-open-links-in-new-window']) ? $opts['general-settings-open-links-in-new-window'] : 'yep',
    'filter_all' => __('All', 'flow-flow'),
    'filter_search' => __('Search', 'flow-flow'),
    'expand_text' => __('Expand', 'flow-flow'),
    'collapse_text' => __('Collapse', 'flow-flow'),
    'posted_on' => __('Posted on', 'flow-flow'),
    'followers' => __('Followers', 'flow-flow'),
    'following' => __('Following', 'flow-flow'),
    'posts' => __('Posts', 'flow-flow'),
    'show_more' => __('Show more', 'flow-flow'),
    'date_style' => isset($opts['general-settings-date-format']) ? $opts['general-settings-date-format'] : 'agoStyleDate',
    'dates' => [
        'Yesterday' => __('Yesterday', 'flow-flow'),
        's' => __('s', 'flow-flow'),
        'm' => __('m', 'flow-flow'),
        'h' => __('h', 'flow-flow'),
        'ago' => __('ago', 'flow-flow'),
        'months' => [
            __('Jan', 'flow-flow'), __('Feb', 'flow-flow'), __('March', 'flow-flow'),
            __('April', 'flow-flow'), __('May', 'flow-flow'), __('June', 'flow-flow'),
            __('July', 'flow-flow'), __('Aug', 'flow-flow'), __('Sept', 'flow-flow'),
            __('Oct', 'flow-flow'), __('Nov', 'flow-flow'), __('Dec', 'flow-flow')
        ],
    ],
    'lightbox_navigate' => __('Navigate with arrow keys', 'flow-flow'),
    'view_on' => __('View on', 'flow-flow'),
    'view_on_site' => __('View on site', 'flow-flow'),
    'view_all' => __('View all', 'flow-flow'),
    'comments' => __('comments', 'flow-flow'),
    'scroll' => __('Scroll for more', 'flow-flow'),
    'no_comments' => __('No comments yet.', 'flow-flow'),
    'check_comments' => __('Check all comments', 'flow-flow'),
    'be_first' => __('Be the first!', 'flow-flow'),
    'loading' => __('Loading', 'flow-flow'),
    'server_time' => current_time( 'timestamp', (isset($context['boosted']) && $context['boosted']) ),
    'forceHTTPS' => isset($opts['general-settings-https']) ? $opts['general-settings-https'] : 'nope',
    'isAdmin' => function_exists('current_user_can') && current_user_can( 'manage_options' ),
    'ajaxurl' => $public_url,
    'isLog' => isset($_REQUEST['fflog']) && $_REQUEST['fflog'] == 1,
    'plugin_base' => $plugin_directory,
    'plugin_ver' => $this->context['version'],
    'domain' => $domain
];
$js_opts['token'] = ($admin && $moderation) ? $context['token'] : '';
?>
<!-- Flow-Flow — Social stream plugin for WordPress -->
<div class="ff-stream" data-plugin="flow_flow" id="ff-stream-<?php echo $id;?>"><span class="ff-loader"><span class="ff-square" ></span><span class="ff-square"></span><span class="ff-square ff-last"></span><span class="ff-square ff-clear"></span><span class="ff-square"></span><span class="ff-square ff-last"></span><span class="ff-square ff-clear"></span><span class="ff-square"></span><span class="ff-square ff-last"></span></span></div>
<svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1"><defs><symbol id="ff-icon-heart" viewBox="0 0 48 48"><path d="M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path></symbol></defs></svg>
<script type="text/javascript" class="ff-stream-inline-js" id="ff-stream-inline-js-<?php echo $id;?>">

    (function () {
        var timer, abortTimer;

        timer = setInterval( function() {
            if ( window.jQuery ) {
                clearInterval( timer );
                afterContentArrived( window.jQuery );
            }
        }, 67);

        abortTimer = setTimeout( function () {

            if ( !window.jQuery ) {
                clearInterval( timer );
                console.log('FLOW-FLOW DEBUG MESSAGE: No jQuery on page, please make sure it is loaded because jQuery is plugin requirement');
            }
        }, 20000);

        function afterContentArrived ( $ ) {

            "use strict";

            var hash = '<?php echo $hash; ?>';

            var opts = window.FlowFlowOpts || <?php echo json_encode( $js_opts ); ?>;

            var isLS = isLocalStorageNameSupported();

            var FF_resource = window.FF_resource ||
                {
                    scriptDeferred: $.Deferred(),
                    styleDeferred:  $.Deferred(),
                    scriptLoading: false,
                    styleLoading: false
                };

            if ( !window.FF_resource ) window.FF_resource = FF_resource;
            if ( !window.FlowFlowOpts ) window.FlowFlowOpts = opts;

	        <?php if( $moderation && $context['boosted'] ):?>
	        if ( ! FF_resource.colorpicker ) {
		        script = document.createElement('script');
		        script.src = "<?php echo $plugin_directory;?>/js/spectrum.js?ver=<?php echo $version; ?>";
		        script.async = true;
		        document.body.appendChild(script);

		        style = document.createElement('link');
		        style.type = "text/css";
		        style.id = "ff_style";
		        style.rel = "stylesheet";
		        style.href = "<?php echo $plugin_directory;?>/css/spectrum.css?ver=<?php echo $version; ?>";
		        style.media = "screen";
		        document.getElementsByTagName("head")[0].appendChild(style);

		        FF_resource.colorpicker = true;
	        }
	        <?php endif;?>

            var data = {
                'shop': '<?php echo $domain;?>',
                'action': 'fetch_posts',
                'stream-id': '<?php echo $id;?>',
                'disable-cache': '<?php echo $disableCache;?>',
                'hash': hash,
                'page': '<?php echo $page;?>',
                'preview': '<?php echo $stream->preview ? 1 : 0;?>',
                'token':  '<?php echo $js_opts['token']; ?>',
                'boosted': '<?php echo $context['boosted'] ? 1 : 0 ?>'
            };

            var isMobile = /android|blackBerry|iphone|ipad|ipod|opera mini|iemobile/i.test( navigator.userAgent );

            var streamOpts = <?php echo json_encode( $stream ); ?>;
            var ads = <?php echo isset( $ads ) ? json_encode( $ads ) : 'false'; ?>;
	        streamOpts.shop = data.shop;
            streamOpts.plugin = 'flow_flow';
            streamOpts.trueLayout = streamOpts.layout;

            /*we will modify 'grid' layout to get 'carousel' layout*/
            if ( streamOpts.layout == 'carousel' ) {
                streamOpts['layout'] = 'grid';
                streamOpts['g-ratio-h'] = "1";
                streamOpts['g-ratio-img'] = "1/2";
                streamOpts['g-ratio-w'] = "1";
                streamOpts['g-overlay'] = "yep";
                streamOpts['c-overlay'] = "yep";
                streamOpts['s-desktop'] = "0";
                streamOpts['s-laptop'] = "0";
                streamOpts['s-smart-l'] = "0";
                streamOpts['s-smart-p'] = "0";
                streamOpts['s-tablet-l'] = "0";
                streamOpts['s-tablet-p'] = "0";
            }
            else if ( streamOpts.layout == 'list' ) {  /*the same with list, we only need news feed style*/
                streamOpts['layout'] = 'masonry';
            }

	        if ( ads ) streamOpts.ads = ads;

            opts.streams['stream' + streamOpts.id] = streamOpts;

            var $cont = $("[data-plugin='flow_flow']#ff-stream-"+data['stream-id']);
            var ajaxDeferred;
            var script, style;
            var layout_pre = streamOpts.layout.charAt(0);
            var isOverlay = layout_pre === 'j' || streamOpts[layout_pre + '-overlay'] === 'yep' && streamOpts.trueLayout !== 'list';
            var imgIndex;
            if (isOverlay) {
                if (streamOpts.template[0] !== 'image') {
                    for (var i = 0, len = streamOpts.template.length; i < len; i++) {
                        if (streamOpts.template[i] === 'image') imgIndex = i;
                    }
                    streamOpts.template.splice(0, 0, streamOpts.template.splice(imgIndex, 1)[0]);
                }
                streamOpts.isOverlay = true;
            };
            if (FF_resource.scriptDeferred.state() === 'pending' && !FF_resource.scriptLoading) {
                script = document.createElement('script');
                script.src = "<?php echo $plugin_directory;?>/js/public.js?ver=<?php echo $version; ?>";
                script.onload = function( script, textStatus ) {
                    FF_resource.scriptDeferred.resolve();
                };
                document.body.appendChild(script);
                FF_resource.scriptLoading = true;
            };
            if (FF_resource.styleDeferred.state() === 'pending' && !FF_resource.styleLoading) {
                style = document.createElement('link');
                style.type = "text/css";
                style.id = "ff_style";
                style.rel = "stylesheet";
                style.href = "<?php echo $plugin_directory;?>/css/public.css?ver=<?php echo $version; ?>";
                style.media = "screen";
                style.onload = function( script, textStatus ) {
                    FF_resource.styleDeferred.resolve();
                };
                document.getElementsByTagName("head")[0].appendChild(style);
                FF_resource.styleLoading = true;
            }
            $cont.addClass('ff-layout-' + streamOpts.trueLayout);
            if (!isMobile && streamOpts.trueLayout !== 'carousel') $cont.css('minHeight', '500px');
            ajaxDeferred = <?php if ($admin) {echo '$.get(opts.ajaxurl, data)';} else {echo 'isLS && sessionStorage.getItem(hash) ? {} : $.get(opts.ajaxurl, data)';} echo PHP_EOL; ?>;
            $.when( ajaxDeferred, FF_resource.scriptDeferred, FF_resource.styleDeferred ).done(function ( data ) {
                var response, $errCont, err;
                var moderation = <?php echo $moderation ? 1 : 0 ?>;
                var original = <?php if ($admin) {echo 'data[0]';} else {echo '(isLS && sessionStorage.getItem(hash)) ? JSON.parse( sessionStorage.getItem(hash) ) : data[0]';}?>;
                try {
                    /* response = JSON.parse(original); */
                    response = original; /* since 4.1 */
                } catch (e) {
                    window.console && window.console.log('Flow-Flow gets invalid data from server');
                    if (opts.isAdmin || opts.isLog) {
	                    $errCont = $('<' + 'div class="ff-errors"><' + 'div class="ff-disclaim">If you see this message then you have administrator permissions and Flow-Flow got invalid data from server. Please provide error message below if you are doing support request.<' + '/div><' + 'div class="ff-err-info"><'+'/div><'+'/div>');
	                    $cont.before($errCont);
                        $errCont.find('.ff-err-info').html(original == '' ? 'Empty response from server' : original);
                    }
                    return;
                }

                if ( ! response ) {
                	console.log( 'FLOW-FLOW: null response from server' );
                	return;
                }

                // injecting ads for cloud streams
                if ( ads ) {

                	var newArr = [];

                	var post, ad;

                	for ( var i = 0, len = response.items.length; i < len; i++ ) {

		                post = response.items[ i ];

                		if ( ads[ i ] ) {

                			ad = ads[ i ];
                            ad.id = 'ad_el_' + ad.id;
                            newArr.push ( ad );

			                delete ads[ i ];
                        }

                        newArr.push( post );
                    }

                    response.items = newArr;
                }

                opts.streams['stream' + streamOpts.id]['items'] = response;
                if (!FlowFlowOpts.dependencies) FlowFlowOpts.dependencies = {};
                <?php
                $dependencies = apply_filters('ff_plugin_dependencies', []);
                foreach ($dependencies as $name) {
                    echo "if (!FlowFlowOpts.dependencies['{$name}']) FlowFlowOpts.dependencies['{$name}'] = true;";
                }
                ?>

                FlowFlow.extensionResourcesRequests = FlowFlow.extensionResourcesRequests || [];
                var request, extension, style;

                for ( extension in FlowFlowOpts.dependencies ) {
                    if ( FlowFlowOpts.dependencies[extension] && FlowFlowOpts.dependencies[extension] !== 'loaded') {
                        request = $.getScript( opts.plugin_base + '-' + extension + '/js/ff_' + extension + '_public.js?ver=<?php echo $version; ?>');
                        FlowFlow.extensionResourcesRequests.push(request);

                        style = document.createElement('link');
                        style.type = "text/css";
                        style.rel = "stylesheet";
                        style.id = "ff_ad_style";
                        style.href = opts.plugin_base + '-' + extension + '/css/ff_' + extension + '_public.css?ver=<?php echo $version; ?>';
                        style.media = "screen";
                        document.getElementsByTagName("head")[0].appendChild(style);

                        FlowFlowOpts.dependencies[extension] = 'loaded';
                    }
                }

                var resourcesLoaded = $.when.apply($, FlowFlow.extensionResourcesRequests);

                resourcesLoaded.done(function(){
                    var $stream, width;
                    console.log('FLOW-FLOW data', response);

                    $stream = FlowFlow.buildStreamWith(response, streamOpts, moderation, FlowFlowOpts.dependencies);

                    <?php if (!$admin) {echo 'if (isLS && response.items.length > 0 && response.hash.length > 0) sessionStorage.setItem(  response.hash , JSON.stringify( original ));' . PHP_EOL;}?>

                    var num = streamOpts.layout === 'compact' || (streamOpts.mobileslider === 'yep' && isMobile)? (streamOpts.mobileslider === 'yep' ? 3 : streamOpts['cards-num']) : false;

                    $cont.append( $stream );

                    if ( typeof $stream !== 'string' ) {
                        FlowFlow.setupGrid($cont.find('.ff-stream-wrapper'), num, streamOpts.scrolltop === 'yep', streamOpts.gallery === 'yep', streamOpts, $cont);
                    }

                    setTimeout(function(){
                        $cont.find('.ff-header').removeClass('ff-loading').end().find('.ff-loader').addClass('ff-squeezed').delay(300).hide();
                    }, 0);

                    <?php do_action('ff_add_view_action', $stream);?>

                }).fail(function(){
                    console.log('Flow-Flow: resource loading failed');
                });

                var isErr = response.status === "errors";
                if ((opts.isAdmin || opts.isLog) && isErr) {
	                $errCont = $('<'+'div class="ff-errors"><'+'div class="ff-err-info">If you see this then you are administrator and Flow-Flow got errors from APIs while requesting data. Please go to plugin admin and after refreshing page check for error(s) on stream settings page. Please provide error message info if you are doing support request.<'+'/div><'+'/div>');
	                $cont.before($errCont);
                }
            });

            function isLocalStorageNameSupported() {
                var testKey = 'test', storage = window.sessionStorage;
                try {
                    storage.setItem(testKey, '1');
                    storage.removeItem(testKey);
                    return true;
                } catch (error) {
                    return false;
                }
            };

            return false;
        }
    })()

</script>
<!-- Flow-Flow — Social streams plugin for Wordpress -->