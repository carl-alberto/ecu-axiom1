<?php if(get_field('banner_type') !== 'none'):
  switch(get_field('banner_type')){
    case 'image':
      get_template_part('partials/heros/hero-image');
      break;
    case 'posts':
      get_template_part('partials/heros/hero-posts');
      break;
    case 'video':
      get_template_part('partials/heros/hero-video');
      break;
    case 'slideshow':
      get_template_part('partials/heros/hero-slider');
      break;
  }
endif;
