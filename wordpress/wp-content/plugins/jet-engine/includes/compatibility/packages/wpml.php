<?php
/**
 * WPML compatibility package
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_WPML_Package' ) ) {

	class Jet_Engine_WPML_Package {

		public function __construct() {
			add_filter( 'wpml_elementor_widgets_to_translate', array( $this, 'add_translatable_nodes' ) );
			add_filter( 'jet-engine/listings/frontend/rendered-listing-id', array( $this, 'set_translated_listing' ) );
		}

		/**
		 * Set translated listing ID to show
		 *
		 * @param [type] $listing_id [description]
		 */
		public function set_translated_listing( $listing_id ) {

			global $sitepress;

			$new_id = $sitepress->get_object_id( $listing_id );

			if ( $new_id ) {
				return $new_id;
			} else {
				return $listing_id;
			}

		}

		/**
		 * Add translation strings
		 */
		public function add_translatable_nodes( $nodes ) {

			$nodes['jet-listing-grid'] = array(
				'conditions' => array(
					'widgetType' => 'jet-listing-grid'
				),
				'fields'     => array(
					array(
						'field'       => 'not_found_message',
						'type'        => esc_html__( 'Listing Grid: Not found message', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes['jet-listing-dynamic-field'] = array(
				'conditions' => array(
					'widgetType' => 'jet-listing-dynamic-field'
				),
				'fields'     => array(
					array(
						'field'       => 'date_format',
						'type'        => esc_html__( 'Field: Date format (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'num_dec_point',
						'type'        => esc_html__( 'Field: Separator for the decimal point (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'num_thousands_sep',
						'type'        => esc_html__( 'Field: Thousands separator (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'dynamic_field_format',
						'type'        => esc_html__( 'Field: Field format (if used)', 'jet-engine' ),
						'editor_type' => 'AREA',
					),
				),
			);

			$nodes['jet-listing-dynamic-link'] = array(
				'conditions' => array(
					'widgetType' => 'jet-listing-dynamic-link'
				),
				'fields'     => array(
					array(
						'field'       => 'link_label',
						'type'        => esc_html__( 'Link: Label (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes['jet-listing-dynamic-meta'] = array(
				'conditions' => array(
					'widgetType' => 'jet-listing-dynamic-meta'
				),
				'fields'     => array(
					array(
						'field'       => 'prefix',
						'type'        => esc_html__( 'Meta: Prefix (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'suffix',
						'type'        => esc_html__( 'Meta: Suffix (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'zero_comments_format',
						'type'        => esc_html__( 'Meta: Zero Comments Format (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'one_comment_format',
						'type'        => esc_html__( 'Meta: One Comments Format (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'more_comments_format',
						'type'        => esc_html__( 'Meta: More Comments Format (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'date_format',
						'type'        => esc_html__( 'Meta: Date Format (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes['jet-listing-dynamic-terms'] = array(
				'conditions' => array(
					'widgetType' => 'jet-listing-dynamic-terms'
				),
				'fields'     => array(
					array(
						'field'       => 'terms_prefix',
						'type'        => esc_html__( 'Terms: Prefix (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'terms_suffix',
						'type'        => esc_html__( 'Terms: Suffix (if used)', 'jet-engine' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes['jet-listing-dynamic-repeater'] = array(
				'conditions' => array(
					'widgetType' => 'jet-listing-dynamic-repeater'
				),
				'fields'     => array(
					array(
						'field'       => 'dynamic_field_format',
						'type'        => esc_html__( 'Repeater: Field format (if used)', 'jet-engine' ),
						'editor_type' => 'AREA',
					),
				),
			);

			return $nodes;

		}

	}

}

new Jet_Engine_WPML_Package();
