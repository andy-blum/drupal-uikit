<?php
$element = $variables['element'];
$sub_menu = '';

if ($element['#below']) {
  $sub_menu = drupal_render($element['#below']);
}

$output = l($element['#title'], $element['#href'], $element['#localized_options']);
print '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
