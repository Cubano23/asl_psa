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
    <title>Nombre d'examens du HBA1c réalisés lors des 12 derniers mois</title>
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





    ?>
    <table border='1'>
        <tr>
            <th>Cabinet</th>
            <th>dossier_id</th>
            <th>numero</th>
            <th>date entrée</th>
            <th>Date 1er HBA</Th>
            <th>Res 1er HBA</th>
            <th>Date HBA 10 mois après</th>
            <th>Res HBA 10 mois après</th>
        </tr>
        <?php

        $req="SELECT cabinet, dossier_id, numero, dsuivi, dHBA, ResHBA FROM suivi_diabete, dossier ".
            "WHERE dossier_id=id and actif='oui' and dsuivi<='2006-12-31' ORDER BY cabinet, dossier_id, dsuivi";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $id_prec="";
        while(list($cabinet, $dossier_id, $numero, $dsuivi, $dHBA, $ResHBA)=mysql_fetch_row($res)){
            if($id_prec!=$dossier_id){
                $id_prec=$dossier_id;

                if($dsuivi>='2006-01-01'){

                    $req2="SELECT min(dHBA) FROM suivi_diabete WHERE dossier_id='$dossier_id' AND dHBA>'0000-00-00'";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    list($dHBA)=mysql_fetch_row($res2);

                    $req2="SELECT ResHBA FROM suivi_diabete WHERE dossier_id='$dossier_id' AND dHBA='$dHBA' ";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    list($ResHBA)=mysql_fetch_row($res2);

                    if($dHBA>$dsuivi){
                        $dHBA=$ResHBA="";
                    }

                    $req2="SELECT dsuivi, dHBA, ResHBA FROM suivi_diabete WHERE dossier_id='$dossier_id' ".
                        "AND DATE_SUB(dHBA, INTERVAL 10 MONTH) >= '$dsuivi' ORDER BY dHBA";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");


                    if(mysql_num_rows($res2)>0){
                        list($dsuivi2, $dHBA2, $ResHBA2)=mysql_fetch_row($res2);
                    }
                    else{
                        $dsuivi2=$dHBA2=$ResHBA2="";
                    }


                    echo "<tr><td>$cabinet</td>
						<td>$dossier_id</td>
							<td>$numero</td>
								<td>$dsuivi</td>
									<td>$dHBA</Td>
										<td>$ResHBA</Td>
											<td>$dHBA2</Td>
												<td>$ResHBA2</td></tr>";
                }
            }
        }
        ?>
    </table>
    <br><br>
    <?php

}


?>
</body>
</html>
