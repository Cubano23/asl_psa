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
    <title>Nombre d'IMC par mois</title>
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


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";
require("../global/entete.php");
//echo $loc;

$titre="Nombre de patients ayant une IMC calculable mois par mois";


entete_asalee($titre);
//echo $loc;
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

$mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
    '11'=>'Novembre', '12'=>'Décembre');

//echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

?>
<br>
<br>

<table border=1>
    <tr>
        <td width='350'></td><td><b>Total</b></td>

        <?php

        foreach($regions as $region){
            echo "	<td align='center'><b>$region</b></td>";
        }

        foreach($tville as $cab) {
            ?>
            <td align='center'><b><?php echo $cab; ?></b></td>
            <?php
        }
        ?>
    </tr>




    <?php

    $req="SELECT dossier_id, poids, dpoids, taille, cabinet from suivi_diabete, dossier where id=dossier_id ".
        "and dpoids>'1990-01-01' order by dossier_id, dpoids";
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

                if(!in_array($dpoids, $liste_imc[$dossier_id])){//Un nouveau poids est indiqué => on regarde le temps par rapport à la 1ère IMC
                    $liste_imc[$dossier_id][]=$dpoids;
                }
                $dossier_prec=$dossier_id;
            }
        }
    }

    $req="SELECT dossier.id, poids, dpoids, taille, cabinet from cardio_vasculaire_depart, ".
        "dossier where cardio_vasculaire_depart.id=dossier.id ".
        "and dpoids>'1990-01-01' order by dossier.id, dpoids";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($dossier_id, $poids, $dpoids, $taille, $cabinet)=mysql_fetch_row($res)){
        if(isset($tab_region[$cabinet])){
            if($taille>0){

                if(!isset($premier_imc[$dossier_id])){
                    $premier_imc[$dossier_id]=$dpoids;
                    $liste_imc[$dossier_id]=array();
                    $dossiers[$cabinet][]=$dossier_id;
                }

                if(!in_array($dpoids, $liste_imc[$dossier_id])){//Un nouveau poids est indiqué => on regarde le temps par rapport à la 1ère IMC
                    $liste_imc[$dossier_id][]=$dpoids;
                }
            }
        }
    }

    foreach($tcabinet as $cab){
        if(!isset($dossiers[$cab])){
            $nb_dossiers[$cabinet][$nb_mois]=0;
        }
        else{
            foreach($dossiers[$cab] as $dossier_id){
                $imc=$liste_imc[$dossier_id];

                sort($imc);

                $premier=$imc[0];

                for($i=1;$i<count($imc);$i++){
                    $nb_jours = round((strtotime($imc[$i]) - strtotime($premier))/(60*60*24));
                    $nb_mois=ceil($nb_jours/30);

                    if(!isset($nb_dossiers[$cab][$nb_mois])){
                        $nb_dossiers[$cab][$nb_mois]=0;
                    }
                    if(!isset($nb_dossiers["tot"][$nb_mois])){
                        $nb_dossiers["tot"][$nb_mois]=0;
                    }
                    if(!isset($nb_dossiers[$tab_region[$cab]][$nb_mois])){
                        $nb_dossiers[$tab_region[$cab]][$nb_mois]=0;
                    }

                    $nb_dossiers[$cab][$nb_mois]=$nb_dossiers[$cab][$nb_mois]+1;
                    $nb_dossiers[$tab_region[$cab]][$nb_mois]=$nb_dossiers[$tab_region[$cab]][$nb_mois]+1;
                    $nb_dossiers["tot"][$nb_mois]=$nb_dossiers["tot"][$nb_mois]+1;
                }


            }
        }

    }

    ksort($nb_dossiers["tot"]);

    foreach($nb_dossiers["tot"] as $nb_mois=>$nb_doss){
        $debut_periode=($nb_mois-1)*30;
        $fin_periode=($nb_mois)*30;
        echo "<tr><td nowrap>Nb dossiers avec un poids et IMC calculable entre $debut_periode et $fin_periode jours <br>($nb_mois mois) après le 1er poids</td>".
            "<td align='right'>".$nb_doss."</td>";

        foreach($regions as $region){
            if(!isset($nb_dossiers[$region][$nb_mois])){
                echo "<td align='right'>0</td>";
            }
            else{
                echo "<td align='right'>".$nb_dossiers[$region][$nb_mois]."</td>";
            }

        }

        foreach($tcabinet as $cab){
            if(!isset($nb_dossiers[$cab][$nb_mois])){
                echo "<td align='right'>0</td>";
            }
            else{
                echo "<td align='right'>".$nb_dossiers[$cab][$nb_mois]."</td>";
            }
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
