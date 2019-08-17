<?php
/**
 * List of all texts in control panel for translation
 *
 * @package Control Panel
 */

/**
 * This function will be hooked into wp_localize_script in admin/general/enqueue-assets.php
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
function jupiterx_adminpanel_textdomain() {
	return array(
		'theme_update_success'                                             => __( 'Theme Updated successfully.', 'jupiterx' ),
		'theme_update_failed'                                              => __( 'Theme update failed. Please try again.', 'jupiterx' ),
		'network_active'                                                   => __( 'Network Active', 'jupiterx' ),
		'agree'                                                            => __( 'Agree', 'jupiterx' ),
		'please_note'                                                      => __( 'Please Note:', 'jupiterx' ),
		'any_customisation_you_have_made_to_theme_files_will_be_lost'      => __( 'Any customisation you have made to theme files will be lost.', 'jupiterx' ),

		'restore_settings'                                                 => __( 'Restore Settings', 'jupiterx' ),
		'installing_sample_data_will_delete_all_data_on_this_website'      => __( 'Installing a new template will remove all current data on your website. Do you want to proceed?', 'jupiterx' ),
		'you_are_trying_to_restore_your_theme_settings_to_this_date'       => __( 'You are trying to Restore your database to this date: ', 'jupiterx' ),
		'yes_install'                                                      => __( 'Yes', 'jupiterx' ),
		'restore'                                                          => __( 'Restore', 'jupiterx' ),
		'reload_page'                                                      => __( 'Reload Page', 'jupiterx' ),
		'uninstalling_template_will_remove_all_your_contents_and_settings' => __( 'Uninstalling template will remove all you current data and settings. Do you want to proceed?', 'jupiterx' ),
		'yes_uninstall'                                                    => __( 'Yes, uninstall ', 'jupiterx' ),
		'template_uninstalled'                                             => __( 'Template uninstalled', 'jupiterx' ),
		'hooray'                                                           => __( 'All Done!', 'jupiterx' ),
		'template_installed_successfully'                                  => __( 'Template is successfully installed.', 'jupiterx' ),

		'preview'                                                          => __( 'Preview', 'jupiterx' ),
		'import'                                                           => __( 'Import', 'jupiterx' ),
		'downloading_sample_package_data'                                  => __( 'Downloading package', 'jupiterx' ),
		'backup_reset_database'                                            => __( 'Backup Database', 'jupiterx' ),
		'install_required_plugins'                                         => __( 'Install required plugins', 'jupiterx' ),
		'install_sample_data'                                              => __( 'Installing in progress...', 'jupiterx' ),
		'installed'                                                        => __( 'Installed', 'jupiterx' ),
		'include_images_and_videos'                                        => __( 'Include Images and Videos?', 'jupiterx' ),
		'using_ie_edge_not_support'                                        => __( 'Your browser is not supported.', 'jupiterx' ),
		'would_you_like_to_import_images_and_videos_as_preview'            => __( 'Would you like to import images and videos as preview? <br> Notice that all images are <strong>strictly copyrighted</strong> and you need to acquire the license in case you want to use them on your project. {param}', 'jupiterx' ),
		'recommend_to_use_other_browsers'                                  => __( 'Jupiter X detected that you are using IE or EDGE browser that will prevent you from successfully importing media. Please use other modern browsers. {param}', 'jupiterx' ),
		'insufficient_system_resource'                                     => __( 'Insufficient system resource', 'jupiterx' ),
		'insufficient_system_resource_notes'                               => __( 'Your system resource is not enough. Please contact our support or {param} here.', 'jupiterx' ),
		'continue_without_media'                                           => __( 'Continue without Media', 'jupiterx' ),
		'do_not_include'                                                   => __( 'Do not Include', 'jupiterx' ),
		'include'                                                          => __( 'Include', 'jupiterx' ),
		'whoops'                                                           => __( 'Whoops!', 'jupiterx' ),
		'dont_panic'                                                       => __( 'There seems to be an inconsistency in installing procedure. Don\'t panic though here we\'ve listed some possible solutions for you to get back on track.<br>( Warning number : {param}) {param} ', 'jupiterx' ),
		'error_in_network_please_check_your_connection_and_try_again'      => __( 'Error in network , Please check your connection and try again', 'jupiterx' ),
		'incorrect_credentials'                                            => __( 'There was an error connecting to the server, Please verify the settings are correct.', 'jupiterx' ),
		'restore_from_last_backup'                                         => __( 'Restore from Last Backup', 'jupiterx' ),
		'restore_theme_settings_to_this_version'                           => __( 'Restore theme settings to this version', 'jupiterx' ),
		'are_you_sure'                                                     => __( 'Are you sure?', 'jupiterx' ),



		'all_done'                                                         => __( 'All Done!', 'jupiterx' ),
		'item_is_successfully_installed'                                   => __( '<strong>{param}</strong> Plugin is successfully installed.', 'jupiterx' ),

		'are_you_sure_you_want_to_remove_plugin'                           => __( 'Are you sure you want to remove <strong>{param}</strong> Plugin? <br> Note that the plugin files will be removed from your server!', 'jupiterx' ),




		'are_you_sure_you_want_to_remove_addon'                            => __( 'Are you sure you want to remove <strong>{param}</strong> Add-on? <br> Note that all any data regarding this add-on will be lost.', 'jupiterx' ),
		'addon_deactivate_successfully'                                    => __( '<strong>{param}</strong> deactivated successfully.', 'jupiterx' ),

		'product_registeration_required'                                   => __( 'Product registration required!', 'jupiterx' ),
		'you_must_register_your_product'                                   => __( 'In order to use this feature you must register your product.', 'jupiterx' ),
		'register_product'                                                 => __( 'Register Product', 'jupiterx' ),
		'registering_theme'                                                => __( 'Registering Jupiter X', 'jupiterx' ),
		'wait_for_api_key_registered'                                      => __( 'Please wait while your API key is being verified.', 'jupiterx' ),
		'discard'                                                          => __( 'Discard', 'jupiterx' ),
		'thanks_registering'                                               => __( 'Thanks for Registration!', 'jupiterx' ),
		'registeration_unsuccessful'                                       => __( 'Oops! Registration was unsuccessful.', 'jupiterx' ),
		'revoke_API_key'                                                   => __( 'Revoke API Key', 'jupiterx' ),
		'you_are_about_to_remove_API_key'                                  => __( 'You are about to remove API key from this website?', 'jupiterx' ),
		'ok'                                                               => __( 'Ok', 'jupiterx' ),
		'cancel'                                                           => __( 'Cancel', 'jupiterx' ),




		'uninstalling_Template'                                            => __( 'Uninstalling Template', 'jupiterx' ),
		'please_wait_for_few_moments'                                      => __( 'Please wait for few moments...', 'jupiterx' ),
		'restoring_database'                                               => __( 'Restoring Database', 'jupiterx' ),
		'remove_image_size'                                                => __( 'Remove Image Size', 'jupiterx' ),
		'are_you_sure_remove_image_size'                                   => __( 'Are you sure you want to remove this image size?', 'jupiterx' ),
		'image_sizes_could_not_be_stored'                                  => __( 'Image sizes could not be stored. Please try again and if issue persists, contact our support.', 'jupiterx' ),
		'download_psd_files'                                               => __( 'Download PSD files', 'jupiterx' ),
		'exporting'                                                        => __( 'Exporting', 'jupiterx' ),
		'export_waiting'                                                   => __( 'Please wait for the export to finish...', 'jupiterx' ),
		'importing'                                                        => __( 'Importing', 'jupiterx' ),
		'import_waiting'                                                   => __( 'Please wait for the import to finish...', 'jupiterx' ),
		'import_select_options'                                            => __( 'Please select only the options which exist in the selected ZIP file.', 'jupiterx' ),
		'site_content'                                                     => __( 'Site Content', 'jupiterx' ),
		'widgets'                                                          => __( 'Widgets', 'jupiterx' ),
		'settings'                                                         => __( 'Settings', 'jupiterx' ),
		'download'                                                         => __( 'Download', 'jupiterx' ),
		'close'                                                            => __( 'Close', 'jupiterx' ),
		'done'                                                             => __( 'Done', 'jupiterx' ),
		'error'                                                            => __( 'Error!', 'jupiterx' ),
		'try_again'                                                        => __( 'Try again', 'jupiterx' ),
		'select'                                                           => __( 'Select', 'jupiterx' ),
		'select_zip_file'                                                  => __( 'Select ZIP file', 'jupiterx' ),
		'successfully_finished'                                            => __( 'has been finished successfully.', 'jupiterx' ),
		'issue_persists'                                                   => __( 'If the issue persists, please contact support.', 'jupiterx' ),
		'template_backup_date'                                             => __( 'Restore database to a backup stored at: ', 'jupiterx' ),
		'add_image_size'                                                   => __( 'Add New Image Size', 'jupiterx' ),
		'image_size_name'                                                  => __( 'Size Name', 'jupiterx' ),
		'image_size_width'                                                 => __( 'Image Width', 'jupiterx' ),
		'image_size_height'                                                => __( 'Image Height', 'jupiterx' ),
		'image_size_crop'                                                  => __( 'Hard Crop?', 'jupiterx' ),
		'save'                                                             => __( 'Save', 'jupiterx' ),
		'edit'                                                             => __( 'Edit', 'jupiterx' ),
		'size_name'                                                        => __( 'Name', 'jupiterx' ),
		'image_size'                                                       => __( 'Size', 'jupiterx' ),
		'crop'                                                             => __( 'Crop', 'jupiterx' ),
		'edit_image_size'                                                  => __( 'Edit Image Size', 'jupiterx' ),
		'saving_image_size'                                                => __( 'Saving Image Sizes', 'jupiterx' ),
		'wait_for_image_size_update'                                       => __( 'Please wait while the image sizes going to be updated', 'jupiterx' ),
		'required'                                                         => __( 'Required', 'jupiterx' ),

		// Plugin manager buttons
		'add'                                                              => __( 'Add', 'jupiterx' ),
		'remove'                                                           => __( 'Remove', 'jupiterx' ),
		'delete'                                                           => __( 'Delete', 'jupiterx' ),
		'install'                                                          => __( 'Install', 'jupiterx' ),
		'activate'                                                         => __( 'Activate', 'jupiterx' ),
		'deactivate'                                                       => __( 'Deactivate', 'jupiterx' ),

		// Common in plugins.
		'continue'                                                         => __( 'Continue ', 'jupiterx' ),
		'something_went_wrong'                                             => __( 'Something went wrong!', 'jupiterx' ),
		'something_wierd_happened_please_try_again'                        => __( 'Something wierd happened , please try again', 'jupiterx' ),

		// Updating a plugin.
		'update'                                                           => __( 'Update', 'jupiterx' ),
		'plugins'                                                          => __( 'Plugins', 'jupiterx' ),
		'themes'                                                           => __( 'Themes', 'jupiterx' ),
		'update_plugin'                                                    => __( 'Update Plugin', 'jupiterx' ),
		'you_are_about_to_update'                                          => __( 'You are about to update <strong>{param}</strong> plugin', 'jupiterx' ),
		'updating_plugin'                                                  => __( 'Updating Plugin', 'jupiterx' ),
		'wait_for_plugin_update'                                           => __( 'Please wait while updating the plugin...', 'jupiterx' ),
		'plugin_is_successfully_updated'                                   => __( 'Plugin is successfully updated', 'jupiterx' ),
		'plugin_updated_recent_version'                                    => __( '<strong>{param}</strong> is successfully updated to the latest version.', 'jupiterx' ),
		'update_plugin_checker_title'                                      => __( 'Checking conflicts', 'jupiterx' ),
		'update_plugin_checker_progress'                                   => __( 'Please wait, looking for possible conflicts with existing plugins & theme.', 'jupiterx' ),
		'update_plugin_checker_warning'                                    => sprintf( __( '%1$s We have found conflicts on updating this plugin. Please resolve following issues before you continue otherwise it may cause unknown issues.', 'jupiterx' ), '<b>' . __( 'Heads up!', 'jupiterx' ). '</b>'),
		'update_plugin_checker_no_conflict'                                => __( 'No conflict found! Please continue to update the plugin.', 'jupiterx' ),
		'upgrade_to_version'                                               => __( 'Upgrade to version', 'jupiterx' ),

		// Installing a plugin.
		'install_plugin'                                                   => __( 'Install Plugin', 'jupiterx' ),
		'you_are_about_to_install'                                         => __( 'You are about to install <strong>{param}</strong> plugin.', 'jupiterx' ),
		'are_you_sure_you_want_to_install'                                 => __( 'Are you sure you want to install <strong>{param}</strong>?', 'jupiterx' ),
		'installing_plugin'                                                => __( 'Installing Plugin', 'jupiterx' ),
		'wait_for_plugin_install'                                          => __( 'Please wait while the plugin going to be installed...', 'jupiterx' ),
		'plugin_is_successfully_installed'                                 => __( 'Plugin is installed successfully.', 'jupiterx' ),
		'plugin_installed_successfully_message'                            => __( 'Latest version of <strong>{param}</strong> is installed successfully.', 'jupiterx' ),

		// Activating a plugin.
		'activating_notice'                                                => __( 'Activating Notice', 'jupiterx' ),
		'are_you_sure_you_want_to_activate'                                => __( 'Are you sure you want to activate <strong>{param}</strong>?', 'jupiterx' ),
		'activating_plugin'                                                => __( 'Activating Plugin', 'jupiterx' ),
		'wait_for_plugin_activation'                                       => __( 'Please wait while the plugin going to be activated...', 'jupiterx' ),
		'item_is_successfully_activated'                                   => __( '<strong>{param}</strong> Plugin is successfully activated.', 'jupiterx' ),

		// Deactivating a plugin
		'important_notice'                                                 => __( 'Important Notice', 'jupiterx' ),
		'are_you_sure_you_want_to_deactivate'                              => __( 'Are you sure you want to deactivate <strong>{param}</strong> plugin?', 'jupiterx' ),
		'deactivating_plugin'                                              => __( 'Deactivating Plugin', 'jupiterx' ),
		'wait_for_plugin_deactivation'                                     => __( 'Please wait while the plugin going to be deactivated...', 'jupiterx' ),
		'deactivating_notice'                                              => __( 'Deactivating Notice', 'jupiterx' ),
		'plugin_deactivate_successfully'                                   => __( 'Plugin deactivated successfully', 'jupiterx' ),

		// Deleting a plugin.
		'delete_plugin'                                                    => __( 'Delete Plugin', 'jupiterx' ),
		'you_are_about_to_delete'                                          => __( 'You are about to delete <strong>{param}</strong>', 'jupiterx' ),
		'wait_for_plugin_delete'                                           => __( 'Please wait while the plugin going to be deleted...', 'jupiterx' ),
		'plugin_is_successfully_deleted'                                   => __( 'Plugin is deleted successfully.', 'jupiterx' ),
		'plugin_deleted_successfully_message'                              => __( '<strong>{param}</strong> is deleted successfully.', 'jupiterx' ),

		// Plugin activation limit warning.
		'plugin_limit_warning'                                             => __( 'Important Notification', 'jupiterx' ),
		'plugin_limit_warning_message'                                     => __( 'Activating too many plugins can cause performance issues to your website. We highly recommend activating only those plugins you really need and deactivate unnecessary ones.', 'jupiterx' ),
		'learn_more'                                                       => __( 'Learn More', 'jupiterx' ),

		// Pro badge.
		'pro_badge_tooltip_title'                                          => jupiterx_is_premium() ? __( 'Activate to Unlock', 'jupiterx' ) : __( 'Upgrade to Unlock', 'jupiterx' ),

		// Theme version change
		'apikey_domain_match_error'                                        => __( 'Api key and domain is not matching', 'jupiterx' )
	);
}
