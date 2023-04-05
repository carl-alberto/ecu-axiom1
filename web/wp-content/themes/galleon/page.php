<?php
/*
 * Template Name: Full Width
 * Template Post Type: page
 */
get_header();
    if ( have_posts() ) : while ( have_posts() ) : the_post();
        print_title();
        the_content();
    endwhile; endif;
get_footer();