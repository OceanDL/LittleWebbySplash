<?php
/**
 * Add Jupiter settings for Title Bar > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_title_bar_settings';

// Full Width.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_title_bar_full_width',
	'section'  => $section,
	'label'    => __( 'Full Width', 'jupiterx' ),
	'column'   => 6,
	'default'  => false,
] );

// Title HTML Tag.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-select',
	'settings' => 'jupiterx_title_bar_title_tag',
	'section'  => $section,
	'label'    => __( 'Title HTML Tag', 'jupiterx' ),
	'column'   => 6,
	'default'  => 'h1',
	'choices'  => [
		'h1'   => 'h1',
		'h2'   => 'h2',
		'h3'   => 'h3',
		'h4'   => 'h4',
		'h5'   => 'h5',
		'h6'   => 'h6',
		'div'  => 'div',
		'span' => 'span',
		'p'    => 'p',
	],
] );

// Content.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-choose',
	'settings' => 'jupiterx_title_bar_elements',
	'section'  => $section,
	'label'    => __( 'Elements', 'jupiterx' ),
	'multiple' => true,
	'default'  => [ 'page_title', 'page_subtitle', 'breadcrumb' ],
	'choices'  => [
		'page_title'    => __( 'Title', 'jupiterx' ),
		'page_subtitle' => __( 'Subtitle', 'jupiterx' ),
		'breadcrumb'    => __( 'Breadcrumb', 'jupiterx' ),
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'          => 'jupiterx-divider',
	'settings'      => 'jupiterx_title_bar_divider',
	'section'       => $section,
	'control_attrs' => [
		'style' => 'margin-top: 10px',
	],
] );

// Exceptions.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-exceptions',
	'settings' => 'jupiterx_title_bar_exceptions',
	'section'  => $section,
	'label'    => __( 'Exceptions', 'jupiterx' ),
	'default'  => [],
	'choices'  => [
		'archive'   => __( 'Archive', 'jupiterx' ),
		'post'      => __( 'Blog', 'jupiterx' ),
		'page'      => __( 'Page', 'jupiterx' ),
		'portfolio' => __( 'Portfolio', 'jupiterx' ),
		'search'    => __( 'Search', 'jupiterx' ),
		'product'   => __( 'Shop', 'jupiterx' ),
	],
	'fields'   => [
		'full_width' => [
			'type'    => 'jupiterx-toggle',
			'label'   => __( 'Full Width', 'jupiterx' ),
			'column'  => 6,
			'default' => false,
		],
		'title_tag' => [
			'type'    => 'jupiterx-select',
			'label'   => __( 'Title HTML Tag', 'jupiterx' ),
			'column'  => 6,
			'default' => 'h1',
			'choices' => [
				'h1'   => 'h1',
				'h2'   => 'h2',
				'h3'   => 'h3',
				'h4'   => 'h4',
				'h5'   => 'h5',
				'h6'   => 'h6',
				'div'  => 'div',
				'span' => 'span',
				'p'    => 'p',
			],
		],
		'elements' => [
			'type'     => 'jupiterx-choose',
			'label'    => __( 'Content', 'jupiterx' ),
			'default'  => [ 'page_title', 'page_subtitle', 'breadcrumb' ],
			'multiple' => true,
			'choices'  => [
				'page_title'    => [ 'label' => __( 'Title', 'jupiterx' ) ],
				'page_subtitle' => [ 'label' => __( 'Subtitle', 'jupiterx' ) ],
				'breadcrumb'    => [ 'label' => __( 'Breadcrumb', 'jupiterx' ) ],
			],
		],
	],
] );
