<?php get_header();?>
  <div class="container">
    <main id="main">
      <div class="gcse-searchbox-only" data-resultsUrl="<?php echo site_url('/search-results'); ?>" ></div>
      <div class="gcse-searchresults-only" data-as_sitesearch="<?php echo get_site_url(); ?>" ></div>
    </main>
  </div>
<?php get_footer(); ?>
