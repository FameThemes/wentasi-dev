jQuery( document ).ready( function() {
	/* === <p> === */

	wp.customize(
		'p_font_family',
		function( value ) {
			value.bind( 
				function( to ) {
					jQuery( 'body.ctypo p' ).css( 'font-family', to );
				} 
			);
		}
	);

} ); // jQuery( document ).ready