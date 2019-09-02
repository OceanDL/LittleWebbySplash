<?php

/**
 * Plugins utilities.
 */

defined( 'ABSPATH' ) || die();

/**
 * Checks if Jupiter theme.
 *
 * @since 1.0.0
 *
 * @return boolean
 */
function jupiter_donut_is_jupiter() {
	$current_theme = jupiter_donut()->get_current_theme();

	if ( in_array( $current_theme->template, [ 'jupiter', 'jupiter2' ] ) ) {
		return true;
	}

	return false;
}

/**
 * Checks if Jupiter X theme.
 *
 * @since 1.0.0
 *
 * @return boolean
 */
function jupiter_donut_is_jupiterx() {
	$current_theme = jupiter_donut()->get_current_theme();

	if ( $current_theme->template === 'jupiterx' ) {
		return true;
	}

	return false;
}

/**
 * Get an option from Jupiter's Theme Options or Jupiter X's Settings.
 *
 * @since 1.0.0
 *
 * @return string The value or default value.
 */
function jupiter_donut_get_option( $option ) {

	// Defaults
	$defaults = [
		'skin_color'                    => '#f97352',
		'grid_width'                    => 1140,
		'content_width'                 => 73,
		'news_slug'                     => 'news-posts',
		'portfolio_slug'                => 'portfolio-posts',
		'portfolio_cat_slug'            => 'portfolio_category',
		'google_maps_api_key'           => get_option( 'jupiterx_donut_google_maps_api_key' ),
		'twitter_consumer_key'          => get_option( 'jupiterx_donut_twitter_consumer_key' ),
		'twitter_consumer_secret'       => get_option( 'jupiterx_donut_twitter_consumer_secret' ),
		'twitter_access_token'          => get_option( 'jupiterx_donut_twitter_access_token' ),
		'twitter_access_token_secret'   => get_option( 'jupiterx_donut_twitter_access_token_secret' ),
		'mailchimp_list_id'             => get_option( 'jupiterx_donut_mailchimp_list_id' ),
		'mailchimp_api_key'             => get_option( 'jupiterx_donut_mailchimp_api_key' ),
		'image_resize_quality'          => 100,
		'blog_single_comments'          => 'true',
		'search_page_layout'            => 'right',
		'archive_page_layout'           => 'right',
		'archive_portfolio_layout'      => 'right',
		'global_lazyload'               => 'true',
		'smoothscroll'                  => 'false',
		'body_font_size'                => 16,
		'theme_header_style'            => '1',
		'logo'                          => '',
		'Portfolio_single_image_height' => '500',
		'single_portfolio_social'       => 'true',
		'responsive_images'             => 'true',
		'retina_images'                 => 'true',
		'minify-css'                    => 'true',
		'move-shortcode-css-footer'     => 'true',
	];

	if ( ! defined( 'THEME_OPTIONS' ) ) {
		return $defaults[ $option ];
	}

	$theme_options = get_option( THEME_OPTIONS );

	return ! empty( $theme_options[ $option ] ) ? $theme_options[ $option ] : $defaults[ $option ];
}
