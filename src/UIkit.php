<?php
/**
 * @file
 * Contains \Drupal\uikit\UIkit.
 */

namespace Drupal\uikit;

/**
 * Provides helper functions for the UIkit base theme.
 */
class UIkit {

  /**
   * The UIkit library project page.
   *
   * @var string
   */
  const UIKIT_LIBRARY = 'https://getuikit.com';

  /**
   * The UIkit library version supported in the UIkit base theme.
   *
   * @var string
   */
  const UIKIT_LIBRARY_VERSION = '2.27.2';

  /**
   * The UIkit library documentation site.
   *
   * @var string
   */
  const UIKIT_LIBRARY_DOCUMENTATION = 'https://getuikit.com/v2/index.html';

  /**
   * The Drupal project page for the UIkit base theme.
   *
   * @var string
   */
  const UIKIT_PROJECT = 'https://www.drupal.org/project/uikit';

  /**
   * The Drupal project branch for the UIkit base theme.
   *
   * @var string
   */
  const UIKIT_PROJECT_BRANCH = '8.x-2.x';

  /**
   * The Drupal project API site for the UIkit base theme.
   *
   * @var string
   */
  const UIKIT_PROJECT_API = 'http://uikit-drupal.com';

  /**
   * Retrieves the active theme.
   *
   * @return
   *   The active theme's machine name.
   */
  public static function getActiveTheme() {
    return \Drupal::theme()->getActiveTheme()->getName();
  }

  /**
   * Retrieves a theme setting.
   *
   * @param null $setting
   *   The setting to get.
   * @param $theme
   *   The theme to get the setting for. Default is active theme.
   *
   * @return mixed
   *   The theme setting's value.
   */
  public static function getThemeSetting($setting, $theme = NULL) {
    if (empty($theme)) {
      $theme = UIkit::getActiveTheme();
    }

    return theme_get_setting($setting, $theme);
  }

  public static function getBaseStyle() {
    return UIkit::getThemeSetting('base_style');
  }

  public static function getUIkitLibrary() {
    switch (UIkit::getBaseStyle()) {
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

  public static function getUIkitComponent($component) {
    switch (UIkit::getBaseStyle()) {
      case 'almost-flat':
        return "uikit/uikit.$component.almost-flat";

      case 'gradient':
        return "uikit/uikit.$component.gradient";

      default:
        return "uikit/uikit.$component";
    }
  }
}
