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
#intialising config variable
$config = array();

#view directory directory
define ( 'VIEW', ROOT . "/views" );

#config directory
define ( 'CONFIG', ROOT . "/config" );

#controller directory
define ( 'CONTROLLER', ROOT . "/controller" );

#controller directory
define ( 'MODEL', ROOT . "/model" );

#media directory
define ( 'MEDIA', ROOT . "/media" );

#css directory
define ( 'CSS', MEDIA . "/css" );

#javascript directory
define ( 'JS', MEDIA . "/js" );

#image directory
define ( 'IMG', MEDIA . "/img" );


#Inlcude silly path files
define ( 'SILLY_LIBS', SILLY . "/libs" );
define ( 'SILLY_CORE', SILLY . "/core" );
define ( 'SILLY_CONTROLLER', SILLY . "/controller" );
define ( 'SILLY_MODEL', SILLY . "/model" );

#Include config files
foreach ( glob ( CONFIG . "/*.php" ) as $filename ) {
	require_once $filename;
}

#Include silly core files
foreach ( glob ( SILLY_CORE . "/*.php" ) as $filename ) {
	require_once $filename;
}

#initializing cookie
cookie::init();

#session start
$session = new Session();
$session->start();

#Include silly library files
foreach ( glob ( SILLY_LIBS . "/*.php" ) as $filename ) {
	require_once $filename;
}

#xpertmailer library
require_once SILLY_LIBS . DS . '/xpertmailer/MAIL.php';

#Include silly controller files
foreach ( glob ( SILLY_CONTROLLER . "/*.php" ) as $filename ) {
	require_once $filename;
}

#Include silly controller files
foreach ( glob ( SILLY_MODEL . "/*.php" ) as $filename ) {
	require_once $filename;
}

require_once ('app.php');

#Inlcude dispatcher file
require_once SILLY . "/dispatcher.php";
?>