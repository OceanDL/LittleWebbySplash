<?php
/**
 * Plugin Name: JetEngine
 * Plugin URI:  https://jetengine.zemez.io/
 * Description: The ultimate solution for managing custom post types, taxonomies and meta boxes.
 * Version:     1.4.1
 * Author:      Zemez
 * Author URI:  https://zemez.io/wordpress/
 * Text Domain: jet-engine
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `Jet_Engine` doesn't exists yet.
if ( ! class_exists( 'Jet_Engine' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class Jet_Engine {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * A reference to an instance of cherry framework core class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private $core = null;

		/**
		 * Holder for base plugin URL
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_url = null;

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		private $version = '1.4.1';

		/**
		 * Holder for base plugin path
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		/**
		 * Plugin base name
		 *
		 * @var string
		 */
		public $plugin_name = null;

		/**
		 * Jet engine menu page slug
		 *
		 * @var string
		 */
		public $admin_page = 'jet-engine';

		/**
		 * Components
		 */
		public $framework;
		public $post_type;
		public $db;
		public $cpt;
		public $taxonomies;
		public $meta_boxes;
		public $relations;
		public $listings;
		public $compatibility;
		public $dynamic_tags;
		public $frontend;
		public $dashboard;
		public $modules;
		public $forms;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			$this->plugin_name = plugin_basename( __FILE__ );

			// Load framework
			add_action( 'after_setup_theme', array( $this, 'framework_loader' ), -20 );

			// Internationalize the text strings used.
			add_action( 'init', array( $this, 'lang' ), -999 );
			// Load files.
			add_action( 'init', array( $this, 'init' ), -999 );
			// Plugin row meta
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );


		}

		/**
		 * Returns plugin version
		 *
		 * @return string
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Load framework modules
		 *
		 * @return [type] [description]
		 */
		public function framework_loader() {

			require $this->plugin_path( 'framework/loader.php' );

			$this->framework = new Jet_Engine_CX_Loader(
				array(
					$this->plugin_path( 'framework/interface-builder/cherry-x-interface-builder.php' ),
					$this->plugin_path( 'framework/post-meta/cherry-x-post-meta.php' ),
					$this->plugin_path( 'framework/term-meta/cherry-x-term-meta.php' ),
				)
			);

		}

		/**
		 * Manually init required modules.
		 *
		 * @return void
		 */
		public function init() {

			$this->load_files();

			$this->post_type     = new Jet_Engine_Listings_Post_Type();
			$this->db            = new Jet_Engine_DB();
			$this->cpt           = new Jet_Engine_CPT();
			$this->taxonomies    = new Jet_Engine_CPT_Tax();
			$this->meta_boxes    = new Jet_Engine_Meta_Boxes();
			$this->relations     = new Jet_Engine_Relations();
			$this->listings      = new Jet_Engine_Listings();
			$this->compatibility = new Jet_Engine_Compatibility();
			$this->dynamic_tags  = new Jet_Engine_Dynamic_Tags_Manager();
			$this->frontend      = new Jet_Engine_Frontend();
			$this->modules       = new Jet_Engine_Modules();
			$this->forms         = new stdClass();

			if ( is_admin() ) {

				$this->dashboard = new Jet_Engine_Dashboard();

				require $this->plugin_path( 'includes/updater/plugin-update.php' );

				new Jet_Engine_Plugin_Update( array(
					'version' => $this->get_version(),
					'slug'    => 'jet-engine',
				) );

			}

			do_action( 'jet-engine/init', $this );

		}

		/**
		 * Load required files
		 *
		 * @return void
		 */
		public function load_files() {

			if ( is_admin() ) {
				require $this->plugin_path( 'includes/dashboard.php' );
			}

			require $this->plugin_path( 'includes/db.php' );
			require $this->plugin_path( 'includes/listings/post-type.php' );
			require $this->plugin_path( 'includes/frontend.php' );
			require $this->plugin_path( 'includes/cpt/manager.php' );
			require $this->plugin_path( 'includes/taxonomies/manager.php' );
			require $this->plugin_path( 'includes/meta-boxes/manager.php' );
			require $this->plugin_path( 'includes/relations/manager.php' );
			require $this->plugin_path( 'includes/listings/manager.php' );
			require $this->plugin_path( 'includes/compatibility/manager.php' );
			require $this->plugin_path( 'includes/dynamic-tags/manager.php' );
			require $this->plugin_path( 'includes/modules/manager.php' );

			// Misc functions
			require $this->plugin_path( 'includes/functions.php' );

		}

		/**
		 * Check if theme has elementor
		 *
		 * @return boolean
		 */
		public function has_elementor() {
			return defined( 'ELEMENTOR_VERSION' );
		}

		/**
		 * Check if theme has elementor
		 *
		 * @return boolean
		 */
		public function has_elementor_pro() {
			return defined( 'ELEMENTOR_PRO_VERSION' );
		}

		/**
		 * Returns path to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_path( $path = null ) {

			if ( ! $this->plugin_path ) {
				$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path . $path;
		}
		/**
		 * Returns url to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
			}

			return $this->plugin_url . $path;
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function lang() {
			load_plugin_textdomain( 'jet-engine', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'jet-engine/template-path', 'jet-engine/' );
		}

		/**
		 * Returns path to template file.
		 *
		 * @return string|bool
		 */
		public function get_template( $name = null ) {

			$template = locate_template( $this->template_path() . $name );

			if ( ! $template ) {
				$template = $this->plugin_path( 'templates/' . $name );
			}

			if ( file_exists( $template ) ) {
				return $template;
			} else {
				return false;
			}
		}

		/**
		 * Add plugin changelog link.
		 *
		 * @param array  $plugin_meta
		 * @param string $plugin_file
		 *
		 * @return array
		 */
		public function plugin_row_meta( $plugin_meta, $plugin_file ) {
			if ( plugin_basename( __FILE__ ) === $plugin_file ) {
				$plugin_meta['changelog'] = sprintf(
					'<a href="http://documentation.zemez.io/wordpress/index.php?project=jetengine&lang=en&section=jetengine-changelog" target="_blank">%s</a>',
					esc_html__( 'Changelog', 'jet-engine' )
				);
			}

			return $plugin_meta;
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function activation() {
			require $this->plugin_path( 'includes/db.php' );
			Jet_Engine_DB::create_all_tables();
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function deactivation() {
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

if ( ! function_exists( 'jet_engine' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function jet_engine() {
		return Jet_Engine::get_instance();
	}
}

jet_engine();
