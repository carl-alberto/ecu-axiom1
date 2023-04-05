<?php

namespace ECU\GLOBALASSETS;

/*
Plugin Name: ECU Global Front-End Assets (e.g. Datatables)
*/

function register_datatables()
{
    wp_register_script('ecu_datatables_js', plugins_url( 'assets/js/jquery.dataTables.min.js', __FILE__ ), array('jquery'), '1.1', true);
    wp_register_script('ecu_datatables_responsive_js', plugins_url( 'assets/js/dataTables.responsive.min.js', __FILE__ ), array('jquery'), '1.1', true);
    wp_enqueue_script('ecu_datatables_js');
    wp_enqueue_script('ecu_datatables_responsive_js');

    // register styles
    register_datatables_styles();
}
function register_datatables_styles()
{
    wp_register_style('ecu_datatables_css',plugins_url( 'assets/css/jquery.dataTables.min.css', __FILE__ ) );
    wp_register_style('ecu_datatables_bootstrap_css', plugins_url( 'assets/css/dataTables.bootstrap.min.css', __FILE__ ) );
    wp_register_style('ecu_datatables_custom_css', plugins_url( 'assets/css/ecu_datatables_custom.css', __FILE__ ));
    wp_enqueue_style('ecu_datatables_css');
    wp_enqueue_style('ecu_datatables_bootstrap_css');
    wp_enqueue_style('ecu_datatables_custom_css');
}

function register_datatables_buttons()
{
    $https = strpos(WP_PLUGIN_URL, 'https') ? WP_PLUGIN_URL : str_replace('http', 'https', WP_PLUGIN_URL);
    wp_register_script('ecu_datatables_buttons', $https . '/' . basename(__DIR__) . '/assets/js/dataTables.buttons.min.js', array('jquery'), '1.1', true);
    wp_register_script('ecu_datatables_buttonshtml5', $https . '/' . basename(__DIR__) . '/assets/js/buttons.html5.min.js', array('jquery'), '1.1', true);
    wp_register_script('ecu_datatables_buttonsflash', $https . '/' . basename(__DIR__) . '/assets/js/buttons.flash.min.js', array('jquery'), '1.1', true);
    wp_enqueue_script('ecu_datatables_buttons');
    wp_enqueue_script('ecu_datatables_buttonshtml5');
    wp_enqueue_script('ecu_datatables_buttonsflash');

    // register styles
    register_datatables_styles_buttons();
}

function register_datatables_styles_buttons()
{
}

function register_selectwoo()
{
    wp_register_script('ecu_selectwoo_js', plugins_url( 'assets/js/selectWoo.full.js', __FILE__ ), array('jquery'), '1.1', true);
    wp_enqueue_script('ecu_selectwoo_js');

    // register styles
    register_selectwoo_styles();
}
function register_selectwoo_styles()
{
    wp_register_style('ecu_selectwoo_css', plugins_url( 'assets/css/select2.min.css', __FILE__ ));
    wp_register_style('ecu_selectwoo_custom_css', plugins_url( 'assets/css/ecu_selectwoo_custom.css', __FILE__ ));
    wp_enqueue_style('ecu_selectwoo_css');
    wp_enqueue_style('ecu_selectwoo_custom_css');
}