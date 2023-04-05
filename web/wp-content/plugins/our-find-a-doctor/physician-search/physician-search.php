<?php

namespace OUR\FINDADOCTOR;

/*
 * Adds support for post featured images
 */
add_theme_support('post-thumbnails', ['physician']);

/*
 * Registers physician custom post type and specialty custom taxonomy
 */
add_action( 'init', __NAMESPACE__ . '\register_physicians', 10, 0 );
function register_physicians() {
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
        'show_in_rest'          =>      true,
        'rewrite'               =>      false,
        'has_archive'           =>      false,
        'hierarchical'          =>      false,
        'supports'              =>      [ 'title', 'thumbnail']
    ]);

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
		'show_in_rest' 		=> true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => false,
    ]);
}

/*
 * Registers styles used for shortcode
 */
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_physician_search_scripts', 10, 0 );
function enqueue_physician_search_scripts(){
    global $post;

    if( has_shortcode( $post->post_content, 'physician_search' ) ){
        wp_enqueue_style( 'physician-search', plugins_url( 'assets/physician-search.css', __FILE__ ), false, '1.0' );
        wp_enqueue_script( 'physician-search', plugins_url( 'assets/physician-search.js', __FILE__ ), [ 'jquery' ], false, '1.0' );
    }
}

/*
 * Changes the placeholder text for new physician
 */
add_filter( 'enter_title_here', __NAMESPACE__ . '\physician_title' );
function physician_title( $title ){
    $screen = get_current_screen();
    if  ( 'physician' == $screen->post_type ) $title = 'Physician Name';
    return $title;
}

/*
 * Adds meta box for meta fields on add/edit physician screen
 */
add_action( 'add_meta_boxes', __NAMESPACE__ . '\physician_meta_box', 10, 0 );
function physician_meta_box(){
    add_meta_box( 'physician_meta', 'Physician Information', __NAMESPACE__ . '\physician_meta_callback', 'physician', 'normal', 'low' );
}
    function physician_meta_callback() {
        include_once( 'views/physician-meta.php' );
    }

/*
 * Saves custom meta fields to db on save
 */
add_action( 'save_post', __NAMESPACE__ . '\save_physician', 10, 3 );
function save_physician( $ID, $post, $update ) {
    if( get_post_type( $ID ) === 'physician' ) {
        if( isset( $_POST['physician_name'] ) ) update_post_meta( $ID, 'physician_name', esc_html( $_POST['physician_name'] ) );
        if( isset( $_POST['physician_accred'] ) ) update_post_meta( $ID, 'physician_accred', sanitize_text_field( $_POST['physician_accred'] ) );
        if( isset( $_POST['physician_ecu_title'] ) ) update_post_meta( $ID, 'physician_ecu_title', sanitize_text_field( $_POST['physician_ecu_title'] ) );
        if( isset( $_POST['physician_clinical_title'] ) ) update_post_meta( $ID, 'physician_clinical_title', sanitize_text_field( $_POST['physician_clinical_title'] ) );
        if( isset( $_POST['physician_location'] ) ) update_post_meta( $ID, 'physician_location', sanitize_text_field( $_POST['physician_location'] ) );
        if( isset( $_POST['physician_profile'] ) ) update_post_meta( $ID, 'physician_profile', sanitize_text_field( $_POST['physician_profile'] ) );
    }
}

/*
 * Registers physician search shortcode
 */
