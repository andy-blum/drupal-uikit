<?php

/**
 * @file
 * Set up variables to be placed within the template (.html.twig) files.
 *
 * The variables set up here apply to both templates (.html.twig) files and
 * functions (theme_HOOK). These are also used for providing
 * @link https://www.drupal.org/node/2354645 Twig Template naming conventions @endlink.
 *
 * @see process.inc
 */

use Drupal\block\Entity\Block;

/**
 * Implements template_preprocess_block().
 */
function uikit_preprocess_block(&$variables) {
  $system_menu_block = $variables['base_plugin_id'] == 'system_menu_block';
  $block = Block::load($variables['elements']['#id']);
  $region = $block->getRegion();

  if ($system_menu_block) {
    switch ($region) {
      case 'navbar':
        $variables['content']['#theme'] = 'menu__navbar';
        break;

      case 'offcanvas':
        $variables['content']['#theme'] = 'menu__offcanvas';
        break;
    }
  }
}

/**
 * Implements hook_preprocess_HOOK() for feed-icon.html.twig.
 *
 * Feed icon on the front page is missing the site name, as reported in
 * @link https://www.drupal.org/node/2082657 Feed icon on the front page misses site title @endlink.
 * We are following this issue so we can make the appropriate changes when the
 * issue is fixed.
 */
function uikit_preprocess_feed_icon(&$variables) {
  $config = \Drupal::config('system.site');
  $variables['site_name'] = $config->get('name');
}

/**
 * Implements template_preprocess_form_element().
 */
function uikit_preprocess_form_element(&$variables) {
  $element = $variables['element'];

  // Grouped form elements do not need the uk-form-row class. This adds a
  // grouped variable to use in form-element.html.twig.
  $groups = isset($element['#groups']) && is_array($element['#groups']);
  $variables['grouped'] = FALSE;
  if ($groups) {
    $variables['grouped'] = TRUE;
  }
}

/**
 * Implements template_preprocess_HOOK().
 */
function uikit_preprocess_user(&$variables) {
  // $variables['content']['member_for'] doesn't style well. Instead of
  // overriding with CSS, we reformat the markup used.
  $created = $variables['elements']['#user']->getCreatedTime();
  $created_diff = \Drupal::service('date.formatter')->formatTimeDiffSince($created);
  $member_for = '<strong>' . t('Member for') . '</strong> ' . $created_diff;
  $variables['content']['member_for']['#markup'] = $member_for;
}
