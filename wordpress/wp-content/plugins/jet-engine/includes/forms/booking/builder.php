<?php
/**
 * Form builder class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Booking_Forms_Builder' ) ) {

	/**
	 * Define Jet_Engine_Booking_Forms_Builder class
	 */
	class Jet_Engine_Booking_Forms_Builder {

		private $form_id       = null;
		private $post          = null;
		private $fields        = array();
		private $args          = array();
		private $settings      = array();
		private $attrs         = array();
		private $rows          = array();
		private $is_hidden_row = true;
		private $is_submit_row = false;
		private $rendered_rows = 0;

		/**
		 * Constructor for the class
		 */
		function __construct( $form_id = null, $fields = false, $args = array() ) {

			$this->form_id = $form_id;

			$this->setup_fields( $fields );

			$this->args = wp_parse_args( $args, array(
				'fields_layout' => 'column',
				'rows_divider'  => false,
				'required_mark' => '*',
				'submit_type'   => 'reload',
			) );

			if ( empty( $post ) ) {
				global $post;
			}

			$this->post = $post;

		}

		/**
		 * Setup fields prop
		 */
		public function setup_fields( $fields = false ) {

			$raw_fields = '';

			if ( $fields ) {
				$raw_fields = $fields;
			} else {
				$raw_fields = get_post_meta( $this->form_id, '_form_data', true );
				$raw_fields = json_decode( wp_unslash( $raw_fields ), true );
			}

			if ( empty( $raw_fields ) ) {
				return;
			}

			// Ensure fields sorted by rows
			usort( $raw_fields, function( $a, $b ) {

				if ( $a['y'] == $b['y'] ) {
					return 0;
				}
				return ( $a['y'] < $b['y'] ) ? -1 : 1;

			} );

			$this->fields = $raw_fields;

			$sorted = array();
			$y      = false;

			foreach ( $raw_fields as $field ) {

				if ( false === $y ) {
					$y = $field['y'];
				}

				if ( $field['y'] === $y ) {

					if ( empty( $sorted[ $y ] ) ) {
						$sorted[ $y ] = array();
					}

					$sorted[ $y ][] = $field;

				} else {

					usort( $sorted[ $y ], function( $a, $b ) {

						if ( $a['x'] == $b['x'] ) {
							return 0;
						}
						return ( $a['x'] < $b['x'] ) ? -1 : 1;

					} );

					$y = $field['y'];

					$sorted[ $y ][] = $field;

				}


			}

			// Ensure last row is sorted
			usort( $sorted[ $y ], function( $a, $b ) {

				if ( $a['x'] == $b['x'] ) {
					return 0;
				}
				return ( $a['x'] < $b['x'] ) ? -1 : 1;

			} );

			$this->rows = $sorted;

		}

		/**
		 * Get hidden value
		 *
		 * @return string
		 */
		public function get_hidden_val( $args = array() ) {

			if ( empty( $this->post ) ) {
				return null;
			}

			$from = isset( $args['hidden_value'] ) ? $args['hidden_value'] : 'post_id';

			switch ( $from ) {

				case 'post_title':
					return get_the_title( $this->post->ID );

				case 'post_meta':

					$key = ! empty( $args['hidden_value_field'] ) ? $args['hidden_value_field'] : '';

					if ( ! $key ) {
						return null;
					} else {
						return get_post_meta( $this->post->ID, $key, true );
					}

				default:

					if ( ! empty( $args['default'] ) ) {
						return $args['default'];
					} else {
						return $this->post->ID;
					}

			}

		}

		/**
		 * Get required attribute value
		 *
		 * @param  [type] $args [description]
		 * @return [type]       [description]
		 */
		public function get_required_val( $args ) {

			if ( ! empty( $args['required'] ) && 'required' === $args['required'] ) {
				return 'required';
			}

			return '';

		}

		/**
		 * Get calulation formula for calculated field
		 *
		 * @return [type] [description]
		 */
		public function get_calculated_data( $args ) {

			if ( empty( $args['calc_formula'] ) ) {
				return '';
			}

			$listen_fields = array();

			$formula = preg_replace_callback(
				'/%([a-zA-Z]+)::([a-zA-Z-_]+)%/',
				function( $matches ) use ( &$listen_fields ) {

					if ( 'field' === strtolower( $matches[1] ) ) {
						$listen_fields[] = $matches[2];
						return '%' . $matches[2] . '%';
					}

					if ( 'meta' === strtolower( $matches[1] ) ) {
						return get_post_meta( $this->post->ID, $matches[2], true );
					}

				},
				$args['calc_formula']
			);

			return array(
				'formula'       => $formula,
				'listen_fields' => $listen_fields,
			);

		}

		/**
		 * Add attribute
		 */
		public function add_attribute( $attr, $value = null ) {

			if ( empty( $value ) ) {
				return;
			}

			if ( ! isset( $this->attrs[ $attr ] ) ) {
				$this->attrs[ $attr ] = $value;
			} else {
				$this->attrs[ $attr ] .= ' ' . $value;
			}

		}

		/**
		 * Render current attributes string
		 *
		 * @return [type] [description]
		 */
		public function render_attributes_string() {

			foreach ( $this->attrs as $attr => $value ) {
				printf( ' %1$s="%2$s"', $attr, $value );
			}

			$this->attrs = array();

		}

		/**
		 * Render form field by passed arguments.
		 *
		 * @param  array  $args [description]
		 * @return [type]       [description]
		 */
		public function render_field( $args = array() ) {

			if ( empty( $args['type'] ) ) {
				return;
			}

			$defaults = array(
				'default'     => '',
				'name'        => '',
				'placeholder' => '',
				'required'    => false,
			);

			// Prepare defaults
			switch ( $args['type'] ) {

				case 'hidden':

					if ( empty( $args['default'] ) ) {
						$defaults['default'] = $this->get_hidden_val( $args );
					}

					if ( isset( $args['default'] ) && empty( $args['default'] ) ) {
						unset( $args['default'] );
					}

					break;

				case 'number':

					$defaults['min'] = '';
					$defaults['max'] = '';

					break;

				case 'text':

					$defaults['field_type'] = 'text';

					break;

				case 'calculated':

					$defaults['formula'] = '';

					break;

				case 'submit':

					$defaults['label']      = __( 'Submit', 'jet-engine' );
					$defaults['class_name'] = '';

					$this->is_submit_row = true;

					break;

				default:

					/**
					 * Render custom field
					 */
					do_action( 'jet-engine/forms/booking/render-field/' . $args['type'], $args, $this );

					break;

			}

			$snaitized_args = array();

			foreach ( $args as $key => $value ) {
				$snaitized_args[ $key ] = $value;
			}

			$args     = wp_parse_args( $snaitized_args, $defaults );
			$template = jet_engine()->get_template( 'forms/fields/' . $args['type'] . '.php' );

			// Ensure args
			switch ( $args['type'] ) {

				case 'select':
				case 'checkboxes':
				case 'radio':

					$args['field_options'] = $this->get_field_options( $args );

					break;
			}

			$label           = $this->get_field_label( $args );
			$desc            = $this->get_field_desc( $args );
			$layout          = $this->args['fields_layout'];
			$args['default'] = $this->maybe_adjust_value( $args );

			if ( 'column' === $layout ) {
				include jet_engine()->get_template( 'forms/common/field-column.php' );
			} else {
				include jet_engine()->get_template( 'forms/common/field-row.php' );
			}

			if ( 'hidden' !== $args['type'] ) {
				$this->is_hidden_row = false;
			}

		}

		/**
		 * Try to get values from request if passed
		 * @param  [type] $args [description]
		 * @return [type]       [description]
		 */
		public function maybe_adjust_value( $args ) {

			if ( 'hidden' === $args['type'] ) {
				return $args['default'];
			}

			$value       = $args['default'];
			$request_val = ! empty( $_REQUEST['values'] ) ? $_REQUEST['values'] : array();

			if ( ! empty( $request_val[ $args['name'] ] ) ) {
				$value = $request_val[ $args['name'] ];
			}

			return $value;

		}

		/**
		 * Returns field label
		 *
		 * @return [type] [description]
		 */
		public function get_field_label( $args ) {

			$no_labels = $this->get_no_labels_types();

			ob_start();
			if ( ! empty( $args['label'] ) && ! in_array( $args['type'], $no_labels ) ) {
				include jet_engine()->get_template( 'forms/common/field-label.php' );
			}
			return ob_get_clean();

		}

		/**
		 * Returns field description
		 *
		 * @return [type] [description]
		 */
		public function get_field_desc( $args ) {

			$no_labels = $this->get_no_labels_types();

			ob_start();
			if ( ! empty( $args['desc'] ) && ! in_array( $args['type'], $no_labels ) ) {
				include jet_engine()->get_template( 'forms/common/field-description.php' );
			}
			return ob_get_clean();

		}

		/**
		 * Return field types without labels
		 *
		 * @return [type] [description]
		 */
		public function get_no_labels_types() {
			return array( 'submit', 'hidden' );
		}

		/**
		 * Returns field options list
		 *
		 * @return array
		 */
		public function get_field_options( $args ) {

			$options_from = ! empty( $args['field_options_from'] ) ? $args['field_options_from'] : 'manual_input';
			$options      = array();

			if ( 'manual_input' === $options_from ) {

				if ( ! empty( $args['field_options'] ) ) {

					foreach ( $args['field_options'] as $option ) {
						$options[ $option['value'] ] = $option['label'];
					}

				}

			} else {

				$key = ! empty( $args['field_options_key'] ) ? $args['field_options_key'] : '';

				if ( $key ) {
					$options = get_post_meta( $this->post->ID, $key, true );
					$options = $this->maybe_parse_repeater_options( $options );
				}

			}

			return $options;

		}

		/**
		 * Returns form action url
		 *
		 * @return [type] [description]
		 */
		public function get_form_action_url() {

			$action = add_query_arg(
				array(
					'jet_engine_action' => 'book',
				),
				home_url( '/' )
			);

			return apply_filters( 'jet-engine/forms/booking/form-action-url', $action, $this );

		}

		/**
		 * Returns form refer url
		 *
		 * @return [type] [description]
		 */
		public function get_form_refer_url() {

			global $wp;
			$refer = home_url( $wp->request );

			if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
				$refer = trailingslashit( $refer ) . '?' . $_SERVER['QUERY_STRING'];
			}

			return apply_filters( 'jet-engine/forms/booking/form-refer-url', $refer, $this );

		}

		/**
		 * Open form wrapper
		 *
		 * @return [type] [description]
		 */
		public function start_form() {

			do_action( 'jet-engine/forms/booking/before-start-form', $this );

			$this->add_attribute( 'class', 'jet-form' );
			$this->add_attribute( 'class', 'layout-' . $this->args['fields_layout'] );
			$this->add_attribute( 'class', 'submit-type-' . $this->args['submit_type'] );
			$this->add_attribute( 'action', $this->get_form_action_url() );
			$this->add_attribute( 'method', 'POST' );
			$this->add_attribute( 'data-form-id', $this->form_id );

			include jet_engine()->get_template( 'forms/common/start-form.php' );

			do_action( 'jet-engine/forms/booking/after-start-form', $this );

		}

		/**
		 * Open form wrapper
		 *
		 * @return [type] [description]
		 */
		public function start_form_row() {

			if ( ! $this->is_hidden_row ) {
				$this->rendered_rows++;
			}

			if ( true === $this->args['rows_divider'] && 1 < $this->rendered_rows && ! $this->is_hidden_row ) {
				echo '<div class="jet-form__divider"></div>';
			}

			do_action( 'jet-engine/forms/booking/before-start-form-row', $this );

			$this->add_attribute( 'class', 'jet-form-row' );

			if ( $this->is_hidden_row ) {
				$this->add_attribute( 'class', 'jet-form-row--hidden' );
			}

			if ( $this->is_submit_row ) {
				$this->add_attribute( 'class', 'jet-form-row--submit' );
			}

			if ( 1 === $this->rendered_rows ) {
				$this->add_attribute( 'class', 'jet-form-row--first-visible' );
			}

			include jet_engine()->get_template( 'forms/common/start-form-row.php' );

			do_action( 'jet-engine/forms/booking/after-start-form-row', $this );

		}

		/**
		 * Close form wrapper
		 *
		 * @return [type] [description]
		 */
		public function end_form() {

			do_action( 'jet-engine/forms/booking/before-end-form', $this );

			include jet_engine()->get_template( 'forms/common/end-form.php' );

			do_action( 'jet-engine/forms/booking/after-end-form', $this );

		}

		/**
		 * Close form wrapper
		 *
		 * @return [type] [description]
		 */
		public function end_form_row() {

			do_action( 'jet-engine/forms/booking/before-end-form-row', $this );

			include jet_engine()->get_template( 'forms/common/end-form-row.php' );

			do_action( 'jet-engine/forms/booking/after-end-form-row', $this );

		}

		/**
		 * Render passed form row
		 *
		 * @param  [type] $row [description]
		 * @return [type]      [description]
		 */
		public function render_row( $row ) {

			$filled = 0;

			foreach ( $row as $field ) {

				$push  = '';
				$col   = 'jet-form-col jet-form-col-' . $field['w'];

				if ( 0 < $filled ) {
					if ( $filled < $field['x'] ) {
						$push   = $field['x'] - $filled;
						$filled = $filled + $push;
						$push   = ' jet-form-push-' . $push;
					}
				} else {
					if ( 0 < $field['x'] ) {
						$push   = ' jet-form-push-' . $field['x'];
						$filled = $filled + $field['x'];
					}
				}

				if ( $this->is_field_visible( $field['settings'] ) ) {

					echo '<div class="' . $col . $push . '">';

					$this->render_field( $field['settings'] );

					echo '</div>';

				}


				$filled = $filled + $field['w'];

			}

		}

		/**
		 * Returns true if field is visible
		 *
		 * @param  array   $field [description]
		 * @return boolean        [description]
		 */
		public function is_field_visible( $field = array() ) {

			// For backward compatibility and hidden fields
			if ( empty( $field['visibility'] ) ) {
				return true;
			}

			// If is visible for all - show field
			if ( 'all' === $field['visibility'] ) {
				return true;
			}

			// If is visible for logged in users and user is logged in - show field
			if ( 'logged_id' === $field['visibility'] && is_user_logged_in() ) {
				return true;
			}

			// If is visible for not logged in users and user is not logged in - show field
			if ( 'not_logged_in' === $field['visibility'] && ! is_user_logged_in() ) {
				return true;
			}

			return false;

		}

		/**
		 * Render from HTML
		 * @return [type] [description]
		 */
		public function render_form( $force_update = false ) {

			if ( ! $force_update ) {

				$cached = $this->get_form_cache();

				if ( $cached ) {
					echo $cached;
					return;
				}

			}

			ob_start();

			$this->start_form();

			$this->render_field( array(
				'type'    => 'hidden',
				'default' => $this->form_id,
				'name'    => '_jet_engine_booking_form_id',
			) );



			$this->render_field( array(
				'type'    => 'hidden',
				'default' => $this->get_form_refer_url(),
				'name'    => '_jet_engine_refer',
			) );

			foreach ( $this->rows as $row ) {

				$this->is_hidden_row = true;
				$this->is_submit_row = false;

				ob_start();
				$this->render_row( $row );
				$rendered_row = ob_get_clean();

				$this->start_form_row( $row );

				echo $rendered_row;

				$this->end_form_row( $row );

			}

			$this->end_form();

			$form = ob_get_clean();

			$this->set_form_cache( $form );

			echo $form;

		}

		/**
		 * Get rendered form
		 * @return [type] [description]
		 */
		public function get_form_cache() {
			return apply_filters(
				'jet-engine/forms/booking/form-cache',
				get_post_meta( $this->form_id, '_rendered_form', true ),
				$this->form_id
			);
		}

		/**
		 * Store rendered form
		 * @param [type] $content [description]
		 */
		public function set_form_cache( $content = null ) {
			update_post_meta( $this->form_id, '_rendered_form', $content );
		}

		/**
		 * Prepare repeater options fields
		 *
		 * @param  [type] $options [description]
		 * @return [type]          [description]
		 */
		public function maybe_parse_repeater_options( $options ) {

			if ( empty( $options ) ) {
				return array();
			}

			$option_values = array_values( $options );

			if ( ! is_array( $option_values[0] ) ) {
				return $options;
			}

			$result = array();

			foreach ( $options as $option ) {

				$values = array_values( $option );

				if ( ! isset( $values[0] ) ) {
					continue;
				}

				$result[ $values[0] ] = isset( $values[1] ) ? $values[1] : $values[0];

			}

			return $result;

		}

	}

}
