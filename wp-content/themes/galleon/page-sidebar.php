<?php
/*
 * Template Name: Sidebar
 * Template Post Type: page
 */
get_header();
    if ( have_posts() ) : while ( have_posts() ) : the_post();

    // TODO: do something about how ugly this code is
    $template = get_page_template_slug();

    if( !empty( $template ) ) {
        $left = !get_meta('ecu_sidebar_position');
    } else if( empty( $template ) ) {
        $left = get_option('ecu_page_template') === 'sidebar-left';
    }

    ?>
        <div class="row">
            <div class="col-md-9 <?php echo $left ? 'order-2' : 'order-12 order-md-1'; ?>">
                <?php print_title(); ?>
                <?php the_content(); ?>
            </div>
            <div class="col-md-3 <?php echo $left ? 'order-1' : 'order-1 order-md-12'; ?>">
                <?php get_sidebar(); ?>
            </div>
        </div>
    <?php endwhile; endif;
get_footer();