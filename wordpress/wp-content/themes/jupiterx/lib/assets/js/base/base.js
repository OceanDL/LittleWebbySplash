
var jupiterx = {
	components: {},
	utils: {},
};

/**
 * Base component.
 *
 * @since 1.0.0
 */
jupiterx.components.Base = Class.extend({
  /**
   * Set elements.
   *
   * @since 1.0.0
   */
  setElements: function () {
    this.elements = {}

    this.elements.window = window;
    this.elements.$window = $(window);
    this.elements.$document = $(document);
    this.elements.$body = $('body');
    this.elements.$site = $('.jupiterx-site');
  },

  /**
   * Set settings.
   *
   * @since 1.0.0
   */
  setSettings: function () {
    this.settings = {}

    this.settings.windowWidth = this.elements.$window.outerWidth();
  },

  /**
   * Bind events.
   *
   * @since 1.0.0
   */
  bindEvents: function () {},

  /**
   * Initialize
   *
   * @since 1.0.0
   */
  init: function () {
    this.setElements()
    this.setSettings()
    this.bindEvents()
  }
});

window.jupiterx = jupiterx;
