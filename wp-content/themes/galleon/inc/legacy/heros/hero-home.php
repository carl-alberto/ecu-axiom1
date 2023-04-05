<?php if(get_field('banner_type') !== 'none'):
  switch(get_field('banner_type')){
    case 'image':
      include_once('hero-image.php');
      break;
    case 'posts':
      include_once('hero-posts.php');
      break;
    case 'video':
      include_once('hero-video.php');
      break;
    case 'slideshow':
      include_once('hero-slider.php');
      break;
  }
endif;
