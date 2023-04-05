<?php do_action('header_meta'); ?>
<?php h1_title(); ?>
<?php if(is_singular('post')): ?>
  <?php if(get_field('secondary_title')): ?>
    <h2 class="secondary-title"><?php the_field('secondary_title'); ?></h2>
  <?php endif; ?>
   <?php if(!get_field('hide_post_meta', 'option')): ?>
      <div class="post-meta">
        <div class="posted">Published <?php the_date('M d, Y'); ?> by <?php the_featured_author(get_the_id(), true); ?></div>
        <div class="cats">Filed under: <?php the_category(); ?></div>
      </div>
    <?php endif; ?>
<?php endif; ?>
