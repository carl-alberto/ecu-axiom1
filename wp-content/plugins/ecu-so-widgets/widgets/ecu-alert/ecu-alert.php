<?php
/*
Widget Name: ECU Alert
Description: A widget to display an ECU Alert widget.
Author: krochmalnyd
Date: 03/01/2017
*/

class Ecu_Alert_Widget extends SiteOrigin_Widget {
    /*
     * Constructor
     *
     */
    function __construct() {
        //wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

        return parent::__construct(
            // The unique id for your widget.
            'ecu-alert',
            // The name of the widget for display purposes.
            __('ECU Alert Widget', 'ecu-widgets-bundle'),
            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => __('A widget to display an ECU Alert Widget', 'ecu-widgets-bundle'),
            ),
            //The $control_options array, which is passed through to WP_Widget
            array(
            ),
            //The $form_options array, which describes the form fields used to configure SiteOrigin widgets. We'll explain these in more detail later.
            false,
            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    /**
     * Creates the form on the admin screen
     * @author Daniel Krochmalny <krochmalnyd@ecu.edu>
     *
     * @return array Widget settings
     */
    function initialize_form() {
        return array(
            'heading' => array (
                'type' => 'text',
                'label' => __('Heading', 'so-widgets-bundle')
            ),
        );
    }

    /**
     * Passes variables to the template
     * @author Daniel Krochmalny <krochmalnyd@ecu.edu>
     *
     * @param  object $instance [description]
     * @param  array $args     arguments
     * @return array           heading
     */
    public function get_template_variables( $instance, $args ) {
        return array(
            'heading' => $instance['heading'],
        );
    }


}

//register the widget
siteorigin_widget_register('ecu-alert', __FILE__, 'Ecu_Alert_Widget');
