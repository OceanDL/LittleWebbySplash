(function ($) {
  var api = wp.customize;

  api('jupiterx_footer_widgets_divider', (value) => {
    value.bind((to, from) => {
      if (typeof to !== 'object' || typeof to.width === 'undefined') {
        return;
      }

      let widgetsDivider = $('.jupiterx-footer-widgets .jupiterx-widget-divider');

      if (to.width === '') {
        widgetsDivider.remove();
      }

      if (to.width === from.width) {
        return
      }

      if (!widgetsDivider.length) {
        $('.jupiterx-footer-widgets .jupiterx-widget').after('<span class="jupiterx-widget-divider"></span>');
      }
    });
  });

})(jQuery);
