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

set_time_limit(120);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type"
          content="text/html; charset=ISO-8859-15">
    <title>Mise à jour des consult cognitif</title>
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


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("./global/entete.php");
//echo $loc;
require_once "../stats/writeexcel/class.writeexcel_workbookbig.inc.php";
require_once "../stats/writeexcel/class.writeexcel_worksheet.inc.php";

entete_asalee("Mise à jour des consult cognitif");
?>
<!--<table cellpadding="2" cellspacing="2" border="0"
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
<font face='times new roman'>Indicateurs d'évaluation Asalée : nombre de patients vus en consultation</font></i>";
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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_tot;

    $req="SELECT dossier.id, date from trouble_cognitif, dossier, account where dossier.id=trouble_cognitif.id ".
        "and dossier.cabinet=account.cabinet and infirmiere!='' and region!='' ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $i=0;
    while(list($id, $date) = mysql_fetch_row($res)) {
        $req2="SELECT id, DATEDIFF(date, '$date') from evaluation_infirmier where id='$id' ".
            "and DATEDIFF(date, '$date') <1 ".
            "and DATEDIFF(date, '$date') >-1 ";//and type_consultation like '%cognitif%' ";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

        if(mysql_num_rows($res2)==0){
            $i++;

            $req2="INSERT INTO evaluation_infirmier SET id='$id', ".
                "date='$date', type_consultation='cognitif' ";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

        }

    }

    echo $i."fin";
}

?>
</body>
</html>
