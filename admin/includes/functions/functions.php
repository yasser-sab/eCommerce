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
				// $url=$_SERVER['HTTP_REFERER'];
				$url='back';
			}else {
				$url='index.php';
			}
		}else {
			$url='index.php';
		}

		echo "<div class='alert alert-".$state."'>".$Message."</div>";
		// echo "<div class='alert alert-'".$state."'>".$Message."</div>";
		echo "<div class='alert alert-info'>you will be redirected to ".$url." after ".$seconds." second(s)</div>";
		if($url==='back'){
			header('refresh:'.$seconds.';url='.$_SERVER['HTTP_REFERER']);
		}else{
			header('refresh:'.$seconds.';url='.$url);
		}
		exit();
	}

	function find($column,$table,$value) {
		global $con;

		$stmt2 = $con->prepare('select '.$column.' from '.$table.' where '.$column.'=?');
		$stmt2->execute(array($value));
		return $stmt2->rowCount()>0;
	}

	function getRecord($column,$table,$value=NULL){
		global $con;
		$stmt2;

		if(is_null($value)){
			$_POST['Password']=sha1($_POST['Password']);
			$keys=array_keys($column);
			$values=array_values($_POST);
			$where=join('=? AND ',$keys).'=?';
			$stmt2 = $con->prepare('select * from '.$table.' where '.$where);
			$stmt2->execute($values);
		}else{
			$stmt2 = $con->prepare('select * from '.$table.' where '.$column.'=?');
			$stmt2->execute(array($value));
		}
		return $stmt2->fetch(PDO::FETCH_ASSOC);
	}

	function insert($table,$p){
		global $con;
		$keys=array_keys($p);
		$header=join(',',$keys);
		$val=':z'.join(',:z',$keys);

		$or=$p;
		$ar=array_keys($p);
		foreach ($ar as $v) {
			unset($p[$v]);
			$p['z'.$v]=$or[$v];
		}
		
		$stmt2 = $con->prepare('insert into '.$table.'('.$header.') values('.$val.')');
		$stmt2->execute($p);
		return $stmt2->rowCount();
		// if($stmt2->rowCount()>0){
		// 	redirect('row added success','success','back');
		// }else {
		// 	redirect('row not added','danger','back');
		// }
	}

	function update($table,$p){
		global $con;

		$keys=array_keys($p);
		$kv=join('=?,',$keys).'=?';
		$values=array_values($p);
		print_r($values);
		$stmt2=$con->prepare('update '.$table.' set '.$kv.' where '.$keys[0].'='.$values[0]);
		$stmt2->execute($values);

		return $stmt2->rowCount();
	}
	
	function delete($table,$p){
		global $con;
		$stmt2=$con->prepare('delete from '.$table.' where '.array_keys($p)[0].'=:z'.array_keys($p)[0]);
		$stmt2->bindParam('z'.array_keys($p)[0],array_values($p)[0]);
		$stmt2->execute();
		return $stmt2->rowCount();
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

	function add_form($table,$nb){
		global $con;

		$columns=array();
		$stmt2=$con->prepare('describe '.$table);
		$stmt2->execute();

		foreach ($stmt2->fetchAll() as $v) {
			$columns[$v['Field']]=$v['Type'];
		}	
		$template="";
		$type="";
		$tTaille="";
		$template.="<h1>".lang('ADD_'.$table)."</h2><form action='?do=insert' method='POST'>";
		foreach (array_slice($columns,1,$nb) as $key => $value) {
			$type=substr($value,0, strpos($value, '('));
			$tTaille=substr($value,strpos($value, '(')+1,strlen(substr($value,strpos($value, '(')))-2);
			switch ($type) {
				case 'varchar':
					if($tTaille=='255'){
						$type='email';
					}elseif($tTaille=='50'){
						$type='password';
					}else{
						$type='text';
					}
					break;
				case 'int':
				case 'tinyint':
					if($tTaille>1){
						$type='number';
					}else {
						$type='checkbox';
					}
					break;
				case 'email':
					$type='email';
					break;
					
				default:
					$type='text';
					break;
			}
			$template.="<label>".lang($key)." :<input type='".$type."' name='".$key."' placeholder='".$key."' autocomplete='off'>
				</label><br>";
		}

		$template.="</from><input type='submit' value='".lang('INSERT')."'>";
		echo $template;
	}

	function edit_form($table,$row,$nb=100){
		$template = "<h1 class='text-center'>".lang('EDIT_'.$table)."</h1><div class='container'><form class='form-horizontal' action='?do=update' method='POST'>";
		// print_r($row);
		$delimitator="01111110";
		global $con;
		$stmt2=$con->prepare('describe '.$table);
		$stmt2->execute();

		foreach ($stmt2->fetchAll(PDO::FETCH_ASSOC) as $value) {
			$row[$value["Field"]].=$delimitator.$value["Type"];
		}

		//edit $row variable for regStatus

		$type="";
		$v="";
		$in="";
		$left="";

		foreach ( array_slice($row,0,$nb+1) as $key => $value) {

			$template.="<div class='form-group form-group-lg'><label class='control-label'>".lang($key)." : <input class='form-control d-inline' name='".$key."'autocomplete='off' placeholder=''";
			$value=explode($delimitator, $value);
			$left=$value[0];
			$v=$left;
			$right=$value[1];
			$ty=substr($right,0, strpos($right, '('));
			$taille=substr($right,strpos($right, '(')+1,strlen(strstr($right, strpos($right, "(")))-1);

			switch ($ty) {
				case 'int':
				case 'tinyint':
					if($taille>1){
						$type='number';
					}else {
						$type='checkbox';
						if($left==1){
							$template.=" checked=''";
						}
					}
					break;
				case 'varchar':
					if($taille=='255'){
						$type='email';
					}elseif($taille=='50'){
						$type='password';
						$v="";
						$in="<input type='hidden' name='oldpassword' value='".$left."'>";
					}else{
						$type='text';
					}
					break;
				case 'email':
					$type="email";
				break;
				
				default:
					# code...
					break;
			}
			$template.=" type='".$type."' value='".$v."'></label></div>";
			if(!empty($in)){
				$template.=$in;
				$in="";
			}
			
		}
		$template.="<div class='form-group form-group-lg'><input class='btn btn-primary btn-lg' type='submit' value='".lang('EDIT')."'></div></form></div>";
		echo $template;

	}


	function manage($table,$q){
		global $con;
		// $stmt=$con->prepare("select userID,Username,RegStatus from ".$table." where GroupeID!=1".$q);
		$stmt=$con->prepare("select * from ".$table);
		$stmt->execute();
		$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
		$template="<h1>manage ".$table."</h1>
		<table border='1' cellspacing='1' cellpadding='0'>
			<thead>
				<tr>";
				foreach ($rows[0] as $key => $value) {
					$template.="<th>".$key."</th>";
				}
				$template.="<th>control</th></tr></thead><tbody>";
		
		foreach ($rows as $row) {
			$template.="<tr>";
			foreach ($row as $key => $value) {
				
			$template.="<td>".$value."</td>";
			}
			$template.="<td>
					<a style='border:1px solid #000' href='?do=edit&catId=".$row['ID']."'>Edit</a>
					<a style='border:1px solid #000' href='?do=delete&catId=".$row['ID']."' class='confirm'>delete</a>";
				
			if($key==='RegStatus' && $row['RegStatus']==0){
				$template.="<a style='border:1px solid #000' href='?do=activate&userId=".$row['userID']."' class='confirm'>activate</a>"; 
			}
			$template.="</td></tr>";
		}
		$template.="</tbody></table>
		<a href='?do=add'>add new ".$table."</a>";
		echo $template;
	}