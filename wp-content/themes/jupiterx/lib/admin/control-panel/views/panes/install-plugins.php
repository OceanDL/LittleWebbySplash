<?php
if ( ! JUPITERX_CONTROL_PANEL_PLUGINS ) {
	return;
}
?>
<div class="jupiterx-cp-pane-box" id="jupiterx-cp-plugins">
	<div class="jupiterx-cp-message">
	<h4><?php
		printf(
			__( 'Plugin management is moved to <a href="%s">Appearance > Install Plugins</a>.', 'jupiterx' ),
			admin_url( 'themes.php?page=tgmpa-install-plugins' )
		);
	?></h4>
	</div>
</div>
