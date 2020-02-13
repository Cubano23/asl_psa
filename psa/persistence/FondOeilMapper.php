<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/FondOeil.php");
	
	class FondOeilMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
	
		function getKeysMap(){
			return array("id"=>"id", "date"=>"date", "oeil"=>"oeil");
		}
		
		function getTableName(){
			return "fond_oeil";
		}
	
		function getLedgerName(){
			return "FondOeilMapper";
		}
	
		function getObject(){
			return new FondOeil();
		}

		function getObjectsByDossier($cabinet, $dossier){
			$query =  "select * from ".$this->getTableName().",dossier where cabinet='$cabinet' and ".
					$this->getTableName().".".$this->getForeignKey()." = dossier.id AND dossier.numero='$dossier' ".
					"ORDER BY date desc, oeil";
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
