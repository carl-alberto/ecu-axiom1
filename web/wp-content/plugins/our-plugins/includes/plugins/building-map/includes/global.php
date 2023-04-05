<?php

namespace OUR_PLUGINS;

/**
 * Renders the google map
 *
 * @param  array $options The Options from the widget / shortcode
 * @return string  The html / javascript for the map
 */
function render_map($options) {

    $map_item = \Database\Tools::query("
        SELECT * FROM homepage_tools.maps_items
        LEFT JOIN homepage_tools.maps_icons on maps_icons.id = maps_items.icon_id
        WHERE maps_items.id = ?", array($options['item']));

    if(empty($map_item)) {
        return "There was an error loading the map!";
    }

    // Don't bother with the map as it won't show in the editor for some reason.
    if(is_admin()) {
        return "<div style='height:180px;background-color:#6C6D68;color:#FFF;font-size:2em;'><div style='position:absolute;top:50%;left:50%;margin-right:-50%;transform:translate(-50%, -50%);text-align:center;'>Map Will Show Here</div></div>";
    }

    $building = \Database\Tools::query("
        SELECT university_buildings.code, university_buildings.name
        FROM homepage_tools.university_buildings
        WHERE university_buildings.id = ?
    ", array($map_item->child_id));

    $map_shapes = \Database\Tools::query("
        SELECT *
        FROM homepage_tools.maps_shapes
        WHERE item_id = ?
            ", array($options['item']));

    $temp_item = new \stdClass();
    $temp_item->id = $map_item->id;
    $temp_item->type_id = $map_item->type_id;
    $temp_item->name = $building->name;

    $temp_icon = new \stdClass();
    $temp_icon->stroke_color = $map_item->stroke_color;
    $temp_icon->fill_color = $map_item->fill_color;
    $temp_icon->image = $map_item->image;

    $temp_item->icon = $temp_icon;

    $temp_shapes = array();
    foreach($map_shapes as $shape){
        $temp_shape = new \stdClass();
        $temp_shape->id = $shape->id;
        $temp_shape->type = $shape->type;

        $temp_points = array();

        $map_points = \Database\Tools::query("
            SELECT *
            FROM homepage_tools.maps_points
            WHERE shape_id = ?
        ", array($shape->id));

        $points = array();
        foreach($map_points as $point){
            $temp_point = new \stdClass();
            $temp_point->id = $point->id;
            $temp_point->lat = $point->lat;
            $temp_point->lng = $point->lng;
            $temp_point->radius = $point->radius;
            $points[] = array($point->lat, $point->lng);
            array_push($temp_points, $temp_point);
        }
        $temp_shape->points = $temp_points;

        array_push($temp_shapes, $temp_shape);
    }

    $temp_item->shapes = $temp_shapes;


    $data = json_encode($temp_item);

    $url = '';
    switch($options['link']) {
        case 'map':
            $url = "https://" . getenv('TOPSITE_ENV') . "/maps/" . urlencode($building->code);
            break;

        case 'directions':
            $url = '#';
            break;

        case 'building':
            $url = 'https://' . getenv('TOPSITE_ENV') . '/buildings/' . urlencode($building->code);
            break;
    }

    if(!empty($url)) {
        $str .= "<a id='link_" . esc_html($options['div_id']) . "' aria-hidden='true' href='" . $url . "'>";
    }

    $str .= "<div id='map_" . esc_html($options['div_id']) . "' aria-hidden='true'></div>";

     if(!empty($url)) {
        $str .= "</a>";
    }

    $str .= "
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script>
    <style type='text/css'>
        #map_" . esc_html($options['div_id']) . " { height:" . esc_html($options['height']) ."; }
        @media (min-width: 768px) { #map { height:200px; } }
        @media (min-width: 992px) { #map { height:250px; } }
        @media (min-width: 1200px) { #map { height:300px; } }
    </style>

    <script async defer type='text/javascript'>

        $( document ).ready(
            function () {
                // Create a map object and specify the DOM element for display.
                var map = new google.maps.Map(document.getElementById('map_" . esc_html($options['div_id']) . "'), {
                    center: new google.maps.LatLng(35.607329, -77.366581),
                    scrollwheel: false,
                    zoom: " . absint($options['zoom']) .",
                    disableDefaultUI: true,
                    draggable: false,
                    disableDoubleClickZoom: true,
                    styles: [
                        { 'featureType': 'water', 'elementType': 'all', 'stylers': [ { 'hue': '#76aee3' }, { 'saturation': 38 }, { 'lightness': -11 }, { 'visibility': 'on' } ] },
                        { 'featureType': 'road.highway', 'elementType': 'all', 'stylers': [ { 'hue': '#8dc749' }, { 'saturation': -47 }, { 'lightness': -17 }, { 'visibility': 'on' } ] },
                        { 'featureType': 'poi.park', 'elementType': 'all', 'stylers': [ { 'hue': '#c6e3a4' }, { 'saturation': 17 }, { 'lightness': -2 }, { 'visibility': 'on' } ] },
                        { 'featureType': 'road.arterial', 'elementType': 'all', 'stylers': [ { 'hue': '#cccccc' }, { 'saturation': -100 }, { 'lightness': 13 }, { 'visibility': 'on' } ] },
                        { 'featureType': 'administrative.land_parcel', 'elementType': 'all', 'stylers': [ { 'hue': '#5f5855' }, { 'saturation': 6 }, { 'lightness': -31 }, { 'visibility': 'on' } ] },
                        { 'featureType': 'road.local', 'elementType': 'all', 'stylers': [ { 'hue': '#ffffff' }, { 'saturation': -100 }, { 'lightness': 100 }, { 'visibility': 'simplified' } ] },
                        { 'featureType': 'water', 'elementType': 'all', 'stylers': [] }
                    ]
                });

                var data = " . $data . ";
                var bounds = new google.maps.LatLngBounds();
                for (j=0;j<=data.shapes.length-1;j++){
                    //FOR EACH SHAPE
                    var shape = data.shapes[j];

                    switch(shape.type) {
                        // Buildings must always be polygon
                        case 'polygon':
                            var points = [];
                            for (k=0;k<=shape.points.length-1;k++){
                                points.push({lat: parseFloat(shape.points[k].lat), lng: parseFloat(shape.points[k].lng) });
                            }
                            var shape = new google.maps.Polygon({
                                path: points,
                                strokeColor: data.icon.stroke_color,
                                fillColor: data.icon.fill_color,
                                fillOpacity: 0.35,
                                strokeWeight:2,
                                ecudetails: data,
                                type: 'polygon',
                            });
                            for (j=0;j<=shape.getPath().length-1;j++){
                                bounds.extend(shape.getPath().getArray()[j]);
                            }

                            shape.setMap(map);

                            break;

                        }
                }";

                if($url == '#') {

                    $str .= "jQuery('#link_" . esc_html($options['div_id']) . "').attr('href','https://www.google.com/maps?daddr='+ bounds.getCenter().lat() + ',' + bounds.getCenter().lng());";
                }
        $str .= "
                map.setCenter({lat:bounds.getCenter().lat(), lng:bounds.getCenter().lng()});

            }
        );

    </script>";

    return do_shortcode($str);
}
