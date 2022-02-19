<?php 

	session_start();

	$noNavbar="";
	$title = 'LOGIN';
	include "init.php";

	


	if($_SERVER['REQUEST_METHOD'] == "POST"){
		// $user = $_POST["name"];
		// $pwd  = $_POST["password"];
		// $hashpwd = sha1($pwd);


		// check existance
		// ;
		// print_r($_POST);
		$_POST['GroupeID']=1;
		print_r($_POST);
		$user = getRecord($_POST,'users');
		if(!empty($user)){
			$_SESSION['ID']=$user['ID'];
			$_SESSION['user']=$user['Name'];
		}else {
			redirect("please check password or name","danger");
		}

		// $stm=$con->prepare('select userID,Username, Password from users where Username=? and Password=? and GroupeID=1');
		// $stm->execute(array($user,$hashpwd));
		// $row = $stm->fetch();
		// $count = $stm->rowCount();


		// if($count>0){
		// 	$_SESSION['ID']=$row['userID'];
		// 	$_SESSION['user']=$user;

			
			
		// }else {
		// 	redirect("please check password or name","danger");
		// }
	}
	if(isset($_SESSION['user']))
	{
		header("Location: dashboard.php");
		exit();
	}

?>
	<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
		<h1><?php echo lang('ADMIN_LOGIN');?></h1>
		<label>
			<input class="form-control" type="text" name="Name" placeholder="<?php echo lang('NAME');?>" autocomplete="off" value="yasser">
		</label>
		<label>
			<input class="form-control" type="password" name="Password" placeholder="<?php echo lang('PASSWORD');?>" autocomplete="new-password" value="123456">
		</label>
		<input class="btn btn-primary" type="submit" value="<?php echo lang('LOGIN'); ?>">
	</form>

<?php 
	include $tpl . "footer.php";
?>