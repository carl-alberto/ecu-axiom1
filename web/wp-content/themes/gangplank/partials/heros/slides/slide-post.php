<div class="slide">
    <img src="<?php echo $banner['url']; ?>" alt="<?php h1_title($slide_post->ID, false); ?>" class="d-none d-sm-block d-md-block d-lg-block d-xl-block img-fluid img-stretch">
    <img src="<?php echo $banner['sizes']['banner_xs']; ?>" alt="<?php h1_title($slide_post->ID, false); ?>" class="d-sm-none d-md-none d-lg-none d-xl-none img-fluid">
  <div class="caption <?php echo $caption ?>">
    <div class="title">
      <div class="container">
        <?php h1_title($slide_post->ID, false); ?>
      </div>
    </div>
    <div class="description">
      <div class="container">
        <p><?php gp_excerpt($slide_post->ID, '...'); ?></p>
        <a href="<?php echo get_the_permalink($slide_post->ID); ?>" aria-label="Read More About <?php h1_title($slide_post->ID, false); ?>" class="pull-right ecu-btn primary arrow">Read More</a>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>
