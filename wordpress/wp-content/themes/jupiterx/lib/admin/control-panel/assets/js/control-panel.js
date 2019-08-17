'use strict';

(function ($) {
	"use strict";

	var $wrap = $('#js__jupiterx-cp-wrap');

	// prevent window to be closed if there is a pending operation
	var requestsPending = false;
	window.onbeforeunload = function (event) {
		var s;
		event = event || window.event;
		if (requestsPending > 0) {
			event.returnValue = s;
			return s;
		}
	};

	/**
  * Check if current browser is IE or Edge.
  *
  * Unfortunately, window.Jupiter is not available in Control Panel page.
  * We need to create a simple function to check current browser is
  * IE or Edge since EventSource doesn't support them.
  *
  * @since 6.0.3
  *
  * @return {Boolean} True if it's IE or Edge.
  */
	var isEdgeIE = function isEdgeIE() {
		var userAgent = navigator.userAgent;
		var result = userAgent.match(/Edge|MSIE|Trident/);

		// Return the status.
		if (result) {
			return true;
		}

		return false;
	};

	var jupiterx_load_bootstrap_scripts = function jupiterx_load_bootstrap_scripts() {
		$('[data-toggle="tooltip"]').tooltip();

		$('[data-toggle="popover"]').click(function (e) {
			e.preventDefault();
		});

		$('[data-toggle="popover"]').popover({
			trigger: 'hover',
			container: '.jupiterx'
		});
	};

	// Load scripts on load
	jupiterx_register_api_key();
	jupiterx_get_system_report();
	jupiterx_load_bootstrap_scripts();

	// Trigger on pane ajax load
	$(window).on('control_panel_pane_loaded', jupiterx_get_system_report);
	$(window).on('control_panel_pane_loaded', jupiterx_register_api_key);
	$(window).on('control_panel_pane_loaded', jupiterx_load_bootstrap_scripts);

	/* Control Panel > pane loading via ajax functionality
  *******************************************************/
	var control_panel_panes = {

		trigger_pane: function trigger_pane(evt) {
			evt.preventDefault();
			var $this = $(this);
			var hash = $this.attr('href');
			window.location.hash = hash;
			var pane_slug = hash.substring(1, hash.length);

			control_panel_panes.get_pane(hash, pane_slug);
		},
		get_pane: function get_pane(hash, pane_name) {
			var $panes = $('#js__jupiterx-cp-panes');
			$panes.addClass('loading-pane');
			var data = {
				action: 'jupiterx_cp_load_pane_action',
				slug: pane_name
			};
			$.post(ajaxurl, data, function (response) {
				$panes.empty();
				$panes.append(response.data);
				$panes.removeClass('loading-pane');
				$('.jupiterx-cp-sidebar-list-items').removeClass('jupiterx-is-active');
				$('[href=' + hash + ']').parent().addClass('jupiterx-is-active');
				$(window).trigger('control_panel_pane_loaded');
			});
		},

		get_pane_on_load: function get_pane_on_load() {
			var hash = window.location.hash;
			if (hash.length > 0) {
				var pane_slug = hash.substring(1, hash.length);
				control_panel_panes.get_pane(hash, pane_slug);
			}
			return;
		},

		call_to_register: function call_to_register() {
			if ($('.jupiterx-call-to-register-product').length > 0) {
				window.location = window.location.href.split('#')[0];
			}
		}

	};

	$(window).on('control_panel_pane_loaded', control_panel_panes.call_to_register);
	$(document).ready(control_panel_panes.get_pane_on_load);

	function jupiterx_reinit_events() {
		$('#js__jupiterx-cp-wrap:not(.jupiterx-call-to-register-product) .js__cp-sidebar-link').off().on('click', control_panel_panes.trigger_pane);
	}
	jupiterx_reinit_events();
	$(window).on('control_panel_pane_loaded', jupiterx_reinit_events);

	/***************/

	/* Control Panel > Register API key
  *******************************************************/

	function jupiterx_register_api_key() {
		$('.jupiterx-setup-wizard-hide-notice').on('click', function (event) {
			event.preventDefault();

			$(this).attr('disabled', 'disabled');

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'jupiterx_setup_wizard_hide_notice'
				},
				beforeSend: function beforeSend() {
					$('.jupiterx-setup-wizard-message').fadeOut(400);
				}
			});
		});
		$('#js__regiser-api-key-btn').on('click', function (e) {
			e.preventDefault();
			var $api_key = $('#jupiterx-cp-register-api-input').val();
			if ($api_key.length === 0) return false;
			jupiterx_modal({
				title: jupiterx_cp_textdomain.registering_theme,
				text: jupiterx_cp_textdomain.wait_for_api_key_registered,
				type: '',
				cancelButtonText: jupiterx_cp_textdomain.discard,
				showCancelButton: true,
				showConfirmButton: false,
				showCloseButton: false,
				showLearnmoreButton: false,
				progress: '100%',
				showProgress: true,
				indefiniteProgress: true
			});
			var data = {
				action: 'jupiterx_cp_register_revoke_api_action',
				method: 'register',
				api_key: $api_key,
				security: $('#security').val()
			};
			$.post(ajaxurl, data, function (response) {
				var response = JSON.parse(response);

				if (response.status === true) {
					jupiterx_modal({
						title: jupiterx_cp_textdomain.thanks_registering,
						text: response.message,
						type: 'success',
						showCancelButton: false,
						showConfirmButton: true,
						showCloseButton: false,
						showLearnmoreButton: false,
						showProgress: false,
						indefiniteProgress: true,
						closeOnOutsideClick: false,
						closeOnConfirm: false,
						onConfirm: function onConfirm() {
							window.location.reload();
						}
					});

					$('.jupiterx-wrap').removeClass('jupiterx-call-to-register-product');
					$('.get-api-key-form').addClass('d-none');
					$('.remove-api-key-form').removeClass('d-none');

					jupiterx_reinit_events();
				} else {
					jupiterx_modal({
						title: jupiterx_cp_textdomain.registeration_unsuccessful,
						text: response.message,
						type: 'error',
						showCancelButton: false,
						showConfirmButton: true,
						showCloseButton: false,
						showLearnmoreButton: false,
						//learnmoreButton: '#',
						showProgress: false,
						onConfirm: function onConfirm() {
							$('#jupiterx-cp-register-api-input').val('');
						}
					});
				}
			});
		});
		$('#js__revoke-api-key-btn').on('click', function (e) {
			e.preventDefault();
			jupiterx_modal({
				title: jupiterx_cp_textdomain.revoke_API_key,
				text: jupiterx_cp_textdomain.you_are_about_to_remove_API_key,
				type: 'warning',
				showCancelButton: true,
				showConfirmButton: true,
				showLearnmoreButton: false,
				confirmButtonText: jupiterx_cp_textdomain.ok,
				cancelButtonText: jupiterx_cp_textdomain.cancel,
				closeOnConfirm: false,
				onConfirm: function onConfirm() {
					var data = {
						action: 'jupiterx_cp_register_revoke_api_action',
						method: 'revoke',
						security: $('#security').val()
					};
					$.post(ajaxurl, data, function (response) {
						var response = JSON.parse(response);
						if (response.status === true) {
							window.location.reload();
						}
					});
				}
			});
		});
	}
	/***************/

	var templates = {

		template_pre_request: 9,
		server_response_status: false,
		install_types: ['preparation', 'backup_db', 'backup_media_records', 'reset_db', 'upload', 'unzip', 'validate', 'install_plugins', 'activate_plugins', 'theme_content', 'setup_pages', 'plugins_content', 'settings', 'menu_locations', 'theme_widget', 'restore_media_records', 'finalize'],
		template_id: null,
		template_name: null,
		import_media: false,
		progress_bar_html: '',

		init: function init() {
			if ($('#jupiterx-cp-templates').length == 0) return;
			templates.get_installed_template();
			templates.get_template_list();
			templates.get_template_category_list();
			templates.template_category_dropdown();
			$(window).scroll(templates.throttle(templates.infinite_load_templates, 200));
			$(document).on('click', '#js__restore-template-btn', templates.get_template_restore_init);
			$(document).on('click', '#js__cp_template_uninstall', templates.uninstall_template_trigger);
			$(document).on('change', '#js__templates-category-filter', templates.template_category_filter);
			$('#js__template-search').on('keyup', _.debounce(templates.template_search, 800));
			$(document).on('click', '.js__cp_template_install', templates.template_install_init);
			$(document).on('click', '.jupiterx-download-psd', templates.download_template_psd);
		},

		download_template_psd: function download_template_psd(e) {
			e.preventDefault();
			var template_name = $(this).attr("data-slug");
			var req_data = {
				action: 'abb_get_template_psd_link',
				template_name: template_name
			};

			jQuery.post(ajaxurl, req_data, function (response) {
				if (response.status == true) {
					top.location.href = response.data.psd_link;
				} else {
					console.log(response);
					swal("Oops...", response.message, "error");
				}
			});
		},

		get_template_list_html: function get_template_list_html(data) {

			// ES6 Patching - Setting default value for "installed" argument
			var installed = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

			// Sanitize template names for URLs
			var installed_item_class = '',
			    slug = '';

			if (data.slug == 'jupiterx-default-template') {
				slug = '';
			} else {
				slug = data.slug + '/';
			}

			var template_slug = data.slug;
			var template_name = data.name;

			if (installed == false) {
				var importBtnAttrs = data.free_template === '1' ? 'class="btn btn-primary mr-2 jupiterx-cp-template-item-btn js__cp_template_install" href="#" data-title="' + template_name.replace(' Jupiterx', '') + '" data-name="' + data.name + '" data-slug="' + data.slug + '" data-id="' + data.id + '"' : 'class="btn btn-primary mr-2 jupiterx-cp-template-item-btn jupiterx-upgrade-modal-trigger" href="#" data-upgrade-link="https://themeforest.net/item/jupiter-multipurpose-responsive-theme/5177775?ref=artbees&utm_source=TemplateSectionImportButton&utm_medium=AdminUpgradePopup&utm_campaign=FreeJupiterXAdminUpgradeCampaign"';
				var btn = '<a ' + importBtnAttrs + '>' + jupiterx_cp_textdomain.import + '</a>' + '<a class="btn btn-outline-secondary mr-2 jupiterx-cp-template-item-btn" href="http://demos.artbees.net/jupiterx/' + template_slug.replace('-jupiterx', '') + '" target="_blank">' + jupiterx_cp_textdomain.preview + '</a>';
			} else {
				var btn = '<a id="js__cp_template_uninstall" class="btn btn-outline-danger mr-2 jupiterx-cp-template-item-btn" href="#" data-title="' + template_name.replace(' Jupiterx', '') + '" data-name="' + data.name + '" data-slug="' + data.slug + '" data-id="' + data.id + '">' + jupiterx_cp_textdomain.remove + '</a>' + '<a class="btn btn-outline-secondary mr-2 jupiterx-cp-template-item-btn" href="http://demos.artbees.net/jupiterx/' + template_slug.replace('-jupiterx', '') + '" target="_blank">' + jupiterx_cp_textdomain.preview + '</a>';
			}

			// if psd file uploaded
			if (data.psd_file) {
				btn += '<a class="btn btn-outline-primary jupiterx-download-psd" data-name="' + data.name + '" href="#" data-slug="' + data.slug.replace('-jupiterx', '') + '" data-id="' + data.id + '" title="' + jupiterx_cp_textdomain.download_psd_files + '">.PSD</a>';
			}

			return '<div class="jupiterx-cp-template-item">' + '<div class="jupiterx-cp-template-item-inner jupiterx-card">' + (data.free_template !== '1' ? '<img class="jupiterx-pro-badge" src="' + jupiterxControlPanel.proBadgeUrl + '" />' : '') + '<figure class="jupiterx-cp-template-item-fig">' + '<img src="' + data.img_url + '" alt="' + data.name + '">' + '</figure>' + '<div class="jupiterx-cp-template-item-meta jupiterx-card-body">' + '<h4 class="jupiterx-cp-template-item-name text-truncate" title="' + template_name.replace(' Jupiterx', '') + '">' + template_name.replace(' Jupiterx', '') + '</h4>' + '<div class="jupiterx-cp-template-item-buttons' + (data.psd_file ? ' has-psd' : '') + '">' + btn + '</div>' + '</div>' + '</div>' + '</div>';
		},

		get_template_list: function get_template_list() {
			var from_number = Number($('.abb-template-page-load-more').data('from'));
			templates.server_response_status = true;

			var req_data = {
				action: 'abb_template_lazy_load',
				from: from_number,
				count: templates.template_pre_request
			};

			if (typeof templates.template_id !== 'undefined' && templates.template_id !== null) {
				req_data['template_category'] = templates.template_id;
			}
			if (typeof templates.template_name !== 'undefined' && templates.template_name !== null) {
				req_data['template_name'] = templates.template_name;
			}

			$('.abb-template-page-load-more').addClass('is-active');

			$.post(ajaxurl, req_data, function (response) {

				if (response.status == true) {
					var backups = response.data.backups;
					var list_of_backups = [];
					var latest_backup_file = null;
					var created_date = "";

					if (backups.hasOwnProperty('list_of_backups')) {

						list_of_backups = backups.list_of_backups;

						if (list_of_backups == null) {

							console.log("List Of Backups is NULL!");
						} else if (list_of_backups.length == 0) {

							console.log("List Of Backups is EMPTY!");
						} else {

							latest_backup_file = backups.latest_backup_file;
							created_date = latest_backup_file.created_date;
							$('#js__restore-template-wrap').addClass('is-active');
							$('#js__restore-template-btn').attr('data-content', jupiterx_cp_textdomain.template_backup_date + created_date);
						}
					}
					if (response.data.templates.length > 0) {

						$('.abb-template-page-load-more').data('from', from_number + response.data.templates.length);

						$.each(response.data.templates, function (key, val) {

							var $installed_template = $('#js__installed-template').data('installed-template');

							if ($installed_template !== val.slug) {
								$('#js__new-templates-list').append(templates.get_template_list_html(val));
							}
						});
						templates.server_response_status = false;
					}
					$('.abb-template-page-load-more').removeClass('is-active');

					initProBadgeTooltip();
				} else {
					console.log(response);
					jupiterx_modal({
						title: jupiterx_cp_textdomain.something_went_wrong,
						text: response.message,
						type: 'error',
						showCancelButton: false,
						showConfirmButton: true,
						showLearnmoreButton: false
					});
				}
			});
		},

		get_template_category_list: function get_template_category_list() {
			var empty_category_list = '<span class="dropdown-item" data-value="no-category">No category found</span>';
			var category_option_wrap = $('.js__template-category-list-wrap');
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: { action: 'abb_get_templates_categories' },
				dataType: "json",
				timeout: 60000,
				success: function success(response) {
					if (response.hasOwnProperty('status') === true) {
						if (response.status === true) {
							var category_list = '<span class="dropdown-item" data-value="all-categories">All Categories</span>';
							$.each(response.data, function (key, val) {
								category_list += '<span class="dropdown-item" data-value="' + val.id + '">' + val.name + '</span>';
							});
							category_option_wrap.html(category_list);
						} else {
							category_option_wrap.html(empty_category_list);
						}
					} else {
						category_option_wrap.html(empty_category_list);
					}
				},
				error: function error(XMLHttpRequest, textStatus, errorThrown) {
					category_option_wrap.html(empty_category_list);
					templates.request_error_handling(XMLHttpRequest, textStatus, errorThrown);
				}
			});
		},

		template_category_dropdown: function template_category_dropdown() {
			$(document).on('click', '.js__template-category-list-wrap span', function (e) {
				var $this = $(this);
				$('#js__templates-category-filter').val($this.attr('data-value')).trigger('change');
				$('#js__templates-category-filter').siblings('button').text($this.text());
			});
		},

		reset_template_list: function reset_template_list() {
			$("#js__new-templates-list").fadeOut(200, function () {
				$(this).empty().fadeIn(200);
			});
			$('.abb-template-page-load-more').data('from', 0);
		},

		template_category_filter: function template_category_filter() {
			templates.reset_template_list();
			templates.template_id = $(this).val();
			templates.get_template_list();
		},

		template_search: function template_search(e) {
			templates.reset_template_list();
			templates.template_name = $(this).val();
			templates.get_template_list();
		},

		get_installed_template: function get_installed_template() {

			// ES6 Patching - Setting default value for "slug" argument
			var id = arguments.length > 0 && arguments[1] !== undefined ? arguments[1] : false;
			var slug = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

			var installed_template_id = $('#js__installed-template').data('installed-template-id');

			if (id) {
				installed_template_id = id;
			}

			var installed_template = $('#js__installed-template').data('installed-template');

			if (slug) {
				installed_template = slug;
			}

			if (installed_template_id.length <= 0 && installed_template.length <= 0) return;

			var req_data = {
				action: 'abb_template_lazy_load',
				from: 0,
				count: 1,
				template_id: installed_template_id,
				template_name: installed_template
			};

			$.post(ajaxurl, req_data, function (response) {

				if (response.status == true) {

					if (response.data.templates.length > 0) {

						$.each(response.data.templates, function (key, val) {
							$('#js__installed-template-wrap').show();
							$('#js__installed-template').attr('data-installed-template-id', val.id).attr('data-installed-template', val.slug).empty().append(templates.get_template_list_html(val, true));
						});
					}
				}
			});
		},

		template_install_init: function template_install_init(e) {
			e.preventDefault();
			var $this = $(this);
			var $CredentialsModal = $('#request-filesystem-credentials-dialog');

			if ($CredentialsModal.length) {

				var $CredentialsData = templates.get_fs_credential_data(); // Get stored credentials data.
				if (!$CredentialsData) {
					templates.get_fs_credential_modal($CredentialsModal, $this, 'install');
				} else {
					templates.get_template_install_modal($this);
				}
			} else {
				templates.get_template_install_modal($this);
			}
		},

		get_template_install_modal: function get_template_install_modal($btn) {
			jupiterx_modal({
				title: jupiterx_cp_textdomain.important_notice,
				text: jupiterx_cp_textdomain.installing_sample_data_will_delete_all_data_on_this_website + '<br> You are about to install <strong>' + $btn.data('title') + '</strong>',
				type: 'warning',
				showCancelButton: true,
				showConfirmButton: true,
				confirmButtonText: jupiterx_cp_textdomain.yes_install,
				showCloseButton: true,
				showLearnmoreButton: false,
				onConfirm: function onConfirm() {
					jupiterx_modal({
						title: jupiterx_cp_textdomain.include_images_and_videos,
						text: templates.language(jupiterx_cp_textdomain.would_you_like_to_import_images_and_videos_as_preview, ['<a href="https://help.artbees.net/getting-started/installing-a-template" target="_blank">Learn More</a>']),
						type: 'warning',
						showCancelButton: true,
						showConfirmButton: true,
						confirmButtonText: jupiterx_cp_textdomain.do_not_include,
						cancelButtonText: jupiterx_cp_textdomain.include,
						showCloseButton: true,
						showLearnmoreButton: false,
						onConfirm: function onConfirm() {
							templates.import_media = false;
							templates.start_importing_template($btn);
						},
						onCancel: function onCancel() {
							templates.import_media = true;
							templates.start_importing_template($btn);
						}
					});
				}
			});
		},

		start_importing_template: function start_importing_template($btn) {
			var custom_html = '';
			custom_html += '<div class="jupiterx-modal-header">';
			custom_html += '<h3 class="jupiterx-modal-title">' + jupiterx_cp_textdomain.install_sample_data + '</h3>';
			custom_html += '</div>';
			custom_html += '<div class="jupiterx-modal-desc">';
			custom_html += '<ul class="jupiterx-modal-step-list">';
			custom_html += '<li id="js__cp-template-install-steps-backup">' + jupiterx_cp_textdomain.backup_reset_database + ' <span class="result-message"></span></li>';
			custom_html += '<li id="js__cp-template-install-steps-upload">' + jupiterx_cp_textdomain.downloading_sample_package_data + ' <span class="result-message"></span></li>';
			custom_html += '<li id="js__cp-template-install-steps-plugin">' + jupiterx_cp_textdomain.install_required_plugins + ' <span class="result-message"></span></li>';
			custom_html += '<li id="js__cp-template-install-steps-install">' + jupiterx_cp_textdomain.install_sample_data + ' <span class="result-message"></span></li>';
			custom_html += '</ul>';
			custom_html += '</div>';

			templates.progress_bar_html = $(custom_html);

			jupiterx_modal({
				html: templates.progress_bar_html,
				showProgress: true,
				progress: '0%',
				showCloseButton: false,
				showConfirmButton: false,
				closeOnOutsideClick: false
			});

			templates.install_template(0, $btn.data('name'), $btn.data('id'));
		},

		finalise_template_install: function finalise_template_install(index, template_name, template_id) {

			templates.get_installed_template(template_name, template_id);

			var $installed_template = $('a[data-slug="' + template_name + '"]');
			$installed_template.parents('.jupiterx-cp-template-item').hide();

			jupiterx_modal({
				title: jupiterx_cp_textdomain.hooray,
				text: jupiterx_cp_textdomain.template_installed_successfully,
				type: 'success',
				showCancelButton: false,
				showConfirmButton: true,
				showCloseButton: false,
				showLearnmoreButton: false
			});

			requestsPending = false;

			templates.create_restore_button();
		},

		install_template: function install_template(index, template_name, template_id) {

			// If no more steps, exit
			if (templates.install_types[index] == undefined) {
				templates.finalise_template_install(index, template_name, template_id);
				return false;
			}

			var $storedCredentialsData = templates.get_fs_credential_data();

			var formDataTemplateProcedure = {
				action: 'abb_install_template_procedure',
				type: templates.install_types[index],
				template_name: template_name,
				template_id: template_id,
				import_media: templates.import_media
			};

			// Inject stored FTP Credentials Data for each request.
			if ($storedCredentialsData) {
				formDataTemplateProcedure = $.extend({}, $storedCredentialsData, formDataTemplateProcedure);
			}

			requestsPending = 1;

			/**
    * Handle theme_content import with Server-Sent Event.
    *
    * If the type is theme_content import and EventSource is exist, use it.
    * ATTENTION: EventSource is not supported in IE and Edge, except we use
    * some extension library. If users are using unsupported browser, they
    * will use Ajax request instead.
    *
    * @since 6.0.3
    */
			if (formDataTemplateProcedure.type == 'theme_content' && !!window.EventSource && !isEdgeIE()) {

				console.log('EventSource is triggered. The event log will be streamed below in separated interval.');

				// Set URL param manually.
				var url = ajaxurl + '?action=abb_install_template_sse&template_name=' + template_name + '&fetch_attachments=' + templates.import_media + '&template_id=' + template_id;

				// Set error modal function.
				var errorModal = function errorModal(text) {
					// Set default error text.
					var errorText = jupiterx_cp_textdomain.something_wierd_happened_please_try_again;
					if (text) {
						errorText = text;
					}

					// Something goes wrong in server response.
					jupiterx_modal({
						title: jupiterx_cp_textdomain.something_went_wrong,
						text: text,
						type: 'error',
						showCancelButton: false,
						showConfirmButton: true,
						showLearnmoreButton: false
					});

					// Pending the installation process.
					requestsPending = false;
				};

				// Create EventSource instance.
				var evtSource = new EventSource(url);

				// Handle messages.
				evtSource.onmessage = function (message) {
					// Parse the response.
					var response = JSON.parse(message.data);

					var response_text = '';
					if (response.hasOwnProperty('message')) {
						response_text = response.message;
					}

					if (response.hasOwnProperty('error') && response.hasOwnProperty('status')) {

						// Success means the status is true and no error found.
						if (!response.error && response.status) {

							console.log('Install Template - ', templates.install_types[index], ' - Fetch media : ', templates.import_media, ' : Req data - ', template_name, ' , Response - ', response);

							// Update the progress bar in modal.
							jupiterx_modal.update({
								progress: Math.round(index * 100 / (templates.install_types.length - 1)) + '%'
							});

							// Show install template message.
							templates.show_install_template_messages(templates.install_types[index], response_text);

							// Go to the next step, from theme_content to menu_locations.
							templates.install_template(++index, template_name, template_id);

							// Close to avoid resent request after success.
							evtSource.close();
							return true;
						}
					}

					errorModal(response_text);

					// Close to avoid resent request after success.
					evtSource.close();
					return true;
				};

				// Handle error event.
				evtSource.onerror = function (e) {
					console.log('EventSource take a long time, try to separate the log.');
				};

				// Tracking and print the server log.
				evtSource.addEventListener('log', function (message) {
					console.log(message.data);
				});
			} else {

				// For other types use default Ajax request.
				$.ajax({
					type: "POST",
					url: ajaxurl,
					data: formDataTemplateProcedure,
					dataType: "json",
					success: function success(response) {

						if ('install_plugins' === templates.install_types[index] && response.hasOwnProperty('install')) {
							$.ajax({
								type: "POST",
								url: response['url'],
								data: response['install'],
								success: function success() {
									console.log('Install Template - ', templates.install_types[index], ' - Fetch media : ', templates.import_media, ' : Req data - ', template_name, ' , Response - ', response);

									if (response.hasOwnProperty('status')) {
										if (response.status == true) {
											jupiterx_modal.update({
												progress: Math.round(index * 100 / (templates.install_types.length - 1)) + '%'
											});
											templates.show_install_template_messages(templates.install_types[index], response.message);
											templates.install_template(++index, template_name, template_id);
										}
									} else {
										// Something goes wrong in server response
										jupiterx_modal({
											title: jupiterx_cp_textdomain.something_went_wrong,
											text: jupiterx_cp_textdomain.something_wierd_happened_please_try_again,
											type: 'error',
											showCancelButton: false,
											showConfirmButton: true,
											showLearnmoreButton: false
										});
										requestsPending = false;
									}
								}
							});
						} else if ('activate_plugins' === templates.install_types[index] && response.hasOwnProperty('activate')) {
							$.ajax({
								type: "POST",
								url: response['url'],
								data: response['activate'],
								success: function success() {
									console.log('Install Template - ', templates.install_types[index], ' - Fetch media : ', templates.import_media, ' : Req data - ', template_name, ' , Response - ', response);

									if (response.hasOwnProperty('status')) {
										if (response.status == true) {
											jupiterx_modal.update({
												progress: Math.round(index * 100 / (templates.install_types.length - 1)) + '%'
											});
											templates.show_install_template_messages(templates.install_types[index], response.message);
											templates.install_template(++index, template_name, template_id);
										}
									} else {
										// Something goes wrong in server response
										jupiterx_modal({
											title: jupiterx_cp_textdomain.something_went_wrong,
											text: jupiterx_cp_textdomain.something_wierd_happened_please_try_again,
											type: 'error',
											showCancelButton: false,
											showConfirmButton: true,
											showLearnmoreButton: false
										});
										requestsPending = false;
									}
								}
							});
						} else {

							console.log('Install Template - ', templates.install_types[index], ' - Fetch media : ', templates.import_media, ' : Req data - ', template_name, ' , Response - ', response);

							if (response.hasOwnProperty('status')) {
								if (response.status == true) {

									jupiterx_modal.update({
										progress: Math.round(index * 100 / (templates.install_types.length - 1)) + '%'
									});

									templates.show_install_template_messages(templates.install_types[index], response.message);

									templates.install_template(++index, template_name, template_id);
								} else {
									console.log(formDataTemplateProcedure.type);
									jupiterx_modal({
										title: jupiterx_cp_textdomain.something_went_wrong,
										text: response.message,
										type: 'error',
										showCancelButton: false,
										showConfirmButton: true,
										showLearnmoreButton: false
									});
									requestsPending = false;
								}
							} else {
								// Something goes wrong in server response
								jupiterx_modal({
									title: jupiterx_cp_textdomain.something_went_wrong,
									text: jupiterx_cp_textdomain.something_wierd_happened_please_try_again,
									type: 'error',
									showCancelButton: false,
									showConfirmButton: true,
									showLearnmoreButton: false
								});
								requestsPending = false;
							}
						}
					},
					error: function error(XMLHttpRequest, textStatus, errorThrown) {
						templates.request_error_handling(XMLHttpRequest, textStatus, errorThrown);
						requestsPending = false;
					}
				});
			}
		},

		uninstall_template_trigger: function uninstall_template_trigger(evt) {
			evt.preventDefault();

			var $btn = $(this);

			jupiterx_modal({
				title: jupiterx_cp_textdomain.important_notice,
				text: jupiterx_cp_textdomain.uninstalling_template_will_remove_all_your_contents_and_settings,
				type: 'warning',
				showCancelButton: true,
				showConfirmButton: true,
				confirmButtonText: jupiterx_cp_textdomain.yes_uninstall + $btn.data('title'),
				showCloseButton: false,
				showLearnmoreButton: false,
				onConfirm: function onConfirm() {
					templates.uninstall_template($btn.data('name'));
				}
			});
		},

		uninstall_template: function uninstall_template(template_slug) {

			jupiterx_modal({
				title: jupiterx_cp_textdomain.uninstalling_Template,
				text: jupiterx_cp_textdomain.please_wait_for_few_moments,
				type: '',
				showCancelButton: false,
				showConfirmButton: false,
				showCloseButton: false,
				showLearnmoreButton: false,
				showProgress: true,
				indefiniteProgress: true
			});

			requestsPending = 1;

			$.post(ajaxurl, {

				action: 'abb_uninstall_template'

			}).done(function (response) {

				console.log('Ajax Req : ', response);

				$('#js__installed-template-wrap').hide();

				// Reset Get Templtes
				templates.reset_template_list();
				templates.get_template_list();

				// Alert
				jupiterx_modal({
					title: jupiterx_cp_textdomain.hooray,
					text: jupiterx_cp_textdomain.template_uninstalled,
					type: 'success',
					showCancelButton: false,
					showConfirmButton: true,
					showCloseButton: false,
					showLearnmoreButton: false
				});
				requestsPending = false;
			}).fail(function (data) {
				console.log('Failed msg : ', data);
				requestsPending = false;
			});
		},

		get_template_restore_init: function get_template_restore_init() {
			var $btn = $(this);

			var $CredentialsModal = $('#request-filesystem-credentials-dialog');

			if ($CredentialsModal.length) {
				var $CredentialsData = templates.get_fs_credential_data(); // Get stored credentials data.
				if (!$CredentialsData) {
					templates.get_fs_credential_modal($CredentialsModal, $btn, 'restore');
				} else {
					templates.get_template_restore_modal($btn);
				}
			} else {
				templates.get_template_restore_modal($btn);
			}
		},

		get_template_restore_modal: function get_template_restore_modal(btn) {

			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: { action: 'abb_is_restore_db' },
				dataType: "json",

				success: function success(response) {

					var created_date = response.data.latest_backup_file.created_date;
					jupiterx_modal({
						title: jupiterx_cp_textdomain.restore_settings,
						text: "<p>" + jupiterx_cp_textdomain.you_are_trying_to_restore_your_theme_settings_to_this_date + "<strong class='jupiterx-tooltip-restore--created-date'>" + created_date + "</strong>. " + jupiterx_cp_textdomain.are_you_sure + "</p>",
						type: 'warning',
						showCancelButton: true,
						showConfirmButton: true,
						confirmButtonText: jupiterx_cp_textdomain.restore,
						showCloseButton: false,
						showLearnmoreButton: false,
						onConfirm: function onConfirm() {
							jupiterx_modal({
								title: jupiterx_cp_textdomain.restoring_database,
								text: jupiterx_cp_textdomain.please_wait_for_few_moments,
								type: '',
								showCancelButton: false,
								showConfirmButton: false,
								showCloseButton: false,
								showLearnmoreButton: false,
								progress: '100%',
								showProgress: true,
								indefiniteProgress: true
							});

							var restoreTemplateParams = {
								action: 'abb_restore_latest_db'
							};
							var $storedCredentialsData = templates.get_fs_credential_data();
							// Inject stored FTP Credentials Data for each request.
							if ($storedCredentialsData) {
								restoreTemplateParams = $.extend({}, $storedCredentialsData, restoreTemplateParams);
							}
							$.ajax({
								type: "POST",
								url: ajaxurl,
								data: restoreTemplateParams,
								dataType: "json",
								success: function success(response) {
									if (response.status) {
										jupiterx_modal({
											title: response.message,
											type: 'success',
											showCancelButton: false,
											showConfirmButton: true,
											showCloseButton: false,
											showLearnmoreButton: false,
											showProgress: false,
											indefiniteProgress: true,
											confirmButtonText: jupiterx_cp_textdomain.reload_page,
											onConfirm: function onConfirm() {
												location.reload();
											}
										});
									} else {
										jupiterx_modal({
											title: jupiterx_cp_textdomain.something_went_wrong,
											text: response.message,
											type: 'error',
											showCancelButton: false,
											showConfirmButton: true,
											showLearnmoreButton: false
										});
									}
								},
								error: function error(XMLHttpRequest, textStatus, errorThrown) {
									console.log("Fail: ", XMLHttpRequest);
								}
							});
						}
					});
				},
				error: function error(XMLHttpRequest, textStatus, errorThrown) {
					console.log("Fail: ", XMLHttpRequest);
				}
			});
		},

		create_restore_button: function create_restore_button() {
			var formDataIsRestoreDb = {
				action: 'abb_is_restore_db'
			};

			var $storedCredentialsData = templates.get_fs_credential_data();

			// Inject stored FTP Credentials Data for each request.
			if ($storedCredentialsData) {
				formDataIsRestoreDb = $.extend({}, $storedCredentialsData, formDataIsRestoreDb);
			}

			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: formDataIsRestoreDb,
				dataType: "json",
				success: function success(response) {

					var data = response.data,
					    list_of_backups = [],
					    latest_backup_file = null,
					    created_date = "",
					    $btnRestore = "";

					if (data.hasOwnProperty('list_of_backups')) {

						list_of_backups = data.list_of_backups;

						if (list_of_backups == null) {
							console.log("List Of Backups is NULL!");
						} else if (list_of_backups.length == 0) {
							console.log("List Of Backups is EMPTY!");
						} else {
							latest_backup_file = data.latest_backup_file;
							created_date = latest_backup_file.created_date;
							$('#js__backup-date').text(created_date);
							$('#js__restore-template-wrap').addClass('is-active');
							console.log("Restore Buttons Created Successfully!");
						}
					} else {
						console.log("No backup files found!");
					}
				},
				error: function error(XMLHttpRequest, textStatus, errorThrown) {
					console.log("Fail: ", XMLHttpRequest);
				}
			});
		},

		show_install_template_messages: function show_install_template_messages(type, message) {
			var backup = $('#js__cp-template-install-steps-backup .result-message');
			var upload = $('#js__cp-template-install-steps-upload .result-message');
			var plugin = $('#js__cp-template-install-steps-plugin .result-message');
			var install = $('#js__cp-template-install-steps-install .result-message');

			switch (type) {
				case 'backup_db':
					backup.text(message);
					break;
				case 'backup_media_records':
					backup.text(message);
					break;
				case 'reset_db':
					backup.text(message);
					$('#js__cp-template-install-steps-backup').addClass('jupiterx-modal-step--done');
					break;
				case 'upload':
					upload.text(message);
					break;
				case 'unzip':
					upload.text(message);
					break;
				case 'validate':
					upload.text(message);
					$('#js__cp-template-install-steps-upload').addClass('jupiterx-modal-step--done');
					break;
				case 'activate_plugins':
					plugin.html(message);
					$('#js__cp-template-install-steps-plugin').addClass('jupiterx-modal-step--done');
					break;
				case 'theme_content':
					install.text(message);
					break;
				case 'menu_locations':
					install.text(message);
					break;
				case 'setup_pages':
					install.text(message);
					break;
				case 'plugins_content':
					install.text(message);
					break;
				case 'theme_widget':
					install.text(message);
					break;
				case 'restore_media_records':
					install.text(message);
					break;
				case 'finalize':
					install.text(message);
					$('#js__cp-template-install-steps-install').addClass('jupiterx-modal-step--done');
					break;
			}
		},

		infinite_load_templates: function infinite_load_templates() {
			var hT = $('.abb-template-page-load-more');
			if (hT.length) {
				var wH = $(window).height(),
				    wS = $(window).scrollTop();

				if (wS > hT.offset().top - wH && templates.server_response_status === false) {
					templates.get_template_list();
				}
			}
		},

		throttle: function throttle(func, wait) {
			var context, args, timeout, throttling, more, result;
			var whenDone = _.debounce(function () {
				more = throttling = false;
			}, wait);
			return function () {
				context = this;args = arguments;
				var later = function later() {
					timeout = null;
					if (more) func.apply(context, args);
					whenDone();
				};
				if (!timeout) timeout = setTimeout(later, wait);
				if (throttling) {
					more = true;
				} else {
					result = func.apply(context, args);
				}
				whenDone();
				throttling = true;
				return result;
			};
		},

		store_fs_credential_data: function store_fs_credential_data($data) {

			if (!$data || !Array.isArray($data)) {
				return false;
			}

			var $storedCreds = [];

			for (var i = 0; i < $data.length; i++) {
				if ($data[i].name && $data[i].name != 'action') {
					$storedCreds.push({ name: $data[i].name, value: Base64.encode($data[i].value) });
				}
			}

			$storedCreds = JSON.stringify($storedCreds);

			if (typeof Storage !== 'undefined') {
				window.sessionStorage.setItem('XFTPCredentialsData', $storedCreds);
				return $storedCreds;
			} else if (navigator.cookieEnabled) {
				setCookie('JupiterXFTPCredentialsData', $storedCreds);
				return $storedCreds;
			}

			return false;
		},

		get_fs_credential_data: function get_fs_credential_data() {

			if (typeof Storage !== 'undefined') {
				var $data = window.sessionStorage.getItem('JupiterXFTPCredentialsData');
			} else if (navigator.cookieEnabled) {
				var $data = getCookie('JupiterXFTPCredentialsData');
			}

			if (typeof $data === 'undefined' || !$data) {
				return false;
			}

			var $storedCreds = {};

			$data = JSON.parse($data);

			for (var i = 0; i < $data.length; i++) {
				if ($data[i].name && $data[i].name != 'action') {
					$storedCreds[$data[i].name] = Base64.decode($data[i].value);
				}
			}
			return $storedCreds;
		},

		get_fs_credential_modal: function get_fs_credential_modal($dialog, $btn, $action) {

			var custom_html = '';

			var form_id = 'jupiterx-install-template-credential';

			var $dialog_content = $dialog.find('.request-filesystem-credentials-dialog-content').clone();

			$dialog_content.find('#request-filesystem-credentials-title').hide();
			$dialog_content.find('input#password, input#username, input#hostname').addClass('jupiterx-textfield');
			$dialog_content.find('#upgrade').hide();
			$dialog_content.find('.cancel-button').hide();
			$dialog_content.find('form').attr('id', form_id).append('<input type="hidden" name="action" value="abb_check_ftp_credentials">');

			custom_html += '<div class="jupiterx-modal--warning js__ftp-creds-container">';
			custom_html += '<div class="jupiterx-modal-header">';
			custom_html += '<span class="jupiterx-modal-icon"></span>';
			custom_html += '<h3 class="jupiterx-modal-title">' + $dialog_content.find('#request-filesystem-credentials-title').text() + '</h3>';
			custom_html += '</div>';
			custom_html += '<div class="jupiterx-modal-desc">' + $dialog_content.html() + '</div>';
			custom_html += '<span class="jupiterx-modal-message-box"></span>';
			custom_html += '<div class="jupiterx-modal-footer">';
			custom_html += '<div class="jupiterx-wrap jupiterx-modal-ok-btn-wrap"><input type="button" id="js__ftp-creds-submit-btn" class="jupiterx-button jupiterx-button--blue jupiterx-button--small jupiterx-modal-ok-btn" value="' + $dialog_content.find('#upgrade').val() + '"></div>';
			custom_html += '<div class="jupiterx-wrap jupiterx-modal-cancel-btn-wrap"><input type="button" class="jupiterx-button jupiterx-button--gray jupiterx-button--small jupiterx-modal-cancel-btn" value="' + $dialog_content.find('.cancel-button').text() + '"></div>';
			custom_html += '</div>';
			custom_html += '</div>';

			var fs_credential_modal = jupiterx_modal({
				html: $(custom_html),
				showCloseButton: true,
				showConfirmButton: true,
				closeOnOutsideClick: false,
				closeOnConfirm: false,
				onConfirm: function onConfirm() {
					var formData = $('#' + form_id).serializeArray();
					$.ajax(ajaxurl, {
						data: formData,
						method: 'POST',
						beforeSend: function beforeSend(jqXHR, settings) {
							$('.request-credentials-form-modal .jupiterx-modal-message-box').hide();
						},
						success: function success(response, textStatus, jqXHR) {
							if (response.status) {
								fs_credential_modal.close();
								templates.store_fs_credential_data(formData);
								if ('install' == $action) {
									templates.get_template_install_modal($btn);
								} else if ('restore' == $action) {
									templates.get_template_restore_modal($btn);
								}
							} else {
								if (response.message) {
									$('.js__ftp-creds-container .jupiterx-modal-message-box').show().text(response.message);
								} else {
									$('.js__ftp-creds-container .jupiterx-modal-message-box').show().text(jupiterx_cp_textdomain.incorrect_credentials);
								}
							}
						},
						error: function error(jqXHR, textStatus, errorThrown) {
							//swal.showInputError('error');
						}
					});
				}
			});
		},

		request_error_handling: function request_error_handling(XMLHttpRequest, textStatus, errorThrown) {

			console.log(XMLHttpRequest);

			var custom_html = '';

			jupiterx_modal({
				html: templates.progress_bar_html,
				showProgress: true,
				progress: '0%',
				showCloseButton: false,
				closeOnOutsideClick: false
			});

			if (XMLHttpRequest.readyState == 4) {
				// HTTP error (can be checked by XMLHttpRequest.status and XMLHttpRequest.statusText)
				if (XMLHttpRequest.responseText != '') {

					custom_html += '<div class="jupiterx-modal--error">';
					custom_html += '<div class="jupiterx-modal-header">';
					custom_html += '<span class="jupiterx-modal-icon"></span>';
					custom_html += '<h3 class="jupiterx-modal-title">' + jupiterx_cp_textdomain.whoops + '</h3>';
					custom_html += '</div>';
					custom_html += '<div class="jupiterx-modal-desc">' + templates.language(jupiterx_cp_textdomain.dont_panic, [XMLHttpRequest.status, '<a href="https://help.artbees.net/getting-started/installing-a-template" target="_blank">Learn More</a>']) + '</div>';
					custom_html += '<textarea readonly="readonly" onclick="this.focus();this.select()" class="jupiterx-modal-textarea">' + XMLHttpRequest.responseText + '</textarea>';
					custom_html += '</div>';

					jupiterx_modal({
						html: $(custom_html),
						showCloseButton: true,
						showConfirmButton: true,
						closeOnOutsideClick: false
					});
				} else {
					jupiterx_modal({
						title: jupiterx_cp_textdomain.something_went_wrong,
						text: templates.language(jupiterx_cp_textdomain.dont_panic, [XMLHttpRequest.status, '<a href="https://help.artbees.net/getting-started/installing-a-template" target="_blank">Learn More</a>']),
						type: 'error',
						showCancelButton: false,
						showConfirmButton: true,
						showLearnmoreButton: false
					});
				}
			} else if (XMLHttpRequest.readyState == 0) {
				// Network error (i.e. connection refused, access denied due to CORS, etc.)
				jupiterx_modal({
					title: jupiterx_cp_textdomain.something_went_wrong,
					text: jupiterx_cp_textdomain.error_in_network_please_check_your_connection_and_try_again,
					type: 'error',
					showCancelButton: false,
					showConfirmButton: true,
					showLearnmoreButton: false
				});
			} else {
				jupiterx_modal({
					title: jupiterx_cp_textdomain.something_went_wrong,
					text: jupiterx_cp_textdomain.something_wierd_happened_please_try_again,
					type: 'error',
					showCancelButton: false,
					showConfirmButton: true,
					showLearnmoreButton: false
				});
			}
		},

		language: function language(string, params) {
			if (typeof string == 'undefined' || string == '') {
				return;
			}
			var array_len = params.length;
			if (array_len < 1) {
				return string;
			}
			var indicator_len = (string.match(/{param}/g) || []).length;

			if (array_len == indicator_len) {
				$.each(params, function (key, val) {
					string = string.replace('{param}', val);
				});
				return string;
			}

			// Array len and indicator lengh is not same;
			console.log('Array len and indicator lengh is not same, Contact support with ID : (3-6H1T4I) .');
			return string;
		}

	};
	$(window).on('control_panel_pane_loaded', templates.init);
	/***************/

	/* Control Panel > Updates ( Theme )
 *******************************************************/

	var update_releases = {
		init: function init() {
			if ($('#jupiterx-cp-updates-downgrades').length == 0) return;
			console.log('updates.theme');
			$(document).on('click', '.js__cp_change_theme_version', update_releases.change_theme_version_init);
			$(document).on('click', '.release-download', update_releases.release_download);
		},

		release_download: function release_download(e) {
			e.preventDefault();
			e.stopPropagation();
			var btn = $(this);
			var status = btn.attr('status');
			if (typeof status === 'undefined') status = 'active';
			if (status === 'active') {
				var release_id = $(this).data("release-id");
				var release_package = $(this).data('release-package');
				var get_release_download_link_nonce = $(this).data("nonce");

				btn.attr("status", 'deactive');

				setTimeout(function () {
					btn.attr("status", 'active');
				}, 9000);

				jQuery.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						security: get_release_download_link_nonce,
						release_id: release_id,
						release_package: release_package,
						action: "jupiterx_get_theme_release_package_url"
					},
					success: function success(res) {
						if (res.success) {
							top.location.href = res.data;
						}
					},
					error: function error(res) {
						console.log(res);
						alert("An error occurred.");
					}
				});
			}
		},
		change_theme_version_init: function change_theme_version_init(e) {
			e.preventDefault();

			var self = $(this);
			var release_id = self.data('release-id');
			var release_version = self.data('release-version');
			var get_release_download_link_nonce = self.data('nonce');
			var feedback = self.siblings('.jupiterx-cp-update-feedback');

			jupiterx_modal({
				title: jupiterx_cp_textdomain.please_note,
				text: jupiterx_cp_textdomain.any_customisation_you_have_made_to_theme_files_will_be_lost,
				type: 'warning',
				showCancelButton: true,
				showConfirmButton: true,
				confirmButtonText: jupiterx_cp_textdomain.agree,
				cancelButtonText: jupiterx_cp_textdomain.discard,
				learnmoreTarget: '_blank',
				learnmoreLabel: 'Read More',
				learnmoreButton: 'https://intercom.help/artbees?q=Update+jupiter+x',
				showCloseButton: true,
				showLearnmoreButton: true,
				onConfirm: function onConfirm() {
					var errors = [{
						text: jupiterx_cp_textdomain.apikey_domain_match_error,
						helpLink: '<a href="https://themes.artbees.net/docs/updating-jupiter-x-theme-automatically/" target="_blank">' + jupiterx_cp_textdomain.learn_more + '</a>'
					}];

					var errorHelpLink = function errorHelpLink(text) {
						var error = _.findWhere(errors, { text: text });
						return error ? error.helpLink : '';
					};

					feedback.removeClass('d-none');
					self.addClass('disabled loading');

					wp.ajax.send('jupiterx_modify_auto_update', {
						data: {
							security: get_release_download_link_nonce,
							release_id: release_id,
							release_version: release_version
						},
						success: function success(response) {
							wp.updates.ajax('update-theme', {
								slug: "jupiterx",
								success: function success(response) {
									feedback.removeClass('text-muted').addClass('text-success').text(jupiterx_cp_textdomain.theme_update_success);
									self.removeClass('disabled loading');
									window.location.reload();
								},
								error: function error(response) {
									feedback.removeClass('text-muted').addClass('text-danger').text(jupiterx_cp_textdomain.theme_update_failed);
									self.removeClass('disabled loading');
								}
							});
						},
						error: function error(response) {
							self.removeClass('disabled loading');
							feedback.removeClass('text-muted').addClass('text-danger').html(response + ' ' + errorHelpLink(response));
						}
					});
				}
			});
		}
	};
	$(window).on('control_panel_pane_loaded', update_releases.init);

	/* Control Panel > Image sizes
 *******************************************************/

	var image_sizes = {

		init: function init() {

			$('.js__cp-clist-add-item').on('click', image_sizes.add);
			$('.js__cp-clist-edit-item').on('click', image_sizes.edit);
			$('.js__cp-clist-remove-item').on('click', image_sizes.remove);
		},

		remove: function remove(e) {
			e.preventDefault();
			var $this = $(this);
			jupiterx_modal({
				title: jupiterx_cp_textdomain.remove_image_size,
				text: jupiterx_cp_textdomain.are_you_sure_remove_image_size,
				type: 'warning',
				showCancelButton: true,
				showConfirmButton: true,
				showCloseButton: false,
				showLearnmoreButton: false,
				onConfirm: function onConfirm() {

					var $list_item = $this.closest('.jupiterx-img-size-item');
					$list_item.remove();

					$(window).trigger('control_panel_save_image_sizes');
				}
			});
		},

		add: function add(e) {

			var custom_html = '';
			custom_html += '<div class="jupiterx-modal-header">';
			custom_html += '<span class="jupiterx-modal-icon"></span>';
			custom_html += '<h3 class="jupiterx-modal-title">' + jupiterx_cp_textdomain.add_image_size + '</h3>';
			custom_html += '</div>';
			custom_html += '<div class="jupiterx-modal-desc">';
			custom_html += '<div class="form-group mb-3">';
			custom_html += '<label><strong>' + jupiterx_cp_textdomain.image_size_name + '</strong></label>';
			custom_html += '<input class="jupiterx-form-control" name="size_n" type="text" required />';
			custom_html += '</div>';
			custom_html += '<div class="form-row">';
			custom_html += '<div class="form-group col-md-6">';
			custom_html += '<label><strong>' + jupiterx_cp_textdomain.image_size_width + '</strong></label>';
			custom_html += '<input class="jupiterx-form-control" min="100" name="size_w" step="1" type="number" required />';
			custom_html += '</div>';
			custom_html += '<div class="form-group col-md-6">';
			custom_html += '<label><strong>' + jupiterx_cp_textdomain.image_size_height + '</strong></label>';
			custom_html += '<input class="jupiterx-form-control" min="100" name="size_h" id="size_h" step="1" type="number" required />';
			custom_html += '</div>';
			custom_html += '</div>';
			custom_html += '<div class="custom-control custom-checkbox form-group mb-3">';
			custom_html += '<input type="checkbox" class="custom-control-input" id="size_c" name="size_c" checked="checked">';
			custom_html += '<label class="custom-control-label" for="size_c"><strong>' + jupiterx_cp_textdomain.image_size_crop + '</strong></label>';
			custom_html += '</div>';
			custom_html += '</div>';

			var add_image_size_modal = jupiterx_modal({
				modalCustomClass: 'js__add-new-image-size',
				type: 'warning',
				html: $(custom_html),
				showCloseButton: true,
				showConfirmButton: true,
				showCancelButton: true,
				closeOnOutsideClick: true,
				closeOnConfirm: false,
				confirmButtonText: jupiterx_cp_textdomain.save,
				cancelButtonText: jupiterx_cp_textdomain.discard,
				onConfirm: function onConfirm() {
					image_sizes.apply(false, add_image_size_modal);
				}
			});
		},

		edit: function edit(e) {

			var $this = $(this);
			var $this_size_item = $this.closest('.js__cp-image-size-item');
			var $this_box = $this.closest('.jupiterx-card-body');
			var $size_name = $this_box.find('[name=size_n]').val();
			var $size_width = $this_box.find('[name=size_w]').val();
			var $size_height = $this_box.find('[name=size_h]').val();
			var $size_crop = $this_box.find('[name=size_c]').val();
			$size_crop = $size_crop == 'on' ? 'checked="checked"' : false;

			var custom_html = '';
			custom_html += '<div class="jupiterx-modal-header">';
			custom_html += '<span class="jupiterx-modal-icon"></span>';
			custom_html += '<h3 class="jupiterx-modal-title">' + jupiterx_cp_textdomain.edit_image_size + '</h3>';
			custom_html += '</div>';
			custom_html += '<div class="jupiterx-modal-desc">';
			custom_html += '<div class="form-group mb-3">';
			custom_html += '<label><strong>' + jupiterx_cp_textdomain.image_size_name + '</strong></label>';
			custom_html += '<input class="jupiterx-form-control" name="size_n" type="text" value="' + $size_name + '" required />';
			custom_html += '</div>';
			custom_html += '<div class="form-row">';
			custom_html += '<div class="form-group col-md-6">';
			custom_html += '<label><strong>' + jupiterx_cp_textdomain.image_size_width + '</strong></label>';
			custom_html += '<input class="jupiterx-form-control" min="100" name="size_w" step="1" type="number"  value="' + $size_width + '" required />';
			custom_html += '</div>';
			custom_html += '<div class="form-group col-md-6">';
			custom_html += '<label><strong>' + jupiterx_cp_textdomain.image_size_height + '</strong></label>';
			custom_html += '<input class="jupiterx-form-control" min="100" name="size_h" id="size_h" step="1" type="number"  value="' + $size_height + '" required />';
			custom_html += '</div>';
			custom_html += '</div>';
			custom_html += '<div class="custom-control custom-checkbox form-group mb-3">';
			custom_html += '<input type="checkbox" class="custom-control-input" id="size_c" name="size_c" ' + $size_crop + '>';
			custom_html += '<label class="custom-control-label" for="size_c"><strong>' + jupiterx_cp_textdomain.image_size_crop + '</strong></label>';
			custom_html += '</div>';
			custom_html += '</div>';

			var add_image_size_modal = jupiterx_modal({
				modalCustomClass: 'js__add-new-image-size',
				type: 'warning',
				html: $(custom_html),
				showCloseButton: true,
				showConfirmButton: true,
				showCancelButton: true,
				closeOnOutsideClick: true,
				closeOnConfirm: false,
				confirmButtonText: jupiterx_cp_textdomain.save,
				cancelButtonText: jupiterx_cp_textdomain.discard,
				onConfirm: function onConfirm() {
					image_sizes.apply($this_size_item, add_image_size_modal);
				}
			});
		},

		apply: function apply($this_size_item, add_image_size_modal) {

			var custom_html = '';
			var $modal = $('.js__add-new-image-size');
			var $size_name = $modal.find('[name=size_n]');
			var $size_width = $modal.find('[name=size_w]');
			var $size_height = $modal.find('[name=size_h]');
			var $size_name_val = $modal.find('[name=size_n]').val();
			var $size_width_val = $modal.find('[name=size_w]').val();
			var $size_height_val = $modal.find('[name=size_h]').val();
			var $size_crop = $modal.find('[name=size_c]:checked').val();
			$size_crop = $size_crop == 'on' ? 'on' : 'off';
			var crop_class = $size_crop == 'on' ? 'status-true' : 'status-false';

			if ($size_name_val == '') {
				$size_name.addClass('is-invalid');
				return false;
			} else {
				$size_name.removeClass('is-invalid');
			}
			if ($size_width_val == '') {
				$size_width.addClass('is-invalid');
				return false;
			} else {
				$size_width.removeClass('is-invalid');
			}
			if ($size_height_val == '') {
				$size_height.addClass('is-invalid');
				return false;
			} else {
				$size_height.removeClass('is-invalid');
			}

			custom_html += '<div class="jupiterx-img-size-item js__cp-image-size-item">';
			custom_html += '<div class="jupiterx-img-size-item-inner jupiterx-card">';
			custom_html += '<div class="jupiterx-card-body fetch-input-data">';
			custom_html += '<div class="js__size-name mb-3"><strong>' + jupiterx_cp_textdomain.size_name + ':</strong> ' + $size_name_val + '</div>';
			custom_html += '<div class="js__size-dimension mb-3"><strong>' + jupiterx_cp_textdomain.image_size + ':</strong> ' + $size_width_val + 'px ' + $size_height_val + 'px</div>';
			custom_html += '<div class="js__size-crop mb-3"><strong>' + jupiterx_cp_textdomain.crop + ':</strong><span class="status-state ' + crop_class + '"></span></div>';
			custom_html += '<button type="button" class="btn btn-outline-success js__cp-clist-edit-item mr-1">' + jupiterx_cp_textdomain.edit + '</button>';
			custom_html += '<button type="button" class="btn btn-outline-danger js__cp-clist-remove-item">' + jupiterx_cp_textdomain.remove + '</button>';
			custom_html += '<input name="size_n" type="hidden" value="' + $size_name_val + '" />';
			custom_html += '<input name="size_w" type="hidden" value="' + $size_width_val + '" />';
			custom_html += '<input name="size_h" type="hidden" value="' + $size_height_val + '" />';
			custom_html += '<input name="size_c" type="hidden" value="' + $size_crop + '" />';
			custom_html += '</div>';
			custom_html += '</div>';

			if ($this_size_item.length > 0) {
				$this_size_item.after(custom_html);
				$this_size_item.remove();
			} else {
				$('.js__jupiterx-img-size-list').append(custom_html);
			}
			image_sizes.init();

			add_image_size_modal.close();
			image_sizes.save();
		},

		save: function save(e) {

			var $container = $('.js__jupiterx-img-size-list'),
			    serialised = [];

			$container.find('.js__cp-image-size-item').each(function () {
				serialised.push($(this).find('.fetch-input-data input').serialize());
			});
			requestsPending = 1;

			var saving_image_sizes = jupiterx_modal({
				title: jupiterx_cp_textdomain.saving_image_size,
				text: jupiterx_cp_textdomain.wait_for_image_size_update,
				type: '',
				showCancelButton: false,
				showConfirmButton: false,
				showCloseButton: false,
				showLearnmoreButton: false,
				progress: '100%',
				showProgress: true,
				indefiniteProgress: true
			});

			jQuery.ajax({
				url: ajaxurl,
				type: "POST",
				data: {
					action: 'jupiterx_save_image_sizes',
					options: serialised,
					security: $('#security').val()
				},
				success: function success(res) {
					saving_image_sizes.close();
					if (res != 1) {
						jupiterx_modal({
							title: jupiterx_cp_textdomain.something_went_wrong,
							text: jupiterx_cp_textdomain.image_sizes_could_not_be_stored,
							type: 'error',
							showCancelButton: false,
							showConfirmButton: true,
							showCloseButton: false,
							showLearnmoreButton: false
						});
					}
					requestsPending = false;
				},
				error: function error(res) {
					console.log(res);
					requestsPending = false;
					jupiterx_modal({
						type: 'error',
						title: jupiterx_cp_textdomain.error,
						text: res + ' ' + jupiterx_cp_textdomain.issue_persists,
						showCancelButton: false,
						showConfirmButton: true,
						showCloseButton: false,
						showLearnmoreButton: false,
						showProgress: false,
						closeOnConfirm: false,
						confirmButtonText: jupiterx_cp_textdomain.try_again,
						closeOnOutsideClick: false,
						onConfirm: function onConfirm() {
							window.location.reload();
						}
					});
				}
			});
		}
	};

	$(window).on('control_panel_save_image_sizes', image_sizes.save);
	$(window).on('control_panel_pane_loaded', image_sizes.init);

	/***************/

	/* Control Panel > System status page get report functionality
  *******************************************************/
	$(window).on('control_panel_pane_loaded', function () {
		$('#jupiterx-mods-cleanup').on('click', function (e) {
			e.preventDefault();

			var $this = $(this);

			$this.replaceWith('<span class="status-state"><span class="jupiterx-cleanup-spinner spinner is-active"></span></span>');

			var data = {
				action: 'jupiterx_cp_cleanup_mods',
				nonce: $(this).attr('data-nonce')
			};

			jupiterxCleanupMods(data);
		});
	});

	function jupiterxCleanupMods(data) {
		$.post(ajaxurl, data, function (res) {
			if (res.success) {
				jupiterxCleanupMods(data);
			} else {
				$('.jupiterx-cleanup-spinner').replaceWith('<span class="status-state"><span class="status-invisible">True</span><span class="status-state status-true"></span></span>');
			}
		});
	}

	function jupiterx_get_system_report() {
		$('.jupiterx-button--get-system-report').click(function () {
			var report = '';
			$('#jupiterx-cp-system-status thead, #jupiterx-cp-system-status tbody').each(function () {
				if ($(this).is('thead')) {
					var label = $(this).find('th:eq(0)').data('export-label') || $(this).text();
					report = report + "\n### " + $.trim(label) + " ###\n\n";
				} else {
					$('tr', $(this)).each(function () {
						var label = $(this).find('td:eq(0)').data('export-label') || $(this).find('td:eq(0)').text();
						var the_name = $.trim(label).replace(/(<([^>]+)>)/ig, ''); // Remove HTML
						var the_value = $.trim($(this).find('td:eq(2)').text());
						var value_array = the_value.split(', ');
						if (value_array.length > 1) {
							var output = '';
							var temp_line = '';
							$.each(value_array, function (key, line) {
								temp_line = temp_line + line + '\n';
							});
							the_value = temp_line;
						}
						report = report + '' + the_name + ': ' + the_value + "\n";
					});
				}
			});
			try {
				$("#jupiterx-textarea--get-system-report").slideDown();
				$("#jupiterx-textarea--get-system-report textarea").val(report).focus().select();
				return false;
			} catch (e) {
				console.log(e);
			}
			return false;
		});

		$('[data-jupiterx-ajax]').each(function () {
			var $self = $(this);
			var type = $self.data('jupiterxAjax');
			var feedbackIcon = $self.find('.status-state');
			var feedbackText = $self.find('.status-text');

			wp.ajax.send('jupiterx_cp_system_status', {
				data: {
					nonce: jupiterxControlPanel.nonce,
					type: type
				},
				success: function success(response) {
					feedbackIcon.html('<span class="status-invisible">True</span><span class="status-state status-true"></span>');
				},
				error: function error(response) {
					feedbackIcon.html('<span class="status-invisible">False</span><span class="status-state status-false"></span>');
					feedbackText.html(response);
				}
			});
		});
	}
	/***************/

	/* Control Panel > Create base64 character set
  *******************************************************/
	var Base64 = {
		_keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
		encode: function encode(e) {
			var t = "";
			var n, r, i, s, o, u, a;
			var f = 0;
			e = Base64._utf8_encode(e);
			while (f < e.length) {
				n = e.charCodeAt(f++);
				r = e.charCodeAt(f++);
				i = e.charCodeAt(f++);
				s = n >> 2;
				o = (n & 3) << 4 | r >> 4;
				u = (r & 15) << 2 | i >> 6;
				a = i & 63;
				if (isNaN(r)) {
					u = a = 64;
				} else if (isNaN(i)) {
					a = 64;
				}
				t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a);
			}
			return t;
		},
		decode: function decode(e) {
			var t = "";
			var n, r, i;
			var s, o, u, a;
			var f = 0;
			e = e.replace(/[^A-Za-z0-9+/=]/g, "");
			while (f < e.length) {
				s = this._keyStr.indexOf(e.charAt(f++));
				o = this._keyStr.indexOf(e.charAt(f++));
				u = this._keyStr.indexOf(e.charAt(f++));
				a = this._keyStr.indexOf(e.charAt(f++));
				n = s << 2 | o >> 4;
				r = (o & 15) << 4 | u >> 2;
				i = (u & 3) << 6 | a;
				t = t + String.fromCharCode(n);
				if (u != 64) {
					t = t + String.fromCharCode(r);
				}
				if (a != 64) {
					t = t + String.fromCharCode(i);
				}
			}
			t = Base64._utf8_decode(t);
			return t;
		},
		_utf8_encode: function _utf8_encode(e) {
			e = e.replace(/rn/g, "n");
			var t = "";
			for (var n = 0; n < e.length; n++) {
				var r = e.charCodeAt(n);
				if (r < 128) {
					t += String.fromCharCode(r);
				} else if (r > 127 && r < 2048) {
					t += String.fromCharCode(r >> 6 | 192);
					t += String.fromCharCode(r & 63 | 128);
				} else {
					t += String.fromCharCode(r >> 12 | 224);
					t += String.fromCharCode(r >> 6 & 63 | 128);
					t += String.fromCharCode(r & 63 | 128);
				}
			}
			return t;
		},
		_utf8_decode: function _utf8_decode(e) {
			var t = "";
			var n = 0;
			var r = 0;
			var c1 = 0;
			var c2 = 0;
			while (n < e.length) {
				r = e.charCodeAt(n);
				if (r < 128) {
					t += String.fromCharCode(r);
					n++;
				} else if (r > 191 && r < 224) {
					c2 = e.charCodeAt(n + 1);
					t += String.fromCharCode((r & 31) << 6 | c2 & 63);
					n += 2;
				} else {
					c2 = e.charCodeAt(n + 1);
					c3 = e.charCodeAt(n + 2);
					t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
					n += 3;
				}
			}
			return t;
		}
	};

	/**
  * Export & Import API.
  *
  * @since 6.0.3
  * @type {Object}
  */
	var exportImport = {
		steps: [],
		modal: '',
		data: {},

		init: function init() {
			$('.jupiterx-cp-export-form').on('submit', exportImport.export);
			$('.jupiterx-cp-import-btn').on('click', exportImport.import);
			$('.jupiterx-cp-import-upload-btn').on('click', exportImport.upload);
		},

		export: function _export(event) {
			event.preventDefault();
			exportImport.steps = [];
			exportImport.modal = '';
			exportImport.cancel = '';
			var options = $(this).serializeArray();
			exportImport.data.filename = options[0].value;

			// Remove filename from options.
			options = _.reject(options, function (option) {
				return option.name == 'filename';
			});

			// Convert options to a flat array.
			if (!exportImport._mapOptions(options)) {
				return;
			}

			// Open the modal.
			exportImport.modal = jupiterx_modal({
				type: false,
				title: jupiterx_cp_textdomain.exporting + ' <span class="cp-export-step">' + exportImport.steps[1] + '</span>...',
				text: jupiterx_cp_textdomain.export_waiting,
				showCancelButton: true,
				showConfirmButton: false,
				showCloseButton: false,
				showLearnmoreButton: false,
				showProgress: true,
				progress: '100%',
				indefiniteProgress: true,
				cancelButtonText: jupiterx_cp_textdomain.discard,
				closeOnConfirm: false,
				closeOnOutsideClick: false,
				onCancel: function onCancel() {
					exportImport.steps = [];
					exportImport.cancel = true;
					exportImport.send('Export', 'Discard');
					exportImport.modal.close();
				}
			});

			// Init the first step.
			exportImport.send('Export', _.first(exportImport.steps));
		},

		import: function _import(event) {
			event.preventDefault();
			exportImport.steps = [];
			exportImport.modal = '';
			exportImport.cancel = '';
			var attachment_id = $('.jupiterx-cp-import-wrap .jupiterx-form-control').data('id');

			// Return false if no package is selected.
			if ('undefined' === typeof attachment_id) {
				return false;
			}

			exportImport.attachment_id = attachment_id;

			exportImport.modal = jupiterx_modal({
				type: false,
				title: 'Import',
				text: jupiterx_cp_textdomain.import_select_options + '\
				<form class="jupiterx-cp-import-form">\
					<label>\
						<input type="checkbox" name="check" value="Content" checked>' + jupiterx_cp_textdomain.site_content + '\
					</label>\
					<label>\
						<input type="checkbox" name="check" value="Widgets" checked>' + jupiterx_cp_textdomain.widgets + '\
					</label>\
					<label>\
						<input type="checkbox" name="check" value="Settings" checked>' + jupiterx_cp_textdomain.settings + '\
					</label>\
				</form>',
				showCancelButton: false,
				showConfirmButton: true,
				showCloseButton: true,
				showLearnmoreButton: false,
				showProgress: false,
				closeOnConfirm: false,
				confirmButtonText: jupiterx_cp_textdomain.import,
				onConfirm: function onConfirm() {
					var options = $('.jupiterx-cp-import-form').serializeArray();

					// Convert options to a flat array.
					if (!exportImport._mapOptions(options)) {
						return;
					}

					jupiterx_modal({
						type: false,
						title: jupiterx_cp_textdomain.importing + ' <span class="cp-export-step">' + exportImport.steps[1] + '</span>...',
						text: jupiterx_cp_textdomain.import_waiting,
						showCancelButton: true,
						showConfirmButton: false,
						showCloseButton: false,
						showLearnmoreButton: false,
						progress: '100%',
						showProgress: true,
						indefiniteProgress: true,
						cancelButtonText: jupiterx_cp_textdomain.discard,
						closeOnOutsideClick: false,
						closeOnConfirm: false,
						onCancel: function onCancel() {
							exportImport.steps = [];
							exportImport.cancel = true;
							exportImport.send('Import', 'Discard');
							exportImport.modal.close();
						}
					});

					// Init the first step.
					exportImport.send('Import', _.first(exportImport.steps));
				}
			});
		},

		send: function send(type, step) {
			wp.ajax.send('jupiterx_cp_export_import', {
				data: {
					nonce: jupiterxControlPanel.nonce,
					type: type,
					step: step,
					attachment_id: exportImport.attachment_id,
					data: exportImport.data
				},
				success: function success(response) {
					exportImport.steps = _.without(exportImport.steps, response.step);
					var firstStep = _.first(exportImport.steps);

					// Open the download modal.
					if (!exportImport.steps.length) {
						if (true === exportImport.cancel) {
							return;
						}
						var confirmButtonText = 'Export' === type ? jupiterx_cp_textdomain.download : jupiterx_cp_textdomain.close;
						jupiterx_modal({
							type: 'success',
							title: type + ' ' + jupiterx_cp_textdomain.done,
							text: type + ' ' + jupiterx_cp_textdomain.successfully_finished,
							showCancelButton: false,
							showConfirmButton: true,
							showCloseButton: false,
							showLearnmoreButton: false,
							showProgress: false,
							closeOnConfirm: false,
							confirmButtonText: confirmButtonText,
							onConfirm: function onConfirm() {
								if ('Export' === type) {
									window.location.href = response.download_url;
								}
								exportImport.modal.close();
							}
						});
						return;
					}

					// Update title in modal except Start one.
					if (response.step != 'Start') {
						$('.cp-export-step').text(response.step);
					}

					// Init the next step.
					exportImport.send(type, firstStep);
				},
				error: function error(response) {

					console.log(response);

					jupiterx_modal({
						type: 'error',
						title: jupiterx_cp_textdomain.error,
						text: response + ' ' + jupiterx_cp_textdomain.issue_persists,
						showCancelButton: false,
						showConfirmButton: true,
						showCloseButton: false,
						showLearnmoreButton: false,
						showProgress: false,
						closeOnConfirm: false,
						confirmButtonText: jupiterx_cp_textdomain.try_again,
						onConfirm: function onConfirm() {
							window.location.reload();
							exportImport.modal.close();
						}
					});
				}
			});
		},

		upload: function upload(event) {
			event.preventDefault();
			var frame;
			var $input = $(event.target).parents('.jupiterx-upload-wrap').find('input');

			if (frame) {
				frame.open();
				return;
			}

			frame = wp.media({
				title: jupiterx_cp_textdomain.select_zip_file,
				button: {
					text: jupiterx_cp_textdomain.select
				},
				multiple: false // Set to true to allow multiple files to be selected
			});

			// When an image is selected in the media frame...
			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();

				$input.attr('data-id', attachment.id);
				$input.val(attachment.url);
			});

			frame.open();
		},

		_mapOptions: function _mapOptions(options) {
			// Convert options to a flat array.
			_.map(options, function (option) {
				return exportImport.steps.push(option.value);
			});

			// Return false if no option is selected.
			if (!exportImport.steps.length) {
				return false;
			}

			exportImport.steps.unshift('Start');
			exportImport.steps.push('End');

			return true;
		}

		// Initialize Export & Import after pane load.
	};$(window).on('control_panel_pane_loaded', exportImport.init);

	/**
  * Settings API.
  *
  * @since 1.0.0
  * @type {Object}
  */
	var settings = {

		init: function init() {
			$('.jupiterx-cp-settings-flush').on('click', settings.flush);
			$('.jupiterx-cp-settings-form').on('submit', settings.save);

			initProBadgeTooltip();
		},

		flush: function flush(event) {
			settings.send('flush');
		},

		save: function save(event) {
			event.preventDefault();
			var fields = {};

			$.map($(this).serializeArray(), function (v) {
				fields[v.name] = v.value;
			});

			settings.send('save', fields);
		},

		send: function send(type) {
			var fields = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

			var $feedback = $(document).find('.jupiterx-cp-settings-' + type + '-feedback');
			var $originalText = $feedback.text();

			$feedback.removeClass('d-none');

			wp.ajax.send('jupiterx_cp_settings', {
				data: {
					nonce: jupiterxControlPanel.nonce,
					type: type,
					fields: fields
				},
				success: function success(response) {
					$feedback.removeClass('text-muted').addClass('text-success').text(response);

					setTimeout(function () {
						$feedback.addClass('d-none text-muted').text($originalText);
					}, 3000);
				},
				error: function error(response) {
					$feedback.removeClass('text-muted').addClass('text-danger').text(response);

					setTimeout(function () {
						$feedback.addClass('d-none text-muted').text($originalText);
					}, 3000);
				}
			});
		}

		// Initialize Settings after pane load.
	};$(window).on('control_panel_pane_loaded', settings.init);

	// Initialize pro badge tooltip.
	function initProBadgeTooltip() {
		$('.jupiterx-pro-badge').tooltip({
			title: jupiterx_cp_textdomain.pro_badge_tooltip_title,
			trigger: 'hover',
			container: '.jupiterx-wrap',
			template: '<div class="tooltip jupiterx-pro-badge-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
		});
	}
})(jQuery);