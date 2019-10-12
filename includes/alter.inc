<?php

/**
 * Implements hook_page_attachments_alter().
 */
function uikit_page_attachments_alter(array &$page) {
  // Tell IE to use latest rendering engine (not to use compatibility mode).
  /*$ie_edge = [
    '#type' => 'html_tag',
    '#tag' => 'meta',
    '#attributes' => [
    'http-equiv' => 'X-UA-Compatible',
    'content' => 'IE=edge',
    ],
  ];
  $page['#attached']['html_head'][] = [$ie_edge, 'ie_edge'];*/
}

/**
 * Implements hook_theme_suggestions_page_alter().
 */
function uikit_theme_suggestions_page_alter(array &$suggestions, array $variables) {

}

/**
 * Implements hook_theme_suggestions_node_alter().
 */
function uikit_theme_suggestions_node_alter(array &$suggestions, array $variables) {
  /*$node = $variables['elements']['#node'];

  if ($variables['elements']['#view_mode'] == "full") {

  }*/
}

/**
 * Implements hook_theme_suggestions_field_alter().
 */
function uikit_theme_suggestions_field_alter(array &$suggestions, array $variables) {
  /*$element = $variables['element'];
  $suggestions[] = 'field__' . $element['#view_mode'];
  $suggestions[] = 'field__' . $element['#view_mode'] . '__' . $element['#field_name'];*/
}

/**
 * Implements hook_theme_suggestions_field_alter().
 */
function uikit_theme_suggestions_fieldset_alter(array &$suggestions, array $variables) {
  /*$element = $variables['element'];
  if (isset($element['#attributes']['class']) && in_array('form-composite', $element['#attributes']['class'])) {
    $suggestions[] = 'fieldset__form_composite';
  }*/
}

/**
 * Implements hook_theme_suggestions_views_view_alter().
 */
function uikit_theme_suggestions_views_view_alter(array &$suggestions, array $variables) {

}

/**
 * Implements hook_theme_suggestions_details_alter().
 */
function uikit_theme_suggestions_details_alter(array &$suggestions, array $variables) {

}