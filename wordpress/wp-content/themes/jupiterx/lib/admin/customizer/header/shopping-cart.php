<?php
/**
 * Add Jupiter settings for Header > Styles tab > Shopping cart to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header_shopping_cart';

// Cart quick view.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-toggle',
	'settings'   => 'jupiterx_header_shopping_cart',
	'section'    => $section,
	'label'      => __( 'Cart Quick View', 'jupiterx' ),
	'column'     => '6',
] );

// Align.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'settings'   => 'jupiterx_header_shopping_cart_position',
	'section'    => $section,
	'label'      => __( 'Position', 'jupiterx' ),
	'column'     => '6',
	'default'    => jupiterx_get_direction( 'left' ),
	'choices'    => [
		'left'  => [
			'icon'  => jupiterx_get_direction( 'alignment-left' ),
		],
		'right' => [
			'icon'  => jupiterx_get_direction( 'alignment-right' ),
		],
	],
	'active_callback' => [
		[
			'setting'  => $section,
			'operator' => '==',
			'value'    => true,
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_shopping_cart_divider',
	'section'  => $section,
] );

// Icon size.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_header_shopping_cart_icon_size',
	'css_var'     => 'header-shopping-cart-icon-size',
	'section'     => $section,
	'column'      => '4',
	'icon'        => 'font-size',
	'units'       => [ 'px', 'em', 'rem' ],
	'transport'   => 'postMessage',
	'default'     => [
		'size' => 1.5,
		'unit' => 'rem',
	],
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart-icon',
			'property' => 'font-size',
		],
	],
] );

// Icon color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_shopping_cart_icon_color',
	'css_var'   => 'header-shopping-cart-icon-color',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'icon-color',
	'transport' => 'postMessage',
	'default'   => '#6c757d',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart-icon',
			'property' => 'color',
		],
	],
] );

// Text color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_shopping_cart_text_color',
	'css_var'   => 'header-shopping-cart-text-color',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'default'   => '#6c757d',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart',
			'property' => 'color',
		],
	],
] );

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Hover', 'jupiterx' ),
	'label_type' => 'fancy',
	'color'      => 'orange',
	'settings'   => 'jupiterx_header_shopping_cart_label',
	'section'    => $section,
] );

// Icon color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_shopping_cart_icon_color_hover',
	'css_var'   => 'header-shopping-cart-icon-color-hover',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'icon-color',
	'transport' => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart:hover .jupiterx-navbar-cart-icon',
			'property' => 'color',
		],
	],
] );

// Text color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_shopping_cart_text_color_hover',
	'css_var'   => 'header-shopping-cart-text-color-hover',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart:hover',
			'property' => 'color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_shopping_cart_divider_2',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_shopping_cart_spacing',
	'css_var'   => 'header-shopping-cart',
	'section'   => $section,
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .jupiterx-navbar-cart',
		],
	],
] );
