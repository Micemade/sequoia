/**
 * ColorPickers
 */

AS.addCallbackForInit( function() {

	// Colorpicker
	jQuery('input:text.as_colorpicker').wpColorPicker();

} );

AS.addCallbackForClonedField( 'AS_Color_Picker', function( newT ) {

	// Reinitialize colorpickers
    newT.find('.wp-color-result').remove();
	newT.find('input:text.as_colorpicker').wpColorPicker();

} );