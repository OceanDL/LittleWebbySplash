// This should be refactored.
jQuery(document).ready(function($) {
  /*
    * May 2015
    * scrollDetection 1.0.0
    * @author Mario Vidov
    * @url http://vidov.it
    * @twitter MarioVidov
    * MIT license
    */
  $.scrollDetection = function(options) {
    var settings = $.extend(
      {
        scrollDown: function() {},
        scrollUp: function() {}
      },
      options
    );

    var scrollPosition = 0;
    $(window).scroll(function() {
      var cursorPosition = $(this).scrollTop();
      if (cursorPosition > scrollPosition) {
        settings.scrollDown();
      } else if (cursorPosition < scrollPosition) {
        settings.scrollUp();
      }
      scrollPosition = cursorPosition;
    });
  };

  if (0 === $('.jupiterx-product-sticky-info').length) {
    return;
  }

  jupiterxWooStickySidebar();

  function jupiterxWooStickySidebar() {
    jupiterxStickyScrollUp();

    $.scrollDetection({
      scrollUp: function() {
        jupiterxStickyScrollUp();
      },
      scrollDown: function() {
        jupiterxStickyScrollDown();
      }
    });
  }

  /**
   * Calculate top value on up scroll.
   *
   * @since 6.0.3
   * @return integer top value.
   */
  function jupiterxStickyScrollUp() {
    var top = 50;

    $('.entry-summary').css({
      top: top
    });
  }

  /**
   * Calculate top value on down scroll.
   *
   * @since 6.0.3
   * @return integer top value.
   */
  function jupiterxStickyScrollDown() {
    var top = 50;
    var sidebarHeight = $('.entry-summary').outerHeight();
    var viewportHeight = $(window).height();

    if (sidebarHeight > viewportHeight) {
      top = viewportHeight - sidebarHeight;
    }

    $('.entry-summary').css({
      top: top
    });
  }
});
