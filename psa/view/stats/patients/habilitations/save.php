<?php

// EA 23-03-2018 ajout erp 
// EA 29-08-2018 ajout psamed

require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/connectannuaire.php");

$con = DoConnect();

$table = "identifications";
$login = $_REQUEST['login'];
$prenom = $_REQUEST['prenom'];
$nom =    $_REQUEST['nom'];

$telephone=   $_REQUEST['telephone'];
$email=   $_REQUEST['email'];
$profession=   $_REQUEST['profession'];
$type=   intval($_REQUEST['type']);
$pass= "frs156"; //  $_REQUEST['pass'];       EA 19-12-2016
$psa=   intval($_REQUEST['psa']);
$psae=   intval($_REQUEST['psae']);
$psv=   intval($_REQUEST['psv']);
$psaet=   intval($_REQUEST['psaet']);
$psvae=   intval($_REQUEST['psvae']);
$idcps=   $_REQUEST['idcps'];
$adeli=   $_REQUEST['adeli'];
$psar=   intval($_REQUEST['psar']);

$erp=   intval($_REQUEST['erp']);
  $psamed=   intval($_REQUEST['psamed']);

$sql = " INSERT INTO `identifications`(`login`, `nom`, `prenom`, `telephone`, `email`, `profession`,`adeli`, `type`) VALUES ('$login', '$nom', '$prenom', '$telephone', '$email', '$profession', '$adeli', ".  strval($type)." )";

$result = @mysql_query($sql);
if ($result){
    $sql = "INSERT INTO `habilitations`(`login`, `pass`, `psa`, `psae`, `psaet`, `psv`, `psvae`,  `psar`, `erp`, `psamed`, `idcps`)".
          " VALUES ('$login', '$pass',". strval($psa).",". strval($psae).",".strval($psaet).",".strval($psv).",".strval($psvae).",".strval($psar).",".strval($erp).",".strval($psamed).",'$idcps')";
    $result = @mysql_query($sql);



    require_once ("Config.php");
    $config = new Config();

    if($_SERVER['APPLICATION_ENV'] != 'dev-herve')
    {
        require_once($config->webservice_path . "/AsaleeLog.php");
        LogAccess("psaet.asalee.fr", "hab_save", $UserIDLog, 'na', $login,  1, "Nouvelle Habilitation: ".$answerLog."/".$result);
    }


    if ($result){
        echo json_encode(array('success'=>true));
    }
    else
    {
        $answer = "Err Habilitations " + mysql_error();
        echo json_encode(array('msg'=>$answer));
    }
}
else {
    $answer = "Err Identifications " + mysql_error();
    echo json_encode(array('msg'=>$answer));
}

?>
