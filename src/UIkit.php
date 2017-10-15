<?php

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
  const UIKIT_LIBRARY = 'https://getuikit.com/v2';

  /**
   * The UIkit library version supported in the UIkit base theme.
   *
   * @var string
   */
  const UIKIT_LIBRARY_VERSION = '2.27.4';

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
  const UIKIT_PROJECT_BRANCH = '7.x-2.x';

  /**
   * The Drupal project API site for the UIkit base theme.
   *
   * @var string
   */
  const UIKIT_PROJECT_API = 'http://uikit-drupal.com';

  /**
   * The jQuery library version UIkit supports.
   *
   * @var string
   */
  const UIKIT_JQUERY_VERSION = '2.1.4';

  /**
   * The jQuery Migrate library version UIkit supports.
   *
   * @var string
   */
  const UIKIT_JQUERY_MIGRATE_VERSION = '1.4.1';

  /**
   * Checks whether the given path is the current path.
   *
   * @param string $path
   *   The path to check against the current path.
   *
   * @return bool
   *   Returns true if the given path is the current path, false if otherwise.
   */
  public static function getActivePath($path) {
    $active_path = FALSE;

    // Checks if the path is the current page.
    $current_page = $path == $_GET['q'];

    // Checks if the path and current page are the front page.
    $front_page = $path == '<front>' && drupal_is_front_page();

    // Checks if the path and current page are both a user page.
    $exploded_path = explode('/', $_GET['q']);
    $user_page = is_array($exploded_path) && $exploded_path[0] == 'user' && $exploded_path[0] == $path;

    // Change $active_path to true if the given path is the current path.
    if ($current_page || $front_page || $user_page) {
      $active_path = TRUE;
    }

    return $active_path;
  }

  /**
   * Retrieves the active theme.
   *
   * @return
   *   The active theme's machine name.
   */
  public static function getActiveTheme() {
    global $theme;
    return $theme;
  }

  /**
   * Retrieves UIkit, jQuery and jQuery Migrate CDN assets.
   */
  public static function getCdnAssets() {
    // Get the UIkit base style theme setting.
    $uikit_style = self::getThemeSetting('base_style', self::getActiveTheme());

    // Default base style.
    $uikit_css = 'uikit.min.css';

    if ($uikit_style == 'customizer-css') {
      // If Customizer is used, locate and add the stylehseet.
      $customizer_css = self::getThemeSetting('customizer_css', self::getActiveTheme());
      $file = file_load($customizer_css['fid']);

      drupal_add_css($file->uri, array(
        'group' => CSS_THEME,
        'every_page' => TRUE,
        'weight' => -20,
        'version' => self::UIKIT_LIBRARY_VERSION,
      ));
    }
    else {
      // If not using the default base style, get the correct style.
      switch ($uikit_style) {
        case 'almost-flat':
          $uikit_css = 'uikit.almost-flat.min.css';
          break;

        case 'gradient':
          $uikit_css = 'uikit.gradient.min.css';
          break;
      }

      // Add the selected UIkit stylesheet.
      drupal_add_css('//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/$uikit_css", array(
        'type' => 'external',
        'group' => CSS_THEME,
        'every_page' => TRUE,
        'weight' => -20,
        'version' => self::UIKIT_LIBRARY_VERSION,
      ));
    }

    // Add the jQuery script.
    drupal_add_js('//cdnjs.cloudflare.com/ajax/libs/jquery/' . self::UIKIT_JQUERY_VERSION . '/jquery.min.js', array(
      'type' => 'external',
      'group' => JS_THEME,
      'every_page' => TRUE,
      'weight' => -20,
      'version' => self::UIKIT_JQUERY_VERSION,
    ));

    // Add the jQuery Migrate script so we can use multiple jQuery versions
    // simultaneously.
    drupal_add_js('//cdnjs.cloudflare.com/ajax/libs/jquery-migrate/' . self::UIKIT_JQUERY_MIGRATE_VERSION . '/jquery-migrate.min.js', array(
      'type' => 'external',
      'group' => JS_THEME,
      'every_page' => TRUE,
      'weight' => -20,
      'version' => self::UIKIT_JQUERY_MIGRATE_VERSION,
    ));

    // Add the UIkit script.
    drupal_add_js('//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . '/js/uikit.min.js', array(
      'type' => 'external',
      'group' => JS_THEME,
      'every_page' => TRUE,
      'weight' => -20,
      'version' => self::UIKIT_LIBRARY_VERSION,
    ));
  }

  /**
   * Retrieves individual UIkit CDN asset.
   *
   * Retrieves an individual UIkit asset from cdnjs.cloudflare.com by a given
   * component name.
   *
   * @param string $component
   *   The component to retrieve CDN assets for.
   */
  public static function getCdnAsset($component = NULL) {
    // Get the base style theme setting.
    $uikit_style = self::getThemeSetting('base_style', self::getActiveTheme());

    switch ($uikit_style) {
      // Get the component style based on the base style.
      case 'almost-flat':
        $css = "$component.almost-flat.min.css";
        break;

      case 'gradient':
        $css = "$component.gradient.min.css";
        break;

      default:
        $css = "$component.min.css";
    }

    // Put all valid components and their assets into an array so we can load the
    // the correct assets.
    $components = array(
      'accordion' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'autocomplete' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'datepicker' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'dotnav' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => NULL,
      ),
      'form-advanced' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => NULL,
      ),
      'form-file' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => NULL,
      ),
      'form-password' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'form-select' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'grid-parallax' => array(
        'css' => NULL,
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'grid' => array(
        'css' => NULL,
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'htmleditor' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'lightbox' => array(
        'css' => NULL,
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'nestable' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'notify' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'pagination' => array(
        'css' => NULL,
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'parallax' => array(
        'css' => NULL,
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'placeholder' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => NULL,
      ),
      'progress' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => NULL,
      ),
      'search' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'slidenav' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => NULL,
      ),
      'slider' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'slideset' => array(
        'css' => NULL,
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'slideshow-fx' => array(
        'css' => NULL,
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'slideshow' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'sortable' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'sticky' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'timepicker' => array(
        'css' => NULL,
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'tooltip' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
      'upload' => array(
        'css' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/css/components/$css",
        'js' => '//cdnjs.cloudflare.com/ajax/libs/uikit/' . self::UIKIT_LIBRARY_VERSION . "/js/components/$component.min.js",
      ),
    );

    if (isset($components[$component])) {
      // Get the css and js assets for the component.
      $component_css = $components[$component]['css'];
      $component_js = $components[$component]['js'];

      if (!empty($component_css)) {
        // Load component's stylesheet if it has one.
        drupal_add_css($component_css, array(
          'type' => 'external',
          'group' => CSS_THEME,
          'every_page' => TRUE,
          'weight' => -10,
          'version' => self::UIKIT_LIBRARY_VERSION,
        ));
      }

      if (!empty($component_js)) {
        // Load component's script if it has one.
        drupal_add_js($component_js, array(
          'type' => 'external',
          'group' => JS_THEME,
          'every_page' => TRUE,
          'weight' => -10,
          'version' => self::UIKIT_LIBRARY_VERSION,
        ));
      }
    }
    else {
      // Warn the user of an invald component.
      $message = t("The component <em class='placeholder'>@component</em> does not exist in <em class='placeholder'>UIkit::getCdnAsset('@component')</em>. Please check <a href='@components/docs/components.html'>Conponents - UIkit Documentation</a> for the correct component name, replacing capital letters with lowercase letters and spaces with a dash, i.e. <em class='placeholder'>UIkit::getCdnAsset('grid-parallax')</em>", array('@component' => $component, '@components' => self::UIKIT_LIBRARY));
      drupal_set_message($message, 'error');
      watchdog('uikit', $message, array(), WATCHDOG_ERROR);
    }
  }

  /**
   * Retrieves a theme setting.
   *
   * @param null $setting
   *   The machine-name of the theme setting to retrieve.
   * @param $theme
   *   The theme to retrieve the setting for. Defaults to the active theme.
   *
   * @return mixed
   *   The theme setting's value.
   */
  public static function getThemeSetting($setting, $theme = NULL) {
    if (empty($theme)) {
      $theme = self::getActiveTheme();
    }

    if (!empty($setting)) {
      return theme_get_setting($setting, $theme);
    }
    else {
      throw new \LogicException('Missing argument $setting');
    }
  }

  /**
   * Retrieves the current page title.
   *
   * @return string
   *   The current page title.
   */
  public static function getPageTitle() {
    return drupal_get_title();
  }

  /**
   * Loads a UIkit include file.
   *
   * This function essentially does the same as Drupal core's
   * module_load_include() function, except targeting theme include files. It also
   * allows you to place the include files in a sub-directory of the theme for
   * better organization.
   *
   * Examples:
   * @code
   *   // Load includes/uikit_subtheme.admin.inc from the node module.
   *   UIkit::loadIncludeFile('inc', 'uikit_subtheme', 'uikit_subtheme.admin', 'includes');
   *   // Load preprocess.inc from the uikit_subtheme theme.
   *   UIkit::loadIncludeFile('inc', 'uikit_subtheme', 'preprocess');
   * @endcode
   *
   * Do not use this function in a global context since it requires Drupal to be
   * fully bootstrapped, use require_once DRUPAL_ROOT . '/path/file' instead.
   *
   * @param string $type
   *   The include file's type (file extension).
   * @param string $theme
   *   The theme to which the include file belongs.
   * @param string $name
   *   (optional) The base file name (without the $type extension). If omitted,
   *   $theme is used; i.e., resulting in "$theme.$type" by default.
   * @param string $sub_directory
   *   (optional) The sub-directory to which the include file resides.
   *
   * @return string
   *   The name of the included file, if successful; FALSE otherwise.
   */
  public static function loadIncludeFile($type, $theme, $name = NULL, $sub_directory = '') {
    static $files = array();

    if (isset($sub_directory)) {
      $sub_directory = '/' . $sub_directory;
    }

    if (!isset($name)) {
      $name = $theme;
    }

    $key = $type . ':' . $theme . ':' . $name . ':' . $sub_directory;

    if (isset($files[$key])) {
      return $files[$key];
    }

    if (function_exists('drupal_get_path')) {
      $file = DRUPAL_ROOT . '/' . drupal_get_path('theme', $theme) . "$sub_directory/$name.$type";
      if (is_file($file)) {
        require_once $file;
        $files[$key] = $file;
        return $file;
      }
      else {
        $files[$key] = FALSE;
      }
    }
    return FALSE;
  }
}