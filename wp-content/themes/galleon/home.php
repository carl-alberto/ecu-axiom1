<?php

/**
 *
 * Page template for posts page (blog)
 *
 */

get_header(); ?>

    <?php if ( have_posts() ):?>
        <?php print_title(); ?>
        <div class="row">
            <div class="col-12 col-20 order-md-2">
                <?php get_sidebar(); ?>
            </div>
            <div class="col-12 col-80 order-md-1">
                <div class="post-listing">
                    <?php $dates = []; while ( have_posts() ) : the_post();

                        // Generates date headings if archive page is not date archive
                        if( !is_date() && !is_sticky() ){
                            $date = get_the_date( 'F Y' );
                            if( !in_array($date, $dates ) ){
                                echo "<h2 class='date-heading'>{$date}</h2>";
                                $dates[] = $date;
                            }
                        }

                        include('template-parts/post/post-entry.php');

                    endwhile; ?>
                </div>
                <?php echo get_the_posts_pagination();?>
            </div>
        </div>
    <?php endif; ?>
<?php get_footer();