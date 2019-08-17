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

if ( ! class_exists( 'Jet_Engine_CPT_Controls_Tax' ) ) {

	/**
	 * Define Jet_Engine_CPT_Controls_Tax class
	 */
	class Jet_Engine_CPT_Controls_Tax extends Jet_Engine_CPT_Controls {

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
					'jet_taxonomy_form' => array(
						'type'   => 'form',
						'action' => $this->action,
					),
				)
			);

			$this->builder->register_section(
				array(
					'jet_taxonomy_fields' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_taxonomy_form',
					),
					'jet_taxonomy_controls' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_taxonomy_form',
						'title'  => __( 'Actions', 'jet-engine' ),
					),
				)
			);

			$this->register_actions_section( 'jet_taxonomy_controls' );

			$this->builder->register_section(
				array(
					'jet_taxonomy_general' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_taxonomy_fields',
						'title'  => __( 'General Information', 'jet-engine' ),
					),
					'jet_taxonomy_labels' => array(
						'type'        => 'section',
						'scroll'      => false,
						'parent'      => 'jet_taxonomy_fields',
						'title'       => __( 'Labels', 'jet-engine' ),
						'description' => sprintf( '<button type="button" class="cx-button cx-button-normal-style cpt-edit-labels">%s</button>', __( 'Edit', 'jet-engine' ) ),
					),
					'jet_taxonomy_args' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_taxonomy_fields',
						'title'  => __( 'Settings', 'jet-engine' ),
					),
					'jet_taxonomy_meta_fields' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_taxonomy_fields',
						'title'  => __( 'Meta Fields', 'jet-engine' ),
					),
				)
			);

			$this->builder->register_settings(
				array(
					'jet_taxonomy_general_settings' => array(
						'parent' => 'jet_taxonomy_general',
					),
					'jet_taxonomy_labels_settings' => array(
						'parent' => 'jet_taxonomy_labels',
					),
					'jet_taxonomy_args_settings' => array(
						'parent' => 'jet_taxonomy_args',
					),
					'jet_taxonomy_meta_fields_settings' => array(
						'parent' => 'jet_taxonomy_meta_fields',
					),
				)
			);

			$this->builder->register_control(
				array(
					'name' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'name' ),
						'name'        => 'name',
						'parent'      => 'jet_taxonomy_general_settings',
						'value'       => $this->get_value( 'name' ),
						'title'       => __( 'Name', 'jet-engine' ),
						'required'    => true,
					),
					'slug' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'slug' ),
						'name'        => 'slug',
						'parent'      => 'jet_taxonomy_general_settings',
						'value'       => $this->get_value( 'slug' ),
						'title'       => __( 'Slug', 'jet-engine' ),
						'required'    => true,
					),
					'object_type' => array(
						'type'     => 'select',
						'id'       => $this->field_id( 'object_type' ),
						'name'     => 'object_type',
						'parent'   => 'jet_taxonomy_general_settings',
						'value'    => $this->get_value( 'object_type' ),
						'title'    => __( 'Post Type', 'jet-engine' ),
						'options'  => jet_engine()->listings->get_post_types_for_options(),
						'multiple' => true,
						'required' => true,
					),
				)
			);

			$this->builder->register_control(
				array(
					'singular_name' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'singular_name' ),
						'name'        => 'singular_name',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'singular_name' ),
						'title'       => __( 'Post type singular name', 'jet-engine' ),
					),
					'menu_name' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'menu_name' ),
						'name'        => 'menu_name',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'menu_name' ),
						'title'       => __( 'Admin Menu text', 'jet-engine' ),
					),
					'all_items' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'all_items' ),
						'name'        => 'all_items',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'all_items' ),
						'title'       => __( 'All Items', 'jet-engine' ),
					),
					'edit_item' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'edit_item' ),
						'name'        => 'edit_item',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'edit_item' ),
						'title'       => __( 'Edit Item', 'jet-engine' ),
					),
					'view_item' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'view_item' ),
						'name'        => 'view_item',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'view_item' ),
						'title'       => __( 'View Item', 'jet-engine' ),
					),
					'add_new_item' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'add_new_item' ),
						'name'        => 'add_new_item',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'add_new_item' ),
						'title'       => __( 'New Item', 'jet-engine' ),
					),
					'update_item' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'update_item' ),
						'name'        => 'update_item',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'update_item' ),
						'title'       => __( 'Update Item', 'jet-engine' ),
					),
					'new_item_name' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'new_item_name' ),
						'name'        => 'new_item_name',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'new_item_name' ),
						'title'       => __( 'New Item Name', 'jet-engine' ),
					),
					'search_items' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'search_items' ),
						'name'        => 'search_items',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'search_items' ),
						'title'       => __( 'Search for items', 'jet-engine' ),
					),
					'parent_item' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'parent_item' ),
						'name'        => 'parent_item',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'parent_item' ),
						'title'       => __( 'Parent item text', 'jet-engine' ),
					),
					'parent_item_colon' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'parent_item_colon' ),
						'name'        => 'parent_item_colon',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'parent_item_colon' ),
						'title'       => __( 'Parent Item', 'jet-engine' ),
					),
					'popular_items' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'popular_items' ),
						'name'        => 'popular_items',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'popular_items' ),
						'title'       => __( 'The popular items text', 'jet-engine' ),
					),
					'separate_items_with_commas' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'separate_items_with_commas' ),
						'name'        => 'separate_items_with_commas',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'separate_items_with_commas' ),
						'title'       => __( 'The separate item with commas text', 'jet-engine' ),
					),
					'add_or_remove_items' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'add_or_remove_items' ),
						'name'        => 'add_or_remove_items',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'add_or_remove_items' ),
						'title'       => __( 'The add or remove items text', 'jet-engine' ),
					),
					'choose_from_most_used' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'choose_from_most_used' ),
						'name'        => 'choose_from_most_used',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'choose_from_most_used' ),
						'title'       => __( 'The choose from most used text used', 'jet-engine' ),
					),
					'not_found' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'not_found' ),
						'name'        => 'not_found',
						'parent'      => 'jet_taxonomy_labels_settings',
						'value'       => $this->get_value( 'not_found' ),
						'title'       => __( 'Not found label', 'jet-engine' ),
					),
				)
			);

			$this->builder->register_control(
				array(
					'public' => array(
						'type'        => 'switcher',
						'id'          => $this->field_id( 'public' ),
						'name'        => 'public',
						'parent'      => 'jet_taxonomy_args_settings',
						'value'       => $this->get_value( 'public', true ),
						'title'       => esc_html__( 'Is Public', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
					'publicly_queryable' => array(
						'type'        => 'switcher',
						'id'          => $this->field_id( 'publicly_queryable' ),
						'name'        => 'publicly_queryable',
						'parent'      => 'jet_taxonomy_args_settings',
						'value'       => $this->get_value( 'publicly_queryable', true ),
						'title'       => esc_html__( 'Publicly Queryable', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
					'show_ui' => array(
						'type'        => 'switcher',
						'id'          => $this->field_id( 'show_ui' ),
						'name'        => 'show_ui',
						'parent'      => 'jet_taxonomy_args_settings',
						'value'       => $this->get_value( 'show_ui', true ),
						'title'       => esc_html__( 'Show Admin UI', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
					'show_in_menu' => array(
						'type'        => 'switcher',
						'id'          => $this->field_id( 'show_in_menu' ),
						'name'        => 'show_in_menu',
						'parent'      => 'jet_taxonomy_args_settings',
						'value'       => $this->get_value( 'show_in_menu', true ),
						'title'       => esc_html__( 'Show in Admin Menu', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
					'show_in_nav_menus' => array(
						'type'        => 'switcher',
						'id'          => $this->field_id( 'show_in_nav_menus' ),
						'name'        => 'show_in_nav_menus',
						'parent'      => 'jet_taxonomy_args_settings',
						'value'       => $this->get_value( 'show_in_nav_menus', true ),
						'title'       => esc_html__( 'Show in Nav Menu', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
					'show_in_rest' => array(
						'type'        => 'switcher',
						'id'          => $this->field_id( 'show_in_rest' ),
						'name'        => 'show_in_rest',
						'parent'      => 'jet_taxonomy_args_settings',
						'value'       => $this->get_value( 'show_in_rest', true ),
						'title'       => esc_html__( 'Show in Rest API (allow tax in Gutenberg editor)', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
					'query_var' => array(
						'type'        => 'switcher',
						'id'          => $this->field_id( 'query_var' ),
						'name'        => 'query_var',
						'parent'      => 'jet_taxonomy_args_settings',
						'value'       => $this->get_value( 'query_var', true ),
						'title'       => esc_html__( 'Register Query Var', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
					'rewrite' => array(
						'type'        => 'switcher',
						'id'          => $this->field_id( 'rewrite' ),
						'name'        => 'rewrite',
						'parent'      => 'jet_taxonomy_args_settings',
						'value'       => $this->get_value( 'rewrite', true ),
						'title'       => esc_html__( 'Rewrite', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
					'rewrite_slug' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'rewrite_slug' ),
						'name'        => 'rewrite_slug',
						'parent'      => 'jet_taxonomy_args_settings',
						'value'       => $this->get_value( 'rewrite_slug' ),
						'placeholder' => __( 'Post type slug will be used if empty', 'jet-engine' ),
						'title'       => __( 'Rewrite Slug', 'jet-engine' ),
					),
					'hierarchical' => array(
						'type'        => 'switcher',
						'id'          => $this->field_id( 'hierarchical' ),
						'name'        => 'hierarchical',
						'parent'      => 'jet_taxonomy_args_settings',
						'value'       => $this->get_value( 'hierarchical' ),
						'title'       => esc_html__( 'Hierarchical', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
				)
			);

			$this->meta_controls->register(
				$this->get_value( 'meta_fields', array() ),
				'jet_taxonomy_meta_fields_settings'
			);

		}

	}

}
