<?php if($video = get_field('video')):?>
  <div class="slide">
    <div class="controls">
      <span id="video-controls" class="fa fa-pause fa-lg" aria-hidden="true"></span>
    </div>
    <video
        loop="true"
        muted="true"
        autoplay="true"
        id="hero-video">
			<source src="<?php echo $video; ?>" type="video/mp4" poster="<?php echo $slide['video_poster']; ?>">
			Your browser does not support the video tag
		</video>
  </div>
<?php endif; ?>
