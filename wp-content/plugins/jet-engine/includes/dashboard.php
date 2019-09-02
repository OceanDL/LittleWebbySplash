<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Dashboard' ) ) {

	/**
	 * Define Jet_Engine_Dashboard class
	 */
	class Jet_Engine_Dashboard {

		public $builder       = null;
		public $skins_manager = null;

		/**
		 * Constructor for the class
		 */
		function __construct() {
			add_action( 'admin_menu', array( $this, 'register_main_menu_page' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'init_builder' ), 0 );
			add_action( 'admin_init', array( $this, 'init_components' ), 99 );
		}

		/**
		 * Register menu page
		 *
		 * @return void
		 */
		public function register_main_menu_page() {

			add_menu_page(
				__( 'JetEngine', 'jet-engine' ),
				__( 'JetEngine', 'jet-engine' ),
				'manage_options',
				jet_engine()->admin_page,
				array( $this, 'render_page' ),
				'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAyOTUuMzI5IDI5NS4zMjkiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0ibTI5MS40MiAxNDIuMzhsLTMzLjE0LTI1LjE2Yy0yLjk5Ny0yLjI3NS03LjAxLTIuNjYxLTEwLjM4My0wLjk4LTMuMzYyIDEuNjY2LTUuNDkyIDUuMTAxLTUuNDkyIDguODYxdjE1LjM5NWgtOS44MDN2LTE1LjU1OWMwLTUuNDY1LTQuNDMtOS44OTYtOS44OTMtOS44OTZoLTE5LjY5NnYtMzYuNzQzYzAtNS40NjYtNC40MzEtOS44OTYtOS44OTQtOS44OTZoLTMwLjc3M3YtMTAuNTAyaDguMjg0YzUuNDY0IDAgOS44OTUtNC40MzEgOS44OTUtOS44OTVzLTQuNDMxLTkuODkzLTkuODk1LTkuODkzaC02My45MmMtNS40NjMgMC05Ljg5NCA0LjQyOS05Ljg5NCA5Ljg5M3M0LjQzMSA5Ljg5NSA5Ljg5NCA5Ljg5NWg4LjI5djEwLjUwMmgtMzguMjVjLTUuNDY0IDAtOS44OTUgNC40My05Ljg5NSA5Ljg5NnYxOS4zMTNoLTE5LjMyM2MtNS40NjUgMC05Ljg5NSA0LjQzLTkuODk1IDkuODk0djIyLjYzNGgtMTcuODQ2di0yOC4wNzNjMC01LjQ2NC00LjQzLTkuODk0LTkuODk0LTkuODk0LTUuNDY0LTFlLTMgLTkuODkzIDQuNDMtOS44OTMgOS44OTN2MTAzLjQ5YzAgNS40NjQgNC40MjkgOS44OTMgOS44OTQgOS44OTMgNS40NjQgMCA5Ljg5NC00LjQzIDkuODk0LTkuODkzdi0yOC4wNzRoMTcuODQ3djIzLjIwM2MwIDUuNDY1IDQuNDMgOS44OTQgOS44OTUgOS44OTRoMjQuODgxbDM0LjkwNyA0Mi45ODljMS44NzkgMi4zMTMgNC43MDEgMy42NTYgNy42OCAzLjY1NmgxMDcuNzFjNS40NjQgMCA5Ljg5My00LjQzMiA5Ljg5My05Ljg5NXYtMTMuMDczaDkuODAzdjEzLjA3M2MwIDMuODY1IDIuMjQ5IDcuMzcyIDUuNzU4IDguOTkgMS4zMjMgMC42MDcgMi43MzUgMC45MDQgNC4xMzUgMC45MDQgMi4zMTkgMCA0LjYwOS0wLjgxNiA2LjQ0MS0yLjM4M2wzMy4xNDEtMjguNDA0YzIuMTkzLTEuODgyIDMuNDUzLTQuNjI1IDMuNDUzLTcuNTE0di02OC42NjNjLTJlLTMgLTMuMDktMS40NTEtNi4wMDgtMy45MTUtNy44Nzh6IiBmaWxsPSJ3aGl0ZSIvPjwvc3ZnPg=='
			);

		}

		public function init_components() {

			if ( ! $this->is_dashboard() && ! wp_doing_ajax() ) {
				return;
			}

			require jet_engine()->plugin_path( 'includes/skins/manager.php' );
			$this->skins_manager = new Jet_Engine_Skins( $this->builder );

		}

		function is_dashboard() {
			return ( isset( $_GET['page'] ) && jet_engine()->admin_page === $_GET['page'] );
		}

		/**
		 * Initialize builder
		 *
		 * @return [type] [description]
		 */
		public function init_builder() {

			if ( ! $this->is_dashboard() ) {
				return;
			}

			$builder_data = jet_engine()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

			$this->builder = new CX_Interface_Builder(
				array(
					'path' => $builder_data['path'],
					'url'  => $builder_data['url'],
				)
			);

			$this->builder->register_section(
				array(
					'jet_engine_dashboard' => array(
						'type'   => 'section',
						'scroll' => false,
						'title'  => esc_html__( 'JetEngine Dashboard', 'jet-engine' ),
					),
				)
			);

			$this->builder->register_component(
				array(
					'jet_engine_dashboard_tabs' => array(
						'type'   => 'component-tab-vertical',
						'parent' => 'jet_engine_dashboard',
					),
				)
			);

			$this->skins_manager->register_controls( $this->builder );
			jet_engine()->modules->register_modules_controls( $this->builder );

		}

		/**
		 * Render main admin page
		 *
		 * @return void
		 */
		public function render_page() {
			$this->builder->render();
		}

	}

}
