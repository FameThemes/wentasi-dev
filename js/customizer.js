/**
 * customizer.js
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

jQuery( document ).ready( function() {

	/* === Site title and description. === */
	wp.customize( 
		'wentasi_site_text_logo', 
		function( value ) {
			value.bind( 
				function( to ) {
					jQuery( '.ft-logo a span' ).text( to );
				} 
			);
		} 
	);

	wp.customize( 
		'wentasi_header_bg', 
		function( value ) {
			value.bind( 
				function( to ) {
					jQuery( '.ft-topbar' ).css( "background-color", to );
				} 
			);
		} 
	);

	wp.customize( 
		'wentasi_secondary_bg', 
		function( value ) {
			value.bind( 
				function( to ) {
					jQuery( '.hentry .ft-bmeta' ).css( "background-color", to );
				} 
			);
		} 
	);

	wp.customize( 
		'wentasi_secondary_color', 
		function( value ) {
			value.bind( 
				function( to ) {
					jQuery( '.hentry .ft-bmeta, .hentry .ft-bmeta a, .hentry .ft-bmeta a:hover' ).css( "color", to );
				} 
			);
		} 
	);

} ); // jQuery( document ).ready