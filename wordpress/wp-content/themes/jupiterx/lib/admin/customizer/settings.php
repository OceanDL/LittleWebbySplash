<?php
/**
 * Add Jupiter settings to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Blog.
JupiterX_Customizer::add_panel( 'jupiterx_blog_panel', [
	'priority' => 145,
	'title'    => __( 'Blog', 'jupiterx' ),
] );

// Portfolio.
JupiterX_Customizer::add_panel( 'jupiterx_portfolio_panel', [
	'priority' => 150,
	'title'    => __( 'Portfolio', 'jupiterx' ),
] );

// Shop.
if ( class_exists( 'woocommerce' ) ) {
	JupiterX_Customizer::add_panel( 'jupiterx_shop_panel', [
		'priority' => 155,
		'title'    => __( 'Shop', 'jupiterx' ),
	] );
}

// Pages.
JupiterX_Customizer::add_panel( 'jupiterx_pages', [
	'priority' => 160,
	'title'    => __( 'Pages', 'jupiterx' ),
] );

/**
 * Load all the popups.
 *
 * @since 1.0.0
 */
$popups = [
	'pro',
	'logo',
	'site-settings',
	'typography',
	'header',
	'title-bar',
	'sidebar',
	'footer',
	'blog-single',
	'blog-archive',
	'portfolio-single',
	'product-archive',
	'product-list',
	'product-page',
	'checkout-cart',
	'search',
	'404',
	'maintenance',
];

foreach ( $popups as $popup ) {
	require_once dirname( __FILE__ ) . '/' . $popup . '/popup.php';
}
