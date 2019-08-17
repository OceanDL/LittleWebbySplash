<?php
/**
 * Add Jupiter settings for Product page > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_product_page_settings';

// Template label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_product_page_label_1',
	'section'  => $section,
	'label'    => __( 'Template', 'jupiterx' ),
] );

// Template.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-radio-image',
	'settings'        => 'jupiterx_product_page_template',
	'section'         => $section,
	'default'         => '1',
	'choices'         => [
		'1'  => 'product-page-01',
		// '2'  => 'product-page-02', @codingStandardsIgnoreLine
		'3'  => [
			'name'    => 'product-page-03',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-03.jpg',
		],
		'4'  => [
			'name'    => 'product-page-04',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-04.jpg',
		],
		'5'  => [
			'name'    => 'product-page-05',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-05.jpg',
		],
		// '6'  => 'product-page-06', @codingStandardsIgnoreLine
		'7'  => [
			'name'    => 'product-page-07',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-07.jpg',
		],
		'8'  => [
			'name'    => 'product-page-08',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-08.jpg',
		],
		'9'  => [
			'name'    => 'product-page-09',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-09.jpg',
		],
		'10' => [
			'name'    => 'product-page-10',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-10.jpg',
		],
	],
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_product_page_label_2',
	'section'  => $section,
	'label'    => __( 'Display Elements', 'jupiterx' ),
] );

// Display elements.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-multicheck',
	'settings' => 'jupiterx_product_page_elements',
	'section'  => $section,
	'css_var'  => 'product-page-elements',
	'default'  => [
		'categories',
		'tags',
		'sku',
		'short_description',
		'quantity',
		'social_share',
		'description_tab',
		'review_tab',
		'additional_info_tab',
		'sale_badge',
		'out_of_stock_badge',
		'rating',
	],
	'choices'  => [
		'categories'          => __( 'Categories', 'jupiterx' ),
		'tags'                => __( 'Tags', 'jupiterx' ),
		'sku'                 => __( 'SKU', 'jupiterx' ),
		'short_description'   => __( 'Short Description', 'jupiterx' ),
		'quantity'            => __( 'Quantity', 'jupiterx' ),
		'social_share'        => __( 'Social Share', 'jupiterx' ),
		'description_tab'     => __( 'Description Tab', 'jupiterx' ),
		'review_tab'          => __( 'Review Tab', 'jupiterx' ),
		'additional_info_tab' => __( 'Additional Info Tab', 'jupiterx' ),
		'sale_badge'          => __( 'Sale Badge', 'jupiterx' ),
		'out_of_stock_badge'  => __( 'Out of Stock Badge', 'jupiterx' ),
		'rating'              => __( 'Rating', 'jupiterx' ),
	],
] );

// Image lightbox.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_image_lightbox',
	'section'  => $section,
	'label'    => __( 'Image Lightbox', 'jupiterx' ),
	'column'   => '6',
	'default'  => true,
] );

// Image zoom.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_image_zoom',
	'section'  => $section,
	'label'    => __( 'Image Zoom', 'jupiterx' ),
	'column'   => '6',
	'default'  => true,
] );

// Full width.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_full_width',
	'section'  => $section,
	'label'    => __( 'Full Width', 'jupiterx' ),
	'column'   => '6',
	'default'  => false,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_product_page_template',
			'operator' => 'contains',
			'value'    => [ '1', '3', '5', '7', '9' ],
		],
	],
] );

// Related products.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_related_products',
	'section'  => $section,
	'label'    => __( 'Related Products', 'jupiterx' ),
	'column'   => '6',
	'default'  => true,
] );

// Upsells products.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_upsells_products',
	'section'  => $section,
	'label'    => __( 'Upsells Products', 'jupiterx' ),
	'column'   => '6',
	'default'  => true,
] );

// Sticky product info.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_sticky_product_info',
	'section'  => $section,
	'label'    => __( 'Sticky Product Info', 'jupiterx' ),
	'column'   => '6',
	'default'  => false,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_product_page_template',
			'operator' => 'contains',
			'value'    => [ '9', '10' ],
		],
	],
] );

// Layout.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_product_page_layout',
	'section'     => $section,
	'label'       => __( 'Layout', 'jupiterx' ),
	'column'      => 6,
	'placeholder' => __( 'Default', 'jupiterx' ),
	'default'     => 'c',
	'choices'     => JupiterX_Customizer_Utils::get_layouts(),
	'active_callback' => [
		[
			'setting'  => 'jupiterx_product_page_template',
			'operator' => 'contains',
			'value'    => [ '1', '3', '5', '7', '9', '10' ],
		],
	],
] );
