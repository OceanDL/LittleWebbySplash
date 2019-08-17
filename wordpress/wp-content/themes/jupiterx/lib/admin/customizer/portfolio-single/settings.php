<?php
/**
 * Add Jupiter settings for Portfolio Single > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_portfolio_single_settings';

// Label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_portfolio_single_label_1',
	'section'  => $section,
	'label'    => __( 'Display Elements', 'jupiterx' ),
] );

// Display elements.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-multicheck',
	'settings' => 'jupiterx_portfolio_single_elements',
	'section'  => $section,
	'css_var'  => 'portfolio-single-elements',
	'default'  => [
		'featured_image',
		'categories',
		'social_share',
		'navigation',
		'related_posts',
		'comments',
	],
	'choices'  => [
		'featured_image' => __( 'Featured Image', 'jupiterx' ),
		'title'          => __( 'Title', 'jupiterx' ),
		'date'           => __( 'Date', 'jupiterx' ),
		'author'         => __( 'Author', 'jupiterx' ),
		'categories'     => __( 'Categories', 'jupiterx' ),
		'social_share'   => __( 'Social Share', 'jupiterx' ),
		'navigation'     => __( 'Navigation', 'jupiterx' ),
		'related_posts'  => __( 'Related Works', 'jupiterx' ),
		'comments'       => __( 'Comments', 'jupiterx' ),
	],
] );
