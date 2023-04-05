<?php /* Template Name: Sidebar Left Template
 Template Post Type: post, page */ ?>
<?php get_header();
  if (have_posts()) : while (have_posts()) : the_post();?>
  <div class="container">
    <div class="row">
      <main class="col-md-9 order-1 order-md-12" id="main">
        <?php get_template_part('partials/components/header-meta'); ?>
        <?php the_content(); ?>
      </main>
      <aside class="col-md-3 order-12 order-md-1">
        <?php get_sidebar(); ?>
      </aside>
    </div>
  </div>
  <?php endwhile; endif;
get_footer(); ?>
