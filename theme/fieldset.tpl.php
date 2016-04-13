<?php

$element = $variables['element'];
element_set_attributes($element, array('id'));
_form_set_class($element, array('form-wrapper'));

// Grouped fieldsets are fieldsets grouped together, such as vertical tabs.
$group_fieldset = isset($element['#group_fieldset']) && $element['#group_fieldset'];

$output = '<fieldset' . drupal_attributes($element['#attributes']) . '>';

if (!empty($element['#title'])) {
  if (!$group_fieldset) {
    // Use an H3 element for non-grouped fieldset titles.
    $output .= '<h3 class="uk-accordion-title"><span class="fieldset-legend">' . $element['#title'] . '</span></h3>';
  }
  else {
    // Always wrap fieldset legends in a SPAN for CSS positioning.
    $output .= '<legend><span class="fieldset-legend">' . $element['#title'] . '</span></legend>';
  }
}

if (!$group_fieldset) {
  // Use the uk-accordion-content class for non-grouped fieldsets.
  $output .= '<div class="uk-accordion-content">';
}
else {
  $output .= '<div class="fieldset-wrapper uk-margin-top uk-margin-bottom">';
}

if (!empty($element['#description'])) {
  $output .= '<div class="uk-form-help-block">' . $element['#description'] . '</div>';
}

$output .= $element['#children'];

if (isset($element['#value'])) {
  $output .= $element['#value'];
}

$output .= '</div>';
$output .= "</fieldset>\n";

print $output;
