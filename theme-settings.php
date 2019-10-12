<?php

function uikit_form_system_theme_settings_alter(&$form, \Drupal\Core\Form\FormStateInterface &$form_state, $form_id = NULL) {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  if (isset($form_id)) {
    return;
  }

  

  $form['uikit_options'] = array(
    '#type' => 'details',
    '#open' => TRUE,
    '#title' => t('UIKit Options')
  );

  $form['uikit_options']['include_css'] = array(
    '#type' => 'checkbox',
    '#title' => t('Include compiled CSS from CDN'),
    '#default_value' => theme_get_setting('include_css'),
    '#description' => t('Includes the minified css & js files from jsdelivr. Disable to use custom <a href="@sasslink">SASS</a> or <a href="@lesslink">LESS</a>', array('@sasslink' => 'https://getuikit.com/docs/sass', '@lesslink' => 'https://getuikit.com/docs/less' ))
  );

  $form['uikit_options']['include_icons'] = array(
    '#type' => 'checkbox',
    '#title' => t('Include UIKit Icons'),
    '#default_value' => theme_get_setting('include_icons'),
    '#description' => t('Includes library of <a href="@icons">UIKit icons</a>', array('@icons' => 'https://getuikit.com/docs/icon'))
  );

  $form['uikit_options']['include_rtl'] = array(
    '#type' => 'checkbox',
    '#title' => t('Include RTL stylesheets'),
    '#default_value' => theme_get_setting('Include RTL support styles'),
    '#description' => t('Include <a href="@rtl">right-to-left language support</a> of UIkit design elements, including properties such as floats, text-align, position coordinates, direction of background shadows and more.', array('@rtl' => 'https://getuikit.com/docs/rtl'))
  );

  //kint($form);

  // $form['themedev'] = array(
  //   '#type'          => 'fieldset',
  //   '#title'         => t('Theme development settings'),
  // );
  // $form['themedev']['zen_rebuild_registry'] = array(
  //   '#type'          => 'checkbox',
  //   '#title'         => t('Rebuild theme registry and output template debugging on every page.'),
  //   '#default_value' => theme_get_setting('zen_rebuild_registry'),
  //   '#description'   => t('During theme development, it can be very useful to continuously <a href="!link">rebuild the theme registry</a> and to output template debugging HTML comments. WARNING: this is a huge performance penalty and must be turned off on production websites.', array('!link' => 'https://drupal.org/node/173880#theme-registry')),
  // );

}
