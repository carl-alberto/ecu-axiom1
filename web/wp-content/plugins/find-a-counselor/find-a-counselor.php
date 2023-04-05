<?php

/*
 * Plugin Name: Find A Counselor
 * Plugin URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Description: [find_a_counselor /] Adds counselor custom post type and shortcode to search and filter counselors.
 * Version:     1.0.0
 * Author:      ATWebDev
 * Author URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 */

/*
 *  Registers post types, adds terms and flushes permalinks on plugin initialization
 *  Flushes permalinks on plugin deactivation
 */
register_activation_hook( __FILE__, 'init_counselor_cpt' );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
function init_counselor_cpt() {
    register_ecu_counselors();
    register_location_terms();
	flush_rewrite_rules();
}

/*
 * Adds support for post featured images 
 */
add_theme_support('post-thumbnails', ['counselor']);

/*
 * Registers counselor custom post type and counselor location custom taxonomy 
 */
add_action( 'init', 'register_ecu_counselors' );
function register_ecu_counselors() {
    register_post_type( 
        'counselor', 
        [
            'labels' => [
                'name'                  => 'Counselors',
                'singular_name'         => 'Counselor',
                'menu_name'             => 'Counselors',
                'name_admin_bar'        => 'Counselor',
                'add_new'               => 'Create Counselor',
                'add_new_item'          => 'Create New Counselor',
                'new_item'              => 'New Counselor',
                'edit_item'             => 'Edit Counselor',
                'view_item'             => 'View Counselor',
                'all_items'             => 'All Counselors',
                'search_items'          => 'Search Counselors',
                'parent_item_colon'     => 'Parent Counselors:',
                'not_found'             => 'Counselor Not Found',
                'not_found_in_trash'    => 'Counselor Not Found in Trash'
            ],
            'description'           => 'Custom post type for find a counselor shortcode',
            'public'                => true,
            'exclude_from_search'   => false,
            'show_ui'               => true,
            'supports'              => [ 'title', 'editor', 'thumbnail' ],
            'has_archive'           => false,
            'menu_icon'             => 'dashicons-id',
        ]
    );

    register_taxonomy( 
        'counselor-location', 
        'counselor', 
        [
            'labels'            => [
                'name'                      => 'Locations',
                'singular_name'             => 'Location',
                'search_items'              => 'Search Locations',
                'all_items'                 => 'All Locations',
                'parent_item'               => 'Parent Location',
                'parent_item_colon'         => 'Parent Location: ',
                'edit_item'                 => 'Edit Location',
                'update_item'               => 'Update Location',
                'add_new_item'              => 'Add New Location',
                'new_item_name'             => 'New Location Name',
                'menu_name'                 => 'Locations',
                'add_or_remove_items'       => 'Add or remove locations',
                'choose_from_most_used'     => 'Choose from the most used locations',
                'not_found'                 => 'No locations found',
            ],
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_rest' 		=> false,
            'show_in_nav_menus' => false,
            'show_admin_column' => true,
            'query_var'         => false,
            'rewrite'           => false,
        ]
    );

    register_taxonomy( 
        'counselor-school', 
        'counselor', 
        [
            'labels'            => [
                'name'                      => 'High Schools',
                'singular_name'             => 'High School',
                'search_items'              => 'Search High Schools',
                'all_items'                 => 'All High Schools',
                'parent_item'               => 'Parent High School',
                'parent_item_colon'         => 'Parent High School: ',
                'edit_item'                 => 'Edit High School',
                'update_item'               => 'Update High School',
                'add_new_item'              => 'Add New High School',
                'new_item_name'             => 'New High School Name',
                'menu_name'                 => 'High Schools',
                'add_or_remove_items'       => 'Add or remove high schools',
                'choose_from_most_used'     => 'Choose from the most used high schools',
                'not_found'                 => 'No high schools found',
            ],
            'hierarchical'      => false,
            'show_ui'           => true,
            'show_in_rest' 		=> false,
            'show_in_nav_menus' => false,
            'show_admin_column' => true,
            'query_var'         => false,
            'rewrite'           => false,
        ]
    );
}

