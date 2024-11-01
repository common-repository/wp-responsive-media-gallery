<?php

//require_once dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/wp-load.php";
	// getting parameters
global $wpdb;
$loadedpost = $_POST['load_more'];
// get id for responsive media gallery shortcode if define
//$arrInfo = shortcode_atts( array( 'id' => '','category' => '' ),$atts );
//var_dump($arrInfo);exit;
$galleryPostId = $_POST['postId'];
$galleryCategoryName = '';
$loadmoreitem = $_POST['loadmoreitem'] - 1;
$galleryCategoryName = $_POST['galleryCategoryName'];
$categoryWiseDisplay = false;			////// This variable is defines that the output will be for particular post id wise or categorywise.
										////// If false than post id wise display
										////// If true than categorywise display
//global $post;
$args = array(
				'post_type'			=> 'vsz_gallery',
				'meta_key' 			=> 'gallery_sortorder',
				'orderby' 			=> 'meta_value_num',
				'order'     		=> 'ASC',
				'paged'		  		=> 1,
				'meta_query'		=> array(array( 'key' => 'gallery_status','value' => 'active',),),
				);
			
// to display gallery by post.
if(!empty($galleryPostId)){
	$args['p'] = $galleryPostId;
}

// for displaying gallery by category
if(!empty($galleryCategoryName)){
	$args['tax_query'] = array( array(
			'taxonomy' => 'vsz_cat',
			'field'    => 'slug',
			'terms'    => trim($galleryCategoryName),
		),
	);
	$categoryWiseDisplay = true;
}


$galleryPostId = '';   ///Clear the variable
///// Getting posts to display
$arrgalleryRecords = new WP_Query( $args );

//////// This variable is saving all the html. This variable will be return at the end.
$content = '';

