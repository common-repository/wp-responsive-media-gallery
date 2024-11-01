<?PHP
/************************************************************************************************
	Purpose: class to handle image resize; can output to file or directly to browser
	Author:	Yuriy Horobey, yuriy@horobey.com
	Property: Horobey Freelance & Telecommuting
	URL: http://horobey.com
	Date: 12.04.2003
************************************************************************************************/

// here you can include your own language error file
include_once "" . dirname(__FILE__) . "/" . "vsz_image_errors.class.php";

	//this class works with image
	class vsz_image {
		var $image_original;
		var $file_original;
		var $image_original_width;
		var $image_original_height;
		var $image_original_type_code;
		var $image_original_type_abbr;
		var $image_original_html_sizes;
		var $image_new_width;
		var $image_new_height;

		var $image_resized;
		var $file_resized;
		var $image_resized_width;
		var $image_resized_height;
		var $image_resized_type_code;
		var $image_resized_type_abbr;
		var $image_resized_html_sizes;

		//some settings
		var $jpeg_quality;
		var $use_gd2;


		function vsz_image($file_original) {
			//constructor of the class
			//it takes given file and creates image out of it
			global $ERR;
			$this->clear(); // clear all.

			if(file_exists($file_original))	{
				$this->file_original = $file_original;
				$this->image_original = $this->imagecreatefromfile($file_original);
				if(!$this->image_original) {
					$this->error($ERR["NO_IMAGE_FOR_OUTPUT"]." file=$file_original");
					return false;
				}


			} else {
				$this->error($ERR["FILE_DOESNOT_EXSIT"]." file=$file_original");
				return false;
			}
		}

		function clear() {
			// clear all the class member varaibles

				$this->image_original			=	0;
				$this->file_original			=	"";
				$this->image_original_width		=	0;
				$this->image_original_height	=	0;
				$this->image_original_type_code	=	0;
				$this->image_original_type_abbr	=	"";
				$this->image_original_html_sizes=	"";
				$this->image_new_width			=	0;
				$this->image_new_height			=	0;

				$this->image_resized			=	0;
				$this->file_resized				=	"";
				$this->image_resized_width		=	0;
				$this->image_resized_height		=	0;
				$this->image_resized_type_code	=	-1;
				$this->image_resized_type_abbr	=	"";
				$this->image_resized_html_sizes	=	"";

				$this->set_parameters();

		}

		function set_parameters($jpeg_quality="85", $use_gd2=true) {

			$this->jpeg_quality=$jpeg_quality;
			$this->use_gd2=$use_gd2;
		}

		function error($msg) {
			//error messages and debug info:
			// here you can implement your own error handling
			// echo("<hr color='red'><font color='red'><b>$msg</b></font><br> file=<b>".__FILE__."</b><hr color='red'>");
			echo("<br><font color='red'><b>$msg</b></font><br>");
		}


		function imagecreatefromfile($img_file) {
			global $ERR;
			$img=0;
			$img_sz =  getimagesize( $img_file ); 	## returns array with some properties like dimensions and type;
			####### Now create original image from uploaded file. Be carefull! GIF is often not supported, as far as I remember from GD 1.6
			switch( $img_sz[2] ) {
				case 1:
					$img = $this->_imagecheckandcreate("ImageCreateFromGif", $img_file);
					$img_type = "GIF";
				break;
				case 2:
					$img = $this->_imagecheckandcreate("ImageCreateFromJpeg", $img_file);
					$img_type = "JPG";
				break;
				case 3:
					$img = $this->_imagecheckandcreate("ImageCreateFromPng", $img_file);
					$img_type = "PNG";
				break;
				// would be nice if this function will be finally supported
				case 4:
					$img = $this->_imagecheckandcreate("ImageCreateFromSwf", $img_file);
					$img_type = "SWF";
				break;
				default:
					$img = 0;
					$img_type = "UNKNOWN";
					$this->error($ERR["IMG_NOT_SUPPORTED"]." $img_file");
					break;

			}//case

			if($img) {
				$this->image_original_width=$img_sz[0];
				$this->image_original_height=$img_sz[1];
				$this->image_original_type_code=$img_sz[2];
				$this->image_original_type_abbr=$img_type;
				$this->image_original_html_sizes=$img_sz[3];

			}else {
				$this->clear();

			}

			return $img;
		}


		function _imagecheckandcreate($function, $img_file) {
			//inner function used from imagecreatefromfile().
			//Checks if the function exists and returns
			//created image or false
			global $ERR;
			if(function_exists($function)) {
				$img = $function($img_file);
			}else{
				$img = false;
				$this->error($ERR["FUNCTION_DOESNOT_EXIST"]." ".$function);
			}

			return $img;

		}

		function resize($desired_width, $desired_height, $mode="-") {
			//this is core function--it resizes created image
			//if any of parameters == "*" then no resizing on this parameter
			//>> mode = "+" then image is resized to cover the region specified by desired_width, _height
			//>> mode = "-" then image is resized to fit into the region specified by desired_width, _height
			// width-to-height ratio is all the time the same
			//>>mode=0 then image will be exactly resized to $desired_width _height.
			//geometrical distortion can occur in this case.
			// say u have picture 400x300 and there is circle on the picture
			//now u resized in mode=0 to 800x300 -- circle shape will be distorted and will look like ellipse.
			//GD2 provides much better quality but is not everywhere installed
			global $ERR;
			if($desired_width == "*" && $desired_height == "*") {
				$this->image_resized = $this->image_original;
				$this->image_new_width = $this->image_original_width;
				$new_width = $this->image_new_width;
				$this->image_new_height = $this->image_original_height;
				$new_height = $this->image_new_height;
				goto afterSwitch;
				Return true;
			}
			if($this->image_original_width == 0 || $this->image_original_height == 0){
				$this->image_resized = $this->image_original;
				$this->image_new_width = $this->image_original_width;
				$new_width = $this->image_new_width;
				$this->image_new_height = $this->image_original_height;
				$new_height = $this->image_new_height;
				goto afterSwitch;
				Return true;
			}
			
			switch($mode) {
				case "-":
				case '+':
					//multipliers
					if($desired_width != "*") $mult_x = $desired_width / $this->image_original_width;
					if($desired_height != "*") $mult_y = $desired_height / $this->image_original_height;
					$ratio = $this->image_original_width / $this->image_original_height;

					if($desired_width == "*") {
						$new_height = $desired_height;
						$new_width = $ratio * $desired_height;
					}elseif($desired_height == "*") {
						$new_height = $desired_width / $ratio;
						$new_width =  $desired_width;
					}else{
						if($mode=="-") {
								if( $this->image_original_height * $mult_x < $desired_height ) {
								//image must be smaller than given $desired_ region
								//test which multiplier gives us best result

									//$mult_x does the job
									$new_width = $desired_width;
									$new_height = $this->image_original_height * $mult_x;
								}else{
									//$mult_y does the job
									$new_width = $this->image_original_width * $mult_y;
									$new_height = $desired_height;
								}

						}else{
							//mode == "+"
							// cover the region
							//image must be bigger than given $desired_ region
							//test which multiplier gives us best result
							if( $this->image_original_height * $mult_x > $desired_height ) {
								//$mult_x does the job
								$new_width = $desired_width;
								$new_height = $this->image_original_height * $mult_x;
							}else{
								//$mult_y does the job
								$new_width = $this->image_original_width * $mult_y;
								$new_height = $desired_height;
							}

						}
					}
					$this->image_new_width = $new_width;
					$this->image_new_height = $new_height;
				break;

				case '0':
					//fit the region exactly.
					if($desired_width == "*") $desired_width = $this->image_original_width;
					if($desired_height == "*") $desired_height = $this->image_original_height;
					$new_width = $desired_width;
					$new_height = $desired_height;
					
					$this->image_new_width = $new_width;
					$this->image_new_height = $new_height;

				break;
				default:
					$this->error($ERR["UNKNOWN_RESIZE_MODE"]."  $mode");
				break;
			}
			afterSwitch:
			// OK here we have $new_width _height
			//create destination image checking for GD2 functions:
			if( $this->use_gd2 ) {
				if( function_exists("imagecreatetruecolor")) {
					$this->image_resized = imagecreatetruecolor($new_width, $new_height) or $this->error($ERR["GD2_NOT_CREATED"]);
				}else {
					$this->error($ERR["GD2_UNAVALABLE"]." ImageCreateTruecolor()");
				}
			} else {


				$this->image_resized = imagecreate($new_width, $new_height) or $this->error($ERR["IMG_NOT_CREATED"]);
			}
			//Resize
			if( $this->use_gd2 ) {

				if( function_exists("imagecopyresampled")) {
					
					if($this->image_original_type_abbr == "PNG" && $this->checkPNGTransperent($this->image_original)){
					
						imagealphablending($this->image_resized, true);
						imagesavealpha($this->image_resized, true);
						$trans_layer_overlay = imagecolorexactalpha($this->image_resized, 199, 158, 65, 127);
						imagefill($this->image_resized, 0, 0, $trans_layer_overlay);
						$res = imagecopyresampled($this->image_resized,
												  $this->image_original,
												  0, 0,  //dest coord
												  0, 0,			//source coord
												  $new_width, $new_height, //dest sizes
												  $this->image_original_width, $this->image_original_height // src sizes
												) or $this->error($ERR["GD2_NOT_RESIZED"]);
						
						/* imagealphablending($this->image_resized,true);
						imagesavealpha($this->image_resized, true);
						$col=imagecolorallocatealpha($this->image_resized,199,158,65,127);
						imagefill($this->image_resized,0,0,$col); */
					}
					else{
						$res = imagecopyresampled($this->image_resized,
												  $this->image_original,
												  0, 0,  //dest coord
												  0, 0,			//source coord
												  $new_width, $new_height, //dest sizes
												  $this->image_original_width, $this->image_original_height // src sizes
												) or $this->error($ERR["GD2_NOT_RESIZED"]);
						
					}
				}else {
					$this->error($ERR["GD2_UNAVALABLE"]." ImageCopyResampled()");
				}
			} else {
				
				if($this->image_original_type_abbr == "PNG" && $this->checkPNGTransperent($this->image_original)){
				
					imagealphablending($this->image_resized, true);
					imagesavealpha($this->image_resized, true);
					$trans_layer_overlay = imagecolorexactalpha($this->image_resized, 199, 158, 65, 127);
					imagefill($this->image_resized, 0, 0, $trans_layer_overlay);
					
					$res = imagecopyresized($this->image_resized,
											  $this->image_original,
											  0, 0,  //dest coord
											  0, 0,			//source coord
											  $new_width, $new_height, //dest sizes
											  $this->image_original_width, $this->image_original_height // src sizes
											) or $this->error($ERR["IMG_NOT_RESIZED"]);
											
					/* imagealphablending($this->image_resized,true);
					imagesavealpha($this->image_resized, true);
					$col=imagecolorallocatealpha($this->image_resized,199,158,65,127);
					imagefill($this->image_resized,0,0,$col); */
					
				}
				else {
					$res = imagecopyresized($this->image_resized,
											  $this->image_original,
											  0, 0,  //dest coord
											  0, 0,			//source coord
											  $new_width, $new_height, //dest sizes
											  $this->image_original_width, $this->image_original_height // src sizes
											) or $this->error($ERR["IMG_NOT_RESIZED"]);
				}							
			}
			
		}

		function output_original($postId,$destination_file, $image_type="JPG") {
            //outputs original image
            //if destination file is empty  image will be output to browser
            // right now $image_type can be JPG or PNG
            return _output_image($postId,$destination_file, $image_type, $this->image_original);
        }

		function output_resized($postId,$destination_file, $image_type="JPG") {
            //if destination file is empty  image will be output to browser
            // right now $image_type can be JPG or PNG
            $res = $this->_output_image($postId,$destination_file, $image_type, $this->image_resized);
            if(trim($destination_file)) {
                $sz=@getimagesize($destination_file);
                $this->file_resized = $destination_file;
                $this->image_resized_width = $sz[0];
                $this->image_resized_height = $sz[1];
                $this->image_resized_type_code=$sz[2];
                $this->image_resized_html_sizes=$sz[3];
                    //only jpeg and png are really supported, but I'd like to think of future
                switch($this->image_resized_type_code) {
                    case 0:
                        $this->image_resized_type_abbr = "GIF";
                    break;
                    case 1:
                        $this->image_resized_type_abbr = "JPG";
                    break;
                    case 2:
                        $this->image_resized_type_abbr = "PNG";
                    break;
                    case 3:
                        $this->image_resized_type_abbr = "SWF";
                    break;
                    default:
                        $this->image_resized_type_abbr = "UNKNOWN";
                    break;
                }

            }
			
			if($this->image_original_type_abbr == "PNG" && $this->checkPNGTransperent($this->image_resized)){
				
				// get resizedImg dimentions
				$new_width = imagesx($this->image_resized);
				$new_height = imagesy($this->image_resized);				

				$thumb = imagecreatetruecolor($new_width, $new_height);
				
				/* imagealphablending($this->image_resized, true);
				imagesavealpha($this->image_resized, true);
				$trans_layer_overlay = imagecolorexactAlpha($this->image_resized, 0, 0, 0, 127);
				imagefill($this->image_resized, 0, 0, $trans_layer_overlay); */
				
				imagealphablending($thumb, true);
				imagesavealpha($thumb, true);
				$trans_layer_overlay = imagecolorexactalpha($thumb, 199, 158, 65, 127);
				imagefill($thumb, 0, 0, $trans_layer_overlay);
				
				imagecopyresampled($thumb, $this->image_resized, 0, 0, 0, 0, $new_width, $new_height, $new_width, $new_height);
				
				/* imagealphablending($thumb,true);
				imagesavealpha($thumb, true);
				$col=imagecolorallocatealpha($thumb,199,158,65,127);
				imagefill($thumb,0,0,$col); */
				
				imagepng($thumb, $destination_file);
				
				$this->image_resized = imagecreatefrompng($destination_file);
				
			}
            return $res;
        }

        function _output_image($postId,$destination_file, $image_type, $image) {
            //if destination file is empty  image will be output to browser
            // right now $image_type can be JPEG or PNG
            global $ERR;
			
				$upload_info = wp_upload_dir();
				$upload_dir = $upload_info['basedir'];
				$upload_url = $upload_info['baseurl'];
				$theme_url = get_template_directory_uri();
				$theme_dir = get_template_directory();
				
			$new_width = $this->image_new_width;
			$new_height = $this->image_new_height;
			$nameArray = explode("/",$destination_file);
			$num = count($nameArray);
			$fileName = $nameArray[$num-1];
			$fileNameArray = explode(".",$fileName);
			$name_num = count($fileNameArray);
			unset($fileNameArray[$name_num-1]);
			$fileName = implode(".",$fileNameArray);
			unset($nameArray[$num-1]);
			$destination_file1 = implode("/",$nameArray);
			$fileName = $postId."-".$new_width."x".$new_height.$fileName.".".strtolower($image_type);
			$destination_file = $upload_dir."/resize/".$fileName;
			if(file_exists($destination_file))	{
				return $fileName;
			}
			else{
				$destination_file = trim($destination_file);
				$res = false;
				if($image) {
					switch($image_type) {
						case 'jpeg':
						case 'jpg':
						case 'JPEG':
						case 'JPG':
							// header("Content-type: image/jpeg");
							$res = ImageJpeg($image, $destination_file, $this->jpeg_quality);
							// header("Content-type: text/html");
						break;
						case 'png':
						case 'PNG':
							// header("Content-type: image/png");
							$res = Imagepng($image, $destination_file);
							// header("Content-type: text/html");
						break;
						default:
							$this->error($ERR["UNKNOWN_OUTPUT_FORMAT"]." $image_type");
						break;

					}
				}else{
					$this->error($ERR["NO_IMAGE_FOR_OUTPUT"]);
				}
				if(!$res) $this->error($ERR["UNABLE_TO_OUTPUT"]." $destination_file");
			}
            return $fileName;
        }
		
		/*
		This function is used for check given PNG image contain any transperent potion or not
		*/
		function checkPNGTransperent($imgdata) {
			$w = imagesx($imgdata);
			$h = imagesy($imgdata);

			if($w>50 || $h>50){ //resize the image to save processing if larger than 50px:
				$thumb = imagecreatetruecolor(10, 10);
				imagealphablending($thumb, true);
				imagesavealpha($thumb, true);
				$trans_layer_overlay = imagecolorexactalpha($thumb, 199, 158, 65, 127);
				imagefill($thumb, 0, 0, $trans_layer_overlay);
				imagecopyresized( $thumb, $imgdata, 0, 0, 0, 0, 10, 10, $w, $h );
				$imgdata = $thumb;
				$w = imagesx($imgdata);
				$h = imagesy($imgdata);
			}
			//run through pixels until transparent pixel is found:
			for($i = 0; $i<$w; $i++) {
				for($j = 0; $j < $h; $j++) {
					$rgba = imagecolorat($imgdata, $i, $j);
					if(($rgba & 0x7F000000) >> 24) return true;
				}
			}
			return false;
		}
}


?>