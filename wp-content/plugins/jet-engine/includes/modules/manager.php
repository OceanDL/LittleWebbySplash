<?php
/**
 * Modules manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Modules' ) ) {

	/**
	 * Define Jet_Engine_Modules class
	 */
	class Jet_Engine_Modules {

		protected $option_name      = 'jet_engine_modules';
		private   $modules          = array();
		private   $active_modules   = array();

		/**
		 * Constructor for the class
		 */
		function __construct() {

			$this->preload_modules();
			$this->init_active_modules();

			add_action( 'admin_action_jet_engine_save_modules', array( $this, 'save_modules' ) );

		}

		/**
		 * Save active modules
		 *
		 * @return [type] [description]
		 */
		public function save_modules() {

			if ( isset( $_REQUEST['active_modules'] ) ) {
				update_option( $this->option_name, $_REQUEST['active_modules'] );
			}

			wp_redirect( add_query_arg(
				array( 'page' => jet_engine()->admin_page ),
				esc_url( admin_url( 'admin.php' ) )
			) );

			die();
		}

		/**
		 * Returns path to file inside modules dir
		 *
		 * @param  [type] $path [description]
		 * @return [type]       [description]
		 */
		public function modules_path( $path ) {
			return jet_engine()->plugin_path( 'includes/modules/' . $path );
		}

		/**
		 * Register modules controls
		 *
		 * @param  [type] $builder [description]
		 * @return [type]          [description]
		 */
		public function register_modules_controls( $builder ) {

			$builder->register_settings(
				array(
					'jet_engine_modules' => array(
						'parent'   => 'jet_engine_dashboard_tabs',
						'title'  => esc_html__( 'Modules Manager', 'jet-engine' ),
					),
				)
			);

			$builder->register_form(
				array(
					'jet_engine_modules_form' => array(
						'type'   => 'form',
						'parent' => 'jet_engine_modules',
						'action' => add_query_arg(
							array( 'action' => 'jet_engine_save_modules' ),
							esc_url( admin_url( 'admin.php' ) )
						),
					),
				)
			);

			$builder->register_control( array(
				'active_modules' => array(
					'type'        => 'checkbox',
					'id'          => 'active_modules',
					'name'        => 'active_modules',
					'parent'      => 'jet_engine_modules_form',
					'value'       => $this->get_active_modules(),
					'options'     => $this->get_all_modules(),
					'title'       => esc_html__( 'Available Modules', 'jet-engine' ),
					'description' => esc_html__( 'List of additional JetEngine modules', 'jet-engine' ),
					'class'       => 'jet-engine-modules'
				),
			) );

			$builder->register_html(
				array(
					'save_button' => array(
						'type'   => 'html',
						'parent' => 'jet_engine_modules_form',
						'class'  => 'cx-control dialog-save',
						'html'   => '<button type="submit" class="cx-button cx-button-primary-style">' . esc_html__( 'Save', 'jet-engine' ) . '</button>',
					),
				)
			);

		}

		/**
		 * Render modules settings
		 *
		 * @return [type] [description]
		 */
		public function render_modules_controls( $builder ) {
			$builder->render();
		}

		/**
		 * Preload modules
		 *
		 * @return void
		 */
		public function preload_modules() {

			$base_path   = jet_engine()->plugin_path( 'includes/modules/' );
			$all_modules = array(
				'Jet_Engine_Module_Gallery_Grid'   => $base_path . 'gallery-grid.php',
				'Jet_Engine_Module_Gallery_Slider' => $base_path . 'gallery-slider.php',
				'Jet_Engine_Module_QR_Code'        => $base_path . 'qr-code.php',
				'Jet_Engine_Module_Calendar'       => $base_path . 'calendar.php',
				'Jet_Engine_Module_Booking_Forms'  => $base_path . 'booking-form.php',
			);

			require jet_engine()->plugin_path( 'includes/modules/base.php' );

			foreach ( $all_modules as $module => $file ) {
				require $file;
				$instance = new $module;
				$this->modules[ $instance->module_id() ] = $instance;
			}

		}

		/**
		 * Initialize active modulles
		 *
		 * @return void
		 */
		public function init_active_modules() {

			$modules = $this->get_active_modules();

			if ( empty( $modules ) ) {
				return;
			}

			foreach ( $modules as $module => $is_active ) {
				if ( 'true' === $is_active ) {
					$module_instance = isset( $this->modules[ $module ] ) ? $this->modules[ $module ] : false;
					if ( $module_instance ) {
						call_user_func( array( $module_instance, 'module_init' ) );
						$this->active_modules[] = $module;
					}
				}
			}

		}

		public function get_all_modules() {
			$result = array();
			foreach ( $this->modules as $module ) {
				$result[ $module->module_id() ] = $module->module_name();
			}
			return $result;
		}

		public function get_active_modules() {
			return get_option( $this->option_name, array() );
		}

		public function is_module_active( $module_id = null ) {
			return in_array( $module_id, $this->active_modules );
		}

		public function get_module( $module_id = null ) {
			return isset( $this->modules[ $module_id ] ) ? $this->modules[ $module_id ] : false;
		}

	}

}
