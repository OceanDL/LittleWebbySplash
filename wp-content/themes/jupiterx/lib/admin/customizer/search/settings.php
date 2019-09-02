<?php
/**
 * Add Jupiter settings for Pages > Search > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_search';

// Label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_search_label_1',
	'section'  => $section,
	'label'    => __( 'Display section', 'jupiterx' ),
] );

// Display content.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-multicheck',
	'settings' => 'jupiterx_search_post_types',
	'section'  => $section,
	'default'  => [ 'post', 'portfolio', 'page', 'product' ],
	'choices'  => [
		'post'      => __( 'Post', 'jupiterx' ),
		'portfolio' => __( 'Portfolio', 'jupiterx' ),
		'page'      => __( 'Page', 'jupiterx' ),
		'product'   => __( 'Product', 'jupiterx' ),
	],
] );

// Posts per page.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-text',
	'settings'    => 'jupiterx_search_posts_per_page',
	'section'     => $section,
	'label'       => __( 'Posts per page', 'jupiterx' ),
	'column'      => 6,
	'default'     => 5,
	'input_type'  => 'number',
	'input_attrs' => [
		'min' => 5,
	],
] );
