<?php

	session_start();

	$title = 'members';

	

	if(isset($_SESSION["user"])){
		include "init.php";

		$do=isset($_GET['do'])?$_GET['do']:'manage';

		if($do == 'manage'){
			
			$query=(isset($_GET['page']) && $_GET['page']=='pending')?" AND RegStatus=0":"";

			manage("users",$query);

		}elseif($do == 'add'){
				add_form('users',2);

		}elseif($do=='insert') {
			if($_SERVER['REQUEST_METHOD']=='POST'){
				try {
					if(!find('Username','users',$_POST['Username'])){
						$_POST['Password']=sha1($_POST['Password']);
						if(insert('users',$_POST)>0){
							redirect("user added success",'success','back');
						}
					}else {
						redirect("user alredy exist !",'danger','back');
					}
				}catch(PDOException $e){
					echo $e->getMessage();
				}

			}else {
				redirect("you can\'t brows this page !",'danger');
			}

		}elseif($do=='edit') {

			$userId = isset($_GET['userId']) && is_numeric($_GET['userId'])?intval($_GET['userId']):0;
			$user = getRecord('userID','users',$userId);
			
			if(!empty($user)) {
				edit_form('users',$user,3);
			}else{
				redirect('user dosen\'t exist','danger');
			}	
			
		}elseif($do=='update') {
		
			if($_SERVER['REQUEST_METHOD']=='POST'){

				$pass=empty($_POST['Password'])?$_POST['oldpassword']:sha1($_POST['Password']);
				unset($_POST['oldpassword']);

				$_POST['Password']=$pass;
				$errors=array();

				// if(empty($_POST['username'])) {
				// 	$errors[]="user name can't be null";
				// }
				// if(empty($_POST['newpassword'])){
				// 	$errors[]="password can't be null";
				// }

				if(empty($errors)){
					if(update('users',$_POST)>0){
						redirect('member added success','success','back');
					}else{
						redirect('no row updated error','danger','back');
					}
				}else{
					foreach ($errors as $error) {
						echo "<div class='alert alert-danger'>".$error."</div></br>";
					}
					redirect('filed not fill','danger','back');
				}

			}else {
				redirect("you can\'t brows this page !",'danger');
			}

		}elseif($do=='delete') {?>

			<h1>delete member</h1>

			<?php $userId = isset($_GET['userId']) && is_numeric($_GET['userId'])?intval($_GET['userId']):0;

			if(find('userID','users',$userId)) {

				try{
					if(delete('users',array('userID'=>$userId))>0){
						redirect('deleted success','success','back');
					}else {
						redirect('deleted fail','danger','back');
					}
				}catch(PDOException $e){
					redirect('deleted fail','danger','back');
				}
			}else {
				redirect('user not exist','danger','back');
			}

		}elseif($do=='activate') {

			$userId = isset($_GET['userId']) && is_numeric($_GET['userId'])?intval($_GET['userId']):0;

			if(find('userID','users',$userId)){
				if(update('users',array('userID'=>$userId,'RegStatus'=>1))>0){
					redirect('user updated success','success','back');
				}else {
					redirect('user not updated !','danger','back');
				}
			}else {
				redirect('user not exist !','danger');
			}
			
		}else{
			header('Location:members.php?do=manage');
		}

		include $tpl . "footer.php";
	}else {
		header('Location:index.php');
		exit();
	}
