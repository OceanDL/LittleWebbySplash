<?php
/**
 * Single date template
 */
?>
<td class="jet-calendar-week__day<?php echo $padclass; ?>">
	<div class="jet-calendar-week__day-wrap">
		<div class="jet-calendar-week__day-header">
			<div class="jet-calendar-week__day-date"><?php echo $num; ?></div>
		</div>
		<?php
			if ( ! empty( $posts ) ) {
				echo '<div class="jet-calendar-week__day-mobile-wrap">';
					echo '<div class="jet-calendar-week__day-mobile-overlay"></div>';
					echo '<div class="jet-calendar-week__day-mobile-trigger"></div>';
				echo '</div>';
			}
		?>
		<div class="jet-calendar-week__day-content">
		<?php
			if ( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {

					$content = jet_engine()->frontend->get_listing_item( $post );

					printf(
						'<div class="jet-calendar-week__day-event" data-post-id="%2$s">%1$s</div>',
						$content,
						$post->ID
					);
				}
			}

		?>
		</div>
	</div>
</td>