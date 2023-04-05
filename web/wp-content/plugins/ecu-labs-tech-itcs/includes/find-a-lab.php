<?php

namespace ECU\LABS;

// define shortcode
add_shortcode('ecu_labs_room_search', 'ECU\LABS\render_table');

// load specific scripts and styles
add_action( 'wp_enqueue_scripts', 'ECU\LABS\load_plugin_assets',10,0);

function load_plugin_assets() {
    global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ecu_labs_room_search') ) {
        // load global datatables assets
        \ECU\GLOBALASSETS\register_datatables();
        // load global selectwoo assets
        \ECU\GLOBALASSETS\register_selectwoo();
        // load specific scripts and styles
        adding_scripts();
    }
}

function adding_scripts() {
    wp_register_script('ecu_labs_js', plugins_url( '/ecu-labs-tech-itcs/templates/find-a-lab/find-a-lab.js' ) , array('jquery'), '1.1', true);
    wp_enqueue_script('ecu_labs_js');
    // add styles
    adding_styles();
}
function adding_styles() {
    wp_register_style('ecu_labs_css', plugins_url( '/ecu-labs-tech-itcs/templates/find-a-lab/find-a-lab.css') );
    wp_enqueue_style('ecu_labs_css');
    wp_register_style('ecu_spinner_css', plugins_url( '/ecu-global-assets/assets/css/spinner.css') );
    wp_enqueue_style('ecu_spinner_css');
}

function render_table() {
    $hours_available = get_hours_available();
    $results = get_lab_data($hours_available);
    $available_software = get_available_software();
    ob_start();
    require_once(dirname(__FILE__,2) . '/templates/find-a-lab/find-a-lab-table.tpl.php');
    return ob_get_clean();
}

