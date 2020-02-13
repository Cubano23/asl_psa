<?php
session_start();
if(!isset($_SESSION['nom'])) {
    # pas passé par l'identification
    $debut=dirname($_SERVER['PHP_SELF']);
    $self=basename($_SERVER['PHP_SELF']);

    header("Location: $debut/ident_util.php?url=$self");
    echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
    exit;
}
set_time_limit(0);

require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");
mysql_connect($serveur,$idDB,$mdpDB) or die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or die("Impossible de se connecter à la base");

$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;


$header['suivi_hebdo_temps_passe_infirmiere'] = array("nom", "prenom", "login", "statut", "type",  "date suivi", "cabinet", "nbre heures declarees");


$filename = "export_".$table_selected."_".date('Y-m-d_H-i');

$export=true;
if($export){
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=".$filename.".csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    $outstream = fopen("php://output", "w");
}



$q = "SELECT * FROM suivi_hebdo_temps_passe_infirmiere order by semaine DESC";
$r = mysql_query($q);


fputcsv($outstream, $header['suivi_hebdo_temps_passe_infirmiere'], ';');


while($tab = mysql_fetch_assoc($r)){

    $currentInf = current(GetInfosByLogin($tab['infirmiere'], $cr)) ;

    if($currentInf['type']=='0'){
        $type = 'salarié';
    }
	elseif($currentInf['type']=='1'){
        $type = 'libérale';
    }
    else{
        $type = 'autre';
    }
    #var_dump($currentInf);
    $data['nom'] = utf8_decode($currentInf['nom']);
    $data['prenom'] = utf8_decode($currentInf['prenom']);
    $data['login'] = $tab['infirmiere'];
    $data['statut'] = utf8_decode($currentInf['status']);
    $data['type'] = utf8_decode($type);
    $data['semaine'] = $tab['semaine'];
    $data['cabinet'] = $tab['cabinet'];
    $data['total_heures'] = $tab['duree'];


    #var_dump($data);exit;

    fputcsv($outstream, $data, ';');
}
fclose($outstream);


?>
