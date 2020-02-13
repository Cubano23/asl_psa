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

entete_asalee("Traitements des diabétiques");
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


    $req="SELECT cabinet, total_diab2, nom_cab, region ".
        "FROM account ".
        "WHERE region!='' and infirmiere!='' and cabinet!='ztest' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {

        if($region!=""){
            // $t_diab[$cab]=0;

            $tville[$cab]=$ville;

            $regions[$cab]=$region;

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
        if(isset($tcabinet_util[$cab])){
            $tcabinet_util[$cab]=1;
        }
    }
    $req="SELECT cabinet from suivi_diabete, dossier where ".
        "dossier.id=suivi_diabete.dossier_id and dsuivi>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        if(isset($tcabinet_util[$cab])){
            $tcabinet_util[$cab]=1;
        }
    }

    $req="SELECT id, ADO, cabinet ".
        "from dossier, suivi_diabete where dossier.id=dossier_id and actif='oui' ".
        "order by dossier_id, dsuivi DESC";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $id_prec="";


    $id_prec="";
    while(list($id, $ADO, $cabinet)=mysql_fetch_row($res)){
        if((isset($tcabinet_util[$cabinet]))&&($tcabinet_util[$cabinet]==1)){//On est sur un cab à observer
            if($id_prec!=$id){//Nouveau dossier donc on regarde le traitement
                if(!isset($nb[$ADO][$cabinet])){
                    $nb[$ADO][$cabinet]=0;
                }
                if(!isset($nb[$ADO]["total"])){
                    $nb[$ADO]["total"]=0;
                }
                $nb[$ADO][$cabinet]=$nb[$ADO][$cabinet]+1;
                $nb[$ADO]["total"]=$nb[$ADO]["total"]+1;
            }
        }
        $id_prec=$id;
    }

    echo "<table border='1'><tr><td></td><td>Total</td>";


    foreach($tcabinet_util as $cab=>$val){
        if((isset($tville[$cab]))&&($val==1)){
            echo "<td>".$tville[$cab]."</td>";
        }
    }

    foreach($nb as $ADO=>$val){
        echo "<tr><td>$ADO</td>";
        echo "<td>".$nb[$ADO]["total"]."</td>";

        foreach($tcabinet_util as $cab=>$val){
            if($val==1){
                if(!isset($nb[$ADO][$cab])){
                    $nb[$ADO][$cab]=0;
                }
                echo "<td>".$nb[$ADO][$cab]."</td>";
            }
        }
        echo "</tr>";
    }


    echo "fin";
}

?>
</body>
</html>
