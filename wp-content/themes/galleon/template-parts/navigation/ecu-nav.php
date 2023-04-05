<section id="main-nav">
    <div class="container">
        <div id="nav-wrap">
            <a class="navbar-brand" href="https://<?php echo getenv('TOPSITE_ENV'); ?>/"><img src="<?php echo get_template_directory_uri() . '/images/ecu-logo.svg'; ?>" alt="East Carolina University" /></a>
            <div id="nav-right">
                <?php if(is_second_level()): ?>
                    <nav id="nav" aria-label="Main Navigation">
                        <ul>
                            <li>
                                <a href="#" aria-haspopup="true" aria-expanded="false" class="has-dropdown">
                                    I am... <span class="fas fa-chevron-down ml-2">
                                </a>
                                <ul class="drop-menu iam collapse"><?php tools_menu(1); ?></ul>
                            </li>
                            <?php tools_menu(2); ?>
                        </ul>
                    </nav>
                <?php endif; ?>
                <div id="resource-wrap">
                    <ul>
                        <?php if(is_second_level()): ?>
                            <li><a href="https://give.ecu.edu/s/722/17/advancement/home.aspx">Give</a></li>
                            <li><a href="https://<?php echo getenv('TOPSITE_ENV'); ?>/apply">Apply</a></li>
                        <?php endif; ?>
                        <li>
                            <button
                                id="toggle-resources"
                                type="button"
                                data-toggle="collapse"
                                data-target="#theme-resources"
                                aria-expanded="false"
                                aria-controls="theme-resources"
                                aria-label="Toggle resources"
                            >
                                <span class="fas fa-search"></span> | <span id="resource-icon" class="fas fa-bars"></span>
                            </button>
                        </li>
                </div>
            </div>
        </div>
    </div>
</section>