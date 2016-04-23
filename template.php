<?php
/**
 * @file
 * Conditional logic and data processing for the UIkit theme.
 */

/**
 * Implements template_preprocess_page().
 */
function uikit_preprocess_page(&$variables) {
  $sidebar_first = $variables['page']['sidebar_first'];
  $sidebar_second = $variables['page']['sidebar_second'];

  // Assign content attributes.
  $variables['content_attributes_array']['id'] = 'page-content';
  $variables['content_attributes_array']['class'][] = 'uk-width-1-1';
  $variables['content_attributes_array']['class'][] = 'uk-margin-top-remove';

  // Assign sidebar_first attributes.
  $variables['sidebar_first_attributes_array'] = array(
    'id' => 'sidebar-first',
    'class' => array('uk-width-1-1', 'uk-width-medium-1-4'),
  );

  // Assign sidebar_second attributes.
  $variables['sidebar_second_attributes_array'] = array(
    'id' => 'sidebar-second',
    'class' => array('uk-width-1-1', 'uk-width-medium-1-4'),
  );

  // Assign additional content attributes if either sidebar is not empty.
  if (!empty($sidebar_first) && !empty($sidebar_second)) {
    $variables['content_attributes_array']['class'][] = 'uk-width-medium-2-4';
  }
  elseif (!empty($sidebar_first) || !empty($sidebar_second)) {
    $variables['content_attributes_array']['class'][] = 'uk-width-medium-3-4';
  }
}

/**
 * Implements template_process_page().
 */
function uikit_process_page(&$variables) {
  // Convert attributes array to an attribute string.
  $variables['sidebar_first_attributes'] = drupal_attributes($variables['sidebar_first_attributes_array']);
  $variables['sidebar_second_attributes'] = drupal_attributes($variables['sidebar_second_attributes_array']);
}

/**
 * Implements hook_preprocess_HOOK() for theme_button().
 */
function uikit_preprocess_button(&$variables) {
  // Add the uk-button class to all buttons.
  $variables['element']['#attributes']['class'][] = 'uk-button';
}

/**
 * Implements hook_preprocess_HOOK() for theme_container().
 */
function uikit_preprocess_container(&$variables) {
  $attributes = $variables['element']['#attributes'];
  $classes = isset($attributes['class']) ? $attributes['class'] : array();
  $checkbox = in_array('form-type-checkbox', $classes);
  $radio = in_array('form-type-radio', $classes);

  // Add the uk-margin-bottom class to all but checkbox and radio containers.
  if (!$checkbox && !$radio) {
    $variables['element']['#attributes']['class'][] = 'uk-margin-bottom';
  }

  // Remove container-inline classes.
  $type = !empty($variables['element']['#type']) ? $variables['element']['#type'] : FALSE;
  $container = $type && $type === 'container';
  if ($container) {
    foreach ($variables['element']['#attributes']['class'] as $key => $class) {
      if ($class == 'container-inline') {
        unset($variables['element']['#attributes']['class'][$key]);
        array_values($variables['element']['#attributes']['class']);
      }
    }
  }

  // Add the uk-button-group class to actions containers that contain more than
  // one action button.
  $actions = $type && $type === 'actions';
  if ($actions) {
    $i = 0;

    foreach ($variables as $key => $value) {
      if ($key == 'element') {
        foreach ($value as $item) {
          $access = isset($item['#access']) && !$item['#access'];
          $input = isset($item['#input']) && $item['#input'];

          if ($input) {
            $i++;
          }
          if ($access) {
            $i--;
          }
        }
      }
    }

    if ($i > 1) {
      $variables['element']['#attributes']['class'][] = 'uk-button-group';
    }
  }
}

/**
 * Implements hook_preprocess_HOOK() for theme_field().
 */
