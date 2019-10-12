<?php

/**
 * Implements hook_preprocess().
 */
function uikit_preprocess(&$variables, $hook) {

}

/**
 * Implements hook_preprocess_HOOK() for html.html.twig.
 */
function uikit_preprocess_html(array &$variables) {
  /* Add class to html tag */
  //$variables['html_attributes']->addClass('no-js');

  // Don't display the site name twice on the front page (and potentially others)
  /*if (isset($variables['head_title_array']['title']) && isset($variables['head_title_array']['name']) && ($variables['head_title_array']['title'] == $variables['head_title_array']['name'])) {
    $variables['head_title'] = $variables['head_title_array']['name'];
  }*/
}

/**
 * Implements hook_preprocess_page() for page.html.twig.
 */
function uikit_preprocess_page(array &$variables) {

}

/**
 * Implements hook_preprocess_HOOK() for Block document templates.
 */
function uikit_preprocess_block(array &$variables) {

}

/**
 * Implements hook_preprocess_node().
 */
function uikit_preprocess_node(array &$variables) {
  // Default to turning off byline/submitted.
  //$variables['display_submitted'] = FALSE;
}

/**
 * Implements hook_preprocess_form().
 */
function uikit_preprocess_form(array &$variables) {
  //$variables['attributes']['novalidate'] = 'novalidate';
}

/**
 * Implements hook_preprocess_select().
 */
function uikit_preprocess_select(array &$variables) {
  //$variables['attributes']['class'][] = 'select-chosen';
}

/**
 * Implements hook_preprocess_field().
 */
function uikit_preprocess_field(array &$variables, $hook) {
  /*switch ($variables['element']['#field_name']) {
  }*/
}

/**
 * Implements hook_preprocess_details().
 */
function uikit_preprocess_details(array &$variables) {
  /*$variables['attributes']['class'][] = 'details';
  $variables['summary_attributes']['class'] = 'summary';*/
}

/**
 * Implements hook_preprocess_menu_local_task().
 */
function uikit_preprocess_menu_local_task(array &$variables) {
  //$variables['element']['#link']['url']->setOption('attributes', ['class'=>'rounded']);
}