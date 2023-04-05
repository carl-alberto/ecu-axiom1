<?php

namespace OUR\OUTPUT\ELEMENT;

/**
 * Register settings
 */
function register_output_head_setting() {
   register_setting( 'custom_output_head_group', 'custom_output_head' );
}
add_action( 'admin_init', __NAMESPACE__ . '\register_output_head_setting' );

/**
 * add menu items
 */
function output_head_menu() {
  add_submenu_page('edit.php?post_type=output_element', 'Head Output', 'Head Output', 'administrators', 'custom_output_head', __NAMESPACE__ . '\custom_output_head_page');
}
add_action('admin_menu',  __NAMESPACE__ . '\output_head_menu');


/**
 * Render Options page
 */
function custom_output_head_page()      {
?>
  <div>
  <h2>Custom Head Output</h2>
  <form method="post" action="options.php">
  <?php settings_fields( 'custom_output_head_group' ); ?>
  <p>Anything entered here will be saved and attached to the head of your wordpress site.</p>
  <table>
    <tr valign="top">
            <td>
                <?php
                    echo '<textarea name="custom_output_head" id="custom_output_head" rows="25" cols="125" />'. get_option( 'custom_output_head', '' ) .'</textarea>';
                ?>
            </td>
    </tr>
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php

}


/**
 * Attach output  to head
 */
add_action('wp_head', __NAMESPACE__ . '\get_custom_head_output');
function get_custom_head_output() {
    $output = get_option('custom_output_head', '');
    if (!empty($output)) {
      echo $output;
    }
}