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
    <title>Nombre de patients ayant eu un HBA1c durant les 4 premiers mois</title>
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

$titre="Nombre de patients ayant eu un HBA1c durant les 4 premiers mois";


entete_asalee($titre);
//echo $loc;
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
<font face='times new roman'>Tenue des examens HBA1c sur la période";
if(isset($_GET['cabinet']))
{
    $req="SELECT nom_cab FROM account where cabinet='".$_GET['cabinet']."'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    list($nom_cab)=mysql_fetch_row($res);
    echo ' pour le cabinet '.$nom_cab;
}
echo "</font></i>";
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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet;


    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */
    /*
    $req="SELECT account.cabinet, count(*), nom_cab ".
             "FROM dossier, account ".
             "WHERE account.cabinet!='zTest' and account.cabinet!='irdes'  ".
             "AND dossier.cabinet=account.cabinet ".
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


    while(list($cab, $pat, $ville) = mysql_fetch_row($res)) {
         $tcabinet[] = $cab;
         $liste_patient[$cab]=array();
         $liste_patient_avant[$cab]=array();
         $liste_patient_avant1[$cab]=array();
         $liste_patient9[$cab]=array();
         $liste_patient18[$cab]=array();
         $tville[]=$ville;
    //	 $tpat[$cab] = $pat;
    }
    */
    $cab="Brioux";

    /*$tcabinet[]="Dominault";
    $tcabinet[]="Lucquin";
    $tcabinet[]="Niort";
    $tcabinet[]="Paquereau";
    $tcabinet[]="Brioux";
    $tcabinet[]="Chizé";
    */
    /*foreach($tcabinet as $cab)
    {*/
    $liste_patient=array();
//}

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    $tab_arrivee=array("claudie"=>'2004-03-01', 'marie-helene'=>'2004-09-27', 'marie-claire'=>'2004-11-02', 'karine'=>'2006-02-06',
        'karine_arg'=>'2006-03-06', 'karine_bouille'=>'2006-10-31',
        'christelle'=>'2006-05-01', 'brigitte'=>'2006-07-01', 'magali'=>'2006-09-10', "claudie_tallud"=>'2005-04-01',
        "claudie_dom"=>"2005-04-01", "marie-helene_paq"=>"2005-06-01", "marie-claire_chiz"=>"2005-04-01");

    $tab_cab_inf=array("Chatillon"=>'claudie', "Dominault"=>"claudie_dom", "Lucquin"=>"claudie_tallud", "Niort"=>"marie-helene",
        "Paquereau"=>"marie-helene_paq", "Brioux"=>"marie-claire", "Chizé"=>"marie-claire_chiz", "Argenton"=>"karine_arg",
        "Saint-Varent"=>"karine", "La-Mothe"=>"christelle", "Lezay"=>"christelle", "Lezay2"=>"christelle",
        "Frontenay"=>"brigitte", "Mauzé"=>'brigitte', "Bessines"=>"brigitte", "Couture"=>"magali",
        "Chef-boutonne1"=>"magali", "Chef-boutonne2"=>"magali", "Bouille"=>"karine_bouille");

//echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php


        $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

        list($annee0, $mois0, $jour)=explode("-", $date_arrivee);

        $mois=$mois0+1;

        if($mois>12)
        {
            $mois=$mois-12;
            $annee=$annee0+1;
        }
        else
        {
            $annee=$annee0;
        }

        if($mois>=10)
        {
            $borne_sup="$annee-$mois-$jour";
        }
        else
        {
            $borne_sup="$annee-0$mois-$jour";
        }

        $mois=$mois0-5;

        if($mois<=0)
        {
            $mois=$mois+12;
            $annee=$annee0-1;
        }
        else
        {
            $annee=$annee0;
        }

        if($mois>=10)
        {
            $borne_inf="$annee-$mois-$jour";
        }
        else
        {
            $borne_inf="$annee-0$mois-$jour";
        }

        $req="SELECT numero, dHBA, ResHBA
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
            "AND cabinet='$cab' ".
            "AND dHBA!='0000-00-00' AND dHBA != 'null' ".
            "AND dHBA>='$borne_inf' AND dHBA<='$borne_sup' ".
            "GROUP BY numero, dHBA ".
            "ORDER BY numero";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($numero, $dHBA, $ResHBA)=mysql_fetch_row($res))
        {
            $liste_patient[]=array("numero"=>$numero, "dHBA"=>$dHBA, "ResHBA"=>$ResHBA);

        }


        foreach($liste_patient as $liste)
        {
            echo "<tr><td>".$liste["numero"]."</td><td>".$liste['dHBA']."</td><td>".$liste['ResHBA']."</td><td>".$borne_inf."</td><td>".$borne_sup."</Td></tr>";
//		$liste_dossier[$cab]=array();
        }
        ?>
    </table>
    <?php

}


?>
</body>
</html>
