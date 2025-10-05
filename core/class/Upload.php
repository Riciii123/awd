<?php

/**
 * UPLOAD
 *
 * This library is licensed software; you can't redistribute it and/or
 * modify it without the permission.
 *
 * @author     Nixo
 * @package    Core
 * @copyright  DATKO.TECH & AWEBIN.SK (c) 2014 - 2020
 * @license    intranet-one
 * @version    0.36
 */

class Upload {

	private 
	/* params */
		$_max_file_length,
		$_allowedExt = array(),
		$_allowedImageOnly = false,
		$_unallowedExt = array(),
		$_image_types = array(
			"gif" => 1,
			"jpg" => 2,
			"png" => 3
		),
	/* Data */
		$_file,
		$_filename,
		$_fullname,
		$_ext,
		$_img_res,
	/* Error */	
		$_error;

	public function __construct($variable = 'file') {
		$this->_file = $_FILES[$variable];
		$this->parseName();
	}

	private function parseName() {
		/*
		$extension = explode('.', $this->_file['name']);
		$extension = strtolower(end($extension));
		$this->_ext = $extension;
		*/
		$path_parts = pathinfo($this->_file['name']);
		
		$this->_ext = strtolower($path_parts['extension']);
		$this->_filename = $path_parts['filename'];
		$this->_fullname = $this->_file['name'];
	}

	public function isFileError() {
		if($this->_file['error'] === 0) {
			return false;
		}
		return true;
	}

	public function getFullName() {
		return $this->_file['name'];
	}

	public function getFileName() {
		return $this->_filename;
	}

	public function getExt() {
		return $this->_ext;
	}

	public function getFileSize() {
		return $this->_file['size'];
	}

	public function getTempName() {
		return $this->_file['tmp_name'];
	}

	public function allowedExt($ext) {
		$this->_allowedExt = $ext;
	}

	public function allowedImageOnly() {
		$this->_allowedImageOnly = true;
	}

	public function unallowedExt($ext) {
		$this->_unallowedExt = $ext;
	}

	public function maxFileLength($size) {
		$this->_max_file_length = $size;
	}

	public function deleteFile() {
		@unlink($this->_file['tmp_name']);
	}

	public function isImage() {
		$res = getimagesize($this->getTempName());
		if($res === false) {
			$this->_error = "FILE_NOT_IMAGE";
			return false;
		}
		if(!in_array($res[2], $this->_image_types)) {
			$this->_error = "FILE_NOT_SUPPORTED_IMAGE";
			return false;
		}
		$this->_img_res = array($res[0], $res[1]);
		return true;
	}

	public function getImgRes() {
		return $this->_img_res;
	}

	public function upload($destination) {
		if(empty($destination)) {
			$destination = $this->_filename;
		}

		if(!$this->check()) {
			$this->deleteFile();
			return false;
		}

		if(@!move_uploaded_file($this->_file['tmp_name'], $destination)) {
			$this->_error = "FILE_CANT_SAVE";
			return false;
		}

		return true;
	}

	public function check() {
		if($this->isFileError()) {
			$this->_error = "FILE_NOT_UPLOADED";
			return false;
		}

		if($this->checkAllowedExt() === false) {
			$this->_error = "FILE_NOT_ALLOWED_EXT";
			return false;
		}

		if($this->checkUnallowedExt() ) {
			$this->_error = "FILE_NOT_ALLOWED_EXT";
			return false;
		}

		if($this->checkAllowedImage() === false) {
			return false;
		}

		if(!$this->checkMaxFileLength() ) {
			$this->_error = array("FILE_EXCEEDED_SIZE", sprintf("%.1f", $this->_max_file_length/1024/1024));
			return false;
		}
		return true;
	}

	public function checkAllowedExt() {
		if(empty($this->_allowedExt)) {
			return true;
		}
		if(in_array($this->_ext, $this->_allowedExt)) {
			return true;
		}
		return false;
	}

	public function checkAllowedImage() {
		if($this->_allowedImageOnly) {
			 return $this->isImage();
		}
		return true;
	}

	public function checkUnallowedExt() {
		if(empty($this->_unallowedExt)) {
			return false;
		}
		if(in_array($this->_ext, $this->_unallowedExt)) {
			return true;
		}
		return false;
	}

	public function checkMaxFileLength() {
		if(empty($this->_max_file_length)) {
			return true;
		}
		if($this->_file['size'] < $this->_max_file_length) {
			return true;
		}
		return false;
	}

	public function error() {
		return $this->_error;
	}
}