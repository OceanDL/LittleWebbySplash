<?php
/**
 * Meta oxes mamager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_CPT_Meta' ) ) {

	/**
	 * Define Jet_Engine_CPT_Meta class
	 */
	class Jet_Engine_CPT_Meta {

		public static $index = 0;

		public $post_type;
		public $meta_box;

		public $custom_css = array();
		public $is_allowed_on_admin_hook = null;

		public static $wrappers_hooked = false;

		/**
		 * Constructor for the class
		 */
		function __construct( $post_type, $meta_box, $title = '', $context = 'normal', $priority = 'high' ) {

			if ( ! $title ) {
				$title = esc_html__( 'Settings', 'jet-engine' );
			}

			$this->post_type = $post_type;
			$this->meta_box  = $meta_box;

			new Cherry_X_Post_Meta( array(
				'id'            => $this->get_box_id(),
				'title'         => $title,
				'page'          => array( $post_type ),
				'context'       => $context,
				'priority'      => $priority,
				'callback_args' => false,
				'builder_cb'    => array( $this, 'get_builder_for_meta' ),
				'fields'        => $this->prepare_meta_fields( $meta_box ),
			) );

			add_action( 'admin_enqueue_scripts', array( $this, 'maybe_enqueue_custom_css' ), 0 );

		}

		public function get_box_id() {
			self::$index++;
			return 'jet-engine-cpt-' . self::$index;
		}

		/**
		 * Returns builder for meta
		 *
		 * @return [type] [description]
		 */
		public function get_builder_for_meta() {

			if ( ! self::$wrappers_hooked ) {

				add_action( 'cx_post_meta/meta_box/before', array( $this, 'open_meta_wrap' ) );
				add_action( 'cx_post_meta/meta_box/after', array( $this, 'close_meta_wrap' ) );

				self::$wrappers_hooked = true;

			}

			$builder_data = jet_engine()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

			return new CX_Interface_Builder(
				array(
					'path' => $builder_data['path'],
					'url'  => $builder_data['url'],
				)
			);

		}

		/**
		 * Open meta wrap
		 * @return [type] [description]
		 */
		public function open_meta_wrap() {
			echo '<div class="jet-engine-meta-wrap">';
		}

		/**
		 * Open meta wrap
		 * @return [type] [description]
		 */
		public function close_meta_wrap() {
			echo '</div>';
		}

		/**
		 * Prepare meta fields for registering
		 *
		 * @param  array  $meta_box [description]
		 * @return [type]           [description]
		 */
		public function prepare_meta_fields( $meta_box = array() ) {

			$result = array();
			$date_assets_added = false;

			foreach ( $meta_box as $field ) {

				$result[ $field['name'] ] = array(
					'type'    => $field['type'],
					'element' => 'control',
					'title'   => $field['title'],
				);

				if ( ! empty( $field['width'] ) && '100%' !== $field['width'] ) {
					$this->custom_css[ $field['name'] ] = $field['width'];
				}

				if ( empty( $field['description'] ) ) {
					$result[ $field['name'] ]['description'] = __( 'Name: ', 'jet-engine' ) . $field['name'];
				} else {
					$result[ $field['name'] ]['description'] = $field['description'];
				}

				switch ( $field['type'] ) {
					case 'select':

						if ( empty( $field['options'] ) ) {
							$field['options'] = array();
						}

						$result[ $field['name'] ]['options'] = $this->prepare_select_options(
							$field['options']
						);

						if ( ! empty( $field['is_multiple'] ) && 'true' === $field['is_multiple'] ) {
							$result[ $field['name'] ]['multiple'] = true;
						}

						break;

					case 'checkbox':

						if ( empty( $field['options'] ) ) {
							$field['options'] = array();
						}

						$result[ $field['name'] ]['options'] = $this->prepare_select_options(
							$field['options']
						);

						break;

					case 'radio':

						if ( empty( $field['options'] ) ) {
							$field['options'] = array();
						}

						$result[ $field['name'] ]['options'] = $this->prepare_radio_options(
							$field['options']
						);

						break;

					case 'repeater':

						if ( empty( $field['repeater-fields'] ) ) {
							$field['repeater-fields'] = array();
						}

						$result[ $field['name'] ]['fields'] = $this->prepare_repeater_fields(
							$field['repeater-fields']
						);

						break;

					case 'iconpicker':

						$result[ $field['name'] ]['icon_data'] = $this->get_icon_data();

						break;

					case 'wysiwyg':

						$result[ $field['name'] ]['sanitize_callback'] = 'jet_engine_sanitize_wysiwyg';

						break;

					case 'textarea':

						$result[ $field['name'] ]['sanitize_callback'] = 'jet_engine_sanitize_textarea';

						break;

					case 'posts':

						$result[ $field['name'] ]['action']    = 'cx_search_posts';
						$result[ $field['name'] ]['post_type'] = $field['search_post_type'];

						if ( ! empty( $field['is_multiple'] ) && 'true' === $field['is_multiple'] ) {
							$result[ $field['name'] ]['multiple'] = true;
						}

						break;

					case 'media':

						$result[ $field['name'] ]['multi_upload'] = false;

						break;

					case 'gallery':

						$result[ $field['name'] ]['type']         = 'media';
						$result[ $field['name'] ]['multi_upload'] = 'add';

						break;

					case 'date':
					case 'time':
					case 'datetime':
					case 'datetime-local':

						$result[ $field['name'] ]['type']       = 'text';
						$result[ $field['name'] ]['input_type'] = $field['type'];

						if ( ! empty( $field['is_timestamp'] ) && 'true' === $field['is_timestamp'] ) {
							$result[ $field['name'] ]['is_timestamp'] = true;
						}

						add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_date_assets' ) );

						break;

				}

			}

			return $result;

		}

		public function is_allowed_on_current_admin_hook( $hook ) {

			if ( null !== $this->is_allowed_on_admin_hook ) {
				return $this->is_allowed_on_admin_hook;
			}

			$allowed_hooks = array(
				'post-new.php',
				'post.php',
			);

			if ( ! in_array( $hook, $allowed_hooks ) ) {
				$this->is_allowed_on_admin_hook = false;
				return $this->is_allowed_on_admin_hook;
			}

			if ( get_post_type() !== $this->post_type ) {
				$this->is_allowed_on_admin_hook = false;
				return $this->is_allowed_on_admin_hook;
			}

			$this->is_allowed_on_admin_hook = true;
			return $this->is_allowed_on_admin_hook;

		}

		/**
		 * Maybe add custom css
		 *
		 * @return [type] [description]
		 */
		public function maybe_enqueue_custom_css( $hook ) {

			if ( ! $this->is_allowed_on_current_admin_hook( $hook ) ) {
				return;
			}

			wp_enqueue_style(
				'jet-engine-meta-boxes',
				jet_engine()->plugin_url( 'assets/css/admin/meta-boxes.css' ),
				array(),
				jet_engine()->get_version()
			);

			if ( ! empty( $this->custom_css ) ) {

				$custom_css = '';

				foreach ( $this->custom_css as $el => $width ) {
					$custom_css .= '.cx-control[data-control-name="' . $el . '"] { max-width: ' . $width . '; flex: 0 0 ' . $width . '; }';
				}

				wp_add_inline_style( 'jet-engine-meta-boxes', $custom_css );

			}

		}

		/**
		 * Enqueue date-related assets
		 *
		 * @param  [type] $hook [description]
		 * @return [type]       [description]
		 */
		public function enqueue_date_assets( $hook ) {

			if ( ! $this->is_allowed_on_current_admin_hook( $hook ) ) {
				return;
			}

			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-slider' );

			wp_enqueue_script(
				'jquery-ui-timepicker-addon',
				jet_engine()->plugin_url( 'assets/lib/jquery-ui-timepicker/jquery-ui-timepicker-addon.min.js' ),
				array(),
				jet_engine()->get_version(),
				true
			);

			wp_enqueue_script(
				'jet-engine-meta-boxes',
				jet_engine()->plugin_url( 'assets/js/admin/meta-boxes.js' ),
				array( 'jquery' ),
				jet_engine()->get_version(),
				true
			);

			wp_enqueue_style(
				'jquery-ui-timepicker-addon',
				jet_engine()->plugin_url( 'assets/lib/jquery-ui-timepicker/jquery-ui-timepicker-addon.min.css' ),
				array(),
				jet_engine()->get_version()
			);

		}

		/**
		 * Returns default icon data
		 *
		 * @return void
		 */
		public function get_icon_data() {

			ob_start();

			include jet_engine()->plugin_path( 'assets/js/admin/icons.json' );
			$json = ob_get_clean();

			$icons_list = array();
			$icons      = json_decode( $json, true );

			foreach ( $icons['icons'] as $icon ) {
				$icons_list[] = $icon['id'];
			}

			return array(
				'icon_set'    => 'jetFontAwesome',
				'icon_css'    => ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/font-awesome.min.css',
				'icon_base'   => 'fa',
				'icon_prefix' => 'fa-',
				'icons'       => $icons_list,
			);

		}

		public function prepare_repeater_fields( $repeater_fields = array() ) {

			if ( ! $repeater_fields ) {
				$repeater_fields = array();
			}

			$result            = array();
			$date_assets_added = false;

			foreach ( $repeater_fields as $field ) {
				$result[ $field['name'] ] = array(
					'type'  => $field['type'],
					'id'    => $field['name'],
					'name'  => $field['name'],
					'label' => $field['title'] . ' (' . __( 'name: ', 'jet-engine' ) . $field['name'] . ')',
				);

				switch ( $field['type'] ) {

					case 'iconpicker':

						$result[ $field['name'] ]['icon_data'] = $this->get_icon_data();

						break;

					case 'wysiwyg':

						$result[ $field['name'] ]['sanitize_callback'] = 'jet_engine_sanitize_wysiwyg';

						break;

					case 'media':

						$result[ $field['name'] ]['multi_upload'] = false;

						break;

					case 'gallery':

						$result[ $field['name'] ]['type']         = 'media';
						$result[ $field['name'] ]['multi_upload'] = 'add';

						break;

					case 'date':
					case 'time':
					case 'datetime':
					case 'datetime-local':

						$result[ $field['name'] ]['type']       = 'text';
						$result[ $field['name'] ]['input_type'] = $field['type'];

						if ( ! $date_assets_added ) {
							add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_date_assets' ) );
							$date_assets_added = true;
						}

						break;

				}

			}

			return $result;

		}

		public function prepare_radio_options( $options = array() ) {

			if ( ! $options ) {
				$options = array();
			}

			$result = array();

			foreach ( $options as $option ) {
				$result[ $option['key'] ] = array(
					'label' => $option['value']
				);
			}

			return $result;

		}

		/**
		 * Prepare options for select
		 * @return [type] [description]
		 */
		public function prepare_select_options( $options = array() ) {

			if ( ! $options ) {
				$options = array();
			}

			$result = array();

			foreach ( $options as $option ) {
				$result[ $option['key'] ] = $option['value'];
			}

			return $result;
		}

	}

}
