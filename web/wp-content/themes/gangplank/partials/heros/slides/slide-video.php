<?php if($slide['video']):?>
  <div class="slide">
    <video
        loop="true"
        class="hidden-xs"
        muted="">
			<source src="<?php echo $slide['video']; ?>" type="video/mp4" poster="<?php echo $slide['video_poster']; ?>">
			Your browser does not support the video tag
		</video>
		<?php if($poster = $slide['video_poster']):?><img src="<?php echo $poster; ?>" class="d-sm-none d-md-none d-lg-none d-xl-none img-stretch" /><?php endif; ?>
    <?php if($slide['enable_caption'] && $caption = $slide['caption']): ?>
      <div class="caption <?php echo $caption['position']; ?>">
        <div class="title">
          <div class="container">
            <?php echo $caption['title']; ?>
          </div>
        </div>
        <div class="description">
          <div class="container">
            <p><?php echo $caption['description']; ?></p>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>
