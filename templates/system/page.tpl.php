<?php

/**
 * @file
 * UIkit's theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['navigation']: Items for the UIkit navbar component.
 * - $page['header']: Items for the header region, including the site slogan.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
 *
 * @ingroup themeable
 */
?>
<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

  <nav class="uk-navbar">
    <?php if ($logo): ?>
      <a href="<?php print $front_page; ?>" id="logo" class="uk-navbar-brand uk-hidden-small" title="<?php print t('Home'); ?>" rel="home">
        <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>"/>
      </a>
    <?php endif; ?>

    <?php if ($site_name): ?>
      <a href="<?php print $front_page; ?>" id="site-name" class="uk-navbar-brand uk-hidden-small" title="<?php print t('Home'); ?>" rel="home">
        <span><?php print $site_name; ?></span>
      </a>
    <?php endif; ?>

    <?php if ($main_menu || $secondary_menu || $page['navigation']): ?>
      <?php print theme('links__system_main_menu', array(
        'links' => $main_menu,
        'attributes' => array(
          'id' => 'main-menu',
          'class' => array('uk-navbar-nav', 'uk-hidden-small'),
        ),
        'heading' => array(
          'text' => t('Main menu'),
          'level' => 'h2',
          'class' => 'element-invisible',
        ),
      )); ?>

      <?php print theme('links__system_secondary_menu', array(
        'links' => $secondary_menu,
        'attributes' => array(
          'id' => 'secondary-menu',
          'class' => array('uk-navbar-nav', 'uk-float-right', 'uk-hidden-small'),
        ),
        'heading' => array(
          'text' => t('Secondary menu'),
          'level' => 'h2',
          'class' => 'element-invisible',
        ),
      )); ?>
    <?php endif; ?>

    <a href="#offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>

    <?php if ($logo || $site_name): ?>
      <div id="site-branding" class="uk-navbar-center uk-visible-small">
        <?php if ($logo): ?>
          <a href="<?php print $front_page; ?>" class="uk-navbar-brand" title="<?php print t('Home'); ?>" rel="home" id="logo">
            <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>"/>
          </a>
        <?php endif; ?>

        <?php if ($site_name): ?>
          <a href="<?php print $front_page; ?>" class="uk-navbar-brand" title="<?php print t('Home'); ?>" rel="home">
            <span><?php print $site_name; ?></span>
          </a>
        <?php endif; ?>
      </div> <!-- /#site-branding -->
    <?php endif; ?>
  </nav> <!-- /.ui-navbar -->

  <?php if ($site_slogan): ?>
    <div id="site-slogan" class="uk-width-medium-1-1">
      <div class="uk-margin-top uk-margin-bottom"><?php print $site_slogan; ?></div>
    </div><!-- /#site-slogan -->
  <?php endif; ?>

  <div class="uk-grid" data-uk-grid-margin>
    <?php if ($page['header']): ?>
      <?php print render($page['header']); ?>
    <?php endif; ?>

    <?php if ($breadcrumb): ?>
      <div id="breadcrumbs" class="uk-width-1-1 uk-margin-top-remove">
        <?php print $breadcrumb; ?>
      </div>
    <?php endif; ?>

    <?php if ($page['highlighted']): ?>
      <div id="highlighted" class="uk-width-1-1 uk-margin-top-remove">
        <?php print render($page['highlighted']); ?>
      </div>
    <?php endif; ?>

    <?php if ($page['sidebar_first']): ?>
      <div<?php print $sidebar_first_attributes; ?>>
        <?php print render($page['sidebar_first']); ?>
      </div> <!-- /#sidebar-first -->
    <?php endif; ?>

    <div<?php print $content_attributes; ?>>
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h1 id="page-title" class="uk-article-title"><?php print $title; ?></h1><?php endif; ?>
      <?php print render($title_suffix); ?>

      <?php if ($tabs): ?>
        <?php print render($tabs); ?>
      <?php endif; ?>

      <?php if ($messages): ?>
        <div id="messages" class="uk-width-1-1 uk-margin-bottom">
          <?php print $messages; ?>
        </div>
      <?php endif; ?>

      <?php print render($page['help']); ?>

      <?php if ($action_links): ?>
        <ul class="action-links uk-subnav uk-subnav-pill"><?php print render($action_links); ?></ul>
      <?php endif; ?>

      <?php print render($page['content']); ?>
      <?php print $feed_icons; ?>
    </div>

    <?php if ($page['sidebar_second']): ?>
      <div<?php print $sidebar_second_attributes; ?>>
        <?php print render($page['sidebar_second']); ?>
      </div> <!-- /#sidebar-second -->
    <?php endif; ?>

    <?php if ($page['footer']): ?>
      <div id="footer" class="uk-width-1-1">
        <?php print render($page['footer']); ?>
      </div>
    <?php endif; ?>

  </div> <!-- /.ui-grid -->
</div> <!-- /.ui-container -->

<div id="offcanvas" class="uk-offcanvas">
  <div class="uk-offcanvas-bar">

    <?php if ($main_menu || $secondary_menu || $page['navigation']): ?>
      <?php print theme('links__system_main_menu', array(
        'links' => $main_menu,
        'attributes' => array(
          'id' => 'main-menu--offcanvas',
          'class' => array('uk-nav', 'uk-nav-offcanvas'),
        ),
        'heading' => array(
          'text' => t('Main menu'),
          'level' => 'h2',
          'class' => 'element-invisible',
        ),
      )); ?>

      <?php print theme('links__system_secondary_menu', array(
        'links' => $secondary_menu,
        'attributes' => array(
          'id' => 'secondary-menu--offcanvas',
          'class' => array('uk-nav', 'uk-nav-offcanvas'),
        ),
        'heading' => array(
          'text' => t('Secondary menu'),
          'level' => 'h2',
          'class' => 'element-invisible',
        ),
      )); ?>
    <?php endif; ?>
  </div> <!-- /.uk-offcanvas-bar -->
</div> <!-- /#offcanvas -->
