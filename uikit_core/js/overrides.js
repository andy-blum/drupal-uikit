/**
 * @file
 */

(function ($) {
  $('a').each(function() {
    var href = $(this).attr('href');

    if (href === '# ') {
      $(this).attr('href', '#');

      if ($(this).parent('[data-uk-dropdown]').length) {
        $(this).attr('href', '');
      }
    }
  });
})(jQuery);