/*
 * Registers scripts and styles used for shortcode 
 */
add_action( 'wp_enqueue_scripts', 'enqueue_counselor_scripts' );
function enqueue_counselor_scripts(){
    wp_register_style( 'find_a_counselor_style', plugins_url( 'assets/find_a_counselor.css', __FILE__ ), false, '1.0' );
    if( is_singular('counselor') ) wp_enqueue_style( 'find_a_counselor_style' );

    wp_register_script( 'find_a_counselor_script', plugins_url( 'assets/find_a_counselor.js', __FILE__ ), false, '1.0' );
}

/*
 * Changes the placeholder text for new counselor 
 */
add_filter( 'enter_title_here', 'counselor_title' );
function counselor_title( $title ){
    $screen = get_current_screen();
    if  ( 'counselor' == $screen->post_type ) $title = 'Counselor Name';
    return $title;
}

/*
 * Adds meta box for meta fields on add/edit counselor screen 
 */
add_action( 'add_meta_boxes', 'counselor_meta_box' );
function counselor_meta_box(){
    add_meta_box( 'counselor_meta', 'Counselor Information', 'counselor_meta_callback', 'counselor', 'side', 'low' );
}
    function counselor_meta_callback() {
        include_once( 'views/counselor-meta.php' );
    }

/*
 * Saves custom meta fields to db on save 
 */
add_action( 'save_post', 'save_counselor', 10, 3 );
function save_counselor( $ID, $post, $update ) {
    if( get_post_type( $ID ) === 'counselor' ) {
        if( isset( $_POST['counselor_title'] ) ) update_post_meta( $ID, 'counselor_title', sanitize_text_field( $_POST['counselor_title'] ) );
        if( isset( $_POST['counselor_email'] ) ) update_post_meta( $ID, 'counselor_email', sanitize_text_field( $_POST['counselor_email'] ) );
        if( isset( $_POST['counselor_appointment'] ) ) update_post_meta( $ID, 'counselor_appointment', sanitize_text_field( $_POST['counselor_appointment'] ) );
        if( isset( $_POST['counselor_phone'] ) ) {
            $phone = preg_replace( '/[^0-9]/', '', sanitize_text_field( $_POST['counselor_phone'] ) );
            if( strlen( $phone) === 10 ){
                $phone = '(' . substr( $phone, 0, 3 ) . ') ' . substr( $phone, 3, 3 ) . '-' . substr( $phone, 6, 4 );
            }
            update_post_meta( $ID, 'counselor_phone', $phone );
        };
        if( isset( $_POST['counselor_type'] ) ) update_post_meta( $ID, 'counselor_type', sanitize_text_field( $_POST['counselor_type'] ) );
        if( isset( $_POST['default_counselor'] ) ) {
            update_post_meta( $ID, 'default_counselor', filter_var( $_POST['default_counselor'], FILTER_VALIDATE_BOOLEAN ) );
        } else {
            delete_post_meta( $ID, 'default_counselor' );
        };
    }
}

/*
 * Applies locally included single-counselor.php template to counselor post type
 */
add_filter( 'single_template', 'load_counselor_template' );
function load_counselor_template( $template ) {
    global $post;
    if ( 'counselor' === $post->post_type ) {
        return plugin_dir_path( __FILE__ ) . 'views/single-counselor.php';
    }
    return $template;
}

/*
 * Registers find a counselor shortcode 
 */
