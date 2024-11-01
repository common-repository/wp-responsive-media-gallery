<?php
	
	if(isset($_REQUEST['checknonce']) && !empty($_REQUEST['checknonce'])){
		
		////// checking for nonce
		if(  wp_verify_nonce($_REQUEST['checknonce'], 'check_video_exist_nonce')){
			if(isset($_REQUEST['url_to_check']) && !empty($_REQUEST['url_to_check'])){
				$videoUrl = urldecode($_REQUEST['url_to_check']);
				$embed_code = wp_oembed_get( $videoUrl, '' );
				echo $embed_code;
			}
		}
	}
	wp_die();
?>