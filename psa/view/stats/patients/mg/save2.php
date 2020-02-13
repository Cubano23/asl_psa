<?php


include 'conn.php';
include '../cab/update_pat.php';

$cabinet   = $_GET['cabinet'];
$prenom    = $_REQUEST['prenom'];
$nom       = $_REQUEST['nom'];
$courriel  = $_REQUEST['courriel'];
$telephone = $_REQUEST['telephone'];
$portable  = $_REQUEST['portable'];
$adeli     = $_REQUEST['adeli'];
$rpps      = $_REQUEST['rpps'];

if( ($cabinet!='') && ($nom!='') && ($prenom!='') )
{
    $sql = "insert into $table(cabinet, prenom, nom, courriel, telephone, portable, adeli, rpps) values('$cabinet', '$prenom', '$nom', '$courriel', '$telephone', '$portable', '$adeli', '$rpps')";
    $result = @mysql_query($sql);


    require_once ("Config.php");
    $config = new Config();

    if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
    {
        require_once($config->webservice_path . "/AsaleeLog.php");
        LogAccess("psaet.asalee.fr", "mg_save", $UserIDLog, 'na', $prenom.' '.$nom,  1, "Nouveau Medecin Traitant: ".$answerLog."/".$result);
    }

    if($result)
    {
        update_patients($cabinet, 1);

        // obtenir l'id et mettre à 0 le recordstatus
        $sql = "SELECT id, cabinet, prenom, nom from $table where cabinet='$cabinet' and prenom = '$prenom' and nom = '$nom' and recordstatus=0;";
        $rs = @mysql_query($sql);
        if($rs)
        {
            $row = mysql_fetch_array($rs);
            $id = $row[0];
            $cab = $row[1];
            $nom = $row[2];
            $prenom = $row[3];
            $sql = "INSERT INTO historique_medecin(medid, cabinet, nom, prenom, actualstatus) VALUES ($id, '$cab', '$nom', '$prenom', 0);";
            $rs2 = @mysql_query($sql);

        }
    }

    if($result)
    {



    }



    if ($result){
        echo json_encode(array('success'=>true));
    }
    else {
        echo json_encode(array('msg'=>'Erreur'));
    }
}
/*  else
		echo json_encode(array('msg'=>'Champ Vide'));*/

?>
