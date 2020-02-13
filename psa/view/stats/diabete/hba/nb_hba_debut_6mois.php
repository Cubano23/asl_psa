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
    <title>Evaluation -3 mois + 1 mois</title>
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
require("../../global/entete.php");
//echo $loc;

$titre="Evaluation -3 mois +1 mois au bout 6 mois";


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

    $req="SELECT account.cabinet, count(*), nom_cab ".
        "FROM dossier, account ".
        "WHERE account.cabinet!='zTest' and account.cabinet!='irdes'   and account.cabinet!='ergo' ".
        "and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
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
        $liste_patient_avant1an[$cab]=array();
        $liste_patient_avant2ans[$cab]=array();
        $liste_patient_avant01[$cab]=array();
        $liste_patient9[$cab]=array();
        $liste_patient18[$cab]=array();
        $liste_patient24[$cab]=array();
        $tville[]=$ville;
//	 $tpat[$cab] = $pat;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    $tab_arrivee=array("claudie"=>'2004-06-01', 'marie-helene'=>'2004-09-27', 'marie-claire'=>'2004-11-02', 'karine'=>'2006-02-06',
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

    <?php

    foreach($tcabinet as $cab)
    {

        $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

        list($annee, $mois, $jour)=explode("-", $date_arrivee);

        $mois=$mois+4;

        if($mois>12)
        {
            $mois=$mois-12;
            $annee++;
        }

        if($mois>=10)
        {
            $borne_sup="$annee-$mois-$jour";
        }
        else
        {
            $borne_sup="$annee-0$mois-$jour";
        }

        $req="SELECT dHBA, cabinet, id
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
            "AND cabinet='$cab' ".
            "AND dHBA!='0000-00-00' AND dHBA != 'null' ".
            "AND dHBA>='$date_arrivee' AND dHBA<='$borne_sup' ".
            "GROUP BY dossier_id, dHBA";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($dHBA, $cabinet, $id)=mysql_fetch_row($res))
        {
            if(!isset($liste_patient[$cabinet][$id]))
            {
                $liste_patient[$cabinet][$id]=1;
            }

        }
    }
    ?>
    <table border=1 width='100%'>
        <tr>
            <td></td><td><b>Total</b></td><td><b>Moyenne eval</b></td><td><b>Moyenne cab 2005</b></Td><td><b>Moyenne cab 2006</b></Td>
            <?php
            foreach($tville as $cab) {
                ?>
                <td align='center'><b><?php echo $cab; ?></b></td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td>Pdt 4 1er mois</td>
            <?php
            $total_pat=0;
            $total_eval=0;
            $total_eval2=0;
            $total_eval3=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval=$total_eval+count($liste_patient[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2=$total_eval2+count($liste_patient[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3=$total_eval3+count($liste_patient[$cab]);
                }

            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient[$cab])."</td>";
            }

            ?>
        </tr>

        <?php
        foreach($tcabinet as $cab)
        {

            $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];


            $req="SELECT dHBA, cabinet, id
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                "AND cabinet='$cab' ".
                "AND dHBA!='0000-00-00' AND dHBA != 'null' ".
                "AND dHBA<'$date_arrivee'  ";

            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            while(list($dHBA, $cabinet, $id)=mysql_fetch_row($res))
            {
                if(!isset($liste_patient_avant[$cabinet][$id]))
                {
                    $liste_patient_avant[$cabinet][$id]=1;
                }

            }
        }
        ?>

        <tr>
            <td>Avant arrivée infirmière</td>

            <?php
            $total_pat=0;
            $total_eval=0;
            $total_eval2=0;
            $total_eval3=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient_avant[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval=$total_eval+count($liste_patient_avant[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2=$total_eval2+count($liste_patient[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3=$total_eval3+count($liste_patient[$cab]);
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient_avant[$cab])."</td>";
            }
            ?>
        </tr>
        <?php
        foreach($tcabinet as $cab)
        {

            $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

            list($annee, $mois, $jour)=explode("-", $date_arrivee);

            $mois=$mois+1;

            if($mois>12)
            {
                $mois=$mois-12;
                $annee++;
            }

            if($mois>=10)
            {
                $date_arrivee="$annee-$mois-$jour";
            }
            else
            {
                $date_arrivee="$annee-0$mois-$jour";
            }

            $req="SELECT dHBA, cabinet, id, ResHBA
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                "AND cabinet='$cab' ".
                "AND dHBA!='0000-00-00' AND dHBA != 'null' ".
                "AND dHBA<'$date_arrivee'  ";

            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            while(list($dHBA, $cabinet, $id, $ResHBA)=mysql_fetch_row($res))
            {
                if(!isset($liste_patient_avant01[$cabinet][$id]))
                {
                    $liste_patient_avant01[$cabinet][$id]=$ResHBA;
                }
                else
                {
                    if($ResHBA<$liste_patient_avant01[$cabinet][$id])
                    {
                        $liste_patient_avant01[$cabinet][$id]=$ResHBA;
                    }
                }

            }
        }

        ?>

        <tr>
            <td>Jusqu'à 1 mois après arrivée infirmière</td>

            <?php
            $total_pat=0;
            $total_eval=0;
            $total_eval2=0;
            $total_eval3=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient_avant01[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval=$total_eval+count($liste_patient_avant01[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2=$total_eval2+count($liste_patient_avant01[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3=$total_eval3+count($liste_patient_avant01[$cab]);
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient_avant01[$cab])."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>
        </tr>











        <?php
        foreach($tcabinet as $cab)
        {

            $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

            list($annee, $mois, $jour)=explode("-", $date_arrivee);

            $mois_fin=$mois+1;
            $annee_dep=$annee_fin=$annee;

            if($mois_fin>12)
            {
                $mois_fin=$mois_fin-12;
                $annee_fin++;
            }

            if($mois_fin>=10)
            {
                $date_fin="$annee_fin-$mois_fin-$jour";
            }
            else
            {
                $date_fin="$annee_fin-0$mois_fin-$jour";
            }

            $mois_dep=$mois-3;

            if($mois_dep<=0)
            {
                $mois_dep=$mois_dep+12;
                $annee_dep--;
            }

            if($mois_dep>=10)
            {
                $date_dep="$annee_dep-$mois_dep-$jour";
            }
            else
            {
                $date_dep="$annee_dep-0$mois_dep-$jour";
            }

            $req="SELECT dHBA, cabinet, id, ResHBA
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                "AND cabinet='$cab' ".
                "AND dHBA!='0000-00-00' AND dHBA != 'null' ".
                "AND dHBA<'$date_fin' and dHBA>='$date_dep' ";

            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            while(list($dHBA, $cabinet, $id, $ResHBA)=mysql_fetch_row($res))
            {
                if(!isset($liste_patient_avant1[$cabinet][$id]))
                {
                    $liste_patient_avant1[$cabinet][$id]=$ResHBA;
                }
                else
                {
                    if($ResHBA<$liste_patient_avant1[$cabinet][$id])
                    {
                        $liste_patient_avant1[$cabinet][$id]=$ResHBA;
                    }
                }

            }
        }

        ?>

        <tr>
            <td>entre -3 et +1 mois après arrivée infirmière</td>

            <?php
            $total_pat=0;
            $total_eval=0;
            $total_eval2=0;
            $total_eval3=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient_avant1[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval=$total_eval+count($liste_patient_avant1[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2=$total_eval2+count($liste_patient[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3=$total_eval3+count($liste_patient[$cab]);
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient_avant1[$cab])."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>
        </tr>














        <?php
        foreach($tcabinet as $cab)
        {

            $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

            list($annee, $mois, $jour)=explode("-", $date_arrivee);

            $annee1=$annee+1;

            if($mois<10)
            {
                $date1an="$annee1-0$mois-$jour";
            }
            else
            {
                $date1an="$annee1-$mois-$jour";
            }

            $mois_fin=$mois+2;
            $annee_dep=$annee_fin=$annee;

            if($mois_fin>12)
            {
                $mois_fin=$mois_fin-12;
                $annee_fin++;
            }

            if($mois_fin>=10)
            {
                $date_fin="$annee_fin-$mois_fin-$jour";
            }
            else
            {
                $date_fin="$annee_fin-0$mois_fin-$jour";
            }

            $mois_dep=$mois-2;

            if($mois_dep<=0)
            {
                $mois_dep=$mois_dep+12;
                $annee_dep--;
            }

            if($mois_dep>=10)
            {
                $date_dep="$annee_dep-$mois_dep-$jour";
            }
            else
            {
                $date_dep="$annee_dep-0$mois_dep-$jour";
            }

            $req="SELECT dHBA, cabinet, id, ResHBA
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                "AND cabinet='$cab' ".
                "AND dHBA!='0000-00-00' AND dHBA != 'null' ".
                "AND dHBA<'$date_fin' and dHBA>='$date_dep' ".
                "AND ( (dossier.actif='oui') ".
                "OR (dossier.actif='non' AND dossier.dmaj>'$date1an 00:00:00')) ";

            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            while(list($dHBA, $cabinet, $id, $ResHBA)=mysql_fetch_row($res))
            {
                if(!isset($liste_patient_avant1an[$cabinet][$id]))
                {
                    $liste_patient_avant1an[$cabinet][$id]=$ResHBA;
                }
                else
                {
                    if($ResHBA<$liste_patient_avant1an[$cabinet][$id])
                    {
                        $liste_patient_avant1an[$cabinet][$id]=$ResHBA;
                    }
                }

            }
        }

        ?>

        <tr>
            <td>ID actif 1 an après</td>

            <?php
            $total_pat=0;
            $total_eval=0;
            $total_eval2=0;
            $total_eval3=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient_avant1an[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval=$total_eval+count($liste_patient_avant1an[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2=$total_eval2+count($liste_patient[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3=$total_eval3+count($liste_patient[$cab]);
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</Td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient_avant1an[$cab])."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>
        </tr>












        <?php
        foreach($tcabinet as $cab)
        {

            $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

            list($annee, $mois, $jour)=explode("-", $date_arrivee);

            $annee1=$annee+2;

            if($mois<10){
                $date2an="$annee1-0$mois-$jour";
            }
            else{
                $date2an="$annee1-$mois-$jour";
            }

            $mois_fin=$mois+2;
            $annee_dep=$annee_fin=$annee;

            if($mois_fin>12)
            {
                $mois_fin=$mois_fin-12;
                $annee_fin++;
            }

            if($mois_fin>=10)
            {
                $date_fin="$annee_fin-$mois_fin-$jour";
            }
            else
            {
                $date_fin="$annee_fin-0$mois_fin-$jour";
            }

            $mois_dep=$mois-2;

            if($mois_dep<=0)
            {
                $mois_dep=$mois_dep+12;
                $annee_dep--;
            }

            if($mois_dep>=10)
            {
                $date_dep="$annee_dep-$mois_dep-$jour";
            }
            else
            {
                $date_dep="$annee_dep-0$mois_dep-$jour";
            }

            $req="SELECT dHBA, cabinet, id, ResHBA
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                "AND cabinet='$cab' ".
                "AND dHBA!='0000-00-00' AND dHBA != 'null' ".
                "AND dHBA<'$date_fin' and dHBA>='$date_dep' ".
                "AND ( (dossier.actif='oui') ".
                "OR (dossier.actif='non' AND dossier.dmaj>'$date2an 00:00:00' )) ";

            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            while(list($dHBA, $cabinet, $id, $ResHBA)=mysql_fetch_row($res))
            {
                if(!isset($liste_patient_avant2ans[$cabinet][$id]))
                {
                    $liste_patient_avant2ans[$cabinet][$id]=$ResHBA;
                }
                else
                {
                    if($ResHBA<$liste_patient_avant2ans[$cabinet][$id])
                    {
                        $liste_patient_avant2ans[$cabinet][$id]=$ResHBA;
                    }
                }

            }
        }

        ?>

        <tr>
            <td>ID actif 2 ans après</td>

            <?php
            $total_pat=0;
            $total_eval=0;
            $total_eval2=0;
            $total_eval3=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient_avant2ans[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval=$total_eval+count($liste_patient_avant2ans[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2=$total_eval2+count($liste_patient[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3=$total_eval3+count($liste_patient[$cab]);
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</Td><td>$total_eval2</Td><td>$total_eval3</Td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient_avant2ans[$cab])."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>
        </tr>






















        <tr>
            <td>ID avec HBA1c entre 6.5 et 8</td>

            <?php
            $total_pat6=0;
            $total_eval6=0;
            $total_eval2_6=0;
            $total_eval3_6=0;

            foreach($tcabinet as $cab)
            {
                foreach($liste_patient_avant1[$cab] as $id=>$res)
                {
                    if(($res<=8) &&($res>6.5)){
                        $total_pat6=$total_pat6+1;

                        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                        {
                            $total_eval6=$total_eval6+1;
                        }
                        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                            (strcasecmp($cab, "chizé")==0))
                        {
                            $total_eval2_6=$total_eval2_6+1;
                        }
                        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                        {
                            $total_eval3_6=$total_eval3_6+1;
                        }
                    }
                }
            }

            echo "<td>$total_pat6</td><td>$total_eval6</td><td>$total_eval2_6</td><td>$total_eval3_6</Td>";


            foreach($tcabinet as $cab)
            {
                $total_pat_cab[$cab]=0;
                foreach($liste_patient_avant1[$cab] as $id=>$res)
                {
                    if(($res<=8) &&($res>6.5))
                    {
                        $total_pat_cab[$cab]=$total_pat_cab[$cab]+1;
                        $liste_dossier[$cab][$id]=$res;
                    }
                    if($res>8){
                        $liste_dossier8[$cab][$id]=$res;
                    }
                }
                echo "<td>".$total_pat_cab[$cab]."</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Rapport</td>
            <td><?php echo round(100*$total_pat6/$total_pat);?>%</td>
            <td><?php echo round(100*$total_eval6/$total_eval);?>%</td>
            <td><?php echo round(100*$total_eval2_6/$total_eval2);?>%</td>
            <td><?php echo round(100*$total_eval3_6/$total_eval3);?>%</td>

            <?php
            foreach($tcabinet as $cab)
            {
                if(count($liste_patient_avant1[$cab])==0)
                    $nb="ND";
                else
                    $nb=round(100*$total_pat_cab[$cab]/(count($liste_patient_avant1[$cab])));
                echo "<td>";
                echo $nb;
                echo "%</td>";
            }
            ?>
        </tr>








        <tr>
            <td>ID avec HBA1c >8</td>

            <?php
            $total_pat8=0;
            $total_eval8=0;
            $total_eval2_8=0;
            $total_eval3_8=0;

            foreach($tcabinet as $cab)
            {
                foreach($liste_patient_avant1[$cab] as $id=>$res)
                {
                    if($res>8){
                        $total_pat8=$total_pat8+1;

                        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                        {
                            $total_eval8=$total_eval8+1;
                        }
                        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                            (strcasecmp($cab, "chizé")==0))
                        {
                            $total_eval2_8=$total_eval2_8+1;
                        }
                        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                        {
                            $total_eval3_8=$total_eval3_8+1;
                        }
                    }
                }
            }

            echo "<td>$total_pat8</td><td>$total_eval8</td><td>$total_eval2_8</td><td>$total_eval3_8</Td>";


            foreach($tcabinet as $cab)
            {
                $total_pat_cab8[$cab]=0;
                foreach($liste_patient_avant1[$cab] as $id=>$res)
                {
                    if($res>8)
                    {
                        $total_pat_cab8[$cab]=$total_pat_cab8[$cab]+1;
                        $liste_dossier8[$cab][$id]=$res;
                    }
                    if($res>8){
                        $liste_dossier8[$cab][$id]=$res;
                    }
                }
                echo "<td>".$total_pat_cab8[$cab]."</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Rapport</td>
            <td><?php echo round(100*$total_pat8/$total_pat);?>%</td>
            <td><?php echo round(100*$total_eval8/$total_eval);?>%</Td>
            <td><?php echo round(100*$total_eval2_8/$total_eval2);?>%</Td>
            <td><?php echo round(100*$total_eval3_8/$total_eval3);?>%</Td>

            <?php
            foreach($tcabinet as $cab)
            {
                if(count($liste_patient_avant1[$cab])==0){
                    $nb='ND';
                }
                else
                    $nb=round(100*$total_pat_cab8[$cab]/(count($liste_patient_avant1[$cab])));
                echo "<td>";
                echo $nb;
                echo "%</td>";
            }
            ?>
        </tr>













        <?php

        foreach($tcabinet as $cab)
        {

            $nb_dossier_descendu[$cab]=0;
            $nb_dossier_descendu8[$cab]=0;

            $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

            list($annee, $mois, $jour)=explode("-", $date_arrivee);

            $mois_dep=$mois+5;
            $annee_dep=$annee;

            if($mois_dep>12)
            {
                $mois_dep=$mois_dep-12;
                $annee_dep=$annee+1;
            }

            if($mois_dep>=10)
            {
                $date_dep="$annee_dep-$mois_dep-$jour";
            }
            else
            {
                $date_dep="$annee_dep-0$mois_dep-$jour";
            }

            //14 mois
            $mois_fin=$mois+9;
            $annee_fin=$annee+1;

            if($mois_fin>12)
            {
                $mois_dep=$mois_dep-12;
                $annee_dep++;
            }

            if($mois_fin>=10)
            {
                $date_fin="$annee_fin-$mois_fin-$jour";
            }
            else
            {
                $date_fin="$annee_fin-0$mois_fin-$jour";
            }


            $req="SELECT dHBA, cabinet, id, ResHBA
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                "AND cabinet='$cab' ".
                "AND dHBA!='0000-00-00' AND dHBA != 'null' ".
                "AND dHBA>='$date_dep' AND dHBA<='$date_fin' ";

            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


            while(list($dHBA, $cabinet, $id, $ResHBA)=mysql_fetch_row($res))
            {
                if(!isset($liste_patient9[$cabinet][$id]))
                {
                    $liste_patient9[$cabinet][$id]=$ResHBA;

                    if($ResHBA<=6.5)
                    {
                        if(isset($liste_dossier[$cabinet][$id]))
                        {
                            $nb_dossier_descendu[$cabinet]=$nb_dossier_descendu[$cabinet]+1;
                        }
                    }

                    if(($ResHBA>6.5)&&($ResHBA<=8))
                    {
                        if(isset($liste_dossier8[$cabinet][$id]))
                        {
                            $nb_dossier_descendu8[$cabinet]=$nb_dossier_descendu8[$cabinet]+1;
                        }
                    }
                }
                else{
                    if($liste_patient9[$cabinet][$id]>$ResHBA)
                    {
                        if($liste_patient9[$cabinet][$id]>6.5){
                            if($ResHBA<=6.5)
                            {
                                if(isset($liste_dossier[$cabinet][$id]))
                                {
                                    $nb_dossier_descendu[$cabinet]=$nb_dossier_descendu[$cabinet]+1;
                                }
                            }
                            if(($ResHBA>6.5)&&($ResHBA<=8))
                            {
                                if(isset($liste_dossier8[$cabinet][$id]))
                                {
                                    $nb_dossier_descendu8[$cabinet]=$nb_dossier_descendu8[$cabinet]+1;
                                }
                            }
                        }
                        $liste_patient9[$cabinet][$id]=$ResHBA;
                    }
                }

            }
        }

        ?>
        <tr>
            <td>Entre 4 et 8 mois</td>
            <?php
            $total_pat=0;
            $total_eval=0;
            $total_eval2=0;
            $total_eval3=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient9[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval=$total_eval+count($liste_patient9[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2=$total_eval2+count($liste_patient9[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3=$total_eval3+count($liste_patient9[$cab]);
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient9[$cab])."</td>";
            }

            ?>
        </tr>
        <tr>
            <td>ID avec HBA1c <= 6.5</td>

            <?php
            $total_pat9_6=0;
            $total_eval9_6=0;
            $total_eval29_6=0;
            $total_eval39_6=0;

            foreach($tcabinet as $cab)
            {
                foreach($liste_patient9[$cab] as $id=>$res)
                {
                    if($res<=6.5){
                        $total_pat9_6=$total_pat9_6+1;

                        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                        {
                            $total_eval9_6=$total_eval9_6+1;
                        }
                        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                            (strcasecmp($cab, "chizé")==0))
                        {
                            $total_eval29_6=$total_eval39_6+1;
                        }
                        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                        {
                            $total_eval39_6=$total_eval39_6+1;
                        }
                    }
                }
            }

            echo "<td>$total_pat9_6</td><td>$total_eval9_6</td><td>$total_eval29_6</td><td>$total_eval39_6</td>";


            foreach($tcabinet as $cab)
            {
                $total_pat_cab_9[$cab]=0;
                foreach($liste_patient9[$cab] as $id=>$res)
                {
                    if($res<=6.5)
                    {
                        $total_pat_cab_9[$cab]=$total_pat_cab_9[$cab]+1;
                    }
                }
                echo "<td>".$total_pat_cab_9[$cab]."</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Nombre de dossier avec HBA descendu sous 6.5</Td>

            <?php
            $total_des=0;
            $total_eval_des=0;
            $total_eval2_des=0;
            $total_eval3_des=0;

            foreach($tcabinet as $cab)
            {
                $total_des=$total_des+$nb_dossier_descendu[$cab];

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_des=$total_eval_des+$nb_dossier_descendu[$cab];
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_des=$total_eval2_des+$nb_dossier_descendu[$cab];
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_des=$total_eval3_des+$nb_dossier_descendu[$cab];
                }
            }
            echo "<td>$total_des</td><td>$total_eval_des</td><td>$total_eval2_des</td><td>$total_eval3_des</td>";


            foreach($tcabinet as $cab)
            {
                echo "<td>".$nb_dossier_descendu[$cab]."</td>";
            }



            ?>
        </tr>
        <tr>
            <td>Rapport</td>

            <?php
            echo "<td>".round(100*$total_des/$total_pat6)."%</td>";
            echo "<td>".round(100*$total_eval_des/$total_eval6)."%</td>";
            echo "<td>".round(100*$total_eval2_des/$total_eval2_6)."%</td>";
            echo "<td>".round(100*$total_eval3_des/$total_eval3_6)."%</td>";

            foreach($tcabinet as $cab)
            {
                if($total_pat_cab[$cab]==0)
                {
                    echo "<td>ND</td>";
                }
                else
                    echo "<td>".round(100*$nb_dossier_descendu[$cab]/$total_pat_cab[$cab])."%</td>";
            }
            ?>

        </tr>























        <?php

        foreach($tcabinet as $cab)
        {

            $nb_dossier_descendu[$cab]=0;
            $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

            list($annee, $mois, $jour)=explode("-", $date_arrivee);

            $mois_dep=$mois+10;
            $annee_dep=$annee+1;

            //22 mois
            if($mois_dep>12)
            {
                $mois_dep=$mois_dep-12;
                $annee_dep++;
            }

            if($mois_dep>=10)
            {
                $date_dep="$annee_dep-$mois_dep-$jour";
            }
            else
            {
                $date_dep="$annee_dep-0$mois_dep-$jour";
            }

            //26 mois
            $mois_fin=$mois+2;
            $annee_fin=$annee+2;

            if($mois_fin>12)
            {
                $mois_dep=$mois_dep-12;
                $annee_fin++;
            }

            if($mois_fin>=10)
            {
                $date_fin="$annee_fin-$mois_fin-$jour";
            }
            else
            {
                $date_fin="$annee_fin-0$mois_fin-$jour";
            }


            $req="SELECT dHBA, cabinet, id, ResHBA
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                "AND cabinet='$cab' ".
                "AND dHBA!='0000-00-00' AND dHBA != 'null' ".
                "AND dHBA>='$date_dep' AND dHBA<='$date_fin' ";

            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


            while(list($dHBA, $cabinet, $id, $ResHBA)=mysql_fetch_row($res))
            {
                if(!isset($liste_patient24[$cabinet][$id]))
                {
                    $liste_patient24[$cabinet][$id]=$ResHBA;

                    if($ResHBA<=6.5)
                    {
                        if(isset($liste_dossier[$cabinet][$id]))
                        {
                            $nb_dossier_descendu[$cabinet]=$nb_dossier_descendu[$cabinet]+1;
                        }
                    }
                }
                else{
                    if($liste_patient24[$cabinet][$id]>$ResHBA)
                    {
                        if($liste_patient24[$cabinet][$id]>6.5){
                            if($ResHBA<=6.5)
                            {
                                if(isset($liste_dossier[$cabinet][$id]))
                                {
                                    $nb_dossier_descendu[$cabinet]=$nb_dossier_descendu[$cabinet]+1;
                                }
                            }
                        }
                        $liste_patient24[$cabinet][$id]=$ResHBA;
                    }
                }

            }
        }

        ?>
        <tr>
            <td>Entre 22 et 26 mois</td>
            <?php
            $total_pat=0;
            $total_eval=0;
            $total_eval2=0;
            $total_eval3=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient24[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval=$total_eval+count($liste_patient24[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2=$total_eval2+count($liste_patient24[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3=$total_eval3+count($liste_patient24[$cab]);
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient24[$cab])."</td>";
            }

            ?>
        </tr>
        <tr>
            <td>ID avec HBA1c <= 6.5</td>

            <?php
            $total_pat24_6=0;
            $total_eval24_6=0;
            $total2_eval24_6=0;
            $total3_eval24_6=0;

            foreach($tcabinet as $cab)
            {
                foreach($liste_patient24[$cab] as $id=>$res)
                {
                    if($res<=6.5){
                        $total_pat24_6=$total_pat24_6+1;

                        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                        {
                            $total_eval24_6=$total_eval24_6+1;
                        }
                        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                            (strcasecmp($cab, "chizé")==0))
                        {
                            $total2_eval24_6=$total2_eval24_6+1;
                        }
                        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                        {
                            $total3_eval24_6=$total3_eval24_6+1;
                        }
                    }
                }
            }

            echo "<td>$total_pat24_6</td><td>$total_eval24_6</Td><td>$total2_eval24_6</Td><td>$total3_eval24_6</Td>";


            foreach($tcabinet as $cab)
            {
                $total_pat_cab_24[$cab]=0;
                foreach($liste_patient24[$cab] as $id=>$res)
                {
                    if($res<=6.5)
                    {
                        $total_pat_cab_24[$cab]=$total_pat_cab_24[$cab]+1;
                    }
                }
                echo "<td>".$total_pat_cab_24[$cab]."</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Nombre de dossier avec HBA descendu sous 6.5</Td>

            <?php
            $total_des=0;
            $total_eval_des=0;
            $total_eval_des2=0;
            $total_eval_des3=0;

            foreach($tcabinet as $cab)
            {
                $total_des=$total_des+$nb_dossier_descendu[$cab];

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_des=$total_eval_des+$nb_dossier_descendu[$cab];
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval_des2=$total_eval_des2+$nb_dossier_descendu[$cab];
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval_des3=$total_eval_des3+$nb_dossier_descendu[$cab];
                }
            }
            echo "<td>$total_des</td><td>$total_eval_des</td><td>$total_eval_des2</td><td>$total_eval_des3</td>";


            foreach($tcabinet as $cab)
            {
                echo "<td>".$nb_dossier_descendu[$cab]."</td>";
            }



            ?>
        </tr>
        <tr>
            <td>Rapport</td>

            <?php
            echo "<td>".round(100*$total_des/$total_pat6)."%</td>";
            echo "<td>".round(100*$total_eval_des/$total_eval6)."%</td>";
            echo "<td>".round(100*$total_eval_des2/$total_eval2_6)."%</td>";
            echo "<td>".round(100*$total_eval_des3/$total_eval3_6)."%</td>";

            foreach($tcabinet as $cab)
            {
                if($total_pat_cab[$cab]==0)
                {
                    echo "<td>ND</td>";
                }
                else
                    echo "<td>".round(100*$nb_dossier_descendu[$cab]/$total_pat_cab[$cab])."%</td>";
            }
            ?>

        </tr>















    </table>
    <?php

}


?>
</body>
</html>
