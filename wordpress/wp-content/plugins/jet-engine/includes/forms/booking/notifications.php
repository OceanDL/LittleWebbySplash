<?php
/**
 * Form notifications class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Booking_Forms_Notifications' ) ) {

	/**
	 * Define Jet_Engine_Booking_Forms_Notifications class
	 */
	class Jet_Engine_Booking_Forms_Notifications {

		private $form            = null;
		private $data            = null;
		private $notifications   = array();
		private $manager         = null;
		private $log             = array();
		private $log_status      = false;
		private $specific_status = false;

		private $headers;
		private $email_data;

		/**
		 * Constructor for the class
		 */
		function __construct( $form = null, $data = array(), $manager ) {

			$this->form          = $form;
			$this->data          = $data;
			$this->manager       = $manager;
			$this->notifications = $this->manager->editor->get_notifications( $this->form );

			$hooks_priority = apply_filters( 'jet-engine/forms/booking/notification/hooks-priority', array(
				'register_user' => 0,
				'webhook'       => 1,
				'hook'          => 2,
				'insert_post'   => 3,
				'email'         => 4,
			) );

			add_action(
				'jet-engine/forms/booking/notification/register_user',
				array( $this, 'register_user' ),
				$hooks_priority['register_user']
			);

			add_action(
				'jet-engine/forms/booking/notification/webhook',
				array( $this, 'webhook' ),
				$hooks_priority['webhook']
			);

			add_action(
				'jet-engine/forms/booking/notification/hook',
				array( $this, 'hook' ),
				$hooks_priority['hook']
			);

			add_action(
				'jet-engine/forms/booking/notification/insert_post',
				array( $this, 'insert_post' ),
				$hooks_priority['insert_post']
			);

			add_action(
				'jet-engine/forms/booking/notification/email',
				array( $this, 'email' ),
				$hooks_priority['email']
			);

			add_action( 'jet-engine/forms/booking/email/send-before', array( $this, 'send_before' ) );
			add_action( 'jet-engine/forms/booking/email/send-after', array( $this, 'send_after' ) );

		}

		/**
		 * Send form notifications
		 *
		 * @return [type] [description]
		 */
		public function send() {

			if ( empty( $this->notifications ) ) {
				return;
			}

			foreach ( $this->notifications as $notification ) {

				/**
				 * Process single notification
				 */
				do_action( 'jet-engine/forms/booking/notification/' . $notification['type'], $notification, $this );

			}

			if ( empty( $this->log ) ) {
				return false;
			} else {
				return count( $this->log ) === count( array_filter( $this->log ) );
			}

		}

		/**
		 * Call a webhook notification
		 *
		 * @param  [type] $notification [description]
		 * @return [type]               [description]
		 */
		public function webhook( $notification ) {

			$webhook_url = ! empty( $notification['webhook_url'] ) ? esc_url( $notification['webhook_url'] ) : false;

			if ( ! $webhook_url ) {
				return;
			}

			$args = array(
				'body' => $this->data,
			);

			/**
			 * Filter webhook argumetns
			 */
			$args = apply_filters(
				'jet-engine/forms/booking/notification/webhook/request-args', $args, $notification, $this
			);

			$response = wp_remote_post( $webhook_url, $args );

			/**
			 * Firtes whe webhook response recieved
			 */
			do_action( 'jet-engine/forms/booking/notification/webhook/response', $response, $notification, $this );

		}

		/**
		 * Insert post notification
		 *
		 * @param  [type] $notification [description]
		 * @return [type]               [description]
		 */
		public function insert_post( $notification ) {

			$post_type = ! empty( $notification['post_type'] ) ? $notification['post_type'] : false;

			if ( ! $post_type || ! post_type_exists( $post_type ) ) {
				return;
			}

			$fields_map = ! empty( $notification['fields_map'] ) ? $notification['fields_map'] : array();
			$meta_input = array();

			foreach ( $this->data as $key => $value ) {
				$key                = ! empty( $fields_map[ $key ] ) ? esc_attr( $fields_map[ $key ] ) : $key;
				$meta_input[ $key ] = $value;
			}

			$post_status = ! empty( $notification['post_status'] ) ? $notification['post_status'] : 'publish';

			$post_id = wp_insert_post( array(
				'post_type'   => $post_type,
				'post_status' => $post_status,
				'meta_input'  => $meta_input,
			) );

			if ( $post_id ) {

				$post_type_obj = get_post_type_object( $post_type );
				$title         = $post_type_obj->labels->singular_name . ' #' . $post_id;

				wp_update_post( array(
					'ID'         => $post_id,
					'post_title' => $title,
				) );

				$this->log[] = true;

			} else {
				$this->log[] = false;
			}

		}

		/**
		 * Regsiter new user notification callback
		 *
		 * @return [type] [description]
		 */
		public function register_user( $notification ) {

			if ( is_user_logged_in() ) {
				$this->log[] = true;

				if ( $notification['add_user_id'] ) {
					$this->data['user_id'] = get_current_user_id();
				}

				return;
			}

			$fields_map = ! empty( $notification['fields_map'] ) ? $notification['fields_map'] : array();

			// Prepare fields
			$username = false;
			$email    = false;
			$password = false;
			$fname    = false;
			$lname    = false;

			// If fields map for login, password or email is not set - abort but allow submit form (its not user fault)
			if ( empty( $fields_map['login'] ) || empty( $fields_map['email'] ) || empty( $fields_map['password'] ) ) {
				$this->log[] = true;
				return;
			}

			/**
			 * Validate username
			 */
			$raw_username = ! empty( $this->data[ $fields_map['login'] ] ) ? $this->data[ $fields_map['login'] ] : false;

			if ( ! $raw_username ) {
				return $this->set_specific_status( 'empty_username' );
			}

			$username = sanitize_user( $raw_username );

			if ( $username !== $raw_username ) {
				return $this->set_specific_status( 'sanitize_user' );
			}

			if ( username_exists( $username ) ) {
				return $this->set_specific_status( 'username_exists' );
			}
			// username - ok

			/**
			 * Validate email
			 */
			$raw_email = ! empty( $this->data[ $fields_map['email'] ] ) ? $this->data[ $fields_map['email'] ] : false;

			if ( ! $raw_email ) {
				return $this->set_specific_status( 'empty_email' );
			}

			$email = sanitize_email( $raw_email );

			if ( $email !== $raw_email ) {
				return $this->set_specific_status( 'empty_email' );
			}

			if ( email_exists( $email ) ) {
				return $this->set_specific_status( 'email_exists' );
			}
			// email - ok

			/**
			 * Validate password
			 */
			$password = ! empty( $this->data[ $fields_map['password'] ] ) ? $this->data[ $fields_map['password'] ] : false;

			if ( ! $password ) {
				return $this->set_specific_status( 'empty_password' );
			}

			if ( ! empty( $fields_map['confirm_password'] ) ) {
				$confirm_password = ! empty( $this->data[ $fields_map['confirm_password'] ] ) ? $this->data[ $fields_map['confirm_password'] ] : false;

				if ( $confirm_password !== $password ) {
					return $this->set_specific_status( 'password_mismatch' );
				}

			}
			// password - ok

			if ( ! empty( $fields_map['first_name'] ) ) {
				$fname = ! empty( $this->data[ $fields_map['first_name'] ] ) ? $this->data[ $fields_map['first_name'] ] : false;
			}

			if ( ! empty( $fields_map['last_name'] ) ) {
				$lname = ! empty( $this->data[ $fields_map['last_name'] ] ) ? $this->data[ $fields_map['last_name'] ] : false;
			}

			$user_id = wp_insert_user( array(
				'user_pass'  => $password,
				'user_login' => $username,
				'user_email' => $email,
				'first_name' => $fname,
				'last_name'  => $lname,
			) );

			if ( ! is_wp_error( $user_id ) ) {

				$this->log[] = true;

				if ( ! empty( $notification['log_in'] ) ) {
					wp_signon( array(
						'user_login'    => $username,
						'user_password' => $password,
					) );
				}

				if ( $notification['add_user_id'] ) {
					$this->data['user_id'] = $user_id;
				}

			} else {
				$this->log[] = false;
			}

		}

		/**
		 * Set specific form status and return error
		 *
		 * @param [type]  $status [description]
		 * @param boolean $log    [description]
		 */
		public function set_specific_status( $status = null, $log = false ) {
			$this->specific_status = $status;
			$this->log[] = false;
		}

		/**
		 * Returns specific status
		 *
		 * @return [type] [description]
		 */
		public function get_specific_status() {
			return $this->specific_status;
		}

		/**
		 * Insert post notification
		 *
		 * @param  [type] $notification [description]
		 * @return [type]               [description]
		 */
		public function email( $notification ) {

			$mail_to     = ! empty( $notification['mail_to'] ) ? $notification['mail_to'] : 'admin';
			$reply_to    = ! empty( $notification['reply_to'] ) ? $notification['reply_to'] : 'form';
			$email       = false;
			$reply_email = false;

			switch ( $mail_to ) {
				case 'admin':
					$email = get_option( 'admin_email' );
					break;

				case 'form':
					$field = ! empty( $notification['from_field'] ) ? $notification['from_field'] : '';

					if ( $field && ! empty( $this->data[ $field ] ) ) {
						$email = $this->data[ $field ];
					}

					break;

				case 'custom':
					$email = ! empty( $notification['custom_email'] ) ? $notification['custom_email'] : '';
					break;
			}

			switch ( $reply_to ) {

				case 'form':
					$field = ! empty( $notification['reply_from_field'] ) ? $notification['reply_from_field'] : '';

					if ( $field && ! empty( $this->data[ $field ] ) ) {
						$reply_email = $this->data[ $field ];
					}

					break;

				case 'custom':
					$reply_email = ! empty( $notification['reply_to_email'] ) ? $notification['reply_to_email'] : '';
					break;
			}

			if ( ! $email || ! is_email( $email ) ) {
				return;
			}

			$this->email_data = ! empty( $notification['email'] ) ? $notification['email'] : array();

			$this->email_data['reply_email'] = $reply_email;

			$subject = ! empty( $this->email_data['subject'] ) ? $this->email_data['subject'] : sprintf(
				__( 'Form on %s Submitted', 'jet-engine' ),
				home_url( '' )
			);

			$message = ! empty( $this->email_data['content'] ) ? $this->email_data['content'] : '';

			$this->send_mail( $email, $subject, $message );

		}

		/**
		 * Send the email
		 * @param  string  $to      The To address to send to.
		 * @param  string  $subject The subject line of the email to send.
		 * @param  string  $message The body of the email to send.
		 */
		public function send_mail( $to, $subject, $message ) {

			/**
			 * Hooks before the email is sent
			 */
			do_action( 'jet-engine/forms/booking/email/send-before', $this );

			$subject    = $this->parse_macros( $subject );
			$message    = $this->parse_macros( $message );
			$message    = wpautop( $message );
			$message    = make_clickable( $message );
			$message    = str_replace( '&#038;', '&amp;', $message );
			$sent       = wp_mail( $to, $subject, $message, $this->get_headers() );
			$log_errors = apply_filters( 'jet-engine/forms/booking/email/log-errors', true, $to, $subject, $message );

			// Test
			$log_errors = false;

			if( ! $sent && true === $log_errors ) {

				if ( is_array( $to ) ) {
					$to = implode( ',', $to );
				}

				$log_message = sprintf(
					__( "Email from JetEngine Booking Form failed to send.\nSend time: %s\nTo: %s\nSubject: %s\nContent: %s\n\n", 'jet-engine' ),
					date_i18n( 'F j Y H:i:s', current_time( 'timestamp' ) ),
					$to,
					$subject,
					$message
				);

				error_log( $log_message );

				$this->log[] = false;

			} else {
				$this->log[] = true;
			}

			/**
			 * Hooks after the email is sent
			 *
			 * @since 2.1
			 */
			do_action( 'jet-engine/forms/booking/email/send-after', $this );

			return $sent;

		}

		/**
		 * Get the email headers
		 *
		 * @since 2.1
		 */
		public function get_headers() {

			$this->headers  = "From: {$this->get_from_name()} <{$this->get_from_address()}>\r\n";
			$this->headers .= "Reply-To: {$this->get_reply_to()}\r\n";
			$this->headers .= "Content-Type: {$this->get_content_type()}; charset=utf-8\r\n";

			return apply_filters( 'jet-engine/forms/booking/email/headers', $this->headers, $this );
		}

		/**
		 * Parse macros in content
		 *
		 * @param  [type] $content [description]
		 * @return [type]          [description]
		 */
		public function parse_macros( $content ) {

			return preg_replace_callback( '/%(.*?)%/', function( $match ) {
				if ( isset( $this->data[ $match[1] ] ) ) {
					return $this->data[ $match[1] ];
				} else {
					return $match[0];
				}
			}, $content );

		}

		/**
		 * Insert post notification
		 *
		 * @param  [type] $notification [description]
		 * @return [type]               [description]
		 */
		public function hook( $notification ) {

			$hook = ! empty( $notification['hook_name'] ) ? $notification['hook_name'] : 'send';

			/**
			 * Fires custom hook
			 *
			 * @var string
			 */
			do_action( 'jet-engine-booking/' . $hook, $this->data, $this->form );

			$this->log[] = true;

		}

		/**
		 * Add filters / actions before the email is sent
		 *
		 * @since 2.1
		 */
		public function send_before() {
			add_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
			add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
			add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );
		}

		/**
		 * Remove filters / actions after the email is sent
		 *
		 * @since 2.1
		 */
		public function send_after() {
			remove_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
			remove_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
			remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

			// Reset heading to an empty string
			$this->heading = '';
		}

		/**
		 * Get the email from name
		 */
		public function get_from_name() {
			$name = ! empty( $this->email_data['from_name'] ) ? $this->email_data['from_name'] : get_bloginfo( 'name' );
			return apply_filters( 'jet-engine/forms/booking/email/from-name', wp_specialchars_decode( $name ), $this );
		}

		/**
		 * Returns e-mail address to set into Reply-to email header
		 *
		 * @return [type] [description]
		 */
		public function get_reply_to() {

			$address = ! empty( $this->email_data['reply_email'] ) ? $this->email_data['reply_email'] : '';

			if ( empty( $address ) || ! is_email( $address ) ) {
				$address = $this->get_from_address();
			}

			return apply_filters( 'jet-engine/forms/booking/email/reply-to', $address, $this );

		}

		/**
		 * Get the email from address
		 */
		public function get_from_address() {

			$address = ! empty( $this->email_data['from_address'] ) ? $this->email_data['from_address'] : '';

			if( empty( $address ) || ! is_email( $address ) ) {
				$address = get_option( 'admin_email' );
			}

			return apply_filters( 'jet-engine/forms/booking/email/from-address', $address, $this );
		}

		/**
		 * Get the email content type
		 */
		public function get_content_type() {
			return apply_filters( 'jet-engine/forms/booking/email/content-type', 'text/html', $this );
		}

	}

}
