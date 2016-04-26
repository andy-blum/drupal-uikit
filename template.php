<?php
/**
 * @file
 * Conditional logic and data processing for the UIkit theme.
 */

/**
 * Implements template_preprocess_page().
 */
function uikit_preprocess_page(&$variables) {
  $uid = $variables['user']->uid;
  $page_title = drupal_get_title();
  $path = explode('/', current_path());
  $user = $path[0] === 'user';
  $user_view = $user && (isset($path[1]) && is_numeric($path[1])) && !isset($path[2]);
  $devel = $user && in_array('devel', $path);
  $edit = $user && in_array('edit', $path);
  $login = $user && (!isset($path[1]) || in_array('login', $path));
  $password = $user && in_array('password', $path);
  $register = $user && in_array('register', $path);
  $shortcuts = $user && in_array('shortcuts', $path);

  if ($user) {
    // Set the active menu items for user paths.
    menu_set_active_item('user');
  }

  if ($user_view) {
    if ($uid) {
      $name = $variables['user']->name;
    }
    else {
      $user_obj = user_load($path[1]);
      $name = $user_obj->name;
    }

    // Set a more semantic page title.
    drupal_set_title(t("@name's account", array('@name' => $name)));
  }

  if ($devel) {
    if (!isset($path[3])) {
      // Set a more semantic page title.
      drupal_set_title(t('Debug @name (load)', array('@name' => $variables['user']->name)));

      // Set the breadcrumb for the user register path.
      drupal_set_breadcrumb(array(
        l(t('Home'), NULL),
        l(t('User account'), 'user'),
      ));
    }
    else {
      // Set a more semantic page title.
      drupal_set_title(t('Debug @name (render)', array('@name' => $variables['user']->name)));

      // Set the breadcrumb for the user register path.
      drupal_set_breadcrumb(array(
        l(t('Home'), NULL),
        l(t('User account'), 'user'),
        l(t('Devel'), 'user/' . $path[1] . '/devel'),
      ));
    }
  }

  if ($edit) {
    // Set a more semantic page title.
    drupal_set_title(t("Edit @name's account", array('@name' => $variables['user']->name)));

    // Set the breadcrumb for the user register path.
    drupal_set_breadcrumb(array(
      l(t('Home'), NULL),
      l(t('User account'), 'user'),
    ));
  }

  if ($login) {
    // Set a more semantic page title.
    drupal_set_title(t('Log in'));

    // Set the active menu items for user login paths.
    menu_set_active_item('user');
    menu_set_active_item('user/login');

    // Set the breadcrumb for the user register path.
    drupal_set_breadcrumb(array(
      l(t('Home'), NULL),
      l(t('User account'), 'user'),
    ));
  }

  if ($password) {
    // Set a more semantic page title.
    drupal_set_title(t('Request new password'));

    // Set the breadcrumb for the user password path.
    drupal_set_breadcrumb(array(
      l(t('Home'), NULL),
      l(t('@title', array('@title' => $page_title)), 'user'),
    ));
  }

  if ($register) {
    // Set a more semantic page title.
    drupal_set_title(t('Create new account'));

    // Set the breadcrumb for the user register path.
    drupal_set_breadcrumb(array(
      l(t('Home'), NULL),
      l(t('@title', array('@title' => $page_title)), 'user'),
    ));
  }

  if ($shortcuts) {
    // Set a more semantic page title.
    drupal_set_title(t("@name's shortcuts", array('@name' => $variables['user']->name)));

    // Set the breadcrumb for the user register path.
    drupal_set_breadcrumb(array(
      l(t('Home'), NULL),
      l(t('User account'), 'user'),
    ));
  }

  $sidebar_first = $variables['page']['sidebar_first'];
  $sidebar_second = $variables['page']['sidebar_second'];

  // Assign content attributes.
  $variables['content_attributes_array']['id'] = 'page-content';
  $variables['content_attributes_array']['class'][] = 'uk-width-1-1';
  $variables['content_attributes_array']['class'][] = 'uk-margin-top-remove';

  // Assign sidebar_first attributes.
  $variables['sidebar_first_attributes_array'] = array(
    'id' => 'sidebar-first',
    'class' => array(
      'uk-width-1-1',
      'uk-width-medium-1-4',
      'uk-margin-top-remove',
    ),
  );

  // Assign sidebar_second attributes.
  $variables['sidebar_second_attributes_array'] = array(
    'id' => 'sidebar-second',
    'class' => array(
      'uk-width-1-1',
      'uk-width-medium-1-4',
      'uk-margin-top-remove',
    ),
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
 * Implements template_preprocess_node().
 */
function uikit_preprocess_node(&$variables) {
  // Add the uk-article-title class to all node titles.
  $variables['title_attributes_array']['class'][] = 'uk-article-title';
}

/**
 * Implements template_preprocess_block().
 */
function uikit_preprocess_block(&$variables) {
  $region = $variables['elements']['#block']->region;
  $delta = $variables['elements']['#block']->delta;
  $subject = $variables['block']->subject;
  $classes = $variables['classes_array'];

  if ($region == 'sidebar_first' || $region == 'sidebar_second') {
    // Add panel and utility classes to all sidebars.
    $variables['classes_array'][] = 'uk-margin-bottom';
    $variables['content_attributes_array']['class'][] = 'uk-panel';
    $variables['content_attributes_array']['class'][] = 'uk-panel-box';
    $variables['title_attributes_array']['class'][] = 'uk-panel-title';

    if (in_array('block-menu', $classes)) {
      // Create a new variable to contain the block menu.
      $menu = array();
      $tree = menu_tree($delta);

      foreach ($tree as $key => $item) {
        if ($key != '#sorted' && $key != '#theme_wrappers') {
          $menu['menu-' . $key] = array(
            'href' => $item['#href'],
            'title' => $item['#title'],
            'attributes' => $item['#attributes'],
          );
        }
      }

      // Add a menu item to the menu containing the block subject, if defined.
      if ($subject) {
        $menu['uk-nav-header'] = array(
          'title' => $subject,
        );

        // Move the menu item to the beginning of the menu array.
        $menu = array('uk-nav-header' => $menu['uk-nav-header']) + $menu;
      }

      // Define the options to theme the menu.
      $options = array(
        'links' => $menu,
        'attributes' => array(
          'class' => array(
            'uk-nav',
            'uk-nav-side',
          ),
        ),
      );

      // Create a themed menu variable for the templates.
      $menu_name = 'links__' . str_replace('-', '_', $delta);
      $variables['block_menu'] = theme($menu_name, $options);

      // Add two new theme hook suggestions to theme the templates.
      $suggestion = 'block__' . $region . '__menu';
      $suggestion_delta = 'block__' . $region . '__menu__' . str_replace('-', '_', $delta);
      $variables['theme_hook_suggestions'][] = $suggestion;
      $variables['theme_hook_suggestions'][] = $suggestion_delta;
    }
  }
}

/**
 * Implements hook_preprocess_HOOK() for theme_button().
 */
function uikit_preprocess_button(&$variables) {
  // Add the uk-button class to all buttons.
  $variables['element']['#attributes']['class'][] = 'uk-button';
}

/**
 * Implements hook_preprocess_HOOK() for theme_checkboxes().
 */
function uikit_preprocess_checkboxes(&$variables) {
  $variables['element']['#attributes']['class'][] = 'uk-form-row';
}

/**
 * Implements template_preprocess_comment().
 */
function uikit_preprocess_comment(&$variables) {
  $comment = $variables['elements']['#comment'];
  $node = $variables['elements']['#node'];

  // Add comment classes.
  $variables['classes_array'][] = 'uk-comment';
  $variables['classes_array'][] = 'uk-clearfix';
  $variables['classes_array'][] = 'uk-margin-top';
  $variables['title_attributes_array']['class'][] = 'uk-comment-title';
  $variables['content_attributes_array']['class'][] = 'uk-comment-body';

  // Use the comment cid as the permalink text in the comment title.
  $cid = $comment->cid;
  $uri = entity_uri('node', $node);
  $uri['options'] += array(
    'attributes' => array(
      'class' => array(
        'permalink',
      ),
      'rel' => 'bookmark',
    ),
    'fragment' => "comment-$cid",
  );
  $variables['permalink'] = l(t('#@cid', array('@cid' => $cid)), $uri['path'], $uri['options']);

  // Use the same uri for the title permalink.
  $variables['title'] = l(t('@subject', array('@subject' => $comment->subject)), $uri['path'], $uri['options']);

  // Use separate submitted by and date variables.
  $variables['submitted_user'] = t('!username', array('!username' => $variables['author']));
  $variables['submitted_date'] = t('!datetime', array('!datetime' => $variables['created']));
}

/**
 * Implements hook_preprocess_HOOK() for comment-wrapper.tpl.php.
 */
function uikit_preprocess_comment_wrapper(&$variables) {
  $variables['classes_array'][] = 'uk-margin-top-remove';
}

/**
 * Implements hook_preprocess_HOOK() for theme_confirm_form().
 */
function uikit_preprocess_confirm_form(&$variables) {
  foreach ($variables['form']['actions'] as $key => $action) {
    $type = isset($action['#type']) ? $action['#type'] : 0;
    if ($type && $type == 'link') {
      $variables['form']['actions'][$key]['#attributes']['class'][] = 'uk-button';
    }
  }
}

/**
 * Implements hook_preprocess_HOOK() for theme_container().
 */
function uikit_preprocess_container(&$variables) {
  // Remove container-inline classes.
  $type = !empty($variables['element']['#type']) ? $variables['element']['#type'] : 0;
  $container = $type && $type === 'container';
  $classes = isset($variables['element']['#attributes']['class']) ? $variables['element']['#attributes']['class'] : array();
  $checkbox = in_array('form-type-checkbox', $classes);
  $help_block = in_array('uk-form-help-block', $classes);
  $inline = in_array('container-inline', $classes);
  $radio = in_array('form-type-radio', $classes);
  $search_block_form = in_array('form-item-search-block-form', $classes);

  if ($container) {
    if ($inline) {
      foreach ($classes as $key => $class) {
        if ($class == 'container-inline') {
          unset($variables['element']['#attributes']['class'][$key]);
          array_values($variables['element']['#attributes']['class']);
        }
      }
    }
  }

  if (!$help_block && !$radio && !$search_block_form) {
    $variables['element']['#attributes']['class'][] = 'uk-form-row';
  }

  // Add the uk-button-group class to actions containers.
  $actions = $type && $type === 'actions';

  if ($actions) {
    $variables['element']['#attributes']['class'][] = 'uk-button-group';
    $variables['element']['#attributes']['class'][] = 'uk-display-block';
    $variables['element']['#attributes']['class'][] = 'uk-margin-top';
  }
}

/**
 * Implements hook_preprocess_HOOK() for theme_field().
 */
function uikit_preprocess_field(&$variables) {
  $type = $variables['element']['#field_type'];
  $classes = $variables['classes_array'];

  // Add utility classes based on field type.
  switch ($type) {
    case 'image':
      $classes[] = 'uk-float-left';
      $classes[] = 'uk-margin-right';
      $classes[] = 'uk-margin-bottom';
      break;

    case 'taxonomy_term_reference':
      $classes[] = 'uk-margin';
      break;

    case 'text':
    case 'text_with_summary':
      $classes[] = 'uk-clearfix';
      break;
  }

  $variables['classes_array'] = $classes;
}

/**
 * Implements hook_preprocess_HOOK() for theme_fieldset().
 */
function uikit_preprocess_fieldset(&$variables) {
  $collapsible = $variables['element']['#collapsible'];
  $group_fieldset = isset($variables['element']['#group_fieldset']) && $variables['element']['#group_fieldset'];
  $format_fieldset = isset($variables['element']['format']);

  // Collapsible, non-grouped fieldsets will use UIkit's accordion components.
  if ($group_fieldset) {
    $variables['theme_hook_suggestions'][] = 'fieldset__grouped';
  }
  elseif ($collapsible) {
    $variables['theme_hook_suggestions'][] = 'fieldset__collapsible';
    $variables['element']['#attributes']['class'][] = 'uk-accordion';
    $variables['element']['#attributes']['data-uk-accordion'] = '';

    foreach ($variables['element']['#attributes']['class'] as $key => $class) {
      if ($class == 'collapsible' || $class == 'collapsed') {
        unset($variables['element']['#attributes']['class'][$key]);
        array_values($variables['element']['#attributes']['class']);
      }
      if ($class == 'collapsed') {
        $variables['element']['#attributes']['data-uk-accordion'] .= '{showfirst: false}';
      }
    }
  }
  elseif ($format_fieldset) {
    $variables['theme_hook_suggestions'][] = 'fieldset__format';
  }
  else {
    $variables['element']['#attributes']['class'][] = 'uk-margin-top';
    $variables['element']['#attributes']['class'][] = 'uk-margin-bottom';
    $variables['theme_hook_suggestions'][] = 'fieldset';
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
  // Add the uk-form class to all but vertical tab forms.
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
  $element = $variables['element'];
  $type = !empty($element['#type']) ? $element['#type'] : FALSE;
  $checkboxes = $type === 'checkbox';
  $radios = $type === 'radio';

  // Add the uk-form-row class to all but radios and checkboxes.
  if (!$checkboxes && !$radios) {
    $variables['element']['#wrapper_attributes']['class'][] = 'uk-form-row';
  }

  // Load advanced form element component stylsheets.
  $theme = drupal_get_path('theme', 'uikit');

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
 * Implements hook_preprocess_HOOK() for theme_item_list().
 */
function uikit_preprocess_item_list(&$variables) {
  // Add the uk-subnav class to all item lists.
  $variables['attributes']['class'][] = 'uk-subnav';
}

/**
 * Implements hook_preprocess_HOOK() for theme_links().
 */
function uikit_preprocess_links(&$variables) {
  $theme_hook_original = $variables['theme_hook_original'];
  $classes = $variables['attributes']['class'];

  // Removes default classes from inline links and adds uk-subnav and
  // uk-subnav-line classes.
  $inline = in_array('inline', $classes);

  if ($inline) {
    foreach ($variables['attributes']['class'] as $key => $class) {
      if ($class == 'links' || $class == 'inline') {
        unset($variables['attributes']['class'][$key]);
        array_values($variables['attributes']['class']);
      }
    }

    $variables['attributes']['class'][] = 'uk-subnav';
    $variables['attributes']['class'][] = 'uk-subnav-line';
    $variables['attributes']['class'][] = 'uk-float-right';
  }

  if ($theme_hook_original == 'links__contextual') {
    $variables['attributes']['class'] = array('uk-nav');
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
 * Implements hook_preprocess_HOOK() for theme_radios().
 */
function uikit_preprocess_radios(&$variables) {
  $variables['element']['#attributes']['class'][] = 'uk-form-row';
}

/**
 * Implements template_preprocess_search_block_form().
 */
function uikit_preprocess_search_block_form(&$variables) {
  // Load search component stylsheet.
  $theme = drupal_get_path('theme', 'uikit');
  drupal_add_css($theme . '/overrides/search.gradient.css', array(
    'group' => CSS_THEME,
  ));

  // The submit button is hidden on all devices, but the actions container will
  // disrupt the flow of the form. Using the uk-hidden class on the actions
  // container, the actions will not disrupt the flow of the form.
  $variables['form']['actions']['#attributes']['class'][] = 'uk-hidden';

  // Re-render the form actions.
  $variables['search']['actions'] = render($variables['form']['actions']);

  // Collect all form elements in search_form to make it easier to print the
  // whole form.
  $variables['search_form'] = implode($variables['search']);
}

/**
 * Implements hook_preprocess_HOOK() for theme_table().
 */
function uikit_preprocess_table(&$variables) {
  $variables['attributes']['class'][] = 'uk-table';

  // Add some additional classes to the table for text format filter tips.
  $filter_tips = current_path() === 'filter/tips';
  if ($filter_tips) {
    $variables['attributes']['class'][] = 'uk-table-striped';
    $variables['attributes']['class'][] = 'table-filter-tips';
  }
}

/**
 * Implements hook_css_alter().
 */
function uikit_css_alter(&$css) {
  $theme = drupal_get_path('theme', 'uikit');

  // Stop Drupal core stylesheets from being loaded.
  unset($css[drupal_get_path('module', 'system') . '/system.messages.css']);
  unset($css[drupal_get_path('module', 'system') . '/system.theme.css']);
  unset($css[drupal_get_path('module', 'system') . '/system.menus.css']);

  // Replace the user module's user.css with a custom version.
  $user_css = drupal_get_path('module', 'user') . '/user.css';
  if (isset($css[$user_css])) {
    $css[$user_css]['data'] = $theme . '/overrides/user.css';
  }

  // Replace the field module's field.css with a custom version.
  $field_css = drupal_get_path('module', 'field') . '/theme/field.css';
  if (isset($css[$field_css])) {
    $css[$field_css]['data'] = $theme . '/overrides/field.css';
  }
}

/**
 * Implements hook_element_info_alter().
 */
function uikit_element_info_alter(&$type) {
  // Remove prefix and suffix from contextual links.
  $contextual_links = isset($type['contextual_links']);
  if ($contextual_links) {
    $type['contextual_links']['#prefix'] = '<div class="contextual-links-wrapper">';
  }
}

/**
 * Implements hook_js_alter().
 */
function uikit_js_alter(&$javascript) {
  $theme = drupal_get_path('theme', 'uikit');

  // Replace the contextual module's contextual.js with a custom version.
  $contextual_js = drupal_get_path('module', 'contextual') . '/contextual.js';
  if (isset($javascript[$contextual_js])) {
    $javascript[$contextual_js]['data'] = $theme . '/overrides/contextual.js';
  }

  // Replace tabledrag.js and tableheader.js with custom versions.
  $tabledrag_js = 'misc/tabledrag.js';
  $tableheader_js = 'misc/tableheader.js';
  if (isset($javascript[$tabledrag_js])) {
    $javascript[$tabledrag_js]['data'] = $theme . '/overrides/tabledrag.js';
  }
  if (isset($javascript[$tableheader_js])) {
    $javascript[$tableheader_js]['data'] = $theme . '/overrides/tableheader.js';
  }

  // Replace the user module's user.js with a custom version.
  $user_js = drupal_get_path('module', 'user') . '/user.js';
  if (isset($javascript[$user_js])) {
    $javascript[$user_js]['data'] = $theme . '/overrides/user.js';
  }
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function uikit_form_system_performance_settings_alter(&$form, &$form_state, $form_id) {
  $classes = 'uk-form-row';
  $cache = variable_get('cache', 0);

  if (!$cache) {
    $classes .= ' js-hide';
  }

  $prefix = '<div id="page-compression-wrapper" class="' . $classes . '"">';
  $form['bandwidth_optimization']['page_compression']['#prefix'] = $prefix;
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function uikit_form_search_block_form_alter(&$form, &$form_state, $form_id) {
  // Add the uk-search class to the form and hide the submit button.
  $form['#attributes']['class'][] = 'uk-search';
  $form['actions']['submit']['#attributes']['class'][] = 'uk-hidden';
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function uikit_form_user_login_block_alter(&$form, &$form_state, $form_id) {
  $form['links']['#prefix'] = '<div class="uk-form-row form-item-list">';
  $form['links']['#suffix'] = '</div>';
}

/**
 * Implements hook_menu_local_tasks_alter().
 */
function uikit_menu_local_tasks_alter(&$data, $router_item, $root_path) {
  // Define various icons.
  $plus = '<i class="uk-icon-plus"></i> ';

  foreach ($data['actions']['output'] as $key => $action) {
    // Add icon based on link path.
    switch ($action['#link']['path']) {
      case 'node/add':
      case 'admin/structure/block/add':
      case 'admin/structure/types/add':
      case 'admin/structure/menu/add':
      case 'admin/structure/taxonomy/add':
      case 'admin/structure/taxonomy/%/add':
      case 'admin/appearance/install':
      case 'admin/people/create':
      case 'admin/modules/install':
      case 'admin/config/content/formats/add':
      case 'admin/config/media/image-styles/add':
      case 'admin/config/search/path/add':
      case 'admin/config/regional/date-time/types/add':
      case 'admin/config/user-interface/shortcut/add-set':
        $title = $plus . $data['actions']['output'][$key]['#link']['title'];
        $data['actions']['output'][$key]['#link']['title'] = $title;
        $data['actions']['output'][$key]['#link']['localized_options']['html'] = TRUE;
        break;
    }
  }
}
