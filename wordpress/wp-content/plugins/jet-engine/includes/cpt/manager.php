<?php
/**
 * Custom post types manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT' ) ) {

	/**
	 * Define Jet_Engine_CPT class
	 */
	class Jet_Engine_CPT {

		/**
		 * Base slug for CPT-related pages
		 * @var string
		 */
		public $page = 'jet-engine-cpt';

		/**
		 * CPT pages
		 *
		 * @var array
		 */
		public $_pages = array();

		/**
		 * Interface builder instance
		 *
		 * @var CX_Interface_Builder
		 */
		public $builder = null;


		/**
		 * Action request key
		 *
		 * @var string
		 */
		public $action_key = 'cpt_action';

		/**
		 * Dataq manger instance
		 *
		 * @var Jet_Engine_CPT_Data
		 */
		public $data = null;

		/**
		 * Notices list
		 *
		 * @var array
		 */
		public $notices = array();

		/**
		 * Metaboxes to register
		 *
		 * @var array
		 */
		public $meta_boxes = array();

		/**
		 * Set object type
		 * @var string
		 */
		public $object_type = 'post-type';

		/**
		 * registered admin columns
		 * @var array
		 */
		public $admin_columns = array();

		/**
		 * registered admin columns
		 * @var array
		 */
		public $render_columns = array();

		/**
		 * Items list
		 *
		 * @var null
		 */
		public $items = null;



		/**
		 * Constructor for the class
		 */
		function __construct() {

			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 20 );
			add_action( 'init', array( $this, 'register_instances' ) );
			add_action( 'admin_init', array( $this, 'register_meta_boxes' ) );

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
		 * Init data instance
		 *
		 * @return [type] [description]
		 */
		public function init_data() {
			require jet_engine()->plugin_path( 'includes/cpt/data.php' );
			$this->data = new Jet_Engine_CPT_Data( $this );
		}

		/**
		 * Register created post types
		 *
		 * @return void
		 */
		public function register_instances() {

			foreach ( $this->get_items() as $post_type ) {

				if ( ! empty( $post_type['meta_fields'] ) ) {
					$this->meta_boxes[ $post_type['slug'] ] = $post_type['meta_fields'];
					unset( $post_type['meta_fields'] );
				}

				if ( ! empty( $post_type['menu_position'] ) ) {
					$post_type['menu_position'] = absint( $post_type['menu_position'] );
				}

				register_post_type( $post_type['slug'], $post_type );

				if ( ! empty( $post_type['admin_columns'] ) ) {
					$this->register_admin_columns( $post_type['slug'], $post_type['admin_columns'] );
				}

			}

		}

		/**
		 * Register admin columns for post type
		 *
		 * @return [type] [description]
		 */
		public function register_admin_columns( $slug, $columns ) {

			$this->admin_columns[ $slug ] = $columns;

			add_filter( 'manage_' . $slug . '_posts_columns', array( $this, 'edit_columns' ) );
			add_action( 'manage_' . $slug . '_posts_custom_column', array( $this, 'manage_columns' ), 10, 2 );

		}

		/**
		 * Edit columns
		 * @return [type] [description]
		 */
		public function edit_columns( $columns ) {

			if ( wp_doing_ajax() ) {

				$post_id = isset( $_REQUEST['post_ID'] ) ? $_REQUEST['post_ID'] : false;

				if ( ! $post_id ) {
					return $columns;
				}

				$slug = get_post_type( $post_id );

			} else {

				$screen = get_current_screen();

				if ( ! $screen ) {
					return $columns;
				}

				$slug = $screen->post_type;
			}

			$new_columns = isset( $this->admin_columns[ $slug ] ) ? $this->admin_columns[ $slug ] : array();

			foreach ( $new_columns as $index => $column_data ) {

				if ( empty( $column_data['title'] ) ) {
					continue;
				}

				$column_key = sanitize_title( $column_data['title'] );

				if ( isset( $columns[ $column_key ] ) ) {
					$column_key .= '-' . $index;
				}

				$this->render_columns[ $column_key ] = $column_data;

				if ( ! empty( $column_data['position'] ) && 0 !== (int) $column_data['position'] ) {

					$length = count( $columns );

					if ( (int) $column_data['position'] > $length ) {
						$columns[ $column_key ] = $column_data['title'];
					}

					$columns_before = array_slice( $columns, 0, (int) $column_data['position'] );
					$columns_after  = array_slice( $columns, (int) $column_data['position'], $length - (int) $column_data['position'] );

					$columns = array_merge(
						$columns_before,
						array(
							$column_key => $column_data['title'],
						),
						$columns_after
					);
				} else {
					$columns[ $column_key ] = $column_data['title'];
				}
			}

			return $columns;

		}

		/**
		 * Render columns content
		 *
		 * @param  string $column  current post list categories.
		 * @param  int    $post_id current post ID.
		 * @return void
		 */
		public function manage_columns( $column, $post_id ) {

			if ( empty( $this->render_columns[ $column ] ) ) {
				return;
			}

			$column_data = $this->render_columns[ $column ];
			$result      = '';

			switch ( $column_data['type'] ) {

				case 'meta_value':

					if ( $column_data['meta_field'] ) {
						$result = get_post_meta( $post_id, $column_data['meta_field'], true );
					}

					break;

				case 'post_terms':

					if ( $column_data['taxonomy'] ) {

						$terms     = wp_get_post_terms( $post_id, $column_data['taxonomy'] );
						$terms_str = array();

						if ( $terms && ! is_wp_error( $terms ) ) {
							foreach ( $terms as $term ) {
								$terms_str[] = $term->name;
							}
						}

						$result = implode( ', ', $terms_str );
					}

					break;

				case 'custom_callback':

					if ( $column_data['callback'] && is_callable( $column_data['callback'] ) ) {
						$result = call_user_func( $column_data['callback'], $column, $post_id );
					}

					break;

			}

			if ( $result ) {
				echo $column_data['prefix'] . $result . $column_data['suffix'];
			}

		}

		public function get_items() {

			if ( ! $this->items ) {
				$this->items = $this->data->get_item_for_register();
			}

			return $this->items;
		}

		/**
		 * Returns metafields for post type
		 *
		 * @param  [type] $post_type [description]
		 * @return [type]            [description]
		 */
		public function get_meta_fields_for_object( $object ) {

			$meta_fields = array();

			if ( ! empty( $this->meta_boxes[ $object ] ) ) {
				$meta_fields = $this->meta_boxes[ $object ];
			}

			return apply_filters(
				'jet-engine/' . $this->object_type . '/' . $object . '/meta-fields',
				$meta_fields
			);

		}

		/**
		 * Register metaboxes
		 *
		 * @return void
		 */
		public function register_meta_boxes() {

			if ( empty( $this->meta_boxes ) ) {
				return;
			}

			if ( ! class_exists( 'Jet_Engine_CPT_Meta' ) ) {
				require jet_engine()->plugin_path( 'includes/meta-boxes/post.php' );
			}

			foreach ( $this->meta_boxes as $post_type => $meta_box ) {
				new Jet_Engine_CPT_Meta( $post_type, $meta_box );
			}

		}

		/**
		 * Add notice to stack
		 *
		 * @param string $type    [description]
		 * @param [type] $message [description]
		 */
		public function add_notice( $type = 'error', $message ) {
			$this->notices[] = array(
				'type'    => $type,
				'message' => $message,
			);
		}

		/**
		 * Print stored notices
		 *
		 * @return [type] [description]
		 */
		public function print_notices() {

			if ( empty( $this->notices ) ) {
				return;
			}

			?>
			<div class="cpt-notices"><?php
				foreach ( $this->notices as $notice ) {
					printf( '<div class="notice notice-%1$s"><p>%2$s</p></div>', $notice['type'], $notice['message'] );
				}
			?></div>
			<?php

		}

		/**
		 * Run actions handlers
		 *
		 * @return void
		 */
		public function handle_actions() {

			if ( ! isset( $_GET['action'] ) ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$core_actions = array(
				'create_item' => array( $this->data, 'create_item' ),
				'edit_item'   => array( $this->data, 'edit_item' ),
				'delete_item' => array( $this->data, 'delete_item' ),
			);

			$action = $_GET['action'];

			if ( ! isset( $core_actions[ $action ] ) ) {
				return;
			}

			call_user_func( $core_actions[ $action ] );

		}

		/**
		 * Register CPT menu page
		 */
		public function add_menu_page() {

			add_submenu_page(
				jet_engine()->admin_page,
				esc_html__( 'Post Types', 'jet-engine' ),
				esc_html__( 'Post Types', 'jet-engine' ),
				'manage_options',
				$this->page_slug(),
				array( $this, 'render_page' )
			);

		}

		/**
		 * Check if CPT-related page currently displayed
		 *
		 * @return boolean
		 */
		public function is_cpt_page() {
			return ( isset( $_GET['page'] ) && $this->page_slug() === $_GET['page'] );
		}

		/**
		 * Initialize page builde rinstance
		 *
		 * @return [type] [description]
		 */
		public function init_builder() {

			$builder_data = jet_engine()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

			$this->builder = new CX_Interface_Builder(
				array(
					'path' => $builder_data['path'],
					'url'  => $builder_data['url'],
				)
			);

			$page = $this->get_current_page();

			if ( ! $page ) {
				return;
			}

			$page->register_controls();

		}

		/**
		 * Enqueue CPT assets
		 *
		 * @return void
		 */
		public function enqueue_assets() {

			wp_enqueue_style(
				'jet-engine-cpt',
				jet_engine()->plugin_url( 'assets/css/admin/cpt.css' ),
				array(),
				jet_engine()->get_version()
			);

			wp_enqueue_script(
				'jet-engine-cpt',
				jet_engine()->plugin_url( 'assets/js/admin/cpt.js' ),
				array( 'jquery' ),
				jet_engine()->get_version(),
				true
			);

			wp_localize_script( 'jet-engine-cpt', 'JetCPTData', array(
				'labels' => array(
					'edit'            => __( 'Edit', 'jet-engine' ),
					'close'           => __( 'Close', 'jet-engine' ),
					'confirmDeletion' => __( 'Are you sure you want to delete this item?', 'jet-engine' ),
				),
			) );

		}

		/**
		 * Register CPT related pages
		 *
		 * @return void
		 */
		public function register_pages() {

			$this->data->ensure_db_table();

			$base_path = jet_engine()->plugin_path( 'includes/pages/' );

			require $base_path . 'base.php';

			$default = array(
				'Jet_Engine_CPT_Page_List' => $base_path . 'list.php',
				'Jet_Engine_CPT_Page_Add'  => $base_path . 'add.php',
				'Jet_Engine_CPT_Page_Edit' => $base_path . 'edit.php',
			);

			foreach ( $default as $class => $file ) {
				require $file;
				$this->register_page( $class );
			}

			/**
			 * You could register custom pages on this hook
			 */
			do_action( 'jet-engine/pages/cpt/register', $this );

		}

		/**
		 * Register new dashboard page
		 *
		 * @return [type] [description]
		 */
		public function register_page( $class ) {
			$page = new $class( $this );
			$this->_pages[ $page->get_slug() ] = $page;
		}

		/**
		 * Return page slug
		 *
		 * @return string
		 */
		public function page_slug() {
			return $this->page;
		}

		/**
		 * Render CPT page
		 *
		 * @return void
		 */
		public function render_page() {

			$page = $this->get_current_page();

			if ( ! $page ) {
				return;
			}
			?>
			<div class="wrap">
				<div class="cpt-header">
					<h1 class="wp-heading-inline"><?php echo $page->get_name(); ?></h1>
					<?php do_action( 'jet-engine/cpt/page/after-title', $page ); ?>
					<hr class="wp-header-end">
				</div>
				<?php $this->print_notices(); ?>
				<div class="cpt-content">
					<?php $page->render_page(); ?>
				</div>
			</div>
			<?php


		}

		/**
		 * Get requested page link
		 *
		 * @param  [type] $page [description]
		 * @return [type]       [description]
		 */
		public function get_page_link( $page = null ) {

			if ( ! $page ) {
				return add_query_arg(
					array(
						'page' => $this->page_slug(),
					),
					esc_url( admin_url( 'admin.php' ) )
				);
			}

			$instance = isset( $this->_pages[ $page ] ) ? $this->_pages[ $page ] : false;

			if ( ! $instance ) {
				return;
			}

			return $instance->get_current_page_link();
		}

		/**
		 * Returns current page object
		 *
		 * @return object
		 */
		public function get_current_page() {

			$action = isset( $_GET[ $this->action_key ] ) ? $_GET[ $this->action_key ] : 'list';
			$page   = isset( $this->_pages[ $action ] ) ? $this->_pages[ $action ] : false;

			return $page;

		}

	}

}
