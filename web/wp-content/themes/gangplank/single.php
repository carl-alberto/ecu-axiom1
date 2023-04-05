<?php get_header();
  if (have_posts()) : while (have_posts()) : the_post();?>
  <div class="container">
    <main id="main" <?php echo is_singular('post') ? 'itemscope itemtype="http://schema.org/NewsArticle"' : ''; ?>>
      <?php get_template_part('partials/components/header-meta'); ?>
      <div itemprop="articleBody">
        <?php the_content(); ?>
      </div>
    </main>
  </div>
  <?php endwhile; endif;
get_footer(); ?>
