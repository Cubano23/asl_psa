<?php

require_once ("Config.php");
$config = new Config();

include 'conn.php';
include 'departements.php';
include '../cab/update_pat.php';

$cabinet = $_REQUEST['cabinet'];
$prenom = addslashes ($_REQUEST['prenom']);
$nom =    addslashes ($_REQUEST['nom']);
$adeli =  $_REQUEST['adeli'];
$rpps =   $_REQUEST['rpps'];
$courriel=   $_REQUEST['courriel'];
$adresse=   addslashes ($_REQUEST['adresse']);
$codepostal=   $_REQUEST['codepostal'];
$ville=   addslashes ($_REQUEST['ville']);
$telephone=   $_REQUEST['telephone'];
$portable=   $_REQUEST['portable'];
$departement="";
$region="";

if(!is_null($codepostal))
{
    if(strlen($codepostal)==5)
    {
        $code2=substr($codepostal, 0, 2);
        $departement=addslashes ($depts[$code2]);
        $region=addslashes ($regions[$code2]);
    }
}

$sql = "insert into $table(cabinet,prenom,nom, adeli, rpps, courriel,adresse,codepostal,ville, departement,region,telephone, portable) values('$cabinet','$prenom','$nom','$adeli','$rpps','$courriel','$adresse','$codepostal','$ville','$departement','$region','$telephone','$portable')";
error_log($sql);
$result = @mysql_query($sql);


//$xLog=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
//require_once("$xLog/WebService/AsaleeLog.php");
if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
{
    require_once($config->webservice_path . "/AsaleeLog.php");
    LogAccess("psaet.asalee.fr", "mg_save", $UserIDLog, 'na', $prenom.' '.$nom,  1, "Nouveau Medecin Traitant: ".$answerLog."/".$result);
}

$medid=0;
if($result)
{
    update_patients($cabinet, 1);
    // obtenir l'id et mettre à 0 le recordstatus
    $sql = "SELECT id from $table where cabinet='$cabinet' and prenom='$prenom' and nom='$nom' and recordstatus=0";
    $rs = @mysql_query($sql);
    if($rs)
    {
        $row = mysql_fetch_array($rs);
        $medid = $row[0];
        $sql = "INSERT INTO $table_historique_medecin( `medid`,  `actualstatus`) VALUES ( $medid, 0)";
        @mysql_query($sql);
    }
}

require($config->erp_path . "/WebService/erp.php");

if($result)
{
    $rows = array();
    $rows["id_asalee"] = $medid;
    $rows["cabinet"]=$cabinet;
    $rows["login_asalee"]=NULL;
    $rows["prenom"]=$prenom;
    $rows["nom"]=$nom;
    $rows["telephone"]=$telephone ;
    $rows["portable"]=$portable  ;
    $rows["email"]=$courriel ;
    $rows["codePostal"]=$codepostal;
    $rows["ville"]=$ville ;
    $rows["rpps"]=$rpps ;
    $rows["adeli"]=$adeli ;
    $result = Erp_Medecin_Insert($rows);
}
if ($result)
{
    echo json_encode(array('success'=>true));
}
else
{
    echo json_encode(array('msg'=>'Erreur'));
}
?>
