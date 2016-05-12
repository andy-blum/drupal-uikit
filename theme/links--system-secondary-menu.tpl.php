<?php

/**
 * @file
 * Returns HTML for the secondary menu links.
 */

$menu_name = variable_get('menu_secondary_links_source', 'user-menu');
$menu_tree = menu_tree($menu_name);
$menu_tree['#theme_wrappers'] = array('menu_tree__system_secondary');
$dropdown_support = theme_get_setting('secondary_menu_dropdown_support');

foreach ($menu_tree as $key => $value) {
  if (isset($value['#below']) && !empty($value['#below']) && $dropdown_support) {
    $theme = drupal_get_path('theme', 'uikit');
    drupal_add_js($theme . '/js/core/dropdown.js', array(
      'type' => 'file',
      'group' => JS_THEME,
    ));
    $menu_tree[$key]['#attributes']['class'][] = 'uk-parent';
    $menu_tree[$key]['#attributes']['data-uk-dropdown'] = '';
    $menu_tree[$key]['#below']['#theme_wrappers'] = array('menu_tree__system_secondary__sub_menu');
  }
  elseif (isset($value['#below']) && !empty($value['#below']) && !$dropdown_support) {
    $menu_tree[$key]['#below'] = '';
  }
}
print drupal_render($menu_tree);
