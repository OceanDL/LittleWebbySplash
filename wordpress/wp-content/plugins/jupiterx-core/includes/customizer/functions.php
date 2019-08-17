<?php
/**
 * The Jupiter Customizer component.
 *
 * @package JupiterX_Core\Customizer
 */

/**
 * Load Kirki library.
 *
 * @since 1.0.0
 */
function jupiterx_customizer_kirki() {
	jupiterx_core()->load_files( [ 'customizer/vendors/kirki/kirki' ] );
}
