<?php get_header();
  if (have_posts()) : while (have_posts()) : the_post();?>
  <div class="container">
    <main id="main">
        <?php if($id = get_field('service')){
            breadcrumbs($id, true);
        }
        ?>
        <?php h1_title(); ?>
      <?php the_content(); ?>
    </main>
  </div>
  <?php endwhile; endif;
get_footer(); ?>
