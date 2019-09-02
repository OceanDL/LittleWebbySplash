# ChangeLog

## 1.4.1
* FIX: Date- and timepicker styles;
* FIX: Listing Grid slider behavior when found posts < number slides to show;
* FIX: Start of month and prev month dates calculations in Calendar widget;
* FIX: Better check for an empty results in Dynamic Field widget.

## 1.4.0
* ADD: `Register user` notification for booking form;
* ADD: `Call a Webhook` notification for booking form;
* ADD: `Field Visibility` option for Booking form fields;
* ADD: `Post status` option for Insert post notification in Booking form;
* ADD: Allow to use dynamic popups from JetPopup inside listing and calendar;
* ADD: Mobile settings for image gallery slider in Dynamic Field widget;
* ADD: Allow to set meta field width;
* UPD: Allow to showcase listing grid as slider;
* UPD: Allow to set equal columns height for listing grid widget;
* UPD: Cross-browser Date- and Timepicker for meta fields;
* FIX: Allow to use shortcode in Not Found Message control;
* FIX: Mobile columns in Listing Grid widget.

## 1.3.2
* ADD: Post status control for Listing Grid Widget;
* UPD: Gallery slider init for Dynamic Field widget;
* UPD: Move meta boxes registration to admin_init hook
* FIX: Better ACF compatibility;
* FIX: Timestamp validation for Format Date callback in Dynamic Field widget;
* FIX: Jet Smart Filters compatibility for Listing Grid Widget when archive query option enabled;
* FIX: Custom meta fields list in Dynamic Repeater Widget;

## 1.3.1
* ADD: 'Get post/page link (linked post title)' callback for Dynamic Field widget;

## 1.3.0
* ADD: Booking Forms functionality;
* FIX: %current_terms% macros processing for terms query;

## 1.2.6
* UPD: Responsive controls for gaps in Listing Grid widget;
* FIX: Better compatibility with JetSmartFilters;
* FIX: Processing datetime meta;

## 1.2.5
* ADD: Better WPML compatibility;
* UPD: Listing returns not found when using related posts macros and no related posts were found;
* FIX: Show in nav menu control for Post Types and Taxonomies;
* FIX: Make relations compatible with PODs;
* FIX: Prevent errors with Elementor 2.4.0;
* FIX: Customize field output in combination with Related posts list callback;
* FIX: Delimiter for checkboxes values

## 1.2.4
* ADD: Admin columns management;
* ADD: Text before and Text after controls for Dynamic terms widget;
* ADD: Allow to enable/disable link on term in Dynamic terms widget;
* ADD: 'queried_term' macros;
* FIX: months translation in Calendar grid widget

## 1.2.3
* ADD: RU localization

## 1.2.2
* ADD: rel and target controls to Dynamic Image link;
* ADD: allow to add meta boxes only for specific posts/pages;
* FIX: Checkbox meta fields processing.

## 1.2.1
* FIX: Allow to use HTML tags in textarea meta field;

## 1.2.0
* ADD: Calendar widget;
* ADD: Posts relationships;
* ADD: Columns gap for Listing Grid widget;
* ADD: 'QR Code' callback to filter Dynamic Field output;
* ADD: 'Format number' callback to filter Dynamic Field output;
* ADD: Date query controls for Posts query settings in Listing Grid widget;
* FIX: Dynamic image tag.

## 1.1.3

* ADD: 'Embed icon' callback for Filter field output option in Dynamic field widget;
* ADD: %current_terms% and %current_meta% macros;
* UPD: Allow to use shortcodes inside Field format control in Dynamic field widget;
* FIX: Prevent JavaScript errors in some cases;
* FIX: Correctly enqueue assets for multiple meta boxes on page.

## 1.1.2

* ADD: Allow to filter posts query arguments;
* ADD: 'Get value from query variable' control for Meta Query in Listing Grid Widget;
* ADD: Allow to use macros in Get terms of posts control in Listing grid widget;
* UPD: Prepare for JetSmartFilters plugin;
* FIX: Current object reset after listing.

## 1.1.1

* ADD: Listing Grid visibility options;
* FIX: Hook name for filtering macros and filters lists;
* FIX: Applying macros in Meta Query values.

## 1.1.0

* ADD: Allow to filter values in repeater items;
* ADD: Gallery meta field;
* ADD: %current_id%, %current_tags% and %current_categories% macros;
* ADD: WYSIWYG meta field;
* ADD: Slider and grid output for gallery meta field;
* ADD: New options for Dynamic Repeater widget;
* ADD: New opitons for Dynamic Link widget;
* ADD: Embed URL callback;
* ADD: Allow to use dynamic terms images on taxonomy archives;
* UPD: Allow to set and output multiple values for select field;
* FIX: Prevent errors on archive pages;
* FIX: Meta boxes registration;
* FIX: Meta fields parsing for dynamic widgets options.

## 1.0.0

* Initial release;
