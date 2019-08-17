<?php
/**
 * Listing items data manager
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Listings_Data' ) ) {

	/**
	 * Define Jet_Engine_Listings_Data class
	 */
	class Jet_Engine_Listings_Data {

		/**
		 * Current listing object
		 *
		 * @var object
		 */
		private $current_object = null;

		/**
		 * Current listing document
		 *
		 * @var array
		 */
		private $current_listing = false;

		/**
		 * Default object holder
		 *
		 * @var mixed
		 */
		private $default_object = null;

		/**
		 * Listing settings defaults
		 * @var array
		 */
		private $defaults = false;

		/**
		 * Set current listing from outside
		 *
		 * @param void
		 */
		public function set_listing( $listing_doc = null ) {
			$this->current_listing = $listing_doc;
		}

		/**
		 * Reset current listing object
		 *
		 * @param  [type] $listing_doc [description]
		 * @return [type]              [description]
		 */
		public function reset_listing( $listing_doc = null ) {
			$this->current_listing = $listing_doc;
			$this->reset_current_object();
		}

		/**
		 * Returns current listing object
		 *
		 * @return [type] [description]
		 */
		public function get_listing() {
			return $this->current_listing;
		}

		/**
		 * Get listing default property
		 *
		 * @param  string $prop [description]
		 * @return [type]       [description]
		 */
		public function listing_defaults( $prop = 'listing_source' ) {

			if ( ! empty( $this->defaults ) ) {
				return isset( $this->defaults[ $prop ] ) ? $this->defaults[ $prop ] : false;
			}

			$default = array(
				'listing_source'    => 'posts',
				'listing_post_type' => 'post',
				'listing_tax'       => 'category',
			);

			if ( ! $this->get_listing() ) {

				$default_object = $this->get_default_object();

				if ( ! $default_object ) {
					$this->defaults = $default;
					return isset( $this->defaults[ $prop ] ) ? $this->defaults[ $prop ] : false;
				}

				$listing = apply_filters( 'jet-engine/listing/data/custom-listing', false, $this, $default_object );

				if ( ! $listing ) {

					if ( isset( $default_object->post_type ) ) {
						$this->defaults = array(
							'listing_source'    => 'posts',
							'listing_post_type' => $default_object->post_type,
							'listing_tax'       => 'category',
						);
					} else {
						$this->defaults = array(
							'listing_source'    => 'terms',
							'listing_post_type' => 'post',
							'listing_tax'       => $default_object->taxonomy,
						);
					}

				} else {
					$this->defaults = $listing;
				}

				return isset( $this->defaults[ $prop ] ) ? $this->defaults[ $prop ] : false;
			}

		}

		/**
		 * Returns listing source
		 *
		 * @return string
		 */
		public function get_listing_source() {

			$lising = $this->get_listing();

			if ( ! $lising ) {
				return $this->listing_defaults( 'listing_source' );
			} else {
				return $lising->get_settings( 'listing_source' );
			}
		}

		/**
		 * Returns post type for query
		 *
		 * @return string
		 */
		public function get_listing_post_type() {

			$lising = $this->get_listing();

			if ( ! $lising ) {

				$post_type = get_post_type();

				$blacklisted = array(
					'elementor_library',
					'jet-theme-core',
				);

				if ( $post_type && ! in_array( $post_type, $blacklisted ) ) {
					return $post_type;
				} else {
					return $this->listing_defaults( 'listing_post_type' );
				}

			} else {
				return $lising->get_settings( 'listing_post_type' );
			}
		}

		/**
		 * Returns taxonomy for query
		 *
		 * @return string
		 */
		public function get_listing_tax() {

			$lising = $this->get_listing();

			if ( ! $lising ) {
				return $this->listing_defaults( 'listing_tax' );
			} else {
				return $lising->get_settings( 'listing_tax' );
			}
		}

		/**
		 * Set $current_object property
		 *
		 * @param object $object
		 */
		public function set_current_object( $object = null ) {
			$this->current_object = $object;
		}

		/**
		 * Set $current_object property
		 *
		 * @param object $object
		 */
		public function reset_current_object() {
			$this->current_object = null;
		}

		/**
		 * Returns $current_object property
		 *
		 * @return object
		 */
		public function get_current_object() {

			if ( null === $this->current_object ) {
				$this->current_object = $this->get_default_object();
			}

			return $this->current_object;
		}

		/**
		 * Returns default object
		 *
		 * @return [type] [description]
		 */
		public function get_default_object() {

			if ( null !== $this->default_object ) {
				return $this->default_object;
			}

			$default_object = false;

			if ( is_singular() ) {
				global $post;
				$default_object = $this->default_object = $post;
			} elseif ( is_tax() || is_category() || is_tag() ) {
				$default_object = $this->default_object =  get_queried_object();
			} elseif ( wp_doing_ajax() ) {
				$post_id = isset( $_REQUEST['editor_post_id'] ) ? $_REQUEST['editor_post_id'] : false;

				if ( ! $post_id ) {
					$default_object = $this->default_object = false;
				} else {
					$default_object = $this->default_object = get_post( $post_id );
				}

			} elseif ( is_archive() || is_home() || is_post_type_archive() ) {
				global $post;
				$default_object = $post;
			} else {
				$default_object = $this->default_object = false;
			}

			$this->default_object = apply_filters( 'jet-engine/listings/data/default-object', $default_object, $this );

			return $this->default_object;

		}

		/**
		 * Returns requested property from current object
		 *
		 * @param  [type] $property [description]
		 * @return [type]           [description]
		 */
		public function get_prop( $property = null ) {
			$vars = get_object_vars( $this->get_current_object() );
			return isset( $vars[ $property ] ) ? $vars[ $property ] : false;
		}

		/**
		 * Returns listing meta fields
		 *
		 * @return [type] [description]
		 */
		public function get_listing_meta_fields() {

			$meta_fields     = false;
			$custom_fields   = array();
			$relation_fields = array();

			switch ( jet_engine()->listings->data->get_listing_source() ) {

				case 'posts':

					$meta_fields = jet_engine()->cpt->get_meta_fields_for_object(
						jet_engine()->listings->data->get_listing_post_type()
					);

					$custom_fields = jet_engine()->meta_boxes->get_meta_fields_for_object(
						jet_engine()->listings->data->get_listing_post_type()
					);

					$relation_fields = jet_engine()->relations->get_relation_fields_for_post_type(
						jet_engine()->listings->data->get_listing_post_type()
					);

					break;

				case 'terms':

					$meta_fields = jet_engine()->taxonomies->get_meta_fields_for_object(
						jet_engine()->listings->data->get_listing_tax()
					);

					$custom_fields = jet_engine()->meta_boxes->get_meta_fields_for_object(
						jet_engine()->listings->data->get_listing_tax()
					);

					break;
			}

			if ( empty( $meta_fields ) ) {
				$meta_fields = array();
			}

			if ( ! empty( $custom_fields ) ) {
				$meta_fields = array_merge( $meta_fields, $custom_fields );
			}

			if ( ! empty( $relation_fields ) ) {
				$meta_fields = array_merge( $meta_fields, $relation_fields );
			}

			return $meta_fields;

		}

		/**
		 * Returns current meta
		 *
		 * @param  [type] $key [description]
		 * @return [type]      [description]
		 */
		public function get_meta( $key ) {

			$object = $this->get_current_object();

			if ( ! $object ) {
				return false;
			}

			$class  = get_class( $object );
			$result = '';

			switch ( $class ) {
				case 'WP_Post':

					if ( jet_engine()->relations->is_relation_key( $key ) ) {
						$single = false;
					} else {
						$single = true;
					}

					return get_post_meta( $object->ID, $key, $single );

				case 'WP_Term':
					return get_term_meta( $object->term_id, $key, true );
			}

		}

		/**
		 * Get permalink to current post/term
		 *
		 * @return string
		 */
		public function get_current_object_permalink() {

			$object = $this->get_current_object();
			$class  = get_class( $object );
			$result = '';

			switch ( $class ) {
				case 'WP_Post':
					return get_permalink( $object->ID );

				case 'WP_Term':
					return get_term_link( $object->term_id );
			}

			return null;

		}

		/**
		 * Returns available list sources
		 *
		 * @return [type] [description]
		 */
		public function get_field_sources() {
			return apply_filters( 'jet-engine/listings/data/sources', array(
				'object' => __( 'Post/Term Data', 'jet-engine' ),
				'meta'   => __( 'Meta Data', 'jet-engine' ),
			) );
		}

	}

}
