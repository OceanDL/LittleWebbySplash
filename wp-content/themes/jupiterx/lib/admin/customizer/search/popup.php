<?php
/**
 * Add Jupiter Search Page popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Layout popup.
JupiterX_Customizer::add_section( 'jupiterx_search', [
	'panel' => 'jupiterx_pages',
	'title' => __( 'Search', 'jupiterx' ),
	'type'  => 'popup',
	'tabs'  => [
		'settings' => __( 'Settings', 'jupiterx' ),
	],
	'preview' => true,
	'help'    => [
		'url'   => 'http://help.artbees.net/how-to-s/customizer/displaying-search-results-from-specific-post-types',
		'title' => __( 'Displaying Search Results from specific Post Types', 'jupiterx' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_search_settings', [
	'popup' => 'jupiterx_search',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
