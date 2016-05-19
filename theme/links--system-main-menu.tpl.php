<?php

/**
 * @file
 * Returns HTML for the main menu links.
 */

$menu_name = variable_get('menu_main_links_source', 'main-menu');
$menu_tree = menu_tree($menu_name);
$menu_tree['#theme_wrappers'] = array('menu_tree__system_main');
$dropdown_support = theme_get_setting('main_menu_dropdown_support');

foreach ($menu_tree as $key => $value) {
  if (isset($value['#below']) && !empty($value['#below']) && $dropdown_support) {
    $theme = drupal_get_path('theme', 'uikit');
    drupal_add_js($theme . '/js/core/dropdown.js', array(
      'type' => 'file',
      'group' => JS_THEME,
    ));

    // Add required attributes for dropdown support.
    $menu_tree[$key]['#attributes']['class'][] = 'uk-parent';
    $menu_tree[$key]['#attributes']['data-uk-dropdown'] = '';

    // Create custom theme wrapper to theme the sub-menu.
    $menu_tree[$key]['#below']['#theme_wrappers'] = array('menu_tree__system_main__sub_menu');

    // Remove the expanded class.
    $classes = isset($menu_tree[$key]['#attributes']['class']) ? $menu_tree[$key]['#attributes']['class'] : array();
    $expanded = array_keys($classes, 'expanded');

    foreach ($expanded as $expanded_key) {
      unset($menu_tree[$key]['#attributes']['class'][$expanded_key]);
    }
  }
  elseif (isset($value['#below']) && !empty($value['#below']) && !$dropdown_support) {
    // Make sure the sub-menus are empty when dropdown support is disabled.
    $menu_tree[$key]['#below'] = '';
  }
}
print drupal_render($menu_tree);
