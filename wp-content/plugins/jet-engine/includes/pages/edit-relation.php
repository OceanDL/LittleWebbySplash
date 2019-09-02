<?php
/**
 * CPTs edit page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT_Page_Edit_Relation' ) ) {

	/**
	 * Define Jet_Engine_CPT_Page_Edit_Relation class
	 */
	class Jet_Engine_CPT_Page_Edit_Relation extends Jet_Engine_CPT_Page_Base {

		/**
		 * Page slug
		 *
		 * @return string
		 */
		public function get_slug() {
			return 'edit-relation';
		}

		/**
		 * Page name
		 *
		 * @return string
		 */
		public function get_name() {
			return esc_html__( 'Edit Relation', 'jet-engine' );
		}

		/**
		 * Register add controls
		 * @return [type] [description]
		 */
		public function register_controls() {

			$id = isset( $_GET['id'] ) ? esc_attr( $_GET['id'] ) : false;

			if ( ! $id ) {

				$this->manager->add_notice(
					'error',
					__( 'Please specify Relation ID', 'jet-engine' )
				);

				return;
			}

			if ( isset( $_GET['notice'] ) && 'added' === $_GET['notice'] ) {
				$this->manager->add_notice(
					'success',
					__( 'New relation created!', 'jet-engine' )
				);
			}

			require jet_engine()->plugin_path( 'includes/controls/relation-controls.php' );

			$post_type_data = $this->manager->data->get_item_for_edit( $id );

			$controls = new Jet_Engine_CPT_Controls_Relation( array(
				'builder' => $this->manager->builder,
				'values'  => $post_type_data,
				'submit'  => __( 'Update Relation', 'jet-engine' ),
				'action'  => add_query_arg(
					array(
						'page'            => $this->manager->page_slug(),
						'cpt_meta_action' => 'edit-relation',
						'id'              => $id,
						'action'          => 'edit_item',
					),
					esc_url( 'admin.php' )
				),
				'delete_link' => add_query_arg(
					array(
						'id'     => $id,
						'action' => 'delete_item',
					),
					$this->get_current_page_link()
				),
			) );

			$controls->register();

		}

		/**
		 * Renderer callback
		 *
		 * @return void
		 */
		public function render_page() {
			$this->manager->builder->render();
		}

	}

}