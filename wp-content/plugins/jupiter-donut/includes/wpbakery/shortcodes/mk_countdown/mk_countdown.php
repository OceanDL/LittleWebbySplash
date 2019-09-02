<?php
$path = pathinfo( __FILE__ ) ['dirname'];
include( $path . '/config.php' );

$class = $el_class;
$class .= ' jupiter-donut-' . $visibility;

?>

<div class="mk-event-countdown <?php echo esc_attr( $class ); ?>" data-offset="<?php echo esc_attr( $offset ); ?>" data-date="<?php echo esc_attr( $date ); ?>">

	<?php if ( ! empty( $title ) ) { ?>
		<div class="mk-event-title"><?php echo $title; ?></div>
	<?php } ?>

	<ul class="mk-event-countdown-ul">
		<li>
			<span class="days timestamp">00</span>
			<span class="timeRef"><?php _e( 'days', 'mk_framework' ); ?></span>
		</li>
		<li>
			<span class="hours timestamp">00</span>
			<span class="timeRef"><?php _e( 'hours', 'mk_framework' ); ?></span>
		</li>
		<li>
			<span class="minutes timestamp">00</span>
			<span class="timeRef"><?php _e( 'minutes', 'mk_framework' ); ?></span>
		</li>
		<li>
			<span class="seconds timestamp">00</span>
			<span class="timeRef"><?php _e( 'seconds', 'mk_framework' ); ?></span>
		</li>
	</ul>

</div>
