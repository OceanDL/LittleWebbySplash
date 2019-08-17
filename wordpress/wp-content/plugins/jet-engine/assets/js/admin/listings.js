(function( $ ) {

	'use strict';

	var JetListings = {

		init: function() {

			var self = this;

			if ( window.JetListingsSettings && ! window.JetListingsSettings.hasElementor ) {
				if ( $( '.page-title-action' ).length ) {
					$( '.page-title-action' ).remove();
				}
			}

			$( document )
				.on( 'click.JetListings', '.page-title-action, #jet_engine_export_skin', self.openPopup )
				.on( 'click.JetListings', '.jet-listings-popup__overlay', self.closePopup )
				.on( 'click.JetListings', '#jet_engine_import_skin', self.switchImport );

			$( 'body' ).on( 'change', '#listing_source', self.switchListingSources );

		},

		switchImport: function( event ) {

			var $this       = $( this ),
				$form       = $this.siblings( 'form' ),
				activeClass = 'import-active';

			if ( $form.hasClass( activeClass ) ) {
				$form.removeClass( activeClass );
			} else {
				$form.addClass( activeClass );
			}

		},

		switchListingSources: function( event ) {

			var $this = $( this ),
				val   = $this.find( 'option:selected' ).val(),
				$row  = $this.closest( '.jet-listings-popup__form-row' );

			$row.siblings( '.jet-template-listing' ).removeClass( 'jet-template-act' );
			$row.siblings( '.jet-template-' + val ).addClass( 'jet-template-act' );

		},

		openPopup: function( event ) {
			event.preventDefault();
			$( '.jet-listings-popup' ).addClass( 'jet-listings-popup-active' );
		},

		closePopup: function() {
			$( '.jet-listings-popup' ).removeClass( 'jet-listings-popup-active' );
		}

	};

	JetListings.init();

})( jQuery );
