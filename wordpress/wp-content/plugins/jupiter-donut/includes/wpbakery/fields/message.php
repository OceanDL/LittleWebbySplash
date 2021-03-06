<?php

defined( 'ABSPATH' ) || die();

/**
 * Add Message Option to Visual Composer Params
 *
 * @author      Artbees Team
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.7
 * @package     artbees
 */

vc_add_shortcode_param( 'message', 'mk_message_param_field' );

function mk_message_param_field( $settings, $value ) {
	$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';

		return '<span class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" name="' . $param_name . '"></span>';
}
