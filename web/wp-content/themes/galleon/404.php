<?php get_header(); print_title();?>
    <p>The requested page cannot be found</p>
    <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable</p>
    <p>Return to the <a href="<?php bloginfo('wpurl'); ?>">homepage</a> or try one of the following:</p>
    <ul>
        <li>If you came here directly, check for typos in the URL.</li>
        <li>If a link brought you here, contact the web site administrator to alert them that the link is broken.</li>
        <li>Use the search below to locate the content you are looking for.</li>
    </ul>
    <div class="row">
        <div class="col-lg-4">
            <?php include('template-parts/navigation/search-form.php'); ?>
        </div>
    </div>
<?php get_footer(); ?>