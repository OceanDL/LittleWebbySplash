<?php
/**
 * Add form select field.
 *
 * @package Raven
 * @since 1.3.0
 */

namespace Raven\Modules\Forms\Fields;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin as Elementor;

/**
 * Select Field.
 *
 * Initializing the select field by extending field base abstract class.
 *
 * @since 1.3.0
 */
class Select extends Field_Base {

	/**
	 * Get field type.
	 *
	 * Retrieve the field type.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Field type.
	 */
	public function get_type() {
		return 'select';
	}

	/**
	 * Render content.
	 *
	 * Render the field content.
	 *
	 * @since 1.3.0
	 * @access public
	 */
	public function render_content() {
		$field    = $this->field;
		$settings = $this->widget->get_active_settings();
		$rows     = empty( $this->field['rows'] ) ? '' : 'size="' . $this->field['rows'] . '"';
		$multiple = empty( $this->field['multiple_selection'] ) ? '' : 'multiple';
		$options  = preg_split( '/\R/', $field['field_options'], -1, PREG_SPLIT_NO_EMPTY );

		if ( empty( $options ) ) {
			return;
		}

		if ( $multiple ) {
			$this->widget->set_render_attribute( 'field-' . $this->get_id(), 'name', 'fields[' . $this->get_id() . '][]' );
		}

		$html = '<div class="raven-field-subgroup">';

		if ( ! $multiple ) {
			$html .= '<i class="raven-field-select-arrow ' . $settings['select_arrow_icon'] . '"></i>';
		}

		$html .= '<select ' . $this->widget->get_render_attribute_string( 'field-' . $this->get_id() ) . $rows . $multiple . '>';

		foreach ( $options as $key => $option ) {
			$id           = $this->get_id();
			$option_id    = $id . $key;
			$option_label = $option;
			$option_value = $option;

			if ( false !== strpos( $option, '|' ) ) {
				list( $option_label, $option_value ) = explode( '|', $option );
			}

			$this->widget->add_render_attribute(
				$option_id,
				[
					'value' => $option_value,
				]
			);

			$html .= '<option ' . $this->widget->get_render_attribute_string( $option_id ) . '>' . $option_label . '</option>';
		}

		$html .= '</select>';

		$html .= '</div>';

		echo $html;
	}
}
