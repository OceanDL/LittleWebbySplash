<?php
/**
 * Add Jupiter admin options.
 *
 * @package JupiterX\Framework\Admin
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'upload_mimes', 'jupiterx_add_svg_mime_type' );
/**
 * Jupiter add SVG mime type.
 *
 * @since 1.0.0
 *
 * @param array $mimes Current array of mime types..
 *
 * @return array Updated array of mime types.
 */
function jupiterx_add_svg_mime_type( $mimes ) {
	if ( ! current_user_can( 'administrator' ) ) {
		return $mimes;
	}

	if ( empty( get_option( 'jupiterx_svg_support' ) ) ) {
		return $mimes;
	}

	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
