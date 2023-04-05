<?php get_header();
  if (have_posts()) : while (have_posts()) : the_post();?>
  <div class="container">
      <div class="row">
        <main class="col-md-9" id="main">
        <?php h1_title(get_the_id()); ?>
          <?php if(get_field('secondary_title')): ?>
            <h2 class="secondary-title"><?php the_field('secondary_title'); ?></h2>
          <?php endif; ?>
           <?php if(!get_field('hide_post_meta', 'option')): ?>
              <div class="post-meta">
                <div class="posted">Published <?php the_date('M d, Y'); ?> by <?php the_featured_author(get_the_id(), true); ?></div>
                <div class="cats">Filed under: <?php the_category(); ?></div>
              </div>
            <?php endif; ?>

      <?php the_content();
      bootstrap_pagination();?>
  </main>
  <aside class="col-md-3">
    <?php get_sidebar(); ?>
  </aside>
</div>
  </div>
  <?php endwhile; endif;

get_footer(); ?>
