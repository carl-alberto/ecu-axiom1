<?php  defined( 'ABSPATH' ) || exit;

/**
 * Register dynamic callback for block
 * 
 * @return null
 */
add_action('plugins_loaded', function() {
    register_block_type( 'wp-blocks/physician', [
        'render_callback' => 'block_physician'
    ]);
});

/**
 * Renders markup for block
 * 
 * @return string The HTML for the block
 */
function block_physician( $attributes ) {
    $id = absint( $attributes['physicianId'] );

    if( !$id ) return;

    $hideImage = wp_validate_boolean( $attributes['hideImage'] );
    $hideName = wp_validate_boolean( $attributes['hideName'] );
    $hideEcuTitle = wp_validate_boolean( $attributes['hideEcuTitle'] );
    $hideClinicalTitle = wp_validate_boolean( $attributes['hideClinicalTitle'] );
    $hideLocation = wp_validate_boolean( $attributes['hideLocation'] );

    $meta = block_physician_get_meta_helper( $id );
    ob_start(); ?>
        <div class="wp-block-wp-blocks-physician">
            <div class="physician">
                <?php if( $meta['image'] && !$hideImage ): ?>
                    <img src="<?php echo $meta['image']; ?>" class="image mr-3" />
                <?php endif; ?>
                <div class="content">
                    <?php if( !$hideName ): ?>
                        <h4 class="physician_name">
                            <?php if( isset( $meta['name'] )  ) echo $meta['name']; ?><?php if( !empty($meta['accreditation'] ) ) echo ', <span>' . $meta['accreditation'] . '</span>'; ?>
                        </h4>
                    <?php endif; ?>
                    <?php if( isset( $meta['ecu_title'] ) && !$hideEcuTitle ) echo '<p>' . $meta['ecu_title'] . '</p>'; ?>
                    <?php if( isset( $meta['clinical_title'] ) && !$hideClinicalTitle ) echo '<p>' . $meta['clinical_title'] . '</p>'; ?>
                    <?php if( isset( $meta['location'] ) && !$hideLocation ) echo '<p>' . $meta['location'] . '</p>'; ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    <?php 
    $output = ob_get_contents();
    ob_end_clean();
    
    return $output;
}

/**
 * Fetches all physicians
 * Physicians are cached for 8 hours
 * 
 * @return array Value / label array of all physicians
 */
add_action( 'wp_ajax_block_physician_get_options', 'block_physician_get_options' );
function block_physician_get_options() {

    if( !post_type_exists( 'physician' ) ) wp_send_json_error();

    if( $transient = get_site_transient( 'ecuphysicians' ) ) wp_send_json_success( $transient );

    $posts = get_posts( [
        'post_type' =>  'physician',
        'orderby'   =>  'title',
        'order'     =>  'asc',
        'posts_per_page'    => -1
    ] );

    if( !$posts ) wp_send_json_error( 'No physicians found.');

    $output = [];

    foreach( $posts as $post ){
        $output[] = [
            'value' => $post->ID,
            'label' => $post->post_title
        ];
    }

    set_site_transient( 'ecuphysicians', $output, 8 * HOUR_IN_SECONDS );
    
    wp_send_json_success( $output );
}

/**
 * Returns meta values via AJAX for block editor
 * 
 * @return object Events found
 */
add_action( 'wp_ajax_block_physician_get_meta', 'block_physician_get_meta' );
function block_physician_get_meta() {

    $id = absint( $_GET['physician_id'] );
    $output = block_physician_get_meta_helper( $id );
    wp_send_json_success( $output );

}

/**
 * Helper function to return data for physician
 * 
 * @return array Array of meta values for physician
 */
function block_physician_get_meta_helper( $id ) {

    if( !$id ) wp_send_json_error();

    $meta = get_post_meta( $id );

    $output = [
        'image'             => get_the_post_thumbnail_url( $id ),
        'accreditation'     => $meta['physician_accred'][0],
        'clinical_title'    => $meta['physician_clinical_title'][0],
        'ecu_title'         => $meta['physician_ecu_title'][0],
        'location'          => $meta['physician_location'][0],
        'name'              => $meta['physician_name'][0]
    ];

    return $output;
}