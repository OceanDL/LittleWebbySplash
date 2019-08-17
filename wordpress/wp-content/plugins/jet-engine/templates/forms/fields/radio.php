<?php
/**
 * input[type="hidden"] template
 */

$required = $this->get_required_val( $args );
$name     = $args['name'];
$default  = ! empty( $args['default'] ) ? $args['default'] : false;

if ( ! empty( $args['field_options'] ) ) {

	foreach ( $args['field_options'] as $value => $label ) {

		$checked = '';

		if ( $default ) {
			$checked = checked( $default, $value, false );
		}

		?>
		<div class="jet-form__field-wrap radio-wrap checkradio-wrap">
			<label class="jet-form__field-label">
				<input
					type="radio"
					name="<?php echo $name; ?>"
					class="jet-form__field radio-field checkradio-field"
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