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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Taux de complétude des dossiers - cabinet actif</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");

set_time_limit(0);

$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../global/entete.php");
//echo $loc;

entete_asalee("Taux de complétude des dossiers - cabinet actifs");
?>

<br><br>
<?php

# boucle principale
do {
    $repete=false;



    # étape 1 : tableau à la date du jour
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {
            //tableau àla date du jour
            case 1:
                etape_1($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//tableau àla date du jour
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;

    $req="SELECT cabinet, total_diab2, nom_cab, region ".
        "FROM account ".
        "WHERE region!='' and infirmiere!='' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $reg=array();
    while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {

        $t_diab[$cab]=0;

        $tville[$cab]=$ville;

        $regions[$cab]=$region;
        $nb_dossiers[$cab]=0;
        $nb_dossiers_HTA[$cab]=0;
        $rcva[$cab]=0;
        $nb_dossiers[$region]=0;
        $nb_dossiers_HTA[$region]=0;
        $rcva[$region]=0;

        if(!in_array($region, $reg)){
            $reg[]=$region;
        }
    }


    foreach($tville as $cab=>$ville){
        $tcabinet_util[$cab]=0;
    }

    $date3mois=date("Y-m-d", mktime(1, 1, 1, date("m")-3, date('d'), date("Y")));
    $req="SELECT cabinet from evaluation_infirmier, dossier where ".
        "dossier.id=evaluation_infirmier.id and date>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }
    $req="SELECT cabinet from cardio_vasculaire_depart, dossier where ".
        "dossier.id=cardio_vasculaire_depart.id and date>='$date3mois' ".
        "group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }

    $nb_dossiers["tot"]=0;
    $nb_dossiers_HTA["tot"]=0;

    $req="SELECT dossier.cabinet, dossier.id, HTA from cardio_vasculaire_depart, ".
        "dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and actif='oui' order by dossier.id, date DESC";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $id_prec="";
    while(list($cabinet, $id, $HTA)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if($id_prec!=$id){
                $nb_dossiers[$cabinet]=$nb_dossiers[$cabinet]+1;
                $nb_dossiers["tot"]=$nb_dossiers["tot"]+1;
                $nb_dossiers[$regions[$cabinet]]=$nb_dossiers[$regions[$cabinet]]+1;
                $id_prec=$id;

                if($HTA=="oui"){
                    $nb_dossiers_HTA[$cabinet]=$nb_dossiers_HTA[$cabinet]+1;
                    $nb_dossiers_HTA["tot"]=$nb_dossiers_HTA["tot"]+1;
                    $nb_dossiers_HTA[$regions[$cabinet]]=$nb_dossiers_HTA[$regions[$cabinet]]+1;
                    $dossiers_HTA[$id]=1;
                }
            }
        }
    }

    $date1an=date("Y");
    $date1an--;
    $date1an=$date1an."-".date("m")."-".date("d");

    $date3ans=date("Y");
    $date3ans=$date3ans-3;
    $date3ans=$date3ans."-".date("m")."-".date("d");

    echo "<table border='1'><tr><td></td><th>Taux moyen Asalée</th>";

    foreach($reg as $region){
        echo "<th>$region</th>";
    }

    foreach($tville as $ville){
        echo "<th>$ville</th>";
    }

    echo "<tr><td width='150'>Antécédents familiaux<sup>1</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, ".
        "dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and (antecedants='oui' or antecedants='non') and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Tabagisme<sup>2</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and (tabac='oui' or tabac='non') and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Poids<sup>3</sup></td>";

    $req="SELECT dossier.cabinet from liste_exam, cardio_vasculaire_depart as c, ".
        "dossier, account ".
        "where dossier.id=liste_exam.id and c.id=liste_exam.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and date_exam>='$date1an' and type_exam='poids' ".
        "and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }


    echo "<tr><td>Alcool<sup>4</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and (alcool='oui' or alcool='non') and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Cholestérol total<sup>5</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id, max(dChol) from cardio_vasculaire_depart, ".
        "dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id, $dChol)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $req2="SELECT traitement, LDL from cardio_vasculaire_depart ".
                "where dLDL='$dChol' and id='$id'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
            list($traitement, $LDL)=mysql_fetch_row($res2);

            if(($traitement=="Aucun")&&($LDL<=1.6)){//Pas d'hyperlipidémie => alerte 3 ans
                $alerteLDL[$id]=$date3ans;
            }
            else{//Sinon alerte 1 an
                $alerteLDL[$id]=$date1an;
            }

            if($dChol>=$alerteLDL[$id]){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>HDL<sup>6</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id, max(dHDL) from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id, $dHDL)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if($dHDL>=$alerteLDL[$id]){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }


    echo "<tr><td>LDL<sup>7</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id, max(dLDL) from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id, $dLDL)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if($dLDL>=$alerteLDL[$id]){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Triglycérides<sup>8</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id, max(dtriglycerides) from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id, $dtriglycerides)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if($dtriglycerides>=$alerteLDL[$id]){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Glycémie<sup>9</sup></td>";

    $req="SELECT dossier.cabinet, glycemie, max(dgly) from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $glycemie, $dgly)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if($glycemie<1.1){
                $date_alerte=$date3ans;
            }
            else{
                $date_alerte=$date1an;
            }

            if($dgly>=$date_alerte){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Créatinine<sup>10</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and cardio_vasculaire_depart.dCreat>='$date1an' and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if(isset($dossiers_HTA[$id])){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers_HTA["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers_HTA[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers_HTA[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers_HTA[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers_HTA[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Kaliémie<sup>11</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dkaliemie>='$date1an' and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if(isset($dossiers_HTA[$id])){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers_HTA["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers_HTA[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers_HTA[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers_HTA[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers_HTA[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Protéinurie<sup>12</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dproteinurie>='$date1an' and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if(isset($dossiers_HTA[$id])){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers_HTA["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers_HTA[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers_HTA[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers_HTA[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers_HTA[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Fond d'oeil<sup>13</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dfond>='$date3ans' and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if(isset($dossiers_HTA[$id])){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers_HTA["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers_HTA[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers_HTA[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers_HTA[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers_HTA[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Fréquence cardiaque<sup>14</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dpouls>='$date1an' and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Tension<sup>15</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dTA>='$date1an' and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Echocardiogramme Hypertrophie Ventriculaire Gauche<sup>16</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and (HVG='oui' or HVG='non') and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>ECG<sup>17</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dECG>='$date3ans' and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>A défaut Surcharge ventriculaire gauche<sup>18</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and (surcharge_ventricule='oui' or surcharge_ventricule='non')  and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Sokolov<sup>19</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dsokolov>='$date3ans' and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Examen cardio-vasculaire<sup>20</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and exam_cardio>='$date1an' and actif='oui' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Nombre de patients actifs avec au moins un suivi<sup>21</sup></td>";

    echo "<td align='right'>".$nb_dossiers["tot"]."</td>";
    foreach($reg as $region){
        echo "<td align='right'>".$nb_dossiers[$region]."</td>";
    }

    foreach($tville as $cab=>$ville){
        echo "<td align='right'>".$nb_dossiers[$cab]."</td>";
    }

    echo "<tr><td>Nombre de patients actifs avec au moins un suivi et présentant une hypertension<sup>22</sup></td>";

    echo "<td align='right'>".$nb_dossiers_HTA["tot"]."</td>";
    foreach($reg as $region){
        echo "<td align='right'>".$nb_dossiers_HTA[$region]."</td>";
    }

    foreach($tville as $cab=>$ville){
        echo "<td align='right'>".$nb_dossiers_HTA[$cab]."</td>";
    }

    echo "</table><br><br>";

    $annee0=2007;
    $mois0=3;

    $annee=date('Y');
    $mois=date('m');

    $mois--;


    if($mois<3)
    {
        $annee--;
        $mois=12;
    }
    elseif(($mois>=3)&&($mois<6))
    {
        $mois=3;
    }
    elseif(($mois>=6)&&($mois<9))
    {
        $mois=6;
    }
    elseif(($mois>=9)&&($mois<12))
    {
        $mois=9;
    }

    $jour[3]=$jour[12]=31;
    $jour[6]=$jour[9]=30;

    while(($annee>$annee0)||(($annee==$annee0)&&($mois>=$mois0)))
    {
        if($mois<10)
        {
            $date=$annee.'-0'.$mois.'-'.$jour[$mois];
        }
        else
        {
            $date=$annee.'-'.$mois.'-'.$jour[$mois];
        }
        tableau($date, $regions);

        $mois=$mois-3;

        if($mois<=0)
        {
            $mois=$mois+12;
            $annee--;
        }
    }

    echo "<sup>1</sup> Taux de patients pour lesquels les antécédents familiaux ont été renseignés à la date du jour<br>".
        "<sup>2</sup> Taux de patients pour lesquels le tabagisme a été renseigné à la date du jour<br>".
        "<sup>3</sup> Taux de patients pour lesquels le poids date de moins d'un an<br>".
        "<sup>4</sup> Taux de patients pour lesquels la consommation d'alcool a été renseignée<br>".
        "<sup>5</sup> Taux de patients pour lesquels le cholestérol total date de moins d'un an<br>".
        "<sup>6</sup> Taux de patients pour lesquels le HDL date de moins d'un an<br>".
        "<sup>7</sup> Taux de patients pour lesquels le LDL date de moins d'un an<br>".
        "<sup>8</sup> Taux de patients pour lesquels les triglycérides datent de moins d'un an<br>".
        "<sup>9</sup> Taux de patients pour lesquels la glycémie date de moins d'un an<br>".
        "<sup>10</sup> Taux de patients pour lesquels la créatinine date de moins d'un an<br>".
        "<sup>11</sup> Taux de patients pour lesquels la kaliémie date de moins d'un an<br>".
        "<sup>12</sup> Taux de patients pour lesquels la protéinurie date de moins d'un an<br>".
        "<sup>13</sup> Taux de patients pour lesquels le fond d'oeil date de moins de 3 ans<br>".
        "<sup>14</sup> Taux de patients pour lesquels la fréquence cardiaque date de moins d'un an<br>".
        "<sup>15</sup> Taux de patients pour lesquels la tension artérielle date de moins d'un an<br>".
        "<sup>16</sup> Taux de patients pour lesquels l'échocardiogramme hypertrophie ventriculaire gauche a été renseigné<br>".
        "<sup>17</sup> Taux de patients pour lesquels l'ECG date de moins de 3 ans<br>".
        "<sup>18</sup> Taux de patients pour lesquels la surcharge ventriculaire gauche a été renseignée<br>".
        "<sup>19</sup> Taux de patients pour lesquels le sokolov date de moins d'un an<br>".
        "<sup>20</sup> Taux de patients pour lesquels l'examen cardio-vasculaire date de moins d'un an<br>".
        "<sup>21</sup> Nombre de patients actifs à la date du jour et ayant eu au moins un questionnaire collecte de données RCVA rempli<br><br>".
        "<sup>22</sup> Nombre de patients actifs à la date du jour, ayant eu au moins un questionnaire collecte de données RCVA rempli et pour lesquels le dernier suivi indique une hyper tension artérielle. Cette valeur sert de base pour le calcul de la kaliémie, de la créatinine, du fond d'oeil, de la protéinurie, de l'hématurie<br><br>".
        "Un cabinet est considéré actif à une date, s'il y a eu au moins une consultation ou un suivi RCVA (formulaire collecte de données) complété dans les 3 mois précédents";

}

//arrêtés trimestriels
function tableau($date, $regions){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";



    $req="SELECT cabinet, total_diab2, nom_cab, region ".
        "FROM account ".
        "WHERE region!='' and infirmiere!='' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $reg=array();
    while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {

        $t_diab[$cab]=0;

        $tville[$cab]=$ville;

        $regions[$cab]=$region;
        $nb_dossiers[$cab]=0;
        $nb_dossiers_HTA[$cab]=0;
        $rcva[$cab]=0;
        $nb_dossiers[$region]=0;
        $nb_dossiers_HTA[$region]=0;
        $rcva[$region]=0;

        if(!in_array($region, $reg)){
            $reg[]=$region;
        }
    }


    foreach($tville as $cab=>$ville){
        $tcabinet_util[$cab]=0;
    }

    $date3mois=date("Y-m-d", mktime(1, 1, 1, $tab_date[1]-3, $tab_date[2], $tab_date[0]));

    $req="SELECT cabinet from evaluation_infirmier, dossier where ".
        "dossier.id=evaluation_infirmier.id and date>='$date3mois' ".
        "and date<='$date' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }
    $req="SELECT cabinet from cardio_vasculaire_depart, dossier where ".
        "dossier.id=cardio_vasculaire_depart.id and date>='$date3mois' ".
        "and date<='$date' ".
        "group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }

    $nb_dossiers["tot"]=0;
    $nb_dossiers_HTA["tot"]=0;

    $date1an=$tab_date[0];
    $date1an--;
    $date1an=$date1an."-".$tab_date[1]."-".$tab_date[2];

    $date3ans=$tab_date[0];
    $date3ans--;
    $date3ans=$date3ans."-".$tab_date[1]."-".$tab_date[2];



    $req="SELECT dossier.cabinet, dossier.id, HTA from cardio_vasculaire_depart, ".
        "dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date'))  and cardio_vasculaire_depart.date<='$date' ".
        "order by dossier.id, date DESC";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $id_prec="";
    while(list($cabinet, $id, $HTA)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if($id_prec!=$id){
                $nb_dossiers[$cabinet]=$nb_dossiers[$cabinet]+1;
                $nb_dossiers["tot"]=$nb_dossiers["tot"]+1;
                $nb_dossiers[$regions[$cabinet]]=$nb_dossiers[$regions[$cabinet]]+1;

                $id_prec=$id;
                if($HTA=="oui"){
                    $nb_dossiers_HTA[$cabinet]=$nb_dossiers_HTA[$cabinet]+1;
                    $nb_dossiers_HTA["tot"]=$nb_dossiers_HTA["tot"]+1;
                    $nb_dossiers_HTA[$regions[$cabinet]]=$nb_dossiers_HTA[$regions[$cabinet]]+1;
                    $dossiers_HTA[$id]=1;
                }
            }
        }
    }

    $date1an=date("Y");
    $date1an--;
    $date1an=$date1an."-".date("m")."-".date("d");

    $date3ans=date("Y");
    $date3ans=$date3ans-3;
    $date3ans=$date3ans."-".date("m")."-".date("d");

    echo "<table border='1'><tr><td></td><th>Taux moyen Asalée</th>";

    foreach($reg as $region){
        echo "<th>$region</th>";
    }

    foreach($tville as $ville){
        echo "<th>$ville</th>";
    }

    echo "<tr><td width='150'>Antécédents familiaux <sup>1</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, ".
        "dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and (antecedants='oui' or antecedants='non') and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date'))  ".
        "and cardio_vasculaire_depart.date<='$date' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Tabagisme <sup>2</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and (tabac='oui' or tabac='non') and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) ".
        "and cardio_vasculaire_depart.date<='$date' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Poids <sup>3</sup></td>";

    $req="SELECT dossier.cabinet from liste_exam, cardio_vasculaire_depart as c, ".
        "dossier, account ".
        "where dossier.id=liste_exam.id and c.id=liste_exam.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and date_exam>='$date1an' and date_exam<='$date' and type_exam='poids' ".
        "and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date'))  group by dossier.id";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }


    echo "<tr><td>Alcool <sup>4</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and (alcool='oui' or alcool='non') and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date'))  ".
        "and cardio_vasculaire_depart.date<='$date' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Cholestérol total <sup>5</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id, max(dChol) from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and dChol<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

// echo $req;
    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id, $dChol)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $req2="SELECT traitement, dLDL, LDL from cardio_vasculaire_depart ".
                "where dLDL<='$date' and id='$id' order by dLDL DESC limit 0, 1";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
            list($traitement, $dLDL, $LDL)=mysql_fetch_row($res2);

            if(($traitement=="Aucun")&&($LDL<=1.6)){//Pas d'hyperlipidémie => alerte 3 ans
                $alerteLDL[$id]=$date3ans;
            }
            else{//Sinon alerte 1 an
                $alerteLDL[$id]=$date1an;
            }

            if($dChol>=$alerteLDL[$id]){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>HDL <sup>6</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id, max(dHDL) from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and dHDL<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id, $dHDL)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if(!isset($alerteLDL[$id])){
                $req2="SELECT traitement, dLDL, LDL from cardio_vasculaire_depart ".
                    "where dLDL<='$date' and id='$id' order by dLDL DESC limit 0, 1";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
                list($traitement, $dLDL, $LDL)=mysql_fetch_row($res2);

                if(($traitement=="Aucun")&&($LDL<=1.6)){//Pas d'hyperlipidémie => alerte 3 ans
                    $alerteLDL[$id]=$date3ans;
                }
                else{//Sinon alerte 1 an
                    $alerteLDL[$id]=$date1an;
                }
            }

            if($dHDL>'0000-00-00'){
                if($dHDL>=$alerteLDL[$id]){
                    $nb["tot"]=$nb["tot"]+1;
                    $nb[$cabinet]=$nb[$cabinet]+1;
                    $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
                }
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }


    echo "<tr><td>LDL <sup>7</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id, max(dLDL) from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and dLDL<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id, $dLDL)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if(!isset($alerteLDL[$id])){
                $req2="SELECT traitement, dLDL, LDL from cardio_vasculaire_depart ".
                    "where dLDL<='$date' and id='$id' order by dLDL DESC limit 0, 1";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
                list($traitement, $dLDL, $LDL)=mysql_fetch_row($res2);

                if(($traitement=="Aucun")&&($LDL<=1.6)){//Pas d'hyperlipidémie => alerte 3 ans
                    $alerteLDL[$id]=$date3ans;
                }
                else{//Sinon alerte 1 an
                    $alerteLDL[$id]=$date1an;
                }
            }
            if($dLDL>=$alerteLDL[$id]){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Triglycérides <sup>8</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id, max(dtriglycerides) from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and dtriglycerides<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id, $dtriglycerides)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if(!isset($alerteLDL[$id])){
                $req2="SELECT traitement, dLDL, LDL from cardio_vasculaire_depart ".
                    "where dLDL<='$date' and id='$id' order by dLDL DESC limit 0, 1";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");
                list($traitement, $dLDL, $LDL)=mysql_fetch_row($res2);

                if(($traitement=="Aucun")&&($LDL<=1.6)){//Pas d'hyperlipidémie => alerte 3 ans
                    $alerteLDL[$id]=$date3ans;
                }
                else{//Sinon alerte 1 an
                    $alerteLDL[$id]=$date1an;
                }
            }
            if($dtriglycerides>=$alerteLDL[$id]){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Glycémie <sup>9</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dgly>='$date1an' and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and dgly<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Créatinine <sup>10</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and cardio_vasculaire_depart.dCreat>='$date1an' and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and cardio_vasculaire_depart.dCreat<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if(isset($dossiers_HTA[$id])){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers_HTA["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers_HTA[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers_HTA[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers_HTA[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers_HTA[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Kaliémie <sup>11</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dkaliemie>='$date1an' and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and dkaliemie<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if(isset($dossiers_HTA[$id])){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers_HTA["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers_HTA[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers_HTA[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers_HTA[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers_HTA[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Protéinurie <sup>12</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dproteinurie>='$date1an' and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and dproteinurie<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if(isset($dossiers_HTA[$id])){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers_HTA["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers_HTA[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers_HTA[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers_HTA[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers_HTA[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Fond d'oeil <sup>13</sup></td>";

    $req="SELECT dossier.cabinet, dossier.id from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dfond>='$date3ans' and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and dfond<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet, $id)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            if(isset($dossiers_HTA[$id])){
                $nb["tot"]=$nb["tot"]+1;
                $nb[$cabinet]=$nb[$cabinet]+1;
                $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
            }
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers_HTA["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers_HTA[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers_HTA[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers_HTA[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers_HTA[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Fréquence cardiaque <sup>14</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dpouls>='$date1an' and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and dpouls<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Tension <sup>15</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dTA>='$date1an' and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and dTA<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Echocardiogramme Hypertrophie Ventriculaire Gauche <sup>16</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and (HVG='oui' or HVG='non') and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date'))  ".
        "and cardio_vasculaire_depart.date<='$date' group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>ECG <sup>17</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dECG>='$date3ans' and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and dECG<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>A défaut Surcharge ventriculaire gauche <sup>18</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and (surcharge_ventricule='oui' or surcharge_ventricule='non')  ".
        "and cardio_vasculaire_depart.date<='$date' and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and date<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Sokolov <sup>19</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and dsokolov>='$date3ans' and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and dsokolov<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Examen cardio-vasculaire <sup>20</sup></td>";

    $req="SELECT dossier.cabinet from cardio_vasculaire_depart, dossier, account ".
        "where dossier.id=cardio_vasculaire_depart.id and ".
        "dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
        "and exam_cardio>='$date1an' and ( (dossier.actif='oui' ".
        "AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND ".
        "dossier.dcreat<='$date')) and exam_cardio<='$date'  group by dossier.id";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb["tot"]=0;
    foreach($reg as $region){
        $nb[$region]=0;
    }

    foreach($tville as $cab=>$ville){
        $nb[$cab]=0;
    }

    while(list($cabinet)=mysql_fetch_row($res)){
        if($tcabinet_util[$cabinet]==1){
            $nb["tot"]=$nb["tot"]+1;
            $nb[$cabinet]=$nb[$cabinet]+1;
            $nb[$regions[$cabinet]]=$nb[$regions[$cabinet]]+1;
        }
    }

    echo "<td align='right'>".round($nb["tot"]/$nb_dossiers["tot"]*100)." %</td>";
    foreach($reg as $region){
        if($nb_dossiers[$region]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$region]/$nb_dossiers[$region]*100)." %</td>";
        }
    }

    foreach($tville as $cab=>$ville){
        if($nb_dossiers[$cab]==0){
            echo "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".round($nb[$cab]/$nb_dossiers[$cab]*100)." %</td>";
        }
    }

    echo "<tr><td>Nombre de patients actifs avec au moins un suivi <sup>21</sup></td>";

    echo "<td align='right'>".$nb_dossiers["tot"]."</td>";
    foreach($reg as $region){
        echo "<td align='right'>".$nb_dossiers[$region]."</td>";
    }

    foreach($tville as $cab=>$ville){
        echo "<td align='right'>".$nb_dossiers[$cab]."</td>";
    }

    echo "<tr><td>Nombre de patients actifs avec au moins un suivi et HTA <sup>22</sup></td>";

    echo "<td align='right'>".$nb_dossiers_HTA["tot"]."</td>";
    foreach($reg as $region){
        echo "<td align='right'>".$nb_dossiers_HTA[$region]."</td>";
    }

    foreach($tville as $cab=>$ville){
        echo "<td align='right'>".$nb_dossiers_HTA[$cab]."</td>";
    }

    echo "</table><br><br>";
}
?>
</body>
</html>
