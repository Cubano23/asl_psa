<?php 

require_once("tools.php"); 
require_once("errorcodes.php");
require_once("Mapper.php"); 
require_once("log/Ledger.php");
require_once("log/LedgerFactory.php");
require_once("bean/Dossier.php");

class DossierMapper extends Mapper{
		
	function getLedgerName(){
		return "DossierMapper";
	}
	
	function getInsertQuery($dossier){
		$taille = $dossier->taille==""?"NULL":"'$dossier->taille'";
		return "insert into dossier(id,cabinet,numero,dnaiss,sexe,taille, actif, dconsentement, dcreat) values".
						"('$dossier->id','$dossier->cabinet','$dossier->numero','$dossier->dnaiss','$dossier->sexe',$taille, '$dossier->actif', '$dossier->dconsentement', '".date('Y-m-d')."')";
	}
	
	function getUpdateQuery($dossier){
		
		$taille = $dossier->taille==""?"NULL":"'$dossier->taille'";
		return "update dossier set dnaiss='$dossier->dnaiss',sexe='$dossier->sexe',taille= $taille, actif='$dossier->actif' , dconsentement='$dossier->dconsentement' ".
						"where cabinet='$dossier->cabinet' and numero ='$dossier->numero'";
	}

	function getUpdateByIdQuery($dossier){
		if(!$this->findDossierById){
			$taille = $dossier->taille==""?"NULL":"'$dossier->taille'";
			$query  = "update dossier set numero='$dossier->numero', dnaiss='$dossier->dnaiss',sexe='$dossier->sexe',taille= $taille, actif='$dossier->actif' , dconsentement='$dossier->dconsentement' ".
				 "where cabinet='$dossier->cabinet' and id ='$dossier->id'";
			$result = $this->queryAny($query);
			return $result;
		}
		else{
			return false;
		}


	}

	function isValidNumber($dossier){
		$query = "select * from dossier ".
			"where cabinet='$dossier->cabinet' and numero ='$dossier->numero'";
			$result = $this->findAnyObject($query);
			if($result == false) return false;
			return $result;
	}
	
	function getFindQuery($dossier){
		return "select * from dossier ".
			"where cabinet='$dossier->cabinet' and numero ='$dossier->numero'";
	}
	
	function getDeleteQuery($dossier){
		return "delete  from dossier ".
			"where numero ='$dossier->numero' and cabinet='$dossier->cabinet'";
	}
	
	function getFindListQUery($dossier){
		return "select * from dossier where cabinet='$dossier->cabinet'";
	}
	
	function doLoadObject($row){
		$dossier = new Dossier($row["id"],$row["cabinet"],$row["numero"],$row["dnaiss"],$row["sexe"],$row["taille"], $row["actif"], (!isset($row["dconsentement"])) ? null : $row["dconsentement"]);	// Pierre : correction "undefined index dconsentement sur AlerteSuiviDiabete"
		return $dossier;
	}
	
	function findDossierById($dossier){
		$query = "select * from dossier where id = $dossier->id";
		$result = $this->findAnyObject($query);
		if($result == false) return false;
		return $result;
	}
	
	function haveChilds($dossier){
		$query = "select * from dossier,depistage_colon,depistage_diabete,depistage_sein,evaluation_infirmier,evaluation_medecin,evaluation_patient,suivi_diabete where dossier.id =$dossier->id and( depistage_colon.id = $dossier->id or depistage_diabete.id = $dossier->id or depistage_sein.id = $dossier->id or evaluation_infirmier.id = $dossier->id or evaluation_medecin.id = $dossier->id or evaluation_patient.id =$dossier->id or suivi_diabete.dossier_id= $dossier->id)";
		$result = $this->findAnyRows($query);
		if($result == false) return false;		
		return true;
	}
	
	function purgeDossierById($dossier){
		$query = "delete from dossier,depistage_colon,depistage_diabete,depistage_sein,evaluation_infirmier,evaluation_medecin,evaluation_patient,suivi_diabete where dossier.id = $dossier->id and depistage_colon.id = $dossier->id and depistage_diabete.id = = $dossier->id and depistage_sein.id = $dossier->id and evaluation_infirmier.id = $dossier->id and evaluation_medecin.id = $dossier->id and evaluation_patient.id = $dossier->id and suivi_diabete.dossier_id= $dossier->id";
		$result = $this->queryAny($query);
		return $result;
	}
	
	function getAge($dossier){
		list($D,$M,$Y) = explode("/",$dossier->dnaiss);
		$age = date('Y') - $Y;
		$currentMonth = date("m");
		$currentDay = date("d");
		if($currentMonth < $M) $age--;
		else if(($currentMonth == $M) and ($currentDay<$D)) $age--;
		return $age;
	}


	/**
	 * listing de tous les groupes du cabinet
	 * @param  [type] $cabinet [description]
	 * @return [type]          [description]
	 */
	function listeGroupsByCabinet($cabinet){

		$query = "select * from groupe where cabinet = '$cabinet'";
		$res = mysql_query($query);
		
		$results = mysql_fetch_object($res);

		return $results;

	}
	
	/**
	 * récupératon des élements d'un dossier par rapport au n° de dossier (N° cabinet)
	 * @param  [type] $dossierID [description]
	 * @return [type]            [description]
	 */
	function getByNum($dossierNum,$cabinet){
		$query = "select * from dossier where numero = '$dossierNum' and cabinet='$cabinet'";
		$res = mysql_query($query);
		$results = mysql_fetch_object($res);
		return $results;
	}

}
?>
