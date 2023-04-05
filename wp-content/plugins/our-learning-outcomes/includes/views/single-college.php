<?php
/*
 * Template Name: College
 * Template Post Type: college
 */
get_header();
    if (have_posts()) : while (have_posts()) : the_post();?>
        <main id="main">
            <div class="container">
                    <?php
                        echo do_shortcode('[learning_outcomes post_type="'.get_post_type().'" college_id="'.$post->ID.'"/]');
                    ?>
                <div id="lo-content-wrap">
                    <div class="container">
                        <div class="row">
                            <?php the_title('<h1>','</h1>'); ?>
                        </div>
                        <div class="row">
                            <h2>Mission Statement</h2>
                            <hr />
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php the_content(); ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                    if ( has_post_thumbnail() ) {
                                        the_post_thumbnail( 'full' );
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <?php endwhile; endif;
get_footer();
?>