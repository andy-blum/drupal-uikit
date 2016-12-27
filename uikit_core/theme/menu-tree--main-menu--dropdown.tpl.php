<?php

/**
 * @file
 * Returns HTML for a wrapper for a menu sub-tree.
 *
 * Available variables:
 * - $variables['tree']: An HTML string containing the tree's items.
 *
 * @see template_preprocess_menu_tree()
 * @see uikit_preprocess_page()
 * @see theme_menu_tree()
 *
 * @ingroup uikit_themeable
 */

$output = '<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom">';
$output .= '<ul class="uk-nav uk-nav-navbar">' . $variables['tree'] . '</ul>';
$output .= '</div>';
print $output;
