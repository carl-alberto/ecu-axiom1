<?php
add_action( 'init', 'physician_cpt' );
function physician_cpt() {
    register_post_type( 'physician', [
        'labels'            =>      [
            'name'                  =>      'Physicians',
            'singular_name'         =>      'Physician',
            'menu_name'             =>      'Physicians',
            'add_new_item'          =>      'Add New Physician',
            'new_item'              =>      'New Physician',
            'edit_item'             =>      'Edit Physician',
            'view_item'             =>      'View Physician',
            'all_items'             =>      'All Physicians',
            'search_items'          =>      'Search Physicians',
            'not_found'             =>      'No physicians found.',
            'not_found_in_trash'    =>      'No physicians found in Trash'
        ],
        'description'           =>      'Physician custom post type',
        'menu_icon'             =>      'dashicons-id',
        'public'                =>      true,
        'publicly_queryable'    =>      false,
        'rewrite'               =>      false,
        'has_archive'           =>      false,
        'hierarchical'          =>      false,
        'supports'              =>      [ 'title' ]
    ]);
}

add_action( 'init', 'specialty_taxonomy' );
function specialty_taxonomy() {
	register_taxonomy( 'specialty', [ 'physician' ], [
        'labels'            => [
            'name'              =>  'Specialties',
            'singular_name'     =>  'Specialty',
            'search_items'      =>  'Search Specialties',
            'all_items'         =>  'All Specialties',
            'edit_item'         =>  'Edit Specialty',
            'update_item'       =>  'Update Specialty',
            'add_new_item'      =>  'Add New Specialty',
            'menu_name'         =>  'Specialties',
            'not_found'         =>  'No specialties found.'
        ],
        'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_rest' 		=> false,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => false,
    ]);
}

function physician_search( ) {
    ob_start(); 
    ?>
    <div class="physician_search">
        <div class="row">
            <div class="col-md-6">
                <form>
                    <div class="form-group">
                        <label for="q">Search by doctor's name:</label>
                        <div class="input-group">
                            <?php $q = sanitize_text_field($_GET['q']); ?>
                            <input type="text" name="q" placeholder="Search" value="<?php echo $q; ?>" aria-label="Search" class="form-control">
                            <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit">Search</button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <form id="specialty-form">
                    <div class="form-group">
                        <label for="specialty">Search by specialty:</label>
                        <select class="form-control" id="specialty" name="term">
                            <?php $terms = get_terms( [
                                'taxonomy'  => 'specialty',
                                'count'     =>  true
                            ] );
                            $qterm = sanitize_text_field( $_GET['term'] );?>
                            <?php if(!$qterm): ?>
                            <option value="" selected="selected">Select Specialization</option>
                            <?php endif; ?>
                            <?php foreach($terms as $term): ?>
                                <option value="<?php echo $term->slug; ?>"
                                <?php if($qterm === $term->slug): ?>
                                selected="selected"
                                <?php endif; ?>>
                                <?php echo "{$term->name} ($term->count)"; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <a href="http://local.ecu.edu/physician-search/?all">or view a list of all ECU Physicians doctors</a>

        <?php
        if( isset($_GET['q']) || isset($_GET['all']) || $qterm):
            echo '<hr />';

            $args = [
                'post_type'         => 'physician',
                'posts_per_page'    => -1,
                'post_status'       => 'publish',
                'orderby'           => 'name', 
                'order'             => 'ASC',
            ];
            if( $_GET['q'] ):
                $args['query']  = $q;
                add_filter( 'posts_where', 'physician_match', 10, 2 );
                $wp_query = new WP_Query( $args );
                remove_filter( 'posts_where', 'physician_match', 10, 2 );
                wp_reset_postdata();
                $posts = $wp_query->posts;
                $heading = "Your search for <strong>{$q}</strong> returned the following " . count($posts) . " doctor";
                $heading .= count($posts) > 1 ? 's.' : '.';
            elseif( isset($_GET['all']) ):
                $posts = get_posts( $args );
                $heading = "The following is a complete list of all " . count($posts) . " doctors with ECU Physicians.";
            elseif( $qterm ):
                $args[ 'tax_query' ] = [
                    [
                        'taxonomy'      =>  'specialty',
                        'field'         =>  'slug',
                        'terms'         =>  $qterm
                    ]
                ];
                $posts = get_posts( $args );
                $term = get_term_by('slug', $qterm, 'specialty');
                $heading = "Your search for doctors specializing in <strong>{$term->name}</strong> returned " . count($posts).
                $heading .= count($posts) > 1 ? ' matches.' : ' match.';
            endif;
            if(count($posts) > 0):
                echo '<p>' . $heading . '</p>';
                $chunks = array_chunk($posts, (ceil(count($posts) / 4)));
                    $sections = [];
                    echo '<div class="row">';
                    foreach($chunks as $chunk){
                        echo '<div class="col-md-3"><ul class="m-0 p-0 list-unstyled">';
                        foreach($chunk as $post){
                            echo "<li class='mt-2 '><a href='?physician={$post->ID}'>{$post->post_title}</a></li>";
                        }
                        echo '</ul></div>';
                    }
                    echo '</div>';
                
            else:
                echo '<p>No physicians found, please refine your query.';
            endif;
        elseif( isset($_GET['physician']) ):
            echo '<hr />';
            $post = get_posts( [
                'post_type'         => 'physician',
                'posts_per_page'    => 1,
                'post_status'       => 'publish',
                'include'          =>  (int) $_GET['physician']
            ]);
            if(!$post){
                echo '<p>Invalid physician ID. Please refine your search.</p>';
            } else {  $meta = get_post_meta($post[0]->ID); ?>
                <?php physician_name( $meta ); ?>
                <?php physician_image( $meta ); ?>
                <div class="meta">
                    <?php if($phone = $meta['phone'][0]): ?>
                        <p>
                            <strong>Phone: </strong> <?php output_phone($phone); ?>
                        </p>
                    <?php endif;
                    if($fax = $meta['fax'][0]): ?>
                        <p class="mt-2">
                            <strong>Fax: </strong> <?php output_phone($fax); ?>
                        </p>
                    <?php endif;
                    if($specialties = wp_get_post_terms($post[0]->ID, 'specialty')):
                        echo '<div class="clearfix"></div><strong class="mt-2 d-block">Specialties</strong>';
                        $output = '';
                        foreach($specialties as $s){
                            $output .= $s->name . ', ';
                        }
                        echo '<p>' . rtrim($output, ', ') . '</p>';
                    endif;
                    if($interests = $meta['clinical_interests'][0]):
                        echo "<strong class='mt-2 d-block'>Clinical Interests</strong><p>{$interests}</p>";
                    endif;
                    if($certifications = $meta['board_certifications'][0]):
                        echo "<strong class='mt-2 d-block'>Board Certifications</strong><p>{$certifications}</p>";
                    endif;
                    if($education = get_field('education', $post[0]->ID)): 
                        echo "<strong class='mt-2 d-block'>Education</strong>";
                        physician_education($education);
                    endif; ?>
                </div>
            <?php }
        endif;

        /**
         * Capture html and store in variable
         */

        $output = ob_get_contents() . '</div>';
        ob_end_clean();
    return $output;
}
add_shortcode( 'physician-search', 'physician_search' );

