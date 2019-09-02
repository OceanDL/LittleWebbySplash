<?php
/**
 * This class provides the methods to Store and retrieve Image sizes from database.
 *
 * @package JupiterX_Core\Control_Panel\Image_Sizes
 *
 * @since 1.2.0
 */

/**
 * Store and retrieve Image sizes.
 *
 * @since 1.2.0
 */
class JupiterX_Control_Panel_Image_Sizes {

	/**
	 * Return list of the stored image sizes.
	 *
	 * If empty, it will return default sample size.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_available_image_sizes() {
		$options = get_option( JUPITERX_IMAGE_SIZE_OPTION );

		if ( empty( $options ) ) {
			$options = [
				[
					'size_w' => 500,
					'size_h' => 500,
					'size_n' => 'Image Size 500x500',
					'size_c' => 'on',
				],
			];
		}

		return $options;
	}

	/**
	 * Class constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_filter( 'jupiterx_control_panel_pane_image_sizes', [ $this, 'view' ] );
		add_action( 'wp_ajax_jupiterx_save_image_sizes', [ $this, 'save_image_size' ] );
	}

	/**
	 * Image sizes HTML directory.
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	public function view() {
		return jupiterx_core()->plugin_dir() . 'includes/control-panel/views/image-sizes.php';
	}

	/**
	 * Process image sizes data passed via admin-ajax.php and store it in wp_options table.
	 *
	 * @since 1.2.0
	 */
	public function save_image_size() {
		check_ajax_referer( 'ajax-image-sizes-options', 'security' );

		$options = [];

		if ( empty( $_POST['options'] ) ) {
			wp_send_json_error( esc_html__( 'Options are not valid.', 'jupiterx-core' ) );
		}

		// phpcs:disable
		$options = array_map( 'sanitize_text_field', $_POST['options'] );
		// phpcs:enable

		$options_array = [];

		foreach ( $options as $sizes ) {
			parse_str( $sizes, $output );
			$options_array[] = $output;
		}

		update_option( JUPITERX_IMAGE_SIZE_OPTION, $options_array );

		wp_die( 1 );
	}
}

new JupiterX_Control_Panel_Image_Sizes();
