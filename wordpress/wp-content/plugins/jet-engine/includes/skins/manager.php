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

if ( ! class_exists( 'Jet_Engine_Skins' ) ) {

	/**
	 * Define Jet_Engine_Skins class
	 */
	class Jet_Engine_Skins {

		public $import;
		public $export;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			require jet_engine()->plugin_path( 'includes/skins/import.php' );
			require jet_engine()->plugin_path( 'includes/skins/export.php' );

			$this->import  = new Jet_Engine_Skins_Import();
			$this->export  = new Jet_Engine_Skins_Export();

			add_action( 'admin_enqueue_scripts', array( $this, 'skins_assets' ) );

		}

		/**
		 * Enqueue skins assets
		 *
		 * @return [type] [description]
		 */
		public function skins_assets() {

			wp_enqueue_style(
				'jet-listings-form',
				jet_engine()->plugin_url( 'assets/css/admin/listings.css' ),
				array(),
				jet_engine()->get_version()
			);

			wp_enqueue_script(
				'jet-listings-form',
				jet_engine()->plugin_url( 'assets/js/admin/listings.js' ),
				array( 'jquery' ),
				jet_engine()->get_version(),
				true
			);

		}

		/**
		 * Register controls
		 *
		 * @return [type] [description]
		 */
		public function register_controls( $builder ) {

			$builder->register_settings(
				array(
					'jet_engine_skins' => array(
						'parent'   => 'jet_engine_dashboard_tabs',
						'title'  => esc_html__( 'Skins Manager', 'jet-engine' ),
					),
				)
			);

			$builder->register_html(
				array(
					'import_skin' => array(
						'type'   => 'html',
						'parent' => 'jet_engine_skins',
						'class'  => 'cx-component',
						'html'   => $this->import->get_controls(),
					),
					'export_skin' => array(
						'type'   => 'html',
						'parent' => 'jet_engine_skins',
						'class'  => 'cx-component',
						'html'   => $this->export->get_controls(),
					),
				)
			);

		}

		/**
		 * Render skins manager
		 *
		 * @return [type] [description]
		 */
		public function render( $builder ) {
			$builder->render();
		}

	}

}
