<?php
/**
 * Add Jupiter Product List popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Product list popup.
JupiterX_Customizer::add_section( 'jupiterx_product_list', [
	'panel'   => 'jupiterx_shop_panel',
	'title'   => __( 'Product List', 'jupiterx' ),
	'type'    => 'popup',
	'tabs'    => [
		'settings' => __( 'Settings', 'jupiterx' ),
		'styles'   => [
			'label' => __( 'Styles', 'jupiterx' ),
			'pro'   => true,
		],
	],
	'preview' => true,
	'help'    => [
		'url'   => 'http://help.artbees.net/shop/product-list-in-shop-customizer',
		'title' => __( 'Product List in Shop Customizer', 'jupiterx' ),
	],

] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_product_list_settings', [
	'popup' => 'jupiterx_product_list',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_product_list_styles', [
	'popup' => 'jupiterx_product_list',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Pro Box.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-pro-box',
	'settings' => 'jupiterx_product_list_styles_pro_box',
	'section'  => 'jupiterx_product_list_styles',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
