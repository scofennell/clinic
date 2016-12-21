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

/**
 * Our jQuery plugin for doing something.
 */
jQuery( document ).ready( function() {

	// Start an options object that we'll pass when we use our jQuery plugin.
	var options = {};

	// Apply our plugin to our table.
	jQuery( '.clinic-show_hide' ).clinicShowHide( options );

});

jQuery( document ).ready( function( $ ) {

	/**
	 * Define our jQuery plugin for doing things.
	 * 
	 * @param  {array}  options An array of options to pass to our plugin, documented above.
	 * @return {object} Returns the item that the plugin was applied to, making it chainable.
	 */
	$.fn.clinicShowHide = function( options ) {

		// For each element to which our plugin is applied...
		return this.each( function() {

			// Save a reference to the table, so that we may safely use "this" later.
			var that = this;

			var showsHides = $( that ).find( '.clinic-shows_hides' );
			var shownHidden = $( that ).find( '.clinic-shown_hidden' );

			console.log( shownHidden );

			$( showsHides ).on( 'click', function( event ) {
				event.preventDefault();

				$( this ).toggleClass( 'clinic-shows_hides-showing' );

				$( shownHidden ).toggleClass( 'clinic-shown_hidden-shown' );

			});

			// Make our plugin chainable.
			return this;

		// End for each element to which our plugin is applied.
		});

	// End the definition of our plugin.
	}

}( jQuery ) );

/**
 * Our jQuery plugin for doing something.
 */
jQuery( document ).ready( function() {

	// Start an options object that we'll pass when we use our jQuery plugin.
	var options = {};

	// Apply our plugin to our table.
	jQuery( '.CLINIC_Autocomplete-get' ).clinicAutocomplete( options );

});

jQuery( document ).ready( function( $ ) {

	/**
	 * Define our jQuery plugin for doing things.
	 * 
	 * @param  {array}  options An array of options to pass to our plugin, documented above.
	 * @return {object} Returns the item that the plugin was applied to, making it chainable.
	 */
	$.fn.clinicAutocomplete = function( options ) {

		// For each element to which our plugin is applied...
		return this.each( function() {

			// Save a reference to the table, so that we may safely use "this" later.
			var that = this;

			var objectType = $( that ).data( 'object_type' );

			var source = CLINIC_Autocomplete.source + objectType;

			/**
			 * Convert a comma-sep list into an array.
			 * 
			 * @param  val A string with comma-sep values.
			 * @return array
			 */
			function split( val ) {
				return val.split( /,\s*/ );
			}
			
			/**
			 * Grab the last item in a comma-sep list.
			 * 
			 * @param  term A string with comma-sep values.
			 * @return string
			 */
			function extractLast( term ) {
				return split( term ).pop();
			} 

			// Select the autosuggest input.
			$( that )

			// Don't navigate away from the field on tab when selecting an item.
			.bind( 'keydown', function( event ) {

				console.log( 'keydown' );

				if ( event.keyCode === jQuery.ui.keyCode.TAB && jQuery( this ).autocomplete( 'instance' ).menu.active ) {
					event.preventDefault();
				}
			})
			
			.autocomplete({
				
				minLength: 0,
				
				source: function( request, response ) {
					
					// delegate back to autocomplete, but extract the last term
					response(
						jQuery.ui.autocomplete.filter(
							source, extractLast( request.term )
						)
					);

				},
				
				focus: function() {

					console.log( 'focus' );

					// prevent value inserted on focus
					return false;
			
				},
				
				select: function( event, ui ) {
					
					console.log( 'select' );

					var terms = split( this.value );
					
					// remove the current input
					terms.pop();
					
					// add the selected item
					if( ! ( jQuery.inArray( ui.item.value, terms ) > -1 ) ) {
						terms.push( ui.item.value );
					}

					// add placeholder to get the comma-and-space at the end
					terms.push( '' );

					this.value = terms.join( ', ' );

					return false;

				}
			});

			// Make our plugin chainable.
			return this;

		// End for each element to which our plugin is applied.
		});

	// End the definition of our plugin.
	}

}( jQuery ) );