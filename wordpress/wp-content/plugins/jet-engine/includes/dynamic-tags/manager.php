<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Dynamic_Tags_Manager' ) ) {

	/**
	 * Define Jet_Engine_Dynamic_Tags_Manager class
	 */
	class Jet_Engine_Dynamic_Tags_Manager {

		/**
		 * Constructor for the class
		 */
		function __construct() {

			add_action( 'elementor/init', array( $this, 'init_module' ) );
			add_action( 'jet-engine/listing/grid/before', array( $this, 'hook_inline_bg' ) );
			add_action( 'jet-engine/listing/grid/after', array( $this, 'unhook_inline_bg' ) );

		}

		/**
		 * Add hooks for inline BG.
		 * @return [type] [description]
		 */
		public function hook_inline_bg() {
			add_action( 'elementor/frontend/section/before_render', array( $this, 'add_inline_bg' ) );
		}

		/**
		 * Remove hooks for inline BG.
		 * @return [type] [description]
		 */
		public function unhook_inline_bg() {
			remove_action( 'elementor/frontend/section/before_render', array( $this, 'add_inline_bg' ) );
		}

		/**
		 * Maybe add inline BG for listing items.
		 *
		 * @param [type] $element [description]
		 */
		public function add_inline_bg( $element ) {

			$settings = $element->get_settings_for_display();

			if ( empty( $settings['background_image']['id'] ) ) {
				return;
			}

			$styles = array(
				'background-image: url(\'' . $settings['background_image']['url'] . '\')',
				'background-position: ' . $settings['background_position'],
				'background-attachment: ' . $settings['background_attachment'],
				'background-repeat: ' . $settings['background_repeat'],
				'background-size: ' . $settings['background_size'],
			);

			$element->add_render_attribute(
				'_wrapper', 'style', array(
					implode( ';', $styles ),
				)
			);

		}

		/**
		 * Initialize module
		 *
		 * @return [type] [description]
		 */
		public function init_module() {
			require jet_engine()->plugin_path( 'includes/dynamic-tags/module.php' );
			new Jet_Engine_Dynamic_Tags_Module();
		}

	}

}
