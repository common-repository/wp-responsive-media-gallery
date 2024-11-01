<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
wp_enqueue_script('Sortable');
wp_enqueue_style('responsive-media-gallery');
$arrMeta = get_post_meta($post->ID);

$arrSecDetails = array();

/// Check if custom meta exists
if(isset($arrMeta['gallery_image_details']) && !empty($arrMeta['gallery_image_details'][0])){

	// unserialize the meta value to convert string into array
	$arrSecDetails = maybe_unserialize($arrMeta['gallery_image_details'][0]);
	
	// Checking for
	$numberOfLoop = count ($arrSecDetails);
	if($numberOfLoop>0){
		foreach($arrSecDetails as $i=>$value){
			if(isset($arrSecDetails[$i]['img_title'])){
				$arrSecDetails[$i]['img_title'] = str_replace("\\\\'","\\'",$arrSecDetails[$i]['img_title']);
			}
			if(isset($arrSecDetails[$i]['img_title'])){
				$arrSecDetails[$i]['img_title'] = str_replace("\\\\\"","\\\"",$arrSecDetails[$i]['img_title']);
			}
			if(isset($arrSecDetails[$i]['freetext'])){
				$arrSecDetails[$i]['freetext'] = str_replace("\\\\'","\\'",$arrSecDetails[$i]['freetext']);
			}
			if(isset($arrSecDetails[$i]['freetext'])){
				$arrSecDetails[$i]['freetext'] = str_replace("\\\\\"","\\\"",$arrSecDetails[$i]['freetext']);
			}

			if(isset($arrSecDetails[$i]['video_title'])){
				$arrSecDetails[$i]['video_title'] = str_replace("\\\\'","\\'",$arrSecDetails[$i]['video_title']);
			}
			if(isset($arrSecDetails[$i]['video_title'])){
				$arrSecDetails[$i]['video_title'] = str_replace("\\\\\"","\\\"",$arrSecDetails[$i]['video_title']);
			}
			if(isset($arrSecDetails[$i]['video_freetext'])){
				$arrSecDetails[$i]['video_freetext'] = str_replace("\\\\'","\\'",$arrSecDetails[$i]['video_freetext']);
			}
			if(isset($arrSecDetails[$i]['video_freetext'])){
				$arrSecDetails[$i]['video_freetext'] = str_replace("\\\\\"","\\\"",$arrSecDetails[$i]['video_freetext']);
			}
		}
	}
}

$maxCount = 0;
if(!empty($arrSecDetails)){
	$maxCount = max(array_keys($arrSecDetails));   /// Getting the number of maximum key for already added gallery elements
}
?><style>
.time-table{
	padding:5px;
	margin:5px 0px;
	background-color:darkgray;
	font-size:15px;
	font-style:sans-serif,arial;
}
.save_image {
    display: inline-block;
    vertical-align: middle;
}
</style>
<!--  Save dom count for js -->
<input type="hidden" id="domCount" value="<?php echo $maxCount; ?>">
<!-- Main display starts form here -->
	<!-- Top section with buttons starts here -->
<div class="top-form-gallery" id="addImageForm">
	<div class="title-form">
		<div class="row">
			<div class="span12">
				<!--  Initial display section -->
                <div class="span4">
					<a class="save_image img" href="#add_image_single" title="Add Image">
						<button title="Add Image" class="button button-primary"><?php echo __('Add Image');?> <i class="fa fa-file-image-o" aria-hidden="true"></i></button>
					</a>
                </div>
                <div class="span4">
					<a class="save_image imgs" href="#add_image_multiple" title="Add Images">
						<button title="Add Images" class="button button-primary"><?php echo __('Add Multiple Images');?> <i class="fa fa-file-image-o" aria-hidden="true"></i> <i class="fa fa-file-image-o" aria-hidden="true"></i></button>
					</a>
                </div>
                <div class="span4">
					<a class="save_image vdo" href="#add_video_single" title="Add Video">
						<button title="Add Video" class="button button-primary"><?php echo __('Add Video');?> <i class="fa fa-video-camera" aria-hidden="true"></i></button>
					</a>
                </div>
			</div>

		</div>

	</div>
</div>
<!-- Top section with buttons ends here -->
<!-- This is for loader image starts  -->
<div class="loader_image" style="display:none;">
	<div class="inner-load">
	<img id="loadingImage" src="<?php echo dirname(plugin_dir_url(__FILE__)); ?>/images/loading.gif" alt="Loading..." />
    </div>
</div>
<!-- This is for loader image ends  -->

<!-- This is for delete functionality and dom functionality -->
<div id="add-gallery">
	<!--  Delete options  -->
	<div class="deleteSection">
		<label class="select-delete-label"><input type="checkbox" class="headCheck" title="Select All" /><?php echo __('Select All');?></label>
		<a title="Delete Selected" class="deleteSelectedButton select-delete" ><?php echo __('Delete Selected');?></a>
		<?php
		// Create an nonce for a hidden field.
		// The target page will perform some action based on the 'do_something' parameter.
		$nonce = wp_create_nonce( 'save_post_gallery' );
		?><input type="hidden" value="<?php echo $nonce; ?>" name="my_testing_nonce" />
	</div>
</div>