function get_available_software() {
    // $query = new \wpdb(TOOLS_DB_USER, TOOLS_DB_PASSWORD, TOOLS_DB_NAME, TOOLS_DB_HOST);
    // $available_software = $query->get_results('SELECT DISTINCT 
    //         labs_labs_software.software_id,
    //         labs_labs_software.os_id,
    //         labs_software.name AS software, 
    //         labs_os.name AS os 
    //     FROM labs_labs_software  
    //     LEFT JOIN labs_software ON labs_labs_software.software_id = labs_software.id 
    //     LEFT JOIN labs_os ON labs_labs_software.os_id = labs_os.id 
    //     ORDER BY 
    //         labs_software.name,
    //         labs_os.name  
    // ');

    $available_software = \Database\Tools::query("
        SELECT DISTINCT 
            labs_labs_software.software_id,
            labs_labs_software.os_id,
            labs_software.name AS software, 
            labs_os.name AS os 
        FROM labs_labs_software  
        LEFT JOIN labs_software ON labs_labs_software.software_id = labs_software.id 
        LEFT JOIN labs_os ON labs_labs_software.os_id = labs_os.id 
        ORDER BY 
            labs_software.name,
            labs_os.name  
    ");

    return $available_software;
}

function get_lab_data($hours_available) {
    // $search = new \wpdb(TOOLS_DB_USER, TOOLS_DB_PASSWORD, TOOLS_DB_NAME, TOOLS_DB_HOST);
    // $results = $search->get_results(
    //     "SELECT DISTINCT
    //     labs_labs.id AS lab_id,
    //     labs_labs.type_id AS lab_type_id,
    //     labs_labs.name AS lab_name,
    //     university_buildings_rooms.id AS room_id,
    //     university_buildings_rooms.capacity AS room_capacity,
    //     university_buildings.id AS building_id,
    //     university_buildings.name AS building_name,
    //     university_buildings.code AS building_code,
    //     labs_type.type AS lab_type,
    //     labs_stats.available_count,
    //     labs_stats.off_count,
    //     labs_stats.total_count,
    //     GROUP_CONCAT(labs_peripherals.name) AS peripherals,
    //     GROUP_CONCAT(labs_peripherals.icon) AS peripheral_icons,
    //     GROUP_CONCAT(labs_labs_peripherals.peripheral_id) AS peripheral_ids,
    //     CONVERT(TRIM(LEADING '0' FROM university_buildings_rooms.room), CHAR) AS room,
    //     (SELECT
    //         GROUP_CONCAT(CONCAT( labs_labs_software.software_id, ';', labs_labs_software.os_id))
    //     FROM
    //         labs_labs_software
    //     WHERE
    //         labs_labs_software.lab_id = labs_labs.id
    //     ) AS 'software'
    // FROM 
    //     labs_labs
    // LEFT JOIN university_buildings_rooms ON labs_labs.university_buildings_rooms_id = university_buildings_rooms.id
    // LEFT JOIN university_buildings ON university_buildings_rooms.building_id = university_buildings.id
    // LEFT JOIN labs_type ON labs_labs.type_id = labs_type.id 
    // LEFT JOIN labs_labs_peripherals ON labs_labs.id = labs_labs_peripherals.lab_id 
    // LEFT JOIN labs_peripherals ON labs_peripherals.id = labs_labs_peripherals.peripheral_id
    // LEFT JOIN labs_labs_stats ON labs_labs.id = labs_labs_stats.lab_id
    // LEFT JOIN labs_stats ON labs_stats.group_id = labs_labs_stats.stat_id
    // GROUP BY 
    //     labs_labs.id
    // ORDER BY 
    //     university_buildings.name ASC, 
    //     university_buildings_rooms.room ASC;");

    $results = \Database\Tools::query("
        SELECT DISTINCT
            labs_labs.id AS lab_id,
            labs_labs.type_id AS lab_type_id,
            labs_labs.name AS lab_name,
            university_buildings_rooms.id AS room_id,
            university_buildings_rooms.capacity AS room_capacity,
            university_buildings.id AS building_id,
            university_buildings.name AS building_name,
            university_buildings.code AS building_code,
            labs_type.type AS lab_type,
            labs_stats.available_count,
            labs_stats.off_count,
            labs_stats.total_count,
            GROUP_CONCAT(labs_peripherals.name) AS peripherals,
            GROUP_CONCAT(labs_peripherals.icon) AS peripheral_icons,
            GROUP_CONCAT(labs_labs_peripherals.peripheral_id) AS peripheral_ids,
            CONVERT(TRIM(LEADING '0' FROM university_buildings_rooms.room), CHAR) AS room,
            (SELECT
                GROUP_CONCAT(CONCAT( labs_labs_software.software_id, ';', labs_labs_software.os_id))
            FROM
                labs_labs_software
            WHERE
                labs_labs_software.lab_id = labs_labs.id
            ) AS 'software'
        FROM 
            labs_labs
        LEFT JOIN university_buildings_rooms ON labs_labs.university_buildings_rooms_id = university_buildings_rooms.id
        LEFT JOIN university_buildings ON university_buildings_rooms.building_id = university_buildings.id
        LEFT JOIN labs_type ON labs_labs.type_id = labs_type.id 
        LEFT JOIN labs_labs_peripherals ON labs_labs.id = labs_labs_peripherals.lab_id 
        LEFT JOIN labs_peripherals ON labs_peripherals.id = labs_labs_peripherals.peripheral_id
        LEFT JOIN labs_labs_stats ON labs_labs.id = labs_labs_stats.lab_id
        LEFT JOIN labs_stats ON labs_stats.group_id = labs_labs_stats.stat_id
        GROUP BY 
            labs_labs.id
        ORDER BY 
            university_buildings.name ASC, 
            university_buildings_rooms.room ASC
    ");

    foreach($results as $key => $item) {
        $equipment = explode(',', $item->peripherals);
        $icons = explode(',', $item->peripheral_icons);
        $peripherals = array_combine($icons, $equipment);
        $software = explode(',', $item->software);
        $software_ids = [];
        foreach($software as $group) {
            $arr = explode(';',$group);
            if(!empty($arr[0]) && !empty($arr[1])) {
                $software_ids[] = $arr[0] . '_' . $arr[1];
            }
        }
        $item->software_ids = $software_ids;
        $seats_available = get_seats_available($item,$hours_available);
        $item->peripherals = $peripherals;
        $item->seats_available = $seats_available;
        $results[$key] = $item;
    }

    return $results;
}

function get_seats_available($item,$hours_available)
{
    if (!is_open($item,$hours_available)) {
        return "Closed";
    }

    $seats_available
        = $item->available_count > 0
        ? $item->available_count + $item->off_count . ' of ' . $item->total_count
        : 'Unavailable';

    return $seats_available;
}

// returns all availabilites with hours
function get_hours_available() {
    // $availquery = new \wpdb(TOOLS_DB_USER, TOOLS_DB_PASSWORD, TOOLS_DB_NAME, TOOLS_DB_HOST);
    // $availability = $availquery->get_results("SELECT 
    //         labs_availability.*, 
    //         labs_hours.open,
    //         labs_hours.close 
    //     FROM labs_availability 
    //     LEFT JOIN labs_hours 
    //         ON labs_availability.day_id = labs_hours.day_id
    //         AND labs_availability.lab_id = labs_hours.lab_id
    // ");

    $availability = \Database\Tools::query("
        SELECT 
            labs_availability.*, 
            labs_hours.open,
            labs_hours.close 
        FROM labs_availability 
        LEFT JOIN labs_hours 
            ON labs_availability.day_id = labs_hours.day_id
            AND labs_availability.lab_id = labs_hours.lab_id
    ");

    $data = [];
    foreach ($availability as $avail) {
        $data[$avail->lab_id . '-' . $avail->day_id] = $avail;
    }
    return $data;
}

function is_open($item,$hours_available) {

    // Determine current day of week
    $today = date('N');
    $i = $today - 1;

    // lookup this item in consolidated data
    $this_lab_availablility = $hours_available[$item->lab_id . '-' . $i];

    // If it is closed today return false
    if ($this_lab_availablility->is_closed) {
        return false;
    }

    // If it is open 24 hours today then return true.
    if ($this_lab_availablility->is_24_hours) {
        return true;
    }

    $now = new \DateTime();
    $open = new \DateTime($this_lab_availablility->open);
    $close = new \DateTime($this_lab_availablility->close);

    // if close is before open then assume the close time is for tomorrow and advance the datetime 
    // object a day.
    if ($close < $open) {
        $close->modify('+1 day');
    }
    
    // If now is inside of these hours then return true.
    if (($open < $now) && ($now < $close)) {
        return true;
    } 

    // Since the current time is not within todays hours return false
    return false;
}

?>