<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class My_image {

	// function to strip EXIF data from images.
	public function stripEXIF($array,$destination){
		// array['upload_data']['full_path']
		$results = array('destination' => '',
				 'file_name' => '',
				 'format' => '' );

		// If magickwand extension is present
		if (extension_loaded('magickwand') && function_exists("NewMagickWand")) {
			/* ImageMagick is installed and working */

			// Create a new image from the supplied path.
			$img = new Imagick($array['full_path']);
			// Strip EXIF data.
			$img->stripImage();
			// Create a new PNG image, which won't hold EXIF data.
			$img->setImageFormat('png');
			// Create the image.
			$img->writeImage($destination);
			// Clear buffer. 
			$img->clear();

			// Build $results array for oldpath, and the new image location.
			$results['old_path'] = $array['full_path'];
			$results['file_name'] = $array['raw_name'].'.png';
			$results['destination'] = $destination;
			$results['format'] = '.png';
		} elseif (extension_loaded('gd') && function_exists('gd_info')) {
			/* GD is installed and working */

			if($array['file_ext'] == '.png'){			
				// Load PNG image.
				$img = imagecreatefrompng($array['full_path']);
			} elseif($array['file_ext'] == '.jpeg' || $array['file_ext'] == '.jpg'){
				// Load JPEG image.
				$img = imagecreatefromjpeg($array['full_path']);
			} elseif($array['file_ext'] == '.gif' ){
				// Load GIF image
				$img = imagecreatefromgif($array['full_path']);
			}			
			
			// Create a PNG image and write it to the destination.
			imagepng($img, $destination);

			// Return array of old & new image path info.
			$results['old_path'] = $array['full_path'];
			$results['file_name'] = $array['raw_name'].'.png';
			$results['destination'] = $destination;
			$results['format'] = '.png';
		} else {
			// Neither PHP-GD or ImageMagick is installed, no EXIF filtering.
			$results['old_path'] = $array['full_path'];
			$results['file_name'] = $array['file_name'];
			$results['destination'] = $array['full_path'];
			$results['format'] = $array['file_ext'];
		}
		// Return array of path information for old and new image.
		return $results;
	}

	// Return the image contant.
	public function displayImage($imageHash,$height = NULL, $width = NULL){	
		// To get an image, call this function.
		$CI = &get_instance();
		$CI->load->model('images_model');
		
		// Check if the image is already held in the database
		// If the DB is missing the entry, BitWasp will try to find the file, and then encode it and add to the DB.
		$image = $CI->images_model->imageFromDB($imageHash);

        	if($image === FALSE) {
			// Failure; Image identifier is invalid.
			return FALSE;
		} else {
			// Return the values for the <img> tag with base64 encoded image, height/width
			if($height !== NULL){ $displayHeight = $height; } else { $displayHeight = $image['height']; }
			if($width !== NULL){ $displayWidth = $width; } else { $displayWidth = $image['width']; }

			$result = array('imageHash' => $imageHash,
					'encoded' => $image['encoded'],
					'height'  => $displayHeight,
					'width'   => $displayWidth );
			return $result;
			// Success; return Image information.
		}
	}

	// This function is displays an image without adding to the DB. Useful for catpchas.
	public function displayTempImage($identifier){
		$CI = &get_instance();
	
		$image = $this->simpleImageEncode($identifier);
		$validHTML = "<img src=\"data:image/gif;base64,{$image}\" />";
		// Return the <img> tag.
		return $validHTML;
	}


	// This function returns the base64 string from an image.
	public function simpleImageEncode($identifier){
		$imageFile = file_get_contents('./assets/images/'.$identifier);
		// Encode to base64/
		$validImage = base64_encode($imageFile);
	//	$validImage = chunk_split($validImage, 64, "\n");			// Uncomment this for issues with new lines

		return $validImage;
	}



};

