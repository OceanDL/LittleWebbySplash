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

if ( ! class_exists( 'Jet_Engine_Listings_Post_Type' ) ) {

	/**
	 * Define Jet_Engine_Listings_Post_Type class
	 */
	class Jet_Engine_Listings_Post_Type {

		/**
		 * Post type slug.
		 *
		 * @var string
		 */
		public $post_type = 'jet-engine';


		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'register_post_type' ) );

			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'add_templates_page' ), 20 );
				add_action( 'add_meta_boxes_' . $this->slug(), array( $this, 'disable_metaboxes' ), 9999 );
			}

			add_action( 'template_include', array( $this, 'set_editor_template' ), 9999 );
			add_action( 'admin_action_jet_create_new_listing', array( $this, 'create_template' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'listing_form_assets' ) );

			add_filter( 'post_row_actions', array( $this, 'remove_view_action' ), 10, 2 );
			add_action( 'current_screen', array( $this, 'no_elementor_notice' ) );

		}

		/**
		 * Add notice on listings page if Elementor not installed
		 *
		 * @return void
		 */
		public function no_elementor_notice() {

			if ( jet_engine()->has_elementor() ) {
				return;
			}

			$screen = get_current_screen();

			if ( $screen->id !== 'edit-' . $this->slug() ) {
				return;
			}

			add_action( 'admin_notices', array( $this, 'no_elementor_warning' ) );

		}

		/**
		 * Print no elementor notice
		 *
		 * @return [type] [description]
		 */
		public function no_elementor_warning() {

			$install_url = add_query_arg(
				array(
					's'    => 'elementor',
					'tab'  => 'search',
					'type' => 'term',
				),
				admin_url( 'plugin-install.php' )
			);

			?>
			<div class="notice notice-warning">
				<p><?php
					_e( 'You need an <b>Elementor Page Builder</b> plugin to create and edit listing items', 'jet-engine' );
				?></p>
				<p>
					<a href="<?php echo $install_url; ?>">
						<b><?php _e( 'Install Elementor Page Builder', 'jet-engine' ); ?></b>
					</a>
				</p>
			</div>
			<?php
		}

		/**
		 * Actions posts
		 *
		 * @param  [type] $actions [description]
		 * @param  [type] $post    [description]
		 * @return [type]          [description]
		 */
		public function remove_view_action( $actions, $post ) {

			if ( $this->slug() === $post->post_type ) {
				unset( $actions['view'] );
			}

			return $actions;

		}

		public function listing_form_assets() {

			$screen = get_current_screen();

			if ( $screen->id !== 'edit-' . $this->slug() ) {
				return;
			}

			wp_enqueue_script(
				'jet-listings-form',
				jet_engine()->plugin_url( 'assets/js/admin/listings.js' ),
				array( 'jquery' ),
				jet_engine()->get_version(),
				true
			);

			wp_localize_script( 'jet-listings-form', 'JetListingsSettings', array(
				'hasElementor' => jet_engine()->has_elementor(),
			) );

			wp_enqueue_style(
				'jet-listings-form',
				jet_engine()->plugin_url( 'assets/css/admin/listings.css' ),
				array(),
				jet_engine()->get_version()
			);

			add_action( 'admin_footer', array( $this, 'print_listings_popup' ), 999 );

		}

		/**
		 * Print template type form HTML
		 *
		 * @return void
		 */
		public function print_listings_popup() {

			$action = add_query_arg(
				array(
					'action' => 'jet_create_new_listing',
				),
				esc_url( admin_url( 'admin.php' ) )
			);

			include jet_engine()->get_template( 'listings-popup.php' );
		}

		/**
		 * Create new template
		 *
		 * @return [type] [description]
		 */
		public function create_template() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die(
					esc_html__( 'You don\'t have permissions to do this', 'jet-engine' ),
					esc_html__( 'Error', 'jet-engine' )
				);
			}

			if ( ! class_exists( 'Elementor\Plugin' ) ) {
				wp_die(
					__( 'Please install <a href="https://wordpress.org/plugins/elementor/" target="_blank">Elementor page builder</a> to manage listings layout', 'jet-engine' ),
					__( 'Elementor missed', 'jet-engine' )
				);
			}

			$documents = Elementor\Plugin::instance()->documents;
			$doc_type  = $documents->get_document_type( jet_engine()->listings->get_id() );

			if ( ! $doc_type ) {
				wp_die(
					esc_html__( 'Incorrect template type. Please try again.', 'jet-engine' ),
					esc_html__( 'Error', 'jet-engine' )
				);
			}

			$post_data = array(
				'post_type'  => $this->slug(),
				'meta_input' => array(
					'_elementor_edit_mode' => 'builder',
				),
				'meta_input' => array(
					$doc_type::TYPE_META_KEY => jet_engine()->listings->get_id(),
				),
			);

			$title = isset( $_REQUEST['template_name'] ) ? esc_attr( $_REQUEST['template_name'] ) : '';

			if ( $title ) {
				$post_data['post_title'] = $title;
			}

			$post_data   = apply_filters( 'jet-engine/templates/create/data', $post_data );
			$template_id = wp_insert_post( $post_data );

			if ( ! $template_id ) {
				wp_die(
					esc_html__( 'Can\'t create template. Please try again', 'jet-engine' ),
					esc_html__( 'Error', 'jet-engine' )
				);
			}

			wp_redirect( Elementor\Utils::get_edit_link( $template_id ) );
			die();

		}

		/**
		 * Templates post type slug
		 *
		 * @return string
		 */
		public function slug() {
			return $this->post_type;
		}

		/**
		 * Disable metaboxes from Jet Templates
		 *
		 * @return void
		 */
		public function disable_metaboxes() {
			global $wp_meta_boxes;
			unset( $wp_meta_boxes[ $this->slug() ]['side']['core']['pageparentdiv'] );
		}

		/**
		 * Register templates post type
		 *
		 * @return void
		 */
		public function register_post_type() {

			$args = array(
				'labels' => array(
					'name'               => esc_html__( 'Listing Items', 'jet-engine' ),
					'singular_name'      => esc_html__( 'Listing Item', 'jet-engine' ),
					'add_new'            => esc_html__( 'Add New', 'jet-engine' ),
					'add_new_item'       => esc_html__( 'Add New Item', 'jet-engine' ),
					'edit_item'          => esc_html__( 'Edit Item', 'jet-engine' ),
					'new_item'           => esc_html__( 'Add New Item', 'jet-engine' ),
					'view_item'          => esc_html__( 'View Item', 'jet-engine' ),
					'search_items'       => esc_html__( 'Search Item', 'jet-engine' ),
					'not_found'          => esc_html__( 'No Templates Found', 'jet-engine' ),
					'not_found_in_trash' => esc_html__( 'No Templates Found In Trash', 'jet-engine' ),
					'menu_name'          => esc_html__( 'My Library', 'jet-engine' ),
				),
				'public'              => true,
				'hierarchical'        => false,
				'show_ui'             => true,
				'show_in_menu'        => false,
				'show_in_nav_menus'   => false,
				'can_export'          => true,
				'exclude_from_search' => true,
				'capability_type'     => 'post',
				'rewrite'             => false,
				'supports'            => array( 'title', 'editor', 'thumbnail', 'author', 'elementor' ),
			);

			register_post_type(
				$this->slug(),
				apply_filters( 'jet-engine/templates/post-type/args', $args )
			);

		}

		/**
		 * Menu page
		 */
		public function add_templates_page() {

			add_submenu_page(
				jet_engine()->admin_page,
				esc_html__( 'Listings', 'jet-engine' ),
				esc_html__( 'Listings', 'jet-engine' ),
				'edit_pages',
				'edit.php?post_type=' . $this->slug()
			);

		}

		/**
		 * Editor templates.
		 *
		 * @param  string $template Current template name.
		 * @return string
		 */
		public function set_editor_template( $template ) {

			$found = false;

			if ( is_singular( $this->slug() ) ) {
				$found    = true;
				$template = jet_engine()->plugin_path( 'templates/blank.php' );
			}

			if ( $found ) {
				do_action( 'jet-engine/post-type/editor-template/found' );
			}

			return $template;

		}

	}

}
