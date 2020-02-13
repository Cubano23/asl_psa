<?php
/*	session_start();
if(!isset($_SESSION['nom'])) {
	# pas passé par l'identification
	$debut=dirname($_SERVER['PHP_SELF']);
	$self=basename($_SERVER['PHP_SELF']);
    header("Location: $debut/ident_util.php?url=$self");
    echo "<a href='$debut/ident_util.php?url=$self'>cliquez ici</a>";
	exit;
}
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Nombre d'examens du HBA1c réalisés lors des 12 derniers mois</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");
require_once "../writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "../writeexcel/class.writeexcel_worksheet.inc.php";

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../global/entete.php");
//echo $loc;

entete_asalee("Nombre de HBA1c réalisés lors des 12 derniers mois");

//echo $loc;
?>
<!--
<table cellpadding="2" cellspacing="2" border="0"
 style="text-align: left; width: 100%;">
  <tbody>
    <tr>
      <td style="width: 20%; vertical-align: top;">
      <br>
      <img src="<?php echo $loc; ?>/images/inf79.gif" alt="logo informed79"><br>
        <a href="mailto:contact@asalee.fr"><font size="-1">contact</font></a>
      </td>
      <td style="text-align: center; vertical-align: top;">
	       <span style="font-family: arial; font-weight: bold;">
 <?php
echo "
<i><font face='times new roman' size='32'>Asalée</font><br>
<font face='times new roman'>Indicateurs d'évaluation Asalée taux de suivi des diabétiques</font></i>";
?>
           </span><br>
 <?php if(isset($_SESSION['nom'])) echo '<font size="-1"><i>'.$_SESSION['nom'].'</i></font>'; ?>
      </td>
      <td style="width: 15%; text-align: right; vertical-align: middle;">
      <img src="<?php echo $loc; ?>/images/urml.jpg" alt="logo urml"><br>
      </td>
    </tr>
  </tbody>
</table>
-->
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

$req="SELECT cabinet, nom_cab, infirmiere ".
    "FROM account ".
    "WHERE region!='' and infirmiere!='' ".
    "GROUP BY cabinet ".
    "ORDER BY cabinet ";

$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

$t_diab['tot']=0;

while(list($cab, $ville, $infirmiere) = mysql_fetch_row($res)) {
    $t_diab[$cab]=array();
    $tville[$cab]=$ville;
    $plus3[$cab]=0;
    $moins3[$cab]=0;
    $infirmieres[$cab]=$infirmiere;
}

$req="SELECT dossier.cabinet, count(*) ".
    "FROM dossier, account ".
    "WHERE dossier.cabinet!='zTest'  and dossier.cabinet!='irdes'  and dossier.cabinet!='ergo' and ".
    "dossier.cabinet!='jgomes' ".
    "and dossier.cabinet!='sbirault' and dossier.cabinet!='saint-varent' ".
    "AND actif='oui' and region!='' and infirmiere!='' ".
    "GROUP BY nom_cab ".
    "ORDER BY nom_cab, numero ";
//echo $req;
//die;
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

if (mysql_num_rows($res)==0) {
    exit ("<p align='center'>Aucun cabinet n'est actif</p>");
}
$tcabinet=array();

while(list($cab, $pat) = mysql_fetch_row($res)) {

    if(isset($t_diab[$cab])){
        $tcabinet[] = $cab;
    }
}

$mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
    '11'=>'Novembre', '12'=>'Décembre');

echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";


?>
<br>
<br>
<table border=1 width='100%'>
    <tr>
        <td></td>

        <?php
        foreach($tcabinet as $cab) {
            /*?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
            <?php*/
        }
        ?>
    </tr>

    <?php

    $max_pat=0;
    $plus3tot=0;
    $moins3tot=0;
    $plus3eval=0;
    $moins3eval=0;
    $plus3eval2=0;
    $moins3eval2=0;
    $plus3eval3=0;
    $moins3eval3=0;

    //Patients avec au moins un suivi
    $req="SELECT cabinet, id, numero, date_format(min(dsuivi), '%d/%m/%Y'), max(sortie), count(*) ".
        "FROM suivi_diabete, dossier ".
        "WHERE actif='oui' ".
        "AND suivi_diabete.dossier_id=dossier.id and cabinet='Gerardmer'".
        "GROUP BY cabinet, dossier_id ".
        "ORDER BY cabinet, dossier_id ";
    //echo $req;
    //die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    while(list($cab, $id, $numero, $premier_suivi, $sortie) = mysql_fetch_row($res)) {

        if($sortie!='1'){
            if(isset($t_diab[$cab])){
                //Nombre de HBA1c réalisés sur les 12 derniers mois
                $req="SELECT  dHBA, count(*) ".
                    "FROM suivi_diabete, dossier ".
                    "WHERE actif='oui' ".
                    "AND suivi_diabete.dossier_id=dossier.id ".
                    "and dHBA is not NULL and dHBA>='2010-02-01' and dHBA<='2011-02-01' ".
                    "and id='$id' ".
                    "GROUP BY id, dHBA";
                //echo $req;
                //die;
                $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");



                $nb_hba=mysql_num_rows($res2);

                if(!isset($liste[$nb_hba])){
                    $liste[$nb_hba]=0;
                }
                $liste[$nb_hba]=$liste[$nb_hba]+1;

                $liste_hba[$id]=$nb_hba;
            }
        }
    }

    print_r($liste_hba);echo "<br><BR>";
    foreach($liste as $nb_hba=>$nb){
        echo "$nb_hba :$nb<br>";
    }



    }


    ?>
</body>
</html>
