<?php
require_once "persistence/ConnectionAnnuairePDO.php";
require_once "persistence/ConnectionInformedPDO.php";


class FicheCabinet{
	  var $cabinet;
	  var $password;
	  var $nom_complet;
	  var $ville;
	  var $contact;
	  var $telephone;
	  var $courriel;
	  var $region;
	  var $infirmiere;
	  var $total_pat;
	  var $total_sein;
	  var $total_cogni;
	  var $total_colon;
	  var $total_uterus;
	  var $total_diab2;
	  var $total_HTA;
	  var $nom_cab;
	  var $con_annuaire;
	  var $listCabinets;
	

	function FicheCabinet(
					 $cabinet = NULL,
					 $password = NULL,
					 $nom_complet = NULL,
					 $ville = NULL,
					 $contact = NULL,
					 $telephone = NULL,
					 $courriel = NULL,
					 $region = NULL,		
					 $infirmiere = NULL,
					 $total_pat = NULL,
					 $total_sein = NULL,
					 $total_cogni = NULL,
					 $total_colon = NULL,
					 $total_uterus = NULL,
					 $total_diab2 = NULL,
					 $total_HTA = NULL,
					 $nom_cab = NULL){
					 
		 $this->cabinet = $cabinet;
		 $this->password = $password;
		 $this->nom_complet = $nom_complet;
		 $this->ville = $ville;
		 $this->contact = $contact;
		 $this->telephone = $telephone;
		 $this->courriel = $courriel;
		 $this->region = $region;
		 $this->infirmiere = $infirmiere;
		 $this->total_pat = $total_pat;
		 $this->total_sein = $total_sein;
		 $this->total_cogni = $total_cogni;
		 $this->total_colon = $total_colon;
		 $this->total_uterus = $total_uterus;
		 $this->total_diab2 = $total_diab2;
		 $this->total_HTA = $total_HTA;
		 $this->nom_cab = $nom_cab;
	}
	public function __construct()
    {
       

        $db_ann = ConnectionAnnuairePDO::getInstance();
		$this->con_annuaire = $db_ann->getDbh();
		
		$db = ConnectionInformedPDO::getInstance();
		$this->con = $db->getDbh();
		
		

       
    }

	 function toString(){
		 return 
			$this->cabinet." ".
			$this->password." ".
			$this->nom_complet." ".
			$this->ville." ".
			$this->contact." ".
			$this->telephone." ".
			$this->courriel." ".
			$this->region." ".
			$this->infirmiere." ". // fin des controles, nous soumettons
			$this->total_pat." ".
			$this->total_sein." ".
			$this->total_cogni." ".
			$this->total_colon." ".
			$this->total_uterus." ".
			$this->total_diab2." ".
			$this->total_HTA." ".
            $this->nom_cab;
	}

	function beforeSerialisation($account){
		$clone = clone $this;
		return $clone;
	}

	function afterDeserialisation($account){
		$clone = clone $this;
		return $clone;
	}


	function check(){
		$errors = array();
		$i = 0;

		if(!is_numeric($this->total_pat) || ($this->total_pat<0)) $errors[$i++]="Le nombre total de patients n'est pas valide";
		
		if(!is_numeric($this->total_sein)||($this->total_sein<0)) $errors[$i++]="Le nombre total de patientes éligibles au cancer du sein n'est pas valide";

		if(!is_numeric($this->total_cogni)||($this->total_cogni<0)) $errors[$i++]="Le nombre total de patients éligibles aux tests cognitifs n'est pas valide";

		if(!is_numeric($this->total_colon)||($this->total_colon<0)) $errors[$i++]="Le nombre total de patients éligibles au dépistage colon n'est pas valide";

		if(!is_numeric($this->total_uterus)||($this->total_uterus<0)) $errors[$i++]="Le nombre total de patients éligibles au dépistage du cancer du col de l'utérus n'est pas valide";

		if(!is_numeric($this->total_diab2)||($this->total_diab2<0)) $errors[$i++]="Le nombre total de patients diabétiques n'est pas valide";

		if(!is_numeric($this->total_HTA)||($this->total_HTA<0)) $errors[$i++]="Le nombre total de patients éligibles au suivi HTA n'est pas valide";

		return $errors;
	}
	/*
	function listCabinets($login){
		$sql = "SELECT cabinet
		FROM allowedcabinets
		WHERE login = '$login'";
		try
		{
			$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$res = $this->con_annuaire->query($sql);

			$this->listCabinets = $res->fetchAll(PDO::FETCH_ASSOC);
			
		}
		catch (PDOException $e)
		{
			exit($e->getMessage());
		}

		return $this->listCabinets;

	}
*/
}

 ?>
