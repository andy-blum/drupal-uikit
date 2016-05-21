<?php

/**
 * @file
 * Provides theme settings for UIkit themes.
 */

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function uikit_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL) {
  global $theme_key;

  // General "alters" use a form id. Settings should not be set here. The only
  // thing useful about this is if you need to alter the form for the running
  // theme and *not* the theme setting.
  // @see http://drupal.org/node/943212
  if (isset($form_id)) {
    return;
  }

  // Get the active theme name.
  $theme_key = $form_state['build_info']['args'][0] === $theme_key ? $form_state['build_info']['args'][0] : $theme_key;

  // Build the markup for the layout demos.
  $demo_layout = '<div class="uk-layout-wrapper">';
  $demo_layout .= '<div class="uk-layout-container">';
  $demo_layout .= '<div class="uk-layout-content"></div>';
  $demo_layout .= '<div class="uk-layout-sidebar uk-layout-sidebar-left"></div>';
  $demo_layout .= '<div class="uk-layout-sidebar uk-layout-sidebar-right"></div>';
  $demo_layout .= '</div></div>';

  // Get the sidebar positions for each layout.
  $standard_sidebar_pos = theme_get_setting('standard_sidebar_positions');
  $tablet_sidebar_pos = theme_get_setting('tablet_sidebar_positions');
  $mobile_sidebar_pos = theme_get_setting('mobile_sidebar_positions');

  // Get all menus.
  $menus = menu_get_menus();

  // Get the main and secondary menus.
  $main_menu = variable_get('menu_main_links_source', 'main-menu');
  $secondary_menu = variable_get('menu_secondary_links_source', 'user-menu');

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
  $demo_local_tasks = '<ul class="uk-subnav">';
  $demo_local_tasks .= '<li class="uk-active"><a href="#">Item</a></li>';
  $demo_local_tasks .= '<li><a href="#">Item</a></li>';
  $demo_local_tasks .= '<li><a href="#">Item</a></li>';
  $demo_local_tasks .= '<li class="uk-disabled"><a href="#">Disabled</a></li>';
  $demo_local_tasks .= '</ul>';

  // Set the subnav options.
  $subnav_options = array(
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

  // Fetch a list of regions for the current theme.
  $all_regions = system_region_list($theme_key);

  // Get theme specific jQuery version.
  $jquery_version = theme_get_setting('jquery_update_jquery_version', $theme_key);

  // Get site wide jQuery version if theme specific one is not set.
  if (!$jquery_version && module_exists('jquery_update')) {
    $jquery_version = variable_get('jquery_update_jquery_version', '1.10');
  }

  // Check if the jquery_update module is both installed and enabled.
  if (!module_exists('jquery_update')) {
    $message = t('jQuery Update is not enabled. UIkit requires a minimum jQuery version of 1.10 or higher. Please enable the <a href="@jquery_update_project_url">jQuery Update</a> module and <a href="@jquery_update_configure">configure</a> the default jQuery version.', array(
      '@jquery_update_project_url' => 'https://www.drupal.org/project/jquery_update',
      '@jquery_update_configure' => url('admin/config/development/jquery_update'),
    ));
    drupal_set_message($message, 'error', FALSE);
  }

  // Check if the minimum jQuery version is met.
  if (module_exists('jquery_update') && !version_compare($jquery_version, '1.10', '>=')) {
    $message = t('UIkit requires a minimum jQuery version of 1.10 or higher. Please <a href="@jquery_update_configure">configure</a> the default jQuery version.', array(
      '@jquery_update_configure' => url('admin/config/development/jquery_update'),
    ));
    drupal_set_message($message, 'error', FALSE);
  }

  // Check if the libraries module is both installed and enabled.
  if (!module_exists('libraries')) {
    $message = t('UIkit requires the Libraries module. Please enable the <a href="@libraries_project_url">Libraries</a> module and follow <a href="@uikit_get_started">these instructions</a> to install the UIkit asset files.', array(
      '@libraries_project_url' => 'https://www.drupal.org/project/libraries',
      '@uikit_get_started' => check_url('/admin/appearance/settings/uikit#edit-get-started'),
    ));
    drupal_set_message($message, 'error', FALSE);
  }

  // Create vertical tabs for all UIkit related settings.
  $form['uikit'] = array(
    '#type' => 'vertical_tabs',
    '#attached' => array(
      'css'  => array(
        drupal_get_path('theme', 'uikit') . '/css/uikit.admin.css',
      ),
      'js'  => array(drupal_get_path('theme', 'uikit') . '/js/uikit.admin.js'),
    ),
    '#prefix' => '<h3>' . t('UIkit Settings') . '</h3>',
    '#weight' => -10,
  );

  // UIkit theme styles.
  $form['theme'] = array(
    '#type' => 'fieldset',
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
    '#default_value' => theme_get_setting('base_style'),
  );

  // Layout settings.
  $form['layout'] = array(
    '#type' => 'fieldset',
    '#title' => t('Layout'),
    '#description' => t('Apply our fully responsive fluid grid system and panels, common layout parts like blog articles and comments and useful utility classes.'),
    '#group' => 'uikit',
  );
  $form['layout']['page_layout'] = array(
    '#type' => 'fieldset',
    '#title' => t('Page Layout'),
    '#description' => t('Change page layout settings.'),
  );
  $form['layout']['page_layout']['standard_layout'] = array(
    '#type' => 'fieldset',
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
    '#default_value' => theme_get_setting('standard_sidebar_positions'),
    '#options' => array(
      'holy-grail' => t('Holy grail'),
      'sidebars-left' => t('Both sidebars left'),
      'sidebars-right' => t('Both sidebars right'),
    ),
  );
  $form['layout']['page_layout']['tablet_layout'] = array(
    '#type' => 'fieldset',
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
    '#default_value' => theme_get_setting('tablet_sidebar_positions'),
    '#options' => array(
      'holy-grail' => t('Holy grail'),
      'sidebars-left' => t('Both sidebars left'),
      'sidebar-left-stacked' => t('Left sidebar stacked'),
      'sidebars-right' => t('Both sidebars right'),
      'sidebar-right-stacked' => t('Right sidebar stacked'),
    ),
  );
  $form['layout']['page_layout']['mobile_layout'] = array(
    '#type' => 'fieldset',
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
    '#default_value' => theme_get_setting('mobile_sidebar_positions'),
    '#options' => array(
      'sidebars-stacked' => t('Sidebars stacked'),
      'sidebars-vertical' => t('Sidebars vertical'),
    ),
  );
  $form['layout']['page_layout']['page_container'] = array(
    '#type' => 'checkbox',
    '#title' => t('Page Container'),
    '#description' => t('Add the .uk-container class to the page container to give it a max-width and wrap the main content of your website. For large screens it applies a different max-width.'),
    '#default_value' => theme_get_setting('page_container'),
  );
  $form['layout']['page_layout']['page_centering'] = array(
    '#type' => 'checkbox',
    '#title' => t('Page Centering'),
    '#description' => t('To center the page container, use the .uk-container-center class.'),
    '#default_value' => theme_get_setting('page_centering'),
  );
  $form['layout']['page_layout']['page_margin'] = array(
    '#type' => 'select',
    '#title' => t('Page margin'),
    '#description' => t('Select the margin to add to the top and bottom of the page container. This is useful, for example, when using the gradient style with a centered page container and a navbar.'),
    '#default_value' => theme_get_setting('page_margin'),
    '#options' => array(
      0 => t('No margin'),
      'uk-margin-top' => t('Top margin'),
      'uk-margin-bottom' => t('Bottom margin'),
      'uk-margin' => t('Top and bottom margin'),
    ),
  );
  $form['layout']['region_layout'] = array(
    '#type' => 'fieldset',
    '#title' => t('Region Layout'),
    '#description' => t('Change region layout settings.<br><br>Use the following links to see an example of each component style.<ul class="links"><li><a href="http://getuikit.com/docs/panel.html" target="_blank">Panel</a></li><li><a href="http://getuikit.com/docs/block.html" target="_blank">Block</a></li></ul>'),
  );

  // Load all regions to assign separate settings for each region.
  foreach ($all_regions as $region_key => $region) {
    $form['layout']['region_layout'][$region_key] = array(
      '#type' => 'fieldset',
      '#title' => t('@region region', array('@region' => $region)),
      '#description' => t('Change the @region region settings.', array('@region' => $region)),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['layout']['region_layout'][$region_key][$region_key . '_style'] = array(
      '#type' => 'select',
      '#title' => t('@title style', array('@title' => $region)),
      '#description' => t('Set the style for the @region region. The theme will automatically style the region accordingly.', array('@region' => $region)),
      '#default_value' => theme_get_setting($region_key . '_style'),
      '#options' => $region_style_options,
    );
  }

  // Navigational settings.
  $form['navigations'] = array(
    '#type' => 'fieldset',
    '#title' => t('Navigations'),
    '#description' => t('UIkit offers different types of navigations, like navigation bars and side navigations. Use breadcrumbs or a pagination to steer through articles.'),
    '#group' => 'uikit',
  );
  $form['navigations']['main_navbar'] = array(
    '#type' => 'fieldset',
    '#title' => t('Navigation bar'),
    '#description' => t('Configure settings for the navigation bar.'),
  );
  $form['navigations']['main_navbar']['navbar_container_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Navbar container'),
    '#description' => t('Configure settings for the navigation bar container.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['navigations']['main_navbar']['navbar_container_settings']['navbar_container'] = array(
    '#type' => 'checkbox',
    '#title' => t('Container'),
    '#description' => t('Add the .uk-container class to the navbar container to give it a max-width and wrap the navbar of your website. For large screens it applies a different max-width.'),
    '#default_value' => theme_get_setting('navbar_container'),
  );
  $form['navigations']['main_navbar']['navbar_container_settings']['navbar_centering'] = array(
    '#type' => 'checkbox',
    '#title' => t('Centering'),
    '#description' => t('To center the navbar container, use the .uk-container-center class.'),
    '#default_value' => theme_get_setting('navbar_centering'),
  );
  $form['navigations']['main_navbar']['navbar_container_settings']['navbar_attached'] = array(
    '#type' => 'checkbox',
    '#title' => t('Navbar attached'),
    '#description' => t("Adds the <code>.uk-navbar-attached</code> class to optimize the navbar's styling to be attached to the top of the viewport. For example, rounded corners will be removed."),
    '#default_value' => theme_get_setting('navbar_attached'),
  );
  $form['navigations']['main_navbar']['navbar_margin'] = array(
    '#type' => 'fieldset',
    '#title' => t('Navbar margin'),
    '#description' => t('Configure the top and bottom margin to apply to the navbar.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['navigations']['main_navbar']['navbar_margin']['navbar_margin_top'] = array(
    '#type' => 'select',
    '#title' => t('Navbar top margin'),
    '#description' => t('Select the amount of top margin to apply to the navbar.'),
    '#default_value' => theme_get_setting('navbar_margin_top'),
    '#options' => $navbar_margin_top_options,
  );
  $form['navigations']['main_navbar']['navbar_margin']['navbar_margin_bottom'] = array(
    '#type' => 'select',
    '#title' => t('Navbar bottom margin'),
    '#description' => t('Select the amount of bottom margin to apply to the navbar.'),
    '#default_value' => theme_get_setting('navbar_margin_bottom'),
    '#options' => $navbar_margin_bottom_options,
  );
  $form['navigations']['main_navbar']['default_menus'] = array(
    '#type' => 'fieldset',
    '#title' => 'Default navbar menus',
    '#description' => t('Adjust settings for the default main and secondary menus in the navbar. Each system-generated and custom menu is available below, except menus assigned to the main and secondary source links.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['navigations']['main_navbar']['default_menus']['main_menu'] = array(
    '#type' => 'fieldset',
    '#title' => 'Main menu',
    '#description' => t('Adjust settings for the main menu in the navbar.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['navigations']['main_navbar']['default_menus']['main_menu']['main_menu_alignment'] = array(
    '#type' => 'select',
    '#title' => 'Main menu alignment',
    '#description' => t('Select whether to align the main menu to the left or right in the navbar.'),
    '#default_value' => theme_get_setting('main_menu_alignment'),
    '#options' => array('Left', 'Right'),
  );
  $form['navigations']['main_navbar']['default_menus']['main_menu']['main_menu_dropdown_support'] = array(
    '#type' => 'checkbox',
    '#title' => 'Main menu dropdown support',
    '#description' => t('Select whether to add dropdown support to the main menu. NOTE: Dropdown functionality is only supported for 2 levels.'),
    '#default_value' => theme_get_setting('main_menu_dropdown_support'),
  );
  $form['navigations']['main_navbar']['default_menus']['secondary_menu'] = array(
    '#type' => 'fieldset',
    '#title' => 'Secondary menu',
    '#description' => t('Adjust settings for the secondary menu in the navbar.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['navigations']['main_navbar']['default_menus']['secondary_menu']['secondary_menu_alignment'] = array(
    '#type' => 'select',
    '#title' => 'Secondary menu alignment',
    '#description' => t('Select whether to align the secondary menu to the left or right in the navbar.'),
    '#default_value' => theme_get_setting('secondary_menu_alignment'),
    '#options' => array('Left', 'Right'),
  );
  $form['navigations']['main_navbar']['default_menus']['secondary_menu']['secondary_menu_dropdown_support'] = array(
    '#type' => 'checkbox',
    '#title' => 'Secondary menu dropdown support',
    '#description' => t('Select whether to add dropdown support to the secondary menu. NOTE: Dropdown functionality is only supported for 2 levels.'),
    '#default_value' => theme_get_setting('secondary_menu_dropdown_support'),
  );
  $form['navigations']['main_navbar']['additional_navbar_menus'] = array(
    '#type' => 'fieldset',
    '#title' => 'Additional navbar menus',
    '#description' => t('Define and adjust settings for additional menus in the navbar.'),
  );

  foreach ($menus as $menu_name => $menu_title) {
    // Ignore the main and secondary menus, they will not be added dynamically.
    $ignore = array(
      $main_menu,
      $secondary_menu,
    );

    if (!in_array($menu_name, $ignore)) {
      // Gets the remaining menus not ignored and creates new settings for each.
      $menu_name = str_replace('-', '_', $menu_name);
      $menu_title = str_replace(' menu', '', $menu_title) . ' menu';

      $form['navigations']['main_navbar']['additional_navbar_menus'][$menu_name] = array(
        '#type' => 'fieldset',
        '#title' => t('@title', array('@title' => $menu_title)),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      $form['navigations']['main_navbar']['additional_navbar_menus'][$menu_name][$menu_name . '_in_navbar'] = array(
        '#type' => 'checkbox',
        '#title' => t('Include the @title in the navbar', array('@title' => $menu_title)),
        '#default_value' => theme_get_setting($menu_name . '_in_navbar'),
      );
      $form['navigations']['main_navbar']['additional_navbar_menus'][$menu_name][$menu_name . '_additional_alignment'] = array(
        '#type' => 'select',
        '#description' => t('Select whether to align the @menu to the left or right in the navbar.', array('@menu' => $menu_title)),
        '#default_value' => theme_get_setting($menu_name . '_alignment'),
        '#options' => array('Left', 'Right'),
      );
    }
  }
  $form['navigations']['local_tasks'] = array(
    '#type' => 'fieldset',
    '#title' => t('Local tasks'),
    '#description' => t('Configure settings for the local tasks menus.'),
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
    '#default_value' => theme_get_setting('primary_tasks_style'),
    '#options' => $subnav_options,
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
    '#default_value' => theme_get_setting('secondary_tasks_style'),
    '#options' => $subnav_options,
  );

  // Basic elements.
  $form['elements'] = array(
    '#type' => 'fieldset',
    '#title' => t('Elements'),
    '#description' => t("Style basic HTML elements, like tables and forms. These components use their own classes. They won't interfere with any default element styling."),
    '#group' => 'uikit',
  );

  // Common components.
  $form['common'] = array(
    '#type' => 'fieldset',
    '#title' => t('Common'),
    '#description' => t("Here you'll find components that you often use within your content, like buttons, icons, badges, overlays, animations and much more."),
    '#group' => 'uikit',
  );

  // Javascript components.
  $form['javascript'] = array(
    '#type' => 'fieldset',
    '#title' => t('Javascript'),
    '#description' => t('These components rely mostly on JavaScript to fade in hidden content, like dropdowns, modal dialogs, off-canvas bars and tooltips.'),
    '#group' => 'uikit',
  );

  // Advanced components.
  $form['components'] = array(
    '#type' => 'fieldset',
    '#title' => t('Components'),
    '#description' => t("UIkit offers some advanced components that are not included in the UIkit core framework. Usually you wouldn't use these components in your everyday website."),
    '#group' => 'uikit',
  );

  // Theme installation instructions.
  $form['get_started'] = array(
    '#type' => 'fieldset',
    '#title' => t('Get started'),
    '#description' => t('Get familiar with the basic setup and structure of UIkit.'),
    '#group' => 'uikit',
  );

  $libraries_enabled = !module_exists('libraries') ? '<span style="color: red">&#x2718;</span> ' : '<span style="color: green">&#x2714;</span> ';
  $root = DRUPAL_ROOT;
  $profile = file_exists($root . drupal_get_path('profile', drupal_get_profile())) ? '<span style="color: green">&#x2714;</span>' : '<span style="color: orange">&#x2718;</span>';
  $all_sites = file_exists($root . '/sites/all/libraries') ? '<span style="color: green">&#x2714;</span>' : '<span style="color: orange">&#x2718;</span>';
  $single_site = file_exists($root . conf_path() . '/libraries') ? '<span style="color: green">&#x2714;</span>' : '<span style="color: orange">&#x2718;</span>';
  $uikit_library_dir = FALSE;

  if (file_exists($root . drupal_get_path('profile', drupal_get_profile()) . '/libraries/uikit')) {
    $uikit_library_dir = $root . drupal_get_path('profile', drupal_get_profile()) . '/libraries/uikit';
  }
  elseif (file_exists($root . '/sites/all/libraries/uikit')) {
    $uikit_library_dir = $root . '/sites/all/libraries/uikit';
  }
  elseif (file_exists($root . conf_path() . '/libraries/uikit')) {
    $uikit_library_dir = $root . conf_path() . '/libraries/uikit';
  }

  $css_exists = file_exists($uikit_library_dir . '/css') ? '<span style="color: green">&#x2714;</span><code>' . t('@uikit/css/...', array(
    '@uikit' => $uikit_library_dir,
  )) . '</code>' : '';

  $fonts_exists = file_exists($uikit_library_dir . '/fonts') ? '<span style="color: green">&#x2714;</span> <code>' . t('@uikit/fonts/...', array(
    '@uikit' => $uikit_library_dir,
  )) . '</code>' : '';

  $js_exists = file_exists($uikit_library_dir . '/js') ? '<span style="color: green">&#x2714;</span> <code>' . t('@uikit/js/...', array(
    '@uikit' => $uikit_library_dir,
  )) . '</code>' : '';

  $uikit_install_success = $libraries_enabled && $css_exists && $fonts_exists && $js_exists ? ' <span style="color: green">&#x2714; Complete!</span>' : ' <span style="color: red">&#x2718; Incomplete!</span>';

  $output = '<div class="form-item form-type-markup">';
  $output .= '<label>' . t('UIkit library installation') . $uikit_install_success . '</label>';
  $output .= '<div class="description">' . t('In general, 3rd party libraries are forbidden in projects hosted on drupal.org. Instead, UIkit uses the Libraries API. Follow the instructions below to install the UIkit library.') . '</div>';
  $output .= '<ol>';

  $output .= '<li>' . $libraries_enabled . t('Download and install the <a href="@libraries_project_url" target="_blank">Libraries API module</a>', array(
    '@libraries_project_url' => 'https://www.drupal.org/project/libraries',
  )) . '</li>';

  $output .= '<li><span style="color: orange">&#x2753;</span> ' . t('Download the <a href="!uikit_library_url" target="_blank">UIkit</a> library', array(
    '!uikit_library_url' => 'http://getuikit.com/docs/documentation_get-started.html',
  )) . '</li>';

  $output .= '<li><span style="color: orange">&#x2753;</span> ' . t('Create a directory named <code>uikit</code> on your desktop and extract the UIkit library into this folder.') . '</li>';
  $output .= '<li>' . t('Check if there is a libraries directory in one of the recommended directories in your Drupal installation. If not, create one:');
  $output .= '<ul>';

  $output .= '<li>' . $profile . ' <code>' . t('@profile/libraries', array(
    '@profile' => drupal_get_path('profile', drupal_get_profile()),
  )) . '</code> OR</li>';

  $output .= '<li>' . $all_sites . ' <code>' . t('sites/all/libraries') . '</code> if you have a mult-site installation and want all sites to have access to the library OR</li>';

  $output .= '<li>' . $single_site . ' <code>' . t('@config/libraries', array(
    '@config' => conf_path(),
  )) . '</code> if the UIkit library should only be accessed by the current site configuration</li>';

  $output .= '</ul>';
  $output .= '</li>';
  $output .= '<li>' . t('Upload the library from your desktop to the libraries directory you chose above');

  if ($uikit_library_dir) {
    $output .= '<br>' . t('Your libraries directory should now look like this:');
    $output .= '<ul>';
    $output .= '<li>' . $css_exists . '</li>';
    $output .= '<li>' . $fonts_exists . '</li>';
    $output .= '<li>' . $js_exists . '</li>';
    $output .= '</ul>';
  }
  else {
    $output .= '<br><span style="color: red">' . t('The UIkit library cannot be found in any libraries directory!') . '</span>';
  }

  $output .= '</li>';
  $output .= '</div>';

  $form['get_started']['basic_setup'] = array(
    '#markup' => $output,
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
  $form['logo']['#attributes']['class'] = array();
  $form['favicon']['#group'] = 'basic_settings';
}
