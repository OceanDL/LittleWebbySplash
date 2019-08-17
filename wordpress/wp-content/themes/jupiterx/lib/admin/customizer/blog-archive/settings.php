<?php
/**
 * Add Jupiter settings for Footer > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_blog_archive_settings';

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_blog_archive',
	'section'   => $section,
	'css_var'   => 'blog-archive',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.archive.category .jupiterx-main-content, .archive.tag .jupiterx-main-content',
		],
	],
] );
