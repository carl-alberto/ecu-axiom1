<?php

namespace OUR\LABSTECHITCS;

/*
Plugin Name: Our Labs / Classroom Tech / ITCS Status
Description: OUR Labs / Classroom Tech / ITCS Status
*/

function register_datatables()
{
    wp_register_script('our_labs_tech_itcs_datatables_js', plugins_url( 'assets/js/jquery.dataTables.min.js', __FILE__ ), array('jquery'), '1.1', true);
    wp_register_script('our_labs_tech_itcs_datatables_responsive_js', plugins_url( 'assets/js/dataTables.responsive.min.js', __FILE__ ), array('jquery'), '1.1', true);
    wp_register_script('our_labs_tech_itcs_bootstrap4', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js', array('jquery'));
    wp_enqueue_script('our_labs_tech_itcs_bootstrap4');
    wp_enqueue_script('our_labs_tech_itcs_datatables_js');
    wp_enqueue_script('our_labs_tech_itcs_datatables_responsive_js');

    // register styles
    register_datatables_styles();
}
function register_datatables_styles()
{
    wp_register_style('our_labs_tech_itcs_datatables_css',plugins_url( 'assets/css/jquery.dataTables.min.css', __FILE__ ) );
    wp_register_style('our_labs_tech_itcs_datatables_bootstrap_css', plugins_url( 'assets/css/dataTables.bootstrap.min.css', __FILE__ ) );
    wp_register_style('our_labs_tech_itcs_datatables_custom_css', plugins_url( 'assets/css/ecu_datatables_custom.css', __FILE__ ));
    wp_enqueue_style('our_labs_tech_itcs_datatables_css');
    wp_enqueue_style('our_labs_tech_itcs_datatables_bootstrap_css');
    wp_enqueue_style('our_labs_tech_itcs_datatables_custom_css');
}

function register_datatables_buttons()
{
    wp_register_script('our_labs_tech_itcs_datatables_buttons', plugins_url( '/assets/js/dataTables.buttons.min.js', __FILE__ ), array('jquery'), '1.1', true);
    wp_register_script('our_labs_tech_itcs_datatables_buttonshtml5', plugins_url( '/assets/js/buttons.html5.min.js', __FILE__ ), array('jquery'), '1.1', true);
    wp_register_script('our_labs_tech_itcs_datatables_buttonsflash', plugins_url( '/assets/js/buttons.flash.min.js', __FILE__ ), array('jquery'), '1.1', true);
    wp_enqueue_script('our_labs_tech_itcs_datatables_buttons');
    wp_enqueue_script('our_labs_tech_itcs_datatables_buttonshtml5');
    wp_enqueue_script('our_labs_tech_itcs_datatables_buttonsflash');

    // register styles
    register_datatables_styles_buttons();
}

function register_datatables_styles_buttons()
{
}

function register_selectwoo()
{
    wp_register_script('our_labs_tech_itcs_selectwoo_js', plugins_url( 'assets/js/selectWoo.full.js', __FILE__ ), array('jquery'), '1.1', true);
    wp_enqueue_script('our_labs_tech_itcs_selectwoo_js');

    // register styles
    register_selectwoo_styles();
}
function register_selectwoo_styles()
{
    wp_register_style('our_labs_tech_itcs_selectwoo_css', plugins_url( 'assets/css/select2.min.css', __FILE__ ));
    wp_register_style('our_labs_tech_itcs_selectwoo_custom_css', plugins_url( 'assets/css/ecu_selectwoo_custom.css', __FILE__ ));
    wp_enqueue_style('our_labs_tech_itcs_selectwoo_css');
    wp_enqueue_style('our_labs_tech_itcs_selectwoo_custom_css');
}

require_once(dirname(__FILE__) . '/includes/find-a-lab.php');

require_once(dirname(__FILE__) . '/includes/itcs-status.php');

require_once(dirname(__FILE__) . '/includes/classroom-tech.php');
