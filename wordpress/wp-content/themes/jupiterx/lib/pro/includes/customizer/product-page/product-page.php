<?php
/**
 * Customizer settings for Product Page.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	$popups = [
		'image'             => __( 'Image', 'jupiterx' ),
		'name'              => __( 'Name', 'jupiterx' ),
		'regular_price'     => __( 'Regular Price', 'jupiterx' ),
		'sale_price'        => __( 'Sale Price', 'jupiterx' ),
		'rating'            => __( 'Rating', 'jupiterx' ),
		'category'          => __( 'Category', 'jupiterx' ),
		'tags'              => __( 'Tags', 'jupiterx' ),
		'sku'               => __( 'SKU', 'jupiterx' ),
		'short_description' => __( 'Short Description', 'jupiterx' ),
		'variations'        => __( 'Variations', 'jupiterx' ),
		'quantity'          => __( 'Quantity', 'jupiterx' ),
		'add_cart_button'   => __( 'Add to Cart Button', 'jupiterx' ),
		'social_share'      => __( 'Social Share', 'jupiterx' ),
		'tabs'              => __( 'Tabs', 'jupiterx' ),
		'sale_badge'        => __( 'Sale Badge', 'jupiterx' ),
		'outstock_badge'    => __( 'Out of Stock', 'jupiterx' ),
	];

	// Product page popup.
	JupiterX_Customizer::update_section( 'jupiterx_product_page', [
		'popups' => $popups,
		'tabs'   => [
			'settings' => __( 'Settings', 'jupiterx' ),
			'styles'   => __( 'Styles', 'jupiterx' ),
		],
	] );

	// Styles tab > Child popups.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-child-popup',
		'settings' => 'jupiterx_product_page_styles_popups',
		'section'  => 'jupiterx_product_page_styles',
		'target'   => 'jupiterx_product_page',
		'choices'  => $popups,
	] );

	// Create popup children.
	foreach ( $popups as $popup_id => $label ) {
		JupiterX_Customizer::add_section( 'jupiterx_product_page_' . $popup_id, [
			'popup' => 'jupiterx_product_page',
			'type'  => 'pane',
			'pane'  => [
				'type' => 'popup',
				'id'   => $popup_id,
			],
		] );
	}

	// Template.
	JupiterX_Customizer::update_field( 'jupiterx_product_page_template', [
		'choices' => [
			'1'  => 'product-page-01',
			'3'  => 'product-page-03',
			'4'  => 'product-page-04',
			'5'  => 'product-page-05',
			'7'  => 'product-page-07',
			'8'  => 'product-page-08',
			'9'  => 'product-page-09',
			'10' => 'product-page-10',
		],
	] );
} );

add_action( 'jupiterx_after_customizer_register', function() {
	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_product_page_styles_pro_box' );
} );

// Image.
add_action( 'jupiterx_after_customizer_register', function() {
	// Main Image Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_image_main_background_color',
		'section'   => 'jupiterx_product_page_image',
		'css_var'   => 'product-page-image-main-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'column'    => '4',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce:not(.jupiterx-product-template-9):not(.jupiterx-product-template-10) div.product div.woocommerce-product-gallery .flex-viewport, .woocommerce:not(.jupiterx-product-template-9):not(.jupiterx-product-template-10) div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image',
				'property' => 'background-color',
			],
			[
				'element'     => '.woocommerce.jupiterx-product-template-9 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image, .woocommerce.jupiterx-product-template-10 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image',
				'property'    => 'background-color',
				'media_query' => '@media (min-width: 992px)',
			],
			[
				'element'     => '.woocommerce.jupiterx-product-template-9 div.product div.woocommerce-product-gallery .flex-viewport, .woocommerce.jupiterx-product-template-9 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image, .woocommerce.jupiterx-product-template-10 div.product div.woocommerce-product-gallery .flex-viewport, .woocommerce.jupiterx-product-template-10 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image',
				'property'    => 'background-color',
				'media_query' => '@media (max-width: 991px)',
			],
		],
	] );

	// Min height.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_product_page_image_min_height',
		'section'     => 'jupiterx_product_page_image',
		'css_var'     => 'product-page-image-min-height',
		'label'       => __( 'Min Height', 'jupiterx' ),
		'column'      => '4',
		'input_attrs' => [ 'placeholder' => 'auto' ],
		'transport'   => 'postMessage',
		'default'     => [
			'unit' => '-',
		],
		'units'       => [ '-', 'px', 'vh' ],
		'output'      => [
			[
				'element'       => '.single-product .woocommerce-product-gallery__image img',
				'property'      => 'min-height',
			],
		],
	] );

	// Max height.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_product_page_image_max_height',
		'section'     => 'jupiterx_product_page_image',
		'css_var'     => 'product-page-image-max-height',
		'label'       => __( 'Max Height', 'jupiterx' ),
		'column'      => '4',
		'input_attrs' => [ 'placeholder' => 'auto' ],
		'transport'   => 'postMessage',
		'default'     => [
			'unit' => '-',
		],
		'units'       => [ '-', 'px', 'vh' ],
		'output'     => [
			[
				'element'       => '.woocommerce div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image img',
				'property'      => 'max-height',
			],
		],
	] );

	// Main Image Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_image_main_border',
		'section'   => 'jupiterx_product_page_image',
		'css_var'   => 'product-page-image-main-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'default'   => [
			'width' => [
				'size' => '0',
				'unit' => 'px',
			],
		],
		'output'    => [
			[
				'element'  => '.woocommerce:not(.jupiterx-product-template-9):not(.jupiterx-product-template-10) div.product div.woocommerce-product-gallery .flex-viewport, .woocommerce:not(.jupiterx-product-template-9):not(.jupiterx-product-template-10) div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image',
			],
			[
				'element'     => '.woocommerce.jupiterx-product-template-9 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image, .woocommerce.jupiterx-product-template-10 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image',
				'media_query' => '@media (min-width: 992px)',
			],
			[
				'element'     => '.woocommerce.jupiterx-product-template-9 div.product div.woocommerce-product-gallery .flex-viewport, .woocommerce.jupiterx-product-template-9 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image, .woocommerce.jupiterx-product-template-10 div.product div.woocommerce-product-gallery .flex-viewport, .woocommerce.jupiterx-product-template-10 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image',
				'media_query' => '@media (max-width: 991px)',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-divider',
		'settings'        => 'jupiterx_product_page_image_divider_1',
		'section'         => 'jupiterx_product_page_image',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_template',
				'operator' => 'contains',
				'value'    => [ '1', '2', '3', '4', '5', '6', '7', '8' ],
			],
		],
	] );

	// Image Gallery Orientation.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-choose',
		'settings'        => 'jupiterx_product_page_image_gallery_orientation',
		'section'         => 'jupiterx_product_page_image',
		'label'           => __( 'Gallery Thumbnail Orientation', 'jupiterx' ),
		'default'         => 'horizontal',
		'choices'         => [
			'vertical'    => [
				'icon' => 'gallery-thumbnail-vertical',
			],
			'horizontal'  => [
				'icon' => 'gallery-thumbnail-horizontal',
			],
			'none' => [
				'icon' => 'gallery-thumbnail-none',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_template',
				'operator' => 'contains',
				'value'    => [ '1', '2', '3', '4', '5', '6', '7', '8' ],
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_image_divider_2',
		'section'  => 'jupiterx_product_page_image',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_image_spacing',
		'section'   => 'jupiterx_product_page_image',
		'css_var'   => 'product-page-image',
		'exclude'   => [ 'padding' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.product div.woocommerce-product-gallery',
			],
		],
	] );
} );

// Name.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_name_typography',
		'section'    => 'jupiterx_product_page_name',
		'responsive' => true,
		'css_var'    => 'product-page-name',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform' ],
		'output'     => [
			[
				'element' => '.single-product div.product .product_title',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_name_divider',
		'section'  => 'jupiterx_product_page_name',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_name_spacing',
		'section'   => 'jupiterx_product_page_name',
		'css_var'   => 'product-page-name',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.single-product div.product .product_title',
			],
		],
	] );
} );

// Regular Price.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_regular_price_typography',
		'section'    => 'jupiterx_product_page_regular_price',
		'responsive' => true,
		'css_var'    => 'product-page-regular-price',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform', 'line_height' ],
		'output'     => [
			[
				'element' => '.single-product div.product .summary p.price, .single-product div.product .summary span.price',
			],
		],
	] );

	// Text decoration.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-select',
		'settings'  => 'jupiterx_product_page_regular_price_text_decoration',
		'section'   => 'jupiterx_product_page_regular_price',
		'css_var'   => 'product-page-regular-price-text-decoration',
		'column'    => '5',
		'icon'      => 'text-decoration',
		'default'   => 'none',
		'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.single-product div.product .summary p.price > span, .single-product div.product .summary span.price > span',
				'property' => 'text-decoration',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_regular_price_divider',
		'section'  => 'jupiterx_product_page_regular_price',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_regular_price_spacing',
		'section'   => 'jupiterx_product_page_regular_price',
		'css_var'   => 'product-page-regular-price',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.single-product div.product .summary p.price, .single-product div.product .summary span.price',
			],
		],
	] );
} );

// Sale Price.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_product_page_sale_price_typography',
		'section'   => 'jupiterx_product_page_sale_price',
		'css_var'   => 'product-page-sale-price',
		'transport' => 'postMessage',
		'exclude'   => [ 'text_transform', 'line_height' ],
		'default'   => [
			'desktop' => [
				'color' => '#212529',
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce.single-product div.product.sale .summary p.price ins, .woocommerce.single-product div.product.sale .summary span.price ins',
			],
		],
	] );

	// Text decoration.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-select',
		'settings'  => 'jupiterx_product_page_sale_price_text_decoration',
		'section'   => 'jupiterx_product_page_sale_price',
		'css_var'   => 'product-page-sale-price-text-decoration',
		'column'    => '5',
		'icon'      => 'text-decoration',
		'default'   => 'none',
		'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce.single-product div.product.sale .summary p.price ins, .woocommerce.single-product div.product.sale .summary span.price ins',
				'property' => 'text-decoration',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_sale_price_divider',
		'section'  => 'jupiterx_product_page_sale_price',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_sale_price_spacing',
		'section'   => 'jupiterx_product_page_sale_price',
		'css_var'   => 'product-page-sale-price',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.single-product div.product.sale .summary p.price ins, .single-product div.product.sale .summary span.price ins',
			],
		],
	] );
} );

// Rating.
add_action( 'jupiterx_after_customizer_register', function() {
	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_rating_label_1',
		'section'  => 'jupiterx_product_page_rating',
		'label'    => __( 'Icon', 'jupiterx' ),
	] );

	// Size.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_product_page_rating_icon_size',
		'section'     => 'jupiterx_product_page_rating',
		'css_var'     => 'product-page-rating-icon-size',
		'column'      => '4',
		'icon'        => 'font-size',
		'units'       => [ 'px', 'em', 'rem' ],
		'transport'   => 'postMessage',
		'output'   => [
			[
				'element'  => '.single-product .woocommerce-product-rating .star-rating',
				'property' => 'font-size',
			],
		],
	] );

	// Icon Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_rating_icon_color',
		'section'   => 'jupiterx_product_page_rating',
		'css_var'   => 'product-page-rating-icon-color',
		'column'    => '3',
		'icon'      => 'icon-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product .woocommerce-product-rating .star-rating:before',
				'property' => 'color',
			],
		],
	] );

	// Active label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Active', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'green',
		'settings' => 'jupiterx_product_page_rating_label_2',
		'section'  => 'jupiterx_product_page_rating',
	] );

	// Icon color active.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_rating_icon_color_active',
		'section'   => 'jupiterx_product_page_rating',
		'css_var'   => 'product-page-rating-icon-color-active',
		'column'    => '3',
		'icon'      => 'icon-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product .woocommerce-product-rating .star-rating span',
				'property' => 'color',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_rating_divider_1',
		'section'  => 'jupiterx_product_page_rating',
	] );

	// Link typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_rating_link_typography',
		'section'    => 'jupiterx_product_page_rating',
		'responsive' => true,
		'css_var'    => 'product-page-rating-link',
		'label'      => __( 'Link', 'jupiterx' ),
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform' ],
		'output'     => [
			[
				'element'  => '.single-product .woocommerce-review-link',
			],
		],
	] );

	// Hover label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Hover', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'orange',
		'settings' => 'jupiterx_product_page_rating_label_3',
		'section'  => 'jupiterx_product_page_rating',
	] );

	// Icon Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_rating_link_color_hover',
		'section'   => 'jupiterx_product_page_rating',
		'css_var'   => 'product-page-rating-link-color-hover',
		'column'    => '3',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product .woocommerce-review-link:hover',
				'property' => 'color',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_rating_divider_2',
		'section'  => 'jupiterx_product_page_rating',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_rating_spacing',
		'section'   => 'jupiterx_product_page_rating',
		'css_var'   => 'product-page-rating',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.single-product .woocommerce-product-rating',
			],
		],
	] );
} );

// Category.
add_action( 'jupiterx_after_customizer_register', function() {
	// Title typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_category_title_typography',
		'section'    => 'jupiterx_product_page_category',
		'responsive' => true,
		'css_var'    => 'product-page-category-title',
		'label'      => __( 'Title', 'jupiterx' ),
		'exclude'    => [ 'line_height' ],
		'transport'  => 'postMessage',
		'output'     => [
			[
				'element' => '.single-product div.product .product_meta span.posted_in .jupiterx-product-category-title',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_category_divider_1',
		'section'  => 'jupiterx_product_page_category',
	] );

	// Text typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_category_text_typography',
		'section'    => 'jupiterx_product_page_category',
		'responsive' => true,
		'css_var'    => 'product-page-category-text',
		'label'      => __( 'Text', 'jupiterx' ),
		'exclude'    => [ 'line_height', 'text_transform' ],
		'transport'  => 'postMessage',
		'output'     => [
			[
				'element' => '.single-product div.product .product_meta span.product-categories, .single-product div.product .product_meta span.posted_in a',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_category_divider_2',
		'section'  => 'jupiterx_product_page_category',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_category_spacing',
		'section'   => 'jupiterx_product_page_category',
		'css_var'   => 'product-page-category',
		'exclude'   => [ 'padding' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.single-product div.product .product_meta span.posted_in',
			],
		],
	] );
} );

// Tags.
add_action( 'jupiterx_after_customizer_register', function() {
	// Title.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_tags_title_typography',
		'section'    => 'jupiterx_product_page_tags',
		'responsive' => true,
		'css_var'    => 'product-page-tags-title',
		'label'      => __( 'Title', 'jupiterx' ),
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.single-product div.product .product_meta span.tagged_as .jupiterx-product-tag-title',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_tags_divider_1',
		'section'  => 'jupiterx_product_page_tags',
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_tags_text_typography',
		'section'    => 'jupiterx_product_page_tags',
		'responsive' => true,
		'css_var'    => 'product-page-tags-text',
		'label'      => __( 'Text', 'jupiterx' ),
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform' ],
		'output'     => [
			[
				'element' => '.single-product div.product .product_meta span.tagged_as span.product-tags, .single-product div.product .product_meta span.tagged_as a',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_tags_divider_2',
		'section'  => 'jupiterx_product_page_tags',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_tags_spacing',
		'section'   => 'jupiterx_product_page_tags',
		'css_var'   => 'product-page-tags',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.single-product div.product .product_meta span.tagged_as',
			],
		],
	] );
} );

// SKU.
add_action( 'jupiterx_after_customizer_register', function() {
	// Title typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_sku_title_typography',
		'section'    => 'jupiterx_product_page_sku',
		'responsive' => true,
		'css_var'    => 'product-page-sku-title',
		'label'      => __( 'Title', 'jupiterx' ),
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.single-product div.product .product_meta span.sku_wrapper .jupiterx-product-sku-title',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_sku_divider_1',
		'section'  => 'jupiterx_product_page_sku',
	] );

	// Text typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_sku_text_typography',
		'section'    => 'jupiterx_product_page_sku',
		'responsive' => true,
		'css_var'    => 'product-page-sku-text',
		'label'      => __( 'Text', 'jupiterx' ),
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform' ],
		'output'     => [
			[
				'element' => '.single-product div.product .product_meta span.sku_wrapper .sku',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_sku_divider_2',
		'section'  => 'jupiterx_product_page_sku',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_sku_spacing',
		'section'   => 'jupiterx_product_page_sku',
		'css_var'   => 'product-page-sku',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.single-product div.product .product_meta span.sku_wrapper',
			],
		],
	] );
} );

// Short Description.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_short_description_typography',
		'section'    => 'jupiterx_product_page_short_description',
		'responsive' => true,
		'css_var'    => 'product-page-short-description',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform' ],
		'output'     => [
			[
				'element' => implode( ',', [
					'.woocommerce div.product .woocommerce-product-details__short-description p',
					'.woocommerce div.product .woocommerce-product-details__short-description h1',
					'.woocommerce div.product .woocommerce-product-details__short-description h2',
					'.woocommerce div.product .woocommerce-product-details__short-description h3',
					'.woocommerce div.product .woocommerce-product-details__short-description h4',
					'.woocommerce div.product .woocommerce-product-details__short-description h5',
					'.woocommerce div.product .woocommerce-product-details__short-description h6',
				] ),
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_short_description_divider',
		'section'  => 'jupiterx_product_page_short_description',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_short_description_spacing',
		'section'   => 'jupiterx_product_page_short_description',
		'css_var'   => 'product-page-short-description',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product .woocommerce-product-details__short-description',
			],
		],
	] );
} );

// Variations.
add_action( 'jupiterx_after_customizer_register', function() {
	// Title.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_variations_title_typography',
		'section'    => 'jupiterx_product_page_variations',
		'responsive' => true,
		'css_var'    => 'product-page-variations-title',
		'label'      => __( 'Title', 'jupiterx' ),
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce div.product form.cart .variations label',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_variations_divider_1',
		'section'  => 'jupiterx_product_page_variations',
	] );

	// Box.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_variations_select_typography',
		'section'    => 'jupiterx_product_page_variations',
		'responsive' => true,
		'css_var'    => 'product-page-variations-select',
		'label'      => __( 'Box', 'jupiterx' ),
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce div.product form.cart .variations select',
			],
		],
	] );

	// Box Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_variations_select_background_color',
		'section'   => 'jupiterx_product_page_variations',
		'css_var'   => 'product-page-variations-select-background-color',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart .variations select, .woocommerce div.product form.cart .variations .btn',
				'property' => 'background-color',
			],
		],
	] );

	// Box Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_variations_select_border',
		'section'   => 'jupiterx_product_page_variations',
		'css_var'   => 'product-page-variations-select-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart .variations select, .woocommerce div.product form.cart .variations .btn',
			],
		],
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_variations_select_spacing',
		'section'   => 'jupiterx_product_page_variations',
		'css_var'   => 'product-page-variations-select',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart .variations select, .woocommerce div.product form.cart .variations .btn',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_variations_divider_2',
		'section'  => 'jupiterx_product_page_variations',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_variations_spacing',
		'section'   => 'jupiterx_product_page_variations',
		'css_var'   => 'product-page-variations',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart .variations',
			],
		],
	] );
} );

// Quantity.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_quantity_input_typography',
		'section'    => 'jupiterx_product_page_quantity',
		'responsive' => true,
		'css_var'    => 'product-page-quantity-input',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'letter_spacing', 'text_transform' ],
		'output'     => [
			[
				'element' => '.woocommerce div.product form.cart div.quantity input, .woocommerce div.product form.cart div.quantity .btn',
			],
		],
	] );

	// Input Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_quantity_input_background_color',
		'section'   => 'jupiterx_product_page_quantity',
		'css_var'   => 'product-page-quantity-input-background-color',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart div.quantity input, .woocommerce div.product form.cart div.quantity .btn',
				'property' => 'background-color',
			],
		],
	] );

	// Input Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_quantity_input_border',
		'section'   => 'jupiterx_product_page_quantity',
		'css_var'   => 'product-page-quantity-input-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart div.quantity input, .woocommerce div.product form.cart div.quantity .btn',
			],
		],
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_quantity_input_spacing',
		'section'   => 'jupiterx_product_page_quantity',
		'css_var'   => 'product-page-quantity-input',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'default'   => [
			'desktop' => [
				'padding_top' => 0.5,
				jupiterx_get_direction( 'padding_right' ) => 0.75,
				'padding_bottom' => 0.5,
				jupiterx_get_direction( 'padding_left' ) => 0.75,
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart div.quantity .btn',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_quantity_divider_1',
		'section'  => 'jupiterx_product_page_quantity',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_quantity_spacing',
		'section'   => 'jupiterx_product_page_quantity',
		'css_var'   => 'product-page-quantity',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart div.quantity',
			],
		],
	] );
} );

// Add to Cart Button.
add_action( 'jupiterx_after_customizer_register', function() {
	// Icon.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_product_page_add_cart_button_icon',
		'section'   => 'jupiterx_product_page_add_cart_button',
		'css_var'   => 'product-page-add-cart-button-icon',
		'label'     => __( 'Icon', 'jupiterx' ),
		'column'    => '3',
		'default'   => true,
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.single-product div.product .single_add_to_cart_button:before',
				'property'      => 'display',
				'exclude'       => [ true ],
				'value_pattern' => 'none',
			],
		],
	] );

	// Full width.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_product_page_add_cart_button_full_width',
		'section'   => 'jupiterx_product_page_add_cart_button',
		'css_var'   => 'product-page-add-cart-button-full-width',
		'label'     => __( 'Full Width', 'jupiterx' ),
		'column'    => '3',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.single-product div.product .single_add_to_cart_button',
				'property'      => 'width',
				'exclude'       => [ false ],
				'value_pattern' => '100',
				'units'         => '%',
			],
		],
	] );

	// Typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_product_page_add_cart_button_typography',
		'section'   => 'jupiterx_product_page_add_cart_button',
		'css_var'   => 'product-page-add-cart-button',
		'exclude'   => [ 'line_height' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.single-product div.product .single_add_to_cart_button',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-color',
		'settings' => 'jupiterx_product_page_add_cart_button_background_color',
		'section'  => 'jupiterx_product_page_add_cart_button',
		'css_var'  => 'product-page-add-cart-button-background-color',
		'icon'     => 'background-color',
		'transport' => 'postMessage',
		'output'   => [
			[
				'element'  => '.single-product div.product .single_add_to_cart_button, .single-product div.product .alt.single_add_to_cart_button',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-border',
		'settings' => 'jupiterx_product_page_add_cart_button_border',
		'section'  => 'jupiterx_product_page_add_cart_button',
		'css_var'  => 'product-page-add-cart-button-border',
		'exclude'  => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'output'   => [
			[
				'element' => '.single-product div.product .single_add_to_cart_button',
			],
		],
	] );

	// Hover label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Hover', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'orange',
		'settings' => 'jupiterx_product_page_add_cart_button_label_1',
		'section'  => 'jupiterx_product_page_add_cart_button',
	] );

	// Text color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_add_cart_button_text_color_hover',
		'section'   => 'jupiterx_product_page_add_cart_button',
		'css_var'   => 'product-page-add-cart-button-text-color-hover',
		'column'    => '3',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product div.product .single_add_to_cart_button:hover',
				'property' => 'color',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_add_cart_button_background_color_hover',
		'section'   => 'jupiterx_product_page_add_cart_button',
		'css_var'   => 'product-page-add-cart-button-background-color-hover',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product div.product .single_add_to_cart_button:hover',
				'property' => 'background-color',
			],
		],
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_add_cart_button_border_color_hover',
		'section'   => 'jupiterx_product_page_add_cart_button',
		'css_var'   => 'product-page-add-cart-button-border-color-hover',
		'column'    => '3',
		'icon'      => 'border-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product div.product .single_add_to_cart_button:hover',
				'property' => 'border-color',
			],
		],
	] );

	// Icon color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_add_cart_button_icon_color_hover',
		'section'   => 'jupiterx_product_page_add_cart_button',
		'css_var'   => 'product-page-add-cart-button-icon-color-hover',
		'column'    => '3',
		'icon'      => 'icon-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product div.product .single_add_to_cart_button:hover:before',
				'property' => 'color',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_add_cart_button_divider_3',
		'section'  => 'jupiterx_product_page_add_cart_button',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_add_cart_button_spacing',
		'section'   => 'jupiterx_product_page_add_cart_button',
		'css_var'   => 'product-page-add-cart-button',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.single-product div.product .single_add_to_cart_button',
			],
		],
	] );
} );

// Social Share.
add_action( 'jupiterx_after_customizer_register', function() {
	// Social Network Filter.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-multicheck',
		'settings' => 'jupiterx_product_page_social_share_filter',
		'section'  => 'jupiterx_product_page_social_share',
		'default'  => [
			'facebook',
			'twitter',
			'pinterest',
			'linkedin',
			'google-plus',
			'reddit',
			'digg',
			'email',
		],
		'icon_choices'  => [
			'facebook'    => 'share-facebook-f',
			'twitter'     => 'share-twitter',
			'pinterest'   => 'share-pinterest-p',
			'linkedin'    => 'share-linkedin-in',
			'google-plus' => 'share-google-plus-g',
			'reddit'      => 'share-reddit-alien',
			'digg'        => 'share-digg',
			'email'       => 'share-email',
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_social_share_divider_1',
		'section'  => 'jupiterx_product_page_social_share',
	] );

	// Icon Size Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_social_share_label',
		'section'  => 'jupiterx_product_page_social_share',
		'label'    => __( 'Icon Size', 'jupiterx' ),
	] );

	// Font Size.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_product_page_social_share-link_font_size',
		'section'     => 'jupiterx_product_page_social_share',
		'css_var'     => 'product-page-social-share-link-font-size',
		'column'      => '4',
		'units'       => [ 'px', 'em', 'rem' ],
		'default'     => [
			'size' => 1,
			'unit' => 'rem',
		],
		'transport'   => 'postMessage',
		'output'      => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share a',
				'property' => 'font-size',
			],
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share .jupiterx-icon::before',
				'property' => 'width',
			],
		],
	] );

	// Gutter Size.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_product_page_social_share-link_gutter_size',
		'section'     => 'jupiterx_product_page_social_share',
		'css_var'     => 'product-page-social-share-link-gutter-size',
		'column'      => '4',
		'icon'        => 'letter-spacing',
		'units'       => [ 'px', 'em', 'rem' ],
		'transport'   => 'postMessage',
		'output'      => [
			[
				'element'       => '.woocommerce div.product .jupiterx-social-share .jupiterx-social-share-inner',
				'property'      => 'margin',
				'value_pattern' => '0 calc(-$ / 2)',
			],
			[
				'element'       => '.woocommerce div.product .jupiterx-social-share a',
				'property'      => 'margin',
				'value_pattern' => '0 calc($ / 2) $',
			],
		],
	] );

	// Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_social_share-link_color',
		'section'   => 'jupiterx_product_page_social_share',
		'css_var'   => 'product-page-social-share-link-color',
		'column'    => '3',
		'icon'      => 'icon-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share a',
				'property' => 'color',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_social_share-link_background_color',
		'section'   => 'jupiterx_product_page_social_share',
		'css_var'   => 'product-page-social-share-link-background-color',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share a',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_social_share-link_border',
		'section'   => 'jupiterx_product_page_social_share',
		'css_var'   => 'product-page-social-share-link-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share a',
			],
		],
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_social_share_link_spacing',
		'section'   => 'jupiterx_product_page_social_share',
		'css_var'   => 'product-page-social-share-link',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'output'    => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share a',
			],
		],
		'default' => [
			'desktop' => [
				'padding_top'    => 0.5,
				jupiterx_get_direction( 'padding_right' ) => 0.5,
				'padding_bottom' => 0.5,
				jupiterx_get_direction( 'padding_left' ) => 0.5,
				'padding_unit'   => 'em',
			],
		],
	] );

	// Fancy.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'settings'   => 'jupiterx_product_page_social_share_hover',
		'section'    => 'jupiterx_product_page_social_share',
		'label'      => __( 'Hover', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'orange',
	] );

	// Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_social_share_link_hover_color',
		'section'   => 'jupiterx_product_page_social_share',
		'css_var'   => 'product-page-social-share-link-hover-color',
		'column'    => '3',
		'icon'      => 'icon-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share a:hover',
				'property' => 'color',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_social_share_link_hover_background_color',
		'section'   => 'jupiterx_product_page_social_share',
		'css_var'   => 'product-page-social-share-link-hover-background-color',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share a:hover',
				'property' => 'background-color',
			],
		],
	] );

	// Border Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_social_share_link_hover_border_color',
		'section'   => 'jupiterx_product_page_social_share',
		'css_var'   => 'product-page-social-share-link-hover-border-color',
		'column'    => '3',
		'icon'      => 'border-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share a:hover',
				'property' => 'border-color',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_social_share_divider_2',
		'section'  => 'jupiterx_product_page_social_share',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_social_share_spacing',
		'section'   => 'jupiterx_product_page_social_share',
		'css_var'   => 'product-page-social-share',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share',
			],
		],
	] );
} );

// Tabs.
add_action( 'jupiterx_after_customizer_register', function() {
	// Title.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_tabs_title_typography',
		'section'    => 'jupiterx_product_page_tabs',
		'responsive' => true,
		'css_var'    => 'product-page-tabs-title',
		'label'      => __( 'Title', 'jupiterx' ),
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce div.product .woocommerce-tabs ul.tabs li a, .woocommerce div.product .woocommerce-tabs ul.tabs li:not(.active) a:hover',
			],
			[
				'element' => '.woocommerce div.product .woocommerce-tabs.accordion .card-title',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_tabs_title_background_color',
		'section'   => 'jupiterx_product_page_tabs',
		'css_var'   => 'product-page-tabs-title-background-color',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'default'   => '#fff',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li',
				'property' => 'background-color',
			],
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs.accordion .card-header',
				'property' => 'background-color',
			],
		],
	] );

	// Active label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Active', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'orange',
		'settings' => 'jupiterx_product_page_tabs_label_1',
		'section'  => 'jupiterx_product_page_tabs',
	] );

	// Text color active.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_tabs_title_color_active',
		'section'   => 'jupiterx_product_page_tabs',
		'css_var'   => 'product-page-tabs-title-color-active',
		'column'    => '3',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active a',
				'property' => 'color',
			],
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs.accordion .card-header:not(.collapsed) .card-title',
				'property' => 'color',
			],
		],
	] );

	// Background color active.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_tabs_title_background_color_active',
		'section'   => 'jupiterx_product_page_tabs',
		'css_var'   => 'product-page-tabs-title-background-color-active',
		'column'    => '3',
		'default'   => '#fff',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active',
				'property' => 'background-color',
			],
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs.accordion .card-header:not(.collapsed)',
				'property' => 'background-color',
			],
		],
	] );

	// Icon color active.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-color',
		'settings'        => 'jupiterx_product_page_tabs_title_icon_color_active',
		'section'         => 'jupiterx_product_page_tabs',
		'css_var'         => 'product-page-tabs-title-icon-color-active',
		'column'          => '3',
		'icon'            => 'icon-color',
		'transport'       => 'postMessage',
		'output'          => [
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs.accordion span[class*="jupiterx-icon"]',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_template',
				'operator' => 'contains',
				'value'    => [ '3', '4', '5', '9', '10' ],
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_tabs_divider_1',
		'section'  => 'jupiterx_product_page_tabs',
	] );

	// Text.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_tabs_text_typography',
		'section'    => 'jupiterx_product_page_tabs',
		'responsive' => true,
		'css_var'    => 'product-page-tabs-text',
		'label'      => __( 'Text', 'jupiterx' ),
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform' ],
		'output'     => [
			[
				'element' => '.woocommerce div.product .woocommerce-tabs .panel, .woocommerce div.product .woocommerce-tabs .panel p',
			],
			[
				'element' => '.woocommerce div.product .woocommerce-tabs.accordion .card-body, .woocommerce div.product .woocommerce-tabs.accordion .card-body p',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_tabs_divider_2',
		'section'  => 'jupiterx_product_page_tabs',
	] );

	// Box label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Box', 'jupiterx' ),
		'settings' => 'jupiterx_product_page_tabs_label_2',
		'section'  => 'jupiterx_product_page_tabs',
	] );

	// Box background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_tabs_box_background_color',
		'section'   => 'jupiterx_product_page_tabs',
		'css_var'   => 'product-page-tabs-box-background-color',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs .panel',
				'property' => 'background-color',
			],
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs.accordion .card-body',
				'property' => 'background-color',
			],
		],
	] );

	// Box border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_tabs_box_border',
		'section'   => 'jupiterx_product_page_tabs',
		'css_var'   => 'product-page-tabs-box-border',
		'exclude'   => [ 'style', 'size', 'radius' ],
		'transport' => 'postMessage',
		'default'   => [
			'width' => [
				'size' => 1,
				'unit' => 'px',
			],
			'color' => '#d3ced2', // WooCommerce border color.
		],
		'output'    => [
			[
				'element' => '.woocommerce div.product .woocommerce-tabs .panel, .woocommerce div.product .woocommerce-tabs ul.tabs:before, .woocommerce div.product .woocommerce-tabs ul.tabs li, .woocommerce div.product .woocommerce-tabs ul.tabs li.active',
			],
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active',
				'property' => 'border-width',
				'choice'   => 'width',
			],
			[
				'element' => '.woocommerce div.product .woocommerce-tabs.accordion .card, .woocommerce div.product .woocommerce-tabs.accordion .card-header',
			],
		],
	] );

	// Box spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_tabs_box_spacing',
		'section'   => 'jupiterx_product_page_tabs',
		'css_var'   => 'product-page-tabs-box',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product .woocommerce-tabs .panel',
			],
			[
				'element' => '.woocommerce div.product .woocommerce-tabs.accordion .card-body',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_tabs_divider_3',
		'section'  => 'jupiterx_product_page_tabs',
	] );

	// Tabs wrapper spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_tabs_spacing',
		'section'   => 'jupiterx_product_page_tabs',
		'css_var'   => 'product-page-tabs',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'default'   => [
			'desktop' => [
				'margin_bottom' => 5,
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce div.product .woocommerce-tabs',
			],
			[
				'element' => '.woocommerce div.product .woocommerce-tabs.accordion',
			],
		],
	] );
} );

// Sale Badge.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_sale_badge_typography',
		'section'    => 'jupiterx_product_page_sale_badge',
		'responsive' => true,
		'css_var'    => 'product-page-sale-badge',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.single-product div.product .jupiterx-product-badges .onsale',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_sale_badge_background_color',
		'section'   => 'jupiterx_product_page_sale_badge',
		'css_var'   => 'product-page-sale-badge-background-color',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product div.product .jupiterx-product-badges .onsale',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_sale_badge_border',
		'section'   => 'jupiterx_product_page_sale_badge',
		'css_var'   => 'product-page-sale-badge-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'default'   => [
			'width'  => [
				'size' => '0',
				'unit' => 'px',
			],
			'radius' => [
				'size' => 4,
				'unit' => 'px',
			],
		],
		'output'    => [
			[
				'element' => '.single-product div.product .jupiterx-product-badges .onsale',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_sale_badge_divider_3',
		'section'  => 'jupiterx_product_page_sale_badge',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_sale_badge_spacing',
		'section'   => 'jupiterx_product_page_sale_badge',
		'css_var'   => 'product-page-sale-badge',
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'margin_bottom' => 1.5,
			],
		],
		'output'    => [
			[
				'element' => '.single-product div.product .jupiterx-product-badges .onsale',
			],
		],
	] );
} );

// Out of Stock Badge.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_outstock_badge_typography',
		'section'    => 'jupiterx_product_page_outstock_badge',
		'responsive' => true,
		'css_var'    => 'product-page-outstock-badge',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.single-product div.product .jupiterx-product-badges .jupiterx-out-of-stock',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_outstock_badge_background_color',
		'section'   => 'jupiterx_product_page_outstock_badge',
		'css_var'   => 'product-page-outstock-badge-background-color',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product div.product .jupiterx-product-badges .jupiterx-out-of-stock',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_outstock_badge_border',
		'section'   => 'jupiterx_product_page_outstock_badge',
		'css_var'   => 'product-page-outstock-badge-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'default'   => [
			'width'  => [
				'size' => '0',
				'unit' => 'px',
			],
			'radius' => [
				'size' => 4,
				'unit' => 'px',
			],
		],
		'output'    => [
			[
				'element' => '.single-product div.product .jupiterx-product-badges .jupiterx-out-of-stock',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_outstock_badge_divider_3',
		'section'  => 'jupiterx_product_page_outstock_badge',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_outstock_badge_spacing',
		'section'   => 'jupiterx_product_page_outstock_badge',
		'css_var'   => 'product-page-outstock-badge',
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'margin_bottom' => 1.5,
			],
		],
		'output'    => [
			[
				'element' => '.single-product div.product .jupiterx-product-badges .jupiterx-out-of-stock',
			],
		],
	] );
} );
