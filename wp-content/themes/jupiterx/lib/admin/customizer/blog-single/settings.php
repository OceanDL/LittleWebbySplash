<?php
/**
 * Add Jupiter settings for Blog Single > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_post_single_settings';

// Template label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_post_single_label_1',
	'section'  => $section,
	'label'    => __( 'Template', 'jupiterx' ),
] );

// Template.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-radio-image',
	'settings'        => 'jupiterx_post_single_template',
	'section'         => $section,
	'default'         => '1',
	'choices'         => [
		'1'  => 'blog-single-01',
		'2'  => [
			'name' => 'blog-single-02',
			'pro'  => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/blog-single-2.jpg',
		],
		'3'  => [
			'name' => 'blog-single-03',
			'pro'  => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/blog-single-3.jpg',
		],
	],
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_post_single_label_2',
	'section'  => $section,
	'label'    => __( 'Display Elements', 'jupiterx' ),
] );

// Display elements.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-multicheck',
	'settings' => 'jupiterx_post_single_elements',
	'section'  => $section,
	'css_var'  => 'post-single-elements',
	'default'  => [
		'featured_image',
		'date',
		'author',
		'categories',
		'tags',
		'social_share',
		'navigation',
		'author_box',
		'related_posts',
		'comments',
	],
	'choices'  => [
		'featured_image' => __( 'Featured Image', 'jupiterx' ),
		'title'          => __( 'Title', 'jupiterx' ),
		'date'           => __( 'Date', 'jupiterx' ),
		'author'         => __( 'Author', 'jupiterx' ),
		'categories'     => __( 'Categories', 'jupiterx' ),
		'tags'           => __( 'Tags', 'jupiterx' ),
		'social_share'   => __( 'Social Share', 'jupiterx' ),
		'navigation'     => __( 'Navigation', 'jupiterx' ),
		'author_box'     => __( 'Author Box', 'jupiterx' ),
		'related_posts'  => __( 'Related Posts', 'jupiterx' ),
		'comments'       => __( 'Comments', 'jupiterx' ),
	],
] );
