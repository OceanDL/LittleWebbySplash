<?php
/**
 * Add Jupiter settings for Footer > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_sidebar_settings';

// Layout.
JupiterX_Customizer::add_field( [
	'type'          => 'jupiterx-select',
	'settings'      => 'jupiterx_sidebar_layout',
	'section'       => $section,
	'label'         => __( 'Layout', 'jupiterx' ),
	'default'       => 'c_sp',
	'choices'       => JupiterX_Customizer_Utils::get_layouts(),
	'control_attrs' => [
		'style' => 'max-width: 48%',
	],
] );

// Sidebar Primary.
JupiterX_Customizer::add_field( [
	'type'         => 'jupiterx-select',
	'settings'     => 'jupiterx_sidebar_primary',
	'section'      => $section,
	'label'        => __( 'Primary Sidebar', 'jupiterx' ),
	'column'       => 6,
	'default'      => 'sidebar_primary',
	'load_choices' => 'widgets_area',
] );

// Sidebar Secondary.
JupiterX_Customizer::add_field( [
	'type'         => 'jupiterx-select',
	'settings'     => 'jupiterx_sidebar_secondary',
	'section'      => $section,
	'label'        => __( 'Secondary Sidebar', 'jupiterx' ),
	'column'       => 6,
	'default'      => 'sidebar_secondary',
	'load_choices' => 'widgets_area',
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'          => 'jupiterx-divider',
	'settings'      => 'jupiterx_sidebar_divider',
	'section'       => $section,
	'control_attrs' => [
		'style' => 'margin-top: 10px',
	],
] );

// Exceptions.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-exceptions',
	'settings' => 'jupiterx_sidebar_exceptions',
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
		'layout' => [
			'type'          => 'jupiterx-select',
			'label'         => __( 'Layout', 'jupiterx' ),
			'default'       => 'c_sp',
			'choices'       => JupiterX_Customizer_Utils::get_layouts(),
			'controlAttrs' => [
				'style' => 'max-width: 48%',
			],
		],
		'primary' => [
			'type'         => 'jupiterx-select',
			'label'        => __( 'Primary Sidebar', 'jupiterx' ),
			'column'       => 6,
			'default'      => 'sidebar_primary',
			'load_choices' => 'widgets_area',
		],
		'secondary' => [
			'type'         => 'jupiterx-select',
			'label'        => __( 'Secondary Sidebar', 'jupiterx' ),
			'column'       => 6,
			'default'      => 'sidebar_secondary',
			'load_choices' => 'widgets_area',
		],
	],
] );
