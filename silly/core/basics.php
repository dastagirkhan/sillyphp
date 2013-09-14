<?php
/***************************************
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
 ***************************************/
function print_data($d = array()) {
	echo '<pre>';
	print_r ( $d );
	echo '</pre>';
}
/**
 * @param string $set
 */
function set_content_type($set = "") {
	if ($set == "") {
		$HTTP_ACCEPT = (explode ( ",", $_SERVER ['HTTP_ACCEPT'] ));
		foreach ( $HTTP_ACCEPT as $ACCEPT ) {
			switch ($ACCEPT) {
				case "application/json" :
					header ( "Content-type: application/json" );
					break;
				case "application/xml" :
					header ( "Content-type: application/xml" );
					break;
			}
		}
	} else
		header ( "Content-type: $set" );
}
/**
 * @param string $url
 */
function is_valid_url($url) {
	return preg_match ( '|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url );
}
/**
 * @param integer $timestamp
 */
function is_valid_timeStamp($timestamp) {
	return (( string ) ( int ) $timestamp === $timestamp) && ($timestamp <= PHP_INT_MAX) && ($timestamp >= ~ PHP_INT_MAX);
}
/**
 * generate unique admin id  
 * using for table id's
 */
function uuid() {
	return sprintf ( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x', // 32 bits for "time_low"
	mt_rand ( 0, 0xffff ), mt_rand ( 0, 0xffff ), 

	// 16 bits for "time_mid"
	mt_rand ( 0, 0xffff ), 

	// 16 bits for "time_hi_and_version",
	// four most significant bits holds version number 4
	mt_rand ( 0, 0x0fff ) | 0x4000, 

	// 16 bits, 8 bits for "clk_seq_hi_res",
	// 8 bits for "clk_seq_low",
	// two most significant bits holds zero and one for variant DCE1.1
	mt_rand ( 0, 0x3fff ) | 0x8000, 

	// 48 bits for "node"
	mt_rand ( 0, 0xffff ), mt_rand ( 0, 0xffff ), mt_rand ( 0, 0xffff ) );
}
/**  
 * @param integer $length
 * @param sting $mode
 * Generates alphanumeric string 
 * for passwords & unique keys with sticker
 */
function random_alphanumeric($length = 7, $mode = 'lower', $sticker = '') {
	//http://phpgoogle.blogspot.fr/2007/08/four-ways-to-generate-unique-id-by-php.html 	
	//set the random id length 
	$random_id_length = $length;
	
	//generate a random id encrypt it and store it in $rnd_id 
	$rnd_id = crypt ( uniqid ( rand (), 1 ) );
	
	//to remove any slashes that might have come 
	$rnd_id = strip_tags ( stripslashes ( $rnd_id ) );
	
	//Removing any . or / and reversing the string 
	$rnd_id = str_replace ( ".", "", $rnd_id );
	$rnd_id = strrev ( str_replace ( "/", "", $rnd_id ) );
	
	//finally I take the first 10 characters from the $rnd_id 
	$rnd_id = substr ( $rnd_id, 0, $random_id_length );
	($mode == 'upper') ? $rnd_id = strtoupper ( $rnd_id ) : '';
	return $rnd_id;
}
/**
 * @param string $path
 * @param string $filename
 * returns boolean
 */
function file_recursive_create($path, $filename) {
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
/**
 * 
 * @param string $classname
 */
function get_given_class_methods($classname) {
	$f = new ReflectionClass ( $classname );
	$methods = array ();
	foreach ( $f->getMethods () as $m ) {
		if ($m->class == $classname) {
			$methods [] = $m->name;
		}
	}
	return $methods;
}
?>