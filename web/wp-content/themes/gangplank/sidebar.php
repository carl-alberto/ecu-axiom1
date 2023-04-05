<?php
$taxonomies = get_taxonomies();
	if(is_home() || is_archive()){
		if(is_home() && $sidebar = get_field('blog_sidebar', 'option')){
			dynamic_sidebar("custom-sidebar-{$sidebar}");
		} elseif (is_category() && $sidebar = get_field('category_sidebar', 'option')) {
			dynamic_sidebar("custom-sidebar-{$sidebar}");
		} elseif ((is_tax('featured-author') || is_author()) && $sidebar = get_field('author_sidebar', 'option')) {
			dynamic_sidebar("custom-sidebar-{$sidebar}");
		} elseif (is_date() && $sidebar = get_field('date_sidebar', 'option')){
			dynamic_sidebar("custom-sidebar-{$sidebar}");
		} else {
			dynamic_sidebar('default_sidebar');
		}
	} elseif(get_field('sidebar_selector')) {
		dynamic_sidebar(get_field('sidebar_selector') ? 'custom-sidebar-' . get_field('sidebar_selector') : 'default_sidebar' );
	} else {
		dynamic_sidebar('default_sidebar');
	}
?>
