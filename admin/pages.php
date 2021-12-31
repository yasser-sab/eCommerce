<?php 

	$do=isset($_GET['do'])?$_GET['do']:'manage';

	if($do == 'manage') {
	?>
		<a href="?do=add">add +</a>
		<a href="?do=insert">insert +</a>
	<?php
	}
	elseif($do == 'add') {
		echo "welcome in add ";
	}
	elseif($do == 'insert') {
		echo "welcome in insert ";
	}
	else{
		echo "error";
	}