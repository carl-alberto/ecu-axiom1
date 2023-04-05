<?php

/**
 * 
 * Template Name: Sidebar left
 * 
 */
// TODO: Delete me

get_header();
    if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="row">
            <div class="col-md-9 order-1 order-md-12">
                <?php print_title(); ?>
                <?php the_content(); ?>
            </div>
            <aside class="col-md-3 order-12 order-md-1">
                <?php get_sidebar(); ?>
            </aside>
        </div>
    <?php
    endwhile; endif;
get_footer();