function physician_match( $where, &$wp_query ) {
    global $wpdb;
    if ( $q = wpdb::esc_like( $wp_query->get( 'query' ) ) ) {
        $where .= " AND ( {$wpdb->posts}.post_title LIKE '%{$q}%')";
    }
    return $where;
}

function physician_name( $meta ) {
    $first = $meta['first_name'][0];
    $middle = $meta['middle_name'][0];
    $last = $meta['last_name'][0];
    $title = $meta['title'][0];

    $output = $first . ' ';

    if($middle){
        $output .= strlen($middle) === 1 ? "{$middle}. " : "{$middle} ";
    }

    $output .= $last;

    if($title)
        $output .= ", {$title}";
    
    echo "<h2 class='mb-3'>{$output}</h2>";
};

function physician_education( $ed ){
    $f = []; $r = []; $i = []; $m = [];
    foreach($ed as $e){
        switch($e['type']){
            case 'Medical School':
                $m[] = [$e['type'], $e['institution']];
                break;
            case 'Internship':
                $i[] = [$e['type'], $e['institution']];
                break;
            case 'Residency':
                $r[] = [$e['type'], $e['institution']];
                break;
            case 'Fellowship':
                $f[] = [$e['type'], $e['institution']];
                break;
        }
    }
    $output = '';
    $education = array_merge($f, $r, $i, $m);
    foreach($education as $e){
        $output .= '<p>' . $e[0] . ': ' . $e[1] . '</p>';
    }
    echo $output;
}

function physician_image( $meta ){
    if($image = $meta['image'][0]){
        $image = wp_get_attachment_image_src($image, 'small');?>
        <img 
            src="<?php echo $image[0]; ?>" 
            class="img-responsive float-left mr-4" 
            alt="<?php echo $meta['first_name'][0] . ' ' . $meta['last_name'][0]; ?>" 
        />
<?php }
}

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5d37029518324',
        'title' => 'Physicians',
        'fields' => array(
            array(
                'key' => 'field_5d37029beb1bb',
                'label' => 'Image',
                'name' => 'image',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'url',
                'preview_size' => 'medium',
                'library' => 'uploadedTo',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            ),
            array(
                'key' => 'field_5d3702c5eb1bc',
                'label' => 'First Name',
                'name' => 'first_name',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '40',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5d3702d5eb1bd',
                'label' => 'Last Name',
                'name' => 'last_name',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '40',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5d3702dbeb1be',
                'label' => 'Title',
                'name' => 'title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '20',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => 'MD',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5d370328eb1bf',
                'label' => 'Phone',
                'name' => 'phone',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5d370336eb1c0',
                'label' => 'Fax',
                'name' => 'fax',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5d370344eb1c1',
                'label' => 'Clinical Interests',
                'name' => 'clinical_interests',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => 4,
                'new_lines' => '',
            ),
            array(
                'key' => 'field_5d370395eb1c2',
                'label' => 'Board Certifications',
                'name' => 'board_certifications',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => 4,
                'new_lines' => '',
            ),
            array(
                'key' => 'field_5d3703a2eb1c3',
                'label' => 'Education',
                'name' => 'education',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => '',
                'min' => 1,
                'max' => 0,
                'layout' => 'table',
                'button_label' => 'Add Education',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5d3703cceb1c4',
                        'label' => 'Type',
                        'name' => 'type',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'fellowship' => 'Fellowship',
                            'residency' => 'Residency',
                            'internship' => 'Internship',
                            'medical_school' => 'Medical School',
                        ),
                        'default_value' => array(
                            0 => 'medical_school',
                        ),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 1,
                        'ajax' => 0,
                        'return_format' => 'label',
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_5d370400eb1c5',
                        'label' => 'Institution',
                        'name' => 'institution',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '66',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'physician',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));
    
    endif;