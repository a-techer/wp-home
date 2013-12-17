var wphmngt = jQuery.noConflict();

wphmngt(document).ready(function( jQuery ) {
	
	/**	When typing or choosing a value in new account creation form	*/
	jQuery( document ).on( "keypress", ".wphmngt-form-fields input", function(e){
		if (  e.charCode != 32 /**	If the current key pressed is spacebar don't do this action	*/) {
			jQuery( this ).closest( "li" ).removeClass( "error" );
			jQuery( "#wphmngt-account-form-uncomplete" ).hide();
			jQuery( "#TB_ajaxContent" ).css( "height", "160px" );
			jQuery( "#wphmngt-account-form-message" ).removeClass( "wphmngt-alert-success wphmngt-alert-error" ).hide().html( "" );
		}
	});
	
	/**	When trying to save a new account by clicking on the button	*/
	jQuery( document ).on( "click", "#wphmngt-new-account-button, #wphmngt-edit-account-button", function(){
		/**	Display a loading picture	*/
		jQuery( this ).next( "img" ).show();
		
		/**	Check if all required fields are filled	*/
		var form_has_error = false;
		jQuery( "#wphmngt-account-form ul li.required" ).each( function(){
			if ( jQuery(this).children( "input" ).val() == "" ) {
				jQuery(this).addClass( "error" );
				form_has_error = true;
			}
		} );
		
		/**	In case form is complete launch it. In other case display error message	*/
		if ( !form_has_error ) {
			/**	Launch form	action */
			jQuery( "#wphmngt-account-form" ).ajaxSubmit({
				dataType: "json",
				success: function( response ) {
					if ( response[ 'status' ] ) {
						jQuery( ".wphmngt-dashboard-alert" ).html( wphmngtConvertAccentTojs( response[ 'message' ] ) ).addClass( 'wphmngt-alert-success' ).slideDown( "slow" );

						jQuery( "#wphmngt-account-form" )[0].reset();
						if ( jQuery( ".wphmngt_no_existing_account" ) ) {
							jQuery( ".wphmngt_no_existing_account" ).remove();
						}
						if ( jQuery( ".wphmngt-dashboard-account-list" ) ) {
							jQuery( ".wphmngt-dashboard-account-list" ).remove();
						}
						jQuery( "#wphmngt-dashboard-account-container .wphmngt-account-creation-button" ).before( response[ 'output' ] );
						jQuery( "#TB_closeWindowButton" ).click( );
						setTimeout(function() {
							jQuery( ".wphmngt-dashboard-alert" ).removeClass( 'wphmngt-alert-success' ).hide( ).html( "" );
						}, "3000");
					}
					else {
						jQuery( "#wphmngt-account-form-message" ).addClass( "wphmngt-alert-error" );
						jQuery( "#TB_ajaxContent" ).css( "height", "210px" );
						jQuery( "#wphmngt-account-form-message" ).html( response[ 'message' ] ).slideDown( "slow" );
						jQuery( "#wphmngt-account-form .wphmngt-loading-picture" ).hide();
					}
				},
			});
		}
		else {
			jQuery( "#wphmngt-account-form-uncomplete" ).show();
			jQuery( "#TB_ajaxContent" ).css( "height", "210px" );
			jQuery( this ).next( "img" ).hide();
		}
		
		return false;
	} );

	/**	When user want to delete an account from summary list	*/
	jQuery( document ).on( "click", ".wphmngt-account-deletion-button", function(){
		if ( confirm( wphmngtConvertAccentTojs( WPHMNGT_MSG_DELETE_ACCOUNT ) ) ) {
			var data = {
				action: "ajax-wphmngt-delete-account",
				account: jQuery( this ).closest( "li" ).attr( "id" ).replace( "wphmngt-account-", "" ),
				"wphmngt-nonce": WPHMNGT_NONCE_DELETE_ACCOUNT,
			};
			jQuery.post( ajaxurl, data, function(response){
				jQuery( ".wphmngt-dashboard-alert" ).html( wphmngtConvertAccentTojs( response[ 'message' ] ) ).addClass( 'wphmngt-alert-' + response[ 'status' ] ).slideDown( "slow" );
				if ( response[ 'status' ] == "success" ) {
					jQuery( "#wphmngt-dashboard-account-container .wphmngt-account-creation-button" ).before( response[ 'output' ] );
				//	jQuery( "#wphmngt-account-" + response[ 'account' ]).remove();
					jQuery( ".wphmngt-dashboard-account-list").remove();
				}
				setTimeout(function(){
					jQuery( ".wphmngt-dashboard-alert" ).removeClass( 'wphmngt-alert-' + response[ 'status' ] ).hide( ).html( "" );
				}, "2500");
			}, "json");
		}
	});

	/**	User want to edit an existing account from dashboard	*/
	jQuery( document ).on( "click", ".wphmngt-account-edition-button", function(){
		var account_id = jQuery( this ).closest( "li" ).attr( "id" ).replace( "wphmngt-account-", "" );
		jQuery( this ).closest( "li" ).append( "<div class='wphmngt-overlay' id='wphmngt-inline-form-" + account_id + "' ></div>" );
	});

});

