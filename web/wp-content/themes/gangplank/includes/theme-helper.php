<?php

/**
 * Generates post title
 * @param  integer $id   post ID if used outside of the_loop
 * @param  boolean $tags determines if h1 tags are returned
 * @param  boolean $echo echo or return; default return
 * @return string        returns title of post
 */
function h1_title($id = false, $tags = true, $echo = true) {
    global $post;

    if($id){
        $title = get_field('h1_title', $id) ? get_field('h1_title', $id) : get_the_title($id);
    } else {
        if(is_archive()){
            if(is_search()){
                $title = 'Search Results';
            } elseif(is_404()){
                $title = 'Page not found';
            } else {
                $title = get_the_archive_title();
            }
        } else {
            if(is_home()){
                $id = get_option('page_for_posts');
                if($id){
                    $title = get_field('h1_title', $id) ? get_field('h1_title', $id) : get_the_title($id);
                } else {
                    $title = get_bloginfo('name') . ": Posts";
                }

            }  else{
                $id = $post->ID;
                $title = get_field('h1_title', $id) ? get_field('h1_title', $id) : get_the_title($id);
            }
        }
    }
    if($tags){
        if(get_field('hide_h1_title', $id)){
            echo '<h1 class="sr-only">' . $title . '</h1>';
        } else {
            echo '<h1>' . $title . '</h1>';
        }
    } else {
        echo $title;
    }
}

/**
 * Determines if environment is production
 * @return boolean
 */
function is_prod(){
    if(!DISABLE_WP_ANALYTICS){
        return true;
    } else {
        return false;
    }
}

/**
 * Filters out tags used in sidebar callout
 * @param  string $content sidebar content
 * @return string          filtered sidebar content
 */
function callout_tags($content) {
  return strip_tags($content, '<strong><p><em><span><del><a><ul><ol><li><blockquote><h2><h3><h4><h5><h6><pre><br><hr><i>');
}

/**
 * Generates custom excerpt to display on posts page
 * @param  integer $id    ID of post
 * @param  string $after Content to display after excerpt text
 * @return string        Excerpt text
 */
function gp_excerpt($id = null, $after = null){
    if($id == null){
      global $post;
      $id = $post->post_ID;
    }
    $postobj = get_post($id);

    if($postobj->post_excerpt){
      echo $postobj->post_excerpt . $after;
    } else {
      echo wp_trim_words(strip_shortcodes($postobj->post_content), 30, $after);
    }
}

/**
 * Generates list of featured authors for post
 * @param  integer  $id    ID of post
 * @param  boolean $links  Whether to display names as links or just text
 * @return string          Unordered list of featured authors
 */
function the_featured_author($id = null, $links = false){
  global $post;
  $id = $id == null ? $post->ID : $id;
  $terms = get_the_terms($id, 'featured-author');
  $output = "<ul class='post-categories'>";
  if($terms){
    if($links){
      foreach($terms as $term){
        $link = get_term_link($term->term_id);
        $output .= "<li><a href='{$link}'>{$term->name}</a></li>";
      }
    } else {
      foreach($terms as $term){
        $link = get_term_link($term->term_id);
        $output .= "<li>{$term->name}</li>";
      }
    }

  } else {
    if($links){
      $output .= "<li><a href='".get_author_posts_url($post->post_author)."'>".get_the_author($id)."</a></li>";
    } else {
      $output .= "<li>".get_the_author($id)."</li>";
    }

  }
  echo $output . '</ul>';
}

/**
 * Generates list of categories associated with post
 * @param  integer $id ID of post
 * @return string     Unordered list of categories
 */
function the_categories($id = null){
  global $post;
  $categories = get_the_category($post->ID);
  $output = "<ul class='post-categories'>";
  foreach($categories as $cat){
    $output .= "<li>{$cat->name}</li>";
  }
  $output .= "</ul>";
  echo $output;
}

/**
 * Returns menu from homepage_tools table
 * @param  integer  $id    ID of menu to retrieve
 * @param  boolean $chunk  Whether or not to group menu items
 * @return array           Array of menu items
 */
function get_menu_from_tools($id, $chunk = false)	{
    $resources = \Database\Homepage::query("
        SELECT menus_items.link, menus_items.link_text, menus_items.is_external, menus.title
        FROM `menus_items`
        INNER JOIN `menus`
        ON menus.id = menus_items.homepage_menus_id
        WHERE menus.id = ?
        ORDER BY menus_items.sort_order ASC
    ", array($id));
  if ($chunk) {
    if(empty($resources)) {
      return array();
    }
    $columns = array_chunk($resources, ceil(count($resources) / 4));
    return $columns;
  } else {
    return $resources;
  }
}

/**
 * Formats 10 digit phone number
 * @param   string  $phone  Phone number string
 * @return  string          Formatted phone number
 */
function output_phone( $phone ) {
  $formatted_phone = "(" . substr( $phone, 0, 3 ) . ") " . substr( $phone, 3, 3) . "-" . substr( $phone, 6, 4);
  echo $formatted_phone;
}

/**
 * Generates paginated links using bootstrap pagination element
 * @param  boolean $echo Whether or not to echo pagination
 * @return string        Bootstrap list of pages
 */
function bootstrap_pagination( $echo = true ) {
    global $wp_query;
    $big = 999999999; // need an unlikely integer
    $pages = paginate_links( array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $wp_query->max_num_pages,
            'type'  => 'array',
            'prev_next'   => true,
            'prev_text'    => '<span class="fa fa-chevron-left" aria-hidden="true"></span> Prev',
            'next_text'    => 'Next <span class="fa fa-chevron-right" aria-hidden="true"></span>',
        )
    );
    if( is_array( $pages ) ) {
        $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
        $pagination = '<div class="pagination-wrap"><ul class="pagination">';
        foreach ( $pages as $page ) {
            $pagination .= "<li>$page</li>";
        }
        $pagination .= '</ul></div>';
        if ( $echo ) {
            echo $pagination;
        } else {
            return $pagination;
        }
    }
}
