( function( $ ) {

	"use strict";

	var JetSmartFiltersAdmin = {

		init: function() {

			var self = JetSmartFiltersAdmin;

			$( document )
				.on( 'change.JetSmartFiltersAdmin', '#_filter_type', self.switchQueryVar )
				.on( 'change.JetSmartFiltersAdmin', '#_data_source', self.switchQueryVar )
				.on( 'change.JetSmartFiltersAdmin', '#_date_source', self.switchQueryVar )
				.on( 'change.JetSmartFiltersAdmin', '#_s_by', self.switchQueryVar );

			self.switchQueryVar();

			$( '#_filter_type' ).attr( 'required', 'required' );

		},

		switchQueryVar: function() {

			var type       = $( '#_filter_type option:selected' ).val(),
				source     = $( '#_data_source option:selected' ).val(),
				dateSource = $( '#_date_source option:selected' ).val(),
				sBy        = $( '#_s_by option:selected' ).val(),
				types      = [ 'checkboxes', 'select', 'radio' ],
				sources    = [ 'taxonomies' ],
				hidden     = false,
				$queryVar  = $( 'div[data-control-name="_query_var"]' );

			if ( 'search' === type ) {
				if ( 'default' === sBy ) {
					hidden = true;
				} else {
					hidden = false;
				}
			} else if ( 'date-range' === type ) {
				if ( 'date_query' === dateSource ) {
					hidden = true;
				} else {
					hidden = false;
				}
			} else if ( -1 !== types.indexOf( type ) && -1 !== sources.indexOf( source ) ) {
				hidden = true;
			}

			if ( hidden && ! $queryVar.hasClass( 'cx-control-hidden' ) ) {
				$queryVar
					.addClass( 'cx-control-hidden' )
					.find( 'input[name="_query_var"]' )
					.removeAttr( 'required' );
			}

			if ( ! hidden && $queryVar.hasClass( 'cx-control-hidden' ) ) {
				$queryVar
					.removeClass( 'cx-control-hidden' )
					.find( 'input[name="_query_var"]' )
					.attr( 'required', 'required' );
			}

		}

	};

	JetSmartFiltersAdmin.init();

}( jQuery ) );
