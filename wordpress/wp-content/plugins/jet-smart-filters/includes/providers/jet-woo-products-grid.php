<?php
/**
 * Class: Jet_Smart_Filters_Provider_Jet_Woo_Grid
 * Name: JetWooBuilder Products Grid
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Provider_Jet_Woo_Grid' ) ) {

	/**
	 * Define Jet_Smart_Filters_Provider_Jet_Woo_Grid class
	 */
	class Jet_Smart_Filters_Provider_Jet_Woo_Grid extends Jet_Smart_Filters_Provider_Base {

		private $_query_id = 'default';

		/**
		 * Watch for default query
		 */
		public function __construct() {

			if ( ! jet_smart_filters()->query->is_ajax_filter() ) {

				add_filter(
					'jet-woo-builder/tools/carousel/pre-options',
					array( $this, 'store_carousel_options' ),
					10, 2
				);

				add_filter(
					'shortcode_atts_jet-woo-products',
					array( $this, 'store_default_atts' ),
					0, 2
				);

				// Add provider and query ID to query
				add_filter(
					'jet-woo-builder/shortcodes/jet-woo-products/query-args',
					array( $this, 'filters_trigger' ),
					10, 2
				);

				add_filter( 'posts_pre_query',
					array( $this, 'store_archive_query' ),
					0, 2
				);
			}

		}

		/**
		 * Store default query args
		 *
		 * @param  array  $args       Query arguments.
		 * @param  array  $attributes Shortcode attributes.
		 * @param  string $type       Shortcode type.
		 * @return array
		 */
		public function store_archive_query( $posts, $query ) {

			if ( ! $query->get( 'wc_query' ) ) {
				return $posts;
			}

			if( 'yes' !== $query->get( 'jet_use_current_query' ) ){
				return $posts;
			}

			$default_query = array(
				'post_type'         => $query->get( 'post_type' ),
				'wc_query'          => $query->get( 'wc_query' ),
				'tax_query'         => $query->get( 'tax_query' ),
				'orderby'           => $query->get( 'orderby' ),
				'paged'             => $query->get( 'paged' ),
				'posts_per_page'    => $query->get( 'posts_per_page' ),
				'jet_smart_filters' => $this->get_id(). '/' . $this->_query_id,
			);

			if ( $query->get( 'taxonomy' ) ) {
				$default_query['taxonomy'] = $query->get( 'taxonomy' );
				$default_query['term'] = $query->get( 'term' );
			}

			jet_smart_filters()->query->store_provider_default_query( $this->get_id(), $default_query, $this->_query_id );

			$query->set( 'jet_smart_filters', $this->get_id() . '/' . $this->_query_id );

			return $posts;

		}

		/**
		 * Save default carousel options
		 *
		 * @param  array  $options [description]
		 * @return [type]          [description]
		 */
		public function store_carousel_options( $options = array(), $all_settings = array() ) {

			if ( empty( $all_settings['_element_id'] ) ) {
				$query_id = 'default';
			} else {
				$query_id = $all_settings['_element_id'];
			}

			jet_smart_filters()->providers->add_provider_settings(
				$this->get_id(),
				array(
					'carousel_options' => $options,
				),
				$query_id
			);

			return $options;
		}

		/**
		 * Store default query args
		 *
		 * @param  array  $args Query arguments.
		 * @return array
		 */
		public function store_default_atts( $atts = array() ) {

			if ( empty( $atts['_element_id'] ) ) {
				$query_id = 'default';
			} else {
				$query_id = $atts['_element_id'];
			}

			$this->_query_id = $query_id;

			jet_smart_filters()->providers->store_provider_settings( $this->get_id(), $atts, $query_id );

			return $atts;
		}

		/**
		 * Get provider name
		 *
		 * @return string
		 */
		public function get_name() {
			return __( 'JetWooBuilder Products Grid', 'jet-smart-filters' );
		}

		/**
		 * Get provider ID
		 *
		 * @return string
		 */
		public function get_id() {
			return 'jet-woo-products-grid';
		}

		public function filters_trigger( $args = array(), $shortcode ) {

			$query_id = $shortcode->get_attr( '_element_id' );

			if ( ! $query_id ) {
				$query_id = 'default';
			}

			$args['no_found_rows']     = false;
			$args['jet_smart_filters'] = jet_smart_filters()->query->encode_provider_data(
				$this->get_id(),
				$query_id
			);

			return $args;
		}

		/**
		 * Get filtered provider content
		 *
		 * @return string
		 */
		public function ajax_get_content() {

			if ( ! function_exists( 'wc' ) || ! function_exists( 'jet_woo_builder' ) ) {
				return;
			}

			add_filter(
				'jet-woo-builder/shortcodes/jet-woo-products/query-args',
				array( $this, 'filters_trigger' ),
				10, 2
			);

			add_filter( 'pre_get_posts', array( $this, 'add_query_args' ), 10 );

			$attributes = jet_smart_filters()->query->get_query_settings();

			if( isset( $attributes['use_current_query'] ) && 'yes' === $attributes['use_current_query']  ){
				global $wp_query;
				$wp_query = new WP_Query( jet_smart_filters()->query->get_query_args() );
			}

			if ( ! empty( $attributes['carousel_options'] ) ) {
				$settings = $attributes['carousel_options'];
				$settings['carousel_enabled'] = 'yes';
			} else {
				$settings['carousel_enabled'] = '';
			}

			$shortcode = jet_woo_builder_shortocdes()->get_shortcode( 'jet-woo-products' );

			echo jet_woo_builder_tools()->get_carousel_wrapper_atts(
				$shortcode->do_shortcode( $attributes ),
				$settings
			);

		}

		/**
		 * Get provider wrapper selector
		 *
		 * @return string
		 */
		public function get_wrapper_selector() {
			return '.elementor-jet-woo-products.jet-woo-builder';
		}

		/**
		 * If added unique ID this paramter will determine - search selector inside this ID, or is the same element
		 *
		 * @return bool
		 */
		public function in_depth() {
			return true;
		}

		/**
		 * Pass args from reuest to provider
		 */
		public function apply_filters_in_request() {

			$args = jet_smart_filters()->query->get_query_args();

			if ( ! $args ) {
				return;
			}

			add_filter(
				'jet-woo-builder/shortcodes/jet-woo-products/query-args',
				array( $this, 'filters_trigger' ),
				10, 2
			);

			add_filter( 'pre_get_posts', array( $this, 'add_query_args' ), 10 );

		}

		/**
		 * Add custom query arguments
		 *
		 * @param array $args [description]
		 */
		public function add_query_args( $query ) {

			if ( ! $query->get( 'jet_smart_filters' ) ) {
				return;
			}

			if ( $query->get( 'jet_smart_filters' ) !== jet_smart_filters()->render->request_provider( 'raw' ) ) {
				return;
			}

			foreach ( jet_smart_filters()->query->get_query_args() as $query_var => $value ) {
				$query->set( $query_var, $value );
			}

		}
	}

}
