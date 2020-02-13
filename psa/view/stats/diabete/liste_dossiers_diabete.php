<?php
/*	session_start();
if(!isset($_SESSION['nom'])) {
	# pas pass� par l'identification
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
    <title>Nombre d'examens du HBA1c r�alis�s lors des 12 derniers mois</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once "Config.php";
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

entete_asalee("Liste des dossiers diab�te");

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
      ��<a href="mailto:contact@asalee.fr"><font size="-1">contact</font></a>
      </td>
      <td style="text-align: center; vertical-align: top;">
	       <span style="font-family: arial; font-weight: bold;">
 <?php
echo "
<i><font face='times new roman' size='32'>Asal�e</font><br>
<font face='times new roman'>Indicateurs d'�valuation Asal�e taux de suivi des diab�tiques</font></i>";
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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;

    $req="SELECT cabinet, nom_cab ".
        "FROM account ".
        "WHERE region!='' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $t_diab['tot']=0;

    while(list($cab, $ville) = mysql_fetch_row($res)) {
        $t_diab[$cab]=array();
        $tville[$cab]=$ville;
        $plus3[$cab]=0;
        $moins3[$cab]=0;
    }

    $req="SELECT dossier.cabinet, count(*) ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet=account.cabinet and region='Poitou-Charentes - 79' ".
        "AND actif='oui' ".
        "GROUP BY dossier.cabinet ".
        "ORDER BY dossier.cabinet, numero ";
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

    $mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'D�cembre');

    echo '<b>Donn�es � la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";


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
        $req="SELECT cabinet, id, numero, min(dsuivi), max(sortie), max(ado), sexe, dnaiss, count(*) ".
            "FROM suivi_diabete, dossier ".
            "WHERE actif='oui' ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet, dossier_id ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($cab, $id, $numero, $premier_suivi, $sortie, $ado, $sexe, $dnaiss) = mysql_fetch_row($res)) {

            if($sortie!='1'){
                if(isset($t_diab[$cab])){
                    //Nombre de HBA1c r�alis�s sur les 12 derniers mois

                    if($ado==""){
                        $ado=0;
                    }
                    else{
                        $ado=1;
                    }

                    $req2="SELECT * FROM evaluation_infirmier ".
                        "WHERE id='$id' and date>='$premier_suivi'";
                    //echo $req;
                    //die;
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    if(mysql_num_rows($res2)>0){
                        $consult=2;
                    }
                    else{
                        $consult=1;
                    }

                    $t_diab[$cab][]=array("id"=>$id, "numero"=>$numero, "premier_suivi"=>$premier_suivi, "sexe"=>$sexe, "dnaiss"=>$dnaiss, "ado"=>$ado,
                        "consult"=>$consult);

                }
            }




        }





        ?>
        <tr>
            <td>Dossiers avec moins 1 suivi diab�te</td></tr>
        <tr><td>Cabinet</td><td>ID</td><td>Num�ro</td><td>Premier suivi</td><td>sexe</Td><td>date naissance</Td><td>ADO</Td><td>Consult</td></Tr>

        <?php
        foreach($tcabinet as $cab) {


            ?>
            <!--	<td valign='top'>--><?php
//				echo "<table border='1'><tr><td>id</td><td>numero</td><td>dHBA</Td><td>ResHBA</td><td>dHBA</Td><td>ResHBA</Td></Tr>";
            foreach($t_diab[$cab] as $dossier){
                echo "<tr><td>".$cab."</td><td>".$dossier['id']."</td><td>".$dossier['numero']."</td>".
                    "<td>".$dossier["premier_suivi"]."</td><td>".$dossier["sexe"]."</td><td>".$dossier["dnaiss"]."</td>".
                    "<td>".$dossier["ado"]."</Td><td>".$dossier["consult"]."</Td>";

                echo "</tr>";
            }
//				echo "</table>";
            /*				?></td>
            //<?php*/
        }
        ?>
        </tr>


    </table>
    <br><br>
    <?php


}


?>
</body>
</html>
