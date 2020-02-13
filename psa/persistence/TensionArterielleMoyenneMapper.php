<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/TensionArterielleMoyenne.php");
	
	class TensionArterielleMoyenneMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "id";
		}
		
		function getKeysMap(){
			return array("id"=>"id","group_id"=>"group_id");
		}
		
		function getTableName(){
			return "tension_arterielle_moyenne";
		}
	
		function getLedgerName(){
			return "TensionArterielleMoyenneMapper";
		}
	
		function getObject(){
			return new TensionArterielleMoyenne();
		}
		
		function getGroupId($dossier){
			$query="select max(group_id) as max from tension_arterielle_moyenne inner join dossier using(id) where numero='$dossier->numero'";
			$resultat = $this->findAnyRows($query); 
			if ($resultat === false) return false;
			$donnees = $this->buildRowArray($resultat);
			return $donnees[0]['max'] +1 ;
		}
		
		function deleteObject($id, $group_id){
			$query="delete from tension_arterielle_moyenne WHERE id='$id' and group_id='$group_id'";
			$resultat = $this->queryAny($query); 
			if ($resultat === false) return false;
			return true ;
		}
		
		function getFindListQuery($object){
			$propertiesArray = get_object_vars($object);
			if(is_null($propertiesArray)) return false;	
			return "select * from ".$this->getTableName()." where id='".$propertiesArray["id"]."' ORDER BY date_debut";
		}
	}  
	
?>
