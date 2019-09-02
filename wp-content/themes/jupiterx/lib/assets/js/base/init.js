jQuery(document).ready(function ($) {

  var jupiterx = window.jupiterx || {}

  /**
   * Initialize components.
   *
   * @since 1.0.0
   */
  jupiterx.initComponents = function () {
    for (component in this.components) {
      new this.components[component];
    }
  }

  /**
   * Initialize.
   *
   * @since 1.0.0
   */
  jupiterx.init = function () {
    this.pubsub = new PubSub()
    this.pubsub.publish('init');

    this.utils = new this.utils()
    this.initComponents()
  }

  jupiterx.init();
});
