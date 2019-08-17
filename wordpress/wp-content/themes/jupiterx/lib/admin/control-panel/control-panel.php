<?php
/**
 * This class handles init of Control Panel.
 *
 * @since 1.0.0
 *
 * @package JupiterX\Framework\Admin\Control_Panel
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Init class.
 *
 * @since 1.0.0
 *
 * @package JupiterX\Framework\Admin\Control_Panel
 */
final class JupiterX_Control_Panel {

	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @var JupiterX_Control_Panel
	 */
	protected static $instance = null;

	/**
	 * Returns JupiterX_Control_Panel instance.
	 *
	 * @since 1.0.0
	 *
	 * @return JupiterX_Control_Panel
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->define_constants();

		if ( $this->is_control_panel() || wp_doing_ajax() ) {
			$this->init();
		}
	}

	/**
	 * Define constants.
	 *
	 * @since 1.0.0
	 */
	public function define_constants() {
		$this->define( 'JUPITERX_CONTROL_PANEL_HOME', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_PLUGINS', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_TEMPLATES', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_IMAGE_SIZES', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_SYSTEM_STATUS', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_EXPORT_IMPORT', false );
		$this->define( 'JUPITERX_CONTROL_PANEL_UPDATES', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_SETTINGS', true );
		$this->define( 'JUPITERX_CONTROL_PANEL_SUPPORT', true );
		define( 'JUPITERX_CONTROL_PANEL_API_V1_URL', 'https://artbees.net/api/v1/' );
		define( 'JUPITERX_CONTROL_PANEL_API_V2_URL', 'https://artbees.net/api/v2/' );
		define( 'JUPITERX_CONTROL_PANEL_PATH', JUPITERX_ADMIN_PATH . 'control-panel/' );
		define( 'JUPITERX_CONTROL_PANEL_URL', JUPITERX_ADMIN_URL . 'control-panel/' );
		define( 'JUPITERX_CONTROL_PANEL_ASSETS_PATH', JUPITERX_CONTROL_PANEL_PATH . 'assets/' );
		define( 'JUPITERX_CONTROL_PANEL_ASSETS_URL', JUPITERX_CONTROL_PANEL_URL . 'assets/' );
	}

	/**
	 * Safely define a constant.
	 *
	 * @since 1.0.4
	 *
	 * @param string $name Constant name.
	 * @param mixed $value Define value.
	 */
	public function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Init control panel.
	 *
	 * Only init the control panel when the visiting page is control panel or currently doing ajax.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		require_once JUPITERX_CONTROL_PANEL_PATH . 'classes/class-customizer-option.php';
		require_once JUPITERX_CONTROL_PANEL_PATH . 'classes/class-filesystem.php';
		require_once JUPITERX_CONTROL_PANEL_PATH . 'classes/class-validator.php';

		require_once JUPITERX_CONTROL_PANEL_PATH . 'includes/export-import-content.php';
		require_once JUPITERX_CONTROL_PANEL_PATH . 'includes/helpers.php';
		require_once JUPITERX_CONTROL_PANEL_PATH . 'includes/database-backups.php';

		require_once JUPITERX_CONTROL_PANEL_PATH . 'includes/messages/js-messages-lib.php';
		require_once JUPITERX_CONTROL_PANEL_PATH . 'includes/messages/logic-messages-lib.php';

		
		require_once JUPITERX_CONTROL_PANEL_PATH . 'includes/activate-theme.php';
		

		require_once JUPITERX_CONTROL_PANEL_PATH . 'includes/system-status.php';
		// JUPITERX-PRO
		require_once JUPITERX_CONTROL_PANEL_PATH . 'includes/update-theme.php';
		require_once JUPITERX_CONTROL_PANEL_PATH . 'includes/downgrade-theme.php';
		// JUPITERX-PRO-END

		/**
		 * Hook for control panel init.
		 *
		 * @since 1.3.0
		 */
		do_action( 'jupiterx_control_panel_init' );

		$this->set_theme_current_version();

		add_filter( 'upload_mimes', [ $this, 'mime_types' ] );
		add_filter( 'getimagesize_mimes_to_exts', [ $this, 'mime_to_ext' ] );
		add_action( 'wp_ajax_jupiterx_cp_load_pane_action', [ $this, 'load_control_panel_pane' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	/**
	 * Check if its control panel is currently viewing page.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean Test currently viewing page.
	 */
	public function is_control_panel() {
		return (boolean) isset( $_GET['page'] ) && $_GET['page'] === JUPITERX_SLUG;
	}

	/**
	 * Load control panel styles and scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_assets() {
		// Enqueue styles.
		wp_enqueue_style( 'jupiterx-modal', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'css/jupiterx-modal' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
		wp_enqueue_style( 'jupiterx-control-panel', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'css/control-panel' . JUPITERX_MIN_CSS . '.css', [], JUPITERX_VERSION );
		wp_enqueue_style( 'jupiterx-admin-font', JUPITERX_ADMIN_URL . 'assets/css/icons-admin.css', [], JUPITERX_VERSION );
		wp_enqueue_media();

		// Enqueue scripts.
		wp_enqueue_script( 'jupiterx-popper', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'lib/popper/popper' . JUPITERX_MIN_JS . '.js', [], '1.14.3', true );
		wp_enqueue_script( 'jupiterx-bootstrap', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'lib/bootstrap/bootstrap' . JUPITERX_MIN_JS . '.js', [], '4.1.2', true );
		wp_enqueue_script( 'jupiterx-gsap', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'lib/gsap/gsap' . JUPITERX_MIN_JS . '.js', [], '1.19.1', true );
		wp_enqueue_script( 'jupiterx-dynamicmaxheight', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'lib/dynamicmaxheight/dynamicmaxheight' . JUPITERX_MIN_JS . '.js', [], '0.0.3', true );
		wp_enqueue_script( 'jupiterx-modal', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'js/jupiterx-modal' . JUPITERX_MIN_JS . '.js', [], JUPITERX_VERSION, true );
		wp_enqueue_script( 'jupiterx-control-panel', JUPITERX_CONTROL_PANEL_ASSETS_URL . 'js/control-panel' . JUPITERX_MIN_JS . '.js', [ 'jquery', 'wp-util', 'updates' ], JUPITERX_VERSION, true );

		// Localize scripts.
		wp_localize_script( 'jupiterx-control-panel', 'jupiterx_cp_textdomain', jupiterx_adminpanel_textdomain() );
		wp_localize_script(
			'jupiterx-control-panel',
			'jupiterxControlPanel',
			[
				'nonce'       => wp_create_nonce( 'jupiterx_control_panel' ),
				'proBadgeUrl' => jupiterx_get_pro_badge_url(),
			]
		);
	}

	/**
	 * Filter hook that will allow WordPress to accept .zip formats in media library.
	 *
	 * @since 1.0.0
	 *
	 * @return array Updated array of mime types.
	 */
	public function mime_types( $mimes ) {
		$mimes['zip'] = 'application/zip';
		return $mimes;
	}

	/**
	 * Map the "image/vnd.microsoft.icon" MIME type to the ico file extension, instead of
	 * modifying the expected MIME types of WordPress in the WordPress wp_get_mime_types()
	 * function.
	 *
	 * This is work-around for a bug in WordPress when the PHP version returns MIME
	 * type of "image/vnd.microsoft.icon" instead of "image/x-icon"
	 * that WordPress expects.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of image mime types and their matching extensions.
	 */
	public function mime_to_ext( $mimes_to_text ) {
		$mimes_to_text['image/vnd.microsoft.icon'] = 'ico';
		return $mimes_to_text;
	}

	/**
	 * Stores theme current version into options table to be used in multiple instances
	 * inside the control panel.
	 *
	 * @since 1.0.0
	 */
	public function set_theme_current_version() {
		if ( get_option( 'jupiterx_theme_current_version' ) !== JUPITERX_VERSION ) {
			update_option( 'jupiterx_theme_current_version', JUPITERX_VERSION );
		}
	}

	/**
	 * Load the pane by the slug name.
	 *
	 * This function is called via admin-ajax.php.
	 *
	 * @since 1.0.0
	 */
	public function load_control_panel_pane() {
		$slug = esc_attr( $_POST['slug'] );
		ob_start();
		$this->print_pane( $slug );
		$pane_html = ob_get_clean();
		wp_send_json_success( $pane_html );
		wp_die();
	}

	/**
	 * Print pane HTML by slug.
	 *
	 * @param string $slug Pane ID.
	 */
	public function print_pane( $slug ) {
		$file = JUPITERX_CONTROL_PANEL_PATH . "/views/panes/{$slug}.php";

		// String pattern replace.
		$slug = str_replace( '-', '_', $slug );
		$file = apply_filters( "jupiterx_control_panel_pane_{$slug}", $file );

		if ( file_exists( $file ) ) {
			include_once $file;
			return;
		}

		include JUPITERX_CONTROL_PANEL_PATH . "/views/panes/home.php";
	}
}

/**
 * Create single instance and globalize.
 *
 * @since 1.0.0
 *
 * @return JupiterX_Control_Panel
 */
function jupiterx_control_panel() {
	return JupiterX_Control_Panel::get_instance();
}

// Initialize control panel.
jupiterx_control_panel();
