<?php defined( 'ABSPATH' ) || exit;

/**
 * Register dynamic callback for block
 * 
 * @return null
 */
add_action( 'plugins_loaded', function() {
    register_block_type( 'wp-blocks/post-grid', [
        'render_callback' => 'block_post_grid'
    ]);
});

add_action( 'wp_ajax_block_post_grid', 'block_post_grid' );
function block_post_grid( $attributes ) {
    $categories = is_array( $attributes['categories'] ) ? $attributes['categories'] : [];

    $total = $attributes['total'] ? absint( $attributes['total'] ) : 6;
    $width = $attributes['width'] ? absint( $attributes['width'] ) : 3;
    $height = $attributes['height'] ? absint( $attributes['height'] ) : 300;

    $cols = [ 'col-12', 'col-md-6', 'col-md-4', 'col-md-3' ];

    ob_start(); ?>
        <div class="wp-block-wp-blocks-post-grid <?php echo $attributes['className']; ?>">
            <ul class="nav nav-tabs" role="tablist">
                <?php $i = 0; foreach( $categories as $category ): ?>
                    <li class="nav-item" role="presentation">
                        <a 
                            class="nav-link <?php echo $i == 0 ? 'active' : ''; ?>" 
                            id="<?php echo $category['value']; ?>-tab" 
                            data-toggle="tab" 
                            href="#<?php echo $category['value']; ?>" 
                            role="tab" 
                            aria-controls="<?php echo $category['value']; ?>" 
                            aria-selected="true">
                                <?php echo $category['label'] ? $category['label'] : $category['value']; ?>
                            </a>
                    </li>
                <?php $i++; endforeach; ?>
            </ul>
            <div class="tab-content">
                <?php $i = 0; foreach( $categories as $category ): ?>
                    <div 
                        class="tab-pane fade <?php echo $i == 0 ? 'show active' : ''; ?>" 
                        id="<?php echo $category['value']; ?>" 
                        role="tabpanel" 
                        aria-labelledby="<?php echo $category['value']; ?>-tab"
                    >
                        <?php $posts = get_banner_posts( $category['value'], $total );
                            if( count( $posts ) > 0 ): ?>
                                <div class="row">
                                    <?php foreach( $posts as $post ): ?>
                                        <div class="wp-block-wp-blocks-grid-item <?php echo $cols[ $width - 1]; ?>">
                                            <div class="bg-wrap" style="background-image: url('<?php echo $post['image']; ?>'); height: <?php echo $height; ?>px">
                                                <a href="<?php echo get_the_permalink( $post['id'] ); ?>" target="_blank" rel="noreferrer noopener">
                                                    <p><?php echo $post['post_title']; ?></p>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else:
                                echo "<p>No posts found for this category.</p>";
                            endif;
                        ?>
                    </div>
                <?php $i++; endforeach; ?>
            </div>
        </div>
    <?php $output = ob_get_contents();
    ob_end_clean();
    
    return $output;
}

add_action( 'wp_ajax_nopriv_block_post_grid_posts', 'block_post_grid_posts' );
add_action( 'wp_ajax_block_post_grid_posts', 'block_post_grid_posts' );
function block_post_grid_posts() {
    global $wpdb;

    if( !$category = sanitize_text_field( $_GET['category'] ) ) wp_send_json_error( "invalid category" );

    $output = get_banner_posts( $category );

    wp_send_json_success( $output );
}

function get_banner_posts( $category, $total = 12 ){
    $posts = get_posts( [
        'posts_per_page'    => -1,
        'category_name'     => $category,
        'post_status'       => 'publish',
        'post_type'         => 'post'
    ] );

    $output = [];
    
    $i = 0;
    while( count( $output ) < $total && !empty( $posts ) ){
        if( $image = get_the_post_thumbnail_url( $posts[$i]->ID, 'small') ) {
            $output[] = [
                "id"            =>  $posts[$i]->ID,
                "post_title"    =>  $posts[$i]->post_title,
                "image"         =>  $image
            ];
        }
        
        unset( $posts[ $i ] );
        $i++;
    }

    return $output;
}