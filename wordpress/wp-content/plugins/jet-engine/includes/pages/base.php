<?php
/**
 * Base class for CPT page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT_Page_Base' ) ) {

	/**
	 * Define Jet_Engine_CPT_Page_Base class
	 */
	abstract class Jet_Engine_CPT_Page_Base {

		/**
		 * Manager instance
		 *
		 * @var Jet_Engine_CPT
		 */
		public $manager = null;

		/**
		 * Class constructor
		 */
		public function __construct( Jet_Engine_CPT $manager ) {
			$this->manager = $manager;
		}

		/**
		 * Check if this page is currently requested
		 *
		 * @return boolean [description]
		 */
		public function is_page_now() {

			if ( ! $this->manager->is_cpt_page() ) {
				return false;
			}

			$key = $this->manager->action_key;

			if ( ! isset( $_GET[ $key ] ) || $this->slug() !== $_GET[ $key ] ) {
				return false;
			}

			return true;

		}

		/**
		 * Returns current page URL
		 *
		 * @return string
		 */
		public function get_current_page_link() {

			return add_query_arg(
				array(
					'page'                     => $this->manager->page_slug(),
					$this->manager->action_key => $this->get_slug(),
				),
				esc_url( admin_url( 'admin.php' ) )
			);

		}

		/**
		 * Register interface builder controls
		 *
		 * @return void
		 */
		public function register_controls() {}

		/**
		 * Page slug
		 *
		 * @return string
		 */
		abstract public function get_slug();

		/**
		 * Page name
		 *
		 * @return string
		 */
		abstract public function get_name();

		/**
		 * Renderer callback
		 *
		 * @return void
		 */
		abstract public function render_page();

	}

}
