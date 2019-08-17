<?php
/**
 * Manage admin assets.
 *
 * @package JupiterX\Framework\Admin
 *
 * @since 1.3.0
 */

jupiterx_add_smart_action( 'admin_enqueue_scripts', 'jupiterx_enqueue_admin_scripts' );
/**
 * Enqueue admin scripts.
 *
 * @since 1.3.0
 */
function jupiterx_enqueue_admin_scripts() {
	wp_enqueue_style( 'jupiterx-admin-icons', JUPITERX_ADMIN_URL . 'assets/css/icons-admin.css', [], JUPITERX_VERSION );
	wp_enqueue_style( 'jupiterx-modal', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'css/jupiterx-modal' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
	wp_enqueue_style( 'jupiterx-common', JUPITERX_ADMIN_ASSETS_URL . 'css/common' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
	wp_enqueue_style( 'jupiterx-help-links', JUPITERX_ASSETS_URL . 'dist/css/admin/help-links' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );

	wp_enqueue_script( 'jupiterx-gsap', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'lib/gsap/gsap' . JUPITERX_MIN_JS . '.js', [], '1.19.1', true );
	wp_enqueue_script( 'jupiterx-modal', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'js/jupiterx-modal' . JUPITERX_MIN_JS . '.js', [], JUPITERX_VERSION, true );
	wp_enqueue_script( 'jupiterx-common', JUPITERX_ADMIN_ASSETS_URL . 'js/common' . JUPITERX_MIN_JS . '.js', [ 'jquery', 'wp-util', 'updates' ], JUPITERX_VERSION, true );
	wp_enqueue_script( 'jupiterx-help-links', JUPITERX_ASSETS_URL . 'dist/js/admin/help-links' . JUPITERX_MIN_JS . '.js', [], JUPITERX_VERSION, true );
	wp_enqueue_script( 'wp-color-picker-alpha', JUPITERX_ADMIN_ASSETS_URL . 'js/wp-color-picker-alpha' . JUPITERX_MIN_JS . '.js', [ 'wp-color-picker' ], JUPITERX_VERSION, true );

	wp_localize_script(
		'jupiterx-common',
		'jupiterxUtils',
		[
			'proBadge'    => jupiterx_get_pro_badge(),
			'proBadgeUrl' => jupiterx_get_pro_badge_url(),
		]
	);

	
	wp_add_inline_script( 'jupiterx-common', 'var jupiterxPremium = true;', 'before' );

	if ( jupiterx_is_callable( 'JupiterX_Pro' ) && method_exists( 'JupiterX_Pro', 'plugin_name' ) ) {
		wp_add_inline_script( 'jupiterx-common', '
			( function() {
				if ( typeof jupiterx === \'object\' && typeof jupiterx.uninstallPro !== \'undefined\' ) {
					jupiterx.uninstallPro();
				}
			} )( );
		', 'after' );
	}
	
}

jupiterx_add_smart_action( 'admin_print_footer_scripts', 'jupiterx_print_admin_templates' );
jupiterx_add_smart_action( 'jupiterx_print_templates', 'jupiterx_print_admin_templates' );
/**
 * Print admin JS templates.
 *
 * @since 1.3.0
 */
function jupiterx_print_admin_templates() {
	?>
	<script type="text/html" id="tmpl-jupiterx-upgrade">
		<div class="jupiterx-upgrade">
			<div class="jupiterx-upgrade-step jupiterx-upgrade-buy active">
				<div class="jupiterx-upgrade-count">
					<span class="jupiterx-upgrade-num">1</span>
				</div>
				<div class="jupiterx-upgrade-content">
					<div class="jupiterx-upgrade-title">
						<?php esc_html_e( 'Get a Jupiter X license', 'jupiterx' ); ?>
						<div class="jupiterx-upgrade-help-buy">
							<a target="_blank" href="https://help.artbees.net/getting-started/upgrading-to-pro">
								<i class="jupiterx-icon-question-circle"></i>
								<?php esc_html_e( 'Help', 'jupiterx' ); ?>
							</a>
						</div>
					</div>
					<a href="{{ data.url || 'https://themeforest.net/item/jupiter-multipurpose-responsive-theme/5177775?ref=artbees&utm_medium=AdminUpgradePopup&utm_campaign=FreeJupiterXAdminUpgradeCampaign' }}" target="_blank" class="jupiterx-upgrade-buy-pro btn btn-primary"><?php esc_html_e( 'Buy Jupiter X Pro', 'jupiterx' ); ?></a>
				</div>
			</div>
			<div class="jupiterx-upgrade-step jupiterx-upgrade-activate-key">
				<div class="jupiterx-upgrade-count">
					<span class="jupiterx-upgrade-num">2</span>
				</div>
				<div class="jupiterx-upgrade-content">
					<div class="jupiterx-upgrade-title"><?php esc_html_e( 'Activate PRO version', 'jupiterx' ); ?></div>
					<div class="form-inline jupiterx-upgrade-api-field">
						<input type="text" class="jupiterx-form-control jupiterx-upgrade-api-key" placeholder="<?php esc_html_e( 'Enter your API key', 'jupiterx' ); ?>">
						<a class="jupiterx-upgrade-help-icon" href="https://help.artbees.net/getting-started/theme-registration/getting-an-api-key" target="_blank">
							<i class="jupiterx-icon-question-circle"></i>
							<span class="screen-reader-text"><?php esc_html_e( 'Getting an API key', 'jupiterx' ); ?></span>
						</a>
					</div>
					<button type="submit" class="btn btn-primary jupiterx-upgrade-activate"><?php esc_html_e( 'Activate Product', 'jupiterx' ); ?></button>
				</div>
			</div>
			<div class="jupiterx-upgrade-step jupiterx-upgrade-install-plugin">
				<div class="jupiterx-upgrade-count">
					<span class="jupiterx-upgrade-num">3</span>
				</div>
				<div class="jupiterx-upgrade-content">
					<div class="jupiterx-upgrade-title"><?php esc_html_e( 'Install Jupiter X Pro plugin', 'jupiterx' ); ?></div>
					<div class="jupiterx-upgrade-install-progress"></div>
				</div>
			</div>
		</div>
	</script>

	<script type="text/html" id="tmpl-jupiterx-activate">
		<div class="jupiterx-upgrade-step jupiterx-upgrade-activate-key active">
			<div class="jupiterx-upgrade-content">
				<div class="form-inline jupiterx-upgrade-api-field">
					<input type="text" class="jupiterx-form-control jupiterx-upgrade-api-key" placeholder="<?php esc_html_e( 'Enter your API key', 'jupiterx' ); ?>">
					<a class="jupiterx-upgrade-help-icon" href="https://help.artbees.net/getting-started/theme-registration/getting-an-api-key" target="_blank">
						<i class="jupiterx-icon-question-circle"></i>
						<span class="screen-reader-text"><?php esc_html_e( 'Getting an API key', 'jupiterx' ); ?></span>
					</a>
				</div>
				<button type="submit" class="btn btn-primary jupiterx-upgrade-activate"><?php esc_html_e( 'Activate Product', 'jupiterx' ); ?></button>
			</div>
		</div>
	</script>

	<script type="text/html" id="tmpl-jupiterx-progress-bar">
		<div class="progress">
			<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 20%"></div>
		</div>
	</script>
	<?php
}
