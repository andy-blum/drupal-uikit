<?php

$output = '';

if (!empty($variables['primary'])) {
  $variables['primary']['#prefix'] = '<h2 class="element-invisible">' . t('Primary tabs') . '</h2>';
  $variables['primary']['#prefix'] .= '<ul class="uk-subnav uk-subnav-pill">';
  $variables['primary']['#suffix'] = '</ul>';
  $output .= drupal_render($variables['primary']);
}
if (!empty($variables['secondary'])) {
  $variables['secondary']['#prefix'] = '<h2 class="element-invisible">' . t('Secondary tabs') . '</h2>';
  $variables['secondary']['#prefix'] .= '<ul class="uk-subnav">';
  $variables['secondary']['#suffix'] = '</ul>';
  $output .= drupal_render($variables['secondary']);
}

print $output;