<!-- Popup starts here -->
<div class="mfp-hide"><!-- This div is to hide at load time -->

	<!-- Popup part of gallery element (Image) starts here  -->
	<div id="edit_popup-image" class="zoom-anim-dialog popup-div mfp-hide vsz-admin-popup">
		<div>

			<table class="form-table">
				<tr>
					<th>
						<label for="img_title-customId"><?php echo __('Title');?></label>
					</th>
					<td>
						<input type="text" autocomplete="off" name="img_title_new[]" class="edit_title backslashNotAllowed" id="img_title-customId" value="edit_image_title">
					</td>
				</tr>
				<tr>
					<th>
						<label for="image"><?php echo __('Image');?></label>
					</th>
					<td>
						<div class="class-btn">
							<div class="gall_input_image" id="defaultimage">
								<span class="file_url" ></span><br>
								<input id="singleImageUpload" type="button" style="margin-top:10px; display:none;" value="Add Image" class="media button add button button-primary">
								<input type="button" value="Remove" id="removeImageButton" class="media button remove hidden remove-btn-image utton button-primary ">
								<input type="hidden" value="" class="file_value" name="imageid">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th>
						<label for="free_text"><?php echo __('Free Text');?></label>
					</th>
					<td>
						<input type="text" autocomplete="off" name="free_text_new[]" id="free_text-customId" class="edit_free_text backslashNotAllowed" value="edit_image_freetext">
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<input type="button" name="save_detail" class="button button-primary" id="save_detail" value="Update" onclick="updateImageDetail(customId)">
					</td>
				</tr>
			</table>
		</div>
	</div>
	<!-- Popup part of gallery element (Image) ends here  -->

	<!-- Popup part of gallery element  (Video) start here  -->
	<div id="edit_popup-video" class="zoom-anim-dialog popup-div mfp-hide vsz-admin-popup">
		<div>

			<table class="form-table">
				<tr>
					<th>
						<label for="video_title-customId"><?php echo __('Title');?></label>
					</th>
					<td>
						<input type="text" autocomplete="off" name="video_title_new[]" class="edit_title backslashNotAllowed" id="video_title-customId" value="edit_video_title">
					</td>
				</tr>
				<tr class="urlSec">
					<th>
						<label for="Video"><?php echo __('Video URL');?></label>
					</th>
					<td>
						<input type="text" Placeholder="Enter video url" class="video_url_to_insert" name="video_url_new[]" />
						<div class="display_video_iframe" ></div>
						<input type="hidden" value="video" name="content_type" />
					</td>
				</tr>
				<tr class="orSec">
					<th colspan="2">
						<?php echo __('OR');?>
					</th>
				</tr>
				<tr class="uploadSec">
					<th>
						<label for="video"><?php echo __('Upload Video');?></label>
					</th>
					<td>
						<div class="class-btn">
							<div class="gall_input_video" id="defaultvideo">
								<span class="file_url" ></span><br>
								<input id="singleVideoUploadEdit" type="button" style="margin-top:-15px;" value="Add Video" class="media button add button button-primary">
								<input type="button" value="Remove" id="removeVideoButton" class="media button remove hidden remove-btn-image">
								<input type="hidden" value="" class="file_value" name="videourl">
								<input type="hidden" value="" class="file_type" name="videotype">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th><label><?php echo __('Thumbnail Image');?></label></th>
					<td>
						<div class="gall_input_thumb" id="defaultThumb">
							<span class="thumb_url_edit" ></span><br>
							<input id="singleThumbUploadEdit" type="button" style="margin-top:-15px;" value="Add Thumbnail" class="media button add button button-primary">
							<input type="button" value="Remove" id="removeThumbButtonEdit" class="media button remove hidden remove-btn-image">
							<input type="hidden" value="" class="thumb_value_edit" name="thumburl" />
							<input type="hidden" value="" class="thumb_id_edit" name="thumbtype" />
						</div>
					</td>
				</tr>
				<tr>
					<th>
						<label for="video_freetext"><?php echo __('Free Text');?></label>
					</th>
					<td>
						<input type="text" autocomplete="off" name="video_freetext_new[]" id="video_freetext-customId" class="backslashNotAllowed" value="edit_video_freetext">
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<input type="button" name="save_detail" class="button button-primary" id="save_detail" value="Update" onclick="updateVideoDetail(customId)">
					</td>
				</tr>
			</table>
		</div>
	</div>
	<!-- Popup part of gallery element (Video) ends here  -->

	<!--	Div structure for image section starts	-->
	<div id="append-image-detail">
		<div class="image-inner"><!-- Display part of gallery element -->
			<div class="head-checkbox">
				<input type="checkbox" class="singleCheck" id="sec-customId" />
			</div>
			<span class="image-title"></span>
			<span class="image-info"></span>
			<span class="image-free_text"></span>
			<input type="hidden" name="detailSection[customId][img_title]" id="update-image-title-customId" value="custom-img_title" >
			<input type="hidden" name="detailSection[customId][content_type]" id="update-content_type-customId" value="custom-content_type" >
			<input type="hidden" name="detailSection[customId][img_id]" id="update-image-id-customId" value="custom-img_id">
			<input type="hidden" name="detailSection[customId][freetext]" id="update-image-freetext-customId" value="custom-img_freetext">
			<div class="admin-btn-edlt">
            	<a href="#" title="Delete">
               		<button title="Delete" class="button button-primary" onclick=" return deleteImage(customId)"><i class="fa fa-trash"></i></button>
				</a>
            </div>
            <div class="admin-btn-edlt">
				<a class="btn-popup1 openImagePopup edit_popup-customId" href="#edit_popup-image" title="Edit">
					<button title="edit" class="bitton-primary"><i class="fa fa-edit" aria-hidden="true"></i></button>
				</a>
            </div>
		</div>
	</div>
	<!--	Div structure for image section ends	-->
	<!--	Div structure for video section starts	-->
	<div id="append-video-detail">
		<div class="video-inner">
			<div class="head-checkbox">
				<input type="checkbox" class="singleCheck" id="sec-customId" />
			</div>
			<span class="video-title"></span>
			<span class="video-info"></span>
			<span class="video-free_text"></span>
			<input type="hidden" name="detailSection[customId][video_title]" id="update-video-title-customId" value="custom-video_title" >
			<input type="hidden" name="detailSection[customId][content_type]" id="update-content_type-customId" value="custom-content_type" >
			<input type="hidden" name="detailSection[customId][video_url]" id="update-video-id-customId" value="custom-video_url">
			<input type="hidden" name="detailSection[customId][video_type]" id="update-video-type-customId" value="custom-video_type">
			<input type="hidden" name="detailSection[customId][thumb_url]" id="update-thumb-url-customId" value="custom-thumb_url">
			<input type="hidden" name="detailSection[customId][thumb_id]" id="update-thumb-id-customId" value="custom-thumb_id">
			<input type="hidden" name="detailSection[customId][video_freetext]" id="update-video-freetext-customId" value="custom-video_free_text">
			<div class="admin-btn-edlt">
            	<a href="#" title="Delete">
               		<button title="Delete" class="button button-primary" onclick="return deleteImage(customId)"><i class="fa fa-trash"></i></button>
				</a>
            </div>
            <div class="admin-btn-edlt">
				<a class="btn-popup1 openVideoPopup edit_popup-customId" href="#edit_popup-video" title="Edit">
					<button title="edit" class="bitton-primary"><i class="fa fa-edit" aria-hidden="true"></i></button>
				</a>
            </div>
		</div>
	</div>
	<!--	Div structure for video section ends	-->
</div>
<!-- Popup ends here -->
<!--  Add Image Popup Starts Here  -->
<div id="add_image_single" class="zoom-anim-dialog popup-div mfp-hide vsz-admin-popup">
	<div>
		<table class="form-table">
			<tr>
				<th>
					<label for="img_title-customId"><?php echo __('Title');?></label>
				</th>
				<td>
					<input name="img_title" class="backslashNotAllowed" id="img_title" autocomplete="off" value="" type="text">
				</td>
			</tr>
			<tr>
				<th>
					<label for="image"><?php echo __('Image');?></label>
				</th>
				<td>
					<div class="class-btn_single">
						<span class="file_url_single_image" ></span>
						<div class="gall_input_image" id="defaultimage">
							<input id="singleImageUpload" type="button" style="margin-top:10px;" value="Add Image" class="media button button-primary">
							<input type="button" value="Remove" id="removeImageButton" class="media button remove_single_image hidden remove-btn-image">
							<input type="hidden" value="" class="file_value_single" name="imageid">
							<input type="hidden" value="image" name="content_type">
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for="free_text"><?php echo __('Free Text');?></label>
				</th>
				<td>
					<input type="text" name="free_text" class="backslashNotAllowed" id="free_text" autocomplete="off" value="">
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<input type="button" name="save_Image" class="button button-primary" id="save_Image" value="Save Image" onclick="addImageDetail();">
				</td>
			</tr>
		</table>
	</div>
</div>
<!--  Add Image Popup Ends Here  -->
<!--  Add Image Multiple Popup Starts Here  -->
<div id="add_image_multiple" class="zoom-anim-dialog popup-div mfp-hide vsz-admin-popup">
	<div>
		<table class="form-table">
			<tr class="good-structure">
				<td>
					<label for="img_title" class="good-label"><?php echo __('Images');?></label>
				</td>
			</tr>
			<tr>
					<td><input type="text" id="imageTitleForMultiple" placeholder="Title" /></td>
			</tr>
			<tr>
				<td><input type="text" id="imageFreetextForMultiple" placeholder="Free Text" /></td>
			</tr>
            <tr class="uniq-tr">
				<td>
					<div class="span12" id="multipleImageSection">
						<div class="class-btn">
							<span class="file_url_multiple_image" >
								<div class="appendImagesHere"></div>
							</span>
							<div class="gall_input_image" id="defaultimage">
								<input id="multipleImageUpload" type="button" style="margin-top:10px;" value="Add Images" class="media button button-primary">
								<input type="hidden" value="" class="numberOfElements" name="imageid_multiple">
								<input type="hidden" value="" class="numberOfElementsToDisplay" name="imageid_multiple_to_display">
								<input type="hidden" value="image" name="content_type">
							</div>
						</div>
					</div>
				</td>
			</tr>
			<tr class="uniq-tr">
				<td class="multiple-btn">
					<input type="button" name="save_Images" class="button button-primary" id="save_Images" value="Save Images" onclick="addImageMultiple();">
				</td>
			</tr>
		</table>
	</div>
