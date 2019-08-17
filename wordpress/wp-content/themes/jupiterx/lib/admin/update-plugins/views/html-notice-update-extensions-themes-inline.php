<?php
/**
 * View for plugin/themes conflicts.
 *
 * @package JupiterX_Core\Updater
 *
 * @since 1.5.0
 */

?>

<div class="jupiterx_plugin_upgrade_notice extensions_warning minor">
	<p><?php echo wp_kses_post( $message ); ?></p>
	<?php if ( count( $conflicts['plugins'] ) > 0 ) : ?>
		<table class="plugin-details-table" cellspacing="0">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Plugins', 'jupiterx' ); ?></th>
					<th><?php esc_html_e( 'Upgrade to version', 'jupiterx' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $conflicts['plugins'] as $conflict ) : ?>
					<tr>
						<td><?php echo wp_kses_post( $conflict['name'] ); ?></td>
						<td><?php echo wp_kses_post( $conflict['min_version'] ); ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	<?php endif; ?>
	<?php if ( count( $conflicts['themes'] ) > 0 ) : ?>
		<table class="plugin-details-table" cellspacing="0">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Themes', 'jupiterx' ); ?></th>
					<th><?php esc_html_e( 'Upgrade to version', 'jupiterx' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $conflicts['themes'] as $conflict ) : ?>
					<tr>
						<td><?php echo wp_kses_post( $conflict['name'] ); ?></td>
						<td><?php echo wp_kses_post( $conflict['min_version'] ); ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
<p class="dummy">
