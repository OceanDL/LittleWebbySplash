<?php
/**
 * Field label template
 */
?>
<div class="jet-form__label"><?php
	echo $args['label'];
	if ( $this->get_required_val( $args ) && ! empty( $this->args['required_mark'] ) ) {
		printf( '<span class="jet-form__required">%s</span>', $this->args['required_mark'] );
	}
?></div>