

<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/SuiviHebdomadaire.php");
	
	class SuiviHebdomadaireMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "cabinet";
		}
		
		function getKeysMap(){
			return array("cabinet"=>"cabinet","date"=>"date");
		}
		
		function getTableName(){
			return "suivi_hebdomadaire";
		}
	
		function getLedgerName(){
			return "SuiviHebdomadaireMapper";
		}
	
		function getObject(){
			return new SuiviHebdomadaire();
		}
		
		function getObjectsByCabinet($cabinet)
		{
		$query =  "select * from ".$this->getTableName()." where cabinet='$cabinet' ".
				"ORDER BY date DESC";

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
