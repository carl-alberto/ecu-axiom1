<?php
global $post;
$args = array(
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => 3,
  'post__not_in' => array($post->ID)
);

$query = new WP_Query( $args );
if($query->have_posts()): ?>
<h3>Recent Posts</h3>
<hr />
<?php while($query->have_posts()): $query->the_post(); ?>
<div class="related-post">
  <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
  <p><?php gp_excerpt($post->ID, ' [...]'); ?></p>
</div>
<?php endwhile; wp_reset_postdata(); endif;
