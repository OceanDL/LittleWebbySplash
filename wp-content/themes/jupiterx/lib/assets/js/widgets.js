/**
 * Refactor the codes to follow same convention as header.
 */

jQuery(document).ready(function ($) {

  // Menu and WooCommerce categories.
  //
  var $widget = $('.jupiterx-widget')
  var $menu_current_item = $widget.find('.current_page_item, .current-cat')

  // Toggle the sub-menus to show the current link.
  if ($menu_current_item.length) {
    $menu_current_item
      .parents('.sub-menu, .children').slideToggle()
      .parents('.menu-item-has-children, .cat-parent')
      .toggleClass('jupiterx-icon-plus jupiterx-icon-minus')
  }

  // Toggle the sub-menus for Menu and WooCommerce categories.
  $(document).on('click', '.jupiterx-widget .menu-item-has-children, .jupiterx-widget .cat-parent', function(e) {
    e.stopPropagation()

    if (e.target.nodeName === 'A') {
      return;
    }

    $(this)
      .toggleClass('jupiterx-icon-plus jupiterx-icon-minus')
      .find('> ul').slideToggle()
  });

});
