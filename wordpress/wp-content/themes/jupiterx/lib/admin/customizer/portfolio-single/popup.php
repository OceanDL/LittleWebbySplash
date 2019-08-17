<?php
/**
 * Add Jupiter portfolio archive popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$popups = [
	'title'          => __( 'Title', 'jupiterx' ),
	'meta'           => __( 'Meta', 'jupiterx' ),
	'featured_image' => __( 'Featured Image', 'jupiterx' ),
	'navigation'     => __( 'Navigation', 'jupiterx' ),
	'social_share'   => __( 'Social Share', 'jupiterx' ),
	'related_posts'  => __( 'Related Posts', 'jupiterx' ),
];

// Portfolio single popup.
JupiterX_Customizer::add_section( 'jupiterx_portfolio_single', [
	'panel'  => 'jupiterx_portfolio_panel',
	'title'  => __( 'Portfolio Single', 'jupiterx' ),
	'type'   => 'popup',
	'tabs'   => [
		'settings' => __( 'Settings', 'jupiterx' ),
		'styles'   => __( 'Styles', 'jupiterx' ),
	],
	'popups'  => $popups,
	'preview' => true,
	'help'    => [
		'url'   => 'http://help.artbees.net/customizer/display-settings-for-blog-shop-single-pages',
		'title' => __( 'Display settings for Blog, Shop single pages', 'jupiterx' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_portfolio_single_settings', [
	'popup' => 'jupiterx_portfolio_single',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_portfolio_single_styles', [
	'popup' => 'jupiterx_portfolio_single',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-child-popup',
	'settings'   => 'jupiterx_portfolio_single_styles_popups',
	'section'    => 'jupiterx_portfolio_single_styles',
	'target'     => 'jupiterx_portfolio_single',
	'choices'    => [
		'title'          => __( 'Title', 'jupiterx' ),
		'meta'           => __( 'Meta', 'jupiterx' ),
		'featured_image' => __( 'Featured Image', 'jupiterx' ),
		'navigation'     => [
			'label' => __( 'Navigation', 'jupiterx' ),
			'pro'   => true,
		],
		'social_share'   => [
			'label' => __( 'Social Share', 'jupiterx' ),
			'pro'   => true,
		],
		'related_posts'  => [
			'label' => __( 'Related Posts', 'jupiterx' ),
			'pro'   => true,
		],
	],
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_portfolio_single_' . $popup_id, [
		'popup' => 'jupiterx_portfolio_single',
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
