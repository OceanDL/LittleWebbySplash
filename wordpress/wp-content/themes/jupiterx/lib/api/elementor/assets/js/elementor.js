'use strict';

(function ($) {

  /**
   * Get templates.
   *
   * @param {object} options
   */
  function getTemplates(options) {
    wp.ajax.send('jupiterx_get_elementor_templates', options);
  }

  /**
   * Open Elementor editor on lightbox.
   *
   * @param {object} options
   */
  function openEditor(options) {
    options = options || {};

    if (!options.url) {
      return;
    }

    $.featherlight({
      variant: 'jupiterx-elementor-editor-lightbox',
      iframe: options.url,
      beforeOpen: function beforeOpen() {
        var $content = this.$instance.find('.featherlight-content');
        if ($content.length) {
          $content.append(getPreloaderHTML());
        }

        if (options.beforeOpen && typeof options.beforeOpen === 'function') {
          return options.beforeOpen(contentWindow);
        }
      },
      beforeClose: function beforeClose() {
        var $iframe = this.$instance.find('iframe');
        if (!$iframe.length) {
          return;
        }

        var contentWindow = $iframe[0].contentWindow;
        if (!contentWindow.elementor) {
          return;
        }

        if (options.beforeClose && typeof options.beforeClose === 'function') {
          return options.beforeClose(contentWindow);
        }
      }
    });
  }

  /**
   * Preloader HTML for Elementor.
   */
  function getPreloaderHTML() {
    return $("\
      <div class='jupiterx-elementor-loading'>\
        <div class='jupiterx-elementor-loader-wrapper'>\
          <div class='jupiterx-elementor-loader'>\
            <div class='jupiterx-elementor-loader-boxes'>\
              <div class='jupiterx-elementor-loader-box'></div>\
              <div class='jupiterx-elementor-loader-box'></div>\
              <div class='jupiterx-elementor-loader-box'></div>\
              <div class='jupiterx-elementor-loader-box'></div>\
            </div>\
          </div>\
          <div class='jupiterx-elementor-loading-title'>\
            Loading\
          </div>\
        </div>\
      </div>\
    ");
  }

  window.jupiterx = window.jupiterx || {};

  // Elementor.
  window.jupiterx.elementor = {
    getTemplates: getTemplates,
    openEditor: openEditor
  };
})(jQuery);