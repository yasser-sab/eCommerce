<?php
	session_start();
	if(isset($_SESSION['user'])){
		$title='items';
		include "init.php";

		$do = isset($_GET['do'])?$_GET['do']:'manage';

		switch ($do) {
			case 'manage':
				// echo "manage items";
				// echo "<a href='items.php?do=add'>add item</a>";
				manage('ITEMS','');
			break;

			case 'add':
				add_form('ITEMS');
			break;

			case 'insert':
				if($_SERVER['REQUEST_METHOD']==='POST'){
					try {
						if(!empty(insert('ITEMS',$_POST))){
							redirect('ITEMS added success !','success','back');
						}else {
							redirect('ITEMS failed','danger','back');
						}
					}catch(PDOException $e){
						echo $e->getMessage();
					}
				}else{
					header('location:index.php');
					exit();
				}
			break;

			case 'edit':
				$id = isset($_GET['ID']) && is_numeric($_GET['ID'])?intval($_GET['ID']):0;	
				if(find('ID','ITEMS',$id)){
					$row = getRecord('ID','ITEMS',$id);
					if(!empty($row)) {
						edit_form('ITEMS',$row);
					}else{
						redirect('ITEMS dosen\'t exist','danger');
					}	
				}else {
					redirect('ITEMS not found','danger');
				}
			break;

			case 'update':
			update('ITEMS',$_POST);
				// if($_SERVER['REQUEST_METHOD']=='POST'){
				// 	// $pass=empty($_POST['Password'])?$_POST['oldpassword']:sha1($_POST['Password']);
				// 	// unset($_POST['oldpassword']);

				// 	// $_POST['Password']=$pass;
				// 	$errors=array();

				// 	// if(empty($_POST['username'])) {
				// 	// 	$errors[]="user name can't be null";
				// 	// }
				// 	// if(empty($_POST['newpassword'])){
				// 	// 	$errors[]="password can't be null";
				// 	// }

				// 	if(empty($errors)){
				// 		if(update('categories',$_POST)>0){
				// 			redirect('categorie added success','success','back');
				// 		}else{
				// 			redirect('no row updated error','danger','back');
				// 		}
				// 	}else{
				// 		foreach ($errors as $error) {
				// 			echo "<div class='alert alert-danger'>".$error."</div></br>";
				// 		}
				// 		redirect('filed not fill','danger','back');
				// 	}

				// }else {
				// 	redirect("you can\'t brows this page !",'danger');
				// }
			break;
			case 'delete':
				// $catId = isset($_GET['catId']) && is_numeric($_GET['catId'])?intval($_GET['catId']):0;

				// if(find('ID','categories',$catId)) {
				// 	try{
				// 		if(delete('categories',array('ID'=>$catId))>0){
				// 			redirect('deleted success','success','back');
				// 		}else {
				// 			redirect('deleted fail','danger','back');
				// 		}
				// 	}catch(PDOException $e){
				// 		redirect('deleted fail exception','danger','back');
				// 	}
				// }else {
				// 	redirect('user not exist','danger','back');
				// }

			break;

			default:
				echo "shiit";
			break;
		}

		include $tpl."footer.php";
	}else {
		header('location:index.php');
		exit();
	}

?>