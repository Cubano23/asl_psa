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
    <title>Stat patients niort 1</title>
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

entete_asalee("stat patients niort 1");
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


    $req="SELECT id, numero, ADO, sexe, date_format(dnaiss, '%d/%m/%Y'), type, actif ".
        "from dossier, suivi_diabete where cabinet='niort' and ".
        "dossier.id=dossier_id order by dossier_id, dsuivi";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    echo "Liste LDL diab <table border='0'><tr><td>N°</td><td>actif</td><td>date naissance</Td><td>sexe</td><td>Type diabète</td><td>".
        "<td>Date</Td><td>valeur LDL</Td><td>ADO</td></Tr>";
    $id_prec="";
    while(list($id, $numero, $ADO, $sexe, $dnaiss, $type, $actif)=mysql_fetch_row($res)){
        if(($id_prec!=$id)&&($id_prec!='')){
            $req2="SELECT date_exam, resultat1 from liste_exam where id='$id_prec' and type_exam='LDL' ".
                "order by date_exam DESC limit 0, 1";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            list($date_exam, $LDL)=mysql_fetch_row($res2);
            echo "<tr><td>$numero_prec</td><td>$actif_prec</td><td>$dnaiss_prec</Td><td>$sexe_prec</td><td>$type_prec</td><td>".
                "<td>$date_exam</Td><td>$LDL</Td><td>$ADO_prec</td></Tr>";
        }

        $id_prec=$id;
        $numero_prec=$numero;
        $ADO_prec=$ADO;
        $sexe_prec=$sexe;
        $dnaiss_prec=$dnaiss;
        $type_prec=$type;
        $actif_prec=$actif;
    }

    echo "</table><br><br>";

    $req="SELECT id, numero, ADO, sexe, date_format(dnaiss, '%d/%m/%Y'), type, actif ".
        "from dossier, suivi_diabete where cabinet='niort' and ".
        "dossier.id=dossier_id order by dossier_id, dsuivi";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
    echo "Liste HBA diab <table border='0'><tr><td>N°</td><td>actif</td><td>date naissance</Td><td>sexe</td><td>Type diabète</td><td>".
        "<td>Date</Td><td>valeur HBA1c</Td><td>ADO</td></Tr>";
    $id_prec="";
    while(list($id, $numero, $ADO, $sexe, $dnaiss, $type, $actif)=mysql_fetch_row($res)){
        if(($id_prec!=$id)&&($id_prec!='')){
            $req2="SELECT date_exam, resultat1 from liste_exam where id='$id_prec' and type_exam='HBA1c' ".
                "order by date_exam DESC limit 0, 1";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            list($date_exam, $hba)=mysql_fetch_row($res2);
            echo "<tr><td>$numero_prec</td><td>$actif_prec</td><td>$dnaiss_prec</Td><td>$sexe_prec</td><td>$type_prec</td><td>".
                "<td>$date_exam</Td><td>$hba</Td><td>$ADO_prec</td></Tr>";
        }

        $id_prec=$id;
        $numero_prec=$numero;
        $ADO_prec=$ADO;
        $sexe_prec=$sexe;
        $dnaiss_prec=$dnaiss;
        $type_prec=$type;
        $actif_prec=$actif;
    }

    echo "</table><br><br>";


    $req="SELECT dossier.id, numero, traitement, sexe, date_format(dnaiss, '%d/%m/%Y'), actif ".
        "from dossier, cardio_vasculaire_depart where cabinet='niort' and ".
        "dossier.id=cardio_vasculaire_depart.id order by dossier.id, date";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    echo "Liste LDL RCVA <table border='0'><tr><td>N°</td></td>actif</td><td>date naissance</Td><td>sexe</td><td>Type diabète</td><td>".
        "<td>Date</Td><td>valeur LDL</Td></Tr>";
    $id_prec="";
    while(list($id, $numero, $traitement, $sexe, $dnaiss, $actif)=mysql_fetch_row($res)){
        if(($id_prec!=$id)&&($id_prec!='')){
            $req2="SELECT date_exam, resultat1 from liste_exam where id='$id_prec' and type_exam='LDL' ".
                "order by date_exam DESC limit 0, 1";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            list($date_exam, $LDL)=mysql_fetch_row($res2);
            echo "<tr><td>$numero_prec</td><td>$actif_prec</td><td>$dnaiss_prec</Td><td>$sexe_prec</td><td>$type_prec</td><td>".
                "<td>$date_exam</Td><td>$LDL</Td><td>$traitement_prec</td></Tr>";
        }

        $id_prec=$id;
        $numero_prec=$numero;
        $sexe_prec=$sexe;
        $dnaiss_prec=$dnaiss;
        $type_prec=$type;
        $actif_prec=$actif;
        $traitement_prec=$traitement;
    }

    echo "</table><br><br>";

    echo "fin";
}

?>
</body>
</html>
