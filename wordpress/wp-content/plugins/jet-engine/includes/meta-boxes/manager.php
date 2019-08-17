<?php
/**
 * Meta boxes manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Meta_Boxes' ) ) {

	/**
	 * Define Jet_Engine_Meta_Boxes class
	 */
	class Jet_Engine_Meta_Boxes extends Jet_Engine_CPT {

		/**
		 * Base slug for CPT-related pages
		 * @var string
		 */
		public $page = 'jet-engine-meta';

		/**
		 * Action request key
		 *
		 * @var string
		 */
		public $action_key = 'cpt_meta_action';

		/**
		 * Set object type
		 * @var string
		 */
		public $object_type = '';

		/**
		 * Constructor for the class
		 */
		function __construct() {

			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 20 );
			add_action( 'admin_init', array( $this, 'register_meta_boxes' ) );

			// Force search posts control to work
			add_action( 'wp_ajax_cx_search_posts', array( $this, 'process_posts_search' ) );
			add_action( 'wp_ajax_jet_engine_meta_box_posts', array( $this, 'process_posts_search' ) );

			$this->init_data();

			if ( ! $this->is_cpt_page() ) {
				return;
			}

			add_action( 'admin_init', array( $this, 'register_pages' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'init_builder' ), 0 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ), 10 );
			add_action( 'admin_init', array( $this, 'handle_actions' ) );

		}

		/**
		 * Process posts search
		 *
		 * @return void
		 */
		public function process_posts_search() {

			add_filter( 'posts_where', array( $this, 'force_search_by_title' ), 10, 2 );

			$type    = $_REQUEST['post_type'];
			$query   = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';
			$type    = explode( ',', $type );
			$exclude = ! empty( $_GET['exclude'] ) ? explode( ',', $_GET['exclude'] ) : false;

			$args = array(
				'post_type'           => $type,
				'ignore_sticky_posts' => true,
				'posts_per_page'      => -1,
				'suppress_filters'    => false,
				's_title'             => $query,
			);

			if ( $exclude ) {
				$args['post__not_in'] = $exclude;
			}

			$posts = get_posts( $args );

			remove_filter( 'posts_where', array( $this, 'force_search_by_title' ), 10, 2 );

			$result = array();

			if ( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$result[] = array(
						'id'   => $post->ID,
						'text' => $post->post_title,
					);
				}
			}

			wp_send_json( array(
				'results' => $result,
			) );

		}

		/**
		 * Force query to look in post title while searching
		 *
		 * @return [type] [description]
		 */
		public function force_search_by_title( $where, $query ) {

			$args = $query->query;

			if ( ! isset( $args['s_title'] ) ) {
				return $where;
			} else {
				global $wpdb;

				$searh = esc_sql( $wpdb->esc_like( $args['s_title'] ) );
				$where .= " AND {$wpdb->posts}.post_title LIKE '%$searh%'";

			}

			return $where;
		}

		/**
		 * Init data instance
		 *
		 * @return [type] [description]
		 */
		public function init_data() {
			require jet_engine()->plugin_path( 'includes/meta-boxes/data.php' );
			$this->data = new Jet_Engine_Meta_Boxes_Data( $this );
		}

		/**
		 * Register metaboxes
		 *
		 * @return void
		 */
		public function register_meta_boxes() {

			$meta_boxes = $this->data->get_raw();

			if ( empty( $meta_boxes ) ) {
				return;
			}

			foreach ( $meta_boxes as $meta_box ) {

				$args        = $meta_box['args'];
				$meta_fields = $meta_box['meta_fields'];
				$object_type = isset( $args['object_type'] ) ? esc_attr( $args['object_type'] ) : 'post';

				switch ( $object_type ) {

					case 'post':

						if ( ! class_exists( 'Jet_Engine_CPT_Meta' ) ) {
							require jet_engine()->plugin_path( 'includes/meta-boxes/post.php' );
						}

						$post_types = ! empty( $args['allowed_post_type'] ) ? $args['allowed_post_type'] : array();
						$title      = isset( $args['name'] ) ? $args['name'] : '';

						foreach ( $post_types as $post_type ) {

							if ( ! empty( $args['allowed_posts'] ) ) {

								$post_id = $this->get_post_id();

								if ( ! $post_id || ! in_array( $post_id, $args['allowed_posts'] ) ) {
									continue;
								}
							}

							new Jet_Engine_CPT_Meta( $post_type, $meta_fields, $title );
						}

						break;

					case 'tax':

						if ( ! class_exists( 'Jet_Engine_CPT_Tax_Meta' ) ) {
							require jet_engine()->plugin_path( 'includes/meta-boxes/tax.php' );
						}

						$taxonomies = ! empty( $args['allowed_tax'] ) ? $args['allowed_tax'] : array();

						foreach ( $taxonomies as $taxonomy ) {
							new Jet_Engine_CPT_Tax_Meta( $taxonomy, $meta_fields );
						}

						break;

				}

			}

		}

		/**
		 * Try to get current post ID from request
		 *
		 * @return [type] [description]
		 */
		public function get_post_id() {

			$post_id = isset( $_GET['post'] ) ? $_GET['post'] : false;

			if ( ! $post_id && isset( $_REQUEST['post_ID'] ) ) {
				$post_id = $_REQUEST['post_ID'];
			}

			return $post_id;

		}

		/**
		 * Return list of meta fields for post type
		 *
		 * @param  string $object [description]
		 * @return [type]            [description]
		 */
		public function get_meta_fields_for_object( $object = 'post' ) {

			$result     = array();
			$meta_boxes = $this->data->get_raw();

			if ( empty( $meta_boxes ) ) {
				return $result;
			}

			foreach ( $meta_boxes as $meta_box ) {

				$args        = $meta_box['args'];
				$meta_fields = $meta_box['meta_fields'];
				$post_types  = ! empty( $args['allowed_post_type'] ) ? $args['allowed_post_type'] : array();
				$taxonomies  = ! empty( $args['allowed_tax'] ) ? $args['allowed_tax'] : array();

				if ( empty( $meta_fields ) ) {
					continue;
				}

				if ( ! in_array( $object, $post_types ) && ! in_array( $object, $taxonomies ) ) {
					continue;
				}

				$result = array_merge( $result, array_values( $meta_fields ) );

			}

			return $result;

		}

		/**
		 * Register CPT menu page
		 */
		public function add_menu_page() {

			add_submenu_page(
				jet_engine()->admin_page,
				esc_html__( 'Meta Boxes', 'jet-engine' ),
				esc_html__( 'Meta Boxes', 'jet-engine' ),
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

			$base_path = jet_engine()->plugin_path( 'includes/pages/' );

			require $base_path . 'base.php';

			$default = array(
				'Jet_Engine_CPT_Page_List_Meta' => $base_path . 'list-meta.php',
				'Jet_Engine_CPT_Page_Add_Meta'    => $base_path . 'add-meta.php',
				'Jet_Engine_CPT_Page_Edit_Meta'   => $base_path . 'edit-meta.php',
			);

			foreach ( $default as $class => $file ) {
				require $file;
				$this->register_page( $class );
			}

			/**
			 * You could register custom pages on this hook
			 */
			do_action( 'jet-engine/pages/meta/register', $this );

		}

		/**
		 * Returns current page object
		 *
		 * @return object
		 */
		public function get_current_page() {

			$action = isset( $_GET[ $this->action_key ] ) ? $_GET[ $this->action_key ] : 'list-meta';
			$page   = isset( $this->_pages[ $action ] ) ? $this->_pages[ $action ] : false;

			return $page;

		}

	}

}
