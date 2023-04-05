<?php
$menu = get_nav_menu_locations();
if (!empty($menu["secondary"])):
  $output = "<div id='dept-footer'><div class='row'>";
  $menuItems = wp_get_nav_menu_items($menu["secondary"]);
  for ($i=0; $i<=count($menuItems)-1;$i++){
    if ($menuItems[$i]->menu_item_parent == 0){
      $footerMenu[] = "<ul><li class='ecu-footer-link'><a href='" . esc_url($menuItems[$i]->url) . "'>" . esc_html($menuItems[$i]->title) . "</a></li>" . getMenuChildren($menuItems, $menuItems[$i]) . "</ul>";
    }
  }
  for ($i=0;$i<=count($footerMenu)-1;$i++){
    switch ($i % 4){
      case 0:
        $col1 .= $footerMenu[$i];
        break;
      case 1:
        $col2 .= $footerMenu[$i];
        break;
      case 2:
        $col3 .= $footerMenu[$i];
        break;
      case 3:
        $col4 .= $footerMenu[$i];
        break;
    }
  }
  switch (count($footerMenu)){
    case 2:
      $output .= "<div class='col-md-6'>" . $col1 . "</div><div class='col-md-6'>" . $col2 . "</div>";
      break;
    case 3:
      $output .= "<div class='col-md-4'>" . $col1 . "</div><div class='col-md-4'>" . $col2 . "</div><div class='col-md-4'>" . $col3 . "</div>";
      break;
    default:
      $output .= "<div class='col-md-3'>" . $col1 . "</div><div class='col-md-3'>" . $col2 . "</div><div class='col-md-3'>" . $col3 . "</div><div class='col-md-3'>" . $col4 . "</div>";
      break;
    }
  $output .= "</div></div>";
  echo $output;
endif;

function getMenuChildren($menuItems, $parent) {
  for($i=0;$i<=count($menuItems)-1;$i++){
    if ($menuItems[$i]->menu_item_parent == $parent->ID){
      $childMenu .= "<li><a href='" . esc_url($menuItems[$i]->url) . "'>" . esc_html($menuItems[$i]->title) . "</a></li>";
    }
  }
  if ($childMenu){
    $childMenu = "<li><ul>" . $childMenu . "</ul></li>";
  }
  return $childMenu;
}
