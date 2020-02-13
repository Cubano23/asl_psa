<?php 
	/* Return true if any apecial charatcters harmful for sql statements are found in a variable */
	function containSpecialCodes($var){
		$specialChar1 = "'";
		$specialChar2 = "%";
		if(!is_array($var)){
			if(strpos($var,$specialChar1) != false ) return true;
			if(strpos($var,$specialChar2) != false ) return true;
		}
		else
		foreach($var as $key=>$value){
			if(strpos($value,$specialChar1) != false ) return true;
			if(strpos($value,$specialChar2) != false ) return true;
		}
		return false;
	}	
	
	// Return a map fiels types where the field name is the key
	function getFieldsMap($connection,$tableName){
		$showFieldsQuery = "show fields from $tableName";
		$result = mysql_query($showFieldsQuery,$connection);
		if($result == false) {
			//echo(mysql_error());
			return false;
		}
		$fieldsMap =array();
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			$fieldsMap[$row["Field"]] = $row["Type"];
		}	
		return $fieldsMap;				
	}
	

?>