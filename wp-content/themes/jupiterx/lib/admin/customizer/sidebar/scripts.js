(function ($) {
  var api = wp.customize;

  api('jupiterx_sidebar_divider_widgets', (value) => {
    value.bind((to, from) => {
      if (typeof to !== 'object' || typeof to.width === 'undefined') {
        return;
      }

      let widgetsDivider = $('.jupiterx-sidebar .jupiterx-widget-divider');

      if (to.width === '') {
        widgetsDivider.remove();
      }

      if (to.width === from.width) {
        return
      }

      if (!widgetsDivider.length) {
        $('.jupiterx-sidebar .jupiterx-widget').after('<span class="jupiterx-widget-divider"></span>');
      }
    });
  });

  api('jupiterx_sidebar_sticky', (value) => {
    value.bind((to) => {
      $('body').toggleClass('jupiterx-sticky-sidebar', to)
    })
  })

})(jQuery);
