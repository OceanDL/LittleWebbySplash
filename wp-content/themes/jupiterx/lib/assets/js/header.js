(function($) {

  var jupiterx = window.jupiterx || {}

  /**
   * Header component.
   *
   * @since 1.0.0
   */
  jupiterx.components.Header = jupiterx.components.Base.extend({
    /**
     * Set elements.
     *
     * @since 1.0.0
     */
    setElements: function () {
      this._super()

      var elements = this.elements
      elements.header = '.jupiterx-header'
      elements.$header = $(elements.header)
      elements.$navbar = elements.$header.find('.navbar-nav')
      elements.$dropdownToggler = elements.$navbar.find('.dropdown-toggle-icon')
      elements.$window = $(window)
    },

    /**
     * Set settings.
     *
     * @since 1.0.0
     */
    setSettings: function () {
      this._super()

      var settings = this.settings
      var headerSettings = this.elements.$header.data('jupiterxSettings')

      settings.breakpoint = headerSettings.breakpoint
      settings.template = headerSettings.template
      settings.stickyTemplate = headerSettings.stickyTemplate
      settings.behavior = headerSettings.behavior
      settings.position = headerSettings.position || 'top'
      settings.offset = headerSettings.offset
      settings.overlap = headerSettings.overlap
    },

    /**
     * Bind events.
     *
     * @since 1.0.0
     */
    bindEvents: function() {
      var self = this
      var elements = this.elements
      var settings = this.settings

      // Behavior.
      self.setBehavior()
      self.mobileMenuScroll()

      // Navbar.
      elements.$dropdownToggler.on('click', function (event) {
        self.initNavbarDropdown(event)
        self.setHeight()
      })

      // Resize subscribe.
      jupiterx.pubsub.subscribe('resize', function (windowWidth) {
        // Behavior.
        self.setBehavior()
        self.setHeight()

        // Navbar
        if (windowWidth > settings.breakpoint) {
          elements.$navbar.find('.dropdown-menu').removeClass('show')
        }
      })

      // Scroll subscribe.
      jupiterx.pubsub.subscribe('scroll', function (position) {
        // Sticky behavior.
        self.setBehaviorSticky(position)
      })
    },

    /**
     * Set maximum height for menu to allow scroll on menu.
     *
     * @since 1.0.1
     */
    setHeight: function() {
      var navContainer = this.elements.$header.find('.navbar-collapse')
      if ( navContainer.length ) {
        var navbar = this.elements.$navbar
        navContainer.css('max-height', document.documentElement.clientHeight - navContainer.offset().top + window.pageYOffset - parseInt(navbar.css('margin-top')) );
      }
    },

    /**
     * Prevent body scroll while scrolling mobile menu. (touch scroll only)
     *
     * @since 1.0.2
     */
    mobileMenuScroll: function () {
      var overlays = document.getElementsByClassName('navbar-collapse'),
        _clientY = null
      for (var i = 0; i < overlays.length; i++) {
        overlays[i].addEventListener('touchstart', function (event) {
          if (event.targetTouches.length === 1) {
            _clientY = event.targetTouches[0].clientY
          }
        }, false)

        overlays[i].addEventListener('touchmove', function (event) {
          if (event.targetTouches.length === 1) {
            var clientY = event.targetTouches[0].clientY - _clientY
            if (overlays[i].scrollTop === 0 && clientY > 0 && event.cancelable) {
              event.preventDefault()
            }
            if (overlays[i].scrollHeight - overlays[i].scrollTop <= overlays[i].clientHeight && clientY < 0 && event.cancelable) {
              event.preventDefault()
            }
          }
        }, false)
      }
    },

    /**
     * Set behavior.
     *
     * @since 1.0.0
     */
    setBehavior: function () {
      this.setBehaviorFixed()
      this.setBehaviorSticky()
    },

    /**
     * Set fixed behavior.
     *
     * @since 1.0.0
     */
    setBehaviorFixed: function () {
      if (this.settings.behavior === 'fixed') {
        this.setSiteSpacing()
      }
    },

    /**
     * Set sticky behavior.
     *
     * @since 1.0.0
     */
    setBehaviorSticky: function (position) {
      var elements = this.elements,
        settings = this.settings

      if (settings.behavior !== 'sticky') {
        return
      }

      // Stick.
      if (position > settings.offset / 2) {
        elements.$body.addClass('jupiterx-header-stick')
        this.setSiteSpacing()
      } else {
        elements.$body.removeClass('jupiterx-header-stick')
        this.clearSiteSpacing()
      }

      // Sticked.
      if (position > settings.offset) {
        elements.$body.addClass('jupiterx-header-sticked')
      } else {
        elements.$body.removeClass('jupiterx-header-sticked')
      }
    },

    /**
     * Set site spacing.
     *
     * @since 1.0.0
     */
    setSiteSpacing: function () {
      var elements = this.elements,
        settings = this.settings

      if (this.isOverlap()) {
        this.clearSiteSpacing()
        return
      }

      var $header = elements.$header

      if ($header.find('.elementor-' + settings.template).length) {
        $header = $header.find('.elementor-' + settings.template)
      }

      elements.$site.css('padding-' + settings.position, $header.outerHeight())
    },

    /**
     * Clear site spacing.
     *
     * @since 1.0.0
     */
    clearSiteSpacing: function () {
      this.elements.$site.css('padding-' + this.settings.position, '')
    },

    /**
     * Check if header should overlap content.
     *
     * @since 1.0.0
     *
     * @return {boolean} Overlap status.
     */
    isOverlap: function () {
      var elements = this.elements,
        windowWidth = elements.$window.outerWidth(),
        overlap = this.settings.overlap

      if (!overlap) {
        return false
      }

      var desktop = (windowWidth > 768 && overlap.indexOf('desktop') > -1),
        tablet = ((windowWidth < 767.98 && windowWidth > 576) && overlap.indexOf('tablet') > -1),
        mobile = (windowWidth < 575.98 && overlap.indexOf('mobile') > -1)

      // Check current state depending on windowWidth.
      if (desktop || tablet || mobile) {
        return true
      }

      return false
    },

    /**
     * Add dropdown behavior to navbar in responsive state.
     *
     * @since 1.0.0
     */
    initNavbarDropdown: function (event) {
      event.preventDefault()
      event.stopPropagation()

      if (this.elements.$window.outerWidth() > this.settings.breakpoint) {
        return
      }

      $(event.target).closest('.menu-item').find('> .dropdown-menu').toggleClass('show')
    },

    /**
     * Initialize
     *
     * @since 1.0.0
     */
    init: function () {
      this.setElements()

      if (!this.elements.$header.length) {
        return;
      }

      this.setSettings()
      this.bindEvents()
    }
  });

})( jQuery );
