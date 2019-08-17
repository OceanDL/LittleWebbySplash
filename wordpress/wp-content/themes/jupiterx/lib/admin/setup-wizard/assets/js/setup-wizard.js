'use strict';

(function ($) {

  var $nav = $('.jupiterx-nav'),
      $content = $('.jupiterx-content'),
      $window = $(window),
      pages = {},
      ajaxSend = void 0,
      moveNextPage = void 0,
      initEvents = void 0;

  /**
   * AJAX call wrapper.
   */
  ajaxSend = function ajaxSend(action, options) {
    if (_.isObject(action)) {
      options = _.extend(action, {});
      action = options.data.action;
    }

    options = _.defaults(options || {}, {
      type: 'POST',
      url: _wpUtilSettings.ajax.url
    });

    options.data = _.extend(options.data || {}, {
      action: 'jupiterx_setup_wizard_' + action
    });

    return $.ajax(options);
  };

  /**
   * Move next page via AJAX call.
   */
  moveNextPage = function moveNextPage() {
    ajaxSend({
      data: {
        'action': 'next_page'
      },
      success: function success(res) {
        var data = res.data;

        $content.hide().attr('class', 'jupiterx-content').addClass('jupiterx-' + data.page).html(data.html).fadeIn(500);

        $nav.trigger('next.owl.carousel');

        // Trigger events.
        $window.trigger('content-loaded', data.page);
      }
    });
  };

  /**
   * Common events for each pages.
   */
  initEvents = function initEvents() {
    $content.find('.jupiterx-next').on('click', function (event) {
      event.preventDefault();
      $(this).button('loading');
      moveNextPage();
    });
  };

  // Update button state.
  $.fn.button = function (state, option) {
    this.each(function () {
      var $this = $(this);

      switch (state) {
        case 'loading':
          $this.attr('disabled', 'disabled');

          // Toggle to remove icon.
          if (!option) {
            $this.append('<i class="fa fa-circle-notch fa-spin"></i>');
          }
          break;

        case 'default':
          $this.removeAttr('disabled').find('.fa').remove();
          break;
      }
    });
  };

  // Prepend an alert box to the element.
  $.fn.alert = function (options) {
    this.each(function () {
      var $node = $(this),
          template = wp.template('jupiterx-alert'),
          $alert = $(template(options)),
          offset = 30;

      $node.find('.alert').remove();

      $node.prepend($alert);

      $window.scrollTop($alert.position().top - offset);
    });

    return this;
  };

  // Add UI click prevention.
  $.fn.blockUi = function () {
    this.each(function () {
      $(this).addClass('jupiterx-block-ui');
    });

    return this;
  };

  // Remove UI click prevention.
  $.fn.unblockUi = function () {
    this.each(function () {
      $(this).removeClass('jupiterx-block-ui');
    });

    return this;
  };

  $.fn.overlaySpinner = function () {
    this.each(function () {
      var $this = $(this);
      $this.addClass('jupiterx-overlay-spinner').append(wp.template('jupiterx-spinner')());
    });

    return this;
  };

  $.fn.removeOverlaySpinner = function () {
    this.each(function () {
      var $this = $(this);
      $this.removeClass('jupiterx-overlay-spinner').find('.jupiterx-spinner-container').remove();
    });

    return this;
  };

  /**
   * Install API activation page.
   */
  pages['api-activation'] = {
    /**
     * Initialize events.
     */
    init: function init() {
      var $form = $content.find('.jupiterx-form'),
          $button = $form.find('button.jupiterx-activate'),
          $input = $form.find('input[type=text]');

      $button.on('click', function (event) {
        event.preventDefault();

        var req = ajaxSend({
          data: {
            'action': 'activate_api',
            'api_key': $input.val()
          },
          beforeSend: function beforeSend() {
            $button.button('loading');
          }
        });

        req.success(function (res) {
          var data = res.data;

          if (data.status) {
            moveNextPage();
          } else {
            $button.button('default');

            $form.alert({
              message: data.message,
              type: 'danger'
            });
          }
        });
      });
    }

    /**
     * Install plugins page.
     */
  };pages['plugins'] = {
    /**
     * Initialize events.
     */
    init: function init() {
      var $form = $content.find('.jupiterx-form'),
          $plugins = $form.find('.jupiterx-plugins-list'),
          $button = $form.find('button.btn');

      $button.on('click', function (event) {
        event.preventDefault();

        var $checkbox = $form.find('input[type=checkbox]:checked'),
            plugins = [],
            req = void 0;

        $checkbox.each(function () {
          plugins.push($(this).val());
        });

        req = ajaxSend({
          data: {
            'action': 'install_plugins',
            'plugins': plugins
          },
          beforeSend: function beforeSend() {
            $button.button('loading');
            $plugins.blockUi();
          }
        });

        req.success(function (res) {

          if (res.hasOwnProperty('install')) {
            $.ajax({
              type: "POST",
              url: res['url'],
              data: res['install'],
              success: function success(res) {

                req = ajaxSend({
                  data: {
                    'action': 'activate_plugins',
                    'plugins': plugins
                  }
                });

                req.success(function (res) {
                  if (res.hasOwnProperty('activate')) {
                    $.ajax({
                      type: "POST",
                      url: res['url'],
                      data: res['activate'],
                      success: function success(res) {
                        moveNextPage();
                      }
                    });
                  }
                });
              }
            });
          } else {

            req = ajaxSend({
              data: {
                'action': 'activate_plugins',
                'plugins': plugins
              }
            });

            req.success(function (res) {
              if (res.hasOwnProperty('activate')) {
                $.ajax({
                  type: "POST",
                  url: res['url'],
                  data: res['activate'],
                  success: function success(res) {
                    moveNextPage();
                  }
                });
              }
            });
          }
        });
      });
    }

    /**
     * Install templates page.
     */
  };pages['templates'] = {
    /**
     * jQuery elements.
     */
    elements: {},

    /**
     * Serves as value cache to determine the start of the current page viewing.
     */
    pageStart: 0,

    /**
     * Total templates pages per request.
     */
    postsPerPage: 9,

    /**
     * Filter templates by name.
     */
    filterName: null,

    /**
     * Load templates from specific category.
     */
    category: null,

    /**
     * Determine whether all templates are completely loaded or it simply means that 'true' value reached the last page.
     */
    loadComplete: false,

    /**
     * To check if AJAX template load is in process.
     */
    isLoading: false,

    /**
     * Check if AJAX template installation is in process.
     */
    isInstalling: false,

    /**
     * Initialize events.
     */
    init: function init() {
      var self = this,
          elements = self.elements;

      elements.$form = $content.find('.jupiterx-form');
      elements.$filter = $content.find('.jupiterx-templates-filter');
      elements.$templates = $content.find('.jupiterx-templates-body');

      self.filterEvents();

      // Load templates on first init.
      self.loadTemplates(function () {
        $window.scroll(_.throttle(self.infiniteLoad.bind(self), 250));
      });
    },

    /**
     * Filter box events.
     */
    filterEvents: function filterEvents() {
      var self = this,
          elements = self.elements,
          $filter = elements.$filter,
          $templates = elements.$templates,
          $name = $filter.find('.jupiterx-templates-filter-name'),
          $category = $filter.find('.jupiterx-templates-filter-category'),
          $submit = $filter.find('.jupiterx-submit'),
          $dropdownToggle = $filter.find('.dropdown-toggle'),
          $dropdown = $filter.find('.dropdown-menu'),
          resetTemplates = void 0;

      resetTemplates = function resetTemplates() {
        // Define load state.
        self.pageStart = 0;
        self.loadComplete = false;
        self.filterName = $name.val();
        self.category = $category.val();

        // Clear templates.
        $templates.empty();
      };

      $submit.on('click', function () {
        if (self.isLoading || self.isInstalling) {
          return;
        }

        event.preventDefault();
        $submit.button('loading', true);

        resetTemplates();
        self.loadTemplates(function () {
          $submit.button('default');
        });
      });

      $dropdown.on('click', '.dropdown-item', function () {
        if (self.isLoading || self.isInstalling) {
          return;
        }

        var $this = $(this);
        $dropdownToggle.text($this.text());
        $category.val($this.data('value'));

        resetTemplates();
        self.loadTemplates();
      });
    },

    /**
     * Get and render templates.
     *
     * @param {function} callback Run callback after request done.
     */
    loadTemplates: function loadTemplates(callback) {
      var self = this,
          elements = self.elements,
          pageStart = self.pageStart,
          postsPerPage = self.postsPerPage,
          $templates = elements.$templates,
          spinner = '.jupiterx-spinner-container',
          req = void 0;

      if (self.isLoading) {
        return;
      }

      if (self.loadComplete) {
        if (callback) callback();
        return;
      }

      if (!$templates.find(spinner).length) {
        self.addSpinner();
      }

      // Send AJAX request.
      req = ajaxSend({
        data: {
          'action': 'get_templates',
          'pagination_start': self.pageStart,
          'pagination_count': self.postsPerPage,
          'template_name': self.filterName,
          'template_category': self.category
        },
        beforeSend: function beforeSend() {
          self.isLoading = true;
        }
      });

      // Add templates and add buttons events.
      req.success(function (res) {
        if (res.data.status) {
          var templates = res.data.templates || [];
          _.each(templates, self.templateBox.bind(self));
        }
      });

      // State updates.
      req.success(function (res) {
        var templates = res.data.templates || [];

        // Update next page to start.
        self.pageStart = pageStart + postsPerPage;

        // Remove spinner and stop loading templates again.
        if (templates.length < postsPerPage) {
          self.removeSpinner();
          self.loadComplete = true;
        }
      });

      req.done(function () {
        self.isLoading = false;
      });

      if (callback) {
        req.done(callback);
      }
    },

    /**
     * Create a template card and apply events.
     *
     * @param {object} data Template data.
     */
    templateBox: function templateBox(data) {
      var self = this,
          template = wp.template('jupiterx-template'),
          $templates = self.elements.$templates,
          $spinner = $templates.children('.jupiterx-spinner-container'),
          $template = void 0,
          temp = void 0;

      temp = _.extend(data, {
        nameClean: data.name.replace(' Jupiterx', ''),
        slugClean: data.slug.replace('-jupiterx', '')
      });

      $template = $(template(temp)).insertBefore($spinner).imagesLoaded().always(function () {
        $template.find('figure').addClass('jupiterx-images-loaded');
      });

      $template
      // Template import button.
      .on('click', '.jupiterx-template-import', function (event) {
        event.preventDefault();

        // Prevent click while other template is installing.
        if (self.isInstalling) {
          return;
        }

        var $this = $(this),
            $figure = $template.find('figure'),
            success = void 0,
            failed = void 0,
            install = void 0;

        success = function success() {
          // Unblock import.
          self.isInstalling = false;
        };

        failed = function failed() {
          // Unblock import and update UI.
          self.isInstalling = false;
          $figure.removeOverlaySpinner();
          $this.button('default');
        };

        install = function install(media) {
          // Block import and update UI.
          self.isInstalling = true;
          $figure.overlaySpinner();
          $this.button('loading', true);

          // Start import.
          self.importTemplate(data, media, success, failed);
        };

        // Template install confirmation.
        jupiterx_modal({
          type: 'warning',
          title: jupiterxWizardSettings['i18n'].installTemplateTitle,
          text: jupiterxWizardSettings['i18n'].installTemplate.replace('{template}', temp.nameClean),
          confirmButtonText: jupiterxWizardSettings['i18n'].confirm,
          cancelButtonText: jupiterxWizardSettings['i18n'].cancel,
          showCancelButton: true,
          showConfirmButton: true,
          onConfirm: function onConfirm() {
            // Media import confirmation.
            jupiterx_modal({
              type: 'warning',
              title: jupiterxWizardSettings['i18n'].importMediaTitle,
              text: jupiterxWizardSettings['i18n'].importMedia,
              confirmButtonText: jupiterxWizardSettings['i18n'].mediaConfirm,
              cancelButtonText: jupiterxWizardSettings['i18n'].mediaCancel,
              showCancelButton: true,
              showConfirmButton: true,
              onConfirm: function onConfirm() {
                // Do not include media.
                install(false);
              },
              onCancel: function onCancel() {
                // Include media.
                install(true);
              }
            });
          }
        });
      })
      // Template install button.
      .on('click', '.jupiterx-template-psd-link', function () {
        var $this = $(this);
        $this.button('loading', true);

        self.templatePsd(data, function () {
          $this.button('default');
        });
      });
    },

    /**
     * Run template installation process.
     *
     * @param {object}   data     Template data.
     * @param {boolean}  media    Import media.
     * @param {function} success  Function success callback.
     * @param {function} failed   Function failed callback.
     */
    importTemplate: function importTemplate(data, media, success, failed) {
      var self = this,
          elements = self.elements,
          current = 0,
          procedures = ['preparation', 'backup_db', 'backup_media_records', 'reset_db', 'upload', 'unzip', 'validate', 'install_plugins', 'activate_plugins', 'theme_content', 'setup_pages', 'plugins_content', 'settings', 'menu_locations', 'theme_widget', 'restore_media_records', 'finalize'],
          _runProc = void 0;

      _runProc = function runProc() {
        var req = ajaxSend({
          data: {
            'action': 'import_template',
            'type': procedures[current],
            'import_media': media,
            'template_id': data.id,
            'template_name': data.name
          }
        });
        req.success(function (res) {
          if ('string' === typeof res) {
            res = JSON.parse(res);
          }
          if ('install_plugins' === procedures[current]) {
            if (!res.hasOwnProperty('install')) {
              current++;
              _runProc();
            } else {
              $.ajax({
                type: "POST",
                url: res['url'],
                data: res['install'],
                success: function success() {
                  current++;
                  _runProc();
                }
              });
            }
          } else if ('activate_plugins' === procedures[current]) {
            if (!res.hasOwnProperty('activate')) {
              current++;
              _runProc();
            } else {
              $.ajax({
                type: "POST",
                url: res['url'],
                data: res['activate'],
                success: function success() {
                  current++;
                  _runProc();
                }
              });
            }
          } else {
            if (!res.status) {
              if (failed) failed();
              elements.$form.alert({
                message: res.message || res.data.message,
                type: 'danger'
              });

              return;
            }

            // Move to next procedure
            current++;

            if (current < procedures.length) {
              // Re-run procedure.
              _runProc();

              // Intentionally added to log result message.
              console.log(res.message);
            } else {
              if (success) success();
              moveNextPage();
            }
          }
        });
      };

      _runProc();
    },

    /**
     * Download PSD.
     *
     * @param {object}   data     Template data.
     * @param {function} callback Function callback.
     */
    templatePsd: function templatePsd(data, callback) {
      var req = ajaxSend({
        data: {
          'action': 'get_template_psd',
          'template_name': data.slugClean
        }
      });

      req.success(function (res) {
        if (res.status && res.data && res.data.psd_link) {
          top.location.href = res.data.psd_link;
        }
      });

      if (callback) {
        req.done(callback);
      }
    },

    /**
     * Load templates on scroll reached the spinner.
     */
    infiniteLoad: function infiniteLoad() {
      var $templates = this.elements.$templates;
      if ($window.scrollTop() > $templates.offset().top + $templates.height() - $window.height()) {
        this.loadTemplates();
      }
    },

    /**
     * Add spinner.
     */
    addSpinner: function addSpinner() {
      this.elements.$templates.append(wp.template('jupiterx-spinner')());
    },

    /**
     * Remove spinner.
     */
    removeSpinner: function removeSpinner() {
      this.elements.$templates.find('.jupiterx-spinner-container').remove();
    }

    // Headings carousel.
  };$nav.owlCarousel({
    center: true,
    items: 3,
    loop: false,
    dots: false,
    nav: false,
    mouseDrag: false,
    touchDrag: false,
    pullDrag: false,
    startPosition: jupiterxWizardSettings.currentPageIndex
  });

  // Initialize events.
  $window.on('content-loaded', function (event, page) {
    initEvents();

    if (page && pages[page]) {
      if (_.isFunction(pages[page].init)) {
        pages[page].init();
      }
    }
  });

  // Trigger events for current page viewing.
  $window.trigger('content-loaded', jupiterxWizardSettings.currentPage);
})(jQuery);