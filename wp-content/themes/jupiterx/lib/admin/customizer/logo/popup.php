<?php
/**
 * Add Jupiter Logo popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Insert button under Site Identity section.
JupiterX_Customizer::add_field( [
	'priority' => 200,
	'type'     => 'jupiterx-popup',
	'settings' => 'jupiterx_popup_logo_switch',
	'section'  => 'title_tagline',
	'label'    => __( 'Site Logo', 'jupiterx' ),
	'text'     => __( 'Logo', 'jupiterx' ),
	'target'   => 'jupiterx_logo',
] );

// Layout popup.
JupiterX_Customizer::add_section( 'jupiterx_logo', [
	'title'  => __( 'Logo', 'jupiterx' ),
	'type'   => 'popup',
	'tabs'   => [
		'settings' => __( 'Settings', 'jupiterx' ),
	],
	'hidden' => true,
	'help'   => [
		'url'   => 'http://help.artbees.net/how-to-s/logo/adding-multiple-versions-of-logo-to-website',
		'title' => __( 'Adding Multiple versions of logo to website', 'jupiterx' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_logo_settings', [
	'popup' => 'jupiterx_logo',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
