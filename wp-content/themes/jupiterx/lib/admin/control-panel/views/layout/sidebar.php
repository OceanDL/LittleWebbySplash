<div class="jupiterx-cp-sidebar">
	<ul class="jupiterx-cp-sidebar-list">
		<?php if ( JUPITERX_CONTROL_PANEL_HOME ) : ?>
		<li class="jupiterx-cp-sidebar-list-items jupiterx-is-active">
			<a class="jupiterx-cp-sidebar-link js__cp-sidebar-link" href="#home">
				<?php esc_html_e( 'Home', 'jupiterx' ); ?>
				<?php if ( ! jupiterx_is_registered() && jupiterx_is_premium() ): ?>
					<img class="jupiterx-premium-warning-badge" src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'control-panel/assets/images/warning-badge.svg' ); ?>" title="<?php _e( 'Activate Product', 'jupiterx' ); ?>" alt="Warning icon" width="16" height="16"/>
				<?php endif; ?>
			</a>
		</li>
		<?php endif; ?>
		<?php if ( JUPITERX_CONTROL_PANEL_PLUGINS ) : ?>
		<li class="jupiterx-cp-sidebar-list-items">
			<a class="jupiterx-cp-sidebar-link js__cp-sidebar-link" href="#install-plugins">
				<?php esc_html_e( 'Plugins', 'jupiterx' ); ?>
			</a>
		</li>
		<?php endif; ?>
		<?php if ( JUPITERX_CONTROL_PANEL_TEMPLATES ) : ?>
		<li class="jupiterx-cp-sidebar-list-items">
			<a class="jupiterx-cp-sidebar-link js__cp-sidebar-link" href="#install-templates">
				<?php esc_html_e( 'Templates', 'jupiterx' ); ?>
			</a>
		</li>
		<?php endif; ?>
		<?php if ( jupiterx_is_callable( 'JupiterX_Core' ) && JUPITERX_CONTROL_PANEL_IMAGE_SIZES ) : ?>
		<li class="jupiterx-cp-sidebar-list-items">
			<a class="jupiterx-cp-sidebar-link js__cp-sidebar-link" href="#image-sizes">
				<?php esc_html_e( 'Image Sizes', 'jupiterx' ); ?>
			</a>
		</li>
		<?php endif; ?>
		<?php if ( JUPITERX_CONTROL_PANEL_SYSTEM_STATUS ) : ?>
		<li class="jupiterx-cp-sidebar-list-items">
			<a class="jupiterx-cp-sidebar-link js__cp-sidebar-link" href="#system-status">
				<?php esc_html_e( 'System Status', 'jupiterx' ); ?>
			</a>
		</li>
		<?php endif; ?>
		<?php if ( JUPITERX_CONTROL_PANEL_EXPORT_IMPORT ) : ?>
		<li class="jupiterx-cp-sidebar-list-items">
			<a class="jupiterx-cp-sidebar-link js__cp-sidebar-link" href="#export-import">
				<?php esc_html_e( 'Export/Import', 'jupiterx' ); ?>
			</a>
		</li>
		<?php endif; ?>
		<?php
		
		if ( JUPITERX_CONTROL_PANEL_UPDATES ) :
		?>
		<li class="jupiterx-cp-sidebar-list-items">
			<a class="jupiterx-cp-sidebar-link js__cp-sidebar-link" href="#update-theme">
				<?php esc_html_e( 'Updates', 'jupiterx' ); ?>
			</a>
		</li>
		<?php
		endif;
		
		?>
		<?php if ( jupiterx_is_callable( 'JupiterX_Core' ) && JUPITERX_CONTROL_PANEL_SETTINGS ) : ?>
		<li class="jupiterx-cp-sidebar-list-items">
			<a class="jupiterx-cp-sidebar-link js__cp-sidebar-link" href="#settings">
				<?php esc_html_e( 'Settings', 'jupiterx' ); ?>
			</a>
		</li>
		<?php endif; ?>
		<?php if ( ! jupiterx_is_pro() && ! jupiterx_is_premium() ) : ?>
		<li class="jupiterx-cp-sidebar-list-items">
			<a class="jupiterx-cp-sidebar-link js__cp-sidebar-link jupiterx-sidebar-item-colored" href="#free-vs-pro">
				<?php esc_html_e( 'Free vs Pro', 'jupiterx' ); ?>
			</a>
		</li>
		<?php endif; ?>
		<?php if ( JUPITERX_CONTROL_PANEL_SUPPORT ) : ?>
		<!-- <li class="jupiterx-cp-sidebar-list-items">
			<a class="jupiterx-cp-sidebar-link js__cp-sidebar-link" href="#support">
				<?php esc_html_e( 'Support', 'jupiterx' ); ?>
			</a>
		</li> -->
		<?php endif; ?>
	</ul>
</div>
