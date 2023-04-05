<?php
/*
Plugin Name: Vendi TinyMCE Anchor
Plugin URI: https://vendiadvertising.com/
Description: Adds TinyMCE's core anchor plugin back into WordPress
Version: 1.0.1
Author: Vendi Advertising (Chris Haas)
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// add new buttons
add_filter(
    'mce_buttons_2',
    static function ($buttons) {
        $buttons[] = 'anchor';

        return $buttons;
    }
);

// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
add_filter(
    'mce_external_plugins',
    static function ($plugin_array) {
        $plugin_array['anchor'] = plugins_url('/tinymce/js/plugins/anchor/plugin.min.js', __FILE__);

        return $plugin_array;
    }
);
