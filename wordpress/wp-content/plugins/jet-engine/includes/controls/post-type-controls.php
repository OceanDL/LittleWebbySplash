<?php
/**
 * CPT controls instance
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT_Controls' ) ) {

	/**
	 * Define Jet_Engine_CPT_Controls class
	 */
	class Jet_Engine_CPT_Controls {

		public $builder;
		public $action;
		public $values;
		public $submit;
		public $delete_link;
		public $meta_controls;

		/**
		 * Constructor for the class
		 */
		function __construct( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'builder'     => false,
				'action'      => '',
				'submit'      => __( 'Add Post Type', 'jet-engine' ),
				'delete_link' => '',
				'values'      => $_POST,
			) );

			$this->builder     = $args['builder'];
			$this->action      = $args['action'];
			$this->values      = $args['values'];
			$this->submit      = $args['submit'];
			$this->delete_link = $args['delete_link'];

			require jet_engine()->plugin_path( 'includes/controls/meta-fields-controls.php' );
			$this->meta_controls = new Jet_Engine_Meta_Fields_Controls( $this->builder );

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

		/**
		 * Fiels ID
		 * @return [type] [description]
		 */
		public function field_id( $field = '' ) {
			return 'jet_post_type_' . $field;
		}

		/**
		 * Register section with controls - save, delete etc.
		 * @param  [type] $parent [description]
		 * @return [type]         [description]
		 */
		public function register_actions_section( $parent ) {

			$delete = '';

			if ( ! empty( $this->delete_link ) ) {
				$delete = sprintf(
					'<a href="%1$s" class="cpt-delete">%2$s</a>',
					$this->delete_link,
					__( 'Delete', 'jet-engine' )
				);
			}

			$this->builder->register_html(
				array(
					'save_button' => array(
						'type'   => 'html',
						'parent' => $parent,
						'class'  => 'cx-control dialog-save',
						'html'   => sprintf(
							'<button type="submit" class="cx-button cx-button-primary-style">%s</button>',
							$this->submit
						) . $delete,
					),
				)
			);

		}

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
					'jet_post_type_form' => array(
						'type'   => 'form',
						'action' => $this->action,
					),
				)
			);

			$this->builder->register_section(
				array(
					'jet_post_type_fields' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_post_type_form',
					),
					'jet_post_type_controls' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_post_type_form',
						'title'  => __( 'Actions', 'jet-engine' ),
					),
				)
			);

			$this->register_actions_section( 'jet_post_type_controls' );

			$this->builder->register_section(
				array(
					'jet_post_type_general' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_post_type_fields',
						'title'  => __( 'General Information', 'jet-engine' ),
					),
					'jet_post_type_labels' => array(
						'type'        => 'section',
						'scroll'      => false,
						'parent'      => 'jet_post_type_fields',
						'title'       => __( 'Labels', 'jet-engine' ),
						'description' => sprintf( '<button type="button" class="cx-button cx-button-normal-style cpt-edit-labels">%s</button>', __( 'Edit', 'jet-engine' ) ),
					),
					'jet_post_type_args' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_post_type_fields',
						'title'  => __( 'Settings', 'jet-engine' ),
					),
					'jet_post_type_meta_fields' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_post_type_fields',
						'title'  => __( 'Meta Fields', 'jet-engine' ),
					),
					'jet_post_type_admin_columns' => array(
						'type'   => 'section',
						'scroll' => false,
						'parent' => 'jet_post_type_fields',
						'title'  => __( 'Admin Columns', 'jet-engine' ),
					),
				)
			);

			$this->builder->register_settings(
				array(
					'jet_post_type_general_settings' => array(
						'parent' => 'jet_post_type_general',
					),
					'jet_post_type_labels_settings' => array(
						'parent' => 'jet_post_type_labels',
					),
					'jet_post_type_args_settings' => array(
						'parent' => 'jet_post_type_args',
					),
					'jet_post_type_meta_fields_settings' => array(
						'parent' => 'jet_post_type_meta_fields',
					),
					'jet_post_type_admin_columns_settings' => array(
						'parent' => 'jet_post_type_admin_columns',
					),
				)
			);

			$this->builder->register_control(
				array(
					'name' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'name' ),
						'name'        => 'name',
						'parent'      => 'jet_post_type_general_settings',
						'value'       => $this->get_value( 'name' ),
						'title'       => __( 'Name', 'jet-engine' ),
						'required'    => true,
					),
					'slug' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'slug' ),
						'name'        => 'slug',
						'parent'      => 'jet_post_type_general_settings',
						'value'       => $this->get_value( 'slug' ),
						'title'       => __( 'Slug', 'jet-engine' ),
						'required'    => true,
					),
				)
			);

			$this->builder->register_control(
				array(
					'singular_name' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'singular_name' ),
						'name'        => 'singular_name',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'singular_name' ),
						'title'       => __( 'Post type singular name', 'jet-engine' ),
					),
					'menu_name' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'menu_name' ),
						'name'        => 'menu_name',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'menu_name' ),
						'title'       => __( 'Admin Menu text', 'jet-engine' ),
					),
					'name_admin_bar' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'name_admin_bar' ),
						'name'        => 'name_admin_bar',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'name_admin_bar' ),
						'title'       => __( 'Add New on Toolbar', 'jet-engine' ),
					),
					'add_new' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'add_new' ),
						'name'        => 'add_new',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'add_new' ),
						'title'       => __( 'Add New', 'jet-engine' ),
					),
					'add_new_item' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'add_new_item' ),
						'name'        => 'add_new_item',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'add_new_item' ),
						'title'       => __( 'Add New Item', 'jet-engine' ),
					),
					'new_item' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'new_item' ),
						'name'        => 'new_item',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'new_item' ),
						'title'       => __( 'New Item', 'jet-engine' ),
					),
					'edit_item' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'edit_item' ),
						'name'        => 'edit_item',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'edit_item' ),
						'title'       => __( 'Edit Item', 'jet-engine' ),
					),
					'view_item' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'view_item' ),
						'name'        => 'view_item',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'view_item' ),
						'title'       => __( 'View Item', 'jet-engine' ),
					),
					'all_items' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'all_items' ),
						'name'        => 'all_items',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'all_items' ),
						'title'       => __( 'All Items', 'jet-engine' ),
					),
					'search_items' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'search_items' ),
						'name'        => 'search_items',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'search_items' ),
						'title'       => __( 'Search for items', 'jet-engine' ),
					),
					'parent_item_colon' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'parent_item_colon' ),
						'name'        => 'parent_item_colon',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'parent_item_colon' ),
						'title'       => __( 'Parent Item', 'jet-engine' ),
					),
					'not_found' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'not_found' ),
						'name'        => 'not_found',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'not_found' ),
						'title'       => __( 'Not found label', 'jet-engine' ),
					),
					'not_found_in_trash' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'not_found_in_trash' ),
						'name'        => 'not_found_in_trash',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'not_found_in_trash' ),
						'title'       => __( 'Not found in trash label', 'jet-engine' ),
					),
					'featured_image' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'featured_image' ),
						'name'        => 'featured_image',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'featured_image' ),
						'title'       => __( 'Override the "Featured Image" label', 'jet-engine' ),
					),
					'set_featured_image' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'set_featured_image' ),
						'name'        => 'set_featured_image',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'set_featured_image' ),
						'title'       => __( 'Override the "Set Featured Image" label', 'jet-engine' ),
					),
					'remove_featured_image' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'remove_featured_image' ),
						'name'        => 'remove_featured_image',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'remove_featured_image' ),
						'title'       => __( 'Override the "Remove Featured Image" label', 'jet-engine' ),
					),
					'use_featured_image' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'use_featured_image' ),
						'name'        => 'use_featured_image',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'use_featured_image' ),
						'title'       => __( 'Override the "Use Featured Image" label', 'jet-engine' ),
					),
					'archives' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'archives' ),
						'name'        => 'archives',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'archives' ),
						'title'       => __( 'The post type archive label used in nav menus', 'jet-engine' ),
					),
					'insert_into_item' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'insert_into_item' ),
						'name'        => 'insert_into_item',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'insert_into_item' ),
						'title'       => __( 'Override the "Insert into post" page label', 'jet-engine' ),
					),
					'uploaded_to_this_item' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'uploaded_to_this_item' ),
						'name'        => 'uploaded_to_this_item',
						'parent'      => 'jet_post_type_labels_settings',
						'value'       => $this->get_value( 'uploaded_to_this_item' ),
						'title'       => __( 'Overrides the "Uploaded to this post"', 'jet-engine' ),
					),
				)
			);

			$supports_options = array(
				'title'           => __( 'Title', 'jet-engine' ),
				'editor'          => __( 'Editor', 'jet-engine' ),
				'comments'        => __( 'Comments', 'jet-engine' ),
				'revisions'       => __( 'Revisions', 'jet-engine' ),
				'trackbacks'      => __( 'Trackbacks', 'jet-engine' ),
				'author'          => __( 'Author', 'jet-engine' ),
				'excerpt'         => __( 'Excerpt', 'jet-engine' ),
				'page-attributes' => __( 'Page Attributes', 'jet-engine' ),
				'thumbnail'       => __( 'Thumbnail (Featured Image)', 'jet-engine' ),
				'custom-fields'   => __( 'Custom Fields', 'jet-engine' ),
				'post-formats'    => __( 'Post Formats', 'jet-engine' )
			);

			$this->builder->register_control(
				array(
					'public' => array(
						'type'        => 'switcher',
						'id'          => $this->field_id( 'public' ),
						'name'        => 'public',
						'parent'      => 'jet_post_type_args_settings',
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
						'parent'      => 'jet_post_type_args_settings',
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
						'parent'      => 'jet_post_type_args_settings',
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
						'parent'      => 'jet_post_type_args_settings',
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
						'parent'      => 'jet_post_type_args_settings',
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
						'parent'      => 'jet_post_type_args_settings',
						'value'       => $this->get_value( 'show_in_rest', true ),
						'title'       => esc_html__( 'Show in Rest API (allow Gutenberg editor)', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
					'query_var' => array(
						'type'        => 'switcher',
						'id'          => $this->field_id( 'query_var' ),
						'name'        => 'query_var',
						'parent'      => 'jet_post_type_args_settings',
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
						'parent'      => 'jet_post_type_args_settings',
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
						'parent'      => 'jet_post_type_args_settings',
						'value'       => $this->get_value( 'rewrite_slug' ),
						'placeholder' => __( 'Post type slug will be used if empty', 'jet-engine' ),
						'title'       => __( 'Rewrite Slug', 'jet-engine' ),
					),
					'capability_type' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'capability_type' ),
						'name'        => 'capability_type',
						'parent'      => 'jet_post_type_args_settings',
						'value'       => $this->get_value( 'capability_type', 'post' ),
						'title'       => __( 'Capability Type', 'jet-engine' ),
					),
					'has_archive' => array(
						'type'        => 'switcher',
						'id'          => $this->field_id( 'has_archive' ),
						'name'        => 'has_archive',
						'parent'      => 'jet_post_type_args_settings',
						'value'       => $this->get_value( 'has_archive', true ),
						'title'       => esc_html__( 'Has Archive', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
					'hierarchical' => array(
						'type'        => 'switcher',
						'id'          => $this->field_id( 'hierarchical' ),
						'name'        => 'hierarchical',
						'parent'      => 'jet_post_type_args_settings',
						'value'       => $this->get_value( 'hierarchical' ),
						'title'       => esc_html__( 'Hierarchical', 'jet-engine' ),
						'toggle'      => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
					),
					'menu_position' => array(
						'type'        => 'text',
						'id'          => $this->field_id( 'menu_position' ),
						'name'        => 'menu_position',
						'parent'      => 'jet_post_type_args_settings',
						'value'       => $this->get_value( 'menu_position' ),
						'title'       => __( 'Menu Position', 'jet-engine' ),
					),
					'menu_icon' => array(
						'type'        => 'iconpicker',
						'id'          => $this->field_id( 'menu_icon' ),
						'name'        => 'menu_icon',
						'parent'      => 'jet_post_type_args_settings',
						'value'       => $this->get_value( 'menu_icon' ),
						'title'       => __( 'Menu Icon', 'jet-engine' ),
						'icon_data'   => array(
							'icon_set'    => 'jetEngineMenuIcons',
							'icon_css'    => false,
							'icon_base'   => 'dashicons',
							'icon_prefix' => 'dashicons-',
							'icons'       => $this->get_icons_set(),
						),
					),
					'supports' => array(
						'type'     => 'select',
						'id'       => $this->field_id( 'supports' ),
						'name'     => 'supports',
						'parent'   => 'jet_post_type_args_settings',
						'value'    => $this->get_value( 'supports', array( 'title', 'editor' ) ),
						'options'  => $supports_options,
						'title'    => __( 'Supports', 'jet-engine' ),
						'multiple' => true,
					),
				)
			);

			$this->builder->register_control(
				array(
					'admin_columns' => array(
						'type'        => 'repeater',
						'id'          => $this->field_id( 'admin_columns' ),
						'name'        => 'admin_columns',
						'parent'      => 'jet_post_type_admin_columns_settings',
						'value'       => $this->get_value( 'admin_columns' ),
						'label'       => __( 'Admin Columns', 'jet-engine' ),
						'add_label'   => __( 'New Column', 'jet-engine' ),
						'title_field' => 'title',
						'fields'      => array(
							'title' => array(
								'type'  => 'text',
								'id'    => 'title',
								'name'  => 'title',
								'label' => __( 'Title', 'jet-engine' ),
								'class' => 'meta-type-control',
							),
							'type' => array(
								'type'    => 'select',
								'id'      => 'type',
								'name'    => 'type',
								'label'   => __( 'Type', 'jet-engine' ),
								'options' => array(
									'meta_value'      => __( 'Meta Value', 'jet-engine' ),
									'post_terms'      => __( 'Post Terms', 'jet-engine' ),
									'custom_callback' => __( 'Custom Callback', 'jet-engine' ),
								),
								'class'   => 'meta-type-control',
							),
							'meta_field' => array(
								'type'  => 'text',
								'id'    => 'meta_field',
								'name'  => 'meta_field',
								'label' => __( 'Value from meta field', 'jet-engine' ),
								'class' => 'meta-type-control meta-type-meta_value meta-type-active',
							),
							'taxonomy' => array(
								'type'  => 'text',
								'id'    => 'taxonomy',
								'name'  => 'taxonomy',
								'label' => __( 'Taxonomy', 'jet-engine' ),
								'class' => 'meta-type-control meta-type-post_terms',
							),
							'callback' => array(
								'type'  => 'text',
								'id'    => 'callback',
								'name'  => 'callback',
								'label' => __( 'Callback function name', 'jet-engine' ),
								'class' => 'meta-type-control meta-type-custom_callback',
							),
							'position' => array(
								'type'  => 'text',
								'id'    => 'position',
								'name'  => 'position',
								'label' => __( 'Column order', 'jet-engine' ),
								'class' => 'meta-type-control',
							),
							'prefix' => array(
								'type'  => 'text',
								'id'    => 'prefix',
								'name'  => 'prefix',
								'label' => __( 'Value prefix', 'jet-engine' ),
								'class' => 'meta-type-control',
							),
							'suffix' => array(
								'type'  => 'text',
								'id'    => 'suffix',
								'name'  => 'suffix',
								'label' => __( 'Value suffix', 'jet-engine' ),
								'class' => 'meta-type-control',
							),
						)
					),
				)
			);


			$this->meta_controls->register(
				$this->get_value( 'meta_fields', array() ),
				'jet_post_type_meta_fields_settings'
			);

		}

		public function get_icons_set() {
			return array(
				'menu','admin-site','dashboard','admin-media','admin-page','admin-comments','admin-appearance','admin-plugins','admin-users','admin-tools','admin-settings','admin-network','admin-generic','admin-home','admin-collapse','filter','admin-customizer','admin-multisite','admin-links','format-links','admin-post','format-standard','format-image','format-gallery','format-audio','format-video','format-chat','format-status','format-aside','format-quote','welcome-write-blog','welcome-edit-page','welcome-add-page','welcome-view-site','welcome-widgets-menus','welcome-comments','welcome-learn-more','image-crop','image-rotate','image-rotate-left','image-rotate-right','image-flip-vertical','image-flip-horizontal','image-filter','undo','redo','editor-bold','editor-italic','editor-ul','editor-ol','editor-quote','editor-alignleft','editor-aligncenter','editor-alignright','editor-insertmore','editor-spellcheck','editor-distractionfree','editor-expand','editor-contract','editor-kitchensink','editor-underline','editor-justify','editor-textcolor','editor-paste-word','editor-paste-text','editor-removeformatting','editor-video','editor-customchar','editor-outdent','editor-indent','editor-help','editor-strikethrough','editor-unlink','editor-rtl','editor-break','editor-code','editor-paragraph','editor-table','align-left','align-right','align-center','align-none','lock','unlock','calendar','calendar-alt','visibility','hidden','post-status','edit','post-trash','trash','sticky','external','arrow-up','arrow-down','arrow-left','arrow-right','arrow-up-alt','arrow-down-alt','arrow-left-alt','arrow-right-alt','arrow-up-alt2','arrow-down-alt2','arrow-left-alt2','arrow-right-alt2','leftright','sort','randomize','list-view','exerpt-view','excerpt-view','grid-view','move','hammer','art','migrate','performance','universal-access','universal-access-alt','tickets','nametag','clipboard','heart','megaphone','schedule','wordpress','wordpress-alt','pressthis','update','screenoptions','cart','feedback','cloud','translation','tag','category','archive','tagcloud','text','media-archive','media-audio','media-code','media-default','media-document','media-interactive','media-spreadsheet','media-text','media-video','playlist-audio','playlist-video','controls-play','controls-pause','controls-forward','controls-skipforward','controls-back','controls-skipback','controls-repeat','controls-volumeon','controls-volumeoff','yes','no','no-alt','plus','plus-alt','plus-alt2','minus','dismiss','marker','star-filled','star-half','star-empty','flag','info','warning','share','share1','share-alt','share-alt2','twitter','rss','email','email-alt','facebook','facebook-alt','networking','googleplus','location','location-alt','camera','images-alt','images-alt2','video-alt','video-alt2','video-alt3','vault','shield','shield-alt','sos','search','slides','analytics','chart-pie','chart-bar','chart-line','chart-area','groups','businessman','id','id-alt','products','awards','forms','testimonial','portfolio','book','book-alt','download','upload','backup','clock','lightbulb','microphone','desktop','laptop','tablet','smartphone','phone','smiley','index-card','carrot','building','store','album','palmtree','tickets-alt','money','thumbs-up','thumbs-down','layout','paperclip',
			);
		}

	}

}
