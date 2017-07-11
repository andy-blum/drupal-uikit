<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page while offline.
 *
 * All the available variables are mirrored in html.tpl.php and page.tpl.php.
 * Some may be blank but they are provided for consistency.
 *
 * @see template_preprocess()
 * @see template_preprocess_maintenance_page()
 *
 * @ingroup themeable
 */
?>
<!DOCTYPE html>
<html lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>" class="uk-height-1-1">
<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>
<body class="<?php print $classes; ?>">
  <header id="page-header" class="uk-container uk-container-center">
    <nav id="page-navbar" class="uk-navbar uk-margin-top">
      <?php if ($logo): ?>
        <a href="<?php print $base_path; ?>" id="logo-large" class="uk-navbar-brand uk-hidden-small" title="<?php print t('Home'); ?>" rel="home">
          <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>"/>
        </a>
      <?php endif; ?>

      <?php if ($site_name): ?>
        <a href="<?php print $base_path; ?>" id="site-name" class="uk-navbar-brand uk-hidden-small" title="<?php print t('Home'); ?>" rel="home">
          <span><?php print $site_name; ?></span>
        </a>
      <?php endif; ?>

      <?php if ($logo || $site_name): ?>
        <div id="site-branding" class="uk-navbar-center uk-visible-small">
          <?php if ($logo): ?>
            <a href="<?php print $base_path; ?>" class="uk-navbar-brand" title="<?php print t('Home'); ?>" rel="home" id="logo-small">
              <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>"/>
            </a>
          <?php endif; ?>

          <?php if ($site_name): ?>
            <a href="<?php print $base_path; ?>" class="uk-navbar-brand" title="<?php print t('Home'); ?>" rel="home">
              <span><?php print $site_name; ?></span>
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </nav>
  </header>

  <div id="page" class="uk-container uk-container-center uk-margin">
    <div class="uk-grid" data-uk-grid-margin>

      <div<?php print $content_attributes; ?>>
        <?php if (!empty($title)): ?>
          <h1 id="page-title" class="uk-article-title"><?php print $title; ?></h1>
        <?php endif; ?>

        <?php if (!empty($messages)): ?>
          <div id="messages" class="uk-width-1-1 uk-margin">
            <?php print $messages; ?>
          </div>
        <?php endif; ?>

        <div class="content"><?php print $content; ?></div>
      </div>

      <?php if (!empty($sidebar_first)): ?>
        <div<?php print $sidebar_first_attributes; ?>>
          <?php print $sidebar_first; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($sidebar_second)): ?>
        <div<?php print $sidebar_second_attributes; ?>>
          <?php print $sidebar_second; ?>
        </div>
      <?php endif; ?>

    </div>

    <?php if (!empty($footer)): ?>
      <?php print $footer; ?>
    <?php endif; ?>

  </div>

</body>
</html>