///// If gets any post
if(!empty($arrgalleryRecords->posts)){
require_once dirname(dirname(plugin_dir_path(__FILE__)))."/custom_image/custom_image.php";

foreach($arrgalleryRecords->posts as $key =>$value){
		$arrMeta = get_post_meta($value->ID);
		$galleryPostId = $value->ID;
		
		/////// If dis-played by category than initial parameters will be from category
		if($categoryWiseDisplay){
			$term = get_term_by( 'slug', $galleryCategoryName, 'vsz_cat' );
			if(isset($term->term_id)){
				///// Getting initial parameters
				$gallery_border_colour = get_term_meta($term->term_id,'gallery_border_colour',true);
				$gallery_hover_colour = get_term_meta($term->term_id,'gallery_hover_colour',true);
				$imgWidth = get_term_meta($term->term_id,'gallery_img_width',true);
				if(empty($imgWidth)){
					$imgWidth = 100;
				}
				$imgHeight = get_term_meta($term->term_id,'gallery_img_height',true);
				if(empty($imgHeight)){
					$imgHeight = 100;
				}
				$noOfCols = get_term_meta($term->term_id,'gallery_num_cols',true);
			}
			else{
				////////// Category not found than initial parameters will be of post
				//  gwetting initial parameters
				$gallery_border_colour = (!empty($arrMeta['gallery_border_colour'][0]) ? $arrMeta['gallery_border_colour'][0] : '');
				$gallery_hover_colour = (!empty($arrMeta['gallery_hover_colour'][0]) ? $arrMeta['gallery_hover_colour'][0] : '');
				$imgWidth = (!empty($arrMeta['gallery_img_width'][0]) ? $arrMeta['gallery_img_width'][0] : '100');
				$imgHeight = (!empty($arrMeta['gallery_img_height'][0]) ? $arrMeta['gallery_img_height'][0] : '100');
				$noOfCols = (!empty($arrMeta['gallery_num_cols'][0]) ? $arrMeta['gallery_num_cols'][0] : '2');
			}
		}
		//////// If displayed by post id than initial parameters will be of post
		else{
			//  gwetting initial parameters
			$gallery_border_colour = (!empty($arrMeta['gallery_border_colour'][0]) ? $arrMeta['gallery_border_colour'][0] : '');
			$gallery_hover_colour = (!empty($arrMeta['gallery_hover_colour'][0]) ? $arrMeta['gallery_hover_colour'][0] : '');
			$imgWidth = (!empty($arrMeta['gallery_img_width'][0]) ? $arrMeta['gallery_img_width'][0] : '100');
			$imgHeight = (!empty($arrMeta['gallery_img_height'][0]) ? $arrMeta['gallery_img_height'][0] : '100');
			$noOfCols = (!empty($arrMeta['gallery_num_cols'][0]) ? $arrMeta['gallery_num_cols'][0] : '2');
		}
		
		//////// If gallery details are present
		if(isset($arrMeta['gallery_image_details']) && !empty($arrMeta['gallery_image_details'][0])){
			$arr_details = maybe_unserialize($arrMeta['gallery_image_details'][0]);
		}
		/////// If gallery JS options are present
		if(isset($arrMeta['JS_OPTION']) && !empty($arrMeta['JS_OPTION'][0])){
			$arrJS = maybe_unserialize($arrMeta['JS_OPTION'][0]);
		}
							
		
		if(isset($arr_details) && !empty($arr_details)){
			// display gallery elements start here  ///////
			/////// Inner forloop starts here  ///////////
				$loadmore = $loadedpost;
				$countload = $loadedpost + $loadmoreitem;
				
				foreach($arr_details as $popupkey => $elementInfo){
				if($popupkey > $loadedpost){
				$popupkey = $value->ID."-".$popupkey;				/////// Creating a unique popup key to differentiate every popup div
				///////////////// For Image Section  /////////////
				if($elementInfo['content_type'] == "image" && $elementInfo['img_id'] != '' && isset($elementInfo['exclude']) && $elementInfo['exclude'] != 1){
					$content .= '<div class="masonry_item col-grid-'.$noOfCols.'">';
						$content .= '<div class="image-outer">
										<!-- style is for hover color -->
										<style>
										.gallery-plugin .image-inner'.$galleryPostId.' a:hover{
											border-color:#'.$gallery_hover_colour.' !important;														
										}
										</style>
										';
								// get custom size image
						$imageURL = wp_get_attachment_image_src($elementInfo['img_id'],'full');    			/// Getting image url
						$imageUrl1 = get_image_url_custom($imgWidth,$imgHeight,true,$imageURL[0]); 			/// Regenerate image as per values defined in admin
											
							// displaying image
							$content .= '<div class="image-inner image-inner'.$galleryPostId.'">
											<a href="#popup-'.$popupkey.'" style="border-color:#'.$gallery_border_colour.'" class="vsz_btn-popup-gallery magnific item" data-desc="'.$elementInfo['freetext'].'" data-title="'.$elementInfo['img_title'].'" title="'.$elementInfo['img_title'].'">
												<img data-id=""  src="'.$imageURL[0].'" alt="'.$elementInfo['img_title'].'" />
											</a>
										</div>';
							///////// popup structure for image
							$content .='<div id="popup-'.$popupkey.'" class="zoom-anim-dialog popup-gallery mfp-hide">
											<div class="gallery-pop-up-images">
												<div class="pop-up-images-image">';
									if(!empty($elementInfo['img_title'])){
										///// Title for this image is added
										$content .='<div class="title-class">
														<h3>'.$elementInfo['img_title'].'</h3>
													</div>';
									}
										$content .= '<div class="img-src">';
										if(!empty($elementInfo['img_id'])){
											///// Image exists
											$imageURL = wp_get_attachment_image_src($elementInfo['img_id']);
											$content .= wp_get_attachment_image( $elementInfo['img_id'], 'full', '','' );
										}
										else{
											///// Image not exists so displaying default image
											$content .= get_image_url('600','600', true,plugin_dir_url(dirname(dirname(__FILE__))).'upload/new-dummy.jpg');
										}
										///// Displaying popup close button
										$content .= '<button title="Close (Esc)" type="button" class="mfp-close">×</button>
													</div>';
											if($elementInfo['freetext'] && $elementInfo['freetext'] != ''){
												///// Text for this image is added
												$content .='<div class="courtsey-class">
													<h2>'.$elementInfo['freetext'].'</h2>
												 </div>';
											}
									$content .= '</div>
											</div>
										</div>
									</div>
								</div>';
				}
				///////////////// For Video  Section  /////////////
				if($elementInfo['content_type'] == "video" && $elementInfo['video_url'] != '' && isset($elementInfo['exclude']) && $elementInfo['exclude'] != 1){
					$content .= '<div class="masonry_item col-grid-'.$noOfCols.'">';
					$content .= '<div class="image-outer">
							<style>
							.gallery-plugin .image-inner'.$galleryPostId.' a:hover{
								border-color:#'.$gallery_hover_colour.' !important;														
							}
							</style>
							';
					$videoUrl = $elementInfo['video_url'];
					$sampleUrl = $elementInfo['thumb_url'];
					$sampleUrl = get_image_url_custom($imgWidth,$imgHeight,true,$sampleUrl);
					$content .= '<div class="image-inner image-inner'.$galleryPostId.'">
									<a href="#popup-'.$popupkey.'" style="border-color:#'.$gallery_border_colour.'" class="vsz_btn-popup-gallery item" data-title="'.$elementInfo['video_title'].'" data-desc="'.$elementInfo['video_freetext'].'" title="'.$elementInfo['video_title'].'">
										<img src="'.$sampleUrl.'" alt="Video" /><div class="ovrlay"></div><span class="play-btn"></span>
									</a>
								</div>';
								///////// popup structure
					$content .='<div id="popup-'.$popupkey.'" class="zoom-anim-dialog popup-gallery popup-gallery-video mfp-hide">
									<div class="gallery-pop-up-images">
										<div class="pop-up-images-image">';
											if(!empty($elementInfo['video_title'])){			 
											$content .= ' <div class="title-class">
															<h3>'.$elementInfo['video_title'].'</h3>
														 </div>';
											}
									$content .= '<div>';
								$video = wp_oembed_get($videoUrl);
								if(empty($video)){
									if($elementInfo['video_type'] == "mp4"){
										$video = '<video controls="true" src="'.$videoUrl.'" type=”video/mp4″ id="'.$popupkey.'-video-element">
												<img src="'.dirname(plugin_dir_url(__FILE__)).'/images/video_doesnot_support.jpg" alt="Your Browser does not support video." />
												</video>';
									}
									else{
										$video = '<iframe src="'.$videoUrl.'"></iframe>';
									}
								}
								$content .= $video;
								$content .= '<button title="Close (Esc)" type="button" class="mfp-close">×</button></div>';
								if($elementInfo['video_freetext'] && $elementInfo['video_freetext'] != ''){
								$content .= '<div class="courtsey-class">
												<h2>'.$elementInfo['video_freetext'].'</h2>
											 </div>';
								}
								$content .= '</div>
									</div>
								</div></div></div>';
				}
				
				if($loadmore == $countload ){
				
					break;
				}
				$loadmore++;
			}
			
			/////// Inner forloop ends here  ///////////
			////// display gallery elements ends here  ///////
			}
		}
		else{
			//////// No elements to dislpay
			$content = "No gallery to display!";
		}
	}
	echo $content;
}	
	
	
	?>