( function( $ ) {

  /**
   * Do not run any codes below if ACF does not exist.
   */
  if ( typeof acf === 'undefined' ) {
    return;
  }

  /**
   * Template field.
   *
   * @since 1.2.0
   */
  acf.add_action( 'ready_field/type=jupiterx_template', function( $field ) {

    const $select = $field.find( 'select' );
    const $editButton = $field.find( '.edit-button' );
    const $newButton = $field.find( '.new-button' );
    const settings = $select.data( 'settings' );

    function updateHasValue() {
      const hasValue = $select.val() !== '' && $select.val() !== 'global' ? true : false;
      $field.toggleClass( 'has-value', hasValue );
    }

    function updateTemplates( templateId ) {
      jupiterx.elementor.getTemplates( {
        data: {
          type: settings.templateType,
        },
        beforeSend: function () {
          $select.empty();
          $field.addClass( 'is-loading' );
          $select.append( '<option value selected>Loading...</option>' );
        },
        success: function ( templates ) {
          $select.empty();
          $field.removeClass( 'is-loading' );

          if ( settings.global ) {
            $select.append( `<option value="global" selected>${settings.global}</option>` );
          }

          if ( templates ) {
            for ( const id in templates ) {
              const selected = parseInt( templateId ) === parseInt( id ) ? 'selected' : '';
              $select.append( `<option ${selected} value="${id}">${templates[ id ]}</option>` );
            }
          }

          $select.trigger( 'change' );
        },
      } );
    }

    $editButton.click( function( event ) {
      event.preventDefault();

      // Format URL.
      function editUrl() {
        return `${settings.editUrl}&post=${$select.val()}`
      }

      jupiterx.elementor.openEditor( {
        url: editUrl(),
        beforeClose: function( contentWindow ) {
          const status = contentWindow.elementor.channels.editor.request( 'status' );

          if (status === false) {
            $select.trigger( 'change' );
          } else if ( status === true && ! confirm( 'Are you sure you want to discard the changes?' ) ) {
            return false;
          }
        },
      } );
    } );

    $newButton.click( function( event ) {
      event.preventDefault();

      jupiterx.elementor.openEditor( {
        url: settings.newUrl,
        beforeClose: function( contentWindow ) {
          if ( contentWindow.elementor.config.document.id ) {
            updateTemplates( contentWindow.elementor.config.document.id );
          }
        },
      } );
    } );

    $select.on( 'change', updateHasValue )
    updateHasValue();

  } );

  /**
   * Button group field.
   *
   * @since 1.3.0
   */
  acf.add_action( 'ready_field/type=button_group', function( $field ) {
    const options = acf.getField( $field );

    if ( ! options.data.proChoices ) {
      return;
    }

    $.each( options.data.proChoices, function( index, choice ) {
      const $label = $field.find( `input[value=${ choice }]` ).parent( 'label' );

      $label
        .append( jupiterxUtils.proBadge )
        .on( 'click', function( event ) {
          event.preventDefault();
        } );
    } );
  } );

} )( jQuery );
