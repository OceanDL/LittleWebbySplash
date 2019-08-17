<?php
/**
 * Handles TGMPA functionalities.
 *
 * @since 1.5.0
 *
 * @package Jupiter\Framework\Admin\TGMPA
 */

add_action( 'tgmpa_register', 'jupiterx_register_tgmpa_plugins' );
/**
 * Register the required plugins.
 *
 * @since 1.5.0
 */
function jupiterx_register_tgmpa_plugins() {
	$plugins = get_transient( 'jupiterx_tgmpa_plugins' );

	if ( false === $plugins ) {
		$headers = [
			'api-key'      => jupiterx_get_api_key(),
			'domain'       => $_SERVER['SERVER_NAME'], // phpcs:ignore
			'theme-name'   => 'JupiterX',
			'from'         => 0,
			'count'        => 0,
			'list-of-attr' => wp_json_encode( [
				'name',
				'slug',
				'required',
				'version',
				'source',
				'pro',
			] ),
		];

		$response = json_decode( wp_remote_retrieve_body( wp_remote_get( 'https://artbees.net/api/v2/tools/plugin-custom-list', [
			'headers'   => $headers,
		] ) ) );

		if ( ! isset( $response->data ) && ! is_array( $response->data ) ) {
			return;
		}

		$wp_plugins = [];

		foreach ( $response->data as $index => $plugin ) {
			$plugins[ $index ] = (array) $plugin;

			if ( 'wp-repo' === $plugin->source ) {
				unset( $plugins[ $index ]['source'] );

				if ( ! jupiterx_is_premium() ) {
					$plugins[ $index ]['required'] = false;
				}

				$wp_plugins[] = $plugins[ $index ];
			}
		}

		if ( ! jupiterx_is_premium() ) {
			$plugins = $wp_plugins;
		}

		set_transient( 'jupiterx_tgmpa_plugins', $plugins, 12 * HOUR_IN_SECONDS );
	}

	$config = [
		'id'           => 'jupiterx',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',
	];

	tgmpa( $plugins, $config );
}
