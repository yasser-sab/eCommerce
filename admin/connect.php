<?php 

	$dns="mysql:host=localhost;dbname=shop";
	$usr = "root";
	$pass = "";
	$option = array(
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
	);

	try {
		$con = new PDO($dns,$usr,$pass,$option);

		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e){
		echo "fail => ".$e->getMessage();
	}

?>