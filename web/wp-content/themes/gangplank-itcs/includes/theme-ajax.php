<?php
add_action( 'wp_ajax_update_services_final', 'update_services_final',10,0 );
add_action( 'wp_ajax_nopriv_update_services_final', 'update_services_final',10,0 );
function update_services_final() {
    $audience = sanitize_text_field($_GET['audience']);
    $category = sanitize_text_field($_GET['category']);
    $view = sanitize_text_field($_GET['view']);

    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'service',
        'orderby' => 'name',
        'order' => 'ASC',
    );
    if($audience != '*' && $category != '*'){
        $args['tax_query'] = array(
        'relation' => 'AND',
            array(
                'taxonomy' => 'audience',
                'field' => 'slug',
                'terms' => $audience
            ),
            array(
                'taxonomy' => 'service-category',
                'field' => 'slug',
                'terms' => $category
            )
        );
    } elseif($audience != '*' && $category == '*'){
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'audience',
                'field' => 'slug',
                'terms' => $audience
            )
        );
    } elseif($audience == '*' && $category != '*'){
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'service-category',
                'field' => 'slug',
                'terms' => $category
            )
        );
    }
    $posts = get_posts($args);
    $output = '';
    if(count($posts) > 0){
        foreach($posts as $post){
            $excerpt = get_the_excerpt($post->post_id) ? '<p class="service-details">' . get_the_excerpt($post->post_id) . '</p>' : '';
            $output .= '<a href="'.get_permalink($post->post_id).'" data-alpha="'.strtoupper($post->post_title[0]).'" class="service-item">
               <span class="link">'.$post->post_title.'</span>'.$excerpt.'</a><br />';
        }
    } else {
        $output .= '<p>No services found. Please refine your search</p>';
    }
    wp_send_json_success($output);
}

add_action( 'wp_ajax_get_category_filters', 'get_category_filters',10,0 );
add_action( 'wp_ajax_nopriv_get_category_filters', 'get_category_filters',10,0 );
function get_category_filters() {
    $output = '<button class="active update-services" data-filter-type="category" data-category="*">All</button>';
    $terms = get_terms(
        array(
            'taxonomy' => 'service-category',
            'hide_empty' => true,
        )
    );
    foreach($terms as $term){
        $output .= '<button class="update-services" data-filter-type="category" data-category="'.$term->slug.'">'.$term->name.'<span class="badge">'.$term->count.'</span></button>';
    }
    wp_send_json_success($output);
}


add_action( 'wp_ajax_get_tax_data', 'get_tax_data',10,0 );
add_action( 'wp_ajax_nopriv_get_tax_data', 'get_tax_data',10,0 );
function get_tax_data() {
    $tax = sanitize_text_field($_GET['tax']);
    $tag = sanitize_text_field($_GET['tag']);

    $args = array(
        'taxonomy' => $tax,
        'orderby' => 'title',
        'order' => 'ASC'
    );
    $posts = get_terms($args);

    wp_send_json_success($posts);
}


add_action( 'wp_ajax_update_services', 'update_services',10,0 );
add_action( 'wp_ajax_nopriv_update_services', 'update_services',10,0 );
function update_services() {
    $audience = sanitize_text_field($_GET['audience']);
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'service',
        'orderby' => 'name',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => 'audience',
                'field' => 'slug',
                'terms' => $audience,
            )
        )
    );
    $posts = get_posts($args);
    $cats = array();
    foreach($posts as $post):
        $terms = get_the_terms($post->ID, 'service-category');
        foreach($terms as $term):
            if(!array_key_exists($term->name, $cats)):
                $cats[$term->name] = array($term, array($post));
            else:
                $cats[$term->name][1][] = $post;
            endif;
        endforeach;
    endforeach;
    ksort($cats);

    $output = '<div class="row">';
    foreach($cats as $cat):
        $title = get_field('icon', $cat[0]) ? get_field('icon', $cat[0]) . $cat[0]->name : $cat[0]->name;
        $output .= "<div class='col-md-6'>
            <div class='service-cat'>
                <a href='" . get_term_link($cat[0]->term_id) . "' class='term_name'>
                    <h2>{$title}</h2>
                    <span class='fa fa-chevron-right'></span>
                </a>";
                foreach($cat[1] as $post):
                    $output .= "<a href='" . get_permalink($post->post_id) . "' class='service-item'>
                        <span class='link'>" . $post->post_title . "</span>
                    </a><br />";
                endforeach;
            $output .= "</div>
        </div>";
    endforeach;
    $output .= "</div>";
    wp_send_json_success($output);
}
