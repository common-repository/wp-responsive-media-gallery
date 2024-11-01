<?php

include_once( 'VSZ_Thumb.php' );

/* function get_image_custom($width,$height,$crop)
{
	$img_class = '';
	if(isset($class) && $class != ''){
		$img_class = $class;
	}
	else{
		$img_class="post_image";
	}
	$url = wp_get_attachment_url(get_post_thumbnail_id());
	$params = array(
					    'width' => $width,
					    'height' => $height,
					    'crop' => $crop
					);
	$url = vsz_thumb( $url, $params );
	$image = '<img class="'.$img_class.'" src="'.$url.'"/>';
	return $image;
} */

function get_image_url_custom($width,$height,$crop,$url)
{
	$img_class = '';
	if(isset($class) && $class != ''){
		$img_class = $class;
	}
	else{
		$img_class="post_image";
	}
	$params = array(
					    'width' => $width,
					    'height' => $height,
					    'crop' => $crop
					);
	$url = vsz_thumb( $url, $params );
	// $image = '<img class="'.$img_class.'" src="'.$url.'"/>';
	return $url;
}

/* function get_image_url_custom_thumb($width,$height,$crop,$url)
{
	$img_class = '';
	if(isset($class) && $class != ''){
		$img_class = $class;
	}
	else{
		$img_class="post_image";
	}
	$params = array(
					    'width' => $width,
					    'height' => $height,
					    'crop' => $crop
					);
	$url = vsz_thumb_custom( $url, $params );
	// $image = '<img class="'.$img_class.'" src="'.$url.'"/>';
	return $url;
} */
function resize_image($type,$data,$intHeight=0,$intWidth=0,$image_type='jpg'){
	$id = '';
	if(!empty($type)){
		switch($type){
			//// image url is given
			case 'url':
				if(!empty($data)){
					$image_url = $data;
				}
				else{			/////// data for url is blank
					$msg = "Please pass valid url.";
					echo("<br><font color='red'><b>$msg</b></font><br>");
					return false;
				}
				break;
				
			/////////  image attachment id is given
			case 'id':
				$id=$data;
				if(!empty($data)){
					$url = get_attached_file( $id );
					if(!empty($url)){
						$image_url = $url;
					}
					else{			////////////// given id is not an attachment id
						$msg = "Please pass valid attachment id.";
						echo("<br><font color='red'><b>$msg</b></font><br>");
						return false;
					}
				}
				else{				/////// data for attachment id is blank
					$msg = "Please pass valid attachment id.";
					echo("<br><font color='red'><b>$msg</b></font><br>");
					return false;
				}
				break;
			
			/////////// post is is given (choose featured image)
			case 'featured':
				if(!empty($data)){
					$id = $data;
					$thumb_id = get_post_thumbnail_id( $id );
					if(!empty($thumb_id)){				////// no featured image attached
						$file_path = get_attached_file( $thumb_id );
						if(!empty($file_path)){
							$image_url = $file_path;
						}
						else{					////// unable to retrieve file 
							$msg = "Sorry unable to fetch image path.";
							echo("<br><font color='red'><b>$msg</b></font><br>");
							return false;
						}
					}
					else{
						$msg = "Sorry requested post doesn't have any features image.";
						echo("<br><font color='red'><b>$msg</b></font><br>");
						return false;
					}
				}
				else{			/////// data for post id is blank
					$msg = "Please pass valid post id.";
					echo("<br><font color='red'><b>$msg</b></font><br>");
					return false;
				}
				break;
			
			///////////// default
			default :
				$msg = "Invalid type.";
				echo("<br><font color='red'><b>$msg</b></font><br>");
				return false;
				break;
				
		}
	}
	else{		/////// typeis blank
		$msg = 'Please define data type.';
		echo("<br><font color='red'><b>$msg</b></font><br>");
		return false;
	}
	$objImage = new vsz_image($image_url);
	if($objImage->image_original){
		$objImage->resize($intHeight, $intWidth, '-');
		$res = $objImage->output_resized($id,$image_url, $image_type);
		if($res){
			$upload_info = wp_upload_dir();
			$upload_dir = $upload_info['basedir'];
			$upload_url = $upload_info['baseurl'];
			$res = $upload_url."/resize/".$res;
			return $res;
		}
		else{
			return $res;
		}
	}
}
?>