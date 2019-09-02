(function($) {

  var jupiterx = window.jupiterx || {}

  /**
   * Utilities.
   *
   * @since 1.0.0
   */
  jupiterx.utils = function () {
    /**
     * Resize.
     *
     * @since 1.0.0
     */
    this.resize = function () {
      var pubsub = jupiterx.pubsub

      $(window).on('resize', _.throttle(function() {
        var width = $(this).outerWidth()

        pubsub.publish('resize', width);
      }, 150));
    },

    /**
     * Scroll.
     *
     * @since 1.0.0
     */
    this.scroll = function () {
      var pubsub = jupiterx.pubsub
      var $dom = $('[data-jupiterx-scroll]')
      var options = _.defaults($dom.data('jupiterxScroll') || {}, {
        offset: 1000,
      })

      $(window).on('scroll', _.throttle(function() {
        var position = $(this).scrollTop()

        pubsub.publish('scroll', position);

        if (_.size($dom) < 1) return

        if (position > options.offset) {
          return $dom.addClass('jupiterx-scrolled')
        }

        $dom.removeClass('jupiterx-scrolled')
      }, 100));
    }

    this.scrollSmooth = function () {
      var defaultDuration = 500

      zenscroll.setup(defaultDuration)

      $(document).on('click', '[data-jupiterx-scroll-target]', function (event) {
        var target = $(this).data('jupiterxScrollTarget')
        event.preventDefault()

        // Number.
        if (_.isNumber(target)) {
          zenscroll.toY(target)
          return
        }

        // CSS selector.
        zenscroll.to($(target)[0])
      })
    }

    // Scroll Up/Down detection.
    this.scrollDirection = function () {
      var pubsub = this.pubsub
      var $dom = $('[data-jupiterx-scroll-direction]')
      var scroll = updwn({ speed: 50 })

      scroll.up(function () {
        pubsub.publish('scroll-up');

        if (_.size($dom) < 1) return
        $dom.addClass('jupiterx-scroll-up')
        $dom.removeClass('jupiterx-scroll-down')
      })

      scroll.down(function () {
        pubsub.publish('scroll-down');

        if (_.size($dom) < 1) return
        $dom.addClass('jupiterx-scroll-down')
        $dom.removeClass('jupiterx-scroll-up')
      })
    }

    /**
     * Alter class.
     *
     * @see https://gist.github.com/peteboere/1517285
     *
     * @since 1.0.0
     */
    this.alterClass = function(elm, removals, additions) {
      var self = elm;

      if (removals.indexOf('*') === -1) {
        // Use native jQuery methods if there is no wildcard matching
        self.removeClass(removals);
        return !additions ? self : self.addClass(additions);
      }

      var patt = new RegExp(
        '\\s' + removals.replace(/\*/g, '[A-Za-z0-9-_]+').split(' ').join('\\s|\\s') + '\\s',
        'g'
      );

      self.each(function(i, it) {
        var cn = ' ' + it.className + ' ';
        while (patt.test(cn)) {
          cn = cn.replace(patt, ' ');
        }
        it.className = $.trim(cn);
      });

      return !additions ? self : self.addClass(additions);
    }

    /**
     * Initialize.
     *
     * @since 1.0.0
     */
    this.init = function(){
      this.resize()
      this.scroll()
      this.scrollSmooth()
    }

    this.init()
  }

})( jQuery );
