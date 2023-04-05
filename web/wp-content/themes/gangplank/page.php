<?php get_header();
  if (have_posts()) : while (have_posts()) : the_post();?>
  <div class="container">
    <div class="row">
      <main class="col-md-9" id="main">
        <?php get_template_part('partials/components/header-meta'); ?>
        <?php the_content(); ?>
      </main>
      <aside class="col-md-3">
        <?php get_sidebar(); ?>
      </aside>
    </div>
  </div>
  <?php endwhile; endif;
get_footer(); ?>
