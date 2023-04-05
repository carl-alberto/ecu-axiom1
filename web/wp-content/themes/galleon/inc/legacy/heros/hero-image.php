<?php

if(is_home() && get_option('posts_per_page') == 1){
	$id = get_the_id();
} elseif(is_home() && get_option('posts_per_page') != 1) {
	$id = is_home() ? get_option('page_for_posts') : get_the_id();
} else {
	$id = get_the_id();
}

if($banner_image = get_field('banner_image', $id)):
	if(is_array($banner_image)):
		$image = esc_url($banner_image['url']);
		$mobile = esc_url($banner_image['original_image']['sizes']['banner_xs']);
	  $desc = $banner_image['caption'] ? $banner_image['caption'] : $banner_image['description'];
	  $full = get_field('banner_full_width');?>

	  <section id="hero" aria-label="page header">
	    <div class="hero-container">
	      <?php if($full): ?>
	        <img src="<?php echo $image; ?>" alt="<?php echo $desc; ?>" class="d-none d-sm-block d-md-block d-lg-block d-xl-block img-fluid img-stretch">
	        <img src="<?php echo $mobile; ?>" alt="<?php echo $desc; ?>" class="d-sm-none d-md-none d-lg-none d-xl-none img-fluid">
	      <?php else: ?>
	        <div class="container">
	          <img src="<?php echo $image; ?>" alt="<?php echo $desc; ?>" class="d-none d-sm-block d-md-block d-lg-block d-xl-block img-fluid img-stretch">
	          <img src="<?php echo $mobile; ?>" alt="<?php echo $desc; ?>" class="d-sm-none d-md-none d-lg-none d-xl-none img-fluid">
	        </div>
	  		<?php endif; ?>
	    </div>

	    <!-- Output caption on single posts -->
	    <?php if(is_singular('post') && $desc): ?>
				<div class="banner-caption">
		      <div class="container">
		        <figcaption>
		          <?php echo $desc; ?>
		        </figcaption>
		      </div>
				</div>
	    <?php endif; ?>

	  </section>
	<?php endif;
endif; ?>
