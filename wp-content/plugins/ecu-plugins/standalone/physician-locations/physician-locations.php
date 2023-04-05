<?php
add_action( 'init', 'physician_location_cpt' );
function physician_location_cpt() {
    register_post_type( 'physician_location', [
        'labels'            =>      [
            'name'                  =>      'Locations',
            'singular_name'         =>      'Location',
            'menu_name'             =>      'Locations',
            'add_new_item'          =>      'Add New Location',
            'new_item'              =>      'New Location',
            'edit_item'             =>      'Edit Location',
            'view_item'             =>      'View Location',
            'all_items'             =>      'All Locations',
            'search_items'          =>      'Search Locations',
            'not_found'             =>      'No locations found.',
            'not_found_in_trash'    =>      'No locations found in Trash'
        ],
        'description'           =>      'Physician locations custom post type',
        'menu_icon'             =>      'dashicons-location-alt',
        'public'                =>      true,
        'publicly_queryable'    =>      false,
        'rewrite'               =>      false,
        'has_archive'           =>      false,
        'hierarchical'          =>      true,
        'supports'              =>      [ 'title', 'page-attributes' ]
    ]);
}

function physician_locations( ) {
    ob_start();

    if(!isset($_GET['location'])){
        $ls = get_posts([
            'post_type'         =>      'physician_location',
            'post_status'       =>      'publish',
            'posts_per_page'    =>      -1,
            'orderby'           =>      'title',
            'order'             =>      'ASC'
        ]);

        /**
         * Orders locations alphabetically by parent -> child relationship
         */

        $locations = [];
        foreach($ls as $l){
            if(!($l->post_parent)){
                $locations[$l->ID] = [$l];
            } else {
                if(array_key_exists($l->post_parent, $locations)){
                    $locations[$l->post_parent][] = $l;
                } else {
                    $locations[$l->ID] = [$l];
                }
            }
        }

        foreach($locations as $ls){
            $n = 0; foreach($ls as $l){
                if(!$n)
                    echo "<a href='?location={$l->ID}'><strong>{$l->post_title}</strong></a>";
                if($n === 1)
                    echo "<ul class='list-unstyled'>";
                if($n > 0)
                    echo "<li class='pl-4'><a href='?location={$l->ID}'>{$l->post_title}</a></li>";
                if($n === (count($ls) - 1))
                    echo "</ul>";
            $n++; }
        }
    } else {
        $l_id = (int) $_GET['location'];
        if($l = get_post($l_id)){ ?>
            <h2 class="mb-3"><?php echo $l->post_title; ?></h2>
            <div class="row">
                <div class="col-md-6">
                    <?php if($address = $l->address): ?>
                        <p><strong>Address</strong><br /><?php echo $address; ?></p>
                    <?php endif; ?>
                    <?php if($phones = get_field('phone_numbers', $l->ID)):
                        echo "<p><strong>Phone:</strong><br />";
                        foreach($phones as $p){
                            if($p['phone'])
                                output_phone($p['phone']);
                            if($p['label'])
                                echo ' <small>(' . $p['label'] . ')</small><br />';
                        }
                        echo "</p>";
                    endif; ?>
                    <?php if($faxs = get_field('fax', $l->ID)):
                        echo "<p><strong>Fax:</strong><br />";
                        foreach($faxs as $f){
                            if($f['fax'])
                                output_phone($f['fax']);
                            if($f['label'])
                                echo ' <small>(' . $f['label'] . ')</small><br />';
                        }
                        echo "</p>";
                    endif; ?>
                    <?php if($hours = $l->hours): ?>
                        <p><strong>Hours</strong><br /><?php echo $hours; ?></p>
                    <?php endif; ?>
                    <?php if($parking = $l->parking): ?>
                        <p><strong>Parking</strong><br /><?php echo $parking; ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?php if($image = get_field('image', $l->ID))
                        echo "<img src='{$image}' alt='$l->post_title' class='img-responsive' />";
                    ?>
                </div>
            </div>
            <?php  $children = get_posts([
                'post_type'         =>      'physician_location',
                'post_status'       =>      'publish',
                'posts_per_page'    =>      -1,
                'orderby'           =>      'title',
                'order'             =>      'ASC',
                'post_parent'       =>      $l->ID
            ]);
            if($l->location_content || $children){
                echo '<hr />';
                if($l->location_content) echo $l->location_content;
                if($children){
                    echo '<div class="mt-3"><strong>Offices at this location:</strong><ul>';
                        foreach($children as $c){
                            echo "<li><a href='?location={$c->ID}'>{$c->post_title}</a></li>";
                        }
                    echo '</ul></div>';
                }
                echo '<hr />';
            }
                if($map = get_field('map', $l->ID)){
                    var_dump($map);
                    echo $map;
                }

                if($file = get_field('map_pdf', $l->ID))
                    echo "<a href='{$file}' aria-label='Download map of {$l->post_title}' target='_blank'>Download a PDF map of this location</a>";
            ?>
        <?php } else {
            echo "<p>Invalid location selected.</p>";
        }
    }

    $output = ob_get_contents();
    ob_end_clean();
    return "<div class='physician-locations mt-4'>" . $output . "</div>";
}
add_shortcode( 'physician-locations', 'physician_locations' );

function my_acf_init() {
	
	acf_update_setting('google_api_key', 'AIzaSyAF7wvOUyMoJ3Or0XJEyV2CON1nugVmRjM');
}

add_action('acf/init', 'my_acf_init');