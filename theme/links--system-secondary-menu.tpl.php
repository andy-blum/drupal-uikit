<?php

/**
 * @file
 * Returns HTML for the secondary menu links.
 */

$menu_name = variable_get('menu_secondary_links_source', 'user-menu');
$menu_tree = menu_tree($menu_name);
$menu_tree['#theme_wrappers'] = array('menu_tree__system_secondary');
$library_path = _uikit_get_library_path();

// Create custom theme wrapper to theme the offcanvas menu.
if (in_array('uk-nav-offcanvas', $variables['attributes']['class'])) {
  $menu_tree['#theme_wrappers'] = array('menu_tree__system_secondary__offcanvas');
}

$dropdown_support = theme_get_setting('secondary_menu_dropdown_support');

if ($dropdown_support) {
  drupal_add_js($library_path . '/js/core/dropdown.js', array(
    'type' => 'file',
    'group' => JS_THEME,
  ));
}

foreach ($menu_tree as $key => $value) {
  if (isset($value['#below']) && !empty($value['#below']) && $dropdown_support) {
    $menu_tree[$key]['#attributes']['class'][] = 'uk-parent';
    $menu_tree[$key]['#attributes']['data-uk-dropdown'] = '';
    $menu_tree[$key]['#below']['#theme_wrappers'] = array('menu_tree__system_secondary__sub_menu');
  }
  elseif (isset($value['#below']) && !empty($value['#below']) && !$dropdown_support) {
    $menu_tree[$key]['#below'] = '';
  }
}
print drupal_render($menu_tree);
