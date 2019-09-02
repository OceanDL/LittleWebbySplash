<?php
/**
 * CPTs edit page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT_Page_Edit_Meta' ) ) {

	/**
	 * Define Jet_Engine_CPT_Page_Edit_Meta class
	 */
	class Jet_Engine_CPT_Page_Edit_Meta extends Jet_Engine_CPT_Page_Base {

		/**
		 * Page slug
		 *
		 * @return string
		 */
		public function get_slug() {
			return 'edit-meta';
		}

		/**
		 * Page name
		 *
		 * @return string
		 */
		public function get_name() {
			return esc_html__( 'Edit Meta Box', 'jet-engine' );
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
					__( 'Please specify Meta Box ID', 'jet-engine' )
				);

				return;
			}

			if ( isset( $_GET['notice'] ) && 'added' === $_GET['notice'] ) {
				$this->manager->add_notice(
					'success',
					__( 'New meta box created!', 'jet-engine' )
				);
			}

			require jet_engine()->plugin_path( 'includes/controls/meta-box-controls.php' );

			$post_type_data = $this->manager->data->get_item_for_edit( $id );

			$controls = new Jet_Engine_CPT_Controls_Meta( array(
				'builder' => $this->manager->builder,
				'values'  => $post_type_data,
				'submit'  => __( 'Update Meta Box', 'jet-engine' ),
				'action'  => add_query_arg(
					array(
						'page'       => $this->manager->page_slug(),
						'cpt_meta_action' => 'edit-meta',
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