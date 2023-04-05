<?php

namespace Intranet;

defined( 'ABSPATH' ) OR exit;

/**
 * Trait ECU Intranet Settings
 */
trait Settings {

    /**
     * The wp option name the settings are saved under.
     */
    private $option_name = 'wp_intranet_settings';

    /**
     * True if intranet is enabled, false otherwise.  Default to false.
     * @var bool
     */
    protected $enabled = false; // Always default to off

    /**
     * The type of intranet;
     *  5 = Employees only
     *  4 = Students only
     *  3 = Group membership or active User
     *  2 = Blog Members Only
     *  1 = Any valid pirate_id
     * @var int
     */
    protected $type = 2; // Always default to blog members only

    /**
     * A comma seperated lists of AD Groups that are authorized for the intranet
     * type = 3.
     * @var string
     */
    protected $ad_groups;

    /**
     * A comma seperated lists of AD Groups that are authorized for the intranet
     * type = 3.
     * @var string
     */
    protected $ad_accounts;

    public function save_settings() {
        $intranet_settings = [];
        $intranet_settings['type'] = $this->get_type();
        $intranet_settings['enabled'] = $this->get_enabled();
        $intranet_settings['ad_accounts'] = $this->get_ad_accounts();
        $intranet_settings['ad_groups'] = $this->get_ad_groups();
        update_option($this->option_name,  $intranet_settings);
        update_option('blog_public', !$this->get_enabled());   // adds no index and blocks crawlers for intranet enabled sites
    }

    public function init_settings() {
        $intranet_settings = get_option($this->option_name, []);
        if(isset($intranet_settings['type'])) {
            $this->set_type($intranet_settings['type']);
        }
        if(isset($intranet_settings['enabled'])) {
           $this->set_enabled($intranet_settings['enabled']);
        }
        if(isset($intranet_settings['ad_accounts'])) {
            $this->set_ad_accounts($intranet_settings['ad_accounts']);
        }
        if(isset($intranet_settings['ad_groups'])) {
            $this->set_ad_groups($intranet_settings['ad_groups']);
        }
    }

    public function get_ad_groups() {
        return $this->ad_groups;
    }

    public function get_ad_accounts() {
        return $this->ad_accounts;
    }

    public function get_type() {
        return $this->type;
    }

    public function get_enabled() {
        return $this->enabled;
    }

    public function set_ad_accounts($accounts) {
        $this->ad_accounts = sanitize_text_field($accounts);
    }

    public function set_ad_groups($groups) {
        $this->ad_groups = sanitize_text_field($groups);
    }

    public function set_type($integer) {
        $this->type = absint($integer);
    }

    public function set_enabled($flag) {
        $this->enabled = (bool) $flag;
    }
}