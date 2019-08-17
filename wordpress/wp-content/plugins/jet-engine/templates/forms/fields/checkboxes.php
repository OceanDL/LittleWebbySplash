<?php
/**
 * input[type="hidden"] template
 */

$required = $this->get_required_val( $args );
$name     = $args['name'];
$default  = ! empty( $args['default'] ) ? $args['default'] : false;

if ( ! empty( $args['field_options'] ) ) {

	if ( 1 < count( $args['field_options'] ) ) {
		$name_suffix = '[]';
	} else {
		$name_suffix = '';
	}

	foreach ( $args['field_options'] as $value => $label ) {

		$checked = '';

		if ( $default ) {
			if ( is_array( $default ) ) {
				$checked = in_array( $value, $default ) ? 'checked' : '';
			} else {
				$checked = checked( $default, $value, false );
			}
		}

		?>
		<div class="jet-form__field-wrap checkboxes-wrap checkradio-wrap">
			<label class="jet-form__field-label">
				<input
					type="checkbox"
					name="<?php echo $name . $name_suffix; ?>"
					class="jet-form__field checkboxes-field checkradio-field"
					value="<?php echo $value; ?>"
					<?php echo $checked; ?>
					<?php echo $required; ?>
				>
				<?php echo $label; ?>
			</label>
		</div>
		<?php

	}

}