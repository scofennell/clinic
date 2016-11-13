/**
 * Our plugin-wide JS file.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

/**
 * A global object with members that any of our jQuery plugins can use.
 * 
 * @type {Object}
 */
var Clinic = {};

jQuery( document ).ready( function() {

	jQuery( '.CLINIC_Post_Meta_Boxes-build_meta_input-touch_time' ).clinicTouchTime();
	
});

jQuery( document ).ready( function( $ ) {

	/**
	 * 
	 * 
	 * @param  {array}  options An array of options to pass to our plugin, documented above.
	 * @return {object} Returns the item that the plugin was applied to, making it chainable.
	 */
	$.fn.clinicTouchTime = function( options ) {

		// For each element to which our plugin is applied...
		return this.each( function() {

			// Save a reference to the table, so that we may safely use "this" later.
			var that = this;

			var inputs = $( that ).find( '.CLINIC_Post_Meta_Boxes-touch_time-control select, .CLINIC_Post_Meta_Boxes-touch_time-control input' );

			var year   = $( that ).find( '.CLINIC_Post_Meta_Boxes-touch_time-aa' );
			var month  = $( that ).find( '.CLINIC_Post_Meta_Boxes-touch_time-mm' );
			var date   = $( that ).find( '.CLINIC_Post_Meta_Boxes-touch_time-jj' );
			var hour   = $( that ).find( '.CLINIC_Post_Meta_Boxes-touch_time-hh' );
			var minute = $( that ).find( '.CLINIC_Post_Meta_Boxes-touch_time-mn' );
		
			var hidden = $( that ).find( '.CLINIC_Post_Meta_Boxes-touch_time-hidden' );
		
			setInputs();

			function setInputs() {

				var val = $( hidden ).val();
				if( val == 0 ) {
					var newDate = new Date();
					var val = newDate.getTime();
					$( hidden ).val( val );
				}

				//console.log( val );

				var dateObj = new Date( val * 1000 );
				
				//console.log( 'year:'   + dateObj.getFullYear() ); 
				//console.log( 'month:'  + dateObj.getMonth() ); 
				//console.log( 'date:'   + dateObj.getDate() ); 
				//console.log( 'hour:'   + dateObj.getHours() ); 
				//console.log( 'minute:' + dateObj.getMinutes() ); 

				$( year ).val(   dateObj.getFullYear() );
				$( month ).val(  zeroise( dateObj.getMonth()   ) );
				$( date ).val(   zeroise( dateObj.getDate()    ) );
				$( hour ).val(   zeroise( dateObj.getHours()   ) );
				$( minute ).val( zeroise( dateObj.getMinutes() ) );

			}

			function zeroise( number ) {
				if( number < 10 ) {
        			return '0' + number;
   				} else {
    	    		return number;
   				}
   			}

			function updateHidden() {
	
				var yearVal   = $( year ).val();
				var monthVal  = $( month ).val();
				var dateVal   = $( date ).val();
				var hourVal   = $( hour ).val();
				var minuteVal = $( minute ).val();
			
				var dateString = monthVal + ' ' + dateVal + ', ' + yearVal + ' ' + hourVal + ':' + minuteVal + ':00';
				console.log( dateString );

				//var newDate = new newDate();
				var timestamp = Date.parse( dateString );
				console.log( 'timestamp: ' + timestamp );
				
				var newDate = new Date( timestamp );
				console.log( 'year:'   + newDate.getFullYear() ); 
				console.log( 'month:'  + newDate.getMonth() ); 
				console.log( 'date:'   + newDate.getDate() ); 
				console.log( 'hour:'   + newDate.getHours() ); 
				console.log( 'minute:' + newDate.getMinutes() ); 

				$( hidden ).val( timestamp / 1000 );

			}

			$( inputs ).each( function() {

				$( this ).on( 'change', function() {

					updateHidden();

				});

			});

			// Make our plugin chainable.
			return this;

		// End for each element to which our plugin is applied.
		});

	// End the definition of our plugin.
	}

}( jQuery ) );