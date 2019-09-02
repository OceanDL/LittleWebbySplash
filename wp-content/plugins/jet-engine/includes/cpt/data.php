<?php
/**
 * CPT data controller class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT_Data' ) ) {

	/**
	 * Define Jet_Engine_CPT_Data class
	 */
	class Jet_Engine_CPT_Data {

		/**
		 * DB manager instance
		 *
		 * @var Jet_Engine_DB
		 */
		public $db = null;

		/**
		 * CPT manager instance
		 *
		 * @var Jet_Engine_CPT
		 */
		public $cpt = null;

		/**
		 * Table name
		 *
		 * @var string
		 */
		public $table = 'post_types';

		/**
		 * Table format
		 *
		 * @var string
		 */
		public $table_format = array( '%s', '%s', '%s', '%s', '%s' );

		/**
		 * Edit slug
		 *
		 * @var string
		 */
		public $edit = 'edit';

		public $raw = false;

		/**
		 * Constructir for the class
		 */
		function __construct( $cpt ) {
			$this->db  = jet_engine()->db;
			$this->cpt = $cpt;
		}

		/**
		 * Create new post type
		 *
		 * @return void
		 */
		public function create_item() {

			if ( ! current_user_can( 'manage_options' ) ) {
				$this->cpt->add_notice(
					'error',
					__( 'You don\'t have permissions to do this', 'jet-engine' )
				);
				return;
			}

			if ( ! $this->sanitize_item_request() ) {
				return;
			}

			$item = $this->sanitize_item_from_request();
			$id   = $this->update_item_in_db( $item );

			if ( ! $id ) {
				$this->cpt->add_notice(
					'error',
					__( 'Couldn\'t create item', 'jet-engine' )
				);
				return;
			}

			flush_rewrite_rules();

			wp_redirect( add_query_arg(
				array(
					'id'     => $id,
					'notice' => 'added',
				),
				$this->cpt->get_page_link( $this->edit )
			) );

			die();

		}

		public function update_item_in_db( $item ) {
			return $this->db->update( $this->table, $item, $this->table_format );
		}

		/**
		 * Update post post type
		 *
		 * @return void
		 */
		public function delete_item() {

			if ( ! current_user_can( 'manage_options' ) ) {
				$this->cpt->add_notice(
					'error',
					__( 'You don\'t have permissions to do this', 'jet-engine' )
				);
				return;
			}

			$id = isset( $_GET['id'] ) ? esc_attr( $_GET['id'] ) : false;

			if ( ! $id ) {
				$this->cpt->add_notice(
					'error',
					__( 'Please provide item ID to delete', 'jet-engine' )
				);
				return;
			}

			$this->db->delete( $this->table, array( 'id' => $id ), array( '%d' ) );

			flush_rewrite_rules();

			wp_redirect( $this->cpt->get_page_link() );
			die();

		}

		/**
		 * Update post post type
		 *
		 * @return void
		 */
		public function edit_item() {

			if ( ! current_user_can( 'manage_options' ) ) {
				$this->cpt->add_notice(
					'error',
					__( 'You don\'t have permissions to do this', 'jet-engine' )
				);
				return;
			}

			if ( ! $this->sanitize_item_request() ) {
				return;
			}

			$id = isset( $_GET['id'] ) ? esc_attr( $_GET['id'] ) : false;

			if ( ! $id ) {

				$this->cpt->add_notice(
					'error',
					__( 'Item ID not passed', 'jet-engine' )
				);

				return;
			}

			$item       = $this->sanitize_item_from_request();
			$item['id'] = $id;
			$id         = $this->update_item_in_db( $item );

			if ( ! $id ) {
				$this->cpt->add_notice(
					'error',
					__( 'Couldn\'t update item', 'jet-engine' )
				);
				return;
			}

			flush_rewrite_rules();

			wp_redirect( add_query_arg(
				array( 'id' => $id ),
				$this->cpt->get_page_link( $this->edit )
			) );

			die();

		}

		/**
		 * Sanitizr post type request
		 *
		 * @return void
		 */
		public function sanitize_item_request() {

			$valid = true;

			if ( empty( $_POST['slug'] ) ) {
				$valid = false;
				$this->cpt->add_notice(
					'error',
					__( 'Please set post type slug', 'jet-engine' )
				);
			}

			if ( empty( $_POST['slug'] ) ) {
				$valid = false;
				$this->cpt->add_notice(
					'error',
					__( 'Please set post type name', 'jet-engine' )
				);
			}

			if ( isset( $_POST['slug'] ) && in_array( $_POST['slug'], $this->items_blacklist() ) ) {
				$valid = false;
				$this->cpt->add_notice(
					'error',
					__( 'Please change post type slug. Current is reserved for WordPress needs', 'jet-engine' )
				);
			}

			/**
			 * @todo  fix validation

			if ( in_array( $_POST['slug'], get_post_types() ) ) {
				$valid = false;
				$this->cpt->add_notice(
					'error',
					__( 'Please change post type slug. Current is already used', 'jet-engine' )
				);
			}
			 */

			return $valid;
		}

		/**
		 * Sanizitze slug
		 *
		 * @param  [type] $slug [description]
		 * @return [type]       [description]
		 */
		public function sanitize_slug( $slug ) {

			$slug = esc_attr( $slug );
			$slug = strtolower( $slug );
			$slug = remove_accents( $slug );
			$slug = preg_replace( '/[^a-z0-9\s\-\_]/', '', $slug );
			$slug = str_replace( ' ', '-', $slug );

			return $slug;

		}

		/**
		 * Returns blacklisted post types slugs
		 *
		 * @return array
		 */
		private function items_blacklist() {
			return array(
				'post',
				'page',
				'attachment',
				'revision',
				'nav_menu_item',
				'custom_css',
				'customize_changeset',
				'action',
				'author',
				'order',
				'theme',
			);
		}

		/**
		 * Returns blacklisted post types slugs
		 *
		 * @return array
		 */
		private function meta_blacklist() {
			return array(
				'_wpnonce',
				'_wp_http_referer',
				'user_ID',
				'action',
				'originalaction',
				'post_author',
				'post_type',
				'original_post_status',
				'referredby',
				'_wp_original_http_referer',
				'post_ID',
				'meta-box-order-nonce',
				'closedpostboxesnonce',
				'post_title',
				'samplepermalinknonce',
				'content',
				'wp-preview',
				'hidden_post_status',
				'post_status',
				'hidden_post_password',
				'hidden_post_visibility',
				'visibility',
				'post_password',
				'mm',
				'jj',
				'aa',
				'hh',
				'mn',
				'ss',
				'hidden_mm',
				'cur_mm',
				'hidden_jj',
				'cur_jj',
				'hidden_aa',
				'cur_aa',
				'hidden_hh',
				'cur_hh',
				'hidden_mn',
				'cur_mn',
				'original_publish',
				'save',
				'post_format',
				'tax_input',
				'parent_id',
				'menu_order',
				'_thumbnail_id',
				'meta',
				'excerpt',
				'trackback_url',
				'_ajax_nonce',
				'metakeyselect',
				'metakeyinput',
				'metavalue',
				'advanced_view',
				'comment_status',
				'ping_status',
				'post_name',
				'post_author_override',
				'post_mime_type',
				'ID',
				'post_content',
				'post_excerpt',
				'post_parent',
				'to_ping',
				'screen',
				'taxonomy',
				'action',
				'tag-name',
				'slug',
				'description',
			);
		}

		/**
		 * Prepare post data from request to write into database
		 *
		 * @return array
		 */
		public function sanitize_item_from_request() {

			$request = $_POST;

			$result = array(
				'slug'        => '',
				'status'      => 'publish',
				'labels'      => array(),
				'args'        => array(),
				'meta_fields' => array(),
			);

			$slug = ! empty( $request['slug'] ) ? $this->sanitize_slug( $request['slug'] ) : false;
			$name = ! empty( $request['name'] ) ? esc_html( $request['name'] ) : false;

			if ( ! $slug ) {
				return false;
			}

			$labels = array(
				'name' => $name,
			);

			$labels_list = array(
				'singular_name',
				'menu_name',
				'name_admin_bar',
				'add_new',
				'add_new_item',
				'new_item',
				'edit_item',
				'view_item',
				'all_items',
				'search_items',
				'parent_item_colon',
				'not_found',
				'not_found_in_trash',
				'featured_image',
				'set_featured_image',
				'remove_featured_image',
				'use_featured_image',
				'archives',
				'insert_into_item',
				'uploaded_to_this_item',
			);

			foreach ( $labels_list as $label_key ) {
				if ( ! empty( $request[ $label_key ] ) ) {
					$labels[ $label_key ] = $request[ $label_key ];
				}
			}

			$args        = array();
			$ensure_bool = array(
				'public',
				'publicly_queryable',
				'show_ui',
				'show_in_menu',
				'show_in_nav_menus',
				'show_in_rest',
				'query_var',
				'rewrite',
				'has_archive',
				'hierarchical',
			);

			foreach ( $ensure_bool as $key ) {
				$args[ $key ] = ! empty( $request[ $key ] )
									? filter_var( $request[ $key ], FILTER_VALIDATE_BOOLEAN )
									: false;
			}

			$regular_args = array(
				'rewrite_slug'    => $slug,
				'capability_type' => 'post',
				'menu_position'   => null,
				'menu_icon'       => '',
				'supports'        => array(),
				'admin_columns'   => array(),
			);

			foreach ( $regular_args as $key => $default ) {
				$args[ $key ] = ! empty( $request[ $key ] ) ? $request[ $key ] : $default;
			}

			/**
			 * @todo Validate meta fields before saving - ensure that used correct types and all names was set.
			 */
			$meta_fields = ! empty( $request['meta_fields'] ) ? $request['meta_fields'] : array();

			$result['slug']        = $slug;
			$result['labels']      = $labels;
			$result['args']        = $args;
			$result['meta_fields'] = $this->sanitize_meta_fields( $meta_fields );

			return $result;

		}

		/**
		 * Sanitize meta fields
		 *
		 * @param  [type] $meta_fields [description]
		 * @return [type]              [description]
		 */
		public function sanitize_meta_fields( $meta_fields ) {

			foreach ( $meta_fields as $key => $field ) {

				// If name is empty - create it from title, else - santize it
				if ( empty( $field['name'] ) ) {
					$field['name'] = $this->sanitize_slug( $field['title'] );
				} else {
					$field['name'] = $this->sanitize_slug( $field['name'] );
				}

				// If still empty - create random name
				if ( empty( $field['name'] ) ) {
					$field['name'] = '_field_' . rand( 10000, 99999 );
				}

				// If name in blak list - add underscore at start
				if ( in_array( $field['name'], $this->meta_blacklist() ) ) {
					$meta_fields[ $key ]['name'] = '_' . $field['name'];
				} else {
					$meta_fields[ $key ]['name'] = $field['name'];
				}
			}

			return $meta_fields;
		}

		/**
		 * Ensure that required database table is exists, create if not.
		 *
		 * @return void
		 */
		public function ensure_db_table() {

			if ( ! $this->db->is_table_exists( $this->table ) ) {
				$this->db->create_table( $this->table );
			}

		}

		/**
		 * Retrieve post for edit
		 *
		 * @return array
		 */
		public function get_item_for_edit( $id ) {
			$item = $this->db->query(
				$this->table,
				array( 'id' => $id ),
				array( $this, 'filter_item_for_edit' )
			);

			if ( ! empty( $item ) ) {
				return $item[0];
			} else {
				return false;
			}

		}

		/**
		 * Returns post type in prepared for register format
		 *
		 * @return array
		 */
		public function get_item_for_register() {

			return $this->db->query(
				$this->table,
				array(),
				array( $this, 'filter_item_for_register' )
			);

		}

		/**
		 * Returns items by args without filtering
		 *
		 * @return array
		 */
		public function get_raw( $args = array() ) {
			if ( ! $this->raw ) {
				$this->raw = $this->db->query( $this->table, $args );
			}
			return $this->raw;
		}

		/**
		 * Filter post type for register
		 *
		 * @return array
		 */
		public function filter_item_for_register( $item ) {

			$result = array();

			$args                = maybe_unserialize( $item['args'] );
			$item['labels']      = maybe_unserialize( $item['labels'] );
			$item['meta_fields'] = maybe_unserialize( $item['meta_fields'] );

			$result = array_merge( $item, $args );

			if ( false !== $result['rewrite'] ) {
				$result['rewrite'] = array(
					'slug'       => $result['rewrite_slug'],
					'with_front' => true,
				);

				unset( $result['rewrite_slug'] );
			}

			unset( $result['args'] );
			unset( $result['status'] );

			return $result;
		}

		/**
		 * Filter post type for edit
		 *
		 * @return array
		 */
		public function filter_item_for_edit( $item ) {

			$result = array();

			$args                = maybe_unserialize( $item['args'] );
			$labels              = maybe_unserialize( $item['labels'] );
			$item['meta_fields'] = maybe_unserialize( $item['meta_fields'] );

			$result = array_merge( $item, $args, $labels );

			return $result;
		}

		/**
		 * Query post types
		 *
		 * @return array
		 */
		public function get_items() {
			return $types = $this->db->query( $this->table );
		}

		/**
		 * Return totals post types count
		 *
		 * @return int
		 */
		public function total_items() {
			return $this->db->count( $this->table );
		}

	}

}
