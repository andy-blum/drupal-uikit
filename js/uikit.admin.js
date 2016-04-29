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
})(jQuery);
