<?php

$element = $variables['element'];
$element['#attributes']['type'] = 'submit';
element_set_attributes($element, array('id', 'name', 'value'));
$value = $element['#attributes']['value'];

$element['#attributes']['class'][] = 'form-' . $element['#button_type'];
if (!empty($element['#attributes']['disabled'])) {
  $element['#attributes']['class'][] = 'form-button-disabled';
}

print '<button' . drupal_attributes($element['#attributes']) . '>' . $value . '</button>';
