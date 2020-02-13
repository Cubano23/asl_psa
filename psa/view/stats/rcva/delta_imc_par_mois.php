<?php
session_start();
if(!isset($_SESSION['nom'])) {
    # pas pass� par l'identification
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
    <title>Nombre d'IMC par mois et delta par rapport � la 1�re IMC</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux donn�es
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter � la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
require("../global/entete.php");
//echo $loc;

$titre="Nombre de patients ayant une IMC calculable mois par mois et delta par rapport � la 1�re IMC";


entete_asalee($titre);
//echo $loc;
?>
<br><br>
<?php

# boucle principale
do {
    $repete=false;

    # fen�tre glissante:
    if (isset($_GET['mois']) && isset($_GET['annee']))
    {
        etape_2($repete);
        exit;
    }

    # �tape 1 : identification du patient et de la date
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1:
                etape_1($repete);
                break;

            # �tape 2  : saisie des d�tails
            case 2:
                etape_2($repete);
                break;

            # �tape 3  : validation des donn�es et m�j base
            case 3:
                etape_3($repete);
                break;
        }
    }
} while($repete);

# fin de traitement principal


function etape_1(&$repete) {
global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet;


$req="SELECT account.cabinet, count(*), nom_cab, region ".
    "FROM dossier, account ".
    "WHERE account.cabinet!='zTest' and account.cabinet!='irdes'   and account.cabinet!='ergo' ".
    "and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
    "AND dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
    "AND actif='oui' ".
    "GROUP BY nom_cab ".
    "ORDER BY nom_cab, numero ";
//echo $req;
//die;
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

if (mysql_num_rows($res)==0) {
    exit ("<p align='center'>Aucun cabinet n'est actif</p>");
}
$tcabinet=array();
$regions=array();
$dossiers["tot"]=array();
// $nb_dossiers["tot"]=0;

while(list($cab, $pat, $ville, $region) = mysql_fetch_row($res)) {
    $tcabinet[] = $cab;
    $tville[]=$ville;
    $tab_region[$cab]=$region;
    $dossiers[$cab]=array();
    // $nb_dossiers[$cab]=0;

    if(!in_array($region, $regions)){
        $regions[]=$region;
        $dossiers[$region]=array();
        // $nb_dossiers[$region]=0;
    }
//	 $tpat[$cab] = $pat;
}

$mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre',
    '11'=>'Novembre', '12'=>'D�cembre');

//echo '<b>Donn�es � la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

?>
<br>
<br>

