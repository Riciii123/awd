<?php

/**
 * IMAGE 
 *
 * This library is licensed software; you can't redistribute it and/or
 * modify it without the permission.
 *
 * @author     Nixo
 * @package    Core
 * @copyright  DATKO.TECH & AWEBIN.SK (c) 2014 - 2020
 * @license    intranet-one
 * @version    0.71
 */

class Image {

	/* Params */
	private static 
		$types = array(
			"gif" => 1,
			"jpg" => 2,
			"png" => 3
			),
		$jpgQuality = 90,   // 0 (worst quality, smaller file) to 100 (best quality)
		$pngQuality = 0;    // from 0 (no compression) to 9.

	private static
		$error;

	private static function isSupportedFormat($id) {
		return in_array($id, self::$types);
	}

	private static function init() {
		self::$error = false;
		return;
	}

	public static function error() {
		return self::$error;
	}

	public static function isError() {
		return !empty(self::$error);
	}


	/*
	no enlargement image (only reduction size of image)
	*/
	public static function resize($file_src, $file_dest, $MaxWidth, $MaxHeight, $typeSave = "jpg")
	{
		self::init();
		$typeSave = self::$types[$typeSave];

		$data = getimagesize($file_src);
		if($data === false) {
			self::$error = "FILE_NOT_IMAGE";
			return false;
		}

		list($OrigWidth, $OrigHeight, $type) = $data;

		if(!self::isSupportedFormat($type)) {
			self::$error = "FILE_NOT_SUPPORTED_IMAGE";
			return false;
		}

		if ($MaxWidth == 0) $MaxWidth = $OrigWidth;
		if ($MaxHeight == 0) $MaxHeight = $OrigHeight;

		$pw = $OrigWidth / $MaxWidth;
		$ph = $OrigHeight / $MaxHeight;

		if ($file_src == $file_dest && $pw <= 1 && $ph <= 1)
			return true;
		if ($pw <= 1 && $ph <= 1 && $type == $typeSave)	{
			@copy($file_src, $file_dest);
			return true;
		}		

		if ($pw > $ph)
			$p = $pw;
		else
			$p = $ph;

		if ($p < 1) $p = 1;

		$NewWidth = (int) round($OrigWidth / $p);
		$NewHeight = (int) round($OrigHeight / $p);

		$image_new = imagecreatetruecolor($NewWidth, $NewHeight);
		$white = imagecolorallocate($image_new, 255, 255, 255);
		imagefill($image_new, 0, 0, $white);
		switch ($type)
		{
			case 1:   // gif
				$image = imagecreatefromgif($file_src);
				break;
			case 2:   // jpeg
				$image = imagecreatefromjpeg($file_src);
				break;
			case 3:  // png
				$image = imagecreatefrompng($file_src);
				break;
		}
	
		imagecopyresampled($image_new, $image, 0, 0, 0, 0, $NewWidth, $NewHeight, $OrigWidth, $OrigHeight);

		switch ($typeSave)
		{
			case 1:   // gif
				imagegif($image_new, $file_dest);
				break;
			case 2:   // jpeg
				imagejpeg($image_new, $file_dest, self::$jpgQuality);
				break;
			case 3:  // png
				imagepng($image_new, $file_dest, self::$pngQuality);
				break;
		}

		imagedestroy($image);
		imagedestroy($image_new);
		return true;
	} 

