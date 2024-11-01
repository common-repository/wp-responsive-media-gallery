<?php

	if(isset($_REQUEST['checknonce']) && !empty($_REQUEST['checknonce'])){
		
		////// checking for nonce
		if(  wp_verify_nonce($_REQUEST['checknonce'], 'check_image_src_nonce')){
			$imageId = $_REQUEST['IMAGEID'];
			$imageUrl = wp_get_attachment_image_src($imageId,true);
			echo $imageUrl[0];
		}
	}
	wp_die();
?>