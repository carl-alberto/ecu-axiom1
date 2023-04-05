<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php h1_title(false, false); ?> | <?php bloginfo('name');?> | ECU</title>
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri() . '/images/favicon.png'; ?>">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
      <?php do_action('wp_body'); ?>
      <?php get_template_part('partials/skip-links'); ?>
      <div class="content-wrap">
          <?php if((get_page_template_slug() == 'page-blank.php' && get_field('toggle_header')) || get_page_template_slug() != 'page-blank.php'): ?>
              <?php get_template_part('partials/nav'); ?>
          <?php endif; ?>
          <header aria-label="header">
              <?php if(!get_query_var('news_feed_id', false)) get_template_part('partials/header-content'); ?>
          </header>
          <div id="main-content" role="main">
