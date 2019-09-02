<?php
/**
 * submit button template
 */

$classes = array(
	'jet-form__submit',
	$args['class_name']
);

$this->add_attribute( 'class', 'jet-form__submit' );
$this->add_attribute( 'class', 'submit-type-' . $this->args['submit_type'] );
$this->add_attribute( 'class', $args['class_name'] );

if ( 'reload' === $this->args['submit_type'] ) {
	$this->add_attribute( 'type', 'submit' );
} else {
	$this->add_attribute( 'type', 'button' );
}

?>
<div class="jet-form__submit-wrap">
	<button<?php $this->render_attributes_string(); ?>><?php echo $args['label']; ?></button>
</div>