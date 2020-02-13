<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/TensionArterielle.php");
	
	class TensionArterielleMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
		
		function getKeysMap(){
			return array("id"=>"id","date"=>"date","momment_journee"=>"momment_journee","indice"=>"indice");
		}
		
		function getTableName(){
			return "tension_arterielle";
		}
	
		function getLedgerName(){
			return "TensionArterielleMapper";
		}
	
		function getObject(){
			return new TensionArterielle();
		}

		function getObjectsByCabinet($cabinet)
		{
			$query =  "SELECT dossier.numero, dossier.sexe, dossier.dnaiss 
					   FROM tension_arterielle_moyenne, dossier 
					   WHERE tension_arterielle_moyenne.id = dossier.id 
					   		 AND dossier.actif = 'oui'
							 AND dossier.cabinet = '$cabinet' 
					   GROUP BY dossier.numero 
					   ORDER BY dossier.numero";

	
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
		
		
		function deleteObject($id, $group_id){
			$query="delete from tension_arterielle WHERE id='$id' and group_id='$group_id'";
			$resultat = $this->queryAny($query); 
			if ($resultat === false) return false;
			return true ;
		}

		function checkDateAvailable($id, $date){
			$sql = "select * FROM tension_arterielle where `id`='$id' and `date`='$date'";
			$query = mysql_query($sql);
			$result = mysql_num_rows($query);
			if ($result > 0) return false;
			return true ;
		}
		
	}  
?>