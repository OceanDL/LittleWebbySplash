<?php

if ( empty( $args ) ) {
	return;
}

$query_var   = $args['query_var'];
$current     = $this->get_current_filter_value( $args );
$from        = '';
$to          = '';

$classes = array(
	'jet-date-range'
);

if ( '' !== $args['button_icon'] ) {
	$classes[] = 'button-icon-position-' . $args['button_icon_position'];
}

if ( $current ) {
	$formated = explode( ':', $current );
}

?>
<div class="<?php echo implode( ' ', $classes ) ?>" <?php $this->filter_data_atts( $args, $filter ); ?>>
	<?php include jet_smart_filters()->get_template( 'common/filter-label.php' ); ?>
	<div class="jet-date-range__inputs">
		<input
			class="jet-date-range__from jet-date-range__control"
			type="text"
			autocomplete="off"
			name="<?php echo $query_var; ?>_from"
			value="<?php echo $from; ?>"
		>
		<input
			class="jet-date-range__to jet-date-range__control"
			type="text"
			autocomplete="off"
			name="<?php echo $query_var; ?>_to"
			value="<?php echo $to; ?>"
		>
	</div>
	<input
		class="jet-date-range__input"
		type="hidden"
		autocomplete="off"
		name="<?php echo $query_var; ?>"
		value="<?php echo $current; ?>"
		<?php $this->control_data_atts( $args ); ?>
	>
	<button
		type="button"
		class="jet-date-range__submit apply-filters__button"
		data-apply-type="<?php echo $args['apply_type']; ?>"
		data-apply-provider="<?php echo $args['content_provider']; ?>"
		data-query-id="<?php echo $args['query_id']; ?>"
	>
	<?php echo 'left' === $args['button_icon_position'] ? $args['button_icon'] : ''; ?>
		<span class="jet-date-range__submit-text"><?php echo $args['button_text']; ?></span>
	<?php echo 'right' === $args['button_icon_position'] ? $args['button_icon'] : ''; ?>
	</button>
</div>
