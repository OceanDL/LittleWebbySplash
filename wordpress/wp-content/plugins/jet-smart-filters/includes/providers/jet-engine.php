<?php
/**
 * Class: Jet_Smart_Filters_Provider_Jet_Engine
 * Name: JetEngine
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Provider_Jet_Engine' ) ) {

	/**
	 * Define Jet_Smart_Filters_Provider_Jet_Engine class
	 */
	class Jet_Smart_Filters_Provider_Jet_Engine extends Jet_Smart_Filters_Provider_Base {

		/**
		 * Watch for default query
		 */
		public function __construct() {

			if ( ! jet_smart_filters()->query->is_ajax_filter() ) {
				add_filter('jet-engine/listing/grid/posts-query-args', array( $this, 'store_default_query' ), 0, 2 );
			}

		}

		/**
		 * Store default query args
		 *
		 * @param  [type] $args [description]
		 * @return [type]       [description]
		 */
		public function store_default_query( $args, $widget ) {

			if ( 'jet-listing-grid' !== $widget->get_name() ) {
				return $args;
			}

			$settings = $widget->get_settings();

			if ( empty( $settings['_element_id'] ) ) {
				$query_id = false;
			} else {
				$query_id = $settings['_element_id'];
			}

			if ( 'yes' === $settings['is_archive_template'] ){
				jet_smart_filters()->query->set_props(
					$this->get_id(),
					array(
						'found_posts'   => $args['found_posts'],
						'max_num_pages' => $args['max_num_pages'],
						'page'          => $args['paged'],
					),
					$query_id
				);
			}

			jet_smart_filters()->query->store_provider_default_query( $this->get_id(), $args, $query_id );

			jet_smart_filters()->providers->store_provider_settings( $this->get_id(), array(
				'lisitng_id'           => $settings['lisitng_id'],
				'columns'              => ! empty( $settings['columns'] ) ? $settings['columns'] : 3,
				'columns_tablet'       => ! empty( $settings['columns_tablet'] ) ? $settings['columns_tablet'] : false,
				'columns_mobile'       => ! empty( $settings['columns_mobile'] ) ? $settings['columns_mobile'] : false,
				'not_found_message'    => ! empty( $settings['not_found_message'] ) ? $settings['not_found_message'] : '',
				'equal_columns_height' => ! empty( $settings['equal_columns_height'] ) ? $settings['equal_columns_height'] : '',
				'carousel_enabled'     => ! empty( $settings['carousel_enabled'] ) ? $settings['carousel_enabled'] : '',
				'slides_to_scroll'     => ! empty( $settings['slides_to_scroll'] ) ? $settings['slides_to_scroll'] : '',
				'arrows'               => ! empty( $settings['arrows'] ) ? $settings['arrows'] : '',
				'arrow_icon'           => ! empty( $settings['arrow_icon'] ) ? $settings['arrow_icon'] : '',
				'dots'                 => ! empty( $settings['dots'] ) ? $settings['dots'] : '',
				'autoplay'             => ! empty( $settings['autoplay'] ) ? $settings['autoplay'] : '',
				'autoplay_speed'       => ! empty( $settings['autoplay_speed'] ) ? $settings['autoplay_speed'] : '',
				'infinite'             => ! empty( $settings['infinite'] ) ? $settings['infinite'] : '',
				'effect'               => ! empty( $settings['effect'] ) ? $settings['effect'] : '',
				'speed'                => ! empty( $settings['speed'] ) ? $settings['speed'] : '',
			), $query_id );

			$args['suppress_filters']  = false;
			$args['jet_smart_filters'] = jet_smart_filters()->query->encode_provider_data(
				$this->get_id(),
				$query_id
			);

			return $args;
		}

		/**
		 * Get provider name
		 *
		 * @return string
		 */
		public function get_name() {
			return __( 'JetEngine', 'jet-smart-filters' );
		}

		/**
		 * Get provider ID
		 *
		 * @return string
		 */
		public function get_id() {
			return 'jet-engine';
		}

		/**
		 * Get filtered provider content
		 *
		 * @return string
		 */
		public function ajax_get_content() {

			if ( ! function_exists( 'jet_engine' ) ) {
				return;
			}

			add_filter( 'jet-engine/listing/grid/posts-query-args', array( $this, 'add_query_args' ), 10, 2 );
			add_filter( 'jet-engine/listing/grid/custom-settings', array( $this, 'add_settings' ), 10, 2 );

			if ( ! class_exists( 'Elementor\Jet_Listing_Grid_Widget' ) ) {
				require_once jet_engine()->plugin_path( 'includes/listings/static-widgets/grid.php' );
			}

			Elementor\Plugin::instance()->frontend->start_excerpt_flag( null );

			$widget = new Elementor\Jet_Listing_Grid_Widget();
			$widget->render_posts();

		}

		/**
		 * Get provider wrapper selector
		 *
		 * @return string
		 */
		public function get_wrapper_selector() {
			return '.elementor-widget-jet-listing-grid > .elementor-widget-container';
		}

		/**
		 * Add custom settings for AJAX request
		 */
		public function add_settings( $settings, $widget ) {

			if ( 'jet-listing-grid' !== $widget->get_name() ) {
				return $settings;
			}

			return jet_smart_filters()->query->get_query_settings();
		}

		/**
		 * Pass args from reuest to provider
		 */
		public function apply_filters_in_request() {

			$args = jet_smart_filters()->query->get_query_args();

			if ( ! $args ) {
				return;
			}

			add_filter( 'jet-engine/listing/grid/posts-query-args', array( $this, 'add_query_args' ), 10, 2 );

		}

		/**
		 * Add custom query arguments
		 *
		 * @param array $args [description]
		 */
		public function add_query_args( $args = array(), $widget ) {

			if ( 'jet-listing-grid' !== $widget->get_name() ) {
				return $args;
			}

			if ( ! jet_smart_filters()->query->is_ajax_filter() ) {

				$settings = $widget->get_settings();

				if ( empty( $settings['_element_id'] ) ) {
					$query_id = 'default';
				} else {
					$query_id = $settings['_element_id'];
				}

				$request_query_id = jet_smart_filters()->query->get_current_provider( 'query_id' );

				if ( $query_id !== $request_query_id ) {
					return $args;
				}

			}

			return array_merge( $args, jet_smart_filters()->query->get_query_args() );

		}
	}

}
