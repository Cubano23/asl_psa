<?php
    error_reporting(E_ERROR); // EA. Les script ne traite pas des valeurs initiales ce qui génère les Notices 22-12-2014
require_once("Mapper.php"); 
require_once("DossierMapper.php");
require_once("tools.php");
require_once("tools/arrays.php");
	
class SelfManagedMapper extends Mapper{
			
	// Those functions should be implemented by child classes:
	// getTableName()  : return the table name related to this mapper
	// getLedgerName() : return the ledger name for this mapper
	// getObject()  : return and empty instance of the mapped bean
	// getKeysMap(): return key names as an array where key=value
	// NB: The findObjectsQuery() is not defined in SelfManagedMapper 
		
	
	function getInsertQuery($object){
		$query = "insert into ".$this->getTableName();

		$propertiesArray = get_object_vars($object);

		if(is_null($propertiesArray)) return false;				
		
		$query = $query." (";
		foreach($propertiesArray as $propName=>$propVal){
			if($propName!=""){
				$query = $query.$propName.",";	
			}
								
		}
		$query = substr($query,0,strlen($query)-1);
		
		if(substr($query, -1)==","){
			$query = substr($query,0,strlen($query)-1);
		}
		$query = $query.") ";
		
		$query = $query."values(";
		foreach($propertiesArray as $propName=>$propVal){
			if($propName!=""){
				if(is_array($propVal)) $propVal = arrayToSet($propVal);
				if($propVal === NULL) $tmpPropVal = "NULL"; 
				else $tmpPropVal = "'$propVal'";
				$query = $query.$tmpPropVal." ,";
			}
		}
		$query = substr($query,0,strlen($query)-1);
		$query = $query.")";
//
// echo $query;

		return $query;
	}
	
	function getWhereClause($propertiesArray){
		$keysMap = $this->getKeysMap();
		
		$where=" where ";
		foreach($keysMap as $key=>$val){
			$where=$where.$key."='$propertiesArray[$key]' and ";
			
		}
		$where = substr($where,0,strlen($where)-5);

		return $where;
	}
	
	function getUpdateQuery($object){
		$query = "update ".$this->getTableName()." set ";
		$keysMap = $this->getKeysMap();
		
		$propertiesArray = get_object_vars($object);
		if(is_null($propertiesArray)) return false;				

		foreach($propertiesArray as $propName=>$propVal){
			if($propName!=""){
				if(array_key_exists($propName,$keysMap)) continue;
				if(is_array($propVal)) $propVal = arrayToSet($propVal);
				if($propVal == NULL) $tmpPropVal = "NULL";
				else {$tmpPropVal = "'$propVal'";}
				if (($this->getTableName() == "depistage_colon") && ($tmpPropVal == "'none'")) {$tmpPropVal = "'aucun'"; }
				$query = $query.$propName."= $tmpPropVal ,";
			}
			
		}
		
		$query = substr($query,0,strlen($query)-1);
		
		$query=$query.$this->getWhereClause($propertiesArray);
		return $query;
	}
	
	function getFindQuery($object){
		$propertiesArray = get_object_vars($object);
		if(is_null($propertiesArray)) return false;
		return "select * from ".$this->getTableName().$this->getWhereClause($propertiesArray);
	}	
	
	function getDeleteQuery($object){
		$propertiesArray = get_object_vars($object);
		if(is_null($propertiesArray)) return false;	
		return "delete from ".$this->getTableName().$this->getWhereClause($propertiesArray);
	}
		
	function doLoadObject($row){
		$object = $this->getObject();

		$propertiesArray = get_object_vars($object);
		if(is_null($propertiesArray)) return false;		
		
		foreach($propertiesArray as $propName=>$propVal){
			if(is_array($propVal)) $object->$propName = setToArray($row[$propName]);			
			else $object->$propName = $row[$propName];
		}
		
		return $object;
	}
	
	function getObjectsByCabinet($cabinet){
		$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".".$this->getForeignKey()." = dossier.id AND dossier.actif='oui' GROUP BY ".
				"dossier.numero";
		$result = $this->findAnyRows($query);
		if($result == false) return false;
		$rowsList = "";
		$count = 0;
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			$rowsList[$count] = $row;
			$count = $count + 1;
		}

		return $rowsList;				
	}		
	
	function getObjectsByDossier($cabinet, $dossier){
		$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
				$this->getTableName().".".$this->getForeignKey()." = dossier.id AND dossier.numero='$dossier'";
		$result = $this->findAnyRows($query);

		if($result == false) return false;
		$rowsList = "";
		$count = 0;
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			$rowsList[$count] = $row;
			$count = $count + 1;
		}

		return $rowsList;
	}
	function getObjectsByDate($cabinet, $date){
		$query =  "select * from ".$this->getTableName()." where cabinet='$cabinet' and ".
				 "date='$date'";
		$result = $this->findAnyRows($query);

		if($result == false) return false;
		$rowsList = "";
		$count = 0;
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			$rowsList[$count] = $row;
			$count = $count + 1;
		}

		return $rowsList;
	}
	
  	function getFindDernierExamQuery($object){
  		return "";
  	}

}
?>