</div>
<!--  Add Image Multiple Popup Ends Here  -->
<!--  Add Video Popup Starts Here  -->
<div id="add_video_single" class="zoom-anim-dialog popup-div mfp-hide vsz-admin-popup">
	<div>
		<table class="form-table">
			<tr>
				<th>
					<label for="video_title-customId"><?php echo __('Title');?></label>
				</th>
				<td>
					<input name="video_title" class="backslashNotAllowed" id="video_title" autocomplete="off" value="" type="text">
				</td>
			</tr>
			<tr class="urlSec">
				<th>
					<label for="Video"><?php echo __('Video Url');?></label>
				</th>
				<td>
					<input type="text" Placeholder="Enter video url" class="video_url_to_insert" />
					<input type="hidden" value="video" name="content_type" />
				</td>
			</tr>
			<tr class="orSec"><th colspan="2"><?php echo __('Or');?></th></tr>
			<tr class="buttonSec">
				<th>
					<label for="image"><?php echo __('Upload Video');?></label>
				</th>
				<td>
					<div class="class-btn_single_video">
						<span class="file_url_single_video"></span>
						<div class="clear"></div>
						<div class="gall_input_video" id="defaultimage">
							<input id="singleVideoUpload" type="button" style="margin-top:10px;display: inline-block;" value="Add Video" class="media button button-primary">
							<input type="button" value="Remove" id="removeVideoButton" class="media button remove_single_video hidden btn-red">
							<input type="hidden" value="" class="file_value_single" name="video_url">
							<input type="hidden" value="image" name="content_type">
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th><label><?php echo __('Thumbnail Image');?></label></th>
				<td>
					<div class="file_url_single_thumb"></div>
					<input id="singleThumbUpload" type="button" style="margin-top:10px;display: inline-block;" value="Add Thumbnail" class="media button button-primary">
					<input type="button" value="Remove" id="removeThumbButton" class="media button remove_single_thumb hidden remove-btn-image">
					<input type="hidden" name="thumb_id" class="thumb_id" />
					<input type="hidden" name="thumb_url" class="thumb_url" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="video_free_text"><?php echo __('Free Text');?></label>
				</th>
				<td>
					<input type="text" name="video_free_text" class="backslashNotAllowed" id="video_free_text" autocomplete="off" value="">
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<input type="button" name="save_video" class="button button-primary" id="save_video" value="Add Video" onclick="addVideoSingle();">
				</td>
			</tr>
		</table>
	</div>
</div>
<!--  Add Video Popup Ends Here  -->
<!-- All dom structure will be appended here -->
<div class="vsz-listing" id="simpleList">

</div>
<!-- Main display ends form here -->
<script type="text/javascript">
//Delete image functionality
// deletediv is the current button object
function delete_image(deletediv){
	if (confirm("Are you sure about delete 'Detail Section'?") == true) {
		jQuery(deletediv).parent().parent().remove();
	}
	return false;
}

jQuery(document).ready(function(jQuery) {

	//////// Sorting functionality
	var container = document.getElementById("simpleList");
	Sortable.create(container, {group: 'shared',handle:'.image'});
	//on publish change name
	//////// Saving process starts from here  ////////////////////////////
	/*
	** This function will change the name property of every gallery element when publish button clicked.
	*/
	var counter = 1;
	jQuery("#publish").click(function(){
		jQuery("body").find(".gallery-item").each(function(){
			/// getting parameters
			var imageid = jQuery(this).find('.gall_input_image .file_value').val();
			var title = jQuery(this).find('.vsz-title').val();
			var description = jQuery(this).find('.vsz-description').val();
			var contenttype = jQuery(this).find('.content-type').val();

			/////// If this instance is for image section
			if(contenttype == 'image'){
				if(imageid != '' || title != '' || description != ''){
					jQuery(this).find('.vsz-title').attr('name','detailSection['+counter+'][img_title]');
					jQuery(this).find('.vsz-description').attr('name','detailSection['+counter+'][freetext]');
					jQuery(this).find('.file_value').attr('name','detailSection['+counter+'][img_id]');
					jQuery(this).find('.content-type').attr('name','detailSection['+counter+'][content_type]');
					var exclude=jQuery(this).find('.exclude').prop("checked");

					if(exclude != true){
						jQuery(this).find('.exclude').after('<input type="hidden" name="detailSection['+counter+'][exclude]" value="0" />')

					}else{
					jQuery(this).find('.exclude').attr('name','detailSection['+counter+'][exclude]');
					}
					counter++;
				}

			}

			/////// If this instance is for video section
			if(contenttype == 'video'){
				jQuery(this).find('.vsz-title').attr('name','detailSection['+counter+'][video_title]');
				jQuery(this).find('.vsz-description').attr('name','detailSection['+counter+'][video_freetext]');
				jQuery(this).find('.content-type').attr('name','detailSection['+counter+'][content_type]');
				jQuery(this).find('.file_type').attr('name','detailSection['+counter+'][video_type]');
				jQuery(this).find('.thumb_url_edit').attr('name','detailSection['+counter+'][thumb_url]');
				jQuery(this).find('.thumb_value_edit').attr('name','detailSection['+counter+'][thumb_id]');

				/// If this is self hosted mp4 video
				if(jQuery(this).find('.file_type').val() == "mp4"){
					jQuery(this).find('.upload-detail .file_value').attr('name','detailSection['+counter+'][video_url]');
				}
				//// If this is third party embedded video
				else{
					jQuery(this).find('.videourl').attr('name','detailSection['+counter+'][video_url]');
				}

				//// Exclude functionality
				var exclude=jQuery(this).find('.exclude').prop("checked");

					if(exclude != true){
						jQuery(this).find('.exclude').after('<input type="hidden" name="detailSection['+counter+'][exclude]" value="0" />')

					}else{
					jQuery(this).find('.exclude').attr('name','detailSection['+counter+'][exclude]');
					}
				counter++;
			}
		});
	});
	//////// Saving process ends here  ////////////////////////////

	/////////////////////////////////////////////////// Delete multiple functionality   ////////////////////////////////////////////////////////////
	jQuery(".vsz-listing").on("click",".deletesingle",function(){
		var totalSec = jQuery(".vsz-listing").find(".deletesingle").length;
		var checkedSec = jQuery(".vsz-listing").find(".deletesingle:checked").length;
		if(totalSec == checkedSec){
			jQuery(".headCheck").prop("checked",true);
		}
		else{
			jQuery(".headCheck").prop("checked",false);
		}
	});

	jQuery(".headCheck").click(function(){
		if(jQuery(".headCheck").prop("checked")){
			var totalSec = jQuery(".vsz-listing").find(".deletesingle").each(function(){
				jQuery(this).prop("checked",true);
			});
		}
		else{
			var totalSec = jQuery(".vsz-listing").find(".deletesingle").each(function(){
				jQuery(this).prop("checked",false);
			});
		}
	});

	/////// delete multiple function
	jQuery(".deleteSelectedButton").click(function(){
		if(jQuery(".vsz-listing").find(".deletesingle:checked").length>0){
			if(confirm("Are you sure to delete these elements ?")){
				jQuery(".vsz-listing").find(".deletesingle:checked").each(function(){
					jQuery(this).parent().parent().remove();
				});
			}
			else{
				return false;
			}
		}
		else{
			alert("PLease select atleast one item to delete.");
		}
		jQuery(".headCheck").prop("checked",false);
		return false;
	});

	// function to prevent user entering backSlash
	/*
	** Add this class name "backslashNotAllowed" and it will prevent entering backslash.
	*/
	jQuery(".backslashNotAllowed").blur(function(){
		regexForPrevent = /^[^\\]*$/;

		var value = jQuery(this).val();
		var idName = jQuery(this).attr("id");

		if(!regexForPrevent.test(value)){
			alert("\\ is not allowed as input !");
				// providing 0 delay time for focus to be worked
				setTimeout(function(){
					jQuery("#"+idName).focus();
				});
			return false;
		}
		else{
			return true;
		}
	});

	/*
	** This function is to delete individual image when multiple images uploaded / selected.
	*/
	jQuery(document).on('click', '.removeButton', function() {
		var classes = jQuery(this).attr("class");
		var arrayClass = classes.split(" ");
		var num = arrayClass.length;    // Total images count
		num--;
		var buttonClass = arrayClass[num];
		var len = arrayClass[num].length;
		var onlyId = arrayClass[num].substr(len-1,len);
		var imageId = ".addedImage"+onlyId;
		jQuery(imageId).remove();

		var number = jQuery(".numberOfElementsToDisplay").val();
		number--;
		jQuery(".numberOfElementsToDisplay").val(number);     	/// Updating images count
		if(number == 0){										///// Show image upload button if all images removed.
			jQuery("#multipleImageUpload").show();
		}
	});

	/*
	** Initializing magnific popup instance
	*/
	jQuery('.btn-popup1').magnificPopup({
			type: 'inline',
			fixedContentPos: true,
			fixedBgPos: true,
			overflowY: 'auto',
			closeBtnInside: true,
			closeOnBgClick:false,
			preloader: false,
			midClick: true,
			removalDelay: 0,
			gallery:{
					enabled:false,
					//arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>' // markup of an arrow button
			  },
	});

	/*
	** Magnific popup instance for "Add Image" , "Add Images" and "Add Video"
	*/
	jQuery('.save_image').magnificPopup({
		type: 'inline',
			fixedContentPos: true,
			fixedBgPos: true,
			overflowY: 'auto',
			closeBtnInside: true,
			preloader: false,
			midClick: true,
			removalDelay: 0,
			gallery:{
					enabled:false,
					//arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>' // markup of an arrow button
			  }
	});

	/*
	** Remove image for single image section
	*/
	jQuery(document).on('click','.gall_input_image .media.button.remove',function(){
		jQuery(this).siblings(".file_url").html('');
		jQuery(this).siblings(".file_value").val('');
		jQuery(this).siblings("#singleImageUpload").show();
		jQuery(this).hide();
	});

	/*
	** Remove vodeo for single video section
	*/
	jQuery(document).on('click','.gall_input_video .remove_single_video',function(){
		jQuery(".urlSec").show();
		jQuery(".orSec").show();
		jQuery(".class-btn_single_video").find(".file_url_single_video").html('');
		jQuery(".remove_single_video").siblings(".file_value_single").val('');
		jQuery(".remove_single_video").siblings("#singleVideoUpload").show();
		jQuery(this).hide();
	});

});

