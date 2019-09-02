<?php
/**
 * Loads Jupiter fragments.
 *
 * @package JupiterX\Framework\Render
 *
 * @since   1.0.0
 */

// Filter.
jupiterx_add_smart_action( 'template_redirect', 'jupiterx_load_global_fragments', 1 );
/**
 * Load global fragments and dynamic views.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_load_global_fragments() {
	jupiterx_load_fragment_file( 'breadcrumb' );
	jupiterx_load_fragment_file( 'footer' );
	jupiterx_load_fragment_file( 'header' );
	jupiterx_load_fragment_file( 'menu' );
	jupiterx_load_fragment_file( 'post-shortcodes' );
	jupiterx_load_fragment_file( 'post' );
	jupiterx_load_fragment_file( 'post-single' );
	jupiterx_load_fragment_file( 'portfolio-single' );
	jupiterx_load_fragment_file( 'search' );
	jupiterx_load_fragment_file( 'title-bar' );
	jupiterx_load_fragment_file( 'widget-area' );
	jupiterx_load_fragment_file( 'embed' );
	jupiterx_load_fragment_file( 'tracking-codes' );
	jupiterx_load_fragment_file( 'deprecated' );

	if ( class_exists( 'woocommerce' ) ) {
		jupiterx_load_fragment_file( 'woocommerce' );
		jupiterx_load_fragment_file( 'product-list' );
		jupiterx_load_fragment_file( 'product-page' );
		jupiterx_load_fragment_file( 'cart' );
		jupiterx_load_fragment_file( 'checkout' );
		jupiterx_load_fragment_file( 'order' );
	}

	jupiterx_load_fragment_file( 'customizer/layout' );
}

// Filter.
jupiterx_add_smart_action( 'after_setup_theme', 'jupiterx_load_ajax_fragments', 1 );
/**
 * Load global fragments and dynamic views for AJAX.
 *
 * @since 1.4.0
 *
 * @return void
 */
function jupiterx_load_ajax_fragments() {
	if ( ! defined( 'DOING_AJAX' ) ) {
		return;
	}

	if ( class_exists( 'woocommerce' ) ) {
		jupiterx_load_fragment_file( 'woocommerce' );
		jupiterx_load_fragment_file( 'product-list' );
		jupiterx_load_fragment_file( 'product-page' );
		jupiterx_load_fragment_file( 'cart' );
		jupiterx_load_fragment_file( 'checkout' );
		jupiterx_load_fragment_file( 'order' );
	}
}

// Filter.
jupiterx_add_smart_action( 'comments_template', 'jupiterx_load_comments_fragment' );
/**
 * Load comments fragments.
 *
 * The comments fragments only loads if comments are active to prevent unnecessary memory usage.
 *
 * @since 1.0.0
 *
 * @param string $template The template filename.
 *
 * @return string The template filename.
 */
function jupiterx_load_comments_fragment( $template ) {

	if ( empty( $template ) ) {
		return;
	}

	jupiterx_load_fragment_file( 'comments' );

	return $template;
}

jupiterx_add_smart_action( 'elementor/widgets/widgets_registered', 'jupiterx_load_widget_fragment' );
jupiterx_add_smart_action( 'dynamic_sidebar_before', 'jupiterx_load_widget_fragment', -1 );
/**
 * Load widget fragments.
 *
 * The widget fragments only loads if a sidebar is active or Elementor's pages
 * to prevent unnecessary memory usage.
 *
 * @since 1.0.0
 *
 * @return bool True on success, false on failure.
 */
function jupiterx_load_widget_fragment() {
	return jupiterx_load_fragment_file( 'widget' );
}

jupiterx_add_smart_action( 'pre_get_posts', 'jupiterx_modify_search_page_query' );
/**
 * Modify search page query.
 *
 * @since 1.0.0
 *
 * @param object $query The query object.
 */
function jupiterx_modify_search_page_query( $query ) {
	if ( is_admin() || ! $query->is_search() || ! $query->is_main_query() ) {
		return;
	}

	global $wp_post_types;

	$search_post_types = get_theme_mod( 'jupiterx_search_post_types', [ 'post', 'page', 'portfolio', 'product' ] );

	$post_types = [ 'post', 'portfolio', 'page' ];

	// Set post type exclude from search when it is not existing in theme mod.
	foreach ( $post_types as $post_type ) {
		if ( post_type_exists( $post_type ) ) {
			$wp_post_types[ $post_type ]->exclude_from_search = ! in_array( $post_type, $search_post_types, true );
		}
	}

	// Always exclude WooCommerce products from search as we have other section to show results.
	if ( class_exists( 'woocommerce' ) && post_type_exists( 'product' ) ) {
		$wp_post_types['product']->exclude_from_search = true;

		if ( jupiterx_is_location_conditions_set( 'archive', 'search' ) ) {
			$wp_post_types['product']->exclude_from_search = false;
		}
	}

	$query->set( 'posts_per_page', get_theme_mod( 'jupiterx_search_posts_per_page', 5 ) );
}

jupiterx_add_smart_action( 'pre_get_search_form', 'jupiterx_load_search_form_fragment' );
/**
 * Load search form fragments.
 *
 * The search form fragments only loads if search is active to prevent unnecessary memory usage.
 *
 * @since 1.0.0
 *
 * @return bool True on success, false on failure.
 */
function jupiterx_load_search_form_fragment() {
	return jupiterx_load_fragment_file( 'searchform' );
}

jupiterx_add_smart_action( 'template_redirect', 'jupiterx_404_page_redirect' );
/**
 * Redirect 404 pages to specific page template.
 *
 * @since 1.0.0
 *
 * @return void
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
function jupiterx_404_page_redirect() {
	// The page where redirect ended up.
	$page_template = intval( get_theme_mod( 'jupiterx_404_template' ) );

	// Legitimate non existing page, page template is not empty and the page status must be published.
	if ( is_404() && ! empty( $page_template ) && 'publish' === get_post_status( $page_template ) ) {
		wp_safe_redirect( get_permalink( $page_template ), 301 );
	} elseif ( ! empty( $page_template ) && get_the_ID() === $page_template ) {
		status_header( 404 );
	}
}

jupiterx_add_smart_action( 'template_redirect', 'jupiterx_maintenance_page_redirect' );
/**
 * Redirect maintenance pages to specific page template.
 *
 * @since 1.0.0
 *
 * @return void
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
function jupiterx_maintenance_page_redirect() {
	// Current viewing page ID.
	$post_id = get_queried_object_id();

	// Is maintenance enabled?
	$is_enabled = get_theme_mod( 'jupiterx_maintenance', false );

	// The page where redirect ended up.
	$page_template = intval( get_theme_mod( 'jupiterx_maintenance_template' ) );

	// Disable when logged in or viewing the current template.
	if ( is_user_logged_in() || $page_template === $post_id ) {
		return;
	}

	// Maintenance is enabled, page template is not empty and the page status is published.
	if ( $is_enabled && ! empty( $page_template ) && 'publish' === get_post_status( $page_template ) ) {
		wp_safe_redirect( get_permalink( $page_template ) );
		exit;
	}
}
