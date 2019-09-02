<?php
/**
 * Editor JS templates.
 *
 * @since 1.2.0
 *
 * @package Raven
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="tmpl-elementor-template-library-get-raven-pro-button">
	<a class="elementor-template-library-template-action elementor-button raven-go-pro-button jupiterx-upgrade-modal-trigger" href="#" target="_blank">
		<i class="jupiterx-icon-rocket-solid"></i>
		<span class="elementor-button-title">
		<?php
		if ( function_exists( 'jupiterx_is_premium' ) && jupiterx_is_premium() ) {
			esc_html_e( 'Activate to Unlock', 'raven' );
		} else {
			esc_html_e( 'Jupiter X Pro', 'raven' );
		}
		?>
		</span>
	</a>
</script>
