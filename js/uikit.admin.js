(function ($) {
  Drupal.behaviors.uikitFieldsetSummaries = {
    attach: function (context) {
      // Provide the summary for the base style form.
      $('fieldset.uikit-theme-settings-form', context).drupalSetSummary(function (context) {
        var vals = [];

        // Base style setting.
        vals.push($(".form-item-base-style select option:selected", context).text());

        return Drupal.checkPlain(vals.join(', '));
      });
    }
  };

  Drupal.behaviors.uikitLayoutDemo = {
    attach: function (context) {
      // Provide a graphical demonstration of the standard layout.
      $('input[name=standard_sidebar_positions]', context).click(function () {
        var itemClass = 'uk-layout-' + $(this).attr('value');
        var formItem = $(this).parents('.form-type-radios');
        var target = formItem.prev();

        // Remove all possible layout classes.
        target.removeClass('uk-layout-holy-grail')
          .removeClass('uk-layout-sidebars-left')
          .removeClass('uk-layout-sidebars-right');

        // Add a class based on which radio is selected.
        target.addClass(itemClass);
      });

      $('input[name=tablet_sidebar_positions]', context).click(function () {
        // Provide a graphical demonstration of the tablet layout.
        var itemClass = 'uk-layout-' + $(this).attr('value');
        var formItem = $(this).parents('.form-type-radios');
        var target = formItem.prev();

        // Remove all possible layout classes.
        target.removeClass('uk-layout-holy-grail')
          .removeClass('uk-layout-sidebars-left')
          .removeClass('uk-layout-sidebar-left-stacked')
          .removeClass('uk-layout-sidebars-right')
          .removeClass('uk-layout-sidebar-right-stacked');

        // Add a class based on which radio is selected.
        target.addClass(itemClass);
      });

      $('input[name=mobile_sidebar_positions]', context).click(function () {
        // Provide a graphical demonstration of the mobile layout.
        var itemClass = 'uk-layout-' + $(this).attr('value');
        var formItem = $(this).parents('.form-type-radios');
        var target = formItem.prev();

        // Remove all possible layout classes.
        target.removeClass('uk-layout-sidebars-stacked')
          .removeClass('uk-layout-sidebars-vertical');

        // Add a class based on which radio is selected.
        target.addClass(itemClass);
      });
    }
  };
})(jQuery);
