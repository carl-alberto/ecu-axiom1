<?php if(is_front_page() || get_page_template_slug() == 'page-blank.php'):
  include_once('hero-home.php');
  elseif((!is_archive() || is_home()) && is_singular()) :
  include_once('hero-image.php');
endif;
?>
