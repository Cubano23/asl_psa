<?php 
require_once("SelfManagedMapper.php");
require_once("bean/DiagnosticEducatif.php");

class DiagnosticEducatifMapper extends SelfManagedMapper{
	

	/**
	 * récupération du dernier diagnostic en cours pour le patient
	 * il ne doit y avoir qu'un seul dossier diagnostic ouvert par dossier.
	 * @param  integer $id_dossier Id du dossier patient
	 * @return object         la ligne complete
	 */
	function getLast($id_dossier,$type){
		
		$req = "select * from diagnostic_educatif where id_dossier='$id_dossier' and type='$type' and statut='1' order by created_at DESC LIMIT 1  ";
		$res = mysql_query($req);
		#echo $req;
		$result = mysql_fetch_object($res);
		#var_dump($result);exit;
		return $result;
	}

	function getLedgerName(){
		return 'diagnostic_educatif';
	}


	static function getObjectToObject($obj){

			$diagnosticEducatif = new DiagnosticEducatif(
				$obj->id_dossier,
				$obj->type,
				$obj->created_at,
				$obj->clotured_at,
				$obj->statut,
				$obj->aspects_limitants,
				$obj->aspects_facilitants,
				$obj->objectifs_patient	
			);
			#var_dump($diagnosticEducatif);
			return $diagnosticEducatif;

		}

	/**
	 * creation d'un nouveau suivi diagnostic educatif
	 * @param [type] $obj [description]
	 */
	static function add($obj){
		#var_dump($obj);exit;
		$req = "INSERT INTO diagnostic_educatif set 
				id_dossier = '$obj->id_dossier',
				created_at = now(),
				type = '$obj->type',
				clotured_at = '0000-00-00 00:00:00',
				statut = '1',
				aspects_limitants = '$obj->aspects_limitants',
				aspects_facilitants = '$obj->aspects_facilitants',
				objectifs_patient = '$obj->objectifs_patient',
				updated_at = now()
				";
		$res = mysql_query($req);

	}


	/**
	 * mise à jour de l'enregistrement du diagnostic
	 * @param  [type] $obj [description]
	 * @return [type]      [description]
	 */
	static function update($obj){

		$req = "UPDATE diagnostic_educatif set 
				clotured_at = '$obj->clotured_at',
				statut = '$obj->statut',
				aspects_limitants = '$obj->aspects_limitants',
				aspects_facilitants = '$obj->aspects_facilitants',
				objectifs_patient = '$obj->objectifs_patient',
				updated_at = now()
				where id_dossier='$obj->id_dossier' and type='$obj->type' and statut='1'
				";
		#echo $req;
		$res = mysql_query($req);

	}


}
