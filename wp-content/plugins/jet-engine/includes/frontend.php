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

if ( ! class_exists( 'Jet_Engine_Frontend' ) ) {

	/**
	 * Define Jet_Engine_Frontend class
	 */
	class Jet_Engine_Frontend {

		private $listing_id = null;

		public function __construct() {
			add_action( 'elementor/frontend/after_enqueue_scripts', array( $this, 'frontend_assets' ) );
			add_action( 'elementor/preview/enqueue_scripts', array( $this, 'preview_scripts' ) );
		}

		/**
		 * Preview scripts
		 *
		 * @return [type] [description]
		 */
		public function preview_scripts() {
			wp_enqueue_script( 'jquery-slick' );
			wp_enqueue_script( 'imagesloaded' );
			wp_enqueue_script( 'jet-engine-frontend' );
		}

		/**
		 * Register front-end assets
		 *
		 * @return [type] [description]
		 */
		public function frontend_assets() {

			wp_enqueue_script(
				'jet-engine-frontend',
				jet_engine()->plugin_url( 'assets/js/frontend.js' ),
				array( 'jquery', 'elementor-frontend' ),
				jet_engine()->get_version(),
				true
			);

			wp_localize_script( 'jet-engine-frontend', 'JetEngineSettings', array(
				'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
			) );

		}

		public function set_listing( $listing_id = null ) {
			$this->listing_id = $listing_id;
		}

		public function reset_listing() {
			$this->reset_data();
			$this->listing_id = null;
		}

		public function get_listing_item( $post ) {

			$this->setup_data( $post );

			$listing_id = apply_filters( 'jet-engine/listings/frontend/rendered-listing-id', $this->listing_id );

			return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $listing_id );
		}

		/**
		 * Setup data
		 *
		 * @param  [type] $post [description]
		 * @return [type]       [description]
		 */
		public function setup_data( $post_obj = null ) {

			if ( 'posts' === jet_engine()->listings->data->get_listing_source() ) {
				global $post;
				$post = $post_obj;
				setup_postdata( $post );
			}

			jet_engine()->listings->data->set_current_object( $post_obj );
		}

		/**
		 * Reset data
		 * @return [type] [description]
		 */
		public function reset_data() {
			if ( 'posts' === jet_engine()->listings->data->get_listing_source() ) {
				wp_reset_postdata();
			}

			jet_engine()->listings->data->reset_current_object();
		}

	}

}
