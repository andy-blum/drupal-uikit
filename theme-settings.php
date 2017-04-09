<?php

/**
 * @file
 * Provides theme settings for UIkit themes.
 */

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function uikit_form_system_theme_settings_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id = NULL) {
  // General "alters" use a form id. Settings should not be set here. The only
  // thing useful about this is if you need to alter the form for the running
  // theme and *not* the theme setting.
  // @see http://drupal.org/node/943212
  if (isset($form_id)) {
    return;
  }

  // Get the active theme name.
  $build_info = $form_state->getBuildInfo();
  $active_theme = \Drupal::theme()->getActiveTheme();
  $theme = $active_theme->getName();
  $theme_key = $build_info['args'][0] === $theme ? $build_info['args'][0] : $theme;

  // Build the markup for the layout demos.
  $demo_layout = '<div class="uk-layout-wrapper">';
  $demo_layout .= '<div class="uk-layout-container">';
  $demo_layout .= '<div class="uk-layout-content"></div>';
  $demo_layout .= '<div class="uk-layout-sidebar uk-layout-sidebar-left"></div>';
  $demo_layout .= '<div class="uk-layout-sidebar uk-layout-sidebar-right"></div>';
  $demo_layout .= '</div></div>';

  // Get the sidebar positions for each layout.
  $standard_sidebar_pos = theme_get_setting('standard_sidebar_positions', $theme_key);
  $tablet_sidebar_pos = theme_get_setting('tablet_sidebar_positions', $theme_key);
  $mobile_sidebar_pos = theme_get_setting('mobile_sidebar_positions', $theme_key);

  // Set the charset options.
  $charsets = array(
    'utf-8' => 'UTF-8: All languages (Recommended)',
    'ISO-8859-1' => 'ISO 8859-1: Latin 1',
    'ISO-8859-2' => 'ISO 8859-2: Central & East European',
    'ISO-8859-3' => 'ISO 8859-3: South European, Maltese & Esperanto',
    'ISO-8859-4' => 'ISO 8859-4: North European',
    'ISO-8859-5' => 'ISO 8859-5: Cyrillic',
    'ISO-8859-6' => 'ISO 8859-6: Arabic',
    'ISO-8859-7' => 'ISO 8859-7: Modern Greek',
    'ISO-8859-8' => 'ISO 8859-8: Hebrew & Yiddish',
    'ISO-8859-9' => 'ISO 8859-9: Turkish',
    'ISO-8859-10' => 'ISO 8859-10: Nordic (Lappish, Inuit, Icelandic)',
    'ISO-8859-11' => 'ISO 8859-11: Thai',
    'ISO-8859-13' => 'ISO 8859-13: Baltic Rim',
    'ISO-8859-14' => 'ISO 8859-14: Celtic',
    'ISO-8859-16' => 'ISO 8859-16: South-Eastern Europe',
  );

  // Set the x-ua-compatible options.
  $x_ua_compatible_ie_options = array(
    0 => 'None (Recommended)',
    'edge' => 'Highest supported document mode',
    '5' => 'Quirks Mode',
    '7' => 'IE7 mode',
    '8' => 'IE8 mode',
    '9' => 'IE9 mode',
    '10' => 'IE10 mode',
    '11' => 'IE11 mode',
  );

  // Set the navbar margin options.
  $navbar_margin_top_options = array(
    'No top margin',
    'Normal top margin',
    'Smaller top margin',
    'Larger top margin',
  );
  $navbar_margin_bottom_options = array(
    'No bottom margin',
    'Normal bottom margin',
    'Smaller bottom margin',
    'Larger bottom margin',
  );

  // Build the markup for the local task demos.
  $demo_local_tasks = '<ul>';
  $demo_local_tasks .= '<li class="uk-active"><a href="#">Item</a></li>';
  $demo_local_tasks .= '<li><a href="#">Item</a></li>';
  $demo_local_tasks .= '<li><a href="#">Item</a></li>';
  $demo_local_tasks .= '<li class="uk-disabled"><a href="#">Disabled</a></li>';
  $demo_local_tasks .= '</ul>';

  // Set the subnav options for primary and secondary tasks.
  $primary_subnav_options = array(
    0 => 'Basic subnav',
    'uk-subnav-line' => 'Subnav line',
    'uk-subnav-pill' => 'Subnav pill',
    'uk-tab' => 'Tabbed',
  );
  $secondary_subnav_options = array(
    0 => 'Basic subnav',
    'uk-subnav-line' => 'Subnav line',
    'uk-subnav-pill' => 'Subnav pill',
  );

  // Set the region style options.
  $region_style_options = array(
    0 => 'No style',
    'panel' => 'Panel',
    'block' => 'Block',
  );

  // Set the viewport scale options.
  $viewport_scale = array(
    -1 => t('-- Select --'),
    '0' => '0',
    '1' => '1',
    '2' => '2',
    '3' => '3',
    '4' => '4',
    '5' => '5',
    '6' => '6',
    '7' => '7',
    '8' => '8',
    '9' => '9',
    '10' => '10',
  );

  // Fetch a list of regions for the current theme.
  $all_regions = system_region_list($theme, $show = REGIONS_VISIBLE);
  $form['#attached']['library'][] = 'uikit/uikit.admin';

  // Create vertical tabs for all UIkit related settings.
  $form['uikit'] = array(
    '#type' => 'vertical_tabs',
    /*'#attached' => array(
      'css' => array(
        drupal_get_path('theme', 'uikit') . '/css/uikit.admin.css',
      ),
      'js' => array(drupal_get_path('theme', 'uikit') . '/js/uikit.admin.js'),
    ),*/
    '#prefix' => '<h3>' . t('UIkit Settings') . '</h3>',
    '#weight' => -10,
  );

  // UIkit theme styles.
  $form['theme'] = array(
    '#type' => 'details',
    '#title' => t('Theme styles'),
    '#description' => t('UIkit comes with a basic theme and two neat themes to get you started. Here you can select which base style to start with.'),
    '#group' => 'uikit',
    '#attributes' => array(
      'class' => array(
        'uikit-theme-settings-form',
      ),
    ),
  );
  $form['theme']['base_style'] = array(
    '#type' => 'select',
    '#title' => t('Base style'),
    '#options' => array(
      0 => t('UIkit default'),
      'almost-flat' => t('UIkit almost flat'),
      'gradient' => t('UIkit gradient'),
    ),
    '#description' => t('Select which base style to use.<ol><li><strong>UIkit default:</strong> No border radius or gradients</li><li><strong>UIkit almost flat:</strong> Small border and border radius</li><li><strong>UIkit gradient:</strong> Almost flat style with gradient backgrounds.</li></ol>'),
    '#default_value' => theme_get_setting('base_style', $theme_key),
  );

  // Layout settings.
  $form['layout'] = array(
    '#type' => 'details',
    '#title' => t('Layout'),
    '#description' => t('Apply our fully responsive fluid grid system and panels, common layout parts like blog articles and comments and useful utility classes.'),
    '#group' => 'uikit',
    '#attributes' => array(
      'class' => array(
        'uikit-layout-settings-form',
      ),
    ),
  );
  $form['layout']['layout_advanced'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show advanced layout settings'),
    '#default_value' => theme_get_setting('layout_advanced', $theme_key),
  );
  $form['layout']['page_layout'] = array(
    '#type' => 'details',
    '#title' => t('Page Layout'),
    '#description' => t('Change page layout settings.'),
    '#attributes' => array(
      'open' => 'open',
    ),
  );
  $form['layout']['page_layout']['standard_layout'] = array(
    '#type' => 'details',
    '#title' => t('Standard Layout'),
    '#description' => t('Change layout settings for desktops and large screens.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['layout']['page_layout']['standard_layout']['standard_layout_demo'] = array(
    '#type' => 'container',
  );
  $form['layout']['page_layout']['standard_layout']['standard_layout_demo']['#attributes']['class'][] = 'uk-admin-demo';
  $form['layout']['page_layout']['standard_layout']['standard_layout_demo']['#attributes']['class'][] = 'uk-layout-' . $standard_sidebar_pos;
  $form['layout']['page_layout']['standard_layout']['standard_layout_demo']['standard_demo'] = array(
    '#markup' => '<div id="standard-layout-demo">' . $demo_layout . '</div>',
  );
  $form['layout']['page_layout']['standard_layout']['standard_sidebar_positions'] = array(
    '#type' => 'radios',
    '#title' => t('Sidebar positions'),
    '#description' => t('Position the sidebars in the standard layout.'),
    '#default_value' => theme_get_setting('standard_sidebar_positions', $theme_key),
    '#options' => array(
      'holy-grail' => t('Holy grail'),
      'sidebars-left' => t('Both sidebars left'),
      'sidebars-right' => t('Both sidebars right'),
    ),
  );
  $form['layout']['page_layout']['tablet_layout'] = array(
    '#type' => 'details',
    '#title' => t('Tablet Layout'),
    '#description' => t('Change layout settings for tablets and medium screens.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['layout']['page_layout']['tablet_layout']['tablet_layout_demo'] = array(
    '#type' => 'container',
  );
  $form['layout']['page_layout']['tablet_layout']['tablet_layout_demo']['#attributes']['class'][] = 'uk-admin-demo';
  $form['layout']['page_layout']['tablet_layout']['tablet_layout_demo']['#attributes']['class'][] = 'uk-layout-' . $tablet_sidebar_pos;
  $form['layout']['page_layout']['tablet_layout']['tablet_layout_demo']['tablet_demo'] = array(
    '#markup' => '<div id="tablet-layout-demo">' . $demo_layout . '</div>',
  );
  $form['layout']['page_layout']['tablet_layout']['tablet_sidebar_positions'] = array(
    '#type' => 'radios',
    '#title' => t('Sidebar positions'),
    '#description' => t('Position the sidebars in the tablet layout.'),
    '#default_value' => theme_get_setting('tablet_sidebar_positions', $theme_key),
    '#options' => array(
      'holy-grail' => t('Holy grail'),
      'sidebars-left' => t('Both sidebars left'),
      'sidebar-left-stacked' => t('Left sidebar stacked'),
      'sidebars-right' => t('Both sidebars right'),
      'sidebar-right-stacked' => t('Right sidebar stacked'),
    ),
  );
  $form['layout']['page_layout']['mobile_layout'] = array(
    '#type' => 'details',
    '#title' => t('Mobile Layout'),
    '#description' => t('Change layout settings for mobile devices and small screens.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['layout']['page_layout']['mobile_layout']['mobile_layout_demo'] = array(
    '#type' => 'container',
  );
  $form['layout']['page_layout']['mobile_layout']['mobile_layout_demo']['#attributes']['class'][] = 'uk-admin-demo';
  $form['layout']['page_layout']['mobile_layout']['mobile_layout_demo']['#attributes']['class'][] = 'uk-layout-' . $mobile_sidebar_pos;
  $form['layout']['page_layout']['mobile_layout']['mobile_layout_demo']['mobile_demo'] = array(
    '#markup' => '<div id="mobile-layout-demo">' . $demo_layout . '</div>',
  );
  $form['layout']['page_layout']['mobile_layout']['mobile_sidebar_positions'] = array(
    '#type' => 'radios',
    '#title' => t('Sidebar positions'),
    '#description' => t('Position the sidebars in the mobile layout.'),
    '#default_value' => theme_get_setting('mobile_sidebar_positions', $theme_key),
    '#options' => array(
      'sidebars-stacked' => t('Sidebars stacked'),
      'sidebars-vertical' => t('Sidebars vertical'),
    ),
  );
  $form['layout']['page_layout']['page_container'] = array(
    '#type' => 'checkbox',
    '#title' => t('Page Container'),
    '#description' => t('Add the .uk-container class to the page container to give it a max-width and wrap the main content of your website. For large screens it applies a different max-width.'),
    '#default_value' => theme_get_setting('page_container', $theme_key),
    '#states' => array(
      'visible' => array(
        ':input[name="layout_advanced"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['layout']['page_layout']['page_centering'] = array(
    '#type' => 'checkbox',
    '#title' => t('Page Centering'),
    '#description' => t('To center the page container, use the .uk-container-center class.'),
    '#default_value' => theme_get_setting('page_centering', $theme_key),
    '#states' => array(
      'visible' => array(
        ':input[name="layout_advanced"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['layout']['page_layout']['page_margin'] = array(
    '#type' => 'select',
    '#title' => t('Page margin'),
    '#description' => t('Select the margin to add to the top and bottom of the page container. This is useful, for example, when using the gradient style with a centered page container and a navbar.'),
    '#default_value' => theme_get_setting('page_margin', $theme_key),
    '#options' => array(
      0 => t('No margin'),
      'uk-margin-top' => t('Top margin'),
      'uk-margin-bottom' => t('Bottom margin'),
      'uk-margin' => t('Top and bottom margin'),
    ),
    '#states' => array(
      'visible' => array(
        ':input[name="layout_advanced"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['layout']['region_layout'] = array(
    '#type' => 'details',
    '#title' => t('Region Layout'),
    '#description' => t('Change region layout settings.<br><br>Use the following links to see an example of each component style.<ul class="links"><li><a href="http://getuikit.com/docs/panel.html" target="_blank">Panel</a></li><li><a href="http://getuikit.com/docs/block.html" target="_blank">Block</a></li></ul>'),
    '#states' => array(
      'visible' => array(
        ':input[name="layout_advanced"]' => array('checked' => TRUE),
      ),
    ),
  );

  // Load all regions to assign separate settings for each region.
  foreach ($all_regions as $region_key => $region) {
    if ($region_key != 'navbar' && $region_key != 'offcanvas') {
      $form['layout']['region_layout'][$region_key] = array(
        '#type' => 'details',
        '#title' => t('@region region', array('@region' => $region)),
        '#description' => t('Change the @region region settings.', array('@region' => $region)),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      $form['layout']['region_layout'][$region_key][$region_key . '_style'] = array(
        '#type' => 'select',
        '#title' => t('@title style', array('@title' => $region)),
        '#description' => t('Set the style for the @region region. The theme will automatically style the region accordingly.', array('@region' => $region)),
        '#default_value' => theme_get_setting($region_key . '_style', $theme_key),
        '#options' => $region_style_options,
      );
    }
  }

  // Navigational settings.
  $form['navigations'] = array(
    '#type' => 'details',
    '#title' => t('Navigations'),
    '#description' => t('UIkit offers different types of navigations, like navigation bars and side navigations. Use breadcrumbs or a pagination to steer through articles.'),
    '#group' => 'uikit',
  );
  $form['navigations']['main_navbar'] = array(
    '#type' => 'details',
    '#title' => t('Navigation bar'),
    '#description' => t('Configure settings for the navigation bar.'),
    '#attributes' => array(
      'open' => 'open',
    ),
  );
  $form['navigations']['main_navbar']['navbar_container_settings'] = array(
    '#type' => 'details',
    '#title' => t('Navbar container'),
    '#description' => t('Configure settings for the navigation bar container.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['navigations']['main_navbar']['navbar_container_settings']['navbar_container'] = array(
    '#type' => 'checkbox',
    '#title' => t('Container'),
    '#description' => t('Add the .uk-container class to the navbar container to give it a max-width and wrap the navbar of your website. For large screens it applies a different max-width.'),
    '#default_value' => theme_get_setting('navbar_container', $theme_key),
  );
  $form['navigations']['main_navbar']['navbar_container_settings']['navbar_centering'] = array(
    '#type' => 'checkbox',
    '#title' => t('Centering'),
    '#description' => t('To center the navbar container, use the .uk-container-center class.'),
    '#default_value' => theme_get_setting('navbar_centering', $theme_key),
  );
  $form['navigations']['main_navbar']['navbar_container_settings']['navbar_attached'] = array(
    '#type' => 'checkbox',
    '#title' => t('Navbar attached'),
    '#description' => t("Adds the <code>.uk-navbar-attached</code> class to optimize the navbar's styling to be attached to the top of the viewport. For example, rounded corners will be removed."),
    '#default_value' => theme_get_setting('navbar_attached', $theme_key),
  );
  $form['navigations']['main_navbar']['navbar_margin'] = array(
    '#type' => 'details',
    '#title' => t('Navbar margin'),
    '#description' => t('Configure the top and bottom margin to apply to the navbar.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['navigations']['main_navbar']['navbar_margin']['navbar_margin_top'] = array(
    '#type' => 'select',
    '#title' => t('Navbar top margin'),
    '#description' => t('Select the amount of top margin to apply to the navbar.'),
    '#default_value' => theme_get_setting('navbar_margin_top', $theme_key),
    '#options' => $navbar_margin_top_options,
  );
  $form['navigations']['main_navbar']['navbar_margin']['navbar_margin_bottom'] = array(
    '#type' => 'select',
    '#title' => t('Navbar bottom margin'),
    '#description' => t('Select the amount of bottom margin to apply to the navbar.'),
    '#default_value' => theme_get_setting('navbar_margin_bottom', $theme_key),
    '#options' => $navbar_margin_bottom_options,
  );
  $form['navigations']['local_tasks'] = array(
    '#type' => 'details',
    '#title' => t('Local tasks'),
    '#description' => t('Configure settings for the local tasks menus.'),
    '#attributes' => array(
      'open' => 'open',
    ),
  );
  $form['navigations']['local_tasks']['primary_tasks'] = array(
    '#type' => 'container',
  );
  $form['navigations']['local_tasks']['primary_tasks']['primary_tasks_demo'] = array(
    '#markup' => '<div id="primary-tasks-demo" class="uk-admin-demo">' . $demo_local_tasks . '</div>',
  );
  $form['navigations']['local_tasks']['primary_tasks']['primary_tasks_style'] = array(
    '#type' => 'select',
    '#title' => t('Primary tasks style'),
    '#description' => t('Select the style to apply to the primary local tasks.'),
    '#default_value' => theme_get_setting('primary_tasks_style', $theme_key),
    '#options' => $primary_subnav_options,
  );
  $form['navigations']['local_tasks']['secondary_tasks'] = array(
    '#type' => 'container',
  );
  $form['navigations']['local_tasks']['secondary_tasks']['secondary_tasks_demo'] = array(
    '#markup' => '<div id="secondary-tasks-demo" class="uk-admin-demo">' . $demo_local_tasks . '</div>',
  );
  $form['navigations']['local_tasks']['secondary_tasks']['secondary_tasks_style'] = array(
    '#type' => 'select',
    '#title' => t('Secondary tasks style'),
    '#description' => t('Select the style to apply to the secondary local tasks.'),
    '#default_value' => theme_get_setting('secondary_tasks_style', $theme_key),
    '#options' => $secondary_subnav_options,
  );
  $form['navigations']['breadcrumb'] = array(
    '#type' => 'details',
    '#title' => t('Breadcrumbs'),
    '#description' => t('Configure settings for breadcrumb navigation.'),
    '#attributes' => array(
      'open' => 'open',
    ),
  );
  $form['navigations']['breadcrumb']['display_breadcrumbs'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display breadcrumbs'),
    '#description' => t('Check this box to display the breadcrumb.'),
    '#default_value' => theme_get_setting('display_breadcrumbs', $theme_key),
  );
  $form['navigations']['breadcrumb']['breakcrumbs_home_link'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display home link in breadcrumbs'),
    '#description' => t('Check this box to display the home link in breadcrumb trail.'),
    '#default_value' => theme_get_setting('breakcrumbs_home_link', $theme_key),
  );

  // Create vertical tabs to place Drupal's default theme settings in.
  $form['basic_settings'] = array(
    '#type' => 'vertical_tabs',
    '#prefix' => '<h3>' . t('Basic Settings') . '</h3>',
    '#weight' => 0,
  );

  // Group Drupal's default theme settings in the basic settings.
  $form['theme_settings']['#group'] = 'basic_settings';
  $form['logo']['#group'] = 'basic_settings';
  $form['favicon']['#group'] = 'basic_settings';
}
