(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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


/*jQuery(window).on('load', function($) {
var $grid = $('.masonry_grid').imagesLoaded( function() {
  $grid.masonry({
    itemSelector: '.masonry_item',
    percentPosition: true,
    //columnWidth: '.grid-sizer'
  }); 
});
});*/


// external js: masonry.pkgd.js
/*console.log('Start....');
var $grid = $('.masonry_grid').masonry({
  itemSelector: '.masonry_item',
  columnWidth: 160,
  // disable initial layout
  initLayout: false
});
// add event listener for initial layout
$grid.on( 'layoutComplete', function( event, items ) {
  console.log( items.length );
  console.log('Layout Complete now....');
  alert('hi');
});
// trigger initial layout
console.log('End....');
$grid.masonry();*/

// external js: masonry.pkgd.js
console.log('Start....');
var msnry = new Masonry( '.masonry_grid', {
  itemSelector: '.masonry_item',
  columnWidth: 160,
  // disable initial layout
  initLayout: false
});
// add event listener for initial layout
msnry.on( 'layoutComplete', function( items ) {
  console.log( items.length );
  alert('hi');
});
// trigger initial layout
msnry.layout();
jQuery(window).load(function(){
	msnry.layout();
});
console.log('Start....');


/*
$('.grid').masonry({
  itemSelector: '.grid-item',
  columnWidth: '.grid-sizer',
  percentPosition: true
});*/

})( jQuery );