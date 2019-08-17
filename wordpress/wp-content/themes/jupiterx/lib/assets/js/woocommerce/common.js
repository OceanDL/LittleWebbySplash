jQuery(document).ready(function($) {

  function InputSpinnerInit() {
    $('.quantity > input').InputSpinner({
      buttonsClass: 'btn-sm btn-outline-secondary',
      buttonsWidth: 0
    });
  }

  $(document).on('ready updated_wc_div', function() {
    InputSpinnerInit();
  });

  // Quick cart view.
  $(document).on('click', '.jupiterx-navbar-cart, .raven-shopping-cart', function(e) {
    if ( '#' !== $(this).attr('href') ) {
      return
    }

    e.preventDefault()
    $('body').addClass('jupiterx-cart-quick-view-overlay')
  })

  $(document).on('click', '.jupiterx-mini-cart-close', function() {
    $('body').alterClass('jupiterx-cart-quick-view-*', '')
  })

});
