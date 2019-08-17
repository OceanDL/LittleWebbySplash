/**
 * Disable lazy load of specific images.
 *
 * @see https://wordpress.org/plugins/lazy-load
 */
(function ($) {
  $('.jet-portfolio__list, .jet-image-comparison').each(function () {
    $(this).find('img[data-lazy-src]').each(function (index, img) {
      var $img = jQuery(img),
        src = $img.attr('data-lazy-src');

      if (!src)
        return;

      $img.unbind('scrollin')
        .hide()
        .removeAttr('data-lazy-src')
        .attr('data-lazy-loaded', 'true');

      img.src = src;
      $img.fadeIn();
    });
  });
})(jQuery)
