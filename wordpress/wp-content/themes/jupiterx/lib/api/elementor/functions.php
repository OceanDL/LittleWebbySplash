<?php
/**
 * The Jupiter Elementor component contains a set of functions for Elementor plugin.
 *
 * @package JupiterX\Framework\API\Elementor
 *
 * @since   1.0.0
 */

add_action( 'elementor/widgets/widgets_registered', 'jupiterx_elementor_register_widgets' );
/**
 * Register widgets to Elementor.
 *
 * @since 1.0.0
 * @access public
 *
 * @param object $widgets_manager The widgets manager.
 */
function jupiterx_elementor_register_widgets( $widgets_manager ) {
	require_once JUPITERX_API_PATH . 'elementor/widgets/sidebar.php';
	require_once JUPITERX_API_PATH . 'elementor/widgets/post-navigation.php';

	// Unregister native sidebar.
	$widgets_manager->unregister_widget_type( 'sidebar' );

	// Register custom sidebar.
	$widgets_manager->register_widget_type( new JupiterX_Elementor_Widget_Sidebar() );
}

add_action( 'wp_enqueue_scripts', 'jupiterx_elementor_modify_template_enqueue', 500 );
/**
 * Fix flash of unstyled components by enqueueing styles in head.
 *
 * @since 1.2.0
 *
 * @return void
 */
function jupiterx_elementor_modify_template_enqueue() {

	if ( class_exists( '\Elementor\Plugin' ) ) {
		$elementor = \Elementor\Plugin::instance();
		$elementor->frontend->enqueue_styles();
	}

	if ( class_exists( '\ElementorPro\Plugin' ) ) {
		$elementor = \ElementorPro\Plugin::instance();
		$elementor->enqueue_styles();
	}

	if ( ! class_exists( 'Elementor\Core\Files\CSS\Post' ) ) {
		return;
	}

	$templates   = [];
	$templates[] = jupiterx_get_option( 'jupiterx_header_template', 'global', '' );
	$templates[] = jupiterx_get_option( 'jupiterx_header_sticky_template', 'global', '' );
	$templates[] = jupiterx_get_option( 'jupiterx_footer_template', 'global', '' );

	foreach ( $templates as $template ) {
		$css_file = new Elementor\Core\Files\CSS\Post( $template );
		$css_file->enqueue();
	}
}

/**
 * Check if Elementor Pro is active.
 *
 * @since 1.2.0
 */
function jupiterx_is_elementor_pro() {
	if ( class_exists( '\ElementorPro\Plugin' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if condition is set for location.
 *
 * @param string $location Name of the main location.
 * @param string $sub_location Name of the sub location.
 *
 * @since 1.7.0
 */
function jupiterx_is_location_conditions_set( $location, $sub_location ) {
	$elementor_conditions = get_option( 'elementor_pro_theme_builder_conditions', [] );

	if ( ! isset( $elementor_conditions[ $location ] ) || ! is_array( $elementor_conditions[ $location ] ) ) {
		return false;
	}

	foreach ( (array) $elementor_conditions[ $location ] as $archive_template ) {
		if ( false !== array_search( "include/{$location}/{$sub_location}", $archive_template, true ) ) {
			return true;
		}
	}

	return false;
}
