<?php

if ( ! JUPITERX_CONTROL_PANEL_TEMPLATES ) {
	return;
}

$menu_items_access = get_site_option( 'menu_items' );

if ( is_multisite() && ! isset( $menu_items_access['plugins'] ) && ! current_user_can( 'manage_network_plugins' ) ) : ?>
	<div class="jupiterx-cp-pane-box" id="jupiterx-no-templates">
		<div class="alert alert-warning" role="alert">
			<?php esc_html_e( 'Now you are using a sub site which it\'s plugin functionalities are disabled by Network admin.', 'jupiterx' ); ?>
			<?php esc_html_e( 'To have a full control over plugins, please go to My Sites > Network Admin > Settings and check Plugins option of "Enable administration menus" option. If you don\'t have access to the mentioned page, please contact Network Admin.', 'jupiterx' ); ?>
		</div>
	</div>
<?php
	return;
endif;

$installed_template_data_attr = '';
$installed_template_id = get_option( 'jupiterx_template_installed_id', '' );
$installed_template_data_attr .= ' data-installed-template-id="' . esc_attr( $installed_template_id ) . '"';
$installed_template = get_option( 'jupiterx_template_installed', '' );
$installed_template_data_attr .= ' data-installed-template="' . esc_attr( $installed_template ) . '"';
wp_print_request_filesystem_credentials_modal();
?>
<div class="jupiterx-cp-pane-box" id="jupiterx-cp-templates">

	<!-- Restore Button wrap -->
	<div id="js__restore-template-wrap" class="jupiterx-restore-template-wrap">
		<a class="btn btn-primary jupiterx-button--restore-backup" id="js__restore-template-btn" href="#" data-content="" data-toggle="popover" data-placement="bottom">
			<?php esc_html_e( 'Restore from Last Backup', 'jupiterx' ); ?>
		</a>
	</div>
	<!-- End of Restore Button wrap -->

	<!-- Installed Template wrap -->
	<div id="js__installed-template-wrap" class="jupiterx-cp-installed-template">
		<h3>
			<?php esc_html_e( 'Installed Template', 'jupiterx' ); ?>
			<?php jupiterx_the_help_link( 'http://help.artbees.net/getting-started/templates/installing-a-template', esc_html__( 'Installing a Template', 'jupiterx' ) ); ?>
		</h3>
		<div id="js__installed-template" <?php echo $installed_template_data_attr; ?>></div>
		<div class="clearfix"></div>
	</div>
	<!-- End of installed template -->

	<div class="jupiterx-cp-install-template">
		<h3>
			<?php esc_html_e( 'Templates', 'jupiterx' ); ?>
			<?php jupiterx_the_help_link( 'http://help.artbees.net/getting-started/templates/installing-a-template', esc_html__( 'Installing a Template', 'jupiterx' ) ); ?>
		</h3>
		<div class="input-group jupiterx-cp-template-search">
		  <div class="input-group-prepend">
		    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php esc_html_e( 'All Categories', 'jupiterx' ); ?></button>
		    <input class="jupiterx-select-box-value" id="js__templates-category-filter" type="hidden">
		    <div class="dropdown-menu js__template-category-list-wrap">
		    </div>
		  </div>
		  <input type="text" class="jupiterx-form-control" id="js__template-search" placeholder="<?php esc_html_e( 'Search...', 'jupiterx' ); ?>">
		</div>

		<div id="js__new-templates-list" class="jupiterx-cp-template-items"></div>

		<div class="abb-template-page-load-more" data-from="0">
			<svg class="jupiterx-spinner" width="50px" height="50px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
				<circle class="jupiterx-spinner-path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
			</svg>
		</div>
	</div>
</div>