function uikit_preprocess_field(&$variables) {
  // Add top and bottom margin utility classes to image fields.
  $formatter = $variables['element']['#formatter'];

  if ($formatter == 'image') {
    $variables['classes_array'][] = 'uk-margin-top';
    $variables['classes_array'][] = 'uk-margin-bottom';
  }
}

/**
 * Implements hook_preprocess_HOOK() for theme_fieldset().
 */
function uikit_preprocess_fieldset(&$variables) {
  $collapsible = $variables['element']['#collapsible'];
  $group_fieldset = isset($variables['element']['#group_fieldset']) && $variables['element']['#group_fieldset'];

  // Collapsible, non-grouped fieldsets will use UIkit's accordion components.
  if ($collapsible && !$group_fieldset) {
    $variables['element']['#attributes']['class'][] = 'uk-accordion';
    $variables['element']['#attributes']['data-uk-accordion'] = '';

    foreach ($variables['element']['#attributes']['class'] as $key => $class) {
      if ($class == 'collapsible') {
        unset($variables['element']['#attributes']['class'][$key]);
        array_values($variables['element']['#attributes']['class']);
      }
    }
  }

  // Load accordion component stylesheet and script.
  $theme = drupal_get_path('theme', 'uikit');
  drupal_add_css($theme . '/css/components/accordion.gradient.min.css');
  drupal_add_js($theme . '/js/components/accordion.min.js',
    array('group' => JS_THEME)
  );
}

/**
 * Implements hook_preprocess_HOOK() for theme_form().
 */
function uikit_preprocess_form(&$variables) {
  // Add the uk-form class to all forms.
  $variables['element']['#attributes']['class'][] = 'uk-form';
  $variables['element']['#attributes']['class'][] = 'uk-form-stacked';

  // Load advanced form component stylesheets.
  $theme = drupal_get_path('theme', 'uikit');
  drupal_add_css($theme . '/css/components/form-advanced.min.css');
}

/**
 * Implements hook_preprocess_HOOK() for theme_form_element().
 */
function uikit_preprocess_form_element(&$variables) {
  $theme = drupal_get_path('theme', 'uikit');

  // Add the auto-margin utility attribute data-uk-margin.
  $variables['element']['#attributes']['data-uk-margin'] = '';

  // Load advanced form element component stylsheets.
  switch ($variables['element']['#type']) {
    case 'managed_file':
      drupal_add_css($theme . '/css/components/form-file.min.css');
      break;

    case 'password':
      drupal_add_css($theme . '/css/components/form-password.min.css');
      drupal_add_js($theme . '/js/components/form-password.min.js',
        array('group' => JS_THEME)
      );
      break;

    case 'select':
      drupal_add_css($theme . '/css/components/form-select.min.css');
      break;
  }
}

/**
 * Implements hook_preprocess_HOOK() for theme_menu_link().
 */
function uikit_preprocess_menu_link(&$variables) {
  $attributes = $variables['element']['#attributes'];

  // Remove the leaf class from menu links.
  if (isset($attributes['class'])) {
    foreach ($attributes['class'] as $key => $class) {
      if ($class == 'leaf') {
        unset($attributes['class'][$key]);
      }
    }
  }

  // Re-index the class array for the menu link.
  $variables['element']['#attributes']['class'] = array_values($attributes['class']);
}

/**
 * Implements hook_css_alter().
 */
function uikit_css_alter(&$css) {
  // Stop Drupal core stylesheets from being loaded.
  unset($css[drupal_get_path('module', 'system') . '/system.messages.css']);
  unset($css[drupal_get_path('module', 'system') . '/system.theme.css']);
}

/**
 * Implements hook_js_alter().
 */
function uikit_js_alter(&$javascript) {
  $theme = drupal_get_path('theme', 'uikit');

  // Replace the user module's user.js script with a custom version.
  $user_js = drupal_get_path('module', 'user') . '/user.js';
  if (isset($javascript[$user_js])) {
    $javascript[$user_js]['data'] = $theme . '/overrides/user.js';
  }
}
