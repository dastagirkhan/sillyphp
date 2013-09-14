<?php
/*******************************************************************
  This file is part of Silly.
 
  Silly is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Silly is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Silly.  If not, see <http://www.gnu.org/licenses/>.
  
  @copyright     Copyright 20012-2013, Silly PHP Framework
  @link          silly.gianstocks.com
  @package       silly
  @since         Silly(tm) v 0.9
  @license       http://www.gnu.org/licenses/
 *******************************************************************/
class images {
	//TODO - Insert your code here
	static $image_ext = array ("png", "gif", "jpg", "jpeg" );
	static $image_type = array ("image/png", "image/gif", "image/jpg", "image/pjpeg", "image/jpeg" );
	
	public function __construct($path = "", $uuid = "", $ext = "", $frame_w = 346, $frame_h = 288, $resize_w = 346, $resize_h = 288) {
		//TODO - Insert your code here
		$this->path = $path;
		$this->uuid = $uuid;
		$this->ext = $ext;
		$this->frame_w = $frame_w;
		$this->frame_h = $frame_h;
		$this->resize_w = $resize_w;
		$this->resize_h = $resize_h;
	}
	public static function is_valid_image_url($url) {
		$headers = @get_headers ( $url, 1 );
		if (isset ( $headers ['Content-Type'] ) && 0 === strncmp ( $headers ['Content-Type'], 'image/', 6 )) {
			$pos = strpos ( $headers ['Content-Type'], "/" ) + 1;
			return substr ( $headers ['Content-Type'], $pos, strlen ( $headers ['Content-Type'] ) );
		}
		return false;
	}
	public static function getExtension($str) {
		$i = strrpos ( $str, "." );
		if (! $i) {
			return "";
		}
		$l = strlen ( $str ) - $i;
		$ext = substr ( $str, $i + 1, $l );
		return $ext;
	}
	/**
	 * 
	 * @param string $path
	 * @param string $filename
	 * returns boolean
	 */
	public function file_recursive_create($path, $filename) {
		if (isset ( $path ) && trim ( $path ) != "" && trim ( $filename ) != "") {
			if (! is_dir ( $path )) {
				if (! mkdir ( $path, 0777, true )) {
					return false;
				}
			}
			$filename = (strlen ( strrchr ( $path, '/' ) ) > 1) ? "$path/$filename" : $path . $filename;
			if (! file_exists ( $filename )) {
				if (! touch ( $filename )) {
					return false;
				}
			}
		} else
			return false;
		
		return true;
	}
	public function upload($path, $file, $extension, $resize = false, $uuid, $width = 100, $height = 0) {
		$this->uuid = $uuid;
		$this->path = $path = trim ( $path );
		$this->ext = $extension;
		if (substr ( $path, strlen ( $path ) - 1, 1 ) != '/')
			$path = $path . "/";
		if (! $this->file_recursive_create ( $path, "$uuid.$extension" ))
			return false;
		if (is_array ( $file ) && count ( $file ) > 0) {
			if (! move_uploaded_file ( $file ["tmp_name"], $path . "$uuid.$extension" ))
				return false;
		}
		if (! is_array ( $file ) && $file != '') {
			if (! file_put_contents ( $path . "$uuid.$extension", file_get_contents ( $file ) ))
				return false;
		}
		if ($resize)
			$this->smart_resize_image ( $path . "$uuid.$extension", $width, $height, true, $path . "$uuid-w$width.$extension", false, false, $extension );
		return true;
	}
	
