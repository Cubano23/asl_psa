<?php
session_start();
require_once("persistence/ConnectionFactory.php");
require_once("bean/Account.php"); 
require_once("persistence/AccountMapper.php");
require_once("persistence/DossierMapper.php");
require_once("controler/GroupesControler.php");
require_once("bean/GroupesDossiers.php");


#
$conn = new ConnectionFactory();
$conn->getConnection();



// creation de l'objet groupe
$groupe = new GroupesDossiers();
$groupe->id_groupe = $_POST['idgroupe'];
$groupe->cabinet = $_POST['cabinet'];
$groupe->libelle = addslashes($_POST['libelle']);
$groupe->commentaire = addslashes($_POST['commentaire']);
$groupe->dossiers = $_POST['dossiers'];

$dossiersTab = explode(',',$groupe->dossiers);
$dossiersTabFull = array();
foreach($dossiersTab as $dossierNum){
	$infosDossier = DossierMapper::getByNum($dossierNum,$groupe->cabinet);
	if($infosDossier && $infosDossier->actif!='non'){
		array_push($dossiersTabFull,array($dossierNum => $infosDossier->id));
	}
}

$groupe->dossiers = json_encode($dossiersTabFull);



if($groupe->id_groupe!=''){
	GroupesDossiers::save($groupe);
}
else{
	GroupesDossiers::add($groupe);
}



#header('location:liste_groupes.php');



?>

<script language="javascript">
document.location.href="liste_groupes.php"
</script>