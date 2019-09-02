<?php
/**
 * input[type="hidden"] template
 */

$this->add_attribute( 'required', $this->get_required_val( $args ) );
$this->add_attribute( 'name', $args['name'] );

$placeholder = ! empty( $args['placeholder'] ) ? $args['placeholder'] : false;
$default     = ! empty( $args['default'] ) ? $args['default'] : false;

?>
<select class="jet-form__field select-field"<?php $this->render_attributes_string(); ?>><?php

	if ( $placeholder ) {
		$selected_placeholder = '';

		if ( !$default ){
			$selected_placeholder = 'selected';
		}

		printf( '<option value="" disabled %s>%s</option>', $selected_placeholder, $placeholder );
	}

	if ( ! empty( $args['field_options'] ) ) {

		foreach ( $args['field_options'] as $value => $label ) {

			$selected = '';

			if ( $default ) {
				$selected = selected( $default, $value, false );
			}

			printf( '<option value="%1$s" %3$s>%2$s</option>', $value, $label, $selected );

		}

	}

?></select>