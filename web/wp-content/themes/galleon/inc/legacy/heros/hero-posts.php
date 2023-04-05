<?php $cat = get_field('category');
$count = get_field('number_of_posts');
$args = array(
  'post_status' => 'publish',
  'posts_per_page' => $count,
  'category__in' => $cat
);
$slides = get_posts($args);
if(count($slides) > 0): $caption = get_field('caption_position');?>
  <div class="home-hero" data-speed="7000" class="slick-initialized slick-slider slick-dotted">
    <?php foreach($slides as $slide):
      $slide_post = $slide;
      if($banner = get_field('banner_image', $slide_post->ID)){
        include(locate_template('/partials/heros/slides/slide-post.php'));
      }
    endforeach; ?>
  </div>
<?php endif; ?>
