<?php
/**
 * Relations data controller class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Relations_Data' ) ) {

	/**
	 * Define Jet_Engine_Relations_Data class
	 */
	class Jet_Engine_Relations_Data extends Jet_Engine_Meta_Boxes_Data {

		/**
		 * Edit slug
		 *
		 * @var string
		 */
		public $edit        = 'edit-relation';
		public $option_name = 'jet_engine_relations';

		/**
		 * Constructir for the class
		 */
		function __construct( $cpt ) {
			$this->cpt = $cpt;
		}

		/**
		 * Modify create item function
		 *
		 * @return [type] [description]
		 */
		public function create_item() {

			if ( empty( $_POST['post_type_1'] ) || empty( $_POST['post_type_2'] ) ) {
				$this->cpt->add_notice(
					'error',
					__( 'Please set both post types', 'jet-engine' )
				);
				return;
			}

			if ( $_POST['post_type_1'] === $_POST['post_type_2'] ) {
				$this->cpt->add_notice(
					'error',
					__( 'Parent and child post type can\'t be the same', 'jet-engine' )
				);
				return;
			}

			parent::create_item();

		}

		/**
		 * Modify create item function
		 *
		 * @return [type] [description]
		 */
		public function edit_item() {

			if ( empty( $_POST['post_type_1'] ) || empty( $_POST['post_type_2'] ) ) {
				$this->cpt->add_notice(
					'error',
					__( 'Please set both post types', 'jet-engine' )
				);
				return;
			}

			if ( $_POST['post_type_1'] === $_POST['post_type_2'] ) {
				$this->cpt->add_notice(
					'error',
					__( 'Parent and child post type can\'t be the same', 'jet-engine' )
				);
				return;
			}

			parent::edit_item();

		}

		/**
		 * Update item in DB
		 *
		 * @param  [type] $item [description]
		 * @return [type]       [description]
		 */
		public function update_item_in_db( $item ) {

			$raw        = $this->get_raw();
			$id         = isset( $item['id'] ) ? $item['id'] : 'item-' . $this->get_numeric_id();
			$item['id'] = $id;
			$raw[ $id ] = $item;

			update_option( $this->option_name, $raw );

			return $id;

		}

		/**
		 * Returns actual numeric ID
		 * @return [type] [description]
		 */
		public function get_numeric_id() {

			$raw  = $this->get_raw();
			$keys = array_keys( $raw );
			$last = end( $keys );

			if ( ! $last ) {
				return 1;
			}

			$num = absint( str_replace( 'item-', '', $last ) );

			return $num + 1;

		}

		/**
		 * Prepare post data from request to write into database
		 *
		 * @return array
		 */
		public function sanitize_item_from_request() {

			$request = $_POST;
			$args    = array(
				'name',
				'post_type_1',
				'post_type_2',
				'type',
				'post_type_1_control',
				'post_type_2_control',
			);

			$result = array();

			foreach ( $args as $key ) {
				$result[ $key ] = isset( $request[ $key ] ) ? esc_attr( $request[ $key ] ) : '';
			}

			return $result;

		}

		/**
		 * Find related posts for ppassed relation key and current post ID pair
		 *
		 * @param  [type] $meta_key [description]
		 * @param  [type] $post_id  [description]
		 * @return [type]           [description]
		 */
		public function find_related_posts( $meta_key, $post_id ) {

			global $wpdb;

			$related = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT post_id FROM $wpdb->postmeta WHERE `meta_key` = '%s' AND `meta_value` = %d;",
					$meta_key,
					$post_id
				)
			);

			return $related;

		}

		/**
		 * Delete all related meta contains passed $post_id
		 *
		 * @param  [type] $meta_key [description]
		 * @param  [type] $post_id  [description]
		 * @return [type]           [description]
		 */
		public function delete_all_related_meta( $meta_key, $post_id ) {

			delete_post_meta( $post_id, $meta_key );
			$old_related = $this->find_related_posts( $meta_key, $post_id );

			if ( ! empty( $old_related ) ) {

				foreach ( $old_related as $related_post_id ) {
					delete_post_meta( $related_post_id, $meta_key, $post_id );
				}

			}

		}

	}

}
