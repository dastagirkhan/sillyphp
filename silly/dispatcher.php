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

class dispatcher {
	var $params = array ("controller" => DEFAULT_CONTROLLER, "url" => array (), "named" => array () );
	var $controllerPath = 'controller/';
	var $modelPath = 'model/';
	public function get() {
		if (isset ( $_GET ['url'] )) {
			$urls = explode ( '/', $_GET ['url'] );
		}
		if (isset ( $urls [0] )) {
			$this->controllerPath = ROOT . "/controller/";
			if (! file_exists ( ROOT . '/controller/' . $urls [0] . '.php' )) {
				#Controller doesn't exist					
				$this->params = array_merge ( $this->params, array ("controller" => "app", "action" => "error", "message" => "page not found" ) );
				$this->controllerPath = ROOT . '/';
			} else if (! isset ( $urls [1] )) {
				#Action not passed to the link				
				$this->params = array_merge ( $this->params, array ("controller" => $urls [0] ) );
			} else {
				#Good Link
				$this->params = array_merge ( $this->params, array ("controller" => $urls [0], "action" => $urls [1] ) );
			}
		}
		#if any parameters other than controller and action passed to the url
		if (isset ( $urls [2] )) {
			foreach ( array_slice ( $urls, 2, count ( $urls ) ) as $slice ) {
				if ($sripos = stripos ( $slice, ":" ))
					$this->params ["named"] [substr ( $slice, 0, $sripos )] = substr ( $slice, $sripos + 1, strlen ( $slice ) );
				else
					$this->params ["url"] [] = $slice;
			
			}
		}
		# clears $_GET varible
		unset ( $_GET ['url'] );
		# include the controller file
		require_once $this->controllerPath . $this->params ["controller"] . '.php';
		
		# returns controller object		
		$obj = new $this->params ["controller"] ();
		
		# include the model file
		$model = Inflector::pluralize ( $this->params ["controller"] );
		$modelpath = $this->modelPath . $model . '.php';
		if (! isset ( $obj->uses ) && file_exists ( $modelpath )) {
			require_once $modelpath;
			$obj->{$model} = new $model ();
		} else if (isset ( $obj->uses ) && count ( $obj->uses ) > 0) {
			foreach ( $obj->uses as $model ) {
				require_once $modelpath = $this->modelPath . $model . '.php';
				$obj->{$model} = new $model ();
			}
		}
		return $obj;
	}
}
?>


<?php
$parse = new dispatcher ();
$obj = $parse->get ();
$obj->params = $params = $parse->params;
if (function_exists ( 'apache_get_modules' ))
	$isrewrite = in_array ( 'mod_rewrite', apache_get_modules () ) === true;
else
	$isrewrite = strpos ( shell_exec ( '/usr/local/apache/bin/apachectl -l' ), 'mod_rewrite' ) !== false;
if (isset ( $config ['dbconfig'] ['default'] ['host'] ))
	$isdbconfigured = $config ['dbconfig'] ['default'] ['host'] != '' && $config ['dbconfig'] ['default'] ['login'] != '' && $config ['dbconfig'] ['default'] ['database'] != '';
else
	$isdbconfigured = false;
if (! empty ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest')
	$obj->layout = "ajax";
ob_start ();
$view = true;
if ($session->has ( 'logged' )) {
	if (isset ( $obj->allow ))
		unset ( $obj->allow );
}
if (isset ( $obj->allow )) {
	if (isset ( $params ["action"] ) && count ( $obj->allow ) > 0) {
		if (array_search ( $params ["action"], $obj->allow ) === false) {
			unset ( $params ["action"] );
			unset ( $obj->action );
		}
	}
	if (isset ( $obj->action ) && count ( $obj->allow ) > 0) {
		if (array_search ( $obj->action, $obj->allow ) === false)
			unset ( $obj->action );
	}
}
if (isset ( $params ["action"] )) {
	if (method_exists ( $obj, $params ["action"] )) {
		$obj->action = $params ["action"];
		$obj->{$params ["action"]} ();
	} else {
		$obj->error ();
		$view = false;
	}
} else if (isset ( $obj->action )) {
	$obj->action = $obj->action;
	$obj->{$obj->action} ();
} else if (method_exists ( $obj, DEFAULT_ACTION )) {
	$obj->action = DEFAULT_ACTION;
	$obj->{DEFAULT_ACTION} ();
} else if (! $session->has ( 'logged' )) {
	header ( "Location:http://" . $_SERVER ['HTTP_HOST'] );
	exit ();
} else {
	$obj->error ();
	$view = false;
}
if (isset ( $obj->vars ))
	extract ( $obj->vars );
if (isset ( $obj->params ['controller'] ) && $view && $obj->layout != "ajax") {
	$title_for_layout = $obj->action;
	$obj->view = VIEW . '/' . $obj->params ['controller'] . '/' . $obj->action . '.php';
	if (! file_exists ( $obj->view )) {
		$title_for_layout = 'error';
		$obj->error ();
	} else
		include $obj->view;
}
$content_for_layout = ob_get_clean ();
?>