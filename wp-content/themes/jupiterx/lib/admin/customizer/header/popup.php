<?php
/**
 * Add Jupiter elements popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$popups = [
	'logo'             => __( 'Logo', 'jupiterx' ),
	'Menu'             => __( 'Menu', 'jupiterx' ),
	'Submenu'          => __( 'Submenu', 'jupiterx' ),
	'search'           => __( 'Search', 'jupiterx' ),
	'shopping_cart'    => __( 'Shopping Cart', 'jupiterx' ),
	'container'        => __( 'Container', 'jupiterx' ),
	'sticky_container' => __( 'Sticky Container', 'jupiterx' ),
	'sticky_logo'      => __( 'Sticky Logo', 'jupiterx' ),
];

// Header popup.
JupiterX_Customizer::add_section( 'jupiterx_header', [
	'title'    => __( 'Header', 'jupiterx' ),
	'type'     => 'popup',
	'priority' => 120,
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx' ),
		'styles'   => __( 'Styles', 'jupiterx' ),
	],
	'popups'   => $popups,
	'help'     => [
		'url'   => 'http://help.artbees.net/header/assigning-the-header-globally',
		'title' => __( 'Assigning the Header Globally', 'jupiterx' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_header_settings', [
	'popup' => 'jupiterx_header',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_header_styles', [
	'popup' => 'jupiterx_header',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Styles warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_header_styles_warning',
	'section'         => 'jupiterx_header_styles',
	'label'           => __( 'The following settings may have conflict with Elementor Pro plugin.', 'jupiterx' ),
	'jupiterx_url'    => 'https://help.artbees.net/troubleshooting/plugin-conflicts-with-jupiter-x',
	'active_callback' => 'jupiterx_is_elementor_pro',
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_header_styles_popups',
	'section'  => 'jupiterx_header_styles',
	'target'   => 'jupiterx_header',
	'choices'  => [
		'logo'          => __( 'Logo', 'jupiterx' ),
		'Menu'          => __( 'Menu', 'jupiterx' ),
		'Submenu'       => __( 'Submenu', 'jupiterx' ),
		'search'        => __( 'Search', 'jupiterx' ),
		'shopping_cart' => __( 'Shopping Cart', 'jupiterx' ),
		'container'     => __( 'Container', 'jupiterx' ),
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '!=',
			'value'    => '_custom',
		],
	],
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_header_styles_popups_sticky',
	'section'  => 'jupiterx_header_styles',
	'target'   => 'jupiterx_header',
	'choices'  => [
		'sticky_logo'      => __( 'Sticky Logo', 'jupiterx' ),
		'sticky_container' => __( 'Sticky Container', 'jupiterx' ),
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_behavior',
			'operator' => '==',
			'value'    => 'sticky',
		],
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '!=',
			'value'    => '_custom',
		],
	],
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_header_' . $popup_id, [
		'popup' => 'jupiterx_header',
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
