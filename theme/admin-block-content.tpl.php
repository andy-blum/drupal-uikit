<?php

/**
 * @file
 * Returns HTML for the content of an administrative block.
 */

$content = $variables['content'];
$output = '';

if (!empty($content)) {
  $class = 'uk-description-list-line';
  if ($compact = system_admin_compact_mode()) {
    $class .= ' compact';
  }
  $output .= '<dl class="' . $class . '">';
  foreach ($content as $item) {
    $output .= '<dt>' . l($item['title'], $item['href'], $item['localized_options']) . '</dt>';
    if (!$compact && isset($item['description'])) {
      $output .= '<dd>' . filter_xss_admin($item['description']) . '</dd>';
    }
  }
  $output .= '</dl>';
}
print $output;
