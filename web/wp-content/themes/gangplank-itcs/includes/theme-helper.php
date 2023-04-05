<?php

function breadcrumbs($id = false, $single = false) {
    global $wp;
    global $post;

    $base = home_url();

    $path = array(array ('<span class="fa fa-home"></span>', $base));
    if(is_singular(array('service', 'faq', 'tutorial'))){
        $id = !is_singular('service') ? get_field('service') : get_the_id();
        $path[] = array('Services', $base.'/services');
        if($terms = wp_get_post_terms($id, 'service-category')) {
            $path[] = array($terms[0]->name, get_term_link($terms[0]->term_id));
        }
        $path[] = array(get_the_title($id), get_the_permalink($id));
        $request = explode('/', $wp->request);
        $end = end($request);
        if($end === 'faqs' || $end === 'tutorials'){
            $path[] = array($end === 'faqs' ? 'FAQs' : 'Tutorials', $base . '/' . $wp->request);
        }
    } elseif(is_singular(array('post', 'page'))) {
        $page = wp_get_post_parent_id($post->ID);
        $parents = array();
        while($page !== 0){
            $parents[] = array(get_the_title($page), get_the_permalink($page));
            $page = wp_get_post_parent_id($page);
        }
        $parents = array_reverse($parents);
        $path = array_merge($path, $parents);
        $path[] = array($post->post_title, get_the_permalink($post->ID));
    } elseif(is_tax()) {
        $tax = get_query_var('taxonomy');
        $term_id = get_queried_object()->term_id;
        $orig = $tax;
        if(strtolower(substr($tax, -1)) != 's') $tax = $tax . 's';
        if($orig === 'goals'){
            $path[] = array(ucwords($tax), $base.'/strategic-plan');
        } else {
            $path[] = array(ucwords($tax), $base.'/'. strtolower($tax));
        }
        $term = get_term($term_id, $orig);
        $parent_id = $term->parent;
        $parents = array();
        while($parent_id !== 0){
            $parent = get_term($parent_id, $orig);
            $parents[] = array($parent->name, get_term_link($parent->term_id));
            $parent_id = $parent->parent;
        }
        $parents = array_reverse($parents);
        $path = array_merge($path, $parents);
        $path[] = array($term->name, get_term_link($term_id));
    }
    $output = '<div id="breadcrumbs">
        <ul>';
        $count = count($path);
        $n = 0;
        foreach($path as $link){
            if($n + 1 == $count){
                $output .= '<li>'.$link[0].'</li>';
            } else {
                $output .= '<li><a href="'.$link[1].'">'.$link[0].'</a></li>';
            }
            $n++;
        }
        $output .= '</ul>
    </div>';
    echo $output;

}

add_filter( 'query_vars', 'add_query_vars');
function add_query_vars($vars){
    $vars[] = "faqs";
    $vars[] = "tutorials";
    return $vars;
}

add_action( 'init', function() {
    add_rewrite_endpoint( 'faqs', EP_PERMALINK );
    add_rewrite_endpoint( 'tutorials', EP_PERMALINK );
},10,0 );

add_action('init', function() {
    $blacklist = array(
        '/services/audience',
        '/tutorial',
        '/faq'
    );

    if(in_array($_SERVER['REQUEST_URI'], $blacklist)){
        wp_redirect(get_bloginfo('wpurl') . '/services', 301);
        exit;
    }
},10,0);

add_filter( 'single_template', 'service_meta' );
function service_meta($templates = ""){
    global $wp_query;
    if(isset($wp_query->query['faqs']) || isset($wp_query->query['tutorials'])){
        $templates = locate_template(array("service-meta.php", $templates),false);
    }
    return $templates;
}

function tax_link($slug = false, $type = false){
    if($slug && $type){
        return get_bloginfo('url') . '/services?' . $type . '=' . rawurlencode(strtolower($slug));
    }
}

function paginate_view($counter = 0, $total = 0, $float = false){
    $float = $float ? 'float-right' : '';
    $output = '<div class="view-nav '.$float.'">
        <ul>
            <li class="view-all"><button class="change-view" data-view="view-0" data-mh="view-nav">View All</button></li>';
            if($counter - 1 != 0){
                $output .= '<li><button class="change-view previous" data-view="view-'. ($counter - 1) .'" data-mh="view-nav">Previous</button></li>';
            }
            if($counter != $total){
                $output .= '<li><button class="change-view next" data-view="view-'. ($counter + 1) .'" data-mh="view-nav">Next</button></li>';
            }
        $output .= '</ul>
    </div>';
    echo $output;
}