add_shortcode( 'physician_search', __NAMESPACE__ . '\physician_search' );
function physician_search( $atts ) {
    $a = shortcode_atts( [
        "appointment"   =>  "https://ecuphysicians.ecu.edu/contact/"
    ], $atts );

    $appointment_url = sanitize_text_field( $a["appointment"] );

    wp_enqueue_style( 'physician-search' );
    ob_start();
    include_once('views/search-form.php');

    $qterm = sanitize_text_field( $_GET['term'] );
    if( isset($_GET['q']) || isset($_GET['all']) || $qterm): ?>

        <?php

            $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
            $args = [
                'post_type'         => 'physician',
                'posts_per_page'    => 20,
                'post_status'       => 'publish',
                'orderby'           => 'title',
                'order'             => 'ASC',
                'paged'             =>  $paged
            ];

            if( $q = sanitize_text_field( $_GET['q'] ) ):

                $args[ 'search_prod_title' ] = $q;

                add_filter( 'posts_where',  __NAMESPACE__ . '\physician_match', 10, 2 );

                    $wp_query = new \WP_Query( $args );

                remove_filter( 'posts_where',  __NAMESPACE__ . '\physician_match', 10, 2 );

                $heading = "Your search for '<strong>{$q}</strong>' returned the following " . $wp_query->found_posts . " doctor" . ( (int) $wp_query->found_posts > 1 ? 's' : '' );

            elseif( isset( $_GET['all'] ) ):

                $wp_query = new \WP_Query( $args );

                $heading = "Viewing all " . $wp_query->found_posts . " doctor" . ( (int) $wp_query->found_posts > 1 ? 's' : '' );
            elseif( $qterm ):

                $args[ 'tax_query' ] = [
                    [
                        'taxonomy'      =>  'specialty',
                        'field'         =>  'slug',
                        'terms'         =>  $qterm
                    ]
                ];
                $wp_query = new \WP_Query( $args );
                $term = get_term_by('slug', $qterm, 'specialty');
                $heading = "Your search for doctors specializing in <strong>{$term->name}</strong> returned " . $wp_query->found_posts . ( (int) $wp_query->found_posts > 1 ? ' matches.' : ' match.' );

            endif;

            if( $wp_query && $wp_query->have_posts() ): ?>
                <hr />
                <p><?php echo $heading; ?></p>
                <div class="row">
                    <?php while( $wp_query->have_posts() ): $wp_query->the_post(); $meta = get_post_meta( get_the_id() ); ?>
                            <div class="col-md-6">
                            <div class="physician">
                                <?php if( has_post_thumbnail( $post->ID ) ): ?>
                                    <div class="img-wrap">
                                        <img src="<?php echo get_the_post_thumbnail_url( get_the_id(), 'small', ['class' => 'image'] ); ?>" alt=""/>
                                    </div>
                                <?php endif; ?>
                                <div class="content">
                                    <h4 class="physician_name">
                                        <?php if( isset( $meta['physician_name'][0] ) ) echo $meta['physician_name'][0]; ?><?php if( !empty($meta['physician_accred'][0]) ) echo ', <span>' . $meta['physician_accred'][0] . '</span>'; ?>
                                    </h4>

                                        <?php // if( isset( $meta['physician_ecu_title'][0] ) ) echo '<p>' . $meta['physician_ecu_title'][0] . '</p>'; ?>
                                        <?php if( isset( $meta['physician_clinical_title'][0] ) ) echo '<p>' . $meta['physician_clinical_title'][0] . '</p>'; ?>
                                        <?php if( isset( $meta['physician_location'][0] ) ) echo '<p>' . $meta['physician_location'][0] . '</p>'; ?>

                                    <?php if( $meta['physician_profile'][0] ):?>
                                    <a href="<?php echo $meta['physician_profile'][0]; ?>" class="btn btn-sm mr-2">
                                        View Profile
                                    </a>
                                    <?php endif; ?>
                                    <a href="<?php echo $appointment_url ?>" class="btn btn-sm">
                                        Schedule Appointment
                                    </a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php echo paginate_results( $wp_query ); ?>
            <?php else:
                echo '<p>No physicians found, please refine your query.</p>';
            endif;
        endif;

        /**
         * Capture html and store in variable
         */

        $output = '<div id="physician_search">' . ob_get_contents() . '</div>';
        ob_end_clean();
        wp_reset_postdata();
    return $output;
}

/*
 * Paginates search results
 */
function paginate_results( $wp_query ) {
    $links = paginate_links( [
        'total'         => $wp_query->max_num_pages,
        'current'       => max( 1, get_query_var( 'paged' ) ),
        'prev_next'     => true,
        'prev_text'     => '« Previous',
        'next_text'     => 'Next »',
        'type'          =>  'array'
    ] );

    if( is_array( $links ) ) {
        if( count( $links ) > 1){
            $output = '<ul class="pagination">';
            foreach( $links as $link ){
                $output .= '<li class="page-item">' . $link . '</li>';
            }
            $output .= '</ul>';
        }
        return $output;
    }
    return null;
}

/*
 * Adds LIKE query to wp_query to partially match physician names
 */
function physician_match( $where, $wp_query ) {
    global $wpdb;
    if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $search_term ) ) . '%\'';
    }
    return $where;
}