<?php
wp_enqueue_style('unite-gallery');
wp_enqueue_script('unitegallery-js');
wp_enqueue_script('ug-theme-tiles-js');

?>
<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#gallery-nested").unitegallery({
				//theme options:					
				theme_enable_preloader: true,		//enable preloader circle
				theme_preloading_height: 300,		//the height of the preloading div, it show before the gallery
				theme_preloader_vertpos: 150,		//the vertical position of the preloader
				theme_gallery_padding: 0,			//the horizontal padding of the gallery from the sides
				theme_appearance_order: "normal",	//normal, shuffle, keep - the appearance order of the tiles. The keep is "keep order"
				theme_auto_open:null,				//auto open lightbox at start - if some number gived, like 0
				
				//gallery options:					
				gallery_theme: "tiles",			//choose gallery theme (if more then one themes includes)
				gallery_width:"100%",				//gallery width
				gallery_min_width: 150,				//gallery minimal width when resizing
				gallery_background_color: "",		//set custom background color. If not set it will be taken from css.
				
				//tiles options:
				tiles_type: "nested",					//must option for the tiles - justified type
				tiles_enable_transition: true,			//enable transition when screen width change
				tiles_space_between_cols:5,			//space between images
				tiles_space_between_cols_mobile:5,     //space between cols for mobile type
				tiles_nested_optimal_tile_width:250,	// tiles optimal width
				tiles_min_columns:2,					//min columns - for mobile size, for 1 column, type 1
				
				//tile design options:															
				tile_enable_border:true,			//enable border of the tile
				tile_border_width:1,				//tile border width
				tile_border_color:"#ff0000",		//tile border color
				tile_border_radius:0,				//tile border radius (applied to border only, not to outline)
				
				tile_enable_outline:false,			//enable outline of the tile (works only together with the border)
				tile_outline_color: "#8B8B8B",		//tile outline color
				
				tile_enable_shadow:false,			//enable shadow of the tile
				tile_shadow_h:1,					//position of horizontal shadow
				tile_shadow_v:1,					//position of vertical shadow
				tile_shadow_blur:3,					//shadow blur
				tile_shadow_spread:2,				//shadow spread
				tile_shadow_color:"#8B8B8B",		//shadow color
				
				tile_enable_action:	true,			//enable tile action on click like lightbox
				tile_as_link: false,				//act the tile as link, no lightbox will appear
				tile_link_newpage: true,			//open the tile link in new page
	
				tile_enable_overlay: true,			//enable tile color overlay (on mouseover)
				tile_overlay_opacity: 0.4,			//tile overlay opacity
				tile_overlay_color: "#000000",		//tile overlay color
				
				tile_enable_icons: true,			//enable icons in mouseover mode
				tile_show_link_icon: false,			//show link icon (if the tile has a link). In case of tile_as_link this option not enabled
				tile_space_between_icons: 26,		//initial space between icons, (on small tiles it may change)
				
				tile_enable_image_effect:false,		//enable tile image effect
				tile_image_effect_type: "bw",		//bw, blur, sepia - tile effect type
				tile_image_effect_reverse: false,	//reverce the image, set only on mouseover state
				
				//tile text panel options:					
				tile_enable_textpanel: true,		 	//enable textpanel
				tile_textpanel_source: "title",		 	//title,desc,desc_title. source of the textpanel. desc_title - if description empty, put title
				tile_textpanel_always_on: false,	 	//textpanel always visible
				tile_textpanel_appear_type: "slide", 	//slide, fade - appear type of the textpanel on mouseover
				tile_textpanel_position:"inside_bottom", //inside_bottom, inside_top, inside_center, top, bottom the position of the textpanel
				tile_textpanel_offset:0,			    //vertical offset of the textpanel
				
				tile_textpanel_padding_top:8,		 	//textpanel padding top 
				tile_textpanel_padding_bottom:8,	 	//textpanel padding bottom
				tile_textpanel_padding_right: 11,	 	//cut some space for text from right
				tile_textpanel_padding_left: 11,	 	//cut some space for text from left
				tile_textpanel_bg_opacity: 0.4,		 	//textpanel background opacity
				tile_textpanel_bg_color:"#000000",	 	//textpanel background color
				tile_textpanel_bg_css:{},			 	//textpanel background css
				
				tile_textpanel_title_color:null,		 //textpanel title color. if null - take from css
				tile_textpanel_title_font_family:null,	 //textpanel title font family. if null - take from css
				tile_textpanel_title_text_align:null,	 //textpanel title text align. if null - take from css
				tile_textpanel_title_font_size:null,	 //textpanel title font size. if null - take from css
				tile_textpanel_title_bold:null,			 //textpanel title bold. if null - take from css
				tile_textpanel_css_title:{},			 //textpanel additional css of the title
	
				tile_textpanel_desc_color:null,			 //textpanel description font family. if null - take from css
				tile_textpanel_desc_font_family:null,	 //textpanel description font family. if null - take from css
				tile_textpanel_desc_text_align:null,	 //textpanel description text align. if null - take from css
				tile_textpanel_desc_font_size:null,		 //textpanel description font size. if null - take from css
				tile_textpanel_desc_bold:null,			 //textpanel description bold. if null - take from css
				tile_textpanel_css_description:{},		 //textpanel additional css of the description
				
				//lightbox options:					
				lightbox_type: "compact",							//compact / wide - lightbox type
									
				lightbox_hide_arrows_onvideoplay: true,			//hide the arrows when video start playing and show when stop
				lightbox_arrows_position: "sides",				//sides, inside: position of the arrows, used on compact type			
				lightbox_arrows_offset: 10,						//The horizontal offset of the arrows
				lightbox_arrows_inside_offset: 10,				//The offset from the image border if the arrows placed inside
				lightbox_arrows_inside_alwayson: false,			//Show the arrows on mouseover, or always on.
				
				lightbox_overlay_color:null,					//the color of the overlay. if null - will take from css
				lightbox_overlay_opacity:0.8,						//the opacity of the overlay. for compact type - 0.6
				lightbox_top_panel_opacity: null,				//the opacity of the top panel
				
				lightbox_close_on_emptyspace:false,				//close the lightbox on empty space
				
				lightbox_show_numbers: true,					//show numbers on the right side
				lightbox_numbers_size: null,					//the size of the numbers string
				lightbox_numbers_color: null,					//the color of the numbers
				lightbox_numbers_padding_top:null,				//the top padding of the numbers (used in compact mode)
				lightbox_numbers_padding_right:null,			//the right padding of the numbers (used in compact mode)
				
				lightbox_slider_image_border: true,				//enable border around the image (for compact type only)
				lightbox_slider_image_border_width: 10,			//image border width
				lightbox_slider_image_border_color: "#ffffff",	//image border color
				lightbox_slider_image_border_radius: 0,			//image border radius
			   
				lightbox_slider_image_shadow: true,				//enable border shadow the image
				
				lightbox_slider_control_swipe:true,				//true,false - enable swiping control
				lightbox_slider_control_zoom:true,				//true, false - enable zooming control
				
				//lightbox text panel:					
				lightbox_show_textpanel: true,						//show the text panel
				lightbox_textpanel_width: 550,						//the width of the text panel. wide type only
				
				lightbox_textpanel_enable_title: true,				//enable the title text
				lightbox_textpanel_enable_description: true,		//enable the description text
				
				lightbox_textpanel_padding_top:5,					//textpanel padding top 
				lightbox_textpanel_padding_bottom:5,				//textpanel padding bottom
				lightbox_textpanel_padding_right: 11,				//cut some space for text from right
				lightbox_textpanel_padding_left: 11,				//cut some space for text from left
	
				lightbox_textpanel_title_color:null,				//textpanel title color. if null - take from css
				lightbox_textpanel_title_font_family:null,			//textpanel title font family. if null - take from css
				lightbox_textpanel_title_text_align:null,			//textpanel title text align. if null - take from css
				lightbox_textpanel_title_font_size:null,			//textpanel title font size. if null - take from css
				lightbox_textpanel_title_bold:null,					//textpanel title bold. if null - take from css
				lightbox_textpanel_css_title:{},					//textpanel additional css of the title
				
				lightbox_textpanel_desc_color:null,					//textpanel description font family. if null - take from css
				lightbox_textpanel_desc_font_family:null,			//textpanel description font family. if null - take from css
				lightbox_textpanel_desc_text_align:null,			//textpanel description text align. if null - take from css
				lightbox_textpanel_desc_font_size:null,				//textpanel description font size. if null - take from css
				lightbox_textpanel_desc_bold:null,					//textpanel description bold. if null - take from css
				lightbox_textpanel_css_description:{},				//textpanel additional css of the description				
				});
		});		
