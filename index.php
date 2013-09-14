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

#define root directory
define ( 'ROOT', dirname(__FILE__) );

#Directory separtor
define ( 'DS', "/" );

#define silly directory
define ( 'SILLY', ROOT . DS . "silly" );

#include path file
require_once (SILLY . DS . 'paths.php');

#content for layout
include (VIEW . '/layout/' . $obj->layout . ".php");
?>


  
