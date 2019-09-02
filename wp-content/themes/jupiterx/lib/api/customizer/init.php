<?php
/**
 * This class handles customizer function.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Extends WordPress customizer capability.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
final class _JupiterX_Customizer_Init {

	/**
	 * List of autoloaded modules.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $modules = [
		'compiler'     => 'JupiterX_Customizer_Compiler',
		'kirki-extend' => 'JupiterX_Customizer_Kirki_Extend',
		'post-message' => 'JupiterX_Customizer_Post_Message',
	];

	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->add_hooks();
		$this->load_modules();
	}

	/**
	 * Define customizer constants.
	 *
	 * @since 1.0.0
	 */
	protected function define_constants() {
		define( 'JUPITERX_CUSTOMIZER_PATH', trailingslashit( JUPITERX_API_PATH . 'customizer' ) );
		define( 'JUPITERX_CUSTOMIZER_URL', trailingslashit( JUPITERX_API_URL . 'customizer' ) );

		if ( function_exists( 'jupiterx_core' ) && is_customize_preview() ) {
			jupiterx_customizer_kirki();
		}
	}

	/**
	 * Include files.
	 *
	 * @since 1.0.0
	 */
	protected function includes() {
		include_once JUPITERX_CUSTOMIZER_PATH . 'includes/class-autoloader.php';
		include_once JUPITERX_CUSTOMIZER_PATH . 'includes/class-templates.php';
		include_once JUPITERX_CUSTOMIZER_PATH . 'classes/class-multilingual.php';
	}

	/**
	 * Add filters and actions.
	 *
	 * @since 1.0.0
	 */
	protected function add_hooks() {
		add_action( 'customize_register', [ $this, 'register_control_types' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'customize_preview_init', [ $this, 'enqueue_preview_scripts' ] );
		add_action( 'scp_js_path_url', [ $this, 'multilingual_script_path' ] );
		add_action( 'after_switch_theme', [ $this, 'update_initial_mods' ] );
	}

	/**
	 * Update initial theme mods to remove mods with integer key.
	 *
	 * @since 1.4.0
	 *
	 * @return void
	 */
	public function update_initial_mods() {
		$mods = get_theme_mods();

		foreach ( $mods as $key => $value ) {
			if ( is_numeric( $key ) ) {
				unset( $mods[ $key ] );
			}
		}

		$theme_stylesheet_slug = get_option( 'stylesheet' );

		update_option( 'theme_mods_' . $theme_stylesheet_slug, $mods );
	}

	/**
	 * Load modules.
	 *
	 * @since 1.0.0
	 */
	protected function load_modules() {
		foreach ( $this->modules as $module ) {
			if ( class_exists( $module ) && ( ! method_exists( $module, 'active' ) || $module::active() ) ) {
				new $module();
			}
		}
	}

	/**
	 * Register all control types.
	 *
	 * @since 1.0.0
	 *
	 * @param object $wp_customize Global customize object.
	 */
	public function register_control_types( $wp_customize ) {
		foreach ( JupiterX_Customizer::$control_types as $control_type ) {
			$wp_customize->register_control_type( $control_type );
		}
	}

	/**
	 * Enqueue styles and scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( 'jupiterx-webfont', JUPITERX_ADMIN_ASSETS_URL . 'lib/webfont/webfont' . JUPITERX_MIN_JS . '.js', [], '1.6.26', false );
		wp_register_script( 'jupiterx-spectrum', JUPITERX_CUSTOMIZER_URL . 'assets/lib/spectrum/spectrum' . JUPITERX_MIN_JS . '.js', [], '1.8.0', true );
		wp_register_script( 'jupiterx-select2', JUPITERX_CUSTOMIZER_URL . 'assets/lib/select2/select2' . JUPITERX_MIN_JS . '.js', [], '4.0.6', true );
		wp_register_script( 'jupiterx-stepper', JUPITERX_CUSTOMIZER_URL . 'assets/lib/stepper/stepper' . JUPITERX_MIN_JS . '.js', [], '1.0.0', true );
		wp_register_script( 'jupiterx-url-polyfill', JUPITERX_CUSTOMIZER_URL . 'assets/lib/url-polyfill/url-polyfill' . JUPITERX_MIN_JS . '.js', [], '1.1.0', false );
		wp_enqueue_script( 'jupiterx-customizer', JUPITERX_CUSTOMIZER_URL . 'assets/js/customizer' . JUPITERX_MIN_JS . '.js', [ 'jquery', 'jquery-ui-draggable', 'jquery-ui-sortable', 'jupiterx-webfont', 'jupiterx-spectrum', 'jupiterx-select2', 'jupiterx-stepper', 'jupiterx-url-polyfill' ], JUPITERX_VERSION, true );
		wp_register_style( 'jupiterx-spectrum', JUPITERX_CUSTOMIZER_URL . 'assets/lib/spectrum/spectrum' . JUPITERX_MIN_CSS . '.css', [], '1.8.0' );
		wp_register_style( 'jupiterx-select2', JUPITERX_CUSTOMIZER_URL . 'assets/lib/select2/select2' . JUPITERX_MIN_CSS . '.css', [], '4.0.6' );
		wp_enqueue_style( 'jupiterx-customizer', JUPITERX_CUSTOMIZER_URL . 'assets/css/customizer' . JUPITERX_RTL . JUPITERX_MIN_CSS . '.css', [ 'jupiterx-spectrum', 'jupiterx-select2' ], JUPITERX_VERSION );
	}

	/**
	 * Enqueue preview styles and scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_preview_scripts() {
		wp_enqueue_script( 'jupiterx-customizer-preview', JUPITERX_ADMIN_ASSETS_URL . 'js/customizer-preview' . JUPITERX_MIN_JS . '.js', [ 'customize-preview', 'jupiterx-utils' ], JUPITERX_VERSION, true );
	}

	/**
	 * Filter js path for customizer multilingual scripts.
	 *
	 * @since 1.0.0
	 */
	public function multilingual_script_path() {
		return JUPITERX_CUSTOMIZER_URL . 'assets/lib/';
	}
}

// Run customizer class.
new _JupiterX_Customizer_Init();
