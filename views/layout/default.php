<?php
/* AJAX check  */
if (! empty ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') {
	$content_type = get_content_type ( $_SERVER );
	if ($content_type)
		header ( "Content-type: application/json" );
} else
	include_once (ROOT.'/views/elements/layout/header.php');
	
//Calls parse object get method to return current object			    
/*if (isset ( $params ["action"] ))
	(method_exists ( $obj, $params ["action"] )) ? $obj->{$params ["action"]} () : $obj->error ();
else
	$obj->{$obj->action} ();*/
	echo $content_for_layout;
	
/* AJAX check  */
if (! empty ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') {
	if ($content_type)
		header ( "Content-type: application/json" );
} else
	include_once (ROOT.'/views/elements/layout/footer.php');

?>