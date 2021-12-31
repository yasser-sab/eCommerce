<?php 

	function getTitle() {
		global $title;

		if(isset($title)){
			echo $title;
		}else{
			echo 'default';
		}
	}


	function redirect($Message,$state,$url=null,$seconds=3) {

		if($url=='back'){
			if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!==''){
				$url=$_SERVER['HTTP_REFERER'];
			}else {
				$url='index.php';
			}
		}else {
			$url='index.php';
		}

		echo "<div class='alert alert-".$state."'>".$Message."</div>";
		// echo "<div class='alert alert-'".$state."'>".$Message."</div>";
		echo "<div class='alert alert-info'>you will be redirected to ".$url." after ".$seconds."</div>";

		header('refresh:'.$seconds.';url='.$url);
		exit();
	}

	function find($column,$table,$value) {
		global $con;

		$stmt2 = $con->prepare('select '.$column.' from '.$table.' where '.$column.'=?');
		$stmt2->execute(array($value));
		return $stmt2->rowCount()>0;
	}

	function countTable($item,$table,$value=-1) {
		global $con;

		$query = '';
		if($value>-1){
			$query = ' AND '.$item.'='.$value;
		}

		$stmt2 = $con->prepare('select count('.$item.') from '.$table.' where 1=1 AND GroupeID=0'.$query);
		$stmt2->execute();
		return $stmt2->fetchColumn(); 
	}

	function getLatest($select,$table,$order,$limit=5) {
		global $con;

		$stmt2=$con->prepare('SELECT '.$select.' FROM '.$table.' ORDER BY '.$order.' DESC LIMIT '.$limit);
		$stmt2->execute();
		$rows=$stmt2->fetchAll();
		return $rows;
	}