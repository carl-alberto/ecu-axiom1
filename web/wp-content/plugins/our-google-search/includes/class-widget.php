<?php
namespace OUR\GOOGLE\SEARCH;

/**
 * The widget class for our google search widget in WordPress.
 *
 * @see:  https://codex.wordpress.org/Widgets%20API
 */
class Widget extends \WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'google_search',

			// Widget name will appear in UI
			__('Google Search', 'google_search_widget_domain'),

			// Widget description
			array( 'description' => __( 'Adds a google search widget', 'google_search_widget_domain' ), )
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		wp_enqueue_script( 'google_search_script' );
        wp_enqueue_script( 'google_search_change_placeholder');
        wp_enqueue_style( 'google_search_style' );

        // Why reinvent the wheel?
		$search = do_shortcode('[google_search_field/]');

        echo $search;
	}
}