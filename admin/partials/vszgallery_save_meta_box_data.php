<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

//Check current request with post array
if(!is_admin() || empty($_POST)){
	return;
}

// Check the user's permissions.
if ( isset( $_POST['post_type'] ) && 'vsz_gallery' == $_POST['post_type'] ) {

	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

} else {

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
}

// return for quick edit
if(!isset($_POST['detailSection']) || empty($_POST['detailSection']) && (!isset($_POST['JS_OPTION']) || !empty($_POST['JS_OPTION']))){
	return;
}

////// checking for nonce
if( ! wp_verify_nonce($_POST['my_testing_nonce'], 'save_post_gallery')){
	die("Sorry ! You can't excess this. You can go to home page from <a href=".admin_url().">here</a>.");
}




/***************************** Start Save image related information ***************************************/

$detailSection = $_POST['detailSection'];

if(!empty($detailSection)){
	
	foreach($detailSection as $key => $value ){
		
		if($key == 'customId'){
			unset($detailSection[$key]);					/////// removing initially appended extra element
		}
	}
	
	$num = count($detailSection);
	if($num>0){
		foreach($detailSection as $i=>$value){
			// to encode the data before save
			if(isset($detailSection[$i]['img_title'])){
				$detailSection[$i]['img_title'] = str_replace("\\\"","\\\\\\\"",$detailSection[$i]['img_title']);
			}
			
			if(isset($detailSection[$i]['video_title'])){
				$detailSection[$i]['video_title'] = str_replace("\\\"","\\\\\\\"",$detailSection[$i]['video_title']);
			}
			
			if(isset($detailSection[$i]['img_title'])){
				$detailSection[$i]['img_title'] = str_replace("\\'","\\\\\\'",$detailSection[$i]['img_title']);
			}
			
			if(isset($detailSection[$i]['video_title'])){
				$detailSection[$i]['video_title'] = str_replace("\\'","\\\\\\'",$detailSection[$i]['video_title']);
			}
			
			if(isset($detailSection[$i]['freetext'])){
				$detailSection[$i]['freetext'] = str_replace("\\'","\\\\\\'",$detailSection[$i]['freetext']);
			}
			
			if(isset($detailSection[$i]['video_freetext'])){
				$detailSection[$i]['video_freetext'] = str_replace("\\'","\\\\\\'",$detailSection[$i]['video_freetext']);
			}
			
			if(isset($detailSection[$i]['freetext'])){
				$detailSection[$i]['freetext'] = str_replace("\\\"","\\\\\\\"",$detailSection[$i]['freetext']);
			}
			
			if(isset($detailSection[$i]['video_freetext'])){
				$detailSection[$i]['video_freetext'] = str_replace("\\\"","\\\\\\\"",$detailSection[$i]['video_freetext']);
			}
		}
	}
	update_post_meta($post_id,'gallery_image_details',$detailSection);
}


/***************************** End Save image related information *****************************************/
/***************************** Start sort order related information ***************************************/
//Save sort order value	
global $wpdb;
$arrMeta = '';
$arrMeta = get_post_meta($post_id);
if(isset($arrMeta['gallery_sortorder'])){
	$sortOrderValue = $arrMeta['gallery_sortorder'][0];
}
else{
	$key = 'gallery_sortorder';
	$status = 'publish';
	$type = 'vsz_gallery';
	$selQry = $wpdb->get_col( $wpdb->prepare( " SELECT pm.meta_value FROM {$wpdb->postmeta} pm
												LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
												WHERE pm.meta_key = '%s'
												AND p.post_status = '%s'
												AND p.post_type = '%s'",
												$key, $status, $type ) );
	if(!empty($selQry[0])){
		
		$maxSortorder = '';
		foreach($selQry as $value){
			
			if(empty($maxSortorder)){
				$maxSortorder = $value;
			}
			if($maxSortorder < $value){
				$maxSortorder = $value;
			}
		}
		$sortOrderValue = (int)$maxSortorder + 1;
	}
	else{
		$sortOrderValue = 1;
	}
}
update_post_meta( $post_id, 'gallery_sortorder', sanitize_text_field($sortOrderValue));

/***************************** End sort order related information ***************************************/

//Save post status
if(!isset($arrCurrentEventMeta['gallery_status'])){
	update_post_meta($post_id,'gallery_status','active');
}

// Save post meta values
$arrMeta = array('gallery_border_colour','gallery_hover_colour','gallery_img_width','gallery_img_height','gallery_num_cols','gallery_template_layout','gallery_load_more','gallery_load_more_item');
foreach($arrMeta as $meta){
	update_post_meta( $post_id, $meta ,sanitize_text_field($_POST[$meta]) );	
}