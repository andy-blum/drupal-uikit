<?php

/**
 * @file
 * Returns HTML for a single local action link.
 */

$link = $variables['element']['#link'];

$output = '<li class="uk-active">';
if (isset($link['href'])) {
  $output .= l($link['title'], $link['href'], isset($link['localized_options']) ? $link['localized_options'] : array());
}
elseif (!empty($link['localized_options']['html'])) {
  $output .= $link['title'];
}
else {
  $output .= check_plain($link['title']);
}
$output .= "</li>\n";

print $output;
