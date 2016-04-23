<?php

/**
 * @file
 * Returns HTML for a tags field.
 */

$output = '';

// Render the label, if it's not hidden.
if (!$variables['label_hidden']) {
  $output .= '<div class="uk-h4"' . $variables['title_attributes'] . '>' . $variables['label'] . '</div>';
}

// Render the items.
$output .= '<ul class="uk-subnav"' . $variables['content_attributes'] . '>';
foreach ($variables['items'] as $delta => $item) {
  $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
  $output .= '<li class="' . $classes . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</li>';
}
$output .= '</ul>';

// Render the top-level DIV.
$output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

print $output;
