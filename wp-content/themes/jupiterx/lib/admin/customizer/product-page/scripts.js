(function ($) {
  var api = wp.customize

  api('jupiterx_product_page_image_main_border', (value) => {
    value.bind((to) => {
      $('.woocommerce-product-gallery').flexslider()
    })
  })

  api( 'jupiterx_product_page_image_max_height', (value) => {
    value.bind((to) => {
      let slider = $('.woocommerce-product-gallery').data('flexslider')
      setTimeout( function(){
        slider.resize()
      }, 10 )
    })
  })

})(jQuery);
