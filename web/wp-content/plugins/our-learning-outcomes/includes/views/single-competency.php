<?php
/*
 * Template Name: Competency
 * Template Post Type: competency
 */
get_header();
    if (have_posts()) : while (have_posts()) : the_post();?>
        <main id="main">
            <div class="container">
                    <?php
                        echo do_shortcode('[learning_outcomes post_type="'.get_post_type().'" selected="/'.get_post_type().'/'.get_post_field( 'post_name').'"/]');
                    ?>
                <div id="lo-content-wrap">
                    <div class="container">
                        <div class="row">
                            <?php the_title('<h1>','</h1>'); ?>
                        </div>
                        <div class="row">
                               <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <?php endwhile; endif;
get_footer();
?>