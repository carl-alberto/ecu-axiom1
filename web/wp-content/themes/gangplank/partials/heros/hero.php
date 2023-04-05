<?php if(is_front_page() || get_page_template_slug() == 'page-blank.php'):
  get_template_part('/partials/heros/hero-home');
  elseif(!is_archive() || is_home()) :
  get_template_part('/partials/heros/hero-image');
endif; ?>
