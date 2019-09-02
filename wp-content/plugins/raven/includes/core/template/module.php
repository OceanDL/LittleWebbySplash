<?php
/**
 * Add Template Library Module.
 *
 * @package Raven
 * @since 1.0.0
 */

namespace Raven\Core\Template;

use Elementor\TemplateLibrary;
use Elementor\Api;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * Raven template library module.
 *
 * Raven template library module handler class is responsible for registering and fetching
 * Raven templates.
 *
 * @since 1.0.0
 */
class Module {

	/**
	 * API URL.
	 *
	 * Holds the URL of the API.
	 *
	 * @access private
	 * @static
	 *
	 * @var string API URL.
	 */
	private static $api_url = 'https://demos.artbees.net/jupiterx/library/wp-json/jupiterx/v1/templates/%s';

	/**
	 * Fetch templates from server.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Templates.
	 */
	public static function get_templates() {
		$url = sprintf( self::$api_url, '' );

		$response = wp_remote_get( $url, [
			'timeout' => 40,
		] );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return new \WP_Error( 'response_code_error', sprintf( 'The request returned with a status code of %s.', $response_code ) );
		}

		$template_content = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $template_content['error'] ) ) {
			return new \WP_Error( 'response_error', $template_content['error'] );
		}

		if ( empty( $template_content ) ) {
			return new \WP_Error( 'template_data_error', 'An invalid data was returned.' );
		}

		return $template_content;
	}

	/**
	 * Fetch template content from server.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $template_id Template ID.
	 *
	 * @return array Template content.
	 */
	public static function get_template_content( $template_id ) {
		$url = sprintf( self::$api_url, $template_id );

		$response = wp_remote_get( $url, [
			'timeout' => 60,
		] );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return new \WP_Error( 'response_code_error', sprintf( 'The request returned with a status code of %s.', $response_code ) );
		}

		$template_content = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $template_content['error'] ) ) {
			return new \WP_Error( 'response_error', $template_content['error'] );
		}

		if ( empty( $template_content['content'] ) ) {
			return new \WP_Error( 'template_data_error', 'An invalid data was returned.' );
		}

		$template_content['content'] = json_decode( $template_content['content'], true );

		return $template_content;
	}

	/**
	 * Initialize Raven template library module.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Add raven categories to the list.
		if ( defined( '\Elementor\Api::LIBRARY_OPTION_KEY' ) ) {
			// Sort before jet elements prepend its categories.
			add_filter( 'option_' . \Elementor\Api::LIBRARY_OPTION_KEY, [ $this, 'add_categories' ], 5 );
		}

		// Register Raven source.
		Elementor::instance()->templates_manager->register_source( __NAMESPACE__ . '\Source_Raven' );

		// Register proper AJAX actions for Raven templates.
		add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ), 20 );
	}

	/**
	 * Override registered Elementor native actions.
	 *
	 * @since 1.0.0
	 *
	 * @param array $ajax AJAX manager.
	 */
	public function register_ajax_actions( $ajax ) {
		// phpcs:disable
		if ( ! isset( $_REQUEST['actions'] ) ) {
			return;
		}

		$actions = json_decode( stripslashes( $_REQUEST['actions'] ), true );

		$data = false;

		foreach ( $actions as $action_data ) {
			if ( ! isset( $action_data['get_template_data'] ) ) {
				$data = $action_data;
			}
		}

		if ( ! $data ) {
			return;
		}

		if ( ! isset( $data['data'] ) ) {
			return;
		}

		$data = $data['data'];

		if ( empty( $data['template_id'] ) ) {
			return;
		}

		if ( false === strpos( $data['template_id'], 'raven_' ) ) {
			return;
		}

		// Once found out that current request is for Raven then replace the native action.
		$ajax->register_ajax_action( 'get_template_data', array( $this, 'get_template_data' ) );
		// phpcs:enable
	}

	/**
	 * Get template data.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Request arguments.
	 *
	 * @return array Template data.
	 */
	public static function get_template_data( $args ) {
		$source = Elementor::instance()->templates_manager->get_source( 'raven' );

		$args['template_id'] = intval( str_replace( 'raven_', '', $args['template_id'] ) );

		$data = $source->get_data( $args );

		return $data;
	}

	/**
	 * Add new categories to list.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Template library data including categories and templates.
	 *
	 * @return array $data Modified library data.
	 */
	public function add_categories( $data ) {
		$raven_categories = [ 'maintenance' ];

		$data['types_data']['block']['categories'] = array_merge( $raven_categories, $data['types_data']['block']['categories'] );

		sort( $data['types_data']['block']['categories'] );

		return $data;
	}
}
