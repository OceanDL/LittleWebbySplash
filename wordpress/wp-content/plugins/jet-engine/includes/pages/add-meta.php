<?php
/**
 * CPTs add page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT_Page_Add_Meta' ) ) {

	/**
	 * Define Jet_Engine_CPT_Page_Add_Meta class
	 */
	class Jet_Engine_CPT_Page_Add_Meta extends Jet_Engine_CPT_Page_Base {

		/**
		 * Page slug
		 *
		 * @return string
		 */
		public function get_slug() {
			return 'add-meta';
		}

		/**
		 * Page name
		 *
		 * @return string
		 */
		public function get_name() {
			return esc_html__( 'Add New Meta Box', 'jet-engine' );
		}

		/**
		 * Register add controls
		 * @return [type] [description]
		 */
		public function register_controls() {

			require jet_engine()->plugin_path( 'includes/controls/meta-box-controls.php' );

			$controls = new Jet_Engine_CPT_Controls_Meta( array(
				'builder' => $this->manager->builder,
				'submit'  => __( 'Add Meta Box', 'jet-engine' ),
				'action'  => add_query_arg(
					array(
						'page'            => $this->manager->page_slug(),
						'cpt_meta_action' => 'add-meta',
						'action'          => 'create_item',
					),
					esc_url( 'admin.php' )
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