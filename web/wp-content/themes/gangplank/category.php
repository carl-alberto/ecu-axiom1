<?php get_header();
if(get_field('category_sidebar', 'option')){
    get_template_part('partials/components/archive-sidebar');
} else {
    get_template_part('partials/components/archive-full');
}
get_footer();
