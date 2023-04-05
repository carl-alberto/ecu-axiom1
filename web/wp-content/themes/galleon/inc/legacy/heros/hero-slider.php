<?php $slides = get_field('slideshow');
if(count($slides) > 0): ?>
  <div class="home-hero" data-speed="7000" class="slick-initialized slick-slider slick-dotted">
    <?php foreach($slides as $slide):
      switch($slide['slide_type']){
        case 'image':
          include_once( 'slides/slide-image.php');
          //include(locate_template('/partials/heros/slides/slide-image.php'));
          break;
        case 'post': $slide_post = $slide['post']; $caption = $slide['caption_position'];
          if($banner = get_field('banner_image', $slide_post->ID)){
            include_once( 'slides/slide-post.php');
            //include(locate_template('/partials/heros/slides/slide-post.php'));
          };
          break;
        case 'video':
          include_once( 'slides/slide-video.php');
          //include(locate_template('/partials/heros/slides/slide-video.php'));
          break;
      }
      ?>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
