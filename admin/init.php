<?php 

	include "connect.php";

	//routes
	
	$tpl = 'includes/templates/';
	$lang  = 'includes/languages/';
	$func = 'includes/functions/';
	$js = './layout/js/';
	$css = './layout/css/';
	global $t;

	// important files

	include $lang . "en.php";
	// include $lang . "ar.php";
	include $func . "functions.php";
	include $tpl . "header.php";


	if(!isset($noNavbar)){include $tpl . "navbar.php";}

	
	

?>