<?php /* Template Name: Full-Width Template
 Template Post Type: post, page */ ?>
<?php get_header();
  if (have_posts()) : while (have_posts()) : the_post();?>
  <div class="container">
    <main id="main">
      <?php get_template_part('partials/components/header-meta'); ?>
      <?php the_content(); ?>
    </main>
  </div>
  <?php endwhile; endif;
get_footer(); ?>
