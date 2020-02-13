<?php

require_once ("Config.php");
$config = new Config();

include 'conn.php';

$cabinet =    trim($_REQUEST['cabinet']," ");
$password = $_REQUEST['password'];
$nom_complet = $_REQUEST['nom_complet'];
$nom_cab = $_REQUEST['nom_cab'];
//$ville =   $_REQUEST['ville'];
$adresseCabinet =   $_REQUEST['adresseCabinet'];

$contact = $_REQUEST['contact'];
$region=   $_REQUEST['region'];
$telephone=   $_REQUEST['telephone'];
$portable=   $_REQUEST['portable'];
$courriel=   $_REQUEST['courriel'];
$infirmiere =    $_REQUEST['infirmiere'];

$total_pat = $_REQUEST['total_pat'];
$total_sein  = $_REQUEST['total_sein'];
$total_cogni  = $_REQUEST['total_cogni'];
$total_colon  = $_REQUEST['total_colon'];
$total_uterus  = $_REQUEST['total_uterus'];
$total_diab2  = $_REQUEST['total_diab2'];
$total_HTA  = $_REQUEST['total_HTA'];
$logiciel = $_REQUEST['logiciel'];
$log_ope = $_REQUEST['log_ope'];

require "departements.php";

$cp = $_REQUEST['code_postal'];  //EA 12-09-2017

if( /*($region=="") &&*/ ($cp!="") )
{
    $cp2 =   substr ( $cp , 0,2 );
    $region = $regions[$cp2];
}

//error_log($cabinet);
$sql="INSERT INTO account SET cabinet='$cabinet', password='$password', ".
    "nom_complet='".addslashes(stripslashes($nom_complet))."', ".
    "adresseCabinet='".addslashes(stripslashes($adresseCabinet))."', contact='".
    addslashes(stripslashes($contact))."', telephone='$telephone', ".
    "courriel='$courriel', total_pat='$total_pat', total_sein='$total_sein', ".
    "total_cogni='$total_cogni', total_colon='$total_colon', ".
    "total_uterus='$total_uterus', total_diab2='$total_diab2', ".
    "total_HTA='$total_HTA', infirmiere='".addslashes(stripslashes($infirmiere))."', ".
    "nom_cab='".addslashes(stripslashes($nom_cab))."', ".
    "portable='$portable', region='".addslashes(stripslashes($region))."', ".
    "log_ope='$log_ope',".
    "code_postal='".addslashes(stripslashes($cp))."', ".
    "logiciel='".addslashes(stripslashes($logiciel))."'";
$result = @mysql_query($sql);

if($result)
{
    $sql = "INSERT INTO `historique_account`( `cabinet`,  `actualstatus`) VALUES ( '$cabinet', 0)";
    @mysql_query($sql);
}


if ($result){
    $req="INSERT INTO histo_account SET cabinet='$cabinet', ".
        "d_modif='".date("Y-m-d H:i:s")."', ".
        "total_pat='$total_pat', total_sein='$total_sein', ".
        "total_cogni='$total_cogni', total_colon='$total_colon', ".
        "total_uterus='$total_uterus', total_diab2='$total_diab2', ".
        "total_HTA='$total_HTA'";
    $result=mysql_query($req);


}

//$xLog=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require($config->erp_path . "/WebService/erp.php");

if ($result){
    $rows = array();
    $req = "select id from account where cabinet='$cabinet'";
    $rs = mysql_query($req);
    $row = mysql_fetch_row($rs);
    $rows["id"] = $row[0];
    $rows["cabinet"]=$cabinet;
    $rows["nom_complet"]=$nom_complet;
    $rows["logiciel"]=$logiciel   ;
    $rows["telephone"]=$telephone ;
    $rows["portable"]=$portable  ;
    $rows["email"]=$courriel ;
    $rows["code_postal"]=$cp;
//    $rows["ville"]=$ville ;
    $rows["adresseCabinet"]=$adresseCabinet ;
    $result = Erp_Cabinet_Insert($rows);
}

//require_once("$xLog/WebService/AsaleeLog.php");

if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
{
    require_once($config->webservice_path . "/AsaleeLog.php");
    LogAccess("psaet.asalee.fr", "cab_save", $UserIDLog, 'na', $cabinet,  1, "Nouveau Cabinet: ".$answerLog."/".$result);
}

if ($result){
    echo json_encode(array('success'=>true));
}
else {
    echo json_encode(array('msg'=>'Erreur'));
}
?>
