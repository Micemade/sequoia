
/**
 * Date & Time Fields
 */

AS.addCallbackForClonedField( ['AS_Date_Field', 'AS_Time_Field', 'AS_Date_Timestamp_Field', 'AS_Datetime_Timestamp_Field' ], function( newT ) {

	// Reinitialize all the datepickers
	newT.find( '.as_datepicker' ).each(function () {
		jQuery(this).attr( 'id', '' ).removeClass( 'hasDatepicker' ).removeData( 'datepicker' ).unbind().datepicker();
	});

	// Reinitialize all the timepickers.
	newT.find('.as_timepicker' ).each(function () {
		jQuery(this).timePicker({
			startTime: "00:00",
			endTime: "23:30",
			show24Hours: false,
			separator: ':',
			step: 30
		});
	});

} );

AS.addCallbackForInit( function() {

	// Datepicker
	jQuery('.as_datepicker').each(function () {
		jQuery(this).datepicker();
	});
	
	// Wrap date picker in class to narrow the scope of jQuery UI CSS and prevent conflicts
	jQuery("#ui-datepicker-div").wrap('<div class="as_element" />');

	// Timepicker
	jQuery('.as_timepicker').each(function () {
		jQuery(this).timePicker({
			startTime: "00:00",
			endTime: "23:30",
			show24Hours: false,
			separator: ':',
			step: 30
		});
	} );

});