<?php

/**
 * @file
 * Returns HTML for a textfield form element.
 */

$element = $variables['element'];
$name = !empty($element['#name']) ? $element['#name'] : FALSE;
$search = $name && $name = 'search_block_form';
element_set_attributes($element, array(
  'id',
  'name',
  'value',
  'size',
  'maxlength',
));
_form_set_class($element, array('form-text'));

if (!$search) {
  $element['#attributes']['type'] = 'text';
}
else {
  $element['#attributes']['type'] = 'search';
  $element['#attributes']['class'][] = 'uk-search-field';
  $element['#attributes']['placeholder'] = 'Search ...';
}

$extra = '';
if ($element['#autocomplete_path'] && !empty($element['#autocomplete_input'])) {
  drupal_add_library('system', 'drupal.autocomplete');
  $element['#attributes']['class'][] = 'form-autocomplete';

  if ($search) {
    $element['#attributes']['autocomplete'] = 'off';
  }

  $attributes = array();
  $attributes['type'] = 'hidden';
  $attributes['id'] = $element['#autocomplete_input']['#id'];
  $attributes['value'] = $element['#autocomplete_input']['#url_value'];
  $attributes['disabled'] = 'disabled';
  $attributes['class'][] = 'autocomplete';
  $extra = '<input' . drupal_attributes($attributes) . ' />';
}

$output = '<input' . drupal_attributes($element['#attributes']) . ' />';

print $output . $extra;
