<?php
/**
 * Add form text field.
 *
 * @package Raven
 * @since 1.0.0
 */

namespace Raven\Modules\Forms\Fields;

defined( 'ABSPATH' ) || die();

/**
 * Text Field.
 *
 * Initializing the text field by extending field base abstract class.
 *
 * @since 1.0.0
 */
class Text extends Field_Base {

	/**
	 * Render content.
	 *
	 * Render the field content.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render_content() {
		?>
		<input <?php echo $this->widget->get_render_attribute_string( 'field-' . $this->get_id() ); ?>>
		<?php
	}

}
