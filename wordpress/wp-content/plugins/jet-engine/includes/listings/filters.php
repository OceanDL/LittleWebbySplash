<?php
/**
 * Filters class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Listings_Filters' ) ) {

	/**
	 * Define Jet_Engine_Listings_Filters class
	 */
	class Jet_Engine_Listings_Filters {

		/**
		 * Return available macros list
		 *
		 * @return [type] [description]
		 */
		public function get_all() {
			return apply_filters( 'jet-engine/listings/filters-list', array(
				'img_url_by_id'  => array(
					'cb'   => array( $this, 'get_img_url' ),
					'args' => 'full',
				),
				'post_url_by_id' => array(
					'cb'   => array( $this, 'get_post_url' ),
					'args' => false,
				),
				'img_gallery_grid' => array(
					'cb'   => array( $this, 'img_gallery_grid' ),
					'args' => 'full',
				),
			) );
		}

		public function apply_filters( $value = null, $filter = null ) {

			if ( ! $value ) {
				return null;
			}

			$filters = $this->get_all();

			if ( ! $filter ) {
				return $value;
			}

			preg_match( '/([a-zA-Z0-9_-]+)(\([a-zA-Z0-9_-]+\))?/', $filter, $filter_data );

			if ( empty( $filter_data ) ) {
				return $value;
			}

			$filter_name = isset( $filter_data[1] ) ? $filter_data[1] : false;
			$filter_arg  = isset( $filter_data[2] ) ? trim( $filter_data[2], '()' ) : false;

			if ( ! isset( $filters[ $filter_name ] ) ) {
				return $value;
			}

			$_filter = $filters[ $filter_name ];

			if ( ! $filter_arg ) {
				$filter_arg = $_filter['args'];
			}

			return call_user_func_array( $_filter['cb'], array_filter( array( $value, $filter_arg ) ) );

		}

		/**
		 * Render images gallery as grid.
		 *
		 * @return [type] [description]
		 */
		public function img_gallery_grid( $img_ids = null, $args = array() ) {

			if ( ! $img_ids ) {
				return null;
			}

			$img_ids = explode( ',', $img_ids );

			if ( empty( $img_ids ) ) {
				return null;
			}

			// Ensure gallery class is included
			jet_engine_get_gallery();

			return Jet_Engine_Img_Gallery::grid( $img_ids, $args );

		}

		/**
		 * Render images gallery as slider.
		 *
		 * @return [type] [description]
		 */
		public function img_gallery_slider( $img_ids = null, $args = array() ) {

			if ( ! $img_ids ) {
				return null;
			}

			$img_ids = explode( ',', $img_ids );

			if ( empty( $img_ids ) ) {
				return null;
			}

			// Ensure gallery class is included
			jet_engine_get_gallery();

			return Jet_Engine_Img_Gallery::slider( $img_ids, $args );

		}

		public function get_img_url( $img_id, $size ) {
			return wp_get_attachment_image_url( $img_id, $size );
		}

		public function get_post_url( $post_id ) {
			return get_permalink( $post_id );
		}

	}

}