function h1_title_itcs($id = false, $tags = true, $force = false, $echo = true) {
     if(is_singular() || is_front_page() || is_home()){
         global $post;
         if($id){
           $pid = $id;
         } elseif(is_home()){
           $pid = get_option('page_for_posts');
         } else {
           $pid = $post->ID;
         }
         $title = get_field('h1_title', $pid) ? get_field('h1_title', $pid) : get_the_title($pid);
     } elseif(is_archive()){
       $title = get_the_archive_title();
     } elseif(is_search()){
         $title = 'Search results';
     } else {
       $title = 'Page not found';
     }
     $title = strip_tags($title);
     if(get_field('hide_h1_title') && !$force){
         if($echo){
             echo $tags ? '<h1 class="sr-only">' . $title . '</h1>' : $title;
         } else {
             return $tags ? '<h1 class="sr-only">' . $title . '</h1>' : $title;
         }
     } else {
       if($echo){
           echo $tags ? '<h1>' . $title . '</h1>' : $title;
       } else {
           return $tags ? '<h1>' . $title . '</h1>' : $title;
       }
     }
   }

   function itcs_load_dashboards($field) {
       // reset choices
       $field['choices'] = array();

       $args = array(
         'post_type' => 'ui-elements',
         'meta_query' => array(
           array(
             'key' => 'element_type',
             'value' => 'dashboard_2',
           )
         )
       );
       $posts = get_posts($args);
       foreach($posts as $post){
         $field['choices'][$post->ID] = $post->post_title;
       }
       return $field;

   }

   add_filter('acf/load_field/name=itcs_footer_dashboard', 'itcs_load_dashboards');

add_action( 'edit_form_advanced', 'related_content',10,0 );
function related_content() { global $post;
    if(get_post_type(get_the_id()) == 'service'){ ?>
    <div class="service-meta">
        <div style="width:49%; float: left;">
            <div class="postbox">
                <h2 class="hndle ui-sortable-handle">
                    <span>FAQs</span>
                    <a class="acf-button button button-primary" href="<?php echo admin_url() . 'post-new.php?post_type=faq'; ?>">Create FAQ <span class="dashicons dashicons-plus"></span></a>
                </h2>
                <div class="inside">
                    <?php $args = array(
                      'posts_per_page' => 100,
                      'post_type' => 'faq',
                      'orderby' => 'name',
                      'order' => 'ASC',
                      'meta_key' => 'service',
                      'meta_value' => $post->ID
                    );
                    $faqs = get_posts($args);
                    if(count($faqs) > 0): ?>
                        <table class="bs-table table-striped">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Edit</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($faqs as $faq):?>
                                <tr>
                                    <td><?php echo $faq->post_title; ?></td>
                                    <td><a href="<?php echo get_edit_post_link( $faq->ID ); ?>"><span class="dashicons dashicons-edit"></span></a></td>
                                    <td><a href="<?php echo get_the_permalink( $faq->ID ); ?>"><span class="dashicons dashicons-admin-links"></span></a></td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        No FAQ's found. <a href="<?php echo admin_url() . 'post-new.php?post_type=faq'; ?>">Click here to create one.</a>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <div  style="width:49%; float: left; margin-left: 2%;">
            <div class="postbox">
                <h2 class="hndle ui-sortable-handle">
                    <span>Tutorials</span>
                    <a class="acf-button button button-primary"  href="<?php echo admin_url() . 'post-new.php?post_type=tutorial'; ?>">Create Tutorial <span class="dashicons dashicons-plus"></span></a>
                </h2>
                    <div class="inside">
                        <?php $args = array(
                          'posts_per_page' => 100,
                          'post_type' => 'tutorial',
                          'orderby' => 'name',
                          'order' => 'ASC',
                          'meta_key' => 'service',
                          'meta_value' => $post->ID
                        );
                        $tuts = get_posts($args);
                        if(count($tuts) > 0): ?>
                        <table class="bs-table table-striped">
                            <thead>
                                <tr>
                                    <th>Tutorial</th>
                                    <th>Edit</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($tuts as $tut):?>
                                <tr>
                                    <td><?php echo $tut->post_title; ?></td>
                                    <td><a href="<?php echo get_edit_post_link( $tut->ID ); ?>"><span class="dashicons dashicons-edit"></span></a></td>
                                    <td><a href="<?php echo get_the_permalink( $tut->ID ); ?>"><span class="dashicons dashicons-admin-links"></span></a></td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                        <?php else: ?>
                            No Tutorial's found. <a href="<?php echo admin_url() . 'post-new.php?post_type=tutorial'; ?>">Click here to create one.</a>
                        <?php endif;?>
                    </div>
                </div>
                </div>
            </div>
            <style type="text/css">
            .service-meta .hndle {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .service-meta .hndle a {
                font-size: 14px;
            }
            .service-meta .hndle .dashicons {
                display: inline;
                height: auto;
                width: auto;
                font-size: 14px;
                line-height: 15.4px;
            }
            .service-meta .bs-table {
                width: 100%;
                max-width: 100%;
                border-collapse: collapse;
            }
            .service-meta .bs-table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6;
            }
            .service-meta .bs-table thead th:first-child {
                width: 80%;
            }
            .service-meta .bs-table thead th {
                width: 10%;
            }
            .service-meta .bs-table tbody td:not(:first-child) {
                text-align: center;
            }
            .service-meta .bs-table tbody tr:nth-of-type(odd) {
                background-color: rgba(0,0,0,.05);
            }
            .service-meta .bs-table th {
                padding: .75rem;
                vertical-align: top;
            }
            .service-meta .bs-table td {
                padding: .75rem;
                vertical-align: top;
                border-top: 1px solid #dee2e6;
            }
            .service-meta .bs-table a {
                text-decoration: none;
            }
            .service-meta .bs-table a:hover {
                text-decoration: none;
            }
            </style>
<?php }
}
