<?php get_header(); ?>
  <div class="container">
    <div class="row">
      <main class="col-md-9" id="main">
        <?php h1_title(); ?>
        <?php if (have_posts()) : while (have_posts()) : the_post();
          get_template_part('/partials/components/blog-post');
        endwhile; endif; ?>
        <?php bootstrap_pagination(); ?>
      </main>
      <aside class="col-md-3">
        <?php get_sidebar(); ?>
      </aside>
    </div>
  </div>
<?php get_footer(); ?>
