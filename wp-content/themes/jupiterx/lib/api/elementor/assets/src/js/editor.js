( function( $ ) {

  if (typeof elementor === 'undefined' || typeof elementorCommonConfig.finder === 'undefined') {
    return;
  }

  /**
   * Add menu items.
   */
  function addMenuItems() {
    const items = [
      {
        name: 'jupiterx-theme-settings',
        icon: '',
        title: 'Theme Settings (Customizer)',
        type: 'link',
        link: elementorCommonConfig.finder.data.site.items['wordpress-customizer'].url,
        newTab: true
      },
      {
        name: 'jupiterx-control-panel',
        icon: '',
        title: 'Control Panel',
        type: 'link',
        link: elementorCommonConfig.finder.data.site.items['wordpress-dashboard'].url + 'admin.php?page=jupiterx',
        newTab: true
      }
    ]

    items.forEach(item => {
      elementor.modules.layouts.panel.pages.menu.Menu.addItem(
        item,
        'go_to',
        'exit-to-dashboard'
      );
    });
  }

  elementor.on('panel:init', addMenuItems)
} )( jQuery )
