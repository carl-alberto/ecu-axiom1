<?php

namespace OUR\CLASSROOMTECH;


// define shortcode
add_shortcode('ecu_classroom_tech_search', 'OUR\CLASSROOMTECH\render_table');
add_shortcode('ecu_classroom_tech_legend', 'OUR\CLASSROOMTECH\render_legend');

// load specific scripts and styles
add_action( 'wp_enqueue_scripts', 'OUR\CLASSROOMTECH\load_plugin_assets',10,0);

function load_plugin_assets() {
    global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ecu_classroom_tech_search') ) {
        // load global datatables assets
        \OUR\LABSTECHITCS\register_datatables();
        // load global datatables button assets
        \OUR\LABSTECHITCS\register_datatables_buttons();
        // load global selectwoo assets
        \OUR\LABSTECHITCS\register_selectwoo();
        // load plugin-specific scripts and styles
        adding_scripts();
    }
}

function adding_scripts() {
    wp_register_script('ecu_classroomtech_js', plugins_url( '/our-labs-tech-itcs/templates/classroom-tech/classroom-tech.js' ) , array('jquery'), '1.1', true);
    wp_enqueue_script('ecu_classroomtech_js');
    // add styles
    adding_styles();
}
function adding_styles() {
    wp_register_style('ecu_classroomtech_css', plugins_url( '/our-labs-tech-itcs/templates/classroom-tech/classroom-tech.css' ) );
    wp_enqueue_style('ecu_classroomtech_css');
    wp_register_style('ecu_spinner_css', plugins_url( '/our-labs-tech-itcs/assets/css/spinner.css') );
    wp_enqueue_style('ecu_spinner_css');
}

function render_table() {
    $results = get_filtered_results();
    $available_technology = get_available_technology();
    ob_start();
    require_once(dirname(__FILE__,2) . '/templates/classroom-tech/classroom-tech-table.tpl.php');
    return ob_get_clean();
}

function render_legend() {
    $available_technology = get_available_technology();
    ob_start();
    require_once(dirname(__FILE__,2) . '/templates/classroom-tech/classroom-tech-legend.tpl.php');
    return ob_get_clean();
}


// Get equipment for a room
// Get all buildings that are used by classroom tech
// SELECT Distinct university_buildings.id, university_buildings.name FROM classroomtech_rooms LEFT JOIN university_buildings_rooms ON classroomtech_rooms.university_buildings_rooms_id = university_buildings_rooms.id LEFT JOIN university_buildings ON university_buildings_rooms.building_id = university_buildings.id
// Get Room Types
// SELECT Distinct classroomtech_rooms_types.id, classroomtech_rooms_types.type FROM classroomtech_rooms_types LEFT JOIN classroomtech_rooms ON classroomtech_rooms.room_type_id = classroomtech_rooms_types.id

function get_available_technology() {
    // $query = new \wpdb(TOOLS_DB_USER, TOOLS_DB_PASSWORD, TOOLS_DB_NAME, TOOLS_DB_HOST);
    // $available_technology = $query->get_results("SELECT * FROM  classroomtech_equipment");
    $available_technology = \Database\Tools::query("
        SELECT *
        FROM  classroomtech_equipment
    ");
    return $available_technology;
}

function get_filtered_results() {

    // $rooms_equipment = new \wpdb(TOOLS_DB_USER, TOOLS_DB_PASSWORD, TOOLS_DB_NAME, TOOLS_DB_HOST);
    // $rooms_equipment_data = $rooms_equipment->get_results("SELECT
    //         classroomtech_rooms.id as room_id,
    //         classroomtech_equipment.id as equipment_id,
    //         classroomtech_equipment.name as equipment_name,
    //         classroomtech_equipment.icon as equipment_icon,
    //         classroomtech_equipment.tooltip as equipment_tooltip,
    //         classroomtech_equipment.url as equipment_url
    //     FROM classroomtech_rooms
    //     LEFT JOIN classroomtech_rooms_equipment ON classroomtech_rooms_equipment.classroomtech_rooms_id = classroomtech_rooms.id
    //     LEFT JOIN classroomtech_equipment ON classroomtech_rooms_equipment.classroomtech_equipment_id = classroomtech_equipment.id
    //     ");

    $rooms_equipment_data = \Database\Tools::query("
        SELECT
            classroomtech_rooms.id as room_id,
            classroomtech_equipment.id as equipment_id,
            classroomtech_equipment.name as equipment_name,
            classroomtech_equipment.icon as equipment_icon,
            classroomtech_equipment.tooltip as equipment_tooltip,
            classroomtech_equipment.url as equipment_url
        FROM classroomtech_rooms
        LEFT JOIN classroomtech_rooms_equipment ON classroomtech_rooms_equipment.classroomtech_rooms_id = classroomtech_rooms.id
        LEFT JOIN classroomtech_equipment ON classroomtech_rooms_equipment.classroomtech_equipment_id = classroomtech_equipment.id
    ");

    $data = [];
    foreach($rooms_equipment_data as $item) {
        $data[$item->room_id][] = $item;
    }

    // $search = new \wpdb(TOOLS_DB_USER, TOOLS_DB_PASSWORD, TOOLS_DB_NAME, TOOLS_DB_HOST);
    // $results = $search->get_results("SELECT
    //         classroomtech_rooms.id,
    //         university_buildings.name as building_name,
    //         university_buildings.code as building_code,
    //         TRIM(LEADING '0' FROM university_buildings_rooms.room) as room,
    //         university_buildings_rooms.capacity as capacity,
    //         classroomtech_rooms_types.type as room_type
    //     FROM classroomtech_rooms
    //     LEFT JOIN classroomtech_rooms_types ON classroomtech_rooms.room_type_id = classroomtech_rooms_types.id
    //     LEFT JOIN university_buildings_rooms ON classroomtech_rooms.university_buildings_rooms_id = university_buildings_rooms.id
    //     LEFT JOIN university_buildings ON university_buildings_rooms.building_id = university_buildings.id
    //     ORDER BY
    //         university_buildings.name,
    //         university_buildings_rooms.room
    //     ");

    $results = \Database\Tools::query("
        SELECT
            classroomtech_rooms.id,
            university_buildings.name as building_name,
            university_buildings.code as building_code,
            TRIM(LEADING '0' FROM university_buildings_rooms.room) as room,
            university_buildings_rooms.capacity as capacity,
            classroomtech_rooms_types.type as room_type
        FROM classroomtech_rooms
        LEFT JOIN classroomtech_rooms_types ON classroomtech_rooms.room_type_id = classroomtech_rooms_types.id
        LEFT JOIN university_buildings_rooms ON classroomtech_rooms.university_buildings_rooms_id = university_buildings_rooms.id
        LEFT JOIN university_buildings ON university_buildings_rooms.building_id = university_buildings.id
        ORDER BY
            university_buildings.name,
            university_buildings_rooms.room
    ");

    foreach($results as $key => $item) {
        $item->equipment = !empty($data[$item->id]) ? $data[$item->id] : [];
        $results[$key] = $item;
    }

    return $results;
}

function get_seats_available($item) {
    $seats_available = $item->available_count > 0 ? $item->available_count + $item->off_count . ' of ' . $item->total_count : 'Closed';
    return $seats_available;
}

?>
