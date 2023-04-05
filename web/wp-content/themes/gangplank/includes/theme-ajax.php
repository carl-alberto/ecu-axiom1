<?php
add_action("wp_ajax_post_banner_validate", "post_banner_validate",10,0);
add_action("wp_ajax_nopriv_post_banner_validate", "post_banner_validate",10,0);
function post_banner_validate () {
  $id = sanitize_text_field($_POST['id']) ? sanitize_text_field($_POST['id']) : false;
  if($id){
    if($banner = get_field('banner_image', $id)){
      wp_send_json_success($banner);
    } else {
      wp_send_json_error('This post does not have an associated banner image and will not be displayed as a slide. Please edit the post and add a banner image.');
    };
  } else {
    wp_send_json_error('Please select a valid post');
  }
}

function load_link_farms($field) {
    // reset choices
    $field['choices'] = array();

    $args = array(
      'post_type' => 'ui-elements',
      'meta_query' => array(
        array(
          'key' => 'element_type',
          'value' => 'link_farm',
        )
      )
    );
    $farms = get_posts($args);
    foreach($farms as $farm){
      $field['choices'][$farm->ID] = $farm->post_title;
    }
    return $field;

}

add_filter('acf/load_field/name=footer_link_farm', 'load_link_farms');