/*
** Adding multiple images in GALLERY IMAGES section
	number -> Number of image to append
	domCount -> Used dom number to append in every section
*/
function addImageMultiple(){

	////// Getting initial parameters
	var imageTitle = jQuery("#imageTitleForMultiple").val();
	var imageFreetext = jQuery("#imageFreetextForMultiple").val();
	var i,domCount = jQuery("#domCount").val();
	var number = jQuery(".numberOfElements").val();     /// Number of image sections to append

	if(number == "" || number == undefined){
		alert("Please attach atleast one image.");
		return false;
	}

	//// Display loader
	jQuery(".loader_image").show();
	jQuery("#loadingImage").show();

	/// Loop as per number of image section to append
	for(i=0;i<number;i++){
		var imageId = jQuery(".addedImage"+i+" .file_value").val();
		domCount ++;
		if(imageId == "" || imageId == undefined){
			// Can't find the image id
			continue;
		}
		else{
			callingAjax(imageId,domCount,imageTitle,imageFreetext);    // Calling function to call ajax
		}
	}

	///// Image sections appended so clearing the popup values
	jQuery(".appendImagesHere").empty();
	jQuery("#multipleImageUpload").show();
	jQuery(".numberOfElements").val('');
	jQuery(".loader_image").hide();
	jQuery("#loadingImage").hide();
	jQuery.magnificPopup.close();    // Close the popup
}

/*
** This function is to call ajax to get the image url form image id
*/
function callingAjax(imageId,domCount,imageTitle,imageFreetext){
	var checkNonce = '<?php echo wp_create_nonce('check_image_src_nonce'); ?>';

	var data = {
				'action':'get_image_src',
				'IMAGEID': imageId,
				'checknonce': checkNonce
			};

	jQuery.ajax({
		url: ajaxurl ,
		async: false ,
		data: data,
		success: function( data ) {
			imageUrl = data;
			creatingImageSection(imageUrl,imageId,domCount,imageTitle,imageFreetext);     //// Calling function which will append the image section
		}
	});
}

/*
** This function will appending data when multiple images are uploaded
*/
function creatingImageSection(imageUrl,imageId,domCount,imageTitle,imageFreetext){
	//check image value is empty or not
	if(imageId == '' || imageId.trim().length == 0){
		return;
	}

	//Get image related parameters
	var img_title = imageTitle;
	var imageid = imageId;
	var free_text = imageFreetext;

	//Get current image URL from add image form
	img_url = '<img src="' + imageUrl + '" style="max-width:100px;"/>';

	////// Creating new image section
	var listing = '<div class="gallery-item">'+
					'<div class="delete-checkbox">'+
						'<input type="checkbox" class="deletesingle" />'+
					'</div>'+
					'<div class="image">'+
						'<div class="gall_input_image" id="defaultimage">'+
							'<span class="file_url">'+img_url+'</span>'+
							'<br>'+
							'<input id="singleImageUpload" style="margin-top:10px; display:none;" value="Add Image" class="media button add button button-primary" type="button" />'+
							'<input value="Remove" id="removeImageButton" class="media button remove hidden remove-btn-image" style="display: inline;" type="button" />'+
							'<input value="'+imageid+'" class="file_value" name="imageid" type="hidden" />'+
						'</div>'+
					'</div>'+
					'<div class="title-detail">'+
						'<label><?php echo __('Title');?></label>'+
						'<input type="text" class="vsz-title" value="'+img_title+'" />'+
						'<br/>'+
						'<label style="vertical-align:top;"><?php echo __('Description');?></label>'+
						'<textarea class="vsz-description">'+free_text+'</textarea>'+
					'</div>'+
					'<div class="description-detail">'+
						'<label><?php echo __('Exclue');?>'+
							'<input class="exclude" value="1" type="checkbox">'+
						'</label>'+
						'<br>'+
						'<button class="delete-image" onclick="return delete_image(this);"><?php echo __('Delete');?></button>'+
					'</div>'+
					'<input value="image" class="content-type" type="hidden" />'+
					'<input type="hidden" class="imageid" value="'+imageid+'" />'+
					'<div class="clear"></div>'+
				'</div>';
	jQuery('.vsz-listing').prepend(listing);

	//// Updating dom count value
	jQuery("#domCount").val(domCount);
}

/*
** Remove thumbnail image function for add functionality
*/
jQuery("#removeThumbButton").click(function(){
	jQuery("#add_video_single .file_url_single_thumb").html('');
	jQuery("#add_video_single #singleThumbUpload").show();
	jQuery("#add_video_single #removeThumbButton").hide();
});

