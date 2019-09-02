/**
 * Refactor the codes to follow same convention as header.
 */

jQuery(document).ready(function ($) {

  $('.jupiterx-footer-fixed').each(function (index, element) {
    var footer = $(element),
      spacer

    spacer = $('<span></span>', {
      class: 'jupiterx-footer-dummy'
    })

    footer.after(spacer)

    $(window).resize(function () {
      if (spacer) {
        spacer.height(footer.outerHeight())
      }
    })

    spacer.height(footer.outerHeight())
  });

});
