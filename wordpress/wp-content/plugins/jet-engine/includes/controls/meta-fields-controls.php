<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Meta_Fields_Controls' ) ) {

	/**
	 * Define Jet_Engine_Meta_Fields_Controls class
	 */
	class Jet_Engine_Meta_Fields_Controls {

		public $builder;

		/**
		 * Constructor for the class
		 */
		function __construct( $builder ) {
			$this->builder = $builder;
		}

		/**
		 * Register Meta Fields controls
		 *
		 * @return void
		 */
		public function register( $saved = array(), $parent ) {

			$this->builder->register_control(
				array(
					'meta_fields' => array(
						'type'        => 'repeater',
						'id'          => $this->field_id( 'meta_fields' ),
						'name'        => 'meta_fields',
						'parent'      => $parent,
						'value'       => $saved,
						'label'       => __( 'Meta Fields', 'jet-engine' ),
						'add_label'   => __( 'New Meta Field', 'jet-engine' ),
						'title_field' => 'title',
						'fields'      => array(
							'title' => array(
								'type'  => 'text',
								'id'    => 'title',
								'name'  => 'title',
								'label' => __( 'Title', 'jet-engine' ),
								'class' => 'meta-type-control',
							),
							'name' => array(
								'type'  => 'text',
								'id'    => 'name',
								'name'  => 'name',
								'label' => __( 'Name / ID', 'jet-engine' ),
								'class' => 'meta-type-control',
							),
							'type' => array(
								'type'    => 'select',
								'id'      => 'type',
								'name'    => 'type',
								'label'   => __( 'Type', 'jet-engine' ),
								'options' => $this->control_types(),
								'class'   => 'meta-type-control',
							),
							'search_post_type' => array(
								'type'     => 'select',
								'id'       => 'search_post_type',
								'name'     => 'search_post_type',
								'label'    => __( 'Post types', 'jet-engine' ),
								'options'  => jet_engine()->listings->get_post_types_for_options(),
								'multiple' => true,
								'class'    => 'meta-type-posts',
							),
							'is_timestamp' => array(
								'type'        => 'switcher',
								'id'          => 'is_timestamp',
								'name'        => 'is_timestamp',
								'label'       => __( 'Save as timestamp. Check this if you will need to sort or query posts by date', 'jet-engine' ),
								'class'       => 'meta-type-date meta-type-datetime-local',
								'toggle'      => array(
									'true_toggle'  => 'Yes',
									'false_toggle' => 'No',
								),
							),
							'is_multiple' => array(
								'type'        => 'switcher',
								'id'          => 'is_multiple',
								'name'        => 'is_multiple',
								'label'       => __( 'Allow to select multiple values', 'jet-engine' ),
								'class'       => 'meta-type-select meta-type-posts',
								'toggle'      => array(
									'true_toggle'  => 'Yes',
									'false_toggle' => 'No',
								),
							),
							'options' => array(
								'type'        => 'repeater',
								'id'          => 'options',
								'name'        => 'options',
								'label'       => __( 'Options List', 'jet-engine' ),
								'add_label'   => __( 'New Option', 'jet-engine' ),
								'title_field' => 'value',
								'class'       => 'meta-type-select meta-type-radio meta-type-checkbox',
								'fields'      => array(
									'key' => array(
										'type'  => 'text',
										'id'    => 'key',
										'name'  => 'key',
										'label' => __( 'Value', 'jet-engine' ),
									),
									'value' => array(
										'type'  => 'text',
										'id'    => 'value',
										'name'  => 'value',
										'label' => __( 'Label', 'jet-engine' ),
									),
								),
							),
							/*'options_input' => array(
								'type'  => 'textarea',
								'id'    => 'options_input',
								'name'  => 'options_input',
								'label' => __( 'Or type options list here (enter each option on a new line, separate value and label with ":". Like this - value : Label)', 'jet-engine' ),
								'class' => 'meta-type-select meta-type-radio meta-type-checkbox',
							),*/
							'repeater-fields' => array(
								'type'        => 'repeater',
								'id'          => 'repeater-fields',
								'name'        => 'repeater-fields',
								'label'       => __( 'Repeater Fields', 'jet-engine' ),
								'add_label'   => __( 'New Field', 'jet-engine' ),
								'title_field' => 'name',
								'class'       => 'meta-type-repeater',
								'fields'      => array(
									'title' => array(
										'type'  => 'text',
										'id'    => 'title',
										'name'  => 'title',
										'label' => __( 'Title', 'jet-engine' ),
									),
									'name' => array(
										'type'  => 'text',
										'id'    => 'name',
										'name'  => 'name',
										'label' => __( 'Name / ID', 'jet-engine' ),
									),
									'type' => array(
										'type'    => 'select',
										'id'      => 'type',
										'name'    => 'type',
										'label'   => __( 'Type', 'jet-engine' ),
										'options' => $this->control_types(
											array( 'radio', 'repeater', 'select', 'checkbox', 'wysiwyg' )
										),
									),
								),
							),
							'width' => array(
								'type'    => 'select',
								'id'      => 'width',
								'name'    => 'width',
								'label'   => __( 'Field Width', 'jet-engine' ),
								'options' => array(
									'100%'      => '100%',
									'75%'       => '75%',
									'66.66666%' => '66.6%',
									'50%'       => '50%',
									'33.33333%' => '33.3%',
									'25%'       => '25%',
								),
								'class'   => 'meta-type-control',
							),
						),
					),
				)
			);

		}

		/**
		 * Returns control types list
		 *
		 * @return array
		 */
		public function control_types( $exclude = array() ) {

			$default = array(
				'text'           => __( 'Text', 'jet-engine' ),
				'date'           => __( 'Date', 'jet-engine' ),
				'time'           => __( 'Time', 'jet-engine' ),
				'datetime-local' => __( 'Datetime', 'jet-engine' ),
				'textarea'       => __( 'Textarea', 'jet-engine' ),
				'wysiwyg'        => __( 'WYSIWYG', 'jet-engine' ),
				'switcher'       => __( 'Switcher', 'jet-engine' ),
				'checkbox'       => __( 'Checkbox', 'jet-engine' ),
				'iconpicker'     => __( 'Iconpicker', 'jet-engine' ),
				'media'          => __( 'Media', 'jet-engine' ),
				'gallery'        => __( 'Gallery', 'jet-engine' ),
				'radio'          => __( 'Radio', 'jet-engine' ),
				'repeater'       => __( 'Repeater', 'jet-engine' ),
				'select'         => __( 'Select', 'jet-engine' ),
				'colorpicker'    => __( 'Colorpicker', 'jet-engine' ),
				'posts'          => __( 'Posts', 'jet-engine' ),
			);

			if ( empty( $exclude ) ) {
				return $default;
			}

			foreach ( $exclude as $excluded ) {
				unset( $default[ $excluded ] );
			}

			return $default;
		}

		/**
		 * Fiels ID
		 * @return [type] [description]
		 */
		public function field_id( $field = '' ) {
			return 'jet_post_type_' . $field;
		}

	}

}
