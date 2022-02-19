<?php
	session_start();

	if(isset($_SESSION["user"])){
		$title = 'DASHBOARD';
		include "init.php";
		?>
		<div class="container">
			<h1><?php echo lang('DASHBOARD');?></h1>
			<div class="row">
				<div class="col-md-3">
					<a href="members.php?do=manage">
						<div class="stat">
							<span>count members : 
								<?php echo countTable('ID','users'); ?>		
							</span>
						</div>
					</a>
				</div>
				<div class="col-md-3">
					<a href="members.php?do=manage&page=pending">
						<div class="stat">pending members : 
							<span><?php echo countTable('RegStatus','users',0); ?></span>
						</div>
					</a>
				</div>
				<div class="col-md-3">
					<div class="stat">one
						<span>200</span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat">one
						<span>200</span>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div>
				<?php
					$latest = getLatest("*","users","ID",3);
				 ?>
				<h3>latest <?php echo count($latest); ?> user registrated</h3>
				<?php
					foreach ($latest as $row) {
						echo "<div>";
						echo $row['Name']." <a href='members.php?do=edit&userId=".$row['ID']."'>edit</a>";
						echo "</div>";
					}
				?>
			</div>
		</div>

		<?php
		include $tpl . "footer.php";
	}else {
		header('Location:index.php');
		exit();
	}
?>