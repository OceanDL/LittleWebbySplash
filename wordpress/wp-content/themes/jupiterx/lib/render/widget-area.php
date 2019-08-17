<?php
/**
 * Registers the Jupiter default widget areas.
 *
 * @package JupiterX\Framework\Render
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'widgets_init', 'jupiterx_do_register_widget_areas', 5 );
/**
 * Register Jupiter's default widget areas.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_do_register_widget_areas() {
	// Keep primary sidebar first for default widget asignment.
	jupiterx_register_widget_area( [
		'name' => __( 'Sidebar Primary', 'jupiterx' ),
		'id'   => 'sidebar_primary',
	] );

	jupiterx_register_widget_area( [
		'name' => __( 'Sidebar Secondary', 'jupiterx' ),
		'id'   => 'sidebar_secondary',
	] );

	if ( current_theme_supports( 'offcanvas-menu' ) ) {
		jupiterx_register_widget_area( [
			'name'       => __( 'Off-Canvas Menu', 'jupiterx' ),
			'id'         => 'offcanvas_menu',
			'jupiterx_type' => 'offcanvas',
		] );
	}

	$columns_count = jupiterx_get_footer_max_columns();

	for ( $i = 1; $i <= $columns_count; $i++ ) {
		jupiterx_register_widget_area( [
			'name' => esc_html__( 'Footer ' . $i, 'jupiterx' ), // @codingStandardsIgnoreLine
			'id'   => 'footer_widgets_column_' . $i,
		] );
	}
}

/**
 * Call register sidebar.
 *
 * Because the WordPress.org checker doesn't understand that we are using register_sidebar properly,
 * we have to add this useless call which only has to be declared once.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 */
add_action( 'widgets_init', 'jupiterx_register_widget_area' );
