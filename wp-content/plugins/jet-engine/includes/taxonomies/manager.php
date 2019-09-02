<?php
/**
 * Custom post types manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT_Tax' ) ) {

	/**
	 * Define Jet_Engine_CPT_Tax class
	 */
	class Jet_Engine_CPT_Tax extends Jet_Engine_CPT {

		/**
		 * Base slug for CPT-related pages
		 * @var string
		 */
		public $page = 'jet-engine-cpt-tax';

		/**
		 * Action request key
		 *
		 * @var string
		 */
		public $action_key = 'cpt_tax_action';

		/**
		 * Set object type
		 * @var string
		 */
		public $object_type = 'taxonomy';

		/**
		 * Init data instance
		 *
		 * @return [type] [description]
		 */
		public function init_data() {
			require jet_engine()->plugin_path( 'includes/taxonomies/data.php' );
			$this->data = new Jet_Engine_CPT_Tax_Data( $this );
		}

		/**
		 * Register created post types
		 *
		 * @return void
		 */
		public function register_instances() {

			foreach ( $this->get_items() as $tax ) {

				if ( ! empty( $tax['meta_fields'] ) ) {

					$this->meta_boxes[ $tax['slug'] ] = $tax['meta_fields'];

					unset( $tax['meta_fields'] );
				}

				register_taxonomy( $tax['slug'], $tax['object_type'], $tax );

			}

		}

		/**
		 * Register metaboxes
		 *
		 * @return void
		 */
		public function register_meta_boxes() {

			if ( empty( $this->meta_boxes ) ) {
				return;
			}

			if ( ! class_exists( 'Jet_Engine_CPT_Tax_Meta' ) ) {
				require jet_engine()->plugin_path( 'includes/meta-boxes/tax.php' );
			}

			foreach ( $this->meta_boxes as $taxonomy => $meta_box ) {
				new Jet_Engine_CPT_Tax_Meta( $taxonomy, $meta_box );
			}

		}

		/**
		 * Register CPT menu page
		 */
		public function add_menu_page() {

			add_submenu_page(
				jet_engine()->admin_page,
				esc_html__( 'Taxonomies', 'jet-engine' ),
				esc_html__( 'Taxonomies', 'jet-engine' ),
				'manage_options',
				$this->page_slug(),
				array( $this, 'render_page' )
			);

		}

		/**
		 * Register CPT related pages
		 *
		 * @return void
		 */
		public function register_pages() {

			$this->data->ensure_db_table();

			$base_path = jet_engine()->plugin_path( 'includes/pages/' );

			require $base_path . 'base.php';

			$default = array(
				'Jet_Engine_CPT_Page_List_Taxes' => $base_path . 'list-tax.php',
				'Jet_Engine_CPT_Page_Add_Tax'    => $base_path . 'add-tax.php',
				'Jet_Engine_CPT_Page_Edit_Tax'   => $base_path . 'edit-tax.php',
			);

			foreach ( $default as $class => $file ) {
				require $file;
				$this->register_page( $class );
			}

			/**
			 * You could register custom pages on this hook
			 */
			do_action( 'jet-engine/pages/tax/register', $this );

		}

		/**
		 * Returns current page object
		 *
		 * @return object
		 */
		public function get_current_page() {

			$action = isset( $_GET[ $this->action_key ] ) ? $_GET[ $this->action_key ] : 'list-tax';
			$page   = isset( $this->_pages[ $action ] ) ? $this->_pages[ $action ] : false;

			return $page;

		}

	}

}
