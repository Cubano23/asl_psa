<?php
require_once("tools/date.php");
require_once("persistence/ConnectionInformedPDO.php");
require_once("persistence/ConnectionAnnuairePDO.php");

class TransfertDossier{
	public $id;
	public $cabinet;
	public $numero;
	public $dnaiss;
	public $sexe;
	public $taille;
	public $actif;
	public $dconsentement;
	public $dcreat;
	public $dmaj;
	public $encnir;
	public $enckey;
	public $numeroIni;
	public $numeroCible;
	public $cabIni;
	public $cabCible;


	public function __construct()
	{
		$db = ConnectionInformedPDO::getInstance();
		$this->con = $db->getDbh();

		$db_ann = ConnectionAnnuairePDO::getInstance();
		$this->con_annuaire = $db_ann->getDbh();
	}

	public function processTransfert(){
		$errors = array();
		$i = 0;

		$this->numeroIni = $_POST['numeroIni'];
		$this->cabIni = $_POST['cabIni'];

		$this->numeroCible = $_POST['numeroCible'];
		$this->cabCible = $_POST['cabCible'];

		if ($this->numeroIni == $this->numeroCible && $this->cabIni == $this->cabCible){
			$errors[$i++] = "Les données cible sont identiques au données source. Veuillez modifier au moins les identifiants";
			return $errors;
		}

		$queryIni = "SELECT *
				FROM dossier
				WHERE numero = :numero
				AND cabinet = :cabinet 
			  	and actif = 'oui' 
				";

		$resIni = $this->con->prepare($queryIni);
		$resIni->bindParam(":numero", $this->numeroIni);
		$resIni->bindParam(":cabinet", $this->cabIni);
		$resIni->execute();
		if ($resIni->rowCount() == 0){
			$errors[$i++] = "Le patient saisi ".$this->numeroIni." n'existe pas dans le cabinet ". $this->cabIni ." .";
			return $errors;
		}

		$queryCible = "SELECT *
				FROM dossier
				WHERE numero = :numero
				AND cabinet = :cabinet 
				";

		$resCible = $this->con->prepare($queryCible);
		$resCible->bindParam(":numero", $this->numeroCible);
		$resCible->bindParam(":cabinet", $this->cabCible);
		$resCible->execute();
		if ($resCible->rowCount() != 0){
			$errors[$i++] = "Le numéro patient cible ".$this->numeroCible." existe déjà dans le cabinet ". $this->cabCible
				." . Veuillez saisir un nouveau numéro et/ou cabinet cible ";
			return $errors;
		}

		// a ce stade nous sommes surs que :
		//  - le patient cible est différent du patient source
		// 	- le patient source existe dans le cabinet source
		//  - le patient cible n'existe pas encore dans le cabinet cible



		$result = $resIni->fetch(PDO::FETCH_OBJ);

		$this->dnaiss = $result->dnaiss;
		$this->sexe = $result->sexe;
		$this->taille = $result->taille;
		$this->dconsentement = $result->dconsentement;
		$this->dmaj = $result->dmaj;
		$this->dcreat = $result->dcreat;
		$this->encnir = $result->encnir;
		$this->enckey = $result->enckey;

		$queryInsert =  "INSERT INTO dossier (cabinet, numero, dnaiss, sexe, taille,actif, dconsentement, dcreat, dmaj, encnir, enckey) 
						VALUES (:cabCible, :numeroCible, :dnaiss, :sexe, :taille, 'oui', :dconsentement, :dcreat, :dmaj, :encnir, :enckey)";
		try
		{
			$resInsert = $this->con->prepare($queryInsert);
			$resInsert->bindParam(':cabCible', 	$this->cabCible , PDO::PARAM_STR);
			$resInsert->bindParam(':numeroCible', $this->numeroCible , PDO::PARAM_INT);
			$resInsert->bindParam(':dnaiss', $this->dnaiss);
			$resInsert->bindParam(':sexe', 	$this->sexe , PDO::PARAM_STR);
			$resInsert->bindParam(':taille', $this->taille , PDO::PARAM_INT);
			$date = date('Y-m-d H:i:s');
			$resInsert->bindParam(':dconsentement',$this->dconsentement);
			error_log("data consentement: " .$this->sexe);
			$resInsert->bindParam(':dmaj',$date);
			$resInsert->bindParam(':dcreat',$date);
			$resInsert->bindParam(':encnir',$this->encnir);
			$resInsert->bindParam(':enckey',$this->enckey);
			$resInsert->execute();
		}
		catch (Exception $exception)
		{
			error_log($exception->getMessage());
			$errors[$i++] = "Insertion impossible";
			return $errors;

		}

		$queryUpdate = "UPDATE dossier
				SET actif = 'non'
				WHERE numero = :numero
				AND cabinet  = :cabinet
				";
		try
		{
			$resUpdate = $this->con->prepare($queryUpdate);
			$resUpdate->bindParam(':numero',$this->numeroIni);
			$resUpdate->bindParam(':cabinet',$this->cabIni);
			$resUpdate->execute();
		}
		catch (Exception $exception)
		{
			error_log($exception->getMessage());
			$errors[$i++] = "Mise à jour impossible";
			return $errors;

		}

		return $errors;
	}
}
?>
