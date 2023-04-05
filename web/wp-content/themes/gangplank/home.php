<?php get_header();
if(get_option('posts_per_page') == 1){
    if(get_field('blog_sidebar', 'option')){
        get_template_part('partials/components/archive-single-sidebar');
    } else {
        get_template_part('partials/components/archive-single');
    }
} else {
    if(get_field('blog_sidebar', 'option')){
        get_template_part('partials/components/archive-sidebar');
    } else {
        get_template_part('partials/components/archive-full');
    }
}
get_footer();
