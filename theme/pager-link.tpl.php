<?php

/**
 * @file
 * Returns HTML for a link to a specific query result page.
 */

$text = $variables['text'];
$page_new = $variables['page_new'];
$element = $variables['element'];
$parameters = $variables['parameters'];
$attributes = $variables['attributes'];

$page = isset($_GET['page']) ? $_GET['page'] : '';
if ($new_page = implode(',', pager_load_array($page_new[$element], $element, explode(',', $page)))) {
  $parameters['page'] = $new_page;
}

$query = array();
if (count($parameters)) {
  $query = drupal_get_query_parameters($parameters, array());
}
if ($query_pager = pager_get_query_parameters()) {
  $query = array_merge($query, $query_pager);
}

// Set each pager link title.
if (!isset($attributes['title'])) {
  static $titles = NULL;
  if (!isset($titles)) {
    $titles = array(
      t('<span><i class="uk-icon-angle-double-left"></i> First</span>') => t('Go to first page'),
      t('<span><i class="uk-icon-angle-left"></i> Previous</span>') => t('Go to previous page'),
      t('<span>Next <i class="uk-icon-angle-right"></i></span>') => t('Go to next page'),
      t('<span>Last <i class="uk-icon-angle-double-right"></i></span>') => t('Go to last page'),
    );
  }
  if (isset($titles[$text])) {
    $attributes['title'] = $titles[$text];
  }
  elseif (is_numeric($text)) {
    $attributes['title'] = t('Go to page @number', array('@number' => $text));
  }
}

$attributes['href'] = url($_GET['q'], array('query' => $query));
print '<a' . drupal_attributes($attributes) . '>' . $text . '</a>';
