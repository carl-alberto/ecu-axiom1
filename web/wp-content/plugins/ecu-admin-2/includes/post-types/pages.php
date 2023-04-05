<?php
defined( 'ABSPATH' ) OR exit;

// Sets custom column for page post type
add_action('manage_page_posts_columns', 'page_add_columns');
function page_add_columns($columns){
  $new = array();
  foreach($columns as $key => $value){
    if($key == 'author') {
      $new['permalink'] =  __('Permalink');
    }
    $new[$key] = $value;
  }
  return $new;
}

// Outputs content for page post type
add_action('manage_page_posts_custom_column', 'page_output_columns', 10, 2);
function page_output_columns($column, $post_id){
  switch($column){
    case 'permalink':
      $perma = get_the_permalink($post_id);
      echo "<a href='{$perma}'>{$perma}</a>";
    break;
  }
}