/*
** Add wp_media window related script
*/
(function(jQuery) {
	jQuery(function() {
		var gallery_media_frame;

		///////////////////////////////// Add image for image edit section  //////////////////////////////////////////////
		jQuery(document).on('click', '.gall_input_image .media.button.add', function(e) {
			jQuerythis = jQuery(this);

			gallery_media_frame = wp.media.frames.gallery_media_frame = wp.media({
				className: 'media-frame gallery_media_frame',
				frame: 'post',
				multiple: false,
				library : { type : 'image' },
			});

			gallery_media_frame.on('insert', function() {
				var attachment = gallery_media_frame.state().get('selection').first().toJSON();

				if ('image' == attachment.type && 'undefined' != typeof attachment.sizes) {

					if(attachment.width < 300 || attachment.height < 300){
						alert("Please upload more than 300X300 dimension image.");
						return
					}
					file_url = attachment.sizes.full.url;

					if ('undefined' != typeof attachment.sizes.thumbnail) {
						file_url = attachment.sizes.thumbnail.url;
					}
					file_url = '<img src="' + file_url + '" style="max-width:100px;"/>';
				}
				else {
					alert("Please Select Image only.");
					return;
				}

				jQuerythis.hide();
				jQuerythis.parent().find('.media.button.remove').show();
				jQuerythis.parent().find('.file_value').val(attachment.id);
				jQuerythis.parent().find('.file_url').html(file_url);
			});

			gallery_media_frame.open();
			gallery_media_frame.content.mode('upload');
		});

		///////////////////////////////// Add image for image add section  //////////////////////////////////////////////
		jQuery(document).on('click', '.class-btn_single #singleImageUpload', function(e) {
			jQuerythis = jQuery(this);
			gallery_media_frame_single = wp.media.frames.gallery_media_frame_single = wp.media({
				className: 'media-frame gallery_media_frame_single',
				frame: 'post',
				multiple: false,
				library : { type : 'image' },
			});

			gallery_media_frame_single.on('insert', function() {
				var attachment = gallery_media_frame_single.state().get('selection').first().toJSON();

				if ('image' == attachment.type && 'undefined' != typeof attachment.sizes) {

					if(attachment.width < 300 || attachment.height < 300){
						alert("Please upload more than 300X300 dimension image.");
						return
					}
					file_url_single = attachment.sizes.full.url;

					if ('undefined' != typeof attachment.sizes.thumbnail) {
						file_url_single = attachment.sizes.thumbnail.url;
					}

					file_url_single ='<img src="' + file_url_single + '" width="70" height="50" class="alignnone size-full" />';
				}
				else {
					alert("Please Select Image only.");
					return;
				}

				jQuerythis.hide();
				jQuerythis.siblings('.media.button.remove_single_image').show();
				jQuerythis.siblings('.file_value_single').val(attachment.id);
				jQuery('.class-btn_single .file_url_single_image').html(file_url_single);
			});

			gallery_media_frame_single.open();
			gallery_media_frame_single.content.mode('upload');
		});


		///////////////////////////////// Add Video Related script for add video section  //////////////////////////////////////////////
		jQuery(document).on('click', '#singleVideoUpload', function(e) {
			jQuerythis = jQuery(this);
			gallery_media_frame_single = wp.media.frames.gallery_media_frame_single = wp.media({
				className: 'media-frame gallery_media_frame_single',
				frame: 'post',
				multiple: false,
				library : { type : 'video' },
			});

			gallery_media_frame_single.on('insert', function() {
				var attachment = gallery_media_frame_single.state().get('selection').first().toJSON();
				var arr = [];
				json = JSON.stringify(attachment); //convert to json string
				arr = jQuery.parseJSON(json); //convert to javascript array
				if ('video' == attachment.type && 'mp4' == attachment.subtype && attachment.filesizeInBytes > 0) {
					file_url = attachment.url;
					file_url_name = attachment.filename;

					file_url_single ='<div class="videoNameDiv">' + file_url_name + '</div>';
				}
				else {
					alert("Please Select mp4 video only.");
					return;
				}
				// hide url section and or section
				jQuery(".urlSec").hide();
				jQuery(".orSec").hide();
				jQuerythis.hide();
				jQuerythis.parent().find('.media.button.remove_single_video').show();
				jQuerythis.parent().find('.file_value_single').val(file_url);
				jQuery('.class-btn_single_video .file_url_single_video').html(file_url_single);
			});

			gallery_media_frame_single.open();
			gallery_media_frame_single.content.mode('upload');
		});

		///////////////////////////////// Add Image Related script for add video thumbnail image  //////////////////////////////////////////////
		jQuery(document).on('click', '#singleThumbUpload', function(e) {
			jQuerythis = jQuery(this);
			gallery_media_thumb_single = wp.media.frames.gallery_media_thumb_single = wp.media({
				className: 'media-frame gallery_media_thumb_single',
				frame: 'post',
				multiple: false,
				library : { type : 'image' },
			});

			gallery_media_thumb_single.on('insert', function() {
				var attachment = gallery_media_thumb_single.state().get('selection').first().toJSON();

				if ('image' == attachment.type && 'undefined' != typeof attachment.sizes) {

					if(attachment.width < 300 || attachment.height < 300){
						alert("Please upload more than 300X300 dimension image.");
						return
					}
					file_url = attachment.sizes.full.url;
					file_id = attachment.id;
					file_url_single = file_url;
					if ('undefined' != typeof attachment.sizes.thumbnail) {
						file_url_single = attachment.sizes.thumbnail.url;
					}
					file_url_single ='<img src="' + file_url_single + '" width="70" height="50" class="alignnone size-full" />';
				}
				else {
					alert("Please Select Image only.");
					return;
				}

				// hide url section and or section
				jQuerythis.hide();
				jQuerythis.siblings('.media.button.remove_single_thumb').show();
				jQuerythis.siblings('.thumb_url').val(file_url);
				jQuerythis.siblings('.thumb_id').val(file_id);
				jQuery('#add_video_single .file_url_single_thumb').html(file_url_single);
			});

			gallery_media_thumb_single.open();
			gallery_media_thumb_single.content.mode('upload');
		});

		///////////////////////////////// Remove Image Functionality for add image  //////////////////////////////////////////////
		jQuery(document).on('click', '.gall_input_image .media.button.remove_single_image', function() {
			jQuery('.class-btn_single .file_url_single_image').html('');
			jQuery('#singleImageUpload').show();
			jQuery(this).siblings('.file_value_single').val('');
			jQuery(this).siblings('.media.button.add').show();
			jQuery(this).hide();
		});
	});
})(jQuery);

/*
** Adding multiple images related script
*/
jQuery(document).ready(function(jQuery){
	///////////////////////////////// Add Image Related script for add multiple image  //////////////////////////////////////////////
	var custom_uploader;
	jQuery(document).on('click', '#multipleImageUpload', function(e) {
		e.preventDefault();
		//If the uploader object has already been created, reopen the dialog
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		//Extend the wp.media object
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Image',
			button: {
				text: 'Choose Multiple Image'
			},
			multiple: 'toggle'				// This will chose image by single click
											// Without ctrl button multiple images can be selected
		});
		custom_uploader.on('select', function() {
            var selection = custom_uploader.state().get('selection');
			var arr = [];
			json = JSON.stringify(selection); //convert to json string
			arr = jQuery.parseJSON(json); //convert to javascript array
			var numberOfImages = arr.length;
			var i,j,k=0,countAlert=0,file_url="",final_images="";
			var elementNo = 0;
			for(i=0;i<numberOfImages;i++){
				json = JSON.stringify(arr[i]);
				attachment = jQuery.parseJSON(json);

				if ('image' == attachment.type && 'undefined' != typeof attachment.sizes) {
					var err=0;
					if(attachment.width < 300 || attachment.height < 300){
						countAlert++;
						err++;
						k++;
						continue;
					}

					if(err == 0){
						file_url = attachment.sizes.full.url;
					}
					if ('undefined' != typeof attachment.sizes.thumbnail) {
						file_url = attachment.sizes.thumbnail.url;
					}
					j = i-k;
					//file_url = '<img src="' + file_url + '" style="max-width:100px;"/>';
					file_url ='<img src="' + file_url + '" width="70" height="50" class="alignnone size-full imageNo'+j+'" style="margin-top: 20px;" />';
					jQuery("#multipleImageUpload").hide();
					var idToSave = jQuery("#multipleImageUpload").siblings('.file_value').val();
						idToSave = attachment.id;
						var addHtml = '<div class="addedImage addedImage'+j+'">'+file_url+'<input type="hidden" class="file_value" name="imageid[]" value="'+idToSave+'" />'+'<span class="for-img-remove"><input class="button remove button-primary removeButton buttonNo'+j+'" type="button" value="X" style="margin-top: 10px;" /></span>'+'</div>';
						jQuery('.appendImagesHere').append(addHtml);

						jQuery(".buttonNo"+j).show();
						elementNo++;
				}
				else {
					alert("Please Select Image only.");
				}

				// alert(elementNo);
				jQuery(".numberOfElements").val(elementNo);
				jQuery(".numberOfElementsToDisplay").val(elementNo);
			}

			if(countAlert >0){
				alert("Sorry "+countAlert+" images could not uploaded. Please upload image with more than 300X300 dimension.");
			}
		});
		custom_uploader.open();
	});

});

/*
** This function is to add single image section
*/
function addImageDetail(){
	//check image value is empty or not
	imageId = jQuery(".file_value_single").val();

	if(imageId == '' || imageId.trim().length == 0){
		alert("Please attach image.");
		return false;
	}

	//Get image title and free text from add form field
	var img_title = jQuery("#img_title").val().trim();
	var free_text = jQuery("#free_text").val().trim();

	//Get current image URL from add image form
	img_url = '<img src="' + jQuery('.file_url_single_image img').attr('src') + '" style="max-width:100px;"/>';

	//Create new div structure with new information
	var listing = '<div class="gallery-item">'+
					'<div class="delete-checkbox">'+
						'<input type="checkbox" class="deletesingle" />'+
					'</div>'+
					'<div class="image">'+
						'<div class="gall_input_image" id="defaultimage">'+
							'<span class="file_url">'+img_url+'</span>'+
							'<br>'+
							'<input id="singleImageUpload" style="margin-top:10px; display:none;" value="Add Image" class="media button add button button-primary" type="button" />'+
							'<input value="Remove" id="removeImageButton" class="media button remove hidden remove-btn-image" style="display: inline;" type="button" />'+
							'<input value="'+imageId+'" class="file_value" name="imageid" type="hidden" />'+
						'</div>'+
					'</div>'+
					'<div class="title-detail">'+
						'<label><?php echo __('Title');?></label>'+
						'<input type="text" class="vsz-title" value="'+img_title+'" />'+
						'<br/>'+
						'<label style="vertical-align:top;"><?php echo __('Description');?></label>'+
						'<textarea class="vsz-description">'+free_text+'</textarea>'+
					'</div>'+
					'<div class="description-detail">'+
						'<label><?php echo __('Exclue');?>'+
							'<input class="exclude" value="1" type="checkbox" />'+
						'</label>'+
						'<br>'+
						'<button class="delete-image" onclick="return delete_image(this);"><?php echo __('Delete');?></button>'+
					'</div>'+
					'<input value="image" class="content-type" type="hidden"/>'+
					'<input type="hidden" class="imageid" value="'+imageId+'" />'+
					'<div class="clear"></div>'+
				'</div>';
	jQuery('.vsz-listing').prepend(listing);

	//Remove value form add form image
	jQuery("#img_title").val('');
	jQuery(".file_value_single").val('');
	jQuery("#free_text").val('');

	//Display image related section in add image form
	jQuery('.class-btn_single .remove_single_image').hide();
	jQuery('.file_url_single_image').html('');
	jQuery('#singleImageUpload').show();
	jQuery.magnificPopup.close();
}

