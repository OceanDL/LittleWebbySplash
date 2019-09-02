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

if ( ! class_exists( 'Jet_Engine_CPT_Controls_Relation' ) ) {

	/**
	 * Define Jet_Engine_CPT_Controls_Relation class
	 */
	class Jet_Engine_CPT_Controls_Relation extends Jet_Engine_CPT_Controls {

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
					'jet_relation_form' => array(
						'type'   => 'form',
						'action' => $this->action,
					),
				)
			);

			$this->builder->register_section(
				array(
					'jet_relation_args' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_relation_form',
					),
					'jet_relation_controls' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_relation_form',
						'title'  => __( 'Actions', 'jet-engine' ),
					),
				)
			);

			$this->register_actions_section( 'jet_relation_controls' );

			$this->builder->register_section(
				array(
					'jet_relation_general' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_relation_args',
						'title'  => __( 'Settings', 'jet-engine' ),
					),
				)
			);

			$this->builder->register_settings(
				array(
					'jet_relation_general_settings' => array(
						'parent' => 'jet_relation_general',
					),
				)
			);

			$this->builder->register_control(
				array(
					$this->field_id( 'name' ) => array(
						'type'     => 'text',
						'name'     => 'name',
						'parent'   => 'jet_relation_general_settings',
						'value'    => $this->get_value( 'name' ),
						'title'    => __( 'Name', 'jet-engine' ),
						'required' => true,
					),
					$this->field_id( 'post_type_1' ) => array(
						'type'     => 'select',
						'name'     => 'post_type_1',
						'parent'   => 'jet_relation_general_settings',
						'value'    => $this->get_value( 'post_type_1' ),
						'title'    => __( 'Post type 1 (Parent)', 'jet-engine' ),
						'options'  => jet_engine()->listings->get_post_types_for_options(),
						'required' => true,
					),
					$this->field_id( 'post_type_2' ) => array(
						'type'     => 'select',
						'name'     => 'post_type_2',
						'parent'   => 'jet_relation_general_settings',
						'value'    => $this->get_value( 'post_type_2' ),
						'title'    => __( 'Post type 2 (Child)', 'jet-engine' ),
						'options'  => jet_engine()->listings->get_post_types_for_options(),
						'required' => true,
					),
					$this->field_id( 'type' ) => array(
						'type'    => 'select',
						'name'    => 'type',
						'parent'  => 'jet_relation_general_settings',
						'value'   => $this->get_value( 'type' ),
						'title'   => __( 'Relation Type', 'jet-engine' ),
						'options' => jet_engine()->relations->get_relations_types(),
					),
					$this->field_id( 'post_type_1_control' ) => array(
						'type'        => 'switcher',
						'name'        => 'post_type_1_control',
						'parent'      => 'jet_relation_general_settings',
						'value'       => $this->get_value( 'post_type_1_control', true ),
						'title'       => esc_html__( 'Add metabox to parent page', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
					$this->field_id( 'post_type_2_control' ) => array(
						'type'        => 'switcher',
						'name'        => 'post_type_2_control',
						'parent'      => 'jet_relation_general_settings',
						'value'       => $this->get_value( 'post_type_2_control', true ),
						'title'       => esc_html__( 'Add metabox to child page', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
				)
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
			return isset( $this->values[ $key ] ) ? $this->values[ $key ] : $default;
		}

	}

}
