<?php 
	$roules = array(
			'tinyint' => array(
				'15'=>array(
					'field'=>'input',
					'type'=>'checkbox',
				),
				'1'=>array(
					'field'=>'input',
					'type'=>'number',
				),
				'25'=>array(
					'field'=>'input',
					'type'=>'number',
				)
			),
			'bit' => array(
				'1'=>array(
					'field'=>'input',
					'type'=>'checkbox',
				)
			),
			'int' => array(
				'255'=>array(
					'field'=>'select'
				)
			),
			'varchar' => array(
				'50'=>array(
					'field'=>'input',
					'type'=>'password',
				),
				'25'=>array(
					'field'=>'input',
					'type'=>'text',
				),
				'60'=>array(
					'field'=>'input',
					'type'=>'text',
				),
				'1'=>array(
					'field'=>'input',
					'type'=>'checkbox',
				),
				'255'=>array(
					'field'=>'input',
					'type'=>'email',
				)
			),
			'date' => array(
				'field'=>'input',
				'type'=>'date',
			),
			'tinytext' => array(
				'field'=>'textarea'
			),
			'float' => array(
				'field'=>'input',
				'type'=>'number'
			)
		);
	
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

	function getAllByColumns($columns,$table){
		global $con;
		$stmt2 = $con->prepare("select ".join(",",$columns)." from {$table}");
		$stmt2->execute();
		return $stmt2->fetchAll(PDO::FETCH_ASSOC);
	}

	// helper function

	function n($num,$char){
		$res="";

		for ($i=0; $i < $num; $i++) { 
			$res.=$char;
			if($i<$num-1)
				$res.=',';
		}
		return $res;
	}

	function insert($table,$p){
		global $con;

		$header=join(',',array_keys($p));
		$val=n(count($p),'?');
		$stmt2 = $con->prepare('insert into '.$table.'('.$header.') values('.$val.')');
		$stmt2->execute(array_values($p));
		return $stmt2->rowCount();
	}

	function update($table,$p){
		global $con;
		$keys=array_keys($p);
		$kv=join('=?,',$keys).'=?';
		echo $kv;
		// $values=explode(',', str_replace("on", '1', join(',',array_values($p))));


		// $stmt2=$con->prepare('describe '.$table);

		// $stmt2->execute();

		// $result = $stmt2->fetchAll(PDO::FETCH_ASSOC);

		// if(count($p)<count($result)){


		// 	$r=array();

		// 	foreach ($result as $tab) {
		// 		array_push($r, $tab[array_key_first($tab)]);
		// 	}

			
		// 	$diff=array_diff($r, $keys);

		// 	if(count($diff)>0){
		// 		$kv .= ','.join('=?,',$diff).'=?';
				
		// 		for ($i=0; $i < count($diff); $i++) { 
		// 			array_push($values,0);
		// 		}
		// 	}
		// }


		// $stmt2=$con->prepare('update '.$table.' set '.$kv.' where '.$keys[0].'='.$values[0]);
		// $stmt2->execute($values);

		// return $stmt2->rowCount();
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

	function getType2($str,$f="(",$e=""){
		// return strstr($str, $f) ? 
		// 		substr($str, strpos($str, $f),strpos($str, $e)) :
		// 		$str;
		// return strpos("yasser", "a");
		$posf=strpos($str, $f);
		if(empty($e))
			return strstr($str, $f)? substr($str,0, $posf):$str;
		
		$pose=strpos($str, $e);
		$posf+=1;
			return strstr($str, $f)? substr($str,$posf, $pose-$posf):"255";
	}

	function getFieldSize($str){
		return strstr($str,"(")? 
				substr($str,
					strpos($str, '(')+1,strlen(substr($str,strpos($str, '(')+1))-1):
				"";
	}

	function describe($table){
		global $con;
		$stmt2=$con->prepare("describe $table");
		$stmt2->execute();
		return $stmt2->fetchAll(PDO::FETCH_ASSOC);
	}

	function add_form($table,$nb=-1){
		global $roules;

		$table_def = describe($table);
		$nb=count($table_def);
		$template="<h1>".lang("ADD_$table")."</h2><form action='?do=insert' method='POST'>";

		foreach (array_slice($table_def,1,$nb-1) as $value) {

			$type=getType2($value['Type']);
			$size=getFieldSize($value['Type']);
			$tag = empty($size)?$roules[$type]:$roules[$type][$size];
			$content="";
			$type="";

			if($tag['field']!='input'){
				if($tag['field']!='select')
					$content=">";
				else{
					$table = getType2($value['Field']."_",'_','_');
					foreach (getAllByColumns(array('ID','Name'),$table) as $value2) {
						$content.="<option value={$value2['ID']}>{$value2['Name']}</option>";
					}
				}
			}
			else{
				$content=">";
				$type="type={$tag['type']}";
				$type.=$tag['type']==='checkbox'?" value=1":"";
			}

			$template.="<label>".lang($value['Field'])." : <{$tag['field']} id={$value['Field']} name={$value['Field']} placeholder={$value['Field']} $type $content</{$tag['field']}></label><br/>";
		}

		$template.="</from><input class='btn btn-primary' type='submit' value='".lang('INSERT')."'>";
		echo $template;
	}

	function edit_form($table,$row,$nb=-1){
		global $roules;

		$table_def=describe($table);
		$nb=count($table_def);
		$template = "<h1>".lang('EDIT_'.$table)."</h1><form action='?do=update' method='POST'>";

		foreach (array_slice($table_def,1,$nb-1) as $value) {	
			
			$type=getType2($value['Type']);
			$size=getFieldSize($value['Type']);
			$tag = empty($size)?$roules[$type]:$roules[$type][$size];
			$content="";
			$type="";
			$checked="";
			$hidden="";
			if($tag['field']!='input'){
				if($tag['field']!='select')
					$content=">{$row[$value['Field']]}";
				else{
					$table = getType2($value['Field']."_",'_','_');
					$selected="";
					foreach (getAllByColumns(array('ID','Name'),$table) as $value2) {
						$selected=$row[$value['Field']]===$value2['ID']?'selected':'';
						$content.="<option value={$value2['ID']} $selected>{$value2['Name']}</option>";
					}
				}
			}
			else{
				$content="value={$row[$value['Field']]}>";
				$type="type={$tag['type']}";
				if($tag['type']=="checkbox"){
					$checked=$row[$value['Field']]?'checked':'';
				}
				else if($tag['type']=="password"){
					$content="";
					$hidden="<input type='hidden' name='oldpassword' value={$row[$value['Field']]}/>";
				}
			}

			$template.="<label>{$value['Field']} : <{$tag['field']} id={$value['Field']} name={$value['Field']} placeholder={$value['Field']} $checked $type $content</{$tag['field']}> $hidden </label><br/>";
			
		}

		$template.="<input type='submit' value='".lang('EDIT')."'/></form>";
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
							<a style='border:1px solid #000' href='?do=edit&ID=".$row['ID']."'>Edit</a>
							<a style='border:1px solid #000' href='?do=delete&ID=".$row['ID']."' class='confirm'>delete</a>";
						
					if($key==='RegStatus' && $row['RegStatus']==0){
						$template.="<a style='border:1px solid #000' href='?do=activate&userId=".$row['userID']."' class='confirm'>activate</a>"; 
					}
					$template.="</td></tr>";
				}
		$template.="</tbody></table>
		<a href='?do=add'>add new ".$table."</a>";
		echo $template;
	}