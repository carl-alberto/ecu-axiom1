<?php get_header();
  if (have_posts()) : while (have_posts()) : the_post();?>

  <div class="container">
    <main class="">
      <?php the_content(); ?>
    </main>
  </div>
  <?php endwhile; endif;
get_footer(); ?>
