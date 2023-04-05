<?php if(!is_second_level()): ?>
<nav id="wordpress" class="navbar navbar-expand-lg navbar-light" aria-label="main navigation">
    <div class="container">
        <a class="navbar-brand" href="<?php echo get_bloginfo('url'); ?>">
            <?php echo get_bloginfo('name'); ?>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#primary-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php wp_nav_menu(
          array(
            'theme_location'    => 'primary',
            'depth'             => 2,
            'container'         => 'div',
            'container_class'   => 'collapse navbar-collapse',
            'container_id'      => 'primary-nav',
            'menu_class'        => 'navbar-nav mr-auto',
            'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback()',
            'walker'            => new WP_Bootstrap_Navwalker()
          )
        ); ?>
    </div>
</nav>
<?php endif; ?>