	/*
	thumbs (cutting and resizing to specified size)
	*/
	public static function thumbs($file_src, $file_dest, $ThumbWidth, $ThumbHeight, $typeSave = "jpg")
	{
		self::init();
		$typeSave = self::$types[$typeSave];

		$data = getimagesize($file_src);
		if($data === false) {
			self::$error = "FILE_NOT_IMAGE";
			return false;
		}

		list($OrigWidth, $OrigHeight, $type) = $data;

		if(!self::isSupportedFormat($type)) {
			self::$error = "FILE_NOT_SUPPORTED_IMAGE";
			return false;
		}

		$ratio = $OrigWidth / $OrigHeight;
		$ratioNorm = $ThumbWidth / $ThumbHeight;
		
		if ($ratio >= $ratioNorm)
		{
			$NewWidth = round($ratioNorm * $OrigHeight);
			$NewHeight = $OrigHeight;
			$offset_x = round(($OrigWidth - $NewWidth)/2);
			$offset_y = 0;
		}
		if ($ratio < $ratioNorm)
		{
			$NewWidth = $OrigWidth;
			$NewHeight = round($OrigWidth / $ratioNorm);
			$offset_x = 0;
			$offset_y = round(($OrigHeight - $NewHeight)/2);
		}
		
		$image_new = imagecreatetruecolor($ThumbWidth, $ThumbHeight);
		$white = imagecolorallocate($image_new, 255, 255, 255);
		imagefill($image_new, 0, 0, $white);
		switch ($type)
		{
			case 1:   // gif
				$image = imagecreatefromgif($file_src);
				break;
			case 2:   // jpeg
				$image = imagecreatefromjpeg($file_src);
				break;
			case 3:  // png
				$image = imagecreatefrompng($file_src);
				break;
		}

		imagecopyresampled($image_new, $image, 0, 0, $offset_x, $offset_y, $ThumbWidth, $ThumbHeight, $NewWidth, $NewHeight);

		switch ($typeSave)
		{
			case 1:   // gif
				imagegif($image_new, $file_dest);
				break;
			case 2:   // jpeg
				imagejpeg($image_new, $file_dest, self::$jpgQuality);
				break;
			case 3:  // png
				imagepng($image_new, $file_dest, self::$pngQuality);
				break;
		}

		imagedestroy($image);
		imagedestroy($image_new);
		return true;		
	} 

	/*
	params:
		ratio: (width : height)
		color: "auto", hex number
	*/
	public static function resizeFillColor($file_src, $file_dest, $maxWidth, $ratio, $color = null, $typeSave = "jpg")
	{
		self::init();
		$typeSave = self::$types[$typeSave];

		$data = getimagesize($file_src);
		if($data === false) {
			self::$error = "FILE_NOT_IMAGE";
			return false;
		}

		list($origWidth, $origHeight, $type) = $data;

		if(!self::isSupportedFormat($type)) {
			self::$error = "FILE_NOT_SUPPORTED_IMAGE";
			return false;
		}

		if($maxWidth == 0)
			$maxWidth = $origWidth;
		$maxHeight = (int) ($maxWidth / $ratio);
		$ratio_orig = $origWidth / $origHeight;
		if($maxWidth > $origWidth) {
			if($ratio_orig >= $ratio) {
				$maxWidth = $origWidth;
				$maxHeight = (int) round($origHeight / $ratio);
			}
			else {
				$maxHeight = $origHeight;
				$maxWidth = (int) round($maxWidth * $ratio);
			}
		}

		if ($ratio_orig > $ratio) {
			$p = $origWidth / $maxWidth;
			$newWidth = (int) round($origWidth / $p);
			$newHeight = (int) round($newWidth / $ratio);
			$newWidthImg = $newWidth;
			$newHeightImg = (int) round($origHeight / $p);
			$offset_x = 0;
			$offset_y = round(($newHeight - $newHeightImg)/2);
		}
		else {
			$p = $origHeight / $maxHeight;
			$newHeight = (int) round($origHeight / $p);
			$newWidth = (int) round($newHeight * $ratio);
			$newHeightImg = $newHeight;
			$newWidthImg = (int) round($origWidth / $p);
			$offset_x = round(($newWidth - $newWidthImg)/2);
			$offset_y = 0;
		}
			/*
			$html = "Original: ".$origWidth ." x " . $origHeight . " (" . $ratio_orig . ")<br>";
			$html .= "New size: ".$newWidth ." x " . $newHeight . "<br>";
			$html .= "Object resize: ".$newWidthImg ." x " . $newHeightImg . "<br>";
			$html .= "Offset: x=".$offset_x ."  y=" . $offset_y . "<br>";
			Notify::set("info", $html);
			*/
		
		switch ($type)
		{
			case 1:   // gif
				$image = imagecreatefromgif($file_src);
				break;
			case 2:   // jpeg
				$image = imagecreatefromjpeg($file_src);
				break;
			case 3:  // png
				$image = imagecreatefrompng($file_src);
				break;
		}
	
		$image_new = imagecreatetruecolor($newWidth, $newHeight);	
		
		if(isset($color)) {
			if(is_string($color)) {
				if($color == "auto") {
					$pixels = array(
						array(
							"x" => 2,
							"y" => 2,
							),
						array(
							"x" => $origWidth - 2,
							"y" => 2,
							),
						array(
							"x" => $origWidth - 2,
							"y" => $origHeight - 2,
							),
						array(
							"x" => 2,
							"y" => $origHeight - 2,
							),
						);

					$pix_colors = array();
					foreach($pixels as $xy) {
						$index_c = imagecolorat($image, $xy["x"], $xy["y"]);
						if(in_array($index_c, $pix_colors)) {
							$new_c = $index_c;
						}
						$pix_colors[] = $index_c;
					}
					if(!isset($new_c)) {
						$new_c = $pix_colors[0];
					}
					$new_colors = imagecolorsforindex($image, $new_c);
					$fill_color = imagecolorallocate($image_new, $new_colors["red"], $new_colors["green"], $new_colors["blue"]);	
				}
				else {
					$color = str_split($color, 2);
					$fill_color = imagecolorallocate($image_new, hexdec($color[0]), hexdec($color[1]), hexdec($color[2]));	
				}
			}
			else if(is_array($color)) {
				$fill_color = imagecolorallocate($image_new, $color[0], $color[1], $color[2]);	
			}
		}
		else {
			$fill_color = imagecolorallocate($image_new, 255, 255, 255);
		}
	
		imagefill($image_new, 0, 0, $fill_color);
		
		imagecopyresampled($image_new, $image, $offset_x, $offset_y, 0, 0, $newWidthImg, $newHeightImg, $origWidth, $origHeight);

		switch ($typeSave)
		{
			case 1:   // gif
				imagegif($image_new, $file_dest);
				break;
			case 2:   // jpeg
				imagejpeg($image_new, $file_dest, self::$jpgQuality);
				break;
			case 3:  // png
				imagepng($image_new, $file_dest, self::$pngQuality);
				break;
		}

		imagedestroy($image);
		imagedestroy($image_new);
		return true;
	} 


