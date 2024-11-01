<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}


////// checking for nonce
// if( ! wp_verify_nonce($_POST['my_testing_nonce'], 'save_post_gallery_category')){
	// die("Sorry ! You can't excess this. You can go to home page from <a href=".admin_url().">here</a>.");
// }

// Extra fields are not set.
/*
** This check is to identify the cases:
	There are 3 cases : 
	1) Quick Edit and 								====> Parameters fields will absent
	2) New Category added from post page			====> Parameters fields will absent
	3) New Category added from category page.		====> Parameters fields will present
*/

// Case 1 and Case 2 will be applicable for if section
if(!isset($_POST['gallery_border_colour'])){
	
	/// Case 2 will be applicable for if section
	$gallery_border_colour = get_term_meta( $term_id, 'gallery_border_colour', true );
	if($gallery_border_colour == ""){
		
		///// Providing initial values for category added from post page
		update_term_meta($term_id,'gallery_border_colour','000000');
		update_term_meta($term_id,'gallery_hover_colour','0085ba');
		update_term_meta($term_id,'gallery_img_width','500');
		update_term_meta($term_id,'gallery_img_height','500');
		update_term_meta($term_id,'gallery_num_cols','3');
	}
	/// Case 1 will be applicable
	else{
		return;
	}
}


/////// save category extra added fields
$arr = array("gallery_border_colour","gallery_hover_colour","gallery_img_width","gallery_img_height","gallery_num_cols","gallery_template_layout",'gallery_load_more','gallery_load_more_item');
foreach($arr as $meta){
	if ( isset( $_POST[$meta] ) ) {
		$value = sanitize_text_field($_POST[$meta]);
		update_term_meta($term_id,$meta,$value);
	}
}
?>