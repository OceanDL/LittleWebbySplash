<?php
/**
 * Functions for updating theme version.
 *
 * @package JupiterX\Framework\API\Compatibility
 *
 * @since 1.0.0
 */

/**
 * Version updates.
 *
 * @since 1.0.2
 *
 * @return void
 */
function jupiterx_update_v102() {
	if ( is_null( get_option( 'jupiterx_setup_wizard_hide_notice', null ) ) ) {
		update_option( 'jupiterx_setup_wizard_hide_notice', true );
	}
}

/**
 * Version updates.
 *
 * @since 1.3.0
 *
 * @return void
 */
function jupiterx_update_v130() {
	set_site_transient( 'jupiterx_update_plugins_notice', 'yes' );
}
