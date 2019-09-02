<?php
/**
 * Add form date field.
 *
 * @package Raven
 * @since 1.2.0
 */

namespace Raven\Modules\Forms\Fields;

use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * Date Field.
 *
 * Initializing the date field by extending field base abstract class.
 *
 * @since 1.2.0
 */
class Date extends Field_Base {

	public function get_type() {
		if ( $this->field['native_html5'] ) {
			return 'date';
		}

		return 'text';
	}

	public function get_class() {
		return 'raven-field flatpickr';
	}

	public function get_style_depends() {
		return [ 'flatpickr' ];
	}

	public function get_script_depends() {
		return [ 'flatpickr' ];
	}

	private function get_min_date() {
		$attr = 'data-min-date';
		$min  = empty( $this->field['min_date'] ) ? '' : $this->field['min_date'];

		if ( $this->field['native_html5'] ) {
			$attr = 'min';
		}

		return $attr . '="' . $min . '"';
	}

	private function get_max_date() {
		$attr = 'data-max-date';
		$max  = empty( $this->field['max_date'] ) ? '' : $this->field['max_date'];

		if ( $this->field['native_html5'] ) {
			$attr = 'max';
		}

		return $attr . '="' . $max . '"';
	}

	public function render_content() {
		?>
		<input <?php echo $this->widget->get_render_attribute_string( 'field-' . $this->get_id() ); ?>
			<?php echo $this->get_min_date(); ?>
			<?php echo $this->get_max_date(); ?>>
		<?php
	}

	public function update_controls( $widget ) {
		$control_data = Elementor::$instance->controls_manager->get_control_from_stack(
			$widget->get_unique_name(),
			'fields'
		);

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$field_controls = [
			'min_date' => [
				'name' => 'min_date',
				'label' => __( 'Min Date', 'raven' ),
				'type' => 'date_time',
				'picker_options' => [
					'enableTime' => false,
					'locale' => [
						'firstDayOfWeek' => 1,
					],
				],
				'label_block' => false,
				'condition' => [
					'type' => 'date',
				],
			],
			'max_date' => [
				'name' => 'max_date',
				'label' => __( 'Max Date', 'raven' ),
				'type' => 'date_time',
				'picker_options' => [
					'enableTime' => false,
					'locale' => [
						'firstDayOfWeek' => 1,
					],
				],
				'label_block' => false,
				'condition' => [
					'type' => 'date',
				],
			],
		];

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $field_controls );
		$widget->update_control( 'fields', $control_data );
	}

}