	public static function resizeCut($file_src, $file_dest, $maxWidth, $ratio, $typeSave = "jpg")
	{
		self::init();
		$typeSave = self::$types[$typeSave];

		$data = getimagesize($file_src);
		if($data === false) {
			self::$error = "FILE_NOT_IMAGE";
			return false;
		}

		list($origWidth, $origHeight, $type) = $data;

		if(!self::isSupportedFormat($type)) {
			self::$error = "FILE_NOT_SUPPORTED_IMAGE";
			return false;
		}

		$ratio_orig = $origWidth / $origHeight;
		$maxHeight = round($maxWidth / $ratio);

		if($maxWidth > $origWidth || $maxHeight > $origHeight) {
			if($ratio_orig <= $ratio) {
				$maxWidth = $origWidth;
				$maxHeight = (int) round($maxWidth / $ratio);
			}
			else {
				$maxHeight = $origHeight;
				$maxWidth = (int) round($maxHeight * $ratio);
			}
		}

		$thumbWidth = $maxWidth;
		$thumbHeight = $maxHeight;

		if($ratio_orig >= $ratio)
		{
			$newWidth = round($ratio * $origHeight);
			$newHeight = $origHeight;
			$offset_x = round(($origWidth - $newWidth)/2);
			$offset_y = 0;
		}
		else {
			$newWidth = $origWidth;
			$newHeight = round($origWidth / $ratio);
			$offset_x = 0;
			$offset_y = round(($origHeight - $newHeight)/2);
		}
		
		$image_new = imagecreatetruecolor($thumbWidth, $thumbHeight);
		$white = imagecolorallocate($image_new, 255, 255, 255);
		imagefill($image_new, 0, 0, $white);
		switch ($type)
		{
			case 1:   // gif
				$image = imagecreatefromgif($file_src);
				break;
			case 2:   // jpeg
				$image = imagecreatefromjpeg($file_src);
				break;
			case 3:  // png
				$image = imagecreatefrompng($file_src);
				break;
		}

		imagecopyresampled($image_new, $image, 0, 0, $offset_x, $offset_y, $thumbWidth, $thumbHeight, $newWidth, $newHeight);

		switch ($typeSave)
		{
			case 1:   // gif
				imagegif($image_new, $file_dest);
				break;
			case 2:   // jpeg
				imagejpeg($image_new, $file_dest, self::$jpgQuality);
				break;
			case 3:  // png
				imagepng($image_new, $file_dest, self::$pngQuality);
				break;
		}

		imagedestroy($image);
		imagedestroy($image_new);
		return true;		
	} 


}