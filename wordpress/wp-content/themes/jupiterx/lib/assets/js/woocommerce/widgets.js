$(document).on('wc_fragments_refreshed wc_fragments_loaded', function() {
  var $buttons = $(document).find('.woocommerce-mini-cart__buttons')

  $.each($buttons, function () {
    $(this).find('a')
    .eq(0)
    .addClass('jupiterx-icon-shopping-cart-6')
  })

  $(document).find('.woocommerce-mini-cart-item .remove')
    .addClass('jupiterx-icon-solid-times-circle')
    .html(' ')

  $(document).find('.woocommerce-mini-cart__empty-message')
    .addClass('jupiterx-icon-shopping-cart-6')
})