/*
** This function is to add video section
*/
function addVideoSingle(){

	//// video_type defines that which type of video is appended either mp4 or embedded
	var video_type = "mp4";

	//// Getting initial parameters
	videoUrl = jQuery(".class-btn_single_video .file_value_single").val();
	videoName = jQuery(".class-btn_single_video .videoNameDiv").text();
	var videoCheck = true;

	//check video url value is empty or not
	if(videoUrl == '' || videoUrl.trim().length == 0){
		videoUrl = jQuery(".video_url_to_insert").val();
		if(videoUrl == "" || videoUrl.trim().length == 0){
			alert("Please insert url or upload a video.");								// Video is not uploaded and url is not inserted so gives alert
			return false;
		}
		else{
			/// calling ajax to confirm that a url have the video or not
			var checkNonce = '<?php echo wp_create_nonce('check_video_exist_nonce'); ?>';
			videoUrl_encoded = encodeURIComponent(videoUrl);
			var data = {
				'action':'check_video_exists',
				'url_to_check': videoUrl_encoded,
				'checknonce': checkNonce
			}

			/////// Calling ajax to check a url have a valid video or not
			jQuery.ajax({
				url: ajaxurl ,
				async: false ,
				data: data,
				success: function( data ) {
					if(data == false){
						videoCheck = false;
						alert("Invalid url. Please insert url which have valid video.");
					}
					else{
						video_type = "embedded";										///// Url have a valid video. So changing the video type to embedded
					}
				}
			});
		}
	}

	// if false than url doesn't have a valid video
	if(videoCheck == false){
		return false;
	}

	//Get video title and free text
	var video_title = jQuery("#video_title").val();
	video_title = video_title.trim();

	var video_free_text = jQuery("#video_free_text").val();
	video_free_text = video_free_text.trim();

	// get thumbnail url and id
	var thumbId = jQuery(".thumb_id").val();
	var thumbUrl = jQuery(".thumb_url").val();

	if(thumbUrl == "" || thumbUrl == undefined){
		thumbUrl = '<?php echo dirname(plugin_dir_url(__FILE__)); ?>/images/Video.jpg';			// Thumb image is not added so displaying a common image
	}

	////// Getting video name from full url
	var urlArray = videoUrl.split("/");
	var arrayLen = urlArray.length;
	var fileName = urlArray[arrayLen-1];

	//Create new div structure with new information
	var newDesignHtml = '<div class="gallery-item video">'+
							'<div class="delete-checkbox">'+
								'<input type="checkbox" class="deletesingle">'+
							'</div>'+
							'<div class="image">'+
								'<div class="gall_input_thumb" id="defaultimage">'+
									'<span class="file_url">'+
										'<img src="'+thumbUrl+'">'+
									'</span>'+
									'<br>'+
									'<input id="singleThumbUploadEdit" style="margin-top:10px; display:none;" value="Add Thumbnail" class="media button add button button-primary" type="button">'+
									'<input value="Remove" id="removeThumbButtonEdit" class="media button remove hidden remove-btn-image" style="display: inline;" type="button">'+
									'<input value="'+thumbId+'" class="thumb_value_edit" name="imageid" type="hidden">'+
									'<input type="hidden" class="thumb_url_edit" value="'+thumbUrl+'" />'+
								'</div>'+
							'</div>';
		if(video_type == 'mp4'){
			newDesignHtml += '<div class="title-detail">'+
								'<label><?php echo __('Title');?></label>'+
								'<input type="text" class="vsz-title" value="'+video_title+'"><br/>'+
								'<label style="vertical-align:top;"><?php echo __('Description');?></label>'+
								'<textarea class="vsz-description">'+video_free_text+'</textarea>'+

							'<div class="video-url-detail">'+
								'<label><?php echo __('video Url');?></label>'+
								'<input type="text" class="videourl" value="">'+
							'</div>'+
							'<div class="or-detail">'+
								'<?php echo __('Or');?>'+
							'</div>'+
							'<div class="upload-detail">'+
								'<label><?php echo __('Upload Video');?></label>'+
								'<div class="gall_input_video" id="defaultvideo">'+
									'<span class="file_url">'+fileName+'</span>'+
									'<br>'+
									'<input id="singleVideoUploadEdit" style="margin-top: -15px;display:none;" value="Add Video" class="media button add button button-primary" type="button">'+
									'<input value="Remove" id="removeVideoButton" class="media button remove remove-btn-video button button-primary " style="display: inline;" type="button">'+
									'<input value="'+videoUrl+'" class="file_value" name="videourl" type="hidden">'+
									'<input value="'+video_type+'" class="file_type" name="videotype" type="hidden">'+
								'</div>'+
							'</div>'+
						'</div>';
		}
		else{
				newDesignHtml += '<div class="title-detail">'+
									'<label><?php echo __('Title');?></label>'+
									'<input type="text" class="vsz-title" value="'+video_title+'"><br/>'+
									'<label style="vertical-align:top;"><?php echo __('Description');?></label>'+
									'<textarea class="vsz-description">'+video_free_text+'</textarea>'+
								'<div class="video-url-detail">'+
									'<label><?php echo __('video Url');?></label>'+
									'<input type="text" class="videourl" value="'+videoUrl+'">'+
								'</div>'+
								'<div class="or-detail">'+
									'<?php echo __('OR');?>'+
								'</div>'+
								'<div class="upload-detail">'+
									'<label><?php echo __('Upload Video');?></label>'+
									'<div class="gall_input_video" id="defaultvideo">'+
										'<span class="file_url"></span>'+
										'<br>'+
										'<input id="singleVideoUploadEdit" style="margin-top: -15px;" value="Add Video" class="media button add button button-primary" type="button">'+
										'<input value="Remove" id="removeVideoButton" class="media button remove hidden remove-btn-video button button-primary " style="display: none;" type="button">'+
										'<input value="'+videoUrl+'" class="file_value" name="videourl" type="hidden">'+
										'<input value="'+video_type+'" class="file_type" name="videotype" type="hidden">'+
									'</div>'+
								'</div>'+
								'</div>';
		}
		newDesignHtml +='<div class="description-detail">'+
							'<label><?php echo __('Exclude');?>'+
								'<input class="exclude" value="1" type="checkbox" />'+
							'</label><br>'+
							'<button class="delete-image" onclick="return delete_image(this);"><?php echo __('Delete');?></button>'+
						'</div>'+
						'<input value="video" class="content-type" type="hidden">'+
						'<input value="'+thumbUrl+'" class="thumb_url" type="hidden">'+
						'<input value="'+thumbId+'" class="thumb_id" type="hidden">'+
						'<div class="clear"></div>'+
					'</div>';
	jQuery('.vsz-listing').prepend(newDesignHtml);

	//Remove value form add form video and thumbnail
	jQuery("#video_title").val('');
	jQuery(".file_value_single").val('');
	jQuery("#video_free_text").val('');
	jQuery("#add_video_single .file_url_single_thumb").html('');
	jQuery("#add_video_single #singleThumbUpload").show();
	jQuery("#add_video_single #removeThumbButton").hide();

	//Display image related section in add image form
	jQuery('.gall_input_video .remove_single_video').hide();
	jQuery('.file_url_single_video').html('');
	jQuery('#singleVideoUpload').show();
	jQuery('#add_video_single .urlSec').show();
	jQuery('#add_video_single .orSec').show();
	jQuery('#add_video_single .video_url_to_insert').val('');
	jQuery.magnificPopup.close();
}


