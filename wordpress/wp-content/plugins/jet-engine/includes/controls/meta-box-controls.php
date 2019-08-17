<?php
/**
 * CPT controls instance
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT_Controls' ) ) {
	require jet_engine()->plugin_path( 'includes/controls/post-type-controls.php' );
}

if ( ! class_exists( 'Jet_Engine_CPT_Controls_Meta' ) ) {

	/**
	 * Define Jet_Engine_CPT_Controls_Meta class
	 */
	class Jet_Engine_CPT_Controls_Meta extends Jet_Engine_CPT_Controls {

		/**
		 * Register controls
		 *
		 * @return [type] [description]
		 */
		public function register() {

			if ( ! $this->builder ) {
				return;
			}

			$this->builder->register_form(
				array(
					'jet_meta_form' => array(
						'type'   => 'form',
						'action' => $this->action,
					),
				)
			);

			$this->builder->register_section(
				array(
					'jet_meta_args' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_meta_form',
					),
					'jet_meta_controls' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_meta_form',
						'title'  => __( 'Actions', 'jet-engine' ),
					),
				)
			);

			$this->register_actions_section( 'jet_meta_controls' );

			$this->builder->register_section(
				array(
					'jet_meta_general' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_meta_args',
						'title'  => __( 'General', 'jet-engine' ),
					),
					'jet_meta_fields' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_meta_args',
						'title'  => __( 'Meta Fields', 'jet-engine' ),
					),
				)
			);

			$this->builder->register_settings(
				array(
					'jet_meta_general_settings' => array(
						'parent' => 'jet_meta_general',
					),
					'jet_meta_fields_settings' => array(
						'parent' => 'jet_meta_fields',
					),
				)
			);

			$allowed_post_types = $this->get_value( 'args/allowed_post_type' );

			if ( empty( $allowed_post_types ) ) {
				$search_post_type = 'post';
			} else {
				$search_post_type = implode( ',', $allowed_post_types );
			}

			$this->builder->register_control(
				array(
					$this->field_id( 'name' ) => array(
						'type'        => 'text',
						'name'        => 'args[name]',
						'parent'      => 'jet_meta_general_settings',
						'value'       => $this->get_value( 'args/name' ),
						'title'       => __( 'Name', 'jet-engine' ),
						'required'    => true,
					),
					$this->field_id( 'object_type' ) => array(
						'type'     => 'select',
						'name'     => 'args[object_type]',
						'parent'   => 'jet_meta_general_settings',
						'value'    => $this->get_value( 'args/object_type' ),
						'title'    => __( 'Meta Box for', 'jet-engine' ),
						'options'  => array(
							''     => __( 'Select...', 'jet-engine' ),
							'post' => __( 'Post', 'jet-engine' ),
							'tax'  => __( 'Taxonomy', 'jet-engine' ),
						),
						'required' => true,
					),
					$this->field_id( 'allowed_post_type' ) => array(
						'type'       => 'select',
						'name'       => 'args[allowed_post_type]',
						'parent'     => 'jet_meta_general_settings',
						'value'      => $this->get_value( 'args/allowed_post_type' ),
						'title'      => __( 'Post types', 'jet-engine' ),
						'options'    => jet_engine()->listings->get_post_types_for_options(),
						'multiple'   => true,
						'conditions' => array(
							$this->field_id( 'object_type' ) => 'post',
						),
					),
					$this->field_id( 'allowed_tax' ) => array(
						'type'       => 'select',
						'name'       => 'args[allowed_tax]',
						'parent'     => 'jet_meta_general_settings',
						'value'      => $this->get_value( 'args/allowed_tax' ),
						'title'      => __( 'Taxonomies', 'jet-engine' ),
						'options'    => jet_engine()->listings->get_taxonomies_for_options(),
						'multiple'   => true,
						'conditions' => array(
							$this->field_id( 'object_type' ) => 'tax',
						),
					),
					$this->field_id( 'allowed_posts' ) => array(
						'type'         => 'posts',
						'name'         => 'args[allowed_posts]',
						'parent'       => 'jet_meta_general_settings',
						'value'        => $this->get_value( 'args/allowed_posts' ),
						'title'        => __( 'Specific Posts', 'jet-engine' ),
						'post_type'    => $search_post_type,
						'action'       => 'jet_engine_meta_box_posts',
						'multiple'     => true,
						'placeholder'  => null,
						'inline_style' => 'width: 100%;',
						'clear_label'  => __( 'Clear', 'jet-engine' ),
						'conditions'   => array(
							$this->field_id( 'object_type' ) => 'post',
						),
					),
				)
			);

			$this->meta_controls->register(
				$this->get_value( 'meta_fields', array() ),
				'jet_meta_fields_settings'
			);

		}

		/**
		 * Return value
		 *
		 * @param  [type] $key     [description]
		 * @param  string $default [description]
		 * @return [type]          [description]
		 */
		public function get_value( $key, $default = '' ) {

			$keys = explode( '/', $key );

			if ( 1 === count( $keys ) ) {
				return isset( $this->values[ $keys[0] ] ) ? $this->values[ $keys[0] ] : $default;
			} else {
				if ( isset( $this->values[ $keys[0] ] ) ) {
					$val = $this->values[ $keys[0] ];
					return isset( $val[ $keys[1] ] ) ? $val[ $keys[1] ] : $default;
				} else {
					return $default;
				}
			}
		}

	}

}
