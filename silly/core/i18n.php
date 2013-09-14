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
class i18n {
	public static  $locale = 'en_US';
	public static  $local_dir;
	var $textdomain = 'messages';
	var $adapter = 'array';	
	public function setLocal() {
		/*textdomain (textdomain (NULL));
		   putenv('LANGUAGE='.$this->locale);
		   putenv('LANG='.$this->locale);
		   putenv('LC_ALL='.$this->locale);
		   putenv('LC_MESSAGES='.$this->locale);
		   setlocale(LC_ALL,$this->locale);
		   setlocale(LC_CTYPE,$this->locale);
		   $locales_dir = 'locale';
		   $mtime = filemtime("$locales_dir/$this->locale/LC_MESSAGES/$this->textdomain.mo");
		   copy("$locales_dir/$this->locale/LC_MESSAGES/$this->textdomain.mo","$locales_dir/$this->locale/LC_MESSAGES/$this->textdomain.$mtime.mo");
		   $toBind =   "$this->textdomain.$mtime";
		   bindtextdomain($this->textdomain,$toBind);
		   bind_textdomain_codeset($toBind, 'UTF-8'); 
		   textdomain($toBind);*/
	}
	public function extract() {
		//exec('C:\wamp\www\apps\core\gettext\bin\xgettext.exe --no-wrap --add-comments=TRANS --copyright-holder="Francois PLANQUE" --msgid-bugs-address=http://fplanque.net/ --output=c:\wamp\www\apps\locale\messages.pot --keyword=T_ --keyword=NT_ --keyword=TS_ c:\wamp\www\apps\views\super\home.php');
	//msgmerge -U i18n/my_project_fr.po i18n/my_project.pot  	
	}
	
	/**
	 * This function will create and return phrases depending on the language set
	 * @param string $phrase	 * 
	 */
	public static function translate($phrase) {
		//Checking for file		
		$path = self::$local_dir . "/" . self::$locale . "/LC_MESSAGES/messages.json";				
		if (self::file_recursive_create ( self::$local_dir . "/" . self::$locale . "/LC_MESSAGES/", "messages.json" )) {
			$languageFile = json_decode ( file_get_contents ( $path ), true );	
			(! is_array ( $languageFile )) ? $languageFile = array ("rules" => array (), "phrases" => array () ) : '';
			if (isset ( $languageFile ['phrases'] ["$phrase"] ) && $languageFile ['phrases'] ["$phrase"]  != "") {
				$phrase = $languageFile ['phrases'] ["$phrase"];
			} else if (! isset ( $languageFile ['phrases'] ["$phrase"] )) {
				$languageFile ['phrases'] ["$phrase"] = "";
				(! file_put_contents ( $path, json_encode ( $languageFile ) )) ? 'log file to be written' : '';
			}
		}
		return $phrase;
	}
}

if(isset($_SESSION ['_language']))i18n::$locale = $_SESSION ['_language'];
i18n::$local_dir = $_SERVER['DOCUMENT_ROOT'].'/locale';
function __($phrase, $return = false) {
	$phrase = i18n::translate ( $phrase );
	if (! $return)
		echo $phrase;
	else
		return $phrase;
}
?>