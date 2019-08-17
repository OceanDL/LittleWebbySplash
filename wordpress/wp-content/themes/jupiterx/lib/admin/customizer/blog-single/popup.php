<?php
/**
 * Add Jupiter blog archive popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$blog_popups = [
	'featured_image' => __( 'Featured Image', 'jupiterx' ),
	'title'          => __( 'Title', 'jupiterx' ),
	'meta'           => __( 'Meta', 'jupiterx' ),
	'tags'           => __( 'Tags', 'jupiterx' ),
	'social_share'   => __( 'Social Share', 'jupiterx' ),
	'navigation'     => __( 'Navigation', 'jupiterx' ),
	'author_box'     => __( 'Author Box', 'jupiterx' ),
	'related_posts'  => __( 'Related Posts', 'jupiterx' ),
];

$template2_popups = [
	'avatar' => __( 'Avatar', 'jupiterx' ),
];

$popups = $template2_popups + $blog_popups;

// Blog single popup.
JupiterX_Customizer::add_section( 'jupiterx_post_single', [
	'panel'  => 'jupiterx_blog_panel',
	'title'  => __( 'Blog Single', 'jupiterx' ),
	'type'   => 'popup',
	'tabs'   => [
		'settings' => __( 'Settings', 'jupiterx' ),
		'styles'   => __( 'Styles', 'jupiterx' ),
	],
	'popups'  => $popups,
	'preview' => true,
	'help'    => [
		'url'   => 'http://help.artbees.net/customizer/display-settings-for-blog-shop-single-pages',
		'title' => __( 'Display settings for Blog, Shop single pages', 'jupiterx' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_post_single_settings', [
	'popup' => 'jupiterx_post_single',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_post_single_styles', [
	'popup' => 'jupiterx_post_single',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_post_single_styles_popups_avatar',
	'section'  => 'jupiterx_post_single_styles',
	'target'   => 'jupiterx_post_single',
	'choices'  => $template2_popups,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template',
			'operator' => '==',
			'value'    => '2',
		],
	],
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-child-popup',
	'settings'   => 'jupiterx_post_single_styles_popups',
	'section'    => 'jupiterx_post_single_styles',
	'target'     => 'jupiterx_post_single',
	'choices'    => [
		'featured_image' => __( 'Featured Image', 'jupiterx' ),
		'title'          => __( 'Title', 'jupiterx' ),
		'meta'           => __( 'Meta', 'jupiterx' ),
		'tags'           => __( 'Tags', 'jupiterx' ),
		'social_share'   => [
			'label' => __( 'Social Share', 'jupiterx' ),
			'pro'   => true,
		],
		'navigation'     => [
			'label' => __( 'Navigation', 'jupiterx' ),
			'pro'   => true,
		],
		'author_box'     => [
			'label' => __( 'Author Box', 'jupiterx' ),
			'pro'   => true,
		],
		'related_posts'  => [
			'label' => __( 'Related Posts', 'jupiterx' ),
			'pro'   => true,
		],
	],
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_post_single_' . $popup_id, [
		'popup' => 'jupiterx_post_single',
		'type'  => 'pane',
		'pane'  => [
			'type' => 'popup',
			'id'   => $popup_id,
		],
	] );
}

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
