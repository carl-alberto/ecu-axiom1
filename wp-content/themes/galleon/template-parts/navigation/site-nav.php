<section id="site-nav">
    <div class="container">
        <div class="nav-wrap">
            <div class="left-wrap">
                <a class="navbar-brand" href="<?php echo get_bloginfo('url'); ?>">
                    <p class="h1"><?php bloginfo('name'); ?></p>
                </a>
                <button 
                    id="toggle-site-nav"
                    class="d-xl-none d-lg-none" 
                    type="button" 
                    data-toggle="collapse" 
                    data-target="#primary-nav" 
                    aria-controls="primary-nav" 
                    aria-expanded="false" 
                    aria-label="Toggle navigation">
                    <span class="fas fa-bars"></span>
                </button>
            </div>
            <?php wp_nav_menu([
                'theme_location'    => 'primary',
                'depth'             => 2,
                'container'         => 'nav',
                'container_class'   => 'collapse',
                'container_id'      => 'primary-nav',
                'menu_class'        => '',
                'menu_id'           => 'menu-main-navigation-menu',
                'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback()',
                'walker'            => new WP_Bootstrap_Navwalker()
            ]); ?>
        </div>
    </div>
</section>