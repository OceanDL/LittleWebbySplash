<?php
/**
 * Misc functions
 */

/**
 * Includes Jet_Engine_Img_Gallery class if it was not included before
 *
 * @return void
 */
function jet_engine_get_gallery() {
	if ( ! class_exists( 'Jet_Engine_Img_Gallery' ) ) {
		require_once jet_engine()->plugin_path( 'includes/gallery.php' );
	}
}

/**
 * Callback for filter field option
 *
 * @return void
 */
function jet_engine_img_gallery_slider( $value = null, $args = array() ) {

	if ( is_array( $value ) ) {
		$value = implode( ',', $value );
	}

	return jet_engine()->listings->filters->img_gallery_slider( $value, $args );
}

/**
 * Callback for filter field option
 *
 * @return void
 */
function jet_engine_img_gallery_grid( $value = null, $args = array() ) {

	if ( is_array( $value ) ) {
		$value = implode( ',', $value );
	}

	return jet_engine()->listings->filters->img_gallery_grid( $value, $args );
}

/**
 * Returns image size array in slug => name format
 *
 * @return  array
 */
function jet_engine_get_image_sizes() {

	global $_wp_additional_image_sizes;

	$sizes  = get_intermediate_image_sizes();
	$result = array();

	foreach ( $sizes as $size ) {
		if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
			$result[ $size ] = ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) );
		} else {
			$result[ $size ] = sprintf(
				'%1$s (%2$sx%3$s)',
				ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
				$_wp_additional_image_sizes[ $size ]['width'],
				$_wp_additional_image_sizes[ $size ]['height']
			);
		}
	}

	return array_merge( array( 'full' => esc_html__( 'Full', 'jet-engine' ), ), $result );
}

/**
 * Sanitize WYSIWYG field
 *
 * @return string
 */
function jet_engine_sanitize_wysiwyg( $input ) {
	$input = wpautop( $input );
	return wp_kses_post( $input );
}

/**
 * Sanitize Textarea field
 *
 * @return string
 */
function jet_engine_sanitize_textarea( $input ) {
	return wp_check_invalid_utf8( $input, true );
}

/**
 * Return multiselect values as string with passed delimiter
 *
 * @param  [type] $value     [description]
 * @param  [type] $delimiter [description]
 * @return [type]            [description]
 */
function jet_engine_render_multiselect( $value = null, $delimiter = ', ' ) {

	if ( ! $value || ! is_array( $value ) ) {
		return $value;
	}

	return wp_kses_post( implode( $delimiter, $value ) );

}

/**
 * Return checkbox values as string with passed delimiter
 *
 * @param  [type] $value     [description]
 * @param  [type] $delimiter [description]
 * @return [type]            [description]
 */
function jet_engine_render_checkbox_values( $value = null, $delimiter = ', ' ) {

	if ( ! $value || ! is_array( $value ) ) {
		return $value;
	}

	$result = array();

	foreach ( $value as $key => $val ) {
		if ( 'true' === $val ) {
			$result[] = $key;
		}
	}

	return wp_kses_post( implode( $delimiter, $result ) );

}

/**
 * Return post titles from post IDs array as string with passed delimiter
 *
 * @param  [type] $value     [description]
 * @param  [type] $delimiter [description]
 * @return [type]            [description]
 */
function jet_engine_render_post_titles( $value = null, $delimiter = ', ' ) {

	if ( ! $value || ! is_array( $value ) ) {
		return $value;
	}

	return wp_kses_post( implode( $delimiter, array_map( 'get_the_title', $value ) ) );

}

/**
 * Returns link to post by ID
 *
 * @return [type] [description]
 */
function jet_get_pretty_post_link( $value ) {

	if ( empty( $value ) ) {
		return;
	}

	$result = '';

	if ( is_array( $value ) ) {

		$delimiter = '';

		foreach ( $value as $post_id ) {

			$result .= sprintf(
				'%3$s<a href="%1$s">%2$s</a>',
				get_permalink( $post_id ),
				get_the_title( $post_id ),
				$delimiter
			);

			$delimiter = ', ';

		}

	} else {
		$post_id = $value;
		$result  = sprintf( '<a href="%1$s">%2$s</a>', get_permalink( $post_id ), get_the_title( $post_id ) );
	}

	return $result;

}

/**
 * Return icon HTML for icon, set in JetEngine iconpicker
 *
 * @param  string $value Icon class
 * @return string
 */
function jet_engine_icon_html( $value = null ) {

	$format = apply_filters(
		'jet-engine/listings/icon-html-format',
		'<i class="fa %s"></i>'
	);

	return sprintf( $format, $value );

}

/**
 * Returns QR code for meta value
 *
 * @return string
 */
function jet_engine_get_qr_code( $meta_value = null, $size = 150 ) {

	$qr_code = jet_engine()->modules->get_module( 'qr-code' );
	return $qr_code->get_qr_code( $meta_value, $size );

}

/**
 * Render related posts array as HTML list
 *
 * @param  array  $related_posts [description]
 * @return [type]                [description]
 */
function jet_related_posts_list( $related_posts = array(), $tag = 'ul', $is_single = false, $is_linked = true, $delimiter = '' ) {

	ob_start();

	if ( empty( $related_posts ) ) {
		return;
	}

	switch ( $tag ) {
		case 'ol':
			$parent_tag = 'ol';
			$child_tag  = 'li';
			break;

		case 'div':
			$parent_tag = 'div';
			$child_tag  = 'span';
			break;

		default:
			$parent_tag = 'ul';
			$child_tag  = 'li';
			break;
	}

	if ( $is_single ) {
		$related_posts = array( $related_posts[0] );
	}

	printf( '<%s>', $parent_tag );

	$count = count( $related_posts );
	$i     = 1;

	foreach ( $related_posts as $post_id ) {

		if ( $i === $count ) {
			$delimiter = '';
		}

		if ( $is_linked ) {

			printf(
				'<%1$s><a href="%3$s">%2$s</a>%4$s</%1$s>',
				$child_tag,
				get_the_title( $post_id ),
				get_permalink( $post_id ),
				$delimiter
			);

		} else {

			printf(
				'<%1$s>%2$s%3$s</%1$s>',
				$child_tag,
				get_the_title( $post_id ),
				$delimiter
			);

		}

		$i++;
	}

	printf( '</%s>', $parent_tag );

	return ob_get_clean();

}
