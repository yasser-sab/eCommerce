<?php

	session_start();

	$title = 'members';

	

	if(isset($_SESSION["user"])){
		include "init.php";

		$do=isset($_GET['do'])?$_GET['do']:'manage';

		if($do == 'manage'){?>

			<h1>manage members</h1>

			<table border="1" cellspacing="1" cellpadding="0">
				<thead>
					<tr>
						<th>#</th>
						<th>user name</th>
						<th>control</th>
					</tr>
				</thead>
				<tbody>

					<?php 

						$query="";

						if(isset($_GET['page']) && $_GET['page']=='pending'){
							$query="AND RegStatus=0";
						}

						$stmt=$con->prepare("select userID,Username,RegStatus from users where GroupeID!=1 ".$query);
						$stmt->execute();
						$rows=$stmt->fetchAll();
						foreach ($rows as $row) {
							echo "<tr>";
							echo "<td>".$row['userID']."</td>";
							echo "<td>".$row['Username']."</td>";
							echo "<td><a href='?do=edit&userId=".$row['userID']."'>edit</a>";
							echo "<a href='?do=delete&userId=".$row['userID']."' class='confirm'>delete</a>"; 
							if($row['RegStatus']==0){
								echo "<a href='?do=activate&userId=".$row['userID']."' class='confirm'>activate</a>"; 
							}
							echo "</td>";
							echo "</tr>";
						}


					?>
				</tbody>
			</table>
			<a href="?do=add">add new member</a>

		<?php }elseif($do == 'add'){?>

			<h1><?php echo lang('ADD_MEMBER') ?></h1>

			<form action="?do=insert" method="POST">
				<label>
					<?php echo lang('USER_NAME'); ?> :
					<input type="text" name="username" placeholder="<?php echo lang('USER_NAME'); ?>" autocomplete="off" required="required">
				</label><br>
				<label>
					<?php echo lang('PASSWORD'); ?> :
					<input type="password" name="password" placeholder="<?php echo lang('PASSWORD'); ?>" autocomplete="off" required="required">
				</label><br>
				<input type="submit" value='<?php echo lang('ADD'); ?>'>
			</form>

		<?php }elseif($do=='edit') {

			$userId = isset($_GET['userId']) && is_numeric($_GET['userId'])?intval($_GET['userId']):0;

			$stmt=$con->prepare('select * from users where userID=?');
			$stmt->execute(array($userId));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();

			if($count > 0) {?>
				

				<h1><?php echo lang('EDIT_MEMBER'); ?></h1>
				<form action="?do=update" method="POST">
					<label>
						<?php echo lang('USER_NAME'); ?> :
						<input type="text" name="username" value="<?php echo $row['Username'] ?>" placeholder="<?php echo lang('USER_NAME'); ?>" autocomplete="off" >
						<input type="hidden" name="userid" value="<?php echo $userId; ?>">
					</label><br>
					<label>
						<?php echo lang('PASSWORD'); ?> :
						<input type="password" name="newpassword" placeholder="<?php echo lang('PASSWORD'); ?>" autocomplete="off">
						<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
					</label><br>
					<input type="submit" value='<?php echo lang('EDIT'); ?>'>
				</form>
			

			<?php }else{
				echo "dosen't exist";
				redirect('user dosen\'t exist','danger',);
			}	
			
		}elseif($do=='update') {
		
			if($_SERVER['REQUEST_METHOD']=='POST'){

				$pass=empty($_POST['newpassword'])?$_POST['oldpassword']:sha1($_POST['newpassword']);

				$errors=array();

				if(empty($_POST['username'])) {
					$errors[]="user name can't be null";
				}
				if(empty($_POST['newpassword'])){
					$errors[]="password can't be null";
				}

				if(empty($errors)){
					$stmt = $con->prepare('update users set Username=?,Password=? where userID=?');
					$stmt->execute(array($_POST['username'],$pass,$_POST['userid']));

					if($stmt->rowCount()>0){
						header('Location: ?do=edit&userId='.$_POST['userid']);
					}
					else {
						echo "error";
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

		}elseif($do=='insert') {
			if($_SERVER['REQUEST_METHOD']=='POST'){
				
				try {
					if(!find('Username','users',$_POST['username'])){
						$stmt=$con->prepare('insert into users(Username,Password) values (:zuser,:zpass)');
						$stmt->execute(
							array(
								'zuser'=>$_POST['username'],
								'zpass'=>sha1($_POST['password'])
							)
						);
						$count = $stmt->rowCount();
						echo $count . 'row insert ';
					}else{
						redirect("user alredy exist !",'danger','back');
					}
				}catch(PDOException $e){
					echo $e->getMessage();
				}

			}else {
				redirect("you can\'t brows this page !",'danger');
			}

		}elseif($do=='delete') {?>

			<h1>delete member</h1>

			<?php $userId = isset($_GET['userId']) && is_numeric($_GET['userId'])?intval($_GET['userId']):0;

			// $stmt=$con->prepare('select * from users where userID=?');
			// $stmt->execute(array($userId));
			// $row = $stmt->fetch();
			// $count = $stmt->rowCount();


			if(find('userID','users',$userId)) {
				$stmt=$con->prepare('DELETE from users WHERE userID=:zuser');
				$stmt->bindParam('zuser',$userId);
				$stmt->execute();
				echo '<div class="alert alert-success">'.$stmt->rowCount().' user(s) deleted</div>';
			}else {
				echo "user not exist";

			}
		}elseif($do=='activate') {?>

			<h1>activated member</h1>

			<?php $userId = isset($_GET['userId']) && is_numeric($_GET['userId'])?intval($_GET['userId']):0;

			// $stmt=$con->prepare('select * from users where userID=?');
			// $stmt->execute(array($userId));
			// $row = $stmt->fetch();
			// $count = $stmt->rowCount();


			if(find('userID','users',$userId)) {
				$stmt=$con->prepare('UPDATE users SET RegStatus=1 WHERE userID=:zuser');
				$stmt->bindParam('zuser',$userId);
				$stmt->execute();
				echo '<div class="alert alert-success">'.$stmt->rowCount().' user(s) updated</div>';
			}else {
				echo "user not exist";

			}
		}else{
			header('Location:members.php?do=manage');
		}

		include $tpl . "footer.php";
	}else {
		header('Location:index.php');
		exit();
	}
