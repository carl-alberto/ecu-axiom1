<?php get_header(); ?>
  <div class="container">
      <main id="main">
        <?php h1_title(); ?>
        <?php if (have_posts()) : while (have_posts()) : the_post();
          get_template_part('/partials/components/blog-post');
        endwhile; endif; ?>
        <?php bootstrap_pagination(); ?>
      </main>
  </div>
<?php get_footer(); ?>
