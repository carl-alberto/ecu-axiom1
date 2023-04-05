<?php

namespace OUR\PLUGINS;

function post_search( $atts ) {
    $a = shortcode_atts( [

            /**
             * Comma separated list of category IDs
             */

            'categories' => ''
        ] , $atts );
    ob_start();

        /**
         * Output search form
         */

        ?>
        <div class="shortcode-post-search">
            <form>
                <div class="input-group">
                    <input type="text" name="q" placeholder="Search" value="<?php echo $_GET[ 'q' ]; ?>" aria-label="Search" class="form-control">
                    <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">Search</button>
                    </span>
                </div>
            </form>
            <?php

            /**
             * Check if post query is set
             */

            if( isset( $_GET[ 'q' ] ) ):
                if( $_GET[ 'q' ] == '' ):

                    /**
                     * Return notice if no search criteria was entered
                     */

                    ?><p class="notice">Please enter a search term.</p><?php
                else:

                    /**
                     * Set query options
                     */

                    $query = sanitize_text_field( $_GET[ 'q' ] );
                    $args = [
                        'post_type'         => 'post',
                        'posts_per_page'    => 10,
                        'query'             => $query,
                        'cat'               => [],
                        'post__not_in'      => get_option( 'sticky_posts' ),
                        'post_status'       => 'publish',
                        'orderby'           => 'date',
                        'order'             => 'DESC',
                        'paged'             => (get_query_var('paged')) ? get_query_var('paged') : 1
                    ];

                    /**
                     * Parse and set categories if included
                     */

                    if( !empty( $a[ 'categories' ] ) ){
                        $cats = explode( ',', $a[ 'categories' ] );
                        foreach( $cats as $cat ){
                            $cat = (int) trim( $cat );
                            if( $cat != 0 ){
                                $args[ 'cat' ][] = $cat;
                            }
                        }
                    }

                    /**
                     * Temporarily add fuzzy match filter during query execution
                     * Remove after query is made
                     * Set global max_num_pages equal to result max_num_pages for pagination
                     */

                    add_filter( 'posts_where', __NAMESPACE__ . '\fuzzy_match', 10, 2 );
                    $query = new WP_Query( $args );
                    remove_filter( 'posts_where', __NAMESPACE__ . '\fuzzy_match', 10, 2 );
                    $GLOBALS['wp_query']->max_num_pages = $query->max_num_pages;

                    if( !$query->have_posts() ):

                        /**
                         * Return notice if no posts were found
                         */

                        ?><p class="notice">No results found, please refine your search.</p><?php
                    else:

                        /**
                         * Output posts
                         */

                        ?><div class="post-search-results"> <?php
                            while( $query->have_posts() ): $query->the_post(); ?>
                                <?php $banner = get_field( 'banner_image', get_the_id() ); ?>
                                <div <?php post_class( [ 'post-entry' ] ); ?>>
                                    <?php if ( $banner ): ?>
                                        <div class="row">
                                            <div class="col-3 col-lg-2">
                                                <div class="post-thumbnail">
                                                    <img src="<?php echo $banner[ 'sizes' ][ 'medium' ]; ?>" alt="<?php echo $banner[ 'alt' ];?>" />
                                                </div>
                                            </div>
                                            <div class="col-9 col-lg-10">
                                                <?php endif; ?>
                                                    <div class="post-headline">
                                                        <a href="<?php the_permalink(); ?>" class="block-link underline">
                                                            <h2 class="h5">
                                                                <?php h1_title( get_the_id(), false, '' ); ?>
                                                            </h2>
                                                        </a>
                                                        <?php gp_excerpt( get_the_id(), ' [...]' ); ?>
                                                        <p class="date"><span class="fa fa-calendar mr-2"></span><?php echo get_the_date( 'n.j.Y' ); ?></p>
                                                    </div>
                                                <?php if( $banner ): ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                            <?php the_posts_pagination([
                                'mid_size' => 1,
                                'screen_reader_text' => false
                            ]); ?>
                        </div>
                    <?php endif; ?>
                <?php wp_reset_postdata();
            endif;
        endif;

        /**
         * Capture html and store in variable
         */

        $output = ob_get_contents() . '</div>';
        ob_end_clean();
    return $output;
}

add_shortcode( 'post-search', __NAMESPACE__ . '\post_search' );

function fuzzy_match( $where, $query ) {
    global $wpdb;
    if ( $q = wpdb::esc_like( $query->get( 'query' ) ) ) {
        $where .= " AND ( {$wpdb->posts}.post_title LIKE '%{$q}%' OR {$wpdb->posts}.post_content LIKE '%{$q}%')";
    }
    return $where;
}