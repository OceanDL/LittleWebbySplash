<?php
/**
 * CPTs list page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT_Page_List_Taxes' ) ) {

	/**
	 * Define Jet_Engine_CPT_Page_List_Taxes class
	 */
	class Jet_Engine_CPT_Page_List_Taxes extends Jet_Engine_CPT_Page_Base {

		/**
		 * Class constructor
		 */
		public function __construct( Jet_Engine_CPT $manager ) {
			$this->manager = $manager;
			add_action( 'jet-engine/cpt/page/after-title', array( $this, 'add_new_btn' ) );
		}

		/**
		 * Add new  post type button
		 */
		public function add_new_btn( $page ) {

			if ( $page->get_slug() !== $this->get_slug() ) {
				return;
			}

			?>
			<a class="page-title-action" href="<?php echo $this->manager->get_page_link( 'add-tax' ); ?>"><?php
				_e( 'Add New', 'jet-engine' );
			?></a>
			<?php

		}

		/**
		 * Page slug
		 *
		 * @return string
		 */
		public function get_slug() {
			return 'list-tax';
		}

		/**
		 * Page name
		 *
		 * @return string
		 */
		public function get_name() {
			return esc_html__( 'Custom Taxonomies List', 'jet-engine' );
		}

		/**
		 * Renderer callback
		 *
		 * @return void
		 */
		public function render_page() {

			require jet_engine()->plugin_path( 'includes/list-tables/taxonomies-list-table.php' );
			$list_table = new Jet_Taxonomies_List_Table();

			$list_table->prepare_items();
			$list_table->display();

		}

	}

}