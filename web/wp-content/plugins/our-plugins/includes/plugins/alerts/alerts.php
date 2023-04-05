<?php
/*
Widget Name: ECU Alert
Description: A widget to display an ECU Alert widget.
*/
namespace OUR\PLUGINS;

    // Register the widget
    add_action( 'widgets_init', function(){
        register_widget( __NAMESPACE__ . '\Ecu_Alert_Widget' );
    },10,0);

class Ecu_Alert_Widget extends \WP_Widget {
    /*
     * Constructor
     *
     */
    function __construct() {
        $widget_ops = array(
            'classname'   => 'ecu-alert',
            'description' => __('A widget to display an ECU Alert Widget', 'ecu-widgets-bundle'),
        );

        parent::__construct( 'ecu-alert', 'ECU Alert Widget', $widget_ops );
    }

    /**
	 * Print widget if there is no alert
	 * @return NULL
	 */
	protected function printNoAlert()	{
		echo "<div class='bs-callout bs-callout-success ecu-alert-widget'>
		<div class='row'>
		<div class='col-xs-3' data-mh='alert-widget'>
			<div class='alert-icon'>
				<span class='fa fa-check fa-2x' aria-hidden='true'></span>
			</div>
		</div>
		<div class='col-xs-9'>
			<div class='alert-content' data-mh='alert-widget'>
				<div class='header'>No Current Alerts</div>
			</div>
		</div>
			</div>
		</div>";

	}

	/**
	 * Print widget if there is an alert
	 *
	 * @return NULL
	 */
	protected function printAlert($alert)	{
		echo "<div class='bs-callout bs-callout-danger ecu-alert-widget'>
		<a href='https://". getenv('TOPSITE_ENV')."/alert' target='_blank'>
		<div class='row'>
		<div class='col-xs-3' data-mh='alert-widget'>
			<div class='alert-icon'>
				<span class='fa fa-exclamation fa-2x' aria-hidden='true'></span>
			</div>
		</div>
		<div class='col-xs-9' >
			<div class='alert-content' data-mh='alert-widget'>
				<div class='header'>".$alert->title."</div>
			</div>
		</div>
			</div>
			</a>
		</div>";
	}

    protected function render($instance) {
        //GET DATA
        // $mydb = new wpdb(getenv('HOMEPAGE_DB_USER'),getenv('HOMEPAGE_DB_PASSWORD'),getenv('HOMEPAGE_DB_NAME'),getenv('HOMEPAGE_DB_HOST'));
        // $rows = $mydb->get_results("select * from rave_alerts where deleted = 0 and expiration >= NOW() and effective <= NOW()");
        $rows = \Database\Homepage::query("
            SELECT *
            FROM rave_alerts
            WHERE deleted = 0
                AND expiration >= NOW()
                AND effective <= NOW()
        ", NULL, false);

        //print heading
        if (isset($instance['title'])) {
            echo "<h3>".$instance['title']."</h3>";
        }
        //print alerts
        if (count($rows)>0) {
            foreach ($rows as $alert) {
                $this->printAlert($alert);
            }
        }	else    {
            $this->printNoAlert();
        }
    }

    /**
     * Outputs the widget within the widgetized area.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $args     The default widget arguments.
     * @param array $instance The settings for the current widget instance.  See the form function for details.
     */
    public function widget( $args, $instance ) {

        // Get Data
        $title = apply_filters( 'widget_title', esc_html( $instance['title'] ) );

        // Output Widget
        echo $args['before_widget'];

        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        if(!WP_HTTP_BLOCK_EXTERNAL){
            $instance['style'] = 'list';
            echo $this->render($instance);
        } else {
            echo "<p>Localist is currently under maintenance and will be back up shortly.</p>";
        }


        echo $args['after_widget'];

    }

    /**
     * Processing widget options on save
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
    public function update( $new_instance, $old_instance ) {

        // Set $instance to the old instance in case no new settings have been updated for a particular field.
        $instance = $old_instance;

        // Sanitize user inputs.
        $instance['title'] = sanitize_text_field( $new_instance['title'] );

        return $instance;
    }

    /**
     * Outputs the widget form where the user can specify settings.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $instance  {
     *      Optional. The settings for the widget instance.
     *
     *      @type int     $max_events       Number of events to return per page. Default 5. Accepts 1-100.
     * }
     */
    public function form( $instance ) {

        // Set form values
        if( isset( $instance['title'] ) ) {
            $title = $instance['title'];
        } else {
            $title = '';
        }

        // title
        echo '<p>';
        echo '<label for="' . $this->get_field_name( 'title' ) . '">Title:</label>';
        echo '<input id="' . $this->get_field_id( 'title' ) . '" class="widefat" type="text" name="' . $this->get_field_name( 'title' ) . '" value="' . esc_attr( $title ) . '" ">';
        echo '</p>';
    }
}