/**
 * Allows to convert html special chars to normal chars in javascript messages
 * @param string text The text we want to change html special chars into normal chars
 * @returns string The text with the correspondance between html characters and ASCII characters
 */
function wphmngtConvertAccentTojs(text){
	text = text.replace(/&Agrave;/g, "\300");
	text = text.replace(/&Aacute;/g, "\301");
	text = text.replace(/&Acirc;/g, "\302");
	text = text.replace(/&Atilde;/g, "\303");
	text = text.replace(/&Auml;/g, "\304");
	text = text.replace(/&Aring;/g, "\305");
	text = text.replace(/&AElig;/g, "\306");
	text = text.replace(/&Ccedil;/g, "\307");
	text = text.replace(/&Egrave;/g, "\310");
	text = text.replace(/&Eacute;/g, "\311");
	text = text.replace(/&Ecirc;/g, "\312");
	text = text.replace(/&Euml;/g, "\313");
	text = text.replace(/&Igrave;/g, "\314");
	text = text.replace(/&Iacute;/g, "\315");
	text = text.replace(/&Icirc;/g, "\316");
	text = text.replace(/&Iuml;/g, "\317");
	text = text.replace(/&Eth;/g, "\320");
	text = text.replace(/&Ntilde;/g, "\321");
	text = text.replace(/&Ograve;/g, "\322");
	text = text.replace(/&Oacute;/g, "\323");
	text = text.replace(/&Ocirc;/g, "\324");
	text = text.replace(/&Otilde;/g, "\325");
	text = text.replace(/&Ouml;/g, "\326");
	text = text.replace(/&Oslash;/g, "\330");
	text = text.replace(/&Ugrave;/g, "\331");
	text = text.replace(/&Uacute;/g, "\332");
	text = text.replace(/&Ucirc;/g, "\333");
	text = text.replace(/&Uuml;/g, "\334");
	text = text.replace(/&Yacute;/g, "\335");
	text = text.replace(/&THORN;/g, "\336");
	text = text.replace(/&Yuml;/g, "\570");
	text = text.replace(/&szlig;/g, "\337");
	text = text.replace(/&agrave;/g, "\340");
	text = text.replace(/&aacute;/g, "\341");
	text = text.replace(/&acirc;/g, "\342");
	text = text.replace(/&atilde;/g, "\343");
	text = text.replace(/&auml;/g, "\344");
	text = text.replace(/&aring;/g, "\345");
	text = text.replace(/&aelig;/g, "\346");
	text = text.replace(/&ccedil;/g, "\347");
	text = text.replace(/&egrave;/g, "\350");
	text = text.replace(/&eacute;/g, "\351");
	text = text.replace(/&ecirc;/g, "\352");
	text = text.replace(/&euml;/g, "\353");
	text = text.replace(/&igrave;/g, "\354");
	text = text.replace(/&iacute;/g, "\355");
	text = text.replace(/&icirc;/g, "\356");
	text = text.replace(/&iuml;/g, "\357");
	text = text.replace(/&eth;/g, "\360");
	text = text.replace(/&ntilde;/g, "\361");
	text = text.replace(/&ograve;/g, "\362");
	text = text.replace(/&oacute;/g, "\363");
	text = text.replace(/&ocirc;/g, "\364");
	text = text.replace(/&otilde;/g, "\365");
	text = text.replace(/&ouml;/g, "\366");
	text = text.replace(/&oslash;/g, "\370");
	text = text.replace(/&ugrave;/g, "\371");
	text = text.replace(/&uacute;/g, "\372");
	text = text.replace(/&ucirc;/g, "\373");
	text = text.replace(/&uuml;/g, "\374");
	text = text.replace(/&yacute;/g, "\375");
	text = text.replace(/&thorn;/g, "\376");
	text = text.replace(/&yuml;/g, "\377");
	text = text.replace(/&oelig;/g, "\523");
	text = text.replace(/&OElig;/g, "\522");
	return text;
}