	public function smart_resize_image($file, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false, $extension) {
		if ($height <= 0 && $width <= 0) {
			return false;
		}
		
		$info = getimagesize ( $file );
		$image = '';
		
		$final_width = 0;
		$final_height = 0;
		list ( $width_old, $height_old ) = $info;
		
		if ($proportional) {
			if ($width == 0)
				$factor = $height / $height_old;
			elseif ($height == 0)
				$factor = $width / $width_old;
			else
				$factor = min ( $width / $width_old, $height / $height_old );
			
			$final_width = round ( $width_old * $factor );
			$final_height = round ( $height_old * $factor );
		
		} else {
			$final_width = ($width <= 0) ? $width_old : $width;
			$final_height = ($height <= 0) ? $height_old : $height;
		}
		
		switch ($info [2]) {
			case IMAGETYPE_GIF :
				$image = imagecreatefromgif ( $file );
				break;
			case IMAGETYPE_JPEG :
				$image = imagecreatefromjpeg ( $file );
				break;
			case IMAGETYPE_PNG :
				$image = imagecreatefrompng ( $file );
				break;
			default :
				return false;
		}
		
		$image_resized = imagecreatetruecolor ( $final_width, $final_height );
		
		if (($info [2] == IMAGETYPE_GIF) || ($info [2] == IMAGETYPE_PNG)) {
			$trnprt_indx = imagecolortransparent ( $image );
			
			// If we have a specific transparent color
			if ($trnprt_indx >= 0) {
				
				// Get the original image's transparent color's RGB values
				$trnprt_color = imagecolorsforindex ( $image, $trnprt_indx );
				
				// Allocate the same color in the new image resource
				$trnprt_indx = imagecolorallocate ( $image_resized, $trnprt_color ['red'], $trnprt_color ['green'], $trnprt_color ['blue'] );
				
				// Completely fill the background of the new image with allocated color.
				imagefill ( $image_resized, 0, 0, $trnprt_indx );
				
				// Set the background color for new image to transparent
				imagecolortransparent ( $image_resized, $trnprt_indx );
			
			} // Always make a transparent background color for PNGs that don't have one allocated already
elseif ($info [2] == IMAGETYPE_PNG) {
				
				// Turn off transparency blending (temporarily)
				imagealphablending ( $image_resized, false );
				
				// Create a new transparent color for image
				$color = imagecolorallocatealpha ( $image_resized, 0, 0, 0, 127 );
				
				// Completely fill the background of the new image with allocated color.
				imagefill ( $image_resized, 0, 0, $color );
				
				// Restore transparency blending
				imagesavealpha ( $image_resized, true );
			}
		}
		
		imagecopyresampled ( $image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old );
		
		if ($delete_original) {
			if ($use_linux_commands)
				exec ( 'rm ' . $file );
			else
				@unlink ( $file );
		}
		
		switch (strtolower ( $output )) {
			case 'browser' :
				$mime = image_type_to_mime_type ( $info [2] );
				header ( "Content-type: $mime" );
				$output = NULL;
				break;
			case 'file' :
				$output = $file;
				break;
			case 'return' :
				return $image_resized;
				break;
			default :
				break;
		}
		
		switch ($info [2]) {
			case IMAGETYPE_GIF :
				imagegif ( $image_resized, $output );
				break;
			case IMAGETYPE_JPEG :
				imagejpeg ( $image_resized, $output );
				break;
			case IMAGETYPE_PNG :
				imagepng ( $image_resized, $output );
				break;
			default :
				return false;
		}
		return true;
	}
	public function smart_image_copy_merge_resize() {
		if ($this->ext == "" && $this->path == "" && $this->uuid == "")
			return false;
		$im = imagecreatetruecolor ( $this->frame_w, $this->frame_h );
		$red = imagecolorallocate ( $im, 255, 0, 0 );
		$black = imagecolorallocate ( $im, 0, 0, 0 );
		
		// Make the background transparent
		imagecolortransparent ( $im, $black );
		
		// Save the image
		imagepng ( $im, "./$this->path/" . $this->uuid . "-transperent.png" );
		imagedestroy ( $im );
		
		# If you don't know the type of image you are using as your originals.
		$this->smart_resize_image ( $this->path . "/$this->uuid.$this->ext", $width = $this->resize_w, $height = $this->resize_h, true, $this->path . "/$this->uuid-w$this->resize_w.$this->ext", false, false, $this->ext );
		$frame_dimension = getimagesize ( "./$this->path/" . $this->uuid . "-transperent.png" );
		$image_dimension = getimagesize ( "./$this->path/" . $this->uuid . "-w$this->resize_w." . $this->ext );
		$image = imagecreatefromstring ( file_get_contents ( "./$this->path/" . $this->uuid . "-w$this->resize_w." . $this->ext ) );
		$frame = imagecreatefromstring ( file_get_contents ( "./$this->path/" . $this->uuid . "-transperent.png" ) );
		
		$xoffset = ($frame_dimension [0] - $image_dimension [0]) / 2;
		$yoffset = ($frame_dimension [1] - $image_dimension [1]) / 2;
		imagecopymerge ( $frame, $image, $xoffset, $yoffset, 0, 0, $this->resize_w, $this->resize_h, 100 );
		# Save the image to a file
		unlink ( "./$this->path/" . $this->uuid . "-w$this->resize_w." . $this->ext );
		imagepng ( $frame, "./$this->path/" . $this->uuid . "-w$this->resize_w.png" );
		unlink ( "./$this->path/" . $this->uuid . "-transperent.png" );
		return true;
	}
}
?>