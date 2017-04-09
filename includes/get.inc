<?php

/**
 * @file
 * Common get functions.
 */

function uikit_get_theme_setting($setting) {
  return theme_get_setting($setting, uikit_get_active_theme());
}

function uikit_get_active_theme() {
  $active_theme = \Drupal::theme()->getActiveTheme();
  return $active_theme->getName();
}

function uikit_get_uikit_library() {
  // Get the active theme's base style theme setting.
  $base_style = uikit_get_theme_setting('base_style');

  switch ($base_style) {
    // Define the UIkit base theme library to return.
    case 'almost-flat':
      return 'uikit/uikit.almost-flat';
      break;

    case 'gradient':
      return 'uikit/uikit.gradient';
      break;

    default:
      return 'uikit/uikit';
  }
}

function uikit_get_component_library($component) {
  // Get the active theme's base style theme setting so the correct component
  // stylesheet is retrieved.
  $base_style = uikit_get_theme_setting('base_style');

  switch ($base_style) {
    // Define the UIkit base theme library to return.
    case 'almost-flat':
      return "uikit/uikit.$component.almost-flat";

    case 'gradient':
      return "uikit/uikit.$component.gradient";

    default:
      return "uikit/uikit.$component";
  }
}
