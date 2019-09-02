<?php
/**
 * This class handles the Compatibility component.
 *
 * @package JupiterX\Framework\API\Compatibility
 *
 * @since 1.0.0
 */

/**
 * The Compatibility component.
 *
 * @since   1.0.0
 * @ignore
 * @access  private
 *
 * @package JupiterX\Framework\API\Compatibility
 */
final class _JupiterX_Compatibility {

	/**
	 * Versions update functions.
	 *
	 * @var array
	 */
	private $updates = [
		'1.0.2' => 'jupiterx_update_v102',
		'1.3.0' => 'jupiterx_update_v130',
	];

	/**
	 * Current Jupiter version saved from database.
	 *
	 * @var string
	 */
	private $current_version = '';

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init() {
		add_action( 'jupiterx_init', [ $this, 'version_check' ], 0 );
	}

	/**
	 * Run version compare.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function version_check() {
		/**
		 * The default value in this option is temporary and it should be 'null'. Temporary set to '0.6.0' because in this version we didn't have
		 * the compatibility updater. This is just to trigger that the theme have to run the initial upgrade for '0.6.5' and later after the
		 * templates websites received the updates we can safely turn the default value back to null.
		 */
		$this->current_version = get_option( 'jupiterx_theme_version', null );

		/**
		 * Null or empty value may considered as a freshly installed theme.
		 */
		if ( is_null( $this->current_version ) || empty( $this->current_version ) ) {
			$this->update_version();
			return;
		}

		if ( version_compare( JUPITERX_VERSION, $this->current_version, '>' ) ) {
			$this->run_updates();
		}
	}

	/**
	 * Update version from the database.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function update_version() {
		update_option( 'jupiterx_theme_version', JUPITERX_VERSION );
	}

	/**
	 * Run updates.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function run_updates() {
		/**
		 * Start running updated one by one.
		 */
		foreach ( $this->updates as $version => $function ) {
			if ( version_compare( $version, $this->current_version, '>' ) ) {
				call_user_func( $function );
			}
		}

		// At this point, it is safe to update the version.
		$this->update_version();
	}
}

new _JupiterX_Compatibility();
