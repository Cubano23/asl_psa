<?php 
	require_once("SelfManagedMapper.php");
	require_once("bean/ListeDonnees.php");
	
	class ListeDonneesMapper extends SelfManagedMapper{
	
		function getForeignKey(){
			return "cabinet";
		}
	
		function getKeysMap(){
			return array("cabinet"=>"cabinet");
		}
		
		function getTableName(){
			return "donnees_cardio";
		}
	
		function getLedgerName(){
			return "ListeDonneesMapper";
		}
	
		function getObject(){
			return new ListeDonnees();
		}
		

		function getdernierExamsRCVA($id){
			$query = "SELECT max(dChol) as dChol, max(dHDL) as dHDL, max(dLDL) as dLDL, max(dtriglycerides) as dtriglycerides, ".
					 "max(dTA) as dTA, max(dsokolov) as dsokolov, max(dCreat) as dCreat, max(dkaliemie) as dkaliemie, ".
					 "max(dproteinurie) as dproteinurie, max(dhematurie) as dhematurie, max(dFond) as dFond, max(dECG) as dECG, ".
					 "max(darret) as darret, max(dpoids) as dpoids, max(dpouls) as dpouls, max(dgly) as dgly, max(exam_cardio) as exam_cardio ".
					 "from ".$this->getTableName()." where id = '$id' GROUP by id";
					  
			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}


		function getValeurs($account){
			$query = "select * from ".$this->getTableName()." where cabinet='$account->cabinet' ORDER BY dmaj DESC";
			
			$result= $this->findAnyRows($query);

			if($result==false) return false;
			return $this->buildRowArray($result);
		}

		function getDonnees(){
			$query = "SELECT * ".
					 "from account LEFT OUTER JOIN ".$this->getTableName()." ON account.cabinet=".$this->getTableName().
					 ".cabinet WHERE account.cabinet!='ztest' AND account.cabinet!='irdes' AND account.cabinet!='jgomes' ".
					 "AND account.cabinet!='clamecy' AND account.cabinet!='ergo' AND account.cabinet!='varzy' ".
					 "AND account.cabinet!='sbirault' ORDER BY nom_cab";

			$result = $this->findAnyRows($query);
			if($result == false) return false;
			return $this->buildRowArray($result);
		}

	}
?>
