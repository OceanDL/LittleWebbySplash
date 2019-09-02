<?php
/**
 * Functions for WooCommerce.
 *
 * @package JupiterX\Framework\API\WooCommerce
 *
 * @since 1.0.0
 */

add_filter( 'woocommerce_template_path', 'jupiterx_wc_modify_template_path' );
/**
 * Override WooCommerce default template path.
 *
 * @param string $path The template path.
 *
 * @since 1.0.0
 */
function jupiterx_wc_modify_template_path( $path ) {

	if ( is_dir( JUPITERX_TEMPLATES_PATH . '/woocommerce' ) ) {
		$path = 'lib/templates/woocommerce/';
	}

	return $path;
}

add_action( 'jupiterx_init', 'jupiterx_wc_add_theme_support' );
/**
 * Add WooCommerce theme support.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_wc_add_theme_support() {
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'woocommerce' );
}

add_filter( 'woocommerce_add_to_cart_fragments', 'jupiterx_wc_cart_count_fragments', 10, 1 );
/**
 * Get refreshed cart count.
 *
 * @param array $fragments The fragments.
 *
 * @since 1.0.0
 */
function jupiterx_wc_cart_count_fragments( $fragments ) {
	$count = WC()->cart->cart_contents_count;

	if ( empty( $count ) ) {
		$count = ' 0';
	}

	$markup = jupiterx_open_markup( 'jupiterx_navbar_cart_count', 'span', 'class=jupiterx-navbar-cart-count' );

		$markup .= jupiterx_output( 'jupiterx_navbar_brand_count_text', $count );

	$markup .= jupiterx_close_markup( 'jupiterx_navbar_cart_count', 'span' );

	$fragments['.jupiterx-navbar-cart-count'] = $markup;

	return $fragments;
}

add_action( 'woocommerce_product_query', 'jupiterx_wc_loop_shop_per_page' );
/**
 * Loop query post per page.
 *
 * @since 1.0.0
 *
 * @param object $query Query object.
 */
function jupiterx_wc_loop_shop_per_page( $query ) {
	if ( $query->is_main_query() ) {
		// Multiply rows and columns.
		$grid_columns = intval( get_theme_mod( 'jupiterx_product_list_grid_columns', 3 ) );
		$grid_rows    = intval( get_theme_mod( 'jupiterx_product_list_grid_rows', 3 ) );
		$grid_total   = $grid_columns * $grid_rows;

		// Set posts per page.
		$query->set( 'posts_per_page', $grid_total );
	}
}

add_action( 'woocommerce_proceed_to_checkout', 'jupiterx_wc_continue_shopping_button', 5 );
add_action( 'woocommerce_review_order_after_submit', 'jupiterx_wc_continue_shopping_button' );
/**
 * Adds continue shopping button to cart and order page.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_wc_continue_shopping_button() {

	$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );

	jupiterx_open_markup_e(
		'jupiterx_continue_shopping_button',
		'a',
		[
			'class' => 'button jupiterx-continue-shopping',
			'href'  => $shop_page_url,
		]
	);

		esc_html_e( 'Continue Shopping', 'jupiterx' );

	jupiterx_close_markup_e( 'jupiterx_continue_shopping_button', 'a' );
}

add_action( 'pre_get_posts', 'jupiterx_modify_author_archive', 20 );
/**
 * Include portfolio to author archive.
 *
 * @since 1.0.0
 *
 * @param object $query Current query object.
 *
 * @return void
 */
function jupiterx_modify_author_archive( $query ) {
	if ( ! is_author() ) {
		return;
	}

	if ( $query->is_main_query() && $query->is_author() ) {
		$query->set( 'post_type', [ 'post', 'portfolio' ] );
	}

	remove_action( 'pre_get_posts', 'jupiterx_modify_author_archive', 20 );
}

/**
 * Enable or disable loop elements.
 *
 * @since 1.0.0
 */
function jupiterx_wc_loop_elements_enabled() {
	/**
	 * Key is the ID of the element from Customizer setting and its value is the element hook, function name and priority.
	 */
	$hooks = [
		'sale_badge'         => [ 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 15 ],
		'out_of_stock_badge' => [ 'woocommerce_before_shop_loop_item', 'jupiterx_wc_template_loop_out_of_stock', 15 ],
		'image'              => [ 'woocommerce_before_shop_loop_item', 'jupiterx_wc_loop_product_thumbnail', 20 ],
		'category'           => [ 'woocommerce_before_shop_loop_item', 'jupiterx_wc_template_loop_item_category' ],
		'name'               => [ 'woocommerce_before_shop_loop_item', 'jupiterx_wc_template_loop_product_title_group' ],
		'rating'             => [ 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_rating' ],
		'price'              => [ 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_price' ],
		'add_to_cart'        => [ 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 ],
	];

	$elements = get_theme_mod( 'jupiterx_product_list_elements', array_keys( $hooks ) );

	// Remove badges when image is hidden.
	if ( ! in_array( 'image', $elements, true ) ) {
		$elements = array_diff( $elements, [ 'sale_badge', 'out_of_stock_badge' ] );
	}

	$remove_elements = array_diff_key( $hooks, array_flip( $elements ) );

	// Remove actions from the hooks.
	foreach ( $remove_elements as $element ) {
		jupiterx_dynamic_remove_action( $element[0], $element[1], isset( $element[2] ) ? $element[2] : null );
	}
}
