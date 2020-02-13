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
    <title>Nb HBA1c d'une liste de patients</title>
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

entete_asalee("Nb HBA1c d'une liste de patients");
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

    $liste_patients=array('83',
        '130',
        '189',
        '372',
        '391',
        '400',
        '549',
        '1282',
        '1421',
        '1445',
        '1449',
        '1470',
        '1552',
        '1602',
        '1751',
        '1796',
        '1936',
        '1946',
        '2037',
        '2081',
        '2087',
        '2487',
        '2631',
        '3318',
        '3483',
        '3682',
        '3965',
        '4287',
        '5069',
        '5072',
        '5875',
        '6068',
        '6079',
        '6656',
        '7782',
        '7884',
        '9296',
        '9568',
        '11070',
        '11502',
        '11820',
        '13030',
        '13170',
        '13184',
        '15615',
        '15760',
        '17860',
        '18677',
        '19036',
        '19284',
        '20699',
        '20765',
        '21025',
        '22031',
        '22215',
        '24947',
        '26305',
        '27676');

    $req="PREPARE requete FROM 'SELECT id from dossier WHERE cabinet=\'niort\' ".
        "and numero=?'";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    foreach($liste_patients as $numero){
        $req="SET @numero='$numero'";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $req="EXECUTE requete USING @numero";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        list($id)=mysql_fetch_row($res);

        $id_patient[$id]=$numero;
    }



    $req="PREPARE requete FROM 'SELECT date_exam from liste_exam WHERE id=? ".
        "and date_exam>=\'2010-12-01\' and date_exam<=\'2011-12-01\' and type_exam=\'HBA1c\''";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    echo "<table border='1'><tr><td>id</td><td>numero</td><td>nb HBA1c</td></tr>";
    foreach($id_patient as $id=>$numero){
        $req="SET @id='$id'";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        $req="EXECUTE requete USING @id";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $nb_hba=mysql_num_rows($res);

        echo "<tr><td>$id</td><Td>$numero</td><td>$nb_hba</td></tr>";

    }


    echo "</table><br><br>";

    echo "fin";
}

?>
</body>
</html>