add_shortcode( 'find_a_counselor', 'counselor_search' );
function counselor_search( $atts) {
    wp_enqueue_script( 'find_a_counselor_script' );
    wp_enqueue_style( 'find_a_counselor_style' );
    $has_searched = false;
    if( $_GET['student_status'] && ( $_GET['counselor_county'] || $_GET['counselor_state'] ) ) {
        $has_searched = true;

        $student_status = sanitize_text_field( $_GET['student_status'] );
        $nc_student = sanitize_text_field( $_GET['nc_student'] );
        $counselor_county = sanitize_text_field( $_GET['counselor_county'] );
        $counselor_state = sanitize_text_field( $_GET['counselor_state'] );

        $counselors = get_posts([
            'post_type'     =>  'counselor',
            'numberposts'   =>  -1,
            'meta_key'      =>  'counselor_type',
            'meta_value'    =>  $student_status,
            'orderby'       =>  'title',
            'order'         =>  'asc',
            'tax_query'     =>  [
                [
                    'taxonomy'      =>  'counselor-location',
                    'field'         =>  'slug',
                    'terms'         =>  $counselor_county ? $counselor_county : $counselor_state
                ]
            ]
        ]);
    };
    $terms = get_terms( [
        'taxonomy'      => 'counselor-location',
        'hide_empty'    => false,
        'orderby'       =>  'slug',
        'order'         =>  'asc',
    ] );
    ob_start(); ?>
        <div id="ecu_find_a_counselor">
            <form method="GET" action="<?php echo get_the_permalink(); ?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student_status">What type of student are you?</label><br />
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label 
                                    data-name="student_status"
                                    class="btn btn-lg 
                                    <?php echo $student_status === 'freshman' ? 'active' : '' ?>">
                                    <input type="radio" name="student_status" value="freshman" 
                                    <?php echo $student_status === 'freshman' ? 'checked' : '' ?>> Freshman
                                </label>
                                <label 
                                    data-name="student_status"
                                    class="btn btn-lg 
                                    <?php echo $student_status === 'transfer' ? 'active' : '' ?>">
                                    <input type="radio" name="student_status" value="transfer" 
                                    <?php echo $student_status === 'transfer' ? 'checked' : '' ?>> Transfer
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nc_student">Do you live in North Carolina?</label><br />
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label 
                                    data-name="nc_student" 
                                    data-group="counselor_county_group" 
                                    class="btn btn-lg 
                                    <?php echo $nc_student === 'true' ? 'active' : '' ?>">
                                    <input type="radio" name="nc_student" value="true"
                                    <?php echo $nc_student === 'true' ? 'checked' : '' ?>> Yes
                                </label>
                                <label 
                                    data-name="nc_student" 
                                    data-group="counselor_state_group" 
                                    class="btn btn-lg 
                                    <?php echo $nc_student === 'false' ? 'active' : '' ?>">
                                    <input type="radio" name="nc_student" value="false" 
                                    <?php echo $nc_student === 'false' ? 'checked' : '' ?>> No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Yes -->
                        <div id="counselor_county_group" class="form-group <?php if( $has_searched && !$counselor_county ) echo 'd-none'; ?>">
                            <label for="counselor_county">Select your county:</label><br />
                                <select 
                                    id="counselor_county" 
                                    name="counselor_county" 
                                    class="form-control" 
                                    <?php if( !$has_searched || ( $has_searched && !$counselor_county ) ) echo 'disabled="disabled"'; ?>>
                                    <?php foreach( $terms as $term ){
                                        if( $term->parent ) {
                                            $selected = $counselor_county === $term->slug ? 'selected' : '';
                                            echo "<option value='{$term->slug}' {$selected}>{$term->name}</option>";
                                        }
                                    }; ?>
                            </select>
                        </div>

                        <!-- No -->
                        <div id="counselor_state_group" class="form-group <?php if( !$has_searched || !$counselor_state ) echo 'd-none'; ?>">
                            <label for="counselor_state">Select your state:</label><br />
                            <select 
                                id="counselor_state" 
                                name="counselor_state" 
                                class="form-control"
                                <?php if( !$has_searched || ( $has_searched && !$counselor_state) ) echo 'disabled="disabled"'; ?>>
                                <?php foreach( $terms as $term ){
                                    if( !$term->parent ) {
                                        $selected = $counselor_state === $term->slug ? 'selected' : '';
                                        echo "<option value='{$term->slug}' $selected>{$term->name}</option>";
                                    }
                                }; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row align-center">
                    <div class="col-md-4">
                        <button 
                            class="btn btn-lg btn-block nc_student student_status" 
                            type="submit" 
                            id="counselor_search" 
                            <?php if(!$has_searched): ?> disabled="disabled"<?php endif; ?>>
                            Search
                        </button>
                    </div>
                </div>
            </form>
            <?php if( $has_searched ):
                echo '<hr />';

                if( empty( $counselors ) ) {
                    $counselors = get_posts([
                        'post_type'     =>  'counselor',
                        'numberposts'   =>  -1,
                        'orderby'       =>  'title',
                        'order'         =>  'asc',
                        'meta_query'    =>  [
                            'relation'      =>  'AND',
                            [
                                'key'       =>  'counselor_type',
                                'value'     =>  $student_status,
                                'compare'   =>  '='
                            ],
                            [
                                'key'       =>  'default_counselor',
                                'value'     =>  '1',
                                'compare'   =>  '=' 
                            ]
                        ]
                    ]);
                }
                
                if( empty( $counselors ) ) {
                    echo '<p>We were unable to find any results that matched your search criteria. Please refine your search and try again.</p>';
                } elseif( count( $counselors ) === 1) {
                    echo '<p>Your search returned <strong>1</strong> result.';
                } else {
                    echo '<p>Your search returned <strong>' . count( $counselors ) . '</strong> result' . (count( $counselors ) > 1 ? 's' : '') . '. To determine your counselor, check the counselor profiles for assigned high schools. If you do not see your high school listed, feel free to contact either counselor.</p>';
                };

                echo '<div id="counselor-search-results"><div class="row">';
                foreach( $counselors as $counselor ): $id = $counselor->ID; $meta = get_post_meta( $id );?>
                    <div class="col-lg-6">
                        <div class="counselor">
                            <?php if( has_post_thumbnail( $id ) ):
                                echo get_the_post_thumbnail( $id, 'medium', ['class' => 'img-fluid mr-3']);
                            endif; ?>
                            <div class="counselor-meta">
                                <h2>
                                    <?php echo $counselor->post_title; ?><?php if( $meta['counselor_title'][0] !== '' ): ?>, <span><?php echo $meta['counselor_title'][0]; ?></span><?php endif; ?>
                                </h2>
                                
                                <?php if( $meta['counselor_email'][0] !== '' ): ?>
                                    <p class="email"><?php echo $meta['counselor_email'][0]; ?></p>
                                <?php endif; ?>
                                <?php if( $meta['counselor_phone'][0] !== '' ): ?>
                                    <p class="email"><?php echo $meta['counselor_phone'][0]; ?></p>
                                <?php endif; ?>
                                <div class="counselor_buttons">
                                    <?php if( isset($meta['counselor_appointment'][0]) && $meta['counselor_appointment'][0] !== '' ): ?>
                                        <a href="<?php echo $meta['counselor_appointment'][0]; ?>" aria-title="Schedule appointment with <?php echo $counselor->post_title; ?>'" class="btn btn-sm">Schedule Appointment</a>
                                    <?php endif; ?>
                                    <a href="<?php echo get_the_permalink( $id ); ?>" aria-title="View <?php echo $counselor->post_title; ?>'s profile.'" class="btn btn-sm">View Profile <span class="fa fa-chevron-right ml-2" aria-hidden="true"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;
                echo '</div></div>';
            endif; ?>
        </div>

    <?php $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

/*
 * Registers US states and NC counties as terms 
 */
function register_location_terms() {
    $states = [
        'al'    =>  'Alabama',
        'ak'    =>  'Alaska',
        'az'    =>  'Arizona',
        'ar'    =>  'Arkansas',
        'ca'    =>  'California',
        'co'    =>  'Colorado',
        'ct'    =>  'Connecticut',
        'de'    =>  'Delaware',
        'fl'    =>  'Florida',
        'ga'    =>  'Georgia',
        'hi'    =>  'Hawaii',
        'id'    =>  'Idaho',
        'il'    =>  'Illinois',
        'in'    =>  'Indiana',
        'ia'    =>  'Iowa',
        'ks'    =>  'Kansas',
        'ky'    =>  'Kentucky',
        'la'    =>  'Louisiana',
        'me'    =>  'Maine',
        'md'    =>  'Maryland',
        'ma'    =>  'Massachusetts',
        'mi'    =>  'Michigan',
        'mn'    =>  'Minnesota',
        'ms'    =>  'Mississippi',
        'mo'    =>  'Missouri',
        'mt'    =>  'Montana',
        'ne'    =>  'Nebraska',
        'nv'    =>  'Nevada',
        'nh'    =>  'New Hampshire',
        'nj'    =>  'New Jersey',
        'nm'    =>  'New Mexico',
        'ny'    =>  'New York',
        'nc'    =>  'North Carolina',
        'nd'    =>  'North Dakota',
        'oh'    =>  'Ohio',
        'ok'    =>  'Oklahoma',
        'or'    =>  'Oregon',
        'pa'    =>  'Pennsylvania',
        'ri'    =>  'Rhode Island',
        'sc'    =>  'South Carolina',
        'sd'    =>  'South Dakota',
        'tn'    =>  'Tennessee',
        'tx'    =>  'Texas',
        'ut'    =>  'Utah',
        'vt'    =>  'Vermont',
        'va'    =>  'Virginia',
        'wa'    =>  'Washington',
        'wv'    =>  'West Virginia',
        'wi'    =>  'Wisconsin',
        'wy'    =>  'Wyoming'
    ];

    if( count( get_terms('counselor-location') ) === 0 ){
        foreach( $states as $abbr => $state ){
            wp_insert_term( $state, 'counselor-location', [ 'slug' => $abbr ] );
        }
    }

    $counties = [
        'Alamance', 
        'Alexander', 
        'Alleghany', 
        'Anson', 
        'Ashe', 
        'Avery', 
        'Beaufort', 
        'Bertie', 
        'Bladen', 
        'Brunswick', 
        'Buncombe', 
        'Burke', 
        'Cabarrus', 
        'Caldwell', 
        'Camden', 
        'Carteret', 
        'Caswell', 
        'Catawba', 
        'Chatham', 
        'Cherokee', 
        'Chowan', 
        'Clay', 
        'Cleveland', 
        'Columbus', 
        'Craven', 
        'Cumberland', 
        'Currituck', 
        'Dare', 
        'Davidson', 
        'Davie', 
        'Duplin', 
        'Durham', 
        'Edgecombe', 
        'Forsyth', 
        'Franklin', 
        'Gaston', 
        'Gates', 
        'Graham', 
        'Granville', 
        'Greene', 
        'Guilford', 
        'Halifax', 
        'Harnett', 
        'Haywood', 
        'Henderson', 
        'Hertford', 
        'Hoke', 
        'Hyde', 
        'Iredell', 
        'Jackson', 
        'Johnston', 
        'Jones', 
        'Lee', 
        'Lenoir', 
        'Lincoln', 
        'McDowell', 
        'Macon', 
        'Madison', 
        'Martin', 
        'Mecklenburg', 
        'Mitchell', 
        'Montgomery', 
        'Moore', 
        'Nash', 
        'New Hanover', 
        'Northampton', 
        'Onslow', 
        'Orange', 
        'Pamlico', 
        'Pasquotank', 
        'Pender', 
        'Perquimans', 
        'Person', 
        'Pitt', 
        'Polk', 
        'Randolph', 
        'Richmond', 
        'Robeson', 
        'Rockingham', 
        'Rowan', 
        'Rutherford', 
        'Sampson', 
        'Scotland', 
        'Stanly', 
        'Stokes', 
        'Surry', 
        'Swain', 
        'Transylvania', 
        'Tyrrell', 
        'Union', 
        'Vance', 
        'Wake', 
        'Warren', 
        'Washington', 
        'Watauga', 
        'Wayne', 
        'Wilkes', 
        'Wilson', 
        'Yadkin', 
        'Yancey'
    ];

    $nc = get_term_by( 'slug', 'nc', 'counselor-location' );

    if( !$nc ) return; 

    if( count( get_term_children( $nc->term_id, 'counselor-location' ) ) > 0 ) return;

    foreach( $counties as $county ){
        wp_insert_term( $county, 'counselor-location', [ 'parent' => $nc->term_id ] );
    }
}