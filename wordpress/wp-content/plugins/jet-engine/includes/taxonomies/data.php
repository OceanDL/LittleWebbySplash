<?php
/**
 * CPT data controller class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT_Tax_Data' ) ) {

	/**
	 * Define Jet_Engine_CPT_Tax_Data class
	 */
	class Jet_Engine_CPT_Tax_Data extends Jet_Engine_CPT_Data {

		/**
		 * Table name
		 *
		 * @var string
		 */
		public $table = 'taxonomies';

		/**
		 * Table format
		 *
		 * @var string
		 */
		public $table_format = array( '%s', '%s', '%s', '%s', '%s', '%s' );

		/**
		 * Edit slug
		 *
		 * @var string
		 */
		public $edit = 'edit-tax';

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
					__( 'Please set taxonomy slug', 'jet-engine' )
				);
			}

			if ( empty( $_POST['slug'] ) ) {
				$valid = false;
				$this->cpt->add_notice(
					'error',
					__( 'Please set taxonomy name', 'jet-engine' )
				);
			}

			if ( empty( $_POST['object_type'] ) ) {
				$valid = false;
				$this->cpt->add_notice(
					'error',
					__( 'Please set post type for taxonomy', 'jet-engine' )
				);
			}

			if ( isset( $_POST['slug'] ) && in_array( $_POST['slug'], $this->items_blacklist() ) ) {
				$valid = false;
				$this->cpt->add_notice(
					'error',
					__( 'Please change taxonomy slug. Current is reserved for WordPress needs', 'jet-engine' )
				);
			}

			return $valid;
		}

		/**
		 * Returns blacklisted post types slugs
		 *
		 * @return array
		 */
		private function items_blacklist() {
			return array(
				'attachment',
				'attachment_id',
				'author',
				'author_name',
				'calendar',
				'cat',
				'category',
				'category__and',
				'category__in',
				'category__not_in',
				'category_name',
				'comments_per_page',
				'comments_popup',
				'customize_messenger_channel',
				'customized',
				'cpage',
				'day',
				'debug',
				'error',
				'exact',
				'feed',
				'fields',
				'hour',
				'link_category',
				'm',
				'minute',
				'monthnum',
				'more',
				'name',
				'nav_menu',
				'nonce',
				'nopaging',
				'offset',
				'order',
				'orderby',
				'p',
				'page',
				'page_id',
				'paged',
				'pagename',
				'pb',
				'perm',
				'post',
				'post__in',
				'post__not_in',
				'post_format',
				'post_mime_type',
				'post_status',
				'post_tag',
				'post_type',
				'posts',
				'posts_per_archive_page',
				'posts_per_page',
				'preview',
				'robots',
				's',
				'search',
				'second',
				'sentence',
				'showposts',
				'static',
				'subpost',
				'subpost_id',
				'tag',
				'tag__and',
				'tag__in',
				'tag__not_in',
				'tag_id',
				'tag_slug__and',
				'tag_slug__in',
				'taxonomy',
				'tb',
				'term',
				'theme',
				'type',
				'w',
				'withcomments',
				'withoutcomments',
				'year',
			);
		}

		/**
		 * Returns blacklisted post types slugs
		 *
		 * @return array
		 */
		private function meta_blacklist() {
			return array(
				'action',
				'screen',
				'taxonomy',
				'action',
				'post_type',
				'_wp_http_referer',
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
				'name',
				'singular_name',
				'menu_name',
				'all_items',
				'edit_item',
				'view_item',
				'update_item',
				'add_new_item',
				'new_item_name',
				'parent_item',
				'parent_item_colon',
				'search_items',
				'popular_items',
				'separate_items_with_commas',
				'add_or_remove_items',
				'choose_from_most_used',
				'not_found',
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
				'hierarchical',
			);

			foreach ( $ensure_bool as $key ) {
				$args[ $key ] = ! empty( $request[ $key ] )
									? filter_var( $request[ $key ], FILTER_VALIDATE_BOOLEAN )
									: false;
			}

			$regular_args = array(
				'rewrite_slug' => $slug,
			);

			foreach ( $regular_args as $key => $default ) {
				$args[ $key ] = ! empty( $request[ $key ] ) ? $request[ $key ] : $default;
			}

			/**
			 * @todo Validate meta fields before saving - ensure that used correct types and all names was set.
			 */
			$meta_fields = ! empty( $request['meta_fields'] ) ? $request['meta_fields'] : array();

			$result['slug']        = $slug;
			$result['object_type'] = $request['object_type'];
			$result['labels']      = $labels;
			$result['args']        = $args;
			$result['meta_fields'] = $this->sanitize_meta_fields( $meta_fields );

			return $result;

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
			$item['object_type'] = maybe_unserialize( $item['object_type'] );

			$result = array_merge( $item, $args );

			if ( false !== $result['rewrite'] ) {
				$result['rewrite'] = array(
					'slug' => $result['rewrite_slug'],
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

			$result['object_type'] = maybe_unserialize( $item['object_type'] );

			return $result;
		}

	}

}
