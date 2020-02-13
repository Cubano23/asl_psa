<?php

include 'conn.php';

$cabinet = $_SESSION['cabinet'];
$numero = $_REQUEST['numero'];
$id = $_REQUEST['id'];
$dnaiss=   $_REQUEST['dnaiss'];
$sexe=   $_REQUEST['sexe'];
$dconsentement=   $_REQUEST['dconsentement'];
$taille=    intval($_REQUEST['taille']);
$actif =    $_REQUEST['actif'];
$nir1 =    $_REQUEST['nir1'];
$nir2 =    isset($_REQUEST['nir2'])?$_REQUEST['nir2']:"";
$dnaiss = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $dnaiss);
$dconsentement = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $dconsentement);

$nirclear="ea";
$sex2="1";
if($sexe=="F")
    $sex2="2";
$dnaiss2=substr($dnaiss,2,2);

$separators = array(" ", "-");
$nir1 = substr(str_replace($separators, "", $nir1),0, 13);
if($nir2!="")
    $nir2 = substr(str_replace($separators,"",$nir2) ,0, 13);

$nirclear =$id.";".$sex2.";".$dnaiss2.";".$nir1.";".$nir2;
putenv("GNUPGHOME=/tmp/apache");






$enc = (null);
$res = gnupg_init();
$err="Erreur";
//
// Charger la clé publique du fichier
$pubkey = file_get_contents( 'CNAMTS_PUB.asc');
if(strtolower($cabinet)=="ztest")
    $pubkey = file_get_contents( 'ASALEE_PUB.asc');

//importer la clé 
$rtv = gnupg_import($res, $pubkey);

$fingerprint =  $rtv['fingerprint'];
//var_dump($rtv);
$rtv = gnupg_addencryptkey($res, $fingerprint);
if($rtv!= false)
{
    $encnir = gnupg_encrypt($res, $nirclear);
//            $encnir =$nirclear ; 


    $sql = "UPDATE `dossier` SET `dnaiss`='$dnaiss',`sexe`='$sexe',`taille`=$taille,`actif`='$actif',`dconsentement`='$dconsentement', `encnir`='$encnir'  ".
        "where numero='$numero' and cabinet = '$cabinet'";


    $result = @mysql_query($sql);

}
else
{
    $result = false;
    $err= gnupg_geterror($res);
}

require_once ("Config.php");
$config = new Config();
require_once($config->webservice_path ."/AsaleeLog.php");
LogAccess("", "nir_update", $UserIDLog, 'na', $numero,  2, "Cabinet:".$cabinet." /".$result);

if ($result){
    echo json_encode(array('success'=>true));
}
else {
    echo json_encode(array('msg'=>$err));
}
?>
