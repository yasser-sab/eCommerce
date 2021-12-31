<?php

	session_start();
	if(isset($_SESSION['user'])){
		$title = 'DASHBOARD';
		include "init.php";
		$do=isset($_GET['do'])?$_GET['do']:'manage';
		if($do=='manage'){?>

			<h1>manage page</h1>

		<?php }else {
			echo $do." page";
		}

		include $tpl . "footer.php";
	}else {
		header('Location:index.php');
		exit();
	}

?>