<table border=1>
    <tr>
        <td width='350'></td>
        <td><b>Nb total dossiers (toutes IMC confondues)</b></td>
        <td><b>IMC au d�part (tous patients confondus)</b></td>
        <td><b>IMC � la p�riode (tous patients confondus)</b></td>
        <td><b>Delta (tous patients confondus)</b></td>
        <td><b>Nb total dossiers (IMC &gt;30 � la 1�re IMC)</b></td>
        <td><b>IMC au d�part (IMC &gt;30 � la 1�re IMC)</b></td>
        <td><b>IMC � la p�riode (IMC &gt;30 � la 1�re IMC)</b></td>
        <td><b>Delta (IMC &gt;30 � la 1�re IMC)</b></td>
        <td><b>Nb total dossiers (25&lt;= IMC &lt;30  � la 1�re IMC)</b></td>
        <td><b>IMC au d�part (25&lt;= IMC &lt;30 � la 1�re IMC)</b></td>
        <td><b>IMC � la p�riode (25&lt;= IMC &lt;30 � la 1�re IMC)</b></td>
        <td><b>Delta (25&lt;= IMC &lt;30 � la 1�re IMC)</b></td>
        <td><b>Nb total dossiers (30&lt;= IMC &lt;40 � la 1�re IMC)</b></td>
        <td><b>IMC au d�part (30&lt;= IMC &lt;40 � la 1�re IMC)</b></td>
        <td><b>IMC � la p�riode (30&lt;= IMC &lt;40 � la 1�re IMC)</b></td>
        <td><b>Delta (30&lt;= IMC &lt;40 � la 1�re IMC)</b></td>
        <td><b>Nb total dossiers (IMC &gt;40 � la 1�re IMC)</b></td>
        <td><b>IMC au d�part (IMC &gt;40 � la 1�re IMC)</b></td>
        <td><b>IMC � la p�riode (IMC &gt;40 � la 1�re IMC)</b></td>
        <td><b>Delta (IMC &gt;30 � la 1�re IMC)</b></td>
        <td><b>Nb total dossiers (toutes IMC confondues - patients avec consultation)</b></td>
        <td><b>IMC au d�part (tous patients confondus - patients avec consultation)</b></td>
        <td><b>IMC � la p�riode (tous patients confondus - patients avec consultation)</b></td>
        <td><b>Delta (tous patients confondus - patients avec consultation)</b></td>
        <td><b>Nb total dossiers (IMC &gt;30 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>IMC au d�part (IMC &gt;30 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>IMC � la p�riode (IMC &gt;30 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>Delta (IMC &gt;30 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>Nb total dossiers (25&lt;= IMC &lt;30  � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>IMC au d�part (25&lt;= IMC &lt;30 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>IMC � la p�riode (25&lt;= IMC &lt;30 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>Delta (25&lt;= IMC &lt;30 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>Nb total dossiers (30&lt;= IMC &lt;40 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>IMC au d�part (30&lt;= IMC &lt;40 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>IMC � la p�riode (30&lt;= IMC &lt;40 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>Delta (30&lt;= IMC &lt;40 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>Nb total dossiers (IMC &gt;40 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>IMC au d�part (IMC &gt;40 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>IMC � la p�riode (IMC &gt;40 � la 1�re IMC - patients avec consultation)</b></td>
        <td><b>Delta (IMC &gt;40 � la 1�re IMC - patients avec consultation)</b></td>

    </tr>




    <?php

    $req="SELECT dossier_id, poids, dpoids, taille, cabinet from suivi_diabete, dossier where id=dossier_id ".
        "and dpoids>'1990-01-01' and poids>'0' order by dossier_id, dpoids";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $dossier_prec="";
    while(list($dossier_id, $poids, $dpoids, $taille, $cabinet)=mysql_fetch_row($res)){
        if(isset($tab_region[$cabinet])){
            if($taille>0){

                if($dossier_prec!=$dossier_id){
                    $premier_imc[$dossier_id]=$dpoids;
                    $liste_imc[$dossier_id]=array();
                    $dossiers[$cabinet][]=$dossier_id;
                }

                if(!in_array($dpoids, $liste_imc[$dossier_id])){//Un nouveau poids est indiqu� => on regarde le temps par rapport � la 1�re IMC
                    $imc=round($poids/pow($taille/100, 2),1);
                    $liste_imc[$dossier_id][$dpoids]=$imc;
                }
                $dossier_prec=$dossier_id;
            }
        }
    }

    $req="SELECT dossier.id, poids, dpoids, taille, cabinet from cardio_vasculaire_depart, ".
        "dossier where cardio_vasculaire_depart.id=dossier.id ".
        "and dpoids>'1990-01-01' and poids>'0' order by dossier.id, dpoids";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($dossier_id, $poids, $dpoids, $taille, $cabinet)=mysql_fetch_row($res)){
        if(isset($tab_region[$cabinet])){
            if($taille>0){

                if(!isset($premier_imc[$dossier_id])){
                    $premier_imc[$dossier_id]=$dpoids;
                    $liste_imc[$dossier_id]=array();
                }

                if(!in_array($dpoids, $liste_imc[$dossier_id])){//Un nouveau poids est indiqu� => on regarde le temps par rapport � la 1�re IMC
                    $imc=round($poids/pow($taille/100, 2),1);
                    $liste_imc[$dossier_id][$dpoids]=$imc;
                }
            }
        }
    }


    foreach($liste_imc as $dossier_id=>$tab){
        ksort($tab);

        $i=0;

        foreach($tab as $date=>$imc){
            if($i==0){
                $premier=$date;
                $imc00=$imc;
            }
            else{
                $nb_jours = round((strtotime($date) - strtotime($premier))/(60*60*24));
                $nb_mois=ceil($nb_jours/30);

                if($nb_mois==118){
                    echo $dossier_id;
                }

                if(!isset($nb_dossiers["tot"][$nb_mois])){
                    $nb_dossiers["tot"][$nb_mois]=0;
                    $imc_moy["tot"][$nb_mois]=0;
                    $imc0["tot"][$nb_mois]=0;
                }

                $nb_dossiers["tot"][$nb_mois]=$nb_dossiers["tot"][$nb_mois]+1;
                $imc0["tot"][$nb_mois]=$imc0["tot"][$nb_mois]+$imc00;
                $imc_moy["tot"][$nb_mois]=$imc_moy["tot"][$nb_mois]+$imc;
            }

            if($imc00>30){
                if($i==0){
                    $premier30=$date;
                    $imc0030=$imc;
                }
                else{
                    $nb_jours = round((strtotime($date) - strtotime($premier))/(60*60*24));
                    $nb_mois=ceil($nb_jours/30);

                    if(!isset($nb_dossiers30["tot"][$nb_mois])){
                        $nb_dossiers30["tot"][$nb_mois]=0;
                        $imc_moy30["tot"][$nb_mois]=0;
                        $imc30["tot"][$nb_mois]=0;
                    }

                    $nb_dossiers30["tot"][$nb_mois]=$nb_dossiers30["tot"][$nb_mois]+1;
                    $imc30["tot"][$nb_mois]=$imc30["tot"][$nb_mois]+$imc0030;
                    $imc_moy30["tot"][$nb_mois]=$imc_moy30["tot"][$nb_mois]+$imc;
                }

            }

            if(($imc00>=25)&&($imc00<30)){
                if($i==0){
                    $premier2530=$date;
                    $imc002530=$imc;
                }
                else{
                    $nb_jours = round((strtotime($date) - strtotime($premier))/(60*60*24));
                    $nb_mois=ceil($nb_jours/30);

                    if(!isset($nb_dossiers2530["tot"][$nb_mois])){
                        $nb_dossiers2530["tot"][$nb_mois]=0;
                        $imc_moy2530["tot"][$nb_mois]=0;
                        $imc2530["tot"][$nb_mois]=0;
                    }

                    $nb_dossiers2530["tot"][$nb_mois]=$nb_dossiers2530["tot"][$nb_mois]+1;
                    $imc2530["tot"][$nb_mois]=$imc2530["tot"][$nb_mois]+$imc002530;
                    $imc_moy2530["tot"][$nb_mois]=$imc_moy2530["tot"][$nb_mois]+$imc;
                }

            }

            if(($imc00>=30)&&($imc00<40)){
                if($i==0){
                    $premier3040=$date;
                    $imc003040=$imc;
                }
                else{
                    $nb_jours = round((strtotime($date) - strtotime($premier))/(60*60*24));
                    $nb_mois=ceil($nb_jours/30);

                    if(!isset($nb_dossiers3040["tot"][$nb_mois])){
                        $nb_dossiers3040["tot"][$nb_mois]=0;
                        $imc_moy3040["tot"][$nb_mois]=0;
                        $imc3040["tot"][$nb_mois]=0;
                    }

                    $nb_dossiers3040["tot"][$nb_mois]=$nb_dossiers3040["tot"][$nb_mois]+1;
                    $imc3040["tot"][$nb_mois]=$imc3040["tot"][$nb_mois]+$imc003040;
                    $imc_moy3040["tot"][$nb_mois]=$imc_moy3040["tot"][$nb_mois]+$imc;
                }

            }

            if($imc00>40){
                if($i==0){
                    $premier40=$date;
                    $imc0040=$imc;
                }
                else{
                    $nb_jours = round((strtotime($date) - strtotime($premier))/(60*60*24));
                    $nb_mois=ceil($nb_jours/30);

                    if(!isset($nb_dossiers40["tot"][$nb_mois])){
                        $nb_dossiers40["tot"][$nb_mois]=0;
                        $imc_moy40["tot"][$nb_mois]=0;
                        $imc40["tot"][$nb_mois]=0;
                    }

                    $nb_dossiers40["tot"][$nb_mois]=$nb_dossiers40["tot"][$nb_mois]+1;
                    $imc40["tot"][$nb_mois]=$imc40["tot"][$nb_mois]+$imc0040;
                    $imc_moy40["tot"][$nb_mois]=$imc_moy40["tot"][$nb_mois]+$imc;
                }

            }

            $req2="SELECT id from evaluation_infirmier where id='$dossier_id'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            $nb_consult1=mysql_num_rows($res2);

            $req2="SELECT id from cardio_premiere_consult where id='$dossier_id'";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            $nb_consult2=mysql_num_rows($res2);

            if(($nb_consult1>0)||($nb_consult2>0)){
                $nb_jours = round((strtotime($date) - strtotime($premier))/(60*60*24));
                $nb_mois=ceil($nb_jours/30);

                if($nb_mois==118){
                    echo $dossier_id;
                }

                if(!isset($nb_dossiers_consult["tot"][$nb_mois])){
                    $nb_dossiers_consult["tot"][$nb_mois]=0;
                    $imc_moy_consult["tot"][$nb_mois]=0;
                    $imc0_consult["tot"][$nb_mois]=0;
                }

                $nb_dossiers_consult["tot"][$nb_mois]=$nb_dossiers_consult["tot"][$nb_mois]+1;
                $imc0_consult["tot"][$nb_mois]=$imc0_consult["tot"][$nb_mois]+$imc00;
                $imc_moy_consult["tot"][$nb_mois]=$imc_moy_consult["tot"][$nb_mois]+$imc;

                if($imc00>30){
                    if($i==0){
                        $premier30=$date;
                        $imc0030=$imc;
                    }
                    else{
                        $nb_jours = round((strtotime($date) - strtotime($premier))/(60*60*24));
                        $nb_mois=ceil($nb_jours/30);

                        if(!isset($nb_dossiers30_consult["tot"][$nb_mois])){
                            $nb_dossiers30_consult["tot"][$nb_mois]=0;
                            $imc_moy30_consult["tot"][$nb_mois]=0;
                            $imc30_consult["tot"][$nb_mois]=0;
                        }

                        $nb_dossiers30_consult["tot"][$nb_mois]=$nb_dossiers30_consult["tot"][$nb_mois]+1;
                        $imc30_consult["tot"][$nb_mois]=$imc30_consult["tot"][$nb_mois]+$imc0030;
                        $imc_moy30_consult["tot"][$nb_mois]=$imc_moy30_consult["tot"][$nb_mois]+$imc;
                    }

                }

                if(($imc00>=25)&&($imc00<30)){
                    if($i==0){
                        $premier2530=$date;
                        $imc002530=$imc;
                    }
                    else{
                        $nb_jours = round((strtotime($date) - strtotime($premier))/(60*60*24));
                        $nb_mois=ceil($nb_jours/30);

                        if(!isset($nb_dossiers2530_consult["tot"][$nb_mois])){
                            $nb_dossiers2530_consult["tot"][$nb_mois]=0;
                            $imc_moy2530_consult["tot"][$nb_mois]=0;
                            $imc2530_consult["tot"][$nb_mois]=0;
                        }

                        $nb_dossiers2530_consult["tot"][$nb_mois]=$nb_dossiers2530_consult["tot"][$nb_mois]+1;
                        $imc2530_consult["tot"][$nb_mois]=$imc2530_consult["tot"][$nb_mois]+$imc002530;
                        $imc_moy2530_consult["tot"][$nb_mois]=$imc_moy2530_consult["tot"][$nb_mois]+$imc;
                    }

                }

                if(($imc00>=30)&&($imc00<40)){
                    if($i==0){
                        $premier3040=$date;
                        $imc003040=$imc;
                    }
                    else{
                        $nb_jours = round((strtotime($date) - strtotime($premier))/(60*60*24));
                        $nb_mois=ceil($nb_jours/30);

                        if(!isset($nb_dossiers3040_consult["tot"][$nb_mois])){
                            $nb_dossiers3040_consult["tot"][$nb_mois]=0;
                            $imc_moy3040_consult["tot"][$nb_mois]=0;
                            $imc3040_consult["tot"][$nb_mois]=0;
                        }

                        $nb_dossiers3040_consult["tot"][$nb_mois]=$nb_dossiers3040_consult["tot"][$nb_mois]+1;
                        $imc3040_consult["tot"][$nb_mois]=$imc3040_consult["tot"][$nb_mois]+$imc003040;
                        $imc_moy3040_consult["tot"][$nb_mois]=$imc_moy3040_consult["tot"][$nb_mois]+$imc;
                    }

                }

                if($imc00>40){
                    if($i==0){
                        $premier40=$date;
                        $imc0040=$imc;
                    }
                    else{
                        $nb_jours = round((strtotime($date) - strtotime($premier))/(60*60*24));
                        $nb_mois=ceil($nb_jours/30);

                        if(!isset($nb_dossiers40_consult["tot"][$nb_mois])){
                            $nb_dossiers40_consult["tot"][$nb_mois]=0;
                            $imc_moy40_consult["tot"][$nb_mois]=0;
                            $imc40_consult["tot"][$nb_mois]=0;
                        }

                        $nb_dossiers40_consult["tot"][$nb_mois]=$nb_dossiers40_consult["tot"][$nb_mois]+1;
                        $imc40_consult["tot"][$nb_mois]=$imc40_consult["tot"][$nb_mois]+$imc0040;
                        $imc_moy40_consult["tot"][$nb_mois]=$imc_moy40_consult["tot"][$nb_mois]+$imc;
                    }

                }
            }
            $i++;
        }
    }

    ksort($nb_dossiers["tot"]);

    foreach($nb_dossiers["tot"] as $nb_mois=>$nb_doss){
        $debut_periode=($nb_mois-1)*30;
        $fin_periode=($nb_mois)*30;
        echo "<tr><td nowrap>Nb dossiers avec un poids et IMC calculable entre $debut_periode et $fin_periode jours <br>($nb_mois mois) apr�s le 1er poids</td>".
            "<td align='right'>".$nb_doss."</td>";

        $imc=round($imc0["tot"][$nb_mois]/$nb_doss, 1);
        echo "<td align='right'>".number_format($imc, 1, ",", "")."</td>";

        $imc1=round($imc_moy["tot"][$nb_mois]/$nb_doss, 1);
        echo "<td align='right'>".number_format($imc1, 1, ",", "")."</td>";

        $delta=round($imc1-$imc, 1);
        echo "<td align='right'>".number_format($delta, 1, ",", "")."</td>";

        if(!isset($nb_dossiers30["tot"][$nb_mois])){
            echo "<td align='right'>0</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".$nb_dossiers30["tot"][$nb_mois]."</td>";
            $imc=round($imc30["tot"][$nb_mois]/$nb_dossiers30["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc, 1, ",", "")."</td>";

            $imc1=round($imc_moy30["tot"][$nb_mois]/$nb_dossiers30["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc1, 1, ",", "")."</td>";

            $delta=round($imc1-$imc, 1);
            echo "<td align='right'>".number_format($delta, 1, ",", "")."</td>";
        }

        if(!isset($nb_dossiers2530["tot"][$nb_mois])){
            echo "<td align='right'>0</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".$nb_dossiers2530["tot"][$nb_mois]."</td>";
            $imc=round($imc2530["tot"][$nb_mois]/$nb_dossiers2530["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc, 1, ",", "")."</td>";

            $imc1=round($imc_moy2530["tot"][$nb_mois]/$nb_dossiers2530["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc1, 1, ",", "")."</td>";

            $delta=round($imc1-$imc, 1);
            echo "<td align='right'>".number_format($delta, 1, ",", "")."</td>";
        }

        if(!isset($nb_dossiers3040["tot"][$nb_mois])){
            echo "<td align='right'>0</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".$nb_dossiers3040["tot"][$nb_mois]."</td>";
            $imc=round($imc3040["tot"][$nb_mois]/$nb_dossiers3040["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc, 1, ",", "")."</td>";

            $imc1=round($imc_moy3040["tot"][$nb_mois]/$nb_dossiers3040["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc1, 1, ",", "")."</td>";

            $delta=round($imc1-$imc, 1);
            echo "<td align='right'>".number_format($delta, 1, ",", "")."</td>";
        }

        if(!isset($nb_dossiers40["tot"][$nb_mois])){
            echo "<td align='right'>0</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".$nb_dossiers40["tot"][$nb_mois]."</td>";
            $imc=round($imc40["tot"][$nb_mois]/$nb_dossiers40["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc, 1, ",", "")."</td>";

            $imc1=round($imc_moy40["tot"][$nb_mois]/$nb_dossiers40["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc1, 1, ",", "")."</td>";

            $delta=round($imc1-$imc, 1);
            echo "<td align='right'>".number_format($delta, 1, ",", "")."</td>";
        }

        if(!isset($nb_dossiers_consult["tot"][$nb_mois])){
            echo "<td align='right'>0</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".$nb_dossiers_consult["tot"][$nb_mois]."</td>";

            $imc=round($imc0_consult["tot"][$nb_mois]/$nb_dossiers_consult["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc, 1, ",", "")."</td>";

            $imc1=round($imc_moy_consult["tot"][$nb_mois]/$nb_dossiers_consult["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc1, 1, ",", "")."</td>";

            $delta=round($imc1-$imc, 1);
            echo "<td align='right'>".number_format($delta, 1, ",", "")."</td>";
        }

        if(!isset($nb_dossiers30_consult["tot"][$nb_mois])){
            echo "<td align='right'>0</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".$nb_dossiers30_consult["tot"][$nb_mois]."</td>";
            $imc=round($imc30_consult["tot"][$nb_mois]/$nb_dossiers30_consult["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc, 1, ",", "")."</td>";

            $imc1=round($imc_moy30_consult["tot"][$nb_mois]/$nb_dossiers30_consult["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc1, 1, ",", "")."</td>";

            $delta=round($imc1-$imc, 1);
            echo "<td align='right'>".number_format($delta, 1, ",", "")."</td>";
        }

        if(!isset($nb_dossiers2530_consult["tot"][$nb_mois])){
            echo "<td align='right'>0</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".$nb_dossiers2530_consult["tot"][$nb_mois]."</td>";
            $imc=round($imc2530_consult["tot"][$nb_mois]/$nb_dossiers2530_consult["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc, 1, ",", "")."</td>";

            $imc1=round($imc_moy2530_consult["tot"][$nb_mois]/$nb_dossiers2530_consult["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc1, 1, ",", "")."</td>";

            $delta=round($imc1-$imc, 1);
            echo "<td align='right'>".number_format($delta, 1, ",", "")."</td>";
        }

        if(!isset($nb_dossiers3040_consult["tot"][$nb_mois])){
            echo "<td align='right'>0</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".$nb_dossiers3040_consult["tot"][$nb_mois]."</td>";
            $imc=round($imc3040_consult["tot"][$nb_mois]/$nb_dossiers3040_consult["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc, 1, ",", "")."</td>";

            $imc1=round($imc_moy3040_consult["tot"][$nb_mois]/$nb_dossiers3040_consult["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc1, 1, ",", "")."</td>";

            $delta=round($imc1-$imc, 1);
            echo "<td align='right'>".number_format($delta, 1, ",", "")."</td>";
        }

        if(!isset($nb_dossiers40_consult["tot"][$nb_mois])){
            echo "<td align='right'>0</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>".
                "<td align='right'>ND</td>";
        }
        else{
            echo "<td align='right'>".$nb_dossiers40_consult["tot"][$nb_mois]."</td>";
            $imc=round($imc40_consult["tot"][$nb_mois]/$nb_dossiers40_consult["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc, 1, ",", "")."</td>";

            $imc1=round($imc_moy40_consult["tot"][$nb_mois]/$nb_dossiers40_consult["tot"][$nb_mois], 1);
            echo "<td align='right'>".number_format($imc1, 1, ",", "")."</td>";

            $delta=round($imc1-$imc, 1);
            echo "<td align='right'>".number_format($delta, 1, ",", "")."</td>";
        }

        echo "</tr>";
    }

    }


    function get_imc($poids, $taille){
        if(($taille==0)||($taille=='')||($taille=="NULL")){
            return 'ND';
        }

        return $poids/($taille*$taille/10000);
    }


    ?>
</body>
</html>