jQuery(document).ready(function(){
	///////////////////////////////// Add video for edit case  //////////////////////////////////////////////
	jQuery(document).on('click', '#singleVideoUploadEdit', function(e) {
		var thisVar = jQuery(this);
		gallery_media_frame_single = wp.media.frames.gallery_media_frame_single = wp.media({
			className: 'media-frame gallery_media_frame_single',
			frame: 'post',
			multiple: false,
			library : { type : 'video' },
		});

		gallery_media_frame_single.on('insert', function() {
			var attachment = gallery_media_frame_single.state().get('selection').first().toJSON();
			var arr = [];
			json = JSON.stringify(attachment); //convert to json string
			arr = jQuery.parseJSON(json); //convert to javascript array
			if ('video' == attachment.type && 'mp4' == attachment.subtype && attachment.filesizeInBytes > 0) {
				file_url = attachment.url;
				file_url_name = attachment.filename;

				file_url_single ='<div class="videoNameDiv">' + file_url_name + '</div>';
			}
			else {
				alert("Please Select mp4 video only.");
				return;
			}


			var parentObj = jQuery(thisVar).parent().parent();

			// hide url section and or section
			jQuery(thisVar).siblings(".video-url-detail").hide();
			jQuery(parentObj).siblings(".or-detail").hide();

			///// Clearing and appending values as required
			jQuery(parentObj).siblings(".video-url-detail").find(".videourl").val('');
			jQuery(parentObj).siblings(".video-url-detail").hide();
			jQuery(thisVar).siblings("#removeVideoButton").show();
			jQuery(thisVar).siblings(".file_value").val(file_url);
			jQuery(thisVar).siblings(".file_url").html(file_url);
			jQuery(thisVar).siblings(".file_type").val('mp4');

			jQuery(thisVar).hide();
		});

		gallery_media_frame_single.open();
		gallery_media_frame_single.content.mode('upload');
	});

	///////////////////////////////// Add image for edit thumbnail case  //////////////////////////////////////////////
	jQuery(document).on('click', '#singleThumbUploadEdit', function(e) {
		var thisVar = jQuery(this);
		gallery_media_frame_single_thumb = wp.media.frames.gallery_media_frame_single_thumb = wp.media({
			className: 'media-frame gallery_media_frame_single_thumb',
			frame: 'post',
			multiple: false,
			library : { type : 'image' },
		});

		gallery_media_frame_single_thumb.on('insert', function() {
			var attachment = gallery_media_frame_single_thumb.state().get('selection').first().toJSON();

			if ('image' == attachment.type && 'undefined' != typeof attachment.sizes) {

				if(attachment.width < 300 || attachment.height < 300){
					alert("Please upload more than 300X300 dimension image.");
					return
				}
				file_url = attachment.sizes.full.url;
				file_id = attachment.id;
				file_url_single = file_url;
				if ('undefined' != typeof attachment.sizes.thumbnail) {
					file_url_single = attachment.sizes.thumbnail.url;
				}

				file_url_single ='<img src="' + file_url_single + '" width="70" height="50" class="alignnone size-full" />';
			}
			else {
				alert("Please Select Image only.");
				return;
			}

			///// Clearing and appending values as required
			jQuery(thisVar).siblings("#removeThumbButtonEdit").show();
			jQuery(thisVar).siblings(".thumb_url_edit").val(file_url);
			jQuery(thisVar).siblings(".thumb_value_edit").val(file_id);
			jQuery(thisVar).siblings("span.file_url").html(file_url_single);
			jQuery(thisVar).hide();
		});

		gallery_media_frame_single_thumb.open();
		gallery_media_frame_single_thumb.content.mode('upload');
	});

	/////////// To remove single image from add section
	jQuery(document).on('click', '.gall_input_image .media.button.remove_single_image', function() {
		jQuery('.class-btn_single .file_url_single_image').html('');
		jQuery('#singleImageUpload').show();
		jQuery(this).siblings('.file_value_single').val('');
		jQuery(this).siblings('.media.button.add').show();
		jQuery(this).hide();
	});


	/////////// To remove thumb image for edit section
	jQuery(document).on('click','.gall_input_thumb .media.button.remove',function(){
		jQuery(this).siblings(".file_url").html('');
		jQuery(this).siblings(".file_value").val('');
		jQuery(this).siblings(".thumb_value_edit").val('');
		jQuery(this).siblings(".thumb_url_edit").val('');
		jQuery(this).siblings("#singleThumbUploadEdit").show();
		jQuery(this).hide();
	});

	/////////// To remove thumb image for edit section
	jQuery(document).on('click','.gall_input_video .media.button.remove',function(){
		var parentObj = jQuery(this).parent().parent();

		jQuery(parentObj).siblings(".video-url-detail").show();
		jQuery(parentObj).siblings(".or-detail").show();
		jQuery(parentObj).siblings(".upload-detail").show();

		jQuery(this).siblings(".file_value").val('');
		jQuery(this).siblings("span.file_url").html('');
		jQuery(this).siblings("#singleVideoUploadEdit").show();
		jQuery(this).siblings(".file_type").val('embedded');
		jQuery(this).hide();
	});

});

