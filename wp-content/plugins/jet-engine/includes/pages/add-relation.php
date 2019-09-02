<?php
/**
 * CPTs add page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT_Page_Add_Relation' ) ) {

	/**
	 * Define Jet_Engine_CPT_Page_Add_Relation class
	 */
	class Jet_Engine_CPT_Page_Add_Relation extends Jet_Engine_CPT_Page_Base {

		/**
		 * Page slug
		 *
		 * @return string
		 */
		public function get_slug() {
			return 'add-relation';
		}

		/**
		 * Page name
		 *
		 * @return string
		 */
		public function get_name() {
			return esc_html__( 'Add New Posts Relationship', 'jet-engine' );
		}

		/**
		 * Register add controls
		 * @return [type] [description]
		 */
		public function register_controls() {

			require jet_engine()->plugin_path( 'includes/controls/relation-controls.php' );

			$controls = new Jet_Engine_CPT_Controls_Relation( array(
				'builder' => $this->manager->builder,
				'submit'  => __( 'Add Relation', 'jet-engine' ),
				'action'  => add_query_arg(
					array(
						'page'                => $this->manager->page_slug(),
						'cpt_relation_action' => 'add-relation',
						'action'              => 'create_item',
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