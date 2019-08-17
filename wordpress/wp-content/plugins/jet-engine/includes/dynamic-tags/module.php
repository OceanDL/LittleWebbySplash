<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Jet_Engine_Dynamic_Tags_Module extends Elementor\Modules\DynamicTags\Module {

	const JET_GROUP = 'jet_engine';

	public function get_tag_classes_names() {
		return array(
			'Jet_Engine_Custom_Image_Tag',
			'Jet_Engine_Custom_Field_Tag',
		);
	}

	public function get_groups() {
		return array(
			self::JET_GROUP => array(
				'title' => __( 'JetEngine', 'jet-engine' ),
			),
		);
	}

	/**
	 * Register tags.
	 *
	 * Add all the available dynamic tags.
	 *
	 * @since  2.0.0
	 * @access public
	 *
	 * @param Manager $dynamic_tags
	 */
	public function register_tags( $dynamic_tags ) {

		foreach ( $this->get_tag_classes_names() as $tag_class ) {

			$file     = str_replace( 'Jet_Engine_', '', $tag_class );
			$file     = str_replace( '_', '-', strtolower( $file ) ) . '.php';
			$filepath = jet_engine()->plugin_path( 'includes/dynamic-tags/tags/' . $file );

			if ( file_exists( $filepath ) ) {
				require $filepath;
			}

			if ( class_exists( $tag_class ) ) {
				$dynamic_tags->register_tag( $tag_class );
			}

		}

	}
}