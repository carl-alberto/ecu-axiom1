<?php get_header();
    if (have_posts()) : while (have_posts()) : the_post();?>
        <main id="main">
            <div class="container">
                <?php get_template_part('partials/components/header-meta'); ?>
                <div class="row">
                    <div class="col-md-9">
                        <?php the_content(); ?>
                    </div>
                    <aside id="service-links" class="col-md-3">
                        <?php get_template_part('partials/related_links'); ?>
                    </aside>
                </div>
            </div>
        </main>
    <?php endwhile; endif;
get_footer(); ?>