//Display attached images / videos for on load
<?php
if(!empty($arrSecDetails)){

	foreach($arrSecDetails as $secId => $arrFieldInfo){
		if(isset($arrFieldInfo['content_type']) && $arrFieldInfo['content_type'] == "image"){
			/***************	For Image section		****************/
			//Get field related information
			$titleVal = isset($arrFieldInfo['img_title']) ? $arrFieldInfo['img_title'] : '' ;
			$imageId = isset($arrFieldInfo['img_id']) ? $arrFieldInfo['img_id'] : '' ;
			$freeText = isset($arrFieldInfo['freetext']) ? $arrFieldInfo['freetext'] : '' ;
			$content_type = isset($arrFieldInfo['content_type']) ? $arrFieldInfo['content_type'] : '' ;
			$exclue = isset($arrFieldInfo['exclude']) ? $arrFieldInfo['exclude'] : '' ;

			//Add new section in structure
			//Check if image id not empty then display image in gallery and edit form
			if(!empty($imageId)){
				?>file_url = '<?php echo wp_get_attachment_image($imageId);?>';
				var listing='<div class="gallery-item">'+
								'<div class="delete-checkbox">'+
									'<input class="deletesingle" type="checkbox" />'+
								'</div>'+
								'<div class="image">'+
									'<div class="gall_input_image" id="defaultimage">'+
										'<span class="file_url">'+file_url+'</span>'+
										'<br>'+
										'<input id="singleImageUpload" style="margin-top:10px; display:none;" value="Add Image" class="media button add button button-primary" type="button" />'+
										'<input value="Remove" id="removeImageButton" class="media button remove hidden remove-btn-image" style="display: inline;" type="button" />'+
										'<input value="<?php echo $imageId;?>" class="file_value" name="imageid" type="hidden" />'+
									'</div>'+
								'</div>'+
								'<div class="title-detail">'+
									'<label><?php echo __('Title');?></label>'+
									'<input type="text" class="vsz-title" value="<?php echo $titleVal;?>" />'+
									'<br/>'+
									'<label style="vertical-align:top;"><?php echo __('Description');?></label>'+
									'<textarea class="vsz-description"><?php echo $freeText;?></textarea>'+
								'</div>'+
								'<div class="description-detail">'+
									'<label><?php echo __('Exclude');?>'+
										'<input class="exclude" value="1" type="checkbox" <?php if($exclue == 1) { ?>checked="checked" <?php } ?> />'+
									'</label>'+
									'<br>'+
									'<button class="delete-image" onclick="return delete_image(this);"><?php echo __('Delete');?></button>'+
								'</div>'+
								'<input value="image" class="content-type" type="hidden" />'+
								'<div class="clear"></div>'+
							'</div>';
				jQuery('.vsz-listing').append(listing);<?php
			}
			else{
				///////////// Image id is empty
		?>var listing = '<div class="gallery-item">'+
								'<div class="delete-checkbox">'+
									'<input class="deletesingle" type="checkbox" />'+
								'</div>'+
							'<div class="image">'+
								'<div class="gall_input_image" id="defaultimage">'+
									'<span class="file_url"></span>'+
									'<br>'+
									'<input id="singleImageUpload" style="margin-top:10px; display:inline;" value="Add Image" class="media button add button button-primary" type="button" />'+
									'<input value="Remove" id="removeImageButton" class="media button remove hidden remove-btn-video button button-primary " style="display: none;" type="button" />'+
									'<input value="" class="file_value" name="imageid" type="hidden" />'+
								'</div>'+
							'</div>'+
							'<div class="title-detail">'+
								'<label><?php echo __('Title');?></label>'+
								'<input type="text" class="vsz-title" value="<?php echo $titleVal;?>" />'+
								'<br/>'+
								'<label style="vertical-align:top;"><?php echo __('Description');?></label>'+
								'<textarea class="vsz-description"><?php echo $freeText;?></textarea>'+
							'</div>'+
							'<div class="description-detail">'+
								'<label><?php echo __('Exclude');?>'+
									'<input class="exclude" value="1" type="checkbox" <?php if($exclue == 1) { ?>checked="checked" <?php } ?> />'+
								'</label>'+
								'<br>'+
								'<button class="delete-image" onclick="return delete_image(this);"><?php echo __('Delete');?></button>'+
							'</div>'+
							'<input value="image" class="content-type" type="hidden" />'+
							'<div class="clear"></div>'+
						'</div>';
				jQuery('.vsz-listing').append(listing);<?php
			}
		}
		if(isset($arrFieldInfo['content_type']) && $arrFieldInfo['content_type'] == "video"){
			/***************	For Video section		****************/
			//Get field related information
			$titleVal = isset($arrFieldInfo['video_title']) ? $arrFieldInfo['video_title'] : '' ;
			$videoType = isset($arrFieldInfo['video_type']) ? $arrFieldInfo['video_type'] : '' ;
			$video_url = isset($arrFieldInfo['video_url']) ? $arrFieldInfo['video_url'] : '' ;
			$thumb_url = isset($arrFieldInfo['thumb_url']) ? $arrFieldInfo['thumb_url'] : '' ;
			$thumb_id = isset($arrFieldInfo['thumb_id']) ? $arrFieldInfo['thumb_id'] : '' ;
			$freeText = isset($arrFieldInfo['video_freetext']) ? $arrFieldInfo['video_freetext'] : '' ;
			$content_type = isset($arrFieldInfo['content_type']) ? $arrFieldInfo['content_type'] : '' ;
			$exclue = isset($arrFieldInfo['exclude']) ? $arrFieldInfo['exclude'] : '' ;
			
			//// Display default image if thumbnail image is not added
			if(empty($thumb_url)){
				$thumb_url = dirname(plugin_dir_url(__FILE__))."/images/Video.jpg";
			}
			
			?>
			var videourl = '<?php echo $video_url;?>';
			var urlArray = videourl.split("/");
			var arrayLen = urlArray.length;
			var fileName = urlArray[arrayLen-1];
			var listing = '<div class="gallery-item video">'+
							'<div class="delete-checkbox">'+
								'<input class="deletesingle" type="checkbox">'+
							'</div>'+
							'<div class="image">'+
								'<div class="gall_input_thumb" id="defaultimage">'+
									'<span class="file_url">'+
									'<img src="<?php echo $thumb_url;?>"></span>'+
									'<br>'+
									'<input id="singleThumbUploadEdit" style="margin-top:10px; display:none;" value="Add Thumbnail" class="media button add button button-primary" type="button">'+
									'<input value="Remove" id="removeThumbButtonEdit" class="media button remove hidden remove-btn-image" style="display: inline;" type="button">'+
									'<input value="<?php echo $thumb_id;?>" class="thumb_value_edit" name="imageid" type="hidden">'+
									'<input type="hidden" class="thumb_url_edit" value="<?php echo $thumb_url; ?>" />'+
								'</div>'+
							'</div>';
			//////////////////// For self hosted video (mp4)
			<?php if($videoType == 'mp4'){ ?>
				listing += '<div class="title-detail">'+
								'<label><?php echo __('Title');?></label>'+
								'<input type="text" class="vsz-title" value="<?php echo $titleVal;?>">'+
								'<label style="vertical-align:top;"><?php echo __('Description');?></label>'+
								'<textarea class="vsz-description"><?php echo $freeText;?></textarea>'+
							'<div class="video-url-detail hidden">'+
								'<label class="hidden"><?php echo __('video Url');?></label>'+
								'<input type="text" class="videourl" value="">'+
							'</div>'+
							'<div class="or-detail hidden">'+
								'<?php echo __('OR');?>'+
							'</div>'+
							'<div class="upload-detail">'+
								'<label><?php echo __('Upload Video');?></label>'+
								'<div class="gall_input_video" id="defaultvideo">'+
									'<span class="file_url">'+fileName+'</span>'+
									'<br>'+
									'<input id="singleVideoUploadEdit" style="margin-top: -15px;display:none;" value="Add Video" class="media button add button button-primary" type="button" >'+
									'<input value="Remove" id="removeVideoButton" class="media button remove remove-btn-video button button-primary " style="display: inline;" type="button">'+
									'<input value="<?php echo $video_url;?>" class="file_value" name="videourl" type="hidden">'+
									'<input value="<?php echo $videoType;?>" class="file_type" name="videotype" type="hidden">'+
								'</div>'+
							'</div>'+
							'</div>'+
							'<div class="description-detail">'+
								'<label><?php echo __('Exclude');?>'+
									'<input class="exclude" value="1" type="checkbox" <?php if($exclue == 1) { ?>checked="checked" <?php } ?> />'+
								'</label>'+
								'<br>'+
								'<button class="delete-image" onclick="return delete_image(this);"><?php echo __('Delete');?></button>'+
							'</div>';
		<?php } else { ?>
		//////////////////// For third party video (embedded)
				listing += '<div class="title-detail">'+
								'<label><?php echo __('Title');?></label>'+
								'<input type="text" class="vsz-title" value="<?php echo $titleVal;?>">'+
								'<label style="vertical-align:top;"><?php echo __('Description');?></label>'+
								'<textarea class="vsz-description"><?php echo $freeText;?></textarea>'+
							'<div class="video-url-detail">'+
								'<label><?php echo __('video Url');?></label>'+
								'<input type="text" class="videourl" value="<?php echo $video_url;?>">'+
							'</div>'+
							'<div class="or-detail">'+
								'<?php echo __('OR');?>'+
							'</div>'+
							'<div class="upload-detail">'+
								'<label><?php echo __('Upload Video');?></label>'+
								'<div class="gall_input_video" id="defaultvideo">'+
									'<span class="file_url"></span>'+
									'<br>'+
									'<input id="singleVideoUploadEdit" style="margin-top: -15px;" value="Add Video" class="media button add button button-primary" type="button">'+
									'<input value="Remove" id="removeVideoButton" class="media button remove hidden remove-btn-video button button-primary" style="display: none;" type="button">'+
									'<input value="'+videourl+'" class="file_value" name="videourl" type="hidden" />'+
									'<input value="<?php echo $videoType; ?>" class="file_type" name="videotype" type="hidden" />'+
								'</div>'+
							'</div>'+
							'</div>'+
							'<div class="description-detail">'+
								'<label><?php echo __('Exclude');?>'+
									'<input class="exclude" value="1" type="checkbox" <?php if($exclue == 1) { ?>checked="checked" <?php } ?> />'+
								'</label>'+
								'<br>'+
								'<button class="delete-image" onclick="return delete_image(this);"><?php echo __('Delete');?></button>'+
							'</div>';<?php
		 }
		?> listing += '<input value="video" class="content-type" type="hidden">'+
						'<input value="<?php echo $thumb_url;?>" class="thumb_url" type="hidden" />'+
						'<input value="<?php echo $thumb_id;?>" class="thumb_id" type="hidden" />'+
						'<div class="clear"></div>'+
					'</div>';
			jQuery('.vsz-listing').append(listing);	<?php
		}
	}
}
?></script>