<?php
/**
 * Calendar widget module
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Module_Calendar' ) ) {

	/**
	 * Define Jet_Engine_Module_Calendar class
	 */
	class Jet_Engine_Module_Calendar extends Jet_Engine_Module_Base {

		/**
		 * Module ID
		 *
		 * @return string
		 */
		public function module_id() {
			return 'calendar';
		}

		/**
		 * Module name
		 *
		 * @return string
		 */
		public function module_name() {
			return __( 'Calendar', 'jet-engine' );
		}

		/**
		 * Module init
		 *
		 * @return void
		 */
		public function module_init() {
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_calendar_widget' ), 20 );
			add_action( 'wp_ajax_jet_engine_calendar_get_month', array( $this, 'calendar_get_month' ) );
			add_action( 'wp_ajax_nopriv_jet_engine_calendar_get_month', array( $this, 'calendar_get_month' ) );
		}

		/**
		 * Ajax handler for months navigation
		 *
		 * @return [type] [description]
		 */
		public function calendar_get_month() {

			ob_start();

			add_filter( 'jet-engine/listing/grid/custom-settings', array( $this, 'add_settings' ) );

			if ( ! class_exists( 'Elementor\Jet_Listing_Grid_Widget' ) ) {
				require_once jet_engine()->plugin_path( 'includes/listings/static-widgets/grid.php' );
			}

			if ( ! class_exists( 'Elementor\Jet_Listing_Calendar_Widget' ) ) {
				require_once jet_engine()->modules->modules_path( 'calendar/calendar.php' );
			}

			$current_post = isset( $_REQUEST['post'] ) ? $_REQUEST['post'] : false;

			if ( $current_post ) {
				global $post;
				$post = get_post( $current_post );
			}

			Elementor\Plugin::instance()->frontend->start_excerpt_flag( null );

			$widget = new Elementor\Jet_Listing_Calendar_Widget();
			$widget->render_posts();

			wp_send_json_success( array(
				'content' => ob_get_clean(),
			) );

		}

		/**
		 * Add custom settings for AJAX request
		 */
		public function add_settings( $settings ) {
			return isset( $_REQUEST['settings'] ) ? $_REQUEST['settings'] : array();
		}

		/**
		 * Register calendar widget
		 *
		 * @return void
		 */
		public function register_calendar_widget( $widgets_manager ) {

			jet_engine()->listings->register_widget(
				jet_engine()->modules->modules_path( 'calendar/calendar.php' ),
				$widgets_manager
			);

		}

	}

}
