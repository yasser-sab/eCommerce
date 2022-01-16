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
			?>

				<!-- <h1>add page</h1> -->

				<!-- <form action="?do=insert" method="POST">
					<label>
						name :
						<input type="text" name="Name" placeholder="Name" required="required">
					</label><br>
					<label>
						Description :
						<textarea name="Description" required="" autocomplete="off">
							
						</textarea>
					</label><br>
					<label>
						Ordering :
						<input type="number" name="Ordering" required="" autocomplete="off">
					</label><br>
					<fieldset>
						<label>
							visibilite :
							<input type="checkbox" name="Visibility" value="1">
						</label><br>
						<label>
							allow comments :
							<input type="checkbox" name="Allow_Comment" value="1">
						</label><br>
						<label>
							allow ads :
							<input type="checkbox" name="Allow_ads" value="1">
						</label><br>
					</fieldset>
					<input type="submit" value='add'>
				</form> -->
			<?php break;

			case 'insert':
				if($_SERVER['REQUEST_METHOD']==='POST'){
					try {
						insert('categories',$_POST);
					}catch(PDOException $e){
						echo $e->getMessage();
					}
				}else{
					header('location:index.php');
					exit();
				}
			break;

			case 'edit':
				$id = isset($_GET['catId']) && is_numeric($_GET['catId'])?intval($_GET['catId']):0;	
				if(find('ID','categories',$id)){
					$row = getRecord('ID','categories',$id);

					if(!empty($row)) {
						edit_form('categories',$row);
					}else{
						redirect('user dosen\'t exist','danger');
					}	
				}else {
					redirect('categories not found','danger');
				}
			break;

			case 'update':
				if($_SERVER['REQUEST_METHOD']=='POST'){
					// $pass=empty($_POST['Password'])?$_POST['oldpassword']:sha1($_POST['Password']);
					// unset($_POST['oldpassword']);

					// $_POST['Password']=$pass;
					$errors=array();

					// if(empty($_POST['username'])) {
					// 	$errors[]="user name can't be null";
					// }
					// if(empty($_POST['newpassword'])){
					// 	$errors[]="password can't be null";
					// }
					if(empty($errors)){
						if(update('categories',$_POST)>0){
							redirect('categorie added success','success','back');
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