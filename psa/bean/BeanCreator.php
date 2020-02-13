<?
	require_once("persistence/tools.php");
	
	$dbase = "RefinedIsas";
	$table = "suivi_diabete";
	$class = "SuiviDiabete";
	$exclude = array("dmaj");
	$add = true;
	
	require_once("persistence\ConnectionFactory.php");
	
	$cf = new ConnectionFactory();
	$conn = $cf->getConnection();
	
	$fields = mysql_list_fields($dbase, $table, $conn); 
	$columns = mysql_num_fields($fields); 

	$fieldsMap = getFieldsMap($conn,$table);

	$file = fopen($class.".php","w");
	if($file == false) return;
			
	
	fwrite($file,"<?php\n\n");
	
	fwrite($file,"require_once(\"tools/date.php\");\n\n");
	
	fwrite($file,"class $class{\n");		
	

	for ($i = 0; $i < $columns; $i++) { 
		if(in_array(mysql_field_name($fields, $i),$exclude)) continue;
		fwrite($file,"\t  var $".mysql_field_name($fields, $i).";\n");
	}
	fwrite($file,"\n\n");
	
	$count = 0;
	fwrite($file,"\tfunction $class(\n");
	for ($i = 0; $i < $columns; $i++) { 
		if(in_array(mysql_field_name($fields, $i),$exclude)) continue;
		if($count != 0) fwrite($file,",\n");		
		$count = $count + 1;
		echo("field name = ".mysql_field_name($fields, $i)." type = ".$fieldsMap[mysql_field_name($fields, $i)]."\n");
		if(strpos ($fieldsMap[mysql_field_name($fields, $i)],"set")===false) 
			fwrite($file,"\t\t\t\t\t $".mysql_field_name($fields, $i)." = NULL");
		else 		
			fwrite($file,"\t\t\t\t\t $".mysql_field_name($fields, $i)." = array()");
	}
	fwrite($file,"){\n");
	
	for ($i = 0; $i < $columns; $i++) { 
		if(in_array(mysql_field_name($fields, $i),$exclude)) continue;
		fwrite($file,"\t\t \$this->".mysql_field_name($fields, $i)." = $".mysql_field_name($fields, $i).";\n");
	}
		
	fwrite($file,"\t}\n\n");					
	
			
	fwrite($file,"\t function toString(){\n");
	fwrite($file,"\t\t return \n");
	$count = 0;
	for ($i = 0; $i < $columns; $i++) { 
		if(in_array(mysql_field_name($fields, $i),$exclude)) continue;
		if($count != 0) fwrite($file,".\" \".\n");
		$count = $count + 1;
		fwrite($file,"\t\t\t\$this->".mysql_field_name($fields, $i));				
	}	
	fwrite($file,";\n");
	fwrite($file,"\t}\n\n");
	
	
	
	fwrite($file,"\tfunction beforeSerialisation(\$account){\n");
	fwrite($file,"\t\t\$clone = \$this;\n");
	for ($i = 0; $i < $columns; $i++) { 		
		$fieldName = mysql_field_name($fields, $i);
		if($fieldsMap[$fieldName] == "date"){
			fwrite($file,"\t\t\$clone->".$fieldName." = dateToMysqlDate(\$clone->".$fieldName.");\n");
		}
	}
	fwrite($file,"\t\treturn \$clone;\n");
	fwrite($file,"\t}\n\n");
	
	fwrite($file,"\tfunction afterDeserialisation(\$account){\n");
	fwrite($file,"\t\t\$clone = \$this;\n");
	for ($i = 0; $i < $columns; $i++) { 		
		$fieldName = mysql_field_name($fields, $i);
		if($fieldsMap[$fieldName] == "date"){
			fwrite($file,"\t\t\$clone->".$fieldName." = mysqlDateTodate(\$clone->".$fieldName.");\n");
		}
	}
	fwrite($file,"\t\treturn \$clone;\n");
	fwrite($file,"\t}\n\n");
	
	
	if($add){
		$toAddFile = fopen("_".$class.".php","r");
		$data = fread($toAddFile,filesize("_".$class.".php"));
		fclose($toAddFile);
		fwrite($file,"\n");
		fwrite($file,$data);
	}
			

	
	fwrite($file,"\n}\n ?>\n");
	fclose($file);
	
	/*
		function test(){
		$propertiesArray = get_object_vars($object);
		if(is_null($propertiesArray)) return false;	
		
		foreach($propertiesArray as $key=>$val){
			echo($key."=");
			if(is_array($val)) {
				echo("array(");
				foreach($propertiesArray as $iKey=>$iVal){
					echo($iKey."=".$iVal." ");
				}
				echo(") ");
			}
			else
				echo($val);
			echo(" ");
		}
	}*/
?>



