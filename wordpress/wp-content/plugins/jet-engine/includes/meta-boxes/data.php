<?php
/**
 * CPT data controller class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Meta_Boxes_Data' ) ) {

	/**
	 * Define Jet_Engine_Meta_Boxes_Data class
	 */
	class Jet_Engine_Meta_Boxes_Data extends Jet_Engine_CPT_Data {

		/**
		 * Edit slug
		 *
		 * @var string
		 */
		public $edit        = 'edit-meta';
		public $option_name = 'jet_engine_meta_boxes';

		/**
		 * Constructir for the class
		 */
		function __construct( $cpt ) {
			$this->cpt = $cpt;
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

			$raw = $this->get_raw();

			if ( isset( $raw[ $id ] ) ) {
				unset( $raw[ $id ] );
				update_option( $this->option_name, $raw );
			}

			wp_redirect( $this->cpt->get_page_link() );
			die();

		}

		/**
		 * Update item in DB
		 *
		 * @param  [type] $item [description]
		 * @return [type]       [description]
		 */
		public function update_item_in_db( $item ) {

			$raw        = $this->get_raw();
			$id         = isset( $item['id'] ) ? $item['id'] : 'meta-' . $this->get_numeric_id();
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

			$num = absint( str_replace( 'meta-', '', $last ) );

			return $num + 1;

		}

		/**
		 * Sanitizr post type request
		 *
		 * @return void
		 */
		public function sanitize_item_request() {

			$valid = true;
			return $valid;

		}

		/**
		 * Prepare post data from request to write into database
		 *
		 * @return array
		 */
		public function sanitize_item_from_request() {

			$request = $_POST;
			$args    = array();

			if ( ! empty( $request['args'] ) ) {
				foreach ( $request['args'] as $arg => $value ) {
					$args[ $arg ] = ! is_array( $value ) ? esc_attr( $value ) : $value;
				}
			}

			$meta_fields = ! empty( $request['meta_fields'] ) ? $request['meta_fields'] : array();

			$result['args']        = $args;
			$result['meta_fields'] = $this->sanitize_meta_fields( $meta_fields );

			return $result;

		}

		/**
		 * Retrieve post for edit
		 *
		 * @return array
		 */
		public function get_item_for_edit( $id ) {
			$raw = $this->get_raw();
			return isset( $raw[ $id ] ) ? $raw[ $id ] : array();
		}

		/**
		 * Returns post type in prepared for register format
		 *
		 * @return array
		 */
		public function get_item_for_register() {
			return $this->get_raw();
		}

		/**
		 * Returns items by args without filtering
		 *
		 * @return array
		 */
		public function get_raw( $args = array() ) {

			if ( ! $this->raw ) {
				$this->raw = get_option( $this->option_name, array() );
			}

			return $this->raw;
		}

		/**
		 * Query post types
		 *
		 * @return array
		 */
		public function get_items() {
			return $this->get_raw();
		}

		/**
		 * Return totals post types count
		 *
		 * @return int
		 */
		public function total_items() {
			return count( $this->get_raw() );
		}

	}

}
