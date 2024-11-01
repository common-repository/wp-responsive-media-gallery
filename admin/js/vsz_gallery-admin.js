(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );
	jQuery(document).ready(function(){
		
		/* To clear the custom field */
		jQuery( document ).ajaxComplete( function(){
			var tagName = jQuery("#tag-name").val(); 
			/* checking for right ajax action */ 
			if(tagName === ""){
				//////////// clearing custom field 
				jQuery("#gallery_border_colour").css("background-color","rgb(0, 0, 0)");
				jQuery("#gallery_hover_colour").css("background-color","rgb(0, 133, 186)");
				jQuery("#gallery_border_colour").val("000000");
				jQuery("#gallery_hover_colour").val("0085BA");
				jQuery("#gallery_img_width").val("500"); 
				jQuery("#gallery_img_height").val("500"); 
				jQuery("#gallery_num_cols").val("3"); 
			}
		});
	
	});