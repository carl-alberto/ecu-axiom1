<?php
/*
 * Template Name: Sidebar
 * Template Post Type: post
 */
get_header();
    if ( have_posts() ) : while ( have_posts() ) : the_post();

    // TODO: do something about how ugly this code is
    $template = get_page_template_slug();
    if( !empty( $template ) ) {
        $left = !get_meta('ecu_sidebar_position');
    } else if( empty( $template ) && is_singular('post') ) { // Legacy can be removed after everyone is on galleon
        $left = get_option('ecu_post_template') === 'sidebar-left';
    }

    ?>
        <div class="row">
            <div class="col-md-9 <?php echo $left ? 'order-2' : 'order-12 order-md-1'; ?>">
                <?php print_post_meta(); ?>
                <?php print_title(); ?>
                <?php print_subtitle(); ?>
                <?php the_content(); ?>
                <?php print_post_category_list(); ?>
            </div>
            <div class="col-md-3 <?php echo $left ? 'order-1' : 'order-1 order-md-12'; ?>">
                <?php get_sidebar(); ?>
            </div>
        </div>
    <?php endwhile; endif;
get_footer();