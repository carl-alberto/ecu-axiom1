<?php get_header(); ?>
  <main id="main">
  <div class="container">
        <?php breadcrumbs(); ?>
</div>
<div id="service-cat-header">
    <div class="container">
      <h1>
          <?php echo get_the_archive_title(); ?>
      </h1>
  </div>
</div>
<div class="container">
      <?php $obj = get_queried_object();
      $args = array(
          'posts_per_page' => -1,
          'post_type' => 'service',
          'orderby' => 'name',
          'order' => 'ASC',
          'tax_query' => array(
              array(
                  'taxonomy' => $obj->taxonomy,
                  'field' => 'term_id',
                  'terms' => $obj->term_id,
              )
          )
      );
      $posts = get_posts($args);
      if(count($posts) > 0):?>
            <div id="services">
                <div class="row">
                  <?php foreach($posts as $post):
                      get_template_part('partials/services');
                  endforeach; ?>
                </div>
            </div>
      <?php else:
          echo 'No services found';
      endif;
       ?>
    </main>
  </div>
  <?php get_footer(); ?>
