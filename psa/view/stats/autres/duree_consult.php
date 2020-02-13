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
    <title>Durée des consultations infirmières</title>
</head>
<body bgcolor=#FFE887>
<?php

require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
    die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
    die("Impossible de se connecter à la base");

require("../global/entete.php");

entete_asalee("Durée des consultations infirmières");
?>

<br><br>
<?php

# boucle principale
do {
    $repete=false;

    # fenêtre glissante:
    if (isset($_GET['mois']) && isset($_GET['annee']))
    {
        etape_2($repete);
        exit;
    }

    # étape 1 : identification du patient et de la date
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1:
                etape_1($repete);
                break;

            # étape 2  : saisie des détails
            case 2:
                etape_2($repete);
                break;

            # étape 3  : validation des données et màj base
            case 3:
                etape_3($repete);
                break;
        }
    }
} while($repete);

# fin de traitement principal


function etape_1(&$repete) {
global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;



$req="SELECT dossier.cabinet, count(*), nom_cab ".
    "FROM dossier, account ".
    "WHERE dossier.cabinet!='irdes' and dossier.cabinet!='ergo' and "./*dossier.cabinet!='zTest' and */
    "dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
    "and dossier.cabinet=account.cabinet and region!='' and infirmiere!='' ".
    "GROUP BY nom_cab ".
    "ORDER BY nom_cab ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

if (mysql_num_rows($res)==0) {
    exit ("<p align='center'>Aucun cabinet n'est actif</p>");
}
$tcabinet=array();

$total_pat=0;

$type_consult=array("dep_diab"=>"Dépistage diabète", "suivi_diab1"=>"Suivi du diabète 1ère consultation",
    "suivi_diab2"=>"Suivi du diabète consultation suivante",
    "automesure1"=>"Automesure Tensionnelle 1ère consultation",
    "automesure2"=>"Automesure Tensionnelle consultation suivante",
    "sein"=>"Dépistage cancer du sein",
    "colon"=>"Dépistage cancer du colon", "uterus"=>"Dépistage cancer col de l'utérus",
    "cognitif"=>"Dépistage des troubles cognitfs", "autres"=>"Autres", "mixte"=>"Mixte", "sevrage_tabac"=>"Sevrage Tabagique");

while(list($cab, $pat, $ville) = mysql_fetch_row($res)) {
    $tcabinet[] = $cab;
    $tpat[$cab] = $pat;
    $total_pat+=$pat;
    $tville[$cab]=$ville;
    foreach($type_consult as $type=>$libelle){
        $nb_dossier[$cab][$type]=0;
        $tps_total[$cab][$type]=0;
        $nb_dossier['total'][$type]=0;
        $tps_total['total'][$type]=0;
    }
}

?>
<br>
<br>
<table border=1 width='100%'>
    <tr>
        <td></td><td align="center"><b>Moyenne</b></td>
        <?php
        foreach($tville as $cab) {
            ?>
            <td align='center'><b><?php echo $cab; ?></b></td>
            <?php
        }
        ?>
    </tr>
    <?php


    $req="SELECT cabinet, type_consultation, duree, date, dossier.id FROM `evaluation_infirmier` , dossier ".
        "WHERE dossier.id = evaluation_infirmier.id and cabinet!='irdes' and cabinet!='ergo' ". /*AND cabinet != 'ztest'*/
        "and cabinet!='jgomes' and cabinet!='sbirault' AND duree>0 ORDER BY cabinet";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    while(list($cabinet, $type_consultation, $duree, $date, $id)=mysql_fetch_row($res)){
        if(array_key_exists($type_consultation, $type_consult)){
            $nb_dossier[$cabinet][$type_consultation]=$nb_dossier[$cabinet][$type_consultation]+1;
            $tps_total[$cabinet][$type_consultation]=$tps_total[$cabinet][$type_consultation]+$duree;
            $nb_dossier['total'][$type_consultation]=$nb_dossier['total'][$type_consultation]+1;
            $tps_total['total'][$type_consultation]=$tps_total['total'][$type_consultation]+$duree;
        }
        else{
            if($type_consultation=="suivi_diab"){
                $req2="SELECT * FROM evaluation_infirmier WHERE date<'$date' and id='$id' and type_consultation LIKE '%suivi_diab%'";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                if(mysql_num_rows($res2)>0){
                    $nb_dossier[$cabinet]['suivi_diab2']=$nb_dossier[$cabinet]['suivi_diab2']+1;
                    $tps_total[$cabinet]['suivi_diab2']=$tps_total[$cabinet]['suivi_diab2']+$duree;
                    $nb_dossier['total']['suivi_diab2']=$nb_dossier['total']['suivi_diab2']+1;
                    $tps_total['total']['suivi_diab2']=$tps_total['total']['suivi_diab2']+$duree;
                }
                else{
                    $nb_dossier[$cabinet]['suivi_diab1']=$nb_dossier[$cabinet]['suivi_diab1']+1;
                    $tps_total[$cabinet]['suivi_diab1']=$tps_total[$cabinet]['suivi_diab1']+$duree;
                    $nb_dossier['total']['suivi_diab1']=$nb_dossier['total']['suivi_diab1']+1;
                    $tps_total['total']['suivi_diab1']=$tps_total['total']['suivi_diab1']+$duree;
                }
            }
            elseif($type_consultation=="automesure"){
                $req2="SELECT * FROM evaluation_infirmier WHERE date<'$date' and id='$id' and type_consultation LIKE '%automesure%'";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                if(mysql_num_rows($res2)>0){
                    $nb_dossier[$cabinet]['automesure2']=$nb_dossier[$cabinet]['automesure2']+1;
                    $tps_total[$cabinet]['automesure2']=$tps_total[$cabinet]['automesure2']+$duree;
                    $nb_dossier['total']['automesure2']=$nb_dossier['total']['automesure2']+1;
                    $tps_total['total']['automesure2']=$tps_total['total']['automesure2']+$duree;
                }
                else{
                    $nb_dossier[$cabinet]['automesure1']=$nb_dossier[$cabinet]['automesure1']+1;
                    $tps_total[$cabinet]['automesure1']=$tps_total[$cabinet]['automesure1']+$duree;
                    $nb_dossier['total']['automesure1']=$nb_dossier['total']['automesure1']+1;
                    $tps_total['total']['automesure1']=$tps_total['total']['automesure1']+$duree;
                }
            }
            else{
                $nb_dossier[$cabinet]['mixte']=$nb_dossier[$cabinet]['mixte']+1;
                $tps_total[$cabinet]['mixte']=$tps_total[$cabinet]['mixte']+$duree;
                $nb_dossier['total']['mixte']=$nb_dossier['total']['mixte']+1;
                $tps_total['total']['mixte']=$tps_total['total']['mixte']+$duree;
            }
        }
    }

    foreach($type_consult as $type=>$libelle){
        echo "<tr><td>$libelle</td><td>";
        echo round($tps_total['total'][$type]/$nb_dossier["total"][$type]);
        echo "</td>";

        foreach($tville as $cab=>$ville){
            echo "<td>";
            if($nb_dossier[$cab][$type]==0){
                echo "ND";
            }
            else{
                echo round($tps_total[$cab][$type]/$nb_dossier[$cab][$type]);
            }
            echo "</td>";
        }
        echo "</Tr>";
    }
    echo "</table>";

    }


    ?>
</body>
</html>
