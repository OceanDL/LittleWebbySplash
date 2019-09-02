<?php
/**
 * Add Jupiter elements popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Elements popup.
JupiterX_Customizer::add_section( 'jupiterx_checkout_cart', [
	'panel'   => 'jupiterx_shop_panel',
	'title'   => __( 'Checkout & Cart', 'jupiterx' ),
	'type'    => 'popup',
	'tabs'    => [
		'styles' => __( 'Styles', 'jupiterx' ),
	],
	'preview' => true,
	'pro'     => true,
	'help'    => [
		'url'   => 'http://help.artbees.net/shop/checkout-cart-pages-in-shop-customizer',
		'title' => __( 'Checkout & Cart Pages in Shop Customizer', 'jupiterx' ),
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_checkout_cart_styles', [
	'popup' => 'jupiterx_checkout_cart',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Pro Box.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-pro-box',
	'settings' => 'jupiterx_checkout_cart_styles_pro_box',
	'section'  => 'jupiterx_checkout_cart_styles',
] );
