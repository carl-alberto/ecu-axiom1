<?php
defined( 'ABSPATH' ) OR exit;

// Defines UI element cpt
add_action('init', 'ecu_cpt_ui_elements',10,0);
function ecu_cpt_ui_elements() {
	$labels = array(
		'name' => 'UI Elements',
		'singular_name' => 'UI Element',
		'menu_name' => 'UI Elements',
		'name_admin_bar' => 'UI Element',
		'add_new' => 'Create Element',
		'add_new_item' => 'Create New Element',
		'new_item' => 'New Element',
		'edit_item' => 'Edit Element',
		'view_item' => 'View Element',
		'all_items' => 'All Elements',
		'search_items' => 'Search Elements',
		'parent_item_colon' => 'Parent Elements:',
		'not_found' => 'Element Not Found',
		'not_found_in_trash' => 'Element Not Found in Trash'
	);

	$args = array(
		'labels' => $labels,
		'description' => 'Allows for custom UI elements to be developed for shortcake integration',
		'public' => false,
		'exclude_from_search' => false,
		'show_ui' => true,
		'supports' => array('title'),
		'has_archive' => false,
		'rewrite' => false,
		'menu_icon' => 'dashicons-layout',
	);

	if(get_option('stylesheet') == 'gangplank-second-level'){
		$args['capability_type'] = array('ui_element', 'ui_elements');
		$args['map_meta_cap'] = true;
	}
	register_post_type( 'ui-elements', $args);
}

// Updates messages related to UI elements post type
add_filter( 'post_updated_messages', 'ecu_cpt_ui_elements_messages' );
function ecu_cpt_ui_elements_messages( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );

	$messages['ui-elements'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'UI Element updated.', 'crowsnest' ),
		2  => __( 'UI Element field updated.', 'crowsnest' ),
		3  => __( 'UI Element deleted.', 'crowsnest' ),
		4  => __( 'UI Element updated.', 'crowsnest' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'UI Element restored to revision from %s', 'crowsnest' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'UI Element published.', 'crowsnest' ),
		7  => __( 'UI Element saved.', 'crowsnest' ),
		8  => __( 'UI Element submitted.', 'crowsnest' ),
		9  => sprintf(
			__( 'UI Element scheduled for: <strong>%1$s</strong>.', 'crowsnest' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', 'crowsnest' ), strtotime( $post->post_date ) )
		),
		10 => __( 'UI Element draft updated.', 'crowsnest' )
	);

	return $messages;
}

// Sets custom column for ui_elements cpt
add_action('manage_ui-elements_posts_columns', 'ecu_ui_elements_add_columns');
function ecu_ui_elements_add_columns($columns){
  return array_merge($columns,
    array(
      'element_type' => __('Element Type')
    )
  );
}

// Outputs content for ui_elements
add_action('manage_ui-elements_posts_custom_column', 'ecu_ui_elements_output_columns', 10, 2);
function ecu_ui_elements_output_columns($column, $post_id){
  switch($column){
    case 'element_type':
      $meta = get_post_meta($post_id, 'element_type');
      if($meta[0] === 'tabbed_grid'){
        $type = 'Post Grid';
      } else {
        $type = ucwords(str_replace('_', ' ', $meta[0]));
      }
      echo $type;
    break;
  }
}

// Selects default ui_type based on GET param
add_filter('acf/load_field/name=element_type', 'ecu_acf_default_ui_value');
function ecu_acf_default_ui_value( $field ) {
  if($_GET['ui-type']){
    $type = sanitize_text_field($_GET['ui-type']);
    $field['choices'] = array();
    $field['choices'][$type] = $type;
  }
   return $field;
}

add_filter('acf/load_field/name=element_type', 'departmental_shortcodes');
function departmental_shortcodes($field){
	global $dptshortcodes;
	if(is_array($dptshortcodes)) {
        foreach( $dptshortcodes as $choice ) {
            $field['choices'][ $choice ] = ucwords(str_replace("_", " ", $choice));
        }
    }
    return $field;
}

// Force UI element post title
add_action('admin_init', 'ecu_force_ui_element_title',10,0);
add_action('edit_form_advanced', 'ecu_force_ui_element_title',10,0);
function ecu_force_ui_element_title() {
  if(get_post_type() == 'ui-elements'){
    echo "<script type='text/javascript'>\n";
    echo "
    jQuery('#publish').click(function(){
          var testervar = jQuery('[id^=\"titlediv\"]')
          .find('#title');
          if (testervar.val().length < 1)
          {
              jQuery('[id^=\"titlediv\"]').css('background', '#F96');
              setTimeout(\"jQuery('#ajax-loading').css('visibility', 'hidden');\", 100);
              alert('Please add a post title.');
              setTimeout(\"jQuery('#publish').removeClass('button-primary-disabled');\", 100);
              return false;
          }
      });
    ";
     echo "</script>\n";
  }
}
