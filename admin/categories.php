<?php
	session_start();
	if(isset($_SESSION['user'])){
		$title='categories';
		include "init.php";

		$do = isset($_GET['do'])?$_GET['do']:'manage';

		switch ($do) {
			case 'manage':
				manage('categories','');
			break;

			case 'add':
				add_form('categories',6);
			break;

			case 'insert':
				if($_SERVER['REQUEST_METHOD']==='POST'){
					try {
						if(!empty(insert('categories',$_POST))){
							redirect('categorie added success !','success','back');
						}else {
							redirect('categorie failed','danger','back');
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
				$ID = $_GET['ID'] && is_numeric($_GET['ID']) ?$_GET['ID']:0;
				if(find('ID','categories',$ID)){
					$row = getRecord('ID','categories',$ID);

					if(!empty($row)) {
						edit_form('CATEGORIES',$row);	
					}else{
						redirect('user dosen\'t exist','danger');
					}	
				}else {
					redirect('categories not found','danger');
				}
			break;

			case 'update':
				if($_SERVER['REQUEST_METHOD']=='POST'){

					if(isset($_POST['Password'])){
						$pass=empty($_POST['Password'])?$_POST['oldpassword']:sha1($_POST['Password']);
						unset($_POST['oldpassword']);

						$_POST['Password']=$pass;
					}
					
					$errors=array();

					// if(empty($_POST['username'])) {
					// 	$errors[]="user name can't be null";
					// }
					// if(empty($_POST['newpassword'])){
					// 	$errors[]="password can't be null";
					// }

					if(empty($errors)){
						update('categories',$_POST);
						// if(update('categories',$_POST)>0){
						// 	redirect('categorie added success','success','back');
						// }else{
						// 	redirect('no row updated error','danger','back');
						// }
					}else{
						foreach ($errors as $error) {
							echo "<div class='alert alert-danger'>".$error."</div></br>";
						}
						redirect('filed not fill','danger','back');
					}

				}else {
					redirect("you can\'t brows this page !",'danger');
				}
			break;
			case 'delete':
				$catId = isset($_GET['catId']) && is_numeric($_GET['catId'])?intval($_GET['catId']):0;

				if(find('ID','categories',$catId)) {
					try{
						if(delete('categories',array('ID'=>$catId))>0){
							redirect('deleted success','success','back');
						}else {
							redirect('deleted fail','danger','back');
						}
					}catch(PDOException $e){
						redirect('deleted fail exception','danger','back');
					}
				}else {
					redirect('user not exist','danger','back');
				}

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