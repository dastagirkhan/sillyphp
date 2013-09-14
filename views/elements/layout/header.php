<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="Silly PHP Framework" />
	<meta name="keywords" content="Silly,PHP Framework,MVC pattern" />
	<meta name="robots" content="Silly, PHP Framworkd,MVC pattern" />
	<meta name="author" content="Mohammed Dastagir" />
	<meta name="viewport" content="width=device-width">
	<title><?php  echo $title_for_layout; ?> </title>
	
    <link rel="stylesheet" type="text/css" href="/media/css/jquery/ui-1.10.0-minified/jquery.ui.core.min.css" />
	<link rel="stylesheet" href="/media/css/main.css" type="text/css" media="all" />
	<!--[if lte IE 6]><link rel="stylesheet" href="media/css/ie6.css" type="text/css" media="all" /><![endif]-->
	<!--[if IE]><style type="text/css" media="screen"> #navigation ul li a em { top:32px; } </style><![endif]-->
			 
    <script type="text/javascript" src="/media/js/jquery/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/media/js/jquery/ui-1.10.0-minified/jquery.ui.core.min.js"></script>
	<script type="text/javascript" src="/media/js/jquery/plugins/jquery.jcarousel.pack.js"></script>	
	<script type="text/javascript" src="/media/js/main.js"></script>
</head>
<body>
<div class ='header'>
  <a href='<?php $_SERVER['HTTP_HOST'];?>'><p class='title' title= 'Silly its all about php'>Silly <sup>Framework</sup><sub>its all about php...</sub></p></a>
</div>
<div style = 'clear:both'></div>
<div class ='settings'>
 <p><?php echo ($isrewrite)?'<img src = "/media/img/main/tick.png"> Well done mod_rewrite is enabled !!':'<img src = "/media/img/main/cross.png"> Please enable mod_rewrite';?></p>
 <p><?php echo ($isdbconfigured)?'<img src = "/media/img/main/tick.png"> Well done database is configured !!':'<img src = "/media/img/main/cross.png"> Database configuration is not set (/config/database.php)';?></p>
</div>
<div style = 'clear:both'></div>

<div class = 'content'>

