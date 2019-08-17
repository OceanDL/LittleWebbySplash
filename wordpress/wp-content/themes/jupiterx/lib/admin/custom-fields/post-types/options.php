<?php
/**
 * Add Jupiter Post Options.
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 *
 * @since   1.0.0
 */

$locations  = [];
$post_types = apply_filters( 'jupiterx_post_options_post_types', [ 'post', 'portfolio', 'page' ] );

foreach ( $post_types as $post_type_slug ) {
	$locations[] = [
		[
			'param'    => 'post_type',
			'operator' => '==',
			'value'    => $post_type_slug,
		],
	];
}

// Post Options.
acf_add_local_field_group( [
	'key'      => 'group_jupiterx_post',
	'title'    => __( 'Post Options', 'jupiterx' ),
	'location' => $locations,
] );
