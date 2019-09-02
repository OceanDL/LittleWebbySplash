<?php
if ( ! JUPITERX_CONTROL_PANEL_HOME ) {
	return;
}

$api_key = get_option( 'artbees_api_key' );
$is_apikey = empty( $api_key ) ? false : true;
$has_api_key = empty( $api_key ) ? 'd-none' : '';
$no_api_key = empty( $has_api_key ) ? 'd-none' : '';
?>
<div class="jupiterx-cp-pane-box" id="jupiterx-cp-home">
	<?php if ( ! jupiterx_setup_wizard()->is_notice_hidden() ) : ?>
	<div class="alert alert-secondary jupiterx-setup-wizard-message" role="alert">
		<div class="row align-items-center">
			<div class="col-md-8">
				<p><?php esc_html_e( 'This wizard helps you configure your new website quick and easy.', 'jupiterx' ); ?></p>
			</div>
			<div class="col-md-4">
				<div class="text-right">
					<a class="btn btn-success" href="<?php echo jupiterx_setup_wizard()->get_url(); ?>"><?php esc_html_e( 'Run Setup Wizard', 'jupiterx' ); ?></a>
					<button class="btn btn-outline-secondary jupiterx-setup-wizard-hide-notice"><?php esc_html_e( 'Discard', 'jupiterx' ); ?></button>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if ( ! jupiterx_is_premium() ) : ?>
		<div class="jupiterx-pro-banner">
			<i class="jupiterx-icon-pro"></i>
			<h1><?php esc_html_e( 'Upgrade to Jupiter X Pro', 'jupiterx' ); ?></h1>
			<a href="<?php echo esc_attr( jupiterx_upgrade_link( 'banner' ) ); ?>" class="btn btn-primary" target="_blank"><?php esc_html_e( 'Upgrade Now', 'jupiterx' ); ?></a>
			<div class="features">
				<ul>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Shop customizer', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Custom Header and Footer', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Blog and Portfolio customizer', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Premium plugins', 'jupiterx' ); ?></span>
					</li>
				</ul>
				<ul>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'More elementor elements', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Block and page templates', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Premium support', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( '150+ pre-made website templates', 'jupiterx' ); ?></span>
					</li>
				</ul>
				<ul>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Premium Slideshows', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<span><?php esc_html_e( 'Adobe fonts', 'jupiterx' ); ?></span>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<?php esc_html_e( 'Advanced tracking options', 'jupiterx' ); ?>
					</li>
					<li>
						<i class="jupiterx-icon-check-solid"></i>
						<?php esc_html_e( 'And much more...', 'jupiterx' ); ?>
					</li>
				</ul>
			</div>
		</div>
	<?php
		
		else :
	?>
		<div class="get-api-key-form <?php echo esc_attr( $no_api_key ); ?>">
			<h3 class="heading-with-icon icon-lock">
				<?php esc_html_e( 'Activate Product', 'jupiterx' ); ?>
			</h3>
			<div class="jupiterx-callout bd-callout-danger mb-4 ml-0">
				<h4>
					<span class="dashicons dashicons-warning"></span>
					<?php esc_html_e( 'Almost Done! Please register Jupiter X to activate its features.', 'jupiterx' ); ?>
				</h4>
				<p>
					<?php esc_html_e( 'By registering Jupiter X you will be able to download hundreds of free templates, contact one on one support, install key plugins, get constant updates and unlock more feature.', 'jupiterx' ); ?>
					<a href="http://help.artbees.net/getting-started/theme-registration/registering-the-theme" target="_blank"><?php esc_html_e( 'Learn how>>', 'jupiterx' ); ?></a>
				</p>
			</div>
			<div class="form-group mb-5">
				<input type="text" id="jupiterx-cp-register-api-input" class="jupiterx-form-control w-50 mb-3 d-inline-block" placeholder="Enter your API key in here">
				<a href="https://help.artbees.net/getting-started/theme-registration/getting-an-api-key" class="btn jupiterx-btn-info mb-3" target="_blank">
					<img src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'setup-wizard/assets/images/question-mark.svg' ); ?>" alt="Help icon" width="16" height="16">
					<span><?php esc_html_e( 'How to get API key', 'jupiterx' ); ?></span>
				</a>
				<button class="btn btn-primary js__activate-product d-block" id="js__regiser-api-key-btn" href="#"><?php esc_html_e( 'Activate Product', 'jupiterx' ); ?></button>
			</div>
		</div>

		<div class="remove-api-key-form mb-5 <?php echo esc_attr( $has_api_key ); ?>">
			<h3 class="heading-with-icon icon-checkmark mb-4">
				<?php echo esc_html( JUPITERX_NAME ); ?>
				<?php esc_html_e( 'is Activated', 'jupiterx' ); ?>
			</h3>
			<?php wp_nonce_field( 'jupiterx-cp-ajax-register-api', 'security' ); ?>
			<button class="btn btn-primary js__deactivate-product" id="js__revoke-api-key-btn" href="#"><?php esc_html_e( 'Deactivate Product', 'jupiterx' ); ?></button>
			<?php jupiterx_the_help_link( 'http://help.artbees.net/getting-started/theme-registration/registering-the-theme', __( 'Registering the theme', 'jupiterx' ) ); ?>
		</div>
	<?php
		

		endif;
	?>

	<div class="row">
		<div class="col">
			<h3 class="heading-with-icon icon-learn"><?php esc_html_e( 'Learn', 'jupiterx' ); ?></h3>
			<?php do_action( 'jupiterx_control_panel_get_started' ); ?>
			<a class="btn btn-primary js__deactivate-product mb-4" href="https://help.artbees.net/getting-started" target="_blank"><?php esc_html_e( 'Get Started Guide', 'jupiterx' ); ?></a>
			<h6><?php esc_html_e( 'Learn deeper:', 'jupiterx' ); ?></h6>
			<ul class="list-unstyled d-inline-block">
				<li><a class="list-with-icon icon-video" target="_blank" href="https://themes.artbees.net/support/jupiterx/videos/"><?php esc_html_e( 'Video Tutorials', 'jupiterx' ); ?></a></li>
				<li><a class="list-with-icon icon-docs" target="_blank" href="https://help.artbees.net/"><?php esc_html_e( 'Articles', 'jupiterx' ); ?></a></li>
			</ul>
			<ul class="list-unstyled d-inline-block">
				<li><a class="list-with-icon icon-community" target="_blank" href="http://forums.artbees.net/"><?php esc_html_e( 'Community Forum', 'jupiterx' ); ?></a></li>
				<li><a class="list-with-icon icon-history" target="_blank" href="https://themes.artbees.net/support/jupiterx/release-notes/"><?php esc_html_e( 'Release History', 'jupiterx' ); ?></a></li>
			</ul>
		</div>
		<div class="col">
			<h3 class="heading-with-icon icon-download"><?php esc_html_e( 'Start with a Template', 'jupiterx' ); ?></h3>
			<p><?php esc_html_e( 'Save time by choosing among beautiful templates designed for different sectors and purposes.', 'jupiterx' ); ?></p>
			<a class="btn btn-secondary js__cp-sidebar-link" href="#install-templates"><?php esc_html_e( 'Import a Template', 'jupiterx' ); ?></a>
		</div>
	</div>
</div>
