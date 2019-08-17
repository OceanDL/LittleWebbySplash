'use strict';

(function ($) {
  var fullWidthBlockStyles = '.wp-block { max-width: 100%; }';
  var mainWidthBlockStyles = '<style id="jupiterx-gutenberg-main-width-styles"> .wp-block { max-width:' + jupiterx_gutenberg_width["main"] + '; } </style>';
  var pageTemplate = $('.editor-page-attributes__template select').val();

  $(document).ready(function () {
    $('head').append(mainWidthBlockStyles);
    $('head').append('<style id="jupiterx-gutenberg-dynamic-width-style"></style>');
  });

  $(document).on('change', '.editor-page-attributes__template select', function () {
    pageTemplate = $(this).val();
    if ('full-width.php' === pageTemplate) {
      $('#jupiterx-gutenberg-dynamic-width-style').html(fullWidthBlockStyles);
    } else {
      $('#jupiterx-gutenberg-dynamic-width-style').html('.wp-block { max-width:' + (jupiterx_gutenberg_width[$('#acf-field_jupiterx_post_main_layout').val()] || "") + '; }');
    }
  });

  $(document).on('change', '#acf-field_jupiterx_post_main_layout', function () {
    if ('full-width.php' === pageTemplate) {
      $('#jupiterx-gutenberg-dynamic-width-style').html(fullWidthBlockStyles);
    } else {
      $('#jupiterx-gutenberg-dynamic-width-style').html('.wp-block { max-width:' + jupiterx_gutenberg_width[$(this).val()] + '; }');
    }
  });
})(jQuery);