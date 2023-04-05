<?php namespace flow;
if ( ! defined( 'WPINC' ) ) die;

use Exception;
use la\core\LABase;
use la\core\LAUtils;
use la\core\settings\LASettingsUtils;
use la\core\settings\LAStreamSettings;

/**
 * Flow-Flow
 *
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `FlowFlowAdmin.php`
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>

 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
class FlowFlow extends LABase {

    protected function getShortcodePrefix(){
        return 'ff';
    }

    /**
     * @param $stream
     * @param $context
     *
     * @return mixed|void
     * @throws Exception
     */
    protected function getPublicContext($stream, $context){
        $context['boosted'] = LASettingsUtils::YepNope2ClassicStyleSafe($stream, 'cloud', false);
        $context = parent::getPublicContext($stream, $context);
        $context['token'] = $context['can_moderate'] ? LAUtils::dbm($context)->getToken(true) : '';
        if ($context['boosted'] && FF_USE_WP){
            $context = apply_filters('ff_build_public_context', $context, new LAStreamSettings($stream));
        }
        return $context;
    }

    protected function getNameJSOptions(){
        return 'FlowFlowOpts';
    }
}
