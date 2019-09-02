<?php
/**
 * Booking forms manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Booking_Forms' ) ) {

	/**
	 * Define Jet_Engine_Booking_Forms class
	 */
	class Jet_Engine_Booking_Forms {

		public  $post_type         = 'jet-engine-booking';
		private $builder_instances = array();
		private $forms_for_options = null;

		public $handler;
		public $editor;

		/**
		 * Constructor for the class
		 */
		function __construct() {

			require_once jet_engine()->plugin_path( 'includes/forms/booking/handler.php' );
			require_once jet_engine()->plugin_path( 'includes/forms/booking/editor.php' );

			$this->editor  = new Jet_Engine_Booking_Forms_Editor( $this );
			$this->handler = new Jet_Engine_Booking_Forms_Handler( $this );

			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );

		}

		public function get_forms_for_options() {

			if ( null !== $this->forms_for_options ) {
				return $this->forms_for_options;
			}

			$forms = get_posts( array(
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'post_type'      => $this->slug(),
			) );

			$this->forms_for_options = wp_list_pluck( $forms, 'post_title', 'ID' );

			return $this->forms_for_options;

		}

		/**
		 * Return builder for passed form ID
		 * @return [type] [description]
		 */
		public function get_form_builder( $form_id, $form_data = false, $args = array() ) {

			if ( ! class_exists( 'Jet_Engine_Booking_Forms_Builder' ) ) {
				require_once jet_engine()->plugin_path( 'includes/forms/booking/builder.php' );
			}

			if ( ! isset( $this->builder_instances[ $form_id ] ) ) {
				$this->builder_instances[ $form_id ] = new Jet_Engine_Booking_Forms_Builder(
					$form_id,
					$form_data,
					$args
				);
			}

			return $this->builder_instances[ $form_id ];

		}

		/**
		 * Returns form messages
		 *
		 * @param  [type] $form_id [description]
		 * @return [type]          [description]
		 */
		public function get_messages_builder( $form_id ) {

			if ( ! class_exists( 'Jet_Engine_Booking_Forms_Messages' ) ) {
				require_once jet_engine()->plugin_path( 'includes/forms/booking/messages.php' );
			}

			return new Jet_Engine_Booking_Forms_Messages( $form_id );

		}

		/**
		 * Retuirns all available notification types
		 *
		 * @return [type] [description]
		 */
		public function get_notification_types() {
			return apply_filters( 'jet-engine/forms/booking/notification-types', array(
				'email'         => __( 'Send Email', 'jet-engine' ),
				'insert_post'   => __( 'Insert Post', 'jet-engine' ),
				'register_user' => __( 'Register New User', 'jet-engine' ),
				'hook'          => __( 'Call a Hook', 'jet-engine' ),
				'webhook'       => __( 'Call a Webhook', 'jet-engine' ),
			) );
		}

		/**
		 * Returna available input types
		 *
		 * @return array
		 */
		public function get_input_types() {
			return apply_filters( 'jet-engine/forms/booking/input-types', array(
				'text'     => __( 'Text', 'jet-engine' ),
				'email'    => __( 'Email', 'jet-engine' ),
				'url'      => __( 'URL', 'jet-engine' ),
				'tel'      => __( 'Tel', 'jet-engine' ),
				'password' => __( 'Password', 'jet-engine' ),
			) );
		}

		/**
		 * Returns all messages types
		 *
		 * @return [type] [description]
		 */
		public function get_message_types() {

			return apply_filters( 'jet-engine/forms/booking/message-types', array(
				'success' => array(
					'label' => __( 'Form successfully submitted.', 'jet-engine' ),
					'default' => __( 'Form successfully submitted.', 'jet-engine' ),
				),
				'failed' => array(
					'label' => __( 'Submit failed.', 'jet-engine' ),
					'default' => __( 'There was an error trying to submit form. Please try again later.', 'jet-engine' ),
				),
				'validation_failed' => array(
					'label' => __( 'Validation error', 'jet-engine' ),
					'default' => __( 'One or more fields have an error. Please check and try again.', 'jet-engine' ),
				),
				'invalid_email' => array(
					'label' => __( 'Entered an invalid email', 'jet-engine' ),
					'default' => __( 'The e-mail address entered is invalid.', 'jet-engine' ),
				),
				'empty_field' => array(
					'label' => __( 'Required field is empty', 'jet-engine' ),
					'default' => __( 'The field is required.', 'jet-engine' ),
				),
				'password_mismatch' => array(
					'label' => __( 'Register User specific: Passwords mismatch', 'jet-engine' ),
					'default' => __( 'Passwords don\'t match.', 'jet-engine' ),
				),
				'username_exists' => array(
					'label' => __( 'Register User specific: Username Exists', 'jet-engine' ),
					'default' => __( 'This username already taken.', 'jet-engine' ),
				),
				'email_exists' => array(
					'label' => __( 'Register User specific: Email exists', 'jet-engine' ),
					'default' => __( 'This e-mail already used.', 'jet-engine' ),
				),
				'sanitize_user' => array(
					'label' => __( 'Register User specific: Incorrect username', 'jet-engine' ),
					'default' => __( 'Username contains not allowed characters.', 'jet-engine' ),
				),
				'empty_username' => array(
					'label' => __( 'Register User specific: Empty username', 'jet-engine' ),
					'default' => __( 'Please set username.', 'jet-engine' ),
				),
				'empty_email' => array(
					'label' => __( 'Register User specific: Empty email', 'jet-engine' ),
					'default' => __( 'Please set user email.', 'jet-engine' ),
				),
				'empty_password' => array(
					'label' => __( 'Register User specific: Empty password', 'jet-engine' ),
					'default' => __( 'Please set user password.', 'jet-engine' ),
				),
			) );

		}

		/**
		 * Templates post type slug
		 *
		 * @return string
		 */
		public function slug() {
			return $this->post_type;
		}

		/**
		 * Returns field types
		 * @return [type] [description]
		 */
		public function get_field_types() {

			return apply_filters( 'jet-engine/forms/booking/field-types', array(
				'text'       => __( 'Text', 'jet-engine' ),
				'textarea'   => __( 'Textarea', 'jet-engine' ),
				'hidden'     => __( 'Hidden', 'jet-engine' ),
				'select'     => __( 'Select', 'jet-engine' ),
				'checkboxes' => __( 'Checkboxes', 'jet-engine' ),
				'radio'      => __( 'Radio', 'jet-engine' ),
				'number'     => __( 'Number', 'jet-engine' ),
				'date'       => __( 'Date', 'jet-engine' ),
				'time'       => __( 'Time', 'jet-engine' ),
				'calculated' => __( 'Calculated', 'jet-engine' ),
			) );

		}

		/**
		 * Register plugin widgets
		 *
		 * @param  [type] $widgets_manager [description]
		 * @return [type]                  [description]
		 */
		public function register_widgets( $widgets_manager ) {

			$base  = jet_engine()->plugin_path( 'includes/forms/booking/widgets/' );

			foreach ( glob( $base . '*.php' ) as $file ) {
				$slug = basename( $file, '.php' );
				$this->register_widget( $file, $widgets_manager );
			}

		}

		/**
		 * Register new widget
		 *
		 * @return void
		 */
		public function register_widget( $file, $widgets_manager ) {

			$base  = basename( str_replace( '.php', '', $file ) );
			$class = ucwords( str_replace( '-', ' ', $base ) );
			$class = str_replace( ' ', '_', $class );
			$class = sprintf( 'Elementor\Jet_Engine_%s_Widget', $class );

			require_once $file;

			if ( class_exists( $class ) ) {
				$widgets_manager->register_widget_type( new $class );
			}

		}

	}

}
