<?php
/**
 * Class handles for required plugins update.
 *
 * @since 1.3.0
 *
 * @package Jupiter\Framework\Admin\Plugins_Notice
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugins update notice.
 *
 * @since 1.3.0
 *
 * @package Jupiter\Framework\Admin\Plugins_Notice
 */
class JupiterX_Update_Plugins {

	/**
	 * Plugins list to update.
	 *
	 * @var array
	 */
	public $update_plugins = [];

	/**
	 * Class constructor.
	 *
	 * @since 1.3.0
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'add_actions' ] );
		add_action( 'upgrader_process_complete', [ $this, 'set_transients' ], 10, 2 );
	}

	/**
	 * Add actions.
	 *
	 * @since 1.3.0
	 */
	public function add_actions() {
		if ( ! get_site_transient( 'jupiterx_update_plugins_notice' ) || wp_doing_ajax() ) {
			return;
		}

		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		$this->update_plugins = jupiterx_get_update_plugins();

		// If there are no plugins to update.
		if ( empty( $this->update_plugins ) ) {
			delete_site_transient( 'jupiterx_update_plugins_notice' );
			return;
		}

		add_action( 'admin_print_scripts', 'wp_print_admin_notice_templates' );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ], 15 );
		add_action( 'admin_notices', [ $this, 'notice' ] );
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @since 1.3.0
	 */
	public function enqueue_scripts() {
		wp_add_inline_script( 'jupiterx-common', '
			( function() {
				if ( typeof jupiterx === \'object\' && typeof jupiterx.updatePluginsModal !== \'undefined\' ) {
					var plugins = JSON.parse( \'' . wp_json_encode( $this->update_plugins ) . '\' );
					jupiterx.updatePluginsModal( plugins );
				}
			} )( );
		', 'after' );
	}

	/**
	 * Print admin notice.
	 *
	 * @since 1.3.0
	 */
	public function notice() {
		$plugin_names = [];

		foreach ( $this->update_plugins as $plugin ) {
			$plugin_names[ $plugin['slug'] ] = $plugin['name'];
		}

		?>
		<div id="jupiterx-update-plugins-notice" class="notice notice-warning jupiterx-update-plugins-notice">
			<div class="jupiterx-update-plugins-notice-logo">
				<img src="<?php echo esc_url( JUPITERX_ADMIN_ASSETS_URL . 'images/jupiterx-notice-logo.png' ); ?>" alt="<?php esc_html_e( 'Jupiter X', 'jupiterx' ); ?>" />
			</div>
			<div class="jupiterx-update-plugins-notice-content">
				<h2><?php esc_html_e( 'Important plugins waiting to be updated! 👋', 'jupiterx' ); ?></h2>
				<p>
					<?php esc_html_e( 'New updates are available for these essential plugins. Jupiter X may not work properly without the latest version of these plugins.', 'jupiterx' ); ?>
					<strong><?php echo esc_html( implode( ', ', $plugin_names ) ); ?></strong>
				</p>
				<button class="button button-primary jupiterx-update-plugins-notice-button"><?php esc_html_e( 'Start Update', 'jupiterx' ); ?></button>
				<input value="<?php echo esc_attr( wp_json_encode( $this->update_plugins ) ); ?>" type="hidden" />
			</div>
		</div>
		<?php
	}

	/**
	 * Set transients.
	 *
	 * @since 1.3.0
	 *
	 * @param WP_Upgrader $upgrader Upgrader skin.
	 * @param array       $status Extra hooks.
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function set_transients( $upgrader, $status ) {
		if ( 'theme' !== $status['type'] || 'update' !== $status['action'] ) {
			return;
		}

		if ( ( isset( $status['theme'] ) && JUPITERX_SLUG === $status['theme'] ) || ( isset( $status['themes'] ) && in_array( JUPITERX_SLUG, $status['themes'], true ) ) ) {
			set_site_transient( 'jupiterx_update_plugins_notice', 'yes' );
		}
	}
}

new JupiterX_Update_Plugins();
