<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>

<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/*
** Parameters used in this file
noOfCols -> admin option value of number of columns to display in a row
galleryPostId -> post id which post is currently displayed (Can be change from loop)
galleryCategoryName -> If displayed by category than name of that category
categoryWiseDisplay -> Defines display by category or post
gallery_border_colour -> Admin option value of gallery div border color
gallery_hover_colour -> Admin option value of gallery div hover color
imgWidth -> Admin option value of gallery image regeneration image width
imgHeight -> Admin option value of gallery image regeneration image height 
*/

global $wpdb;
// get id for responsive media gallery shortcode if define
$arrInfo = shortcode_atts( array( 'id' => '','category' => '' ),$atts );
$galleryPostId = $arrInfo['id'];
$galleryCategoryName = '';
$galleryCategoryName = $arrInfo['category'];
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
	///// Main output starts here  ///////
	$content .='<div class="rmg_wrap">
					<div class="gallery-plugin">
						<div class="gallery-outer magnific-all">
							<div class="row">';
	/// Including class file and function for image regeneration
	require_once dirname(dirname(plugin_dir_path(__FILE__)))."/custom_image/custom_image.php";
	
	/////// Main loop starts here  ///////////
	foreach($arrgalleryRecords->posts as $key =>$value){
		$arrMeta = get_post_meta($value->ID);
		$galleryPostId = $value->ID;
		$gallery_load_more=$arrMeta['gallery_load_more'][0];
		$gallery_load_more_item = $arrMeta['gallery_load_more_item'][0];
		/////// If dis-played by category than initial parameters will be from category
		if($categoryWiseDisplay){
			$term = get_term_by( 'slug', $galleryCategoryName, 'vsz_cat' );
			if(isset($term->term_id)){
				///// Getting initial parameters
				$gallery_border_colour = get_term_meta($term->term_id,'gallery_border_colour',true);
				$gallery_hover_colour = get_term_meta($term->term_id,'gallery_hover_colour',true);
				$imgWidth = get_term_meta($term->term_id,'gallery_img_width',true);
				$gallery_load_more=get_term_meta($term->term_id,'gallery_load_more',true);
				$gallery_load_more_item = get_term_meta($term->term_id,'gallery_load_more_item',true);
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
			$loadmore = 1;
			foreach($arr_details as $popupkey => $elementInfo){
				$popupkey = $value->ID."-".$popupkey;				/////// Creating a unique popup key to differentiate every popup div
				///////////////// For Image Section  /////////////
				$temp = 0;
				if(isset($elementInfo['content_type']) && $elementInfo['content_type'] == "image" && !empty($elementInfo['img_id']) && isset($elementInfo['exclude']) && $elementInfo['exclude'] != 1){
					$temp = 1;
					$content .= '<div class="col-grid-'.$noOfCols.' sel-equal-box">';
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
												<img data-id=""  src="'.$imageUrl1.'" alt="'.$elementInfo['img_title'].'" />
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
												if($elementInfo['freetext'] && $elementInfo['freetext'] !=''){
										///// Text for this image is added
										$content .= '<div class="courtsey-class">
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
				if(isset($elementInfo['content_type']) && $elementInfo['content_type'] == "video" && !empty($elementInfo['video_url']) && isset($elementInfo['exclude']) && $elementInfo['exclude'] != 1){
					$temp = 1;
					$content .= '<div class="col-grid-'.$noOfCols.' sel-equal-box">';
						$content .= '<div class="image-outer">
										<!-- style is for hover color -->
										<style>
										.gallery-plugin .image-inner'.$galleryPostId.' a:hover{
											border-color:#'.$gallery_hover_colour.' !important;														
										}
										</style>
										';
					$videoUrl = $elementInfo['video_url'];										/// Getting video url
					$sampleUrl = $elementInfo['thumb_url'];	
					//// Set default image if thumbnail is not added
					if(empty($sampleUrl)){
						$sampleUrl = dirname(dirname(plugin_dir_url(__FILE__)))."/admin/images/Video.jpg";
					}
					/// Getting thumb image url
					$sampleUrl = get_image_url_custom($imgWidth,$imgHeight,true,$sampleUrl);	/// Regenerate image as per
							$content .= '<div class="image-inner image-inner'.$galleryPostId.'">
											<a href="#popup-'.$popupkey.'" style="border-color:#'.$gallery_border_colour.'" class="vsz_btn-popup-gallery item" data-title="'.$elementInfo['video_title'].'" data-desc="'.$elementInfo['video_freetext'].'" title="'.$elementInfo['video_title'].'">
												<img src="'.$sampleUrl.'" alt="Video" />
												<div class="ovrlay"></div>
												<span class="play-btn"></span>
											</a>
										</div>';
							///////// popup structure for video
							$content .='<div id="popup-'.$popupkey.'" class="zoom-anim-dialog popup-gallery popup-gallery-video mfp-hide">
											<div class="gallery-pop-up-images">
												<div class="pop-up-images-image">';
									if(!empty($elementInfo['video_title'])){
										//// Title for this video is inserted
										$content .= '<div class="title-class">
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
									///// Displaying popup close button
									$content .= '<button title="Close (Esc)" type="button" class="mfp-close">×</button>
											</div>';
								if($elementInfo['video_freetext'] && $elementInfo['video_freetext'] !=''){
									//// Text for this video is inserted
									$content .= '<div class="courtsey-class">
												<h2>'.$elementInfo['video_freetext'].'</h2>
											</div>';
								}
									$content .= '</div>
											</div>
										</div>
									</div>
								</div>';
				}
				if($gallery_load_more == 'yes'){
					if($loadmore == $gallery_load_more_item){
						break;
					}
					if($temp == 1){
						$loadmore++;
					}	
				}
			}
			/////// Inner forloop ends here  ///////////
			////// display gallery elements ends here  ///////
		}
		else{
			//////// No elements to dislpay
			$content_empty = "No gallery to display!";
		}
	}
	$content .= '</div>
			</div>
			<input type="hidden" name="totalExistImage" id="totalExistImage" value="6">
		</div>';
		if($gallery_load_more == 'yes'){
			if($loadmore == $gallery_load_more_item){
					$content .='<div class="load-more">
						<button id="loading">Load More</button>
					</div>';
					}
				}
		
	///// CHecking constant. Thus restricted to include script more than one.
	if(!defined('VSZ_GALLERY_SHORTCODE_JS_INCLUDED')){
		/* This will be useful when pagination or load more functionality will be added
		$content .= '<div class="list-ajax" style="display:none">
						<div class="inner-ajax">
							<img alt="Loading..." src="'.plugin_dir_url(dirname(dirname(__FILE__))).'upload/load4.gif'.'" height="100" width="100">
						</div>
					</div>';*/
		$content .= '<script type="text/javascript">';
		// $buttonString = "<button title='%title%' type='button' class='rmg-arrow rmg-arrow-%dir%' ><span></span></button>";
		$buttonString = "<button title='%title%' type='button' class='gallry-btn mfp-arrow mfp-arrow-%dir%' ></button>";
		$content .= 'var galleryFlag = true;
		jQuery(document).ready(function(){
			// window.onload = jQuery("#totalExistImage").val(6);';
			// getting initial parameters
			if(!empty($arrJS['gallery_js_option_fixedContentPosition'])){ $gallery_js_option_fixedContentPosition = $arrJS['gallery_js_option_fixedContentPosition']; } else{ $gallery_js_option_fixedContentPosition = 'false'; }
			if(!empty($arrJS['gallery_js_option_fixedBgPosition'])){ $gallery_js_option_fixedBgPosition = $arrJS['gallery_js_option_fixedBgPosition']; } else{ $gallery_js_option_fixedBgPosition = 'auto'; }
			if(!empty($arrJS['gallery_js_option_overflowY'])){ $gallery_js_option_overflowY = $arrJS['gallery_js_option_overflowY']; } else{ $gallery_js_option_overflowY = 'auto'; }
			// setting these parameters
	$content .= '
			jQuery(".vsz_btn-popup-gallery").magnificPopup({
				closeOnBgClick: true,
				fixedContentPos: "'.$gallery_js_option_fixedContentPosition.'",
				fixedBgPos: "'.$gallery_js_option_fixedBgPosition.'",
				overflowY: "'.$gallery_js_option_overflowY.'",
				mainClass: "mfp-fade vsz_fade",
				type:"inline",
				cursor: "mfp-pointer-cur",
				gallery:{
						enabled:true,
						navigateByImgClick: true,
						tPrev: "Previous",
						tNext: "Next",
						arrowMarkup: "'.$buttonString.'" // markup of an arrow button
				  },
				callbacks: {
					buildControls: function() {
					  // re-appends controls inside the main container
					  this.contentContainer.append(this.arrowLeft.add(this.arrowRight));
					},
					change: function() {
						///// Paise video code
						jQuery(".popup-gallery-video").find("video").each(function(){
							// checking the browser. If not safari than apply code to pause the video
							var userAgent = navigator.userAgent.toLowerCase();
							var userPlatform = navigator.platform;
							if (userAgent.indexOf("safari")== -1){
								/// Execute for other than safari browsers
								jQuery(this)[0].pause();
							}
							else{
								/// Execute for safari browsers
								if(userPlatform.indexOf("Win") == -1){
									//// Other than windows platform
									jQuery(this)[0].pause();
								}
							}
						});
					},
					close: function(){
						///// Stop video code
						jQuery(".popup-gallery-video").find("video").each(function(){
							// jQuery(this)[0].pause();
							// checking the browser. If not safari than apply code to pause the video
							var userAgent = navigator.userAgent.toLowerCase();
							var userPlatform = navigator.platform;
							if (userAgent.indexOf("safari")== -1){
								/// Execute for other than safari browsers
								jQuery(this)[0].pause();
								jQuery(this)[0].currentTime = 0;
							}
							else{
								/// Execute for safari browsers
								if(userPlatform.indexOf("Win") == -1){
									//// Other than windows platform
									jQuery(this)[0].pause();
									jQuery(this)[0].currentTime = 0;
								}
							}
						});
					}
				}
			});
			
			 equalheight(".sel-equal-box");
			jQuery(window).load(function(){
				equalheight(".sel-equal-box");
			});
			jQuery(window).resize(function(){
				equalheight(".sel-equal-box");
			});
			var totalimage = '.sizeof($arr_details).';
			var loadmore = '.$gallery_load_more_item.';
			jQuery("#loading").click(function(){
					baseUrl = "'.plugin_dir_url(__FILE__).'"+"load_more.php";
					var postId = "'.$galleryPostId.'";
					var galleryCategoryName = "'.$galleryCategoryName.'";
					var ajax_nonce = "'.wp_create_nonce( "post_taxonomy_nonce" ).'";
					var ajaxurl = "'.admin_url( 'admin-ajax.php' ).'";
					
					 var loadmoreitem = '.$gallery_load_more_item.';
					jQuery.ajax({
						url: ajaxurl, 
						type: "POST",
						data: {"layout":"defult",
								"load_more":loadmore,
								"postId":postId,
								"galleryCategoryName":galleryCategoryName,
								"action":"load_more_images",
								"ajax_nonce":ajax_nonce,
								"loadmoreitem":loadmoreitem},
						success: function(data){
							jQuery(".gallery-outer .row").append(data);
							loadmore= loadmore + loadmoreitem;
							equalheight(".sel-equal-box");
							setTimeout(function(){
								equalheight(".sel-equal-box");
							},1000);
							jQuery(".gallery-outer").imagesLoaded()
							  .done( function( instance ) {
								console.log("all images successfully loaded");
								equalheight(".sel-equal-box");
							  });
							jQuery(".vsz_btn-popup-gallery").magnificPopup({
				closeOnBgClick: true,
				fixedContentPos: "'.$gallery_js_option_fixedContentPosition.'",
				fixedBgPos: "'.$gallery_js_option_fixedBgPosition.'",
				overflowY: "'.$gallery_js_option_overflowY.'",
				mainClass: "mfp-fade vsz_fade",
				type:"inline",
				cursor: "mfp-pointer-cur",
				gallery:{
						enabled:true,
						navigateByImgClick: true,
						tPrev: "Previous",
						tNext: "Next",
						arrowMarkup: "'.$buttonString.'" // markup of an arrow button
				  },
				callbacks: {
					buildControls: function() {
					  // re-appends controls inside the main container
					  this.contentContainer.append(this.arrowLeft.add(this.arrowRight));
					},
					change: function() {
						///// Paise video code
						jQuery(".popup-gallery-video").find("video").each(function(){
							// checking the browser. If not safari than apply code to pause the video
							var userAgent = navigator.userAgent.toLowerCase();
							var userPlatform = navigator.platform;
							if (userAgent.indexOf("safari")== -1){
								/// Execute for other than safari browsers
								jQuery(this)[0].pause();
							}
							else{
								/// Execute for safari browsers
								if(userPlatform.indexOf("Win") == -1){
									//// Other than windows platform
									jQuery(this)[0].pause();
								}
							}
						});
					},
					close: function(){
						///// Stop video code
						jQuery(".popup-gallery-video").find("video").each(function(){
							// jQuery(this)[0].pause();
							// checking the browser. If not safari than apply code to pause the video
							var userAgent = navigator.userAgent.toLowerCase();
							var userPlatform = navigator.platform;
							if (userAgent.indexOf("safari")== -1){
								/// Execute for other than safari browsers
								jQuery(this)[0].pause();
								jQuery(this)[0].currentTime = 0;
							}
							else{
								/// Execute for safari browsers
								if(userPlatform.indexOf("Win") == -1){
									//// Other than windows platform
									jQuery(this)[0].pause();
									jQuery(this)[0].currentTime = 0;
								}
							}
						});
					}
				}
			});
							if(loadmore >= totalimage){
								jQuery(".load-more").hide();
							}
							console.log(loadmore);
							console.log(totalimage);
							
						}
					});	
				});			
		});
		
		/********* equalHeight *******************/
		equalheight = function(container){
			var currentTallest = 0,
				currentRowStart = 0,
			rowDivs = new Array(),
						$el,
			topPosition = 0;
			jQuery(container).each(function() {
				$el = jQuery(this);
				jQuery($el).height("auto");
				topPostion = $el.position().top;
				if (currentRowStart != topPostion) {
					for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
						rowDivs[currentDiv].height(currentTallest);
					}
					rowDivs.length = 0; // empty the array
					currentRowStart = topPostion;
					currentTallest = $el.height();
					rowDivs.push($el);
				} else {
					rowDivs.push($el);
					currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
				}
				for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
					rowDivs[currentDiv].height(currentTallest);
				}
			});
		}
		</script></div>';
		///// Defining constant as a script is included once. Thus restricted to include script more than one.
		define('VSZ_GALLERY_SHORTCODE_JS_INCLUDED',"YES");
		
	}
	$content .= '</div>';
	///// Main output ends here  ///////
}
if(isset($content_empty)){
	return $content_empty ;  // Return variable to display html
}
else{
	return $content ;  // Return variable to display html
}