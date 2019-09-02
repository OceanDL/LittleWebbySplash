<?php
/**
 * This class handles printing custom templates in Customizer preview.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print custom templates.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
final class JupiterX_Customizer_Templates {

	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'customize_controls_print_footer_scripts', [ $this, 'render_templates' ], 0 );
	}

	/**
	 * Print templates in Customizer page.
	 *
	 * @since 1.0.0
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	public function render_templates() {
		?>
		<script type="text/html" id="tmpl-customize-jupiterx-popup-content">
			<div id="customize-jupiterx-popup-content" class="jupiterx-popup">
				<div id="customize-jupiterx-popup-controls" class="jupiterx-popup-container"></div>
			</div>
		</script>

		<script type="text/html" id="tmpl-customize-jupiterx-popup-child">
			<div class="jupiterx-popup-child">
				<div class="jupiterx-child-popup active">
					<# if ( data.title ) { #>
						<div class="jupiterx-child-popup-header">
							<h3 class="jupiterx-child-popup-title">{{{ data.title }}}</h3>
							<div class="jupiterx-child-popup-header-buttons">
								<button class="jupiterx-child-popup-button jupiterx-child-popup-close">
									<span class="dashicons dashicons-no"></span>
									<span class="screen-reader-text"><?php esc_html_e( 'Close', 'jupiterx' ); ?></span>
								</button>
							</div>
						</div>
					<# } #>
					<div class="jupiterx-child-popup-content"></div>
				</div>
			</div>
		</script>

		<script type="text/html" id="tmpl-customize-jupiterx-fonts-control-preview">
			<div class="jupiterx-fonts-control-preview" data-font-family="{{ data.name }}">
				<span class="jupiterx-fonts-control-preview-family">{{{ data.name }}}</span>
				<h3 class="jupiterx-fonts-control-preview-sample" style="font-family: {{ data.value || data.name }};"><?php esc_html_e( 'The spectate before us was indeed sublime.', 'jupiterx' ); ?></h3>
				<button class="jupiterx-fonts-control-preview-remove">
					<?php JupiterX_Customizer_Utils::print_svg_icon( 'x-white' ); ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Remove', 'jupiterx' ); ?></span>
				</button>
			</div>
		</script>

		<script type="text/html" id="tmpl-customize-jupiterx-fonts-control-selector">
			<div class="jupiterx-fonts-control-popup jupiterx-popup-child">
				<div class="jupiterx-child-popup active">
					<div class="jupiterx-child-popup-content">
						<div class="jupiterx-fonts-control-selector">
							<div class="jupiterx-fonts-control-selector-preview">
								<h3 class="jupiterx-fonts-control-selector-sample"><?php esc_html_e( 'The spectate before us was indeed sublime.', 'jupiterx' ); ?></h3>
							</div>
							<span class="customize-control-title"><?php esc_html_e( 'Select a Font Family', 'jupiterx' ); ?></span>
							<div class="jupiterx-fonts-control-selector-group">
								<div class="jupiterx-fonts-control-selector-families">
									<div class="jupiterx-control jupiterx-select-control">
										<select class="jupiterx-select-control-field">
											<# _.each( data.fontFamilies, function( props, name ) { #>
												<# type = props.type || props #>
												<# value = props.value || name #>
												<option data-type="{{ type }}" value="{{ value }}">{{{ name }}}</option>
											<# } ); #>
										</select>
									</div>
								</div>
								<div class="jupiterx-fonts-control-selector-filters">
									<div class="jupiterx-control jupiterx-select-control">
										<select class="jupiterx-select-control-field">
											<option value=""><?php esc_html_e( 'All Fonts', 'jupiterx' ); ?></option>
											<# _.each( data.fontTypes, function( fontName, fontType ) { #>
												<# disabled = fontName.indexOf('jupiterx-pro-badge') != -1 ? 'disabled' : '' #>
												<option value="{{ fontType }}" {{ disabled }}><span>{{ fontName }}</span></option>
											<# } ); #>
										</select>
									</div>
								</div>
							</div>
							<div class="jupiterx-fonts-control-selector-buttons">
								<button class="jupiterx-fonts-control-selector-cancel jupiterx-button jupiterx-button-danger">
									<img src="<?php echo esc_url( JupiterX_Customizer_Utils::get_icon_url( 'x' ) ); ?>">
									<span class="screen-reader-text"><?php esc_html_e( 'Cancel', 'jupiterx' ); ?></span>
								</button>
								<button class="jupiterx-fonts-control-selector-submit jupiterx-button">
									<img src="<?php echo esc_url( JupiterX_Customizer_Utils::get_icon_url( 'check' ) ); ?>">
									<span class="screen-reader-text"><?php esc_html_e( 'Submit', 'jupiterx' ); ?></span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</script>

		<script type="text/html" id="tmpl-customize-jupiterx-exceptions-control-group">
			<div class="jupiterx-exceptions-control-group">
				<h3>{{{ data.text }}}</h3>
				<button class="jupiterx-exceptions-control-remove jupiterx-button jupiterx-button-outline jupiterx-button-danger jupiterx-button-small" data-id="{{ data.id }}"><?php esc_html_e( 'Remove', 'jupiterx' ); ?></button>
				<ul class="jupiterx-row jupiterx-group-controls"></ul>
			</div>
		</script>

		<script type="text/html" id="tmpl-customize-jupiterx-pro-preview-lightbox">
			<div class="jupiterx-pro-preview">
				<div class="jupiterx-pro-preview-header">
					<a class="jupiterx-pro-preview-back" href="#"><span class="jupiterx-icon-arrow-left-solid"></span> <?php esc_html_e( 'Back', 'jupiterx' ); ?></a>
					<?php if ( jupiterx_is_premium() ) : ?>
						<span>
							<span class="jupiterx-pro-preview-modal-description"><?php esc_html_e( 'Activate Jupiter X to unlock this feature', 'jupiterx' ); ?></span>
							<a class="jupiterx-pro-preview-upgrade jupiterx-upgrade-modal-trigger" data-upgrade-link="<?php echo esc_attr( jupiterx_upgrade_link( 'customizer' ) ); ?>" href="#"><?php esc_html_e( 'Activate Now', 'jupiterx' ); ?></a>
						</span>
					<?php else : ?>
						<a class="jupiterx-pro-preview-upgrade jupiterx-upgrade-modal-trigger" data-upgrade-link="<?php echo esc_attr( jupiterx_upgrade_link( 'customizer' ) ); ?>" href="#"><?php esc_html_e( 'Upgrade to Jupiter X Pro', 'jupiterx' ); ?></a>
					<?php endif; ?>
				</div>
				<div class="jupiterx-pro-preview-content">
					<div class="jupiterx-pro-preview-container">
						<# if ( data.preview ) { #>
							<img class="jupiterx-pro-preview-image" src="{{ data.preview }}" />
						<# } #>
					</div>
				</div>
			</div>
		</script>
		<?php
	}
}

// Initialize.
new JupiterX_Customizer_Templates();
