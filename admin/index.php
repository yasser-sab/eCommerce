<?php 

	session_start();

	$noNavbar="";
	$title = 'LOGIN';
	include "init.php";

	


	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$user = $_POST["name"];
		$pwd  = $_POST["password"];
		$hashpwd = sha1($pwd);


		// check existance
		$stm=$con->prepare('select userID,Username, Password from users where Username=? and Password=? and GroupeID=1');
		$stm->execute(array($user,$hashpwd));
		$row = $stm->fetch();
		$count = $stm->rowCount();


		if($count>0){
			$_SESSION['ID']=$row['userID'];
			$_SESSION['user']=$user;

			
			
		}else {
			redirect("please check password or name","danger");
		}
	}
	if(isset($_SESSION['user']))
	{
		header("Location: dashboard.php");
		exit();
	}

?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
		<label>
			<?php echo lang('NAME'); ?> :
			<input type="text" name="name" placeholder="name" autocomplete="off" value="yasser">
		</label><br> 
		<label>
			<?php echo lang('PASSWORD'); ?> :
			<input type="password" name="password" placeholder="password" autocomplete="new-password" value="123456">
		</label><br>
		<input type="submit" value="<?php echo lang('LOGIN'); ?>">
	</form>

<?php 
	include $tpl . "footer.php";
?>