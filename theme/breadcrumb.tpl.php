<?php

$breadcrumb = $variables['breadcrumb'];

if (!empty($breadcrumb)) {
  // Provide a navigational heading to give context for breadcrumb links to
  // screen-reader users. Make the heading invisible with .element-invisible.
  $output = '<h2 class="uk-hidden">' . t('You are here') . '</h2>';

  $output .= '<ul class="uk-breadcrumb">';

  foreach ($breadcrumb as $crumb) {
    $output .= '<li>' . $crumb . '</li>';
  }

  $output .= '</ul>';
  print $output;
}
