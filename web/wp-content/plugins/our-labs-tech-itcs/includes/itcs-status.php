<?php

namespace OUR\ITCSSTATUS;

// define shortcode for page
add_shortcode('ecu_itcs_status', 'OUR\ITCSSTATUS\show_status');

// define shortcode for alert
// [ecu_itcs_alert url="http://google.com"]
add_shortcode('ecu_itcs_alert', 'OUR\ITCSSTATUS\show_alert');

// define widget
add_action('widgets_init', 'OUR\ITCSSTATUS\ecu_itcs_load_widget',10,0);

// load specific scripts and styles
add_action('wp_enqueue_scripts', 'OUR\ITCSSTATUS\adding_scripts',10,0);

function ecu_itcs_load_widget() {
    register_widget('OUR\ITCSSTATUS\ecu_itcs_widget');
}

function adding_scripts() {
    adding_styles();
}

function adding_styles() {
    wp_register_style('ecu_itcs_css', plugins_url( '/our-labs-tech-itcs/templates/itcs-status/itcs-status.css' ) );
    wp_enqueue_style('ecu_itcs_css');
}

function show_status()
{
    $issues = get_issues();
    $maintenances = get_maintenances();
    ob_start();
    require_once(dirname(__FILE__,2) . '/templates/itcs-status/itcs-status.tpl.php');
    return ob_get_clean();
}

function show_alert($atts = [])
{
    $issues = get_issues();
    $href = $atts['url'];
    $href = esc_url($href);
    ob_start();
    require_once(dirname(__FILE__,2) . '/templates/itcs-status/itcs-alert.tpl.php');
    return ob_get_clean();
}

function get_issues()
{
    // $itcs = new \wpdb(TOOLS_DB_USER, TOOLS_DB_PASSWORD, TOOLS_DB_NAME, TOOLS_DB_HOST);
    // $issues = $itcs->get_results("
    //     SELECT *
    //     FROM notifications_system_status
    //     WHERE date_start <= now()
    //     AND date_end >= now()
    //     AND post_web = 1
    //     AND status_id = 2
    //     ORDER BY date_start
    //     DESC
    // ");
    $issues = \Database\Tools::query("
        SELECT *
        FROM notifications_system_status
        WHERE date_start <= now()
        AND date_end >= now()
        AND post_web = 1
        AND status_id = 2
        ORDER BY date_start
        DESC
    ");
    return $issues;
}

function get_maintenances()
{
    // $itcs = new \wpdb(TOOLS_DB_USER, TOOLS_DB_PASSWORD, TOOLS_DB_NAME, TOOLS_DB_HOST);
    // $maintenances = $itcs->get_results("
    //     SELECT *
    //     FROM notifications_notifications
    //     WHERE date_start <= now()
    //     AND date_end >= now()
    //     AND post_web = 1
    //     AND status_id = 2
    //     ORDER BY date_start
    //     DESC
    // ");
    $maintenances = \Database\Tools::query("
        SELECT *
        FROM notifications_notifications
        WHERE date_start <= now()
        AND date_end >= now()
        AND post_web = 1
        AND status_id = 2
        ORDER BY date_start
        DESC
    ");
    return $maintenances;
}

function itcs_strip_tags($content) {
    $allowed_tags = [
        'p' => [],
        'a' => ['href' => [], 'target' => [], 'title' => []],
        'b' => [],
        'i' => [],
        'br' => [],
        'img' => ['src' => [], 'alt' => []],
        'ul' => ['class' => []],
        'li' => ['class' => []],
        'ol' => ['class' => []],
        'hr' => [],
        'ins' => [],
        'del' => [],
        'strong' => [],
        'em' => [],
        'h1' => [],
        'h2' => [],
        'h3' => [],
        'h4' => [],
        'h5' => [],
        'h6' => [],
        'div' => ['class' => []]
    ];
    return wp_kses($content, $allowed_tags);
}

class ecu_itcs_widget extends \WP_Widget {

    function __construct() {
        parent::__construct(
            'OUR\ITCSSTATUS\ecu_itcs_load_widget', __('ECU ITCS Widget', 'ecu_itcs_widget_domain'),
            array('description' => __('ECU ITCS Status Notifications', 'ecu_itcs_widget_domain'),)
        );
    }

    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        $url = apply_filters('widget_url', $instance['url']);

        echo $args['before_widget'];

        if (!empty($title))
            echo $args['before_title'] . esc_html($title) . $args['after_title'];

        $issues = get_issues();

        if (count($issues) > 0) {
            echo __('
                <ul class="ecu-general-status-services">
                    <a href="' . esc_url($url) . '">
                        <li class="ecu-error-status">
                            <i class="fa fa-exclamation"></i> Status: Issues Reported
                        </li>
                    </a>
                </ul>
            ', 'ecu_itcs_widget_domain');
        } else {
            echo __('
                <ul class="ecu-general-status-services">
                    <a href="' . esc_url($url) . '">
                        <li class="ecu-ok-status">
                            <i class="fa fa-check"  aria-hidden="true"></i> Status: Normal
                        </li>
                    </a>
                </ul>
            ', 'ecu_itcs_widget_domain');
        }
        echo __('
            <p style="text-align:center; margin-bottom: 60px;">
                <a style="font-size: 14px" href="' . esc_url($url) . '">View Maintenance</a>
            </p>
        ', 'ecu_itcs_widget_domain');

        echo $args['after_widget'];
    }

    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('System Status', 'ecu_itcs_widget_domain');
        }
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
                <input class="widefat"
                    id="<?php echo $this->get_field_id('title'); ?>"
                    name="<?php echo $this->get_field_name('title'); ?>"
                    type="text"
                    value="<?php echo esc_attr($title); ?>" />
            </p>
        <?php

        if (isset($instance['url'])) {
            $url = $instance['url'];
        } else {
            $url = __(isset($_SERVER["HTTPS"]) ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . '/cs-itcs', 'ecu_itcs_widget_domain');
        }
         ?>
            <p>
                <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Url:'); ?></label>
                <input class="widefat"
                    id="<?php echo $this->get_field_id('url'); ?>"
                    name="<?php echo $this->get_field_name('url'); ?>"
                    type="text"
                    value="<?php echo esc_attr($url); ?>" />
            </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['url'] = (!empty($new_instance['url'])) ? sanitize_text_field($new_instance['url']) : '';
        return $instance;
    }

}

?>
