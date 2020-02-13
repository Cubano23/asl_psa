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
    <title>Evolution de la moyenne IMC pour les patients avec IMC>30</title>
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

$titre="Evolution de la moyenne IMC pour les patients avec IMC>30";


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
$nb_dossiers["tot"]=0;

while(list($cab, $pat, $ville, $region) = mysql_fetch_row($res)) {
    $tcabinet[] = $cab;
    $tville[]=$ville;
    $tab_region[$cab]=$region;
    $dossiers[$cab]=array();
    $nb_dossiers[$cab]=0;

    if(!in_array($region, $regions)){
        $regions[]=$region;
        $dossiers[$region]=array();
        $nb_dossiers[$region]=0;
    }
//	 $tpat[$cab] = $pat;
}

$mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
    '11'=>'Novembre', '12'=>'Décembre');

//echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

?>
<br>
<br>

<table border=1 width='100%'>
    <tr>
        <td></td><td><b>Total</b></td>

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
                $imc=round($poids/pow($taille/100, 2),1);

                if($dossier_prec!=$dossier_id){
                    $premier_imc[$dossier_id]=array("imc"=>$imc, "date"=>$dpoids);
                    $nb_dossiers[$cabinet]=$nb_dossiers[$cabinet]+1;
                    $nb_dossiers[$tab_region[$cabinet]]=$nb_dossiers[$tab_region[$cabinet]]+1;
                    $nb_dossiers["tot"]=$nb_dossiers["tot"]+1;
                }
                $dernier_imc[$dossier_id]=array("imc"=>$imc, "date"=>$dpoids);

                if($dernier_imc[$dossier_id]["date"]!=$premier_imc[$dossier_id]["date"]){
                    if(!in_array($dossier_id, $dossiers[$cabinet])){
                        if($imc>30){
                            $dossiers[$cabinet][]=$dossier_id;
                            $dossiers["tot"][]=$dossier_id;
                            $dossiers[$tab_region[$cabinet]][]=$dossier_id;
                        }
                    }
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
                $imc=round($poids/pow($taille/100, 2),1);

                if(!isset($premier_imc[$dossier_id])){
                    $premier_imc[$dossier_id]=array("imc"=>$imc, "date"=>$dpoids);
                    $nb_dossiers[$cabinet]=$nb_dossiers[$cabinet]+1;
                    $nb_dossiers[$tab_region[$cabinet]]=$nb_dossiers[$tab_region[$cabinet]]+1;
                    $nb_dossiers["tot"]=$nb_dossiers["tot"]+1;
                }
                else{
                    if($dpoids<$premier_imc[$dossier_id]["date"]){
                        $premier_imc[$dossier_id]=array("imc"=>$imc, "date"=>$dpoids);
                    }
                }

                if(!isset($dernier_imc[$dossier_id])){
                    $dernier_imc[$dossier_id]=array("imc"=>$imc, "date"=>$dpoids);
                }
                else{
                    if($dpoids>$dernier_imc[$dossier_id]["date"]){
                        $dernier_imc[$dossier_id]=array("imc"=>$imc, "date"=>$dpoids);
                    }
                }

                if($dernier_imc[$dossier_id]["date"]!=$premier_imc[$dossier_id]["date"]){
                    if(!in_array($dossier_id, $dossiers[$cabinet])){
                        if($imc>30){
                            $dossiers[$cabinet][]=$dossier_id;
                            $dossiers["tot"][]=$dossier_id;
                            $dossiers[$tab_region[$cabinet]][]=$dossier_id;
                        }
                    }
                }
            }
        }
    }

    echo "<tr><td>Nb dossiers total avec un poids et IMC calculable</td><td align='right'>".$nb_dossiers["tot"]."</td>";

    foreach($regions as $region){
        echo "<td align='right'>".$nb_dossiers[$region]."</td>";

    }

    foreach($tcabinet as $cab){
        echo "<td align='right'>".$nb_dossiers[$cab]."</td>";
    }
    echo "</tr>";

    echo "<tr><td>Nb dossiers avec 1ère IMC &gt;30 et au moins 2 poids</td><td align='right'>".count($dossiers["tot"])."</td>";

    foreach($regions as $region){
        echo "<td align='right'>".count($dossiers[$region])."</td>";

    }

    foreach($tcabinet as $cab){
        echo "<td align='right'>".count($dossiers[$cab])."</td>";
    }
    echo "</tr>";

    echo "<tr><td>1ère IMC moyenne pour les dossiers avec IMC &gt;30</td>";

    $totaldeb["tot"]=0;
    $totalfin["tot"]=0;

    foreach($tcabinet as $cab){
        $tab=$dossiers[$cab];
        $totaldeb[$cab]=0;
        $totalfin[$cab]=0;

        if(!isset($totaldeb[$tab_region[$cab]])){
            $totaldeb[$tab_region[$cab]]=0;
            $totalfin[$tab_region[$cab]]=0;
        }

        foreach($tab as $dossier_id){
            $totaldeb[$cab]=$totaldeb[$cab]+$premier_imc[$dossier_id]["imc"];
            $totaldeb[$tab_region[$cab]]=$totaldeb[$tab_region[$cab]]+$premier_imc[$dossier_id]["imc"];
            $totaldeb["tot"]=$totaldeb["tot"]+$premier_imc[$dossier_id]["imc"];
            $totalfin[$cab]=$totalfin[$cab]+$dernier_imc[$dossier_id]["imc"];
            $totalfin[$tab_region[$cab]]=$totalfin[$tab_region[$cab]]+$dernier_imc[$dossier_id]["imc"];
            $totalfin["tot"]=$totalfin["tot"]+$dernier_imc[$dossier_id]["imc"];
        }
    }

    $moyenne1["tot"]=round($totaldeb["tot"]/count($dossiers["tot"]), 1);
    $moyennefin["tot"]=round($totalfin["tot"]/count($dossiers["tot"]), 1);

    foreach($regions as $region){
        if(count($dossiers[$region])==0){
            $moyenne1[$region]=$moyennefin[$region]="ND";
        }
        else{
            $moyenne1[$region]=round($totaldeb[$region]/count($dossiers[$region]), 1);
            $moyennefin[$region]=round($totalfin[$region]/count($dossiers[$region]), 1);
        }
    }

    foreach($tcabinet as $cab){
        if(count($dossiers[$cab])==0){
            $moyenne1[$cab]=$moyennefin[$cab]="ND";
        }
        else{
            $moyenne1[$cab]=round($totaldeb[$cab]/count($dossiers[$cab]), 1);
            $moyennefin[$cab]=round($totalfin[$cab]/count($dossiers[$cab]), 1);
        }
    }

    echo "<td align='right'>".$moyenne1["tot"]."</td>";

    foreach($regions as $region){
        echo "<td align='right'>".$moyenne1[$region]."</td>";

    }

    foreach($tcabinet as $cab){
        echo "<td align='right'>".$moyenne1[$cab]."</td>";
    }
    echo "</tr>";

    echo "<tr><td>Dernière IMC moyenne pour les dossiers avec IMC &gt;30</td>";

    echo "<td align='right'>".$moyennefin["tot"]."</td>";

    foreach($regions as $region){
        echo "<td align='right'>".$moyennefin[$region]."</td>";

    }

    foreach($tcabinet as $cab){
        echo "<td align='right'>".$moyennefin[$cab]."</td>";
    }
    echo "</tr>";

    echo "<tr><td>Nb jours entre la 1ère et la dernière IMC</td>";

    $totaljours["tot"]=0;
    foreach($tcabinet as $cab){
        $tab=$dossiers[$cab];
        $totaljours[$cab]=0;

        if(!isset($totaljours[$tab_region[$cab]])){
            $totaljours[$tab_region[$cab]]=0;
        }

        foreach($tab as $dossier_id){
            $nb_jours = round((strtotime($dernier_imc[$dossier_id]["date"]) - strtotime($premier_imc[$dossier_id]["date"]))/(60*60*24));
            $totaljours[$cab]=$totaljours[$cab]+$nb_jours;
            $totaljours[$tab_region[$cab]]=$totaljours[$tab_region[$cab]]+$nb_jours;
            $totaljours["tot"]=$totaljours["tot"]+$nb_jours;
        }
    }

    $moyennejours["tot"]=round($totaljours["tot"]/count($dossiers["tot"]));

    foreach($regions as $region){
        if(count($dossiers[$region])==0){
            $moyennejours[$region]="ND";
        }
        else{
            $moyennejours[$region]=round($totaljours[$region]/count($dossiers[$region]));
        }
    }

    foreach($tcabinet as $cab){
        if(count($dossiers[$cab])==0){
            $moyennejours[$cab]="ND";
        }
        else{
            $moyennejours[$cab]=round($totaljours[$cab]/count($dossiers[$cab]));
        }
    }

    echo "<td align='right'>".$moyennejours["tot"]."</td>";

    foreach($regions as $region){
        echo "<td align='right'>".$moyennejours[$region]."</td>";

    }

    foreach($tcabinet as $cab){
        echo "<td align='right'>".$moyennejours[$cab]."</td>";
    }
    echo "</tr>";

    echo "<tr><td>Nb mois entre la 1ère et la dernière IMC (1 mois=30jours)</td>";


    echo "<td align='right'>".round($moyennejours["tot"]/30, 1)."</td>";

    foreach($regions as $region){
        echo "<td align='right'>".round($moyennejours[$region]/30, 1)."</td>";

    }

    foreach($tcabinet as $cab){
        echo "<td align='right'>".round($moyennejours[$cab]/30,1)."</td>";
    }
    echo "</tr>";

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
