<?php
/**
 * CPTs add page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT_Page_Add_Tax' ) ) {

	/**
	 * Define Jet_Engine_CPT_Page_Add_Tax class
	 */
	class Jet_Engine_CPT_Page_Add_Tax extends Jet_Engine_CPT_Page_Base {

		/**
		 * Page slug
		 *
		 * @return string
		 */
		public function get_slug() {
			return 'add-tax';
		}

		/**
		 * Page name
		 *
		 * @return string
		 */
		public function get_name() {
			return esc_html__( 'Add New Taxonomy', 'jet-engine' );
		}

		/**
		 * Register add controls
		 * @return [type] [description]
		 */
		public function register_controls() {

			require jet_engine()->plugin_path( 'includes/controls/taxonomy-controls.php' );

			$controls = new Jet_Engine_CPT_Controls_Tax( array(
				'builder' => $this->manager->builder,
				'submit'  => __( 'Add Taxonomy', 'jet-engine' ),
				'action'  => add_query_arg(
					array(
						'page'           => $this->manager->page_slug(),
						'cpt_tax_action' => 'add-tax',
						'action'         => 'create_item',
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