<?php
/**
 * Add Jupiter Fonts & Typography popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$popups = [
	'body'  => __( 'Body', 'jupiterx' ),
	'links' => __( 'Links', 'jupiterx' ),
	'h1'    => __( 'Heading 1', 'jupiterx' ),
	'h2'    => __( 'Heading 2', 'jupiterx' ),
	'h3'    => __( 'Heading 3', 'jupiterx' ),
	'h4'    => __( 'Heading 4', 'jupiterx' ),
	'h5'    => __( 'Heading 5', 'jupiterx' ),
	'h6'    => __( 'Heading 6', 'jupiterx' ),
];

// Popup.
JupiterX_Customizer::add_section( 'jupiterx_typography', [
	'title'    => __( 'Fonts & Typography', 'jupiterx' ),
	'type'     => 'popup',
	'priority' => 100,
	'tabs'     => [
		'settings' => __( 'Fonts', 'jupiterx' ),
		'styles'   => __( 'Typography', 'jupiterx' ),
	],
	'popups'   => $popups,
	'help'     => [
		'url'   => 'http://help.artbees.net/how-to-s/typography/changing-typography-for-body-headings-and-links',
		'title' => __( 'Changing typography for Body, Headings and Links', 'jupiterx' ),
	],
] );

// Fonts tab.
JupiterX_Customizer::add_section( 'jupiterx_typography_settings', [
	'popup' => 'jupiterx_typography',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Typography tab.
JupiterX_Customizer::add_section( 'jupiterx_typography_styles', [
	'popup' => 'jupiterx_typography',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Typography warning.
JupiterX_Customizer::add_field( [
	'type'         => 'jupiterx-alert',
	'settings'     => 'jupiterx_typography_styles_warning',
	'section'      => 'jupiterx_typography_styles',
	'label'        => __( 'The following settings may have conflict with Elementor plugin.', 'jupiterx' ),
	'jupiterx_url' => 'https://help.artbees.net/troubleshooting/plugin-conflicts-with-jupiter-x',
] );

// Typography tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_typography_styles_popups',
	'section'  => 'jupiterx_typography_styles',
	'target'   => 'jupiterx_typography',
	'choices'  => $popups,
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_typography_' . $popup_id, [
		'popup' => 'jupiterx_typography',
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
