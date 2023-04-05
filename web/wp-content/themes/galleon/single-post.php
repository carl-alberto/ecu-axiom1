<?php
/*
 * Template Name: Full Width
 * Template Post Type: post
 */
get_header();
    if ( have_posts() ) : while ( have_posts() ) : the_post();
        print_post_meta();
        print_title();
        print_subtitle();
        the_content();
        print_post_category_list();
    endwhile; endif;
get_footer();