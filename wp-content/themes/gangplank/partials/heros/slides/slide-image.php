<div class="slide">
    <img src="<?php echo $slide['image']['url']; ?>" alt="<?php echo $slide['image']['alt'] ?>" class="d-none d-sm-block d-md-block d-lg-block d-xl-block img-fluid img-stretch">
    <img src="<?php echo $slide['image']['sizes']['banner_xs']; ?>" alt="<?php echo $slide['image']['desc']; ?>" class="d-sm-none d-md-none d-lg-none d-xl-none img-fluid">
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
