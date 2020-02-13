<?php
session_start();


if($_SERVER['APPLICATION_ENV']=='dev-herve'){

    $idDB = 'root';
    $mdpDB = 'root';
    $DB = 'informed3';

    mysql_connect($serveur,$idDB,$mdpDB) or
    die("Impossible de se connecter au SGBD");
    mysql_select_db($DB) or
    die("Impossible de se connecter &agrave; la base");


}
else{
    // prod
    if(!isset($_SESSION['nom'])) {
        # pas passé par l'identification
        $debut=dirname($_SERVER['PHP_SELF']);
        $self=basename($_SERVER['PHP_SELF']);

        header("Location: $debut/ident_util.php?url=$self");
        echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
        exit;
    }

    set_time_limit(3600);



    require_once "Config.php";
    $config = new Config();

    require($config->inclus_path . "/accesbase.inc.php");
    mysql_connect($serveur,$idDB,$mdpDB) or die("Impossible de se connecter au SGBD");
    mysql_select_db($DB) or die("Impossible de se connecter à la base");


}




function getTpsReu($cabinet,$date_deb){
    //2016-01-04, 2016-12-28
    //prendre 7 jours de plus pour caler la semaine

    $timestamp = strtotime(date($date_deb));
    $dateFin = strtotime("+7day" ,$timestamp);
    $date_fin = date("Y-m-d", $dateFin);
    $sql = "SELECT * from suivi_reunion_medecin where cabinet='$cabinet' and date_reunion >= '$date_deb' and date_reunion <= '$date_fin' ";

    $req = mysql_query($sql);
    $dureeTT = 0;
    $nbre = 0;
    while($row = mysql_fetch_assoc($req)){

        $dureeTT = $dureeTT+$row['duree'];$nbre=$nbre+1;
    }

    return array("temps"=>$dureeTT,"nbre"=>$nbre);
}

function getConsultations($cabinet,$date_deb){
    //2016-01-04, 2016-12-28
    //prendre 7 jours de plus pour caler la semaine

    $timestamp = strtotime(date($date_deb));
    $dateFin = strtotime("+7day" ,$timestamp);
    $date_fin = date("Y-m-d", $dateFin);

    $sql = "SELECT * FROM `evaluation_infirmier` , `dossier` WHERE dossier.id = evaluation_infirmier.id  and dossier.cabinet='$cabinet' and date >= '$date_deb' and date <= '$date_fin' ";
    $req = mysql_query($sql);
    $dureeTT = $tt_diab = $tt_dep_diab = $tt_rcva = $tt_bpco = $tt_cognitif = $tt_autres = 0;
    $nbre = 0;
    while($row = mysql_fetch_assoc($req)){
        $dureeTT = $dureeTT+$row['duree'];$nbre=$nbre+1;
        if(strstr("suivi_diab",$row['type_consultation'])){$tt_diab = $tt_diab+$row['duree'];}
        if(strstr("dep_diab",$row['type_consultation'])){$tt_dep_diab = $tt_dep_diab+$row['duree'];}
        if(strstr("rcva",$row['type_consultation'])){$tt_rcva = $tt_rcva+$row['duree'];}
        if(strstr("bpco",$row['type_consultation'])){$tt_bpco = $tt_bpco+$row['duree'];}
        if(strstr("cognitif",$row['type_consultation'])){$tt_cognitif = $tt_cognitif+$row['duree'];}
        if(strstr("autres",$row['type_consultation'])){$tt_autres = $tt_autres+$row['duree'];}
    }

    $tpsc = array("suivi_diab"=>$tt_diab,"dep_diab"=>$dep_diab,"rcva"=>$rcva,"bpco"=>$bpco,"cognitif"=>$cognitif,"autres"=>$autres);
    $prepaBilan = calculPreparationBilan($tpsc);

    return array("temps"=>$dureeTT,"nbre"=>$nbre,"prepaBilan"=>$prepaBilan);
}


function calculPreparationBilan($TpsConsultation){
    $tempsPrepaBilanConsultation = (($TpsConsultation['suivi_diab']*0.25) + ($TpsConsultation['dep_diab']*0.25) + ($TpsConsultation['rcva']*0.25) +
        ($TpsConsultation['bpco']*0.2) + ($TpsConsultation['cognitif']*0.1) + ($TpsConsultation['autres']*0.2));

    return round($tempsPrepaBilanConsultation);
}

function calculNonAttribue($sh){

    #var_dump($sh);exit;
    $tpsPasse = $sh['info_asalee']+$sh['tps_contact_tel_patient']+$sh['autoformation']+$sh['formation']+$sh['stagiaires']+$sh['tmpsPrepaBilan']+$sh['tps_reunion_infirmiere']+$sh['tps_reu_medecin']+$sh['tps_reunion_medecin']+$sh['tps_consult'];

    $restant = $sh['tps_passe_cabinet']-$tpsPasse;
    return $restant;
}

$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";


$header['suivi_hebdo_temps_passe'] = array("cabinet", "date semaine", "Gestion sur dossier patient", "info_dossiermed (plus utilise)", "nb_contact_tel_patient (plus utilise)", "Contribution aux actions de developpement d'Asalee", "autoformation", "formation", "stagiaires", "nb reunion medecin", "temps reunion medecin (plus utilise)", "nb reunion infirmiere", "temps reunion infirmiere", "Nb de jours travailles dans la semaine", "Autres et/ou Non attribue", "Date de mise a jour", "Temps de preparation bilan",  "Temps reunions medecins (suivi_reunion)", "Nbre de reunions medecins (suivi_reunion)", "Temps consultation (evaluations)", "Nbre de consultations (evaluations)","Tps non attribué recalculé");

$filename = "export_".$table_selected."_".date('Y-m-d_H-i');

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=".$filename.".csv");
header("Pragma: no-cache");
header("Expires: 0");
$outstream = fopen("php://output", "w");

$q = "SELECT * FROM suivi_hebdo_temps_passe ";


$r = mysql_query($q);

fputcsv($outstream, $header['suivi_hebdo_temps_passe'], ';');
while($tab = mysql_fetch_assoc($r)){
    // calcul du temps de réunion medecin sur cette periode
    $suiviReu = getTpsReu($tab['cabinet'],$tab['date']); // renvoi un array
    $consult = getConsultations($tab['cabinet'],$tab['date']);


    $tab['tmpsPrepaBilan'] = $consult['prepaBilan'];

    $tab['tps_reu_medecin'] = $suiviReu['temps'];
    $tab['nbre_reu_medecin'] = $suiviReu['nbre'];

    // duree dans evaluations infirmer
    $tab['tps_consult'] = $consult['temps'];
    $tab['nbre_consult'] = $consult['nbre'];

    // temps restant non attribué
    $tab['restant'] = calculNonAttribue($tab);
    fputcsv($outstream, $tab, ';');
}
fclose($outstream);


?>


