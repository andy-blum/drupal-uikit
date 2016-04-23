<?php

/**
 * @file
 * Returns HTML for a form element label and required marker.
 */

$element = $variables['element'];
$display = isset($element['#title_display']) ? $element['#title_display'] : 'before';
$type = !empty($element['#type']) ? $element['#type'] : FALSE;
$checkbox = $type && $type === 'checkbox';
$radio = $type && $type === 'radio';

if (!$checkbox && !$radio) {
  $attributes['class'][] = 'uk-form-label';
}

// Extract variables.
$output = '';

$title = !empty($element['#title']) ? filter_xss_admin($element['#title']) : '';

// Only show the required marker if there is an actual title to display.
if ($title && $required = !empty($element['#required']) ? theme('form_required_marker', array('element' => $element)) : '') {
  $title .= ' ' . $required;
}

// Immediately return if the element is not a checkbox or radio and there is
// no label to be rendered.
if (!$checkbox && !$radio && ($display === 'none' || !$title)) {
  return '';
}

// Add the necessary 'for' attribute if the element ID exists.
if (!empty($element['#id'])) {
  $attributes['for'] = $element['#id'];
}

// Checkboxes and radios must construct the label differently.
if ($checkbox || $radio) {
  if ($display === 'before') {
    $output .= $title;
  }
  elseif ($display === 'none' || $display === 'invisible') {
    $output .= '<span class="element-invisible">' . $title . '</span>';
  }
  // Inject the rendered checkbox or radio element inside the label.
  if (!empty($element['#children'])) {
    $output .= $element['#children'];
  }
  if ($display === 'after') {
    $output .= $title;
  }
}
// Otherwise, just render the title as the label.
else {
  // Show label only to screen readers to avoid disruption in visual flows.
  if ($display === 'invisible') {
    $attributes['class'][] = 'element-invisible';
  }
  $output .= $title;
}

// The leading whitespace helps visually separate fields from inline labels.
print ' <label' . drupal_attributes($attributes) . '>' . $output . "</label>\n";
