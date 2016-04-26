<?php

/**
 * @file
 * Returns HTML for a set of filter tips.
 */

$tips = $variables['tips'];
$long = $variables['long'];
$output = '';

$multiple = count($tips) > 1;
if ($multiple) {
  $output = '<h2>' . t('Text Formats') . '</h2>';
}

if (count($tips)) {
  if ($multiple) {
    $output .= '<div class="compose-tips">';
  }
  foreach ($tips as $name => $tiplist) {
    if ($multiple) {
      $output .= '<div class="filter-type filter-' . drupal_html_class($name) . '">';
      $output .= '<h3>' . check_plain($name) . '</h3>';
    }

    if (count($tiplist) > 0) {
      $output .= '<ul class="tips uk-list uk-list-space">';
      foreach ($tiplist as $tip) {
        $output .= '<li' . ($long ? ' id="filter-' . str_replace("/", "-", $tip['id']) . '">' : '>') . $tip['tip'] . '</li>';
      }
      $output .= '</ul>';
    }

    if ($multiple) {
      $output .= '</div><hr class="uk-article-divider">';
    }
  }
  if ($multiple) {
    $output .= '</div>';
  }
}

print $output;
