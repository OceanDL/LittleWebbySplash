<?php
/**
 * Compatibility filters and actions
 */

// WPML and Woo compatibility
add_filter( 'wcml_multi_currency_ajax_actions', 'jet_smart_filters_add_action_to_multi_currency_ajax', 10, 1 );

function jet_smart_filters_add_action_to_multi_currency_ajax( $ajax_actions = array() ) {

	$ajax_actions[] = 'jet_smart_filters';

	return $ajax_actions;
}
