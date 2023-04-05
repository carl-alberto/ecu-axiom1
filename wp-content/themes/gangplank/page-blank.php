<?php /* Template Name: Blank Template
 Template Post Type: post, page */ ?>
<?php get_header(); ?>
    <?php if (have_posts()) : while (have_posts()) : the_post();?>
        <main id="main">
            <?php the_content(); ?>
        </main>
    <?php endwhile; endif; ?>
<?php get_footer(); ?>
