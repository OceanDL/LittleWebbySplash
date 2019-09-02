<?php
/**
 * Add Jupiter elements popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$popups = [
	'widgets_title'     => __( 'Widgets Title', 'jupiterx' ),
	'widgets_text'      => __( 'Widgets Text', 'jupiterx' ),
	'widgets_link'      => __( 'Widgets Link', 'jupiterx' ),
	'widgets_thumbnail' => __( 'Widgets Thumbnail', 'jupiterx' ),
	'widgets_container' => __( 'Widgets Container', 'jupiterx' ),
	'divider'           => __( 'Divider', 'jupiterx' ),
];

// Sidebar popup.
JupiterX_Customizer::add_section( 'jupiterx_sidebar', [
	'title'    => __( 'Sidebar', 'jupiterx' ),
	'type'     => 'popup',
	'priority' => 130,
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx' ),
		'styles'   => __( 'Styles', 'jupiterx' ),
	],
	'popups'   => $popups,
	'help'     => [
		'url'   => 'http://help.artbees.net/sidebar/adding-a-sidebar-globally',
		'title' => __( 'Adding a Sidebar globally', 'jupiterx' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_sidebar_settings', [
	'popup' => 'jupiterx_sidebar',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_sidebar_styles', [
	'popup' => 'jupiterx_sidebar',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_sidebar_styles_popups',
	'section'  => 'jupiterx_sidebar_styles',
	'target'   => 'jupiterx_sidebar',
	'choices'  => $popups,
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_sidebar_' . $popup_id, [
		'popup' => 'jupiterx_sidebar',
		'type'  => 'pane',
		'pane'  => [
			'type' => 'popup',
			'id'   => $popup_id,
		],
	] );
}

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
