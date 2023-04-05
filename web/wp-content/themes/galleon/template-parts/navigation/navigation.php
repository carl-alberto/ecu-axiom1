<header class="sticky-top">
    <?php global $site_options; 
        include('skip-links.php');
        include('ecu-nav.php');
        include('resource-nav.php');
        if(!$site_options->hide_site_nav)
            include('site-nav.php');
    ?>
</header>