/*-----------------------------------------------------------------------------------*/
/*	Begin
/*-----------------------------------------------------------------------------------*/


jQuery(document).ready(function() {

"use strict";

/*-----------------------------------------------------------------------------------*/
/*	Search Form
/*-----------------------------------------------------------------------------------*/

	jQuery('.search-bt').click(function(){
    	jQuery('.ft-search-hide').fadeIn(300, function(){
        	jQuery('.ft-search-hide #searchform').fadeIn(100);
    	});
    jQuery('.ft-search-hide .ft-shbg').click(function(){
        	jQuery('.ft-search-hide #searchform').fadeOut(100, function(){
        		jQuery('.ft-search-hide').fadeOut(300);
    		});
    	});
	});

/*-----------------------------------------------------------------------------------*/
/*	FitVids
/*-----------------------------------------------------------------------------------*/

	jQuery(".container").fitVids();

/*-----------------------------------------------------------------------------------*/
/*	Back Top
/*-----------------------------------------------------------------------------------*/
	
	jQuery(".back-top").hide();
		jQuery(function () {
			jQuery(window).scroll(function () {
				if (jQuery(this).scrollTop() > 400) {
					jQuery('.back-top').fadeIn();
				} else {
					jQuery('.back-top').fadeOut();
				}
			});

			jQuery('.back-top').click(function () {
				jQuery('body,html').animate({
				scrollTop: 0
				}, 500);
			return false;
		});
	}); 

/*-----------------------------------------------------------------------------------*/
/*	Footer
/*-----------------------------------------------------------------------------------*/

	jQuery("#ft-footerinside").hide();
		jQuery("#ft-ftgbt").click(function () {
			
			jQuery("#ft-footerinside").fadeToggle();
			
			jQuery(this).toggleClass("footer-toggle-active");
			
			jQuery("html, body").animate({
                scrollTop: jQuery("#ft-footerinside").offset().top
            }, 500);
			
			return false;
		});

/*-----------------------------------------------------------------------------------*/
/*	The End
/*-----------------------------------------------------------------------------------*/	

});