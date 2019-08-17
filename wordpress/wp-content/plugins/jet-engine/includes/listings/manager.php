<?php
/**
 * Listings manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Listings' ) ) {

	/**
	 * Define Jet_Engine_Listings class
	 */
	class Jet_Engine_Listings {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Library items id for tabs and options list
		 *
		 * @var string
		 */
		private $_id= 'jet-listing-items';

		/**
		 * Macros manager instance
		 *
		 * @var null
		 */
		public $macros = null;

		/**
		 * Filters manager instance
		 *
		 * @var null
		 */
		public $filters = null;

		/**
		 * Data manager instance
		 *
		 * @var null
		 */
		public $data = null;

		/**
		 * Constructor for the class
		 */
		function __construct() {

			add_filter( 'jet-engine/templates/create/data', array( $this, 'inject_listing_settings' ) );

			add_action( 'elementor/documents/register', array( $this, 'register_document_type' ) );

			add_action( 'elementor/init', array( $this, 'register_category' ) );
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ), 10 );

			add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_listing_css' ) );
			add_action( 'jet-engine/locations/enqueue-location-css', array( $this, 'loc_enqueue_listing_css' ) );

			add_filter( 'body_class', array( $this, 'add_body_classes' ) );

			add_action( 'elementor/dynamic_tags/before_render', array( $this, 'switch_to_preview_query' ) );
			add_action( 'elementor/dynamic_tags/after_render', array( $this, 'restore_current_query' ) );

			add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'frontend_assets' ) );
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );

			require jet_engine()->plugin_path( 'includes/listings/macros.php' );
			require jet_engine()->plugin_path( 'includes/listings/filters.php' );
			require jet_engine()->plugin_path( 'includes/listings/data.php' );

			$this->macros  = new Jet_Engine_Listings_Macros();
			$this->filters = new Jet_Engine_Listings_Filters();
			$this->data    = new Jet_Engine_Listings_Data();

		}

		/**
		 * Enqueue editor styles
		 *
		 * @return void
		 */
		public function editor_styles() {

			wp_enqueue_style(
				'jet-engine-font',
				jet_engine()->plugin_url( 'assets/lib/jetengine-font/style.css' ),
				array(),
				jet_engine()->get_version()
			);

		}

		/**
		 * Enqueue front-end styles
		 *
		 * @return [type] [description]
		 */
		public function frontend_assets() {
			wp_enqueue_style(
				'jet-engine-frontend',
				jet_engine()->plugin_url( 'assets/css/frontend.css' ),
				array(),
				jet_engine()->get_version()
			);
		}

		/**
		 * Switch to specific preview query
		 *
		 * @return void
		 */
		public function switch_to_preview_query() {

			$current_post_id = get_the_ID();

			if ( jet_engine()->post_type->slug() !== get_post_type( $current_post_id ) ) {
				return;
			}

			$document = Elementor\Plugin::instance()->documents->get_doc_or_auto_save( $current_post_id );

			if ( ! is_object( $document ) || ! method_exists( $document, 'get_preview_as_query_args' ) ) {
				return;
			}

			$new_query_vars = $document->get_preview_as_query_args();

			if ( empty( $new_query_vars ) ) {
				return;
			}

			Elementor\Plugin::instance()->db->switch_to_query( $new_query_vars );

		}

		/**
		 * Restore default query
		 *
		 * @return void
		 */
		public function restore_current_query() {
			Elementor\Plugin::instance()->db->restore_current_query();
		}

		/**
		 * Add body classes
		 */
		public function add_body_classes( $classes ) {

			$template_type = get_post_meta( get_the_ID(), '_elementor_template_type', true );

			if ( 'jet-listing-items' === $template_type ) {
				$classes[] = 'jet-listing-item';
			}

			return $classes;
		}

		/**
		 * Register cherry category for elementor if not exists
		 *
		 * @return void
		 */
		public function register_category() {

			$elements_manager = Elementor\Plugin::instance()->elements_manager;

			$elements_manager->add_category(
				'jet-listing-elements',
				array(
					'title' => esc_html__( 'Listing Elements', 'jet-engine' ),
					'icon'  => 'font',
				),
				0
			);
		}

		public function get_listings() {
			return get_posts( array(
				'post_type'      => jet_engine()->post_type->slug(),
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			) );
		}

		/**
		 * Register listing widgets
		 *
		 * @return void
		 */
		public function register_widgets( $widgets_manager ) {

			$base      = jet_engine()->plugin_path( 'includes/listings/' );
			$post_type = get_post_type();

			foreach ( glob( $base . 'dynamic-widgets/*.php' ) as $file ) {
				$slug = basename( $file, '.php' );
				$this->register_widget( $file, $widgets_manager );
			}

			foreach ( glob( $base . 'static-widgets/*.php' ) as $file ) {
				$slug = basename( $file, '.php' );
				$this->register_widget( $file, $widgets_manager );
			}

		}


		/**
		 * Register new widget
		 *
		 * @return void
		 */
		public function register_widget( $file, $widgets_manager ) {

			$base  = basename( str_replace( '.php', '', $file ) );
			$class = ucwords( str_replace( '-', ' ', $base ) );
			$class = str_replace( ' ', '_', $class );
			$class = sprintf( 'Elementor\Jet_Listing_%s_Widget', $class );

			require_once $file;

			if ( class_exists( $class ) ) {
				$widgets_manager->register_widget_type( new $class );
			}

		}

		/**
		 * Register apropriate Document Types for listing items
		 *
		 * @return void
		 */
		public function register_document_type( $documents_manager ) {
			require jet_engine()->plugin_path( 'includes/document-types/listing-item.php' );
			$documents_manager->register_document_type( $this->get_id(), 'Jet_Listing_Item_Document' );
		}

		/**
		 * Inject listing settings from tamplate into _elementor_page_settings meta
		 * @param  [type] $template_data [description]
		 * @return [type]                [description]
		 */
		public function inject_listing_settings( $template_data ) {

			if ( ! isset( $_REQUEST['listing_source'] ) ) {
				return $template_data;
			}

			$source    = ! empty( $_REQUEST['listing_source'] ) ? esc_attr( $_REQUEST['listing_source'] ) : 'posts';
			$post_type = ! empty( $_REQUEST['listing_post_type'] ) ? esc_attr( $_REQUEST['listing_post_type'] ) : '';
			$tax       = ! empty( $_REQUEST['listing_tax'] ) ? esc_attr( $_REQUEST['listing_tax'] ) : '';

			$template_data['meta_input']['_elementor_page_settings']['listing_source']    = $source;
			$template_data['meta_input']['_elementor_page_settings']['listing_post_type'] = $post_type;
			$template_data['meta_input']['_elementor_page_settings']['listing_tax']       = $tax;

			return $template_data;

		}

		/**
		 * Get post types list for options.
		 *
		 * @return array
		 */
		public function get_post_types_for_options() {

			$args = array(
				'public' => true,
			);

			$post_types = get_post_types( $args, 'objects', 'and' );
			$post_types = wp_list_pluck( $post_types, 'label', 'name' );

			if ( isset( $post_types[ jet_engine()->post_type->slug() ] ) ) {
				unset( $post_types[ jet_engine()->post_type->slug() ] );
			}

			return $post_types;
		}

		/**
		 * Get post taxonomies for options.
		 *
		 * @return array
		 */
		public function get_taxonomies_for_options() {

			$args = array(
				'public'   => true,
			);

			$taxonomies = get_taxonomies( $args, 'objects', 'and' );

			return wp_list_pluck( $taxonomies, 'label', 'name' );
		}

		/**
		 * Return Listings items slug/ID
		 *
		 * @return [type] [description]
		 */
		public function get_id() {
			return $this->_id;
		}

		/**
		 * Store lis
		 * @param [type] $post_id    [description]
		 * @param [type] $listing_id [description]
		 */
		public function maybe_enqueue_listing_css( $post_id = null ) {

			if ( ! $post_id ) {
				$post_id = get_the_ID();
			}

			if ( ! $post_id ) {
				return;
			}

			$elementor_data = get_post_meta( $post_id, '_elementor_data', true );

			if ( ! $elementor_data ) {
				return;
			}

			preg_match_all( '/[\'\"]lisitng_id[\'\"]\:[\'\"](\d+)[\'\"]/', $elementor_data, $matches );

			//var_dump( $matches );

			if ( empty( $matches[1] ) ) {
				return;
			}

			foreach ( $matches[1] as $listing_id ) {

				if ( class_exists( 'Elementor\Core\Files\CSS\Post' ) ) {
					$css_file = new Elementor\Core\Files\CSS\Post( $listing_id );
				} else {
					$css_file = new Elementor\Post_CSS_File( $listing_id );
				}

				$css_file->enqueue();
			}

		}

		/**
		 * [loc_enqueue_listing_css description]
		 * @return [type] [description]
		 */
		public function loc_enqueue_listing_css( $template_id ) {
			$this->maybe_enqueue_listing_css( $template_id );
		}

		/**
		 * Returns allowed fields callbacks
		 *
		 * @return [type] [description]
		 */
		public function get_allowed_callbacks() {

			return apply_filters( 'jet-engine/listings/allowed-callbacks', array(
				'date'                              => __( 'Format date', 'jet-engine' ),
				'date_i18n'                         => __( 'Format date (localized)', 'jet-engine' ),
				'number_format'                     => __( 'Format number', 'jet-engine' ),
				'get_permalink'                     => __( 'Get post/page link (only URL)', 'jet-engine' ),
				'jet_get_pretty_post_link'          => __( 'Get post/page link (linked post title)', 'jet-engine' ),
				'get_term_link'                     => __( 'Get term link', 'jet-engine' ),
				'wp_oembed_get'                     => __( 'Embed URL', 'jet-engine' ),
				'make_clickable'                    => __( 'Make clickable', 'jet-engine' ),
				'jet_engine_icon_html'              => __( 'Embed icon from Iconpicker', 'jet-engine' ),
				'jet_engine_render_multiselect'     => __( 'Multiple select field values', 'jet-engine' ),
				'jet_engine_render_checkbox_values' => __( 'Checkbox field values', 'jet-engine' ),
				'jet_engine_render_post_titles'     => __( 'Get post titles from IDs', 'jet-engine' ),
				'jet_related_posts_list'            => __( 'Related posts list', 'jet-engine' ),
				'wp_get_attachment_image'           => __( 'Get image by ID', 'jet-engine' ),
			) );

		}

	}

}
