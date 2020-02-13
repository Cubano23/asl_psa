
<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/Epices.php");
	
	class EpicesMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
	
		function getKeysMap(){
			return array("id"=>"id","date"=>"date");
		}
		
		function getTableName(){
			return "epices";
		}
	
		function getLedgerName(){
			return "EpicesMapper";
		}
	
		function getObject(){
			return new Epices();
		}
		
		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;
			return "select * from ".$this->getTableName()." where id=".$propertiesArray["id"];
		}
		
	 	function getObjectsByCabinet($cabinet){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
					$this->getTableName().".".$this->getForeignKey()." = dossier.id";
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
			return "select * from ".$this->getTableName()." where id ='$object->id' ORDER BY `date` DESC limit 0,1";

		}		

		function getObjectsByDossier($cabinet, $dossier){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
					$this->getTableName().".id = dossier.id AND dossier.numero='$dossier' ".
					"ORDER by date";
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
	}
?>