</script>


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

// get id for banner shortcode if define
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
	$content .='<div id="gallery-nested" class="gallery-nested">';
	/// Including class file and function for image regeneration
	require_once dirname(dirname(plugin_dir_path(__FILE__)))."/custom_image/custom_image.php";

	/////// Main loop starts here  ///////////
	foreach($arrgalleryRecords->posts as $key =>$value){
		$arrMeta = get_post_meta($value->ID);
		$galleryPostId = $value->ID;
		$gallery_load_more=$arrMeta['gallery_load_more'][0];
		$gallery_load_more_item = $arrMeta['gallery_load_more_item'][0];
		/////// If dis-played by category than initial parameters will be of category
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
				$gallery_load_more=get_term_meta($term->term_id,'gallery_load_more',true);
				$gallery_load_more_item = get_term_meta($term->term_id,'gallery_load_more_item',true);
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

		// display gallery elements
		if(isset($arr_details) && !empty($arr_details)){
			// display gallery elements start here  ///////
			/////// Inner forloop starts here  ///////////
			$loadmore = 1;	
			foreach($arr_details as $popupkey => $elementInfo){
				$popupkey = $value->ID."-".$popupkey;		/////// Creating a unique popup key to differentiate every popup div
				$temp = 0;
				///////////////// For Image Section  /////////////
				if($elementInfo['content_type'] == "image" && $elementInfo['img_id'] != '' && isset($elementInfo['exclude']) && $elementInfo['exclude'] != 1){
					$temp = 1;
					
								// get custom size image
						$imageURL = wp_get_attachment_image_src($elementInfo['img_id'],'full');    			/// Getting image url
						$imageUrl1 = get_image_url_custom($imgWidth,$imgHeight,true,$imageURL[0]); 			/// Regenerate image as per values defined in admin

							// displaying image
							$content .= '<img alt="'.$elementInfo['img_title'].'"
											 src="'.$imageURL[0].'"
											 data-image="'.$imageURL[0].'"
											 data-description="'.$elementInfo['freetext'].'">';
												}
				///////////////// For Video  Section  /////////////
				if($elementInfo['content_type'] == "video" && $elementInfo['video_url'] != '' && isset($elementInfo['exclude']) && $elementInfo['exclude'] != 1){
					$temp = 1;
					$videoUrl = $elementInfo['video_url'];
					$sampleUrl = $elementInfo['thumb_url'];
					$sampleUrl = get_image_url_custom($imgWidth,$imgHeight,true,$sampleUrl);

						 
						 // If YouTube Video
						$yt_rx = '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/';
					    $has_match_youtube = preg_match($yt_rx, $videoUrl, $yt_matches);
						if($has_match_youtube) {
							$video_id = $yt_matches[5]; 
							$type = 'youtube';							
							$content .= '<img alt="'.$elementInfo['video_title'].'"
											 data-type="'.$type.'"
											 src="'.$sampleUrl.'"
											 data-image="'.$sampleUrl.'"
											 data-videoid="'.$video_id.'"
											 data-description="'.$elementInfo['video_freetext'].'">';
						}
						
						// If Vimeo Video
						$vm_rx = '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([‌​0-9]{6,11})[?]?.*/';
					    $has_match_vimeo = preg_match($vm_rx, $videoUrl, $vm_matches);
						if($has_match_vimeo) {
							$video_id = $vm_matches[5]; 
							$type = 'vimeo';
							$content .= '<img alt="'.$elementInfo['video_title'].'"
											 data-type="'.$type.'"
											 src="'.$sampleUrl.'"
											 data-image="'.$sampleUrl.'"
											 data-videoid="'.$video_id.'"
											 data-description="'.$elementInfo['video_freetext'].'">';
						}
						
						 // HTML 5 Video
						 if($elementInfo['video_type'] == "mp4"){
						 $content .='<img alt="'.$elementInfo['video_title'].'"
							 src="'.$sampleUrl.'"
							 data-type="html5video"
							 data-image="'.$sampleUrl.'"
							 data-videomp4="'.$videoUrl.'"
							 data-description="'.$elementInfo['video_freetext'].'">';
						 }
					
								
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
	
		
		///// CHecking constant. Thus restricted to include script more than one.
		if(!defined('VSZ_GALLERY_SHORTCODE_JS_INCLUDED')){
		
		///// Defining constant as a script is included once. Thus restricted to include script more than one.
		define('VSZ_GALLERY_SHORTCODE_JS_INCLUDED',"YES");
	}
	$content .= '</div>';
}
//var_dump($content);exit;
if(isset($content_empty)){
	return $content_empty ;  // Return variable to display html
}
else{
	return $content ;  // Return variable to display html
}
