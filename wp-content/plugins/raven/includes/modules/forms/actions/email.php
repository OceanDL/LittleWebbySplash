<?php
/**
 * Add form Email action.
 *
 * @package Raven
 * @since 1.0.0
 */

namespace Raven\Modules\Forms\Actions;

use Raven\Utils;

defined( 'ABSPATH' ) || die();

/**
 * Email Action.
 *
 * Initializing the emil action by extending action base.
 *
 * @since 1.0.0
 */
class Email extends Action_Base {

	/**
	 * Update controls.
	 *
	 * Add Email section.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {

		$widget->start_controls_section(
			'section_email',
			[
				'label' => __( 'Email', 'raven' ),
				'condition' => [
					'actions' => 'email',
				],
			]
		);

		$widget->add_control(
			'email_to',
			[
				'label' => __( 'To', 'raven' ),
				'type' => 'text',
				'default' => get_bloginfo( 'admin_email' ),
				'placeholder' => get_bloginfo( 'admin_email' ),
				'title' => __( 'Separate emails with commas', 'raven' ),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'email_subject',
			[
				'label' => __( 'Email Subject', 'raven' ),
				'type' => 'text',
				'default' => 'New message from "' . get_bloginfo( 'name' ) . '"',
				'placeholder' => 'New message from "' . get_bloginfo( 'name' ) . '"',
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'email_from',
			[
				'label' => __( 'From Email', 'raven' ),
				'type' => 'text',
				'default' => 'email@' . Utils::get_site_domain(),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'email_name',
			[
				'label' => __( 'From Name', 'raven' ),
				'type' => 'text',
				'default' => get_bloginfo( 'name' ),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'email_reply_to',
			[
				'label' => __( 'Reply-To', 'raven' ),
				'type' => 'text',
				'default' => 'email@' . Utils::get_site_domain(),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'email_cc',
			[
				'label' => __( 'Cc', 'raven' ),
				'type' => 'text',
				'title' => __( 'Separate emails with commas', 'raven' ),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'email_bcc',
			[
				'label' => __( 'Bcc', 'raven' ),
				'type' => 'text',
				'title' => __( 'Separate emails with commas', 'raven' ),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->end_controls_section();

	}

	/**
	 * Run action.
	 *
	 * Send email.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 */
	public static function run( $ajax_handler ) {
		$email_to       = $ajax_handler->form['settings']['email_to'];
		$email_subject  = $ajax_handler->form['settings']['email_subject'];
		$email_name     = $ajax_handler->form['settings']['email_name'];
		$email_from     = $ajax_handler->form['settings']['email_from'];
		$email_reply_to = $ajax_handler->form['settings']['email_reply_to'];
		$email_cc       = ! empty( $ajax_handler->form['settings']['email_cc'] ) ? explode( ',', $ajax_handler->form['settings']['email_cc'] ) : [];
		$email_bcc      = ! empty( $ajax_handler->form['settings']['email_bcc'] ) ? explode( ',', $ajax_handler->form['settings']['email_bcc'] ) : [];
		$body           = '';

		// Body.
		foreach ( $ajax_handler->form['settings']['fields'] as $field ) {
			$body .= $field['label'] . ': ' . $ajax_handler->record['fields'][ $field['_id'] ] . '<br>';
		}

		$body .= '<br> ---- <br><br>';
		/* translators: %s: Today date */
		$body .= sprintf( __( 'Date: %s', 'raven' ), date( 'F j, Y' ) ) . '<br>';
		/* translators: %s: Today time */
		$body .= sprintf( __( 'Time: %s', 'raven' ), date( 'h:i A' ) ) . '<br>';
		/* translators: %s: Referrer URL */
		$body .= sprintf( __( 'Page URL: %s', 'raven' ), $ajax_handler->record['referrer'] ) . '<br>';

		$headers[] = 'Content-Type: text/html';
		$headers[] = 'charset=UTF-8';
		$headers[] = 'From: ' . $email_name . ' < ' . $email_from . '>';

		if ( ! empty( $email_reply_to ) ) {
			$headers[] = 'Reply-To: ' . $email_name . '<' . $email_reply_to . '>';
		}

		if ( ! empty( $email_cc ) ) {
			foreach ( $email_cc as $email ) {
				$headers[] = 'Cc: ' . $email;
			}
		}

		if ( ! empty( $email_bcc ) ) {
			foreach ( $email_bcc as $email ) {
				$headers[] = 'Bcc: ' . $email;
			}
		}

		wp_mail( $email_to, $email_subject, $body, $headers );

		$ajax_handler->add_response( 'success', 'Email sent.' );
	}

}
