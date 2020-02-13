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
    <title>Statistiques Asalée suivis hebdomadaires</title>
</head>
<body bgcolor=#FFE887>
<?php
error_reporting(E_ERROR); // EA. Les script ne traite pas des valeurs initiales ce qui génère les Notices 29-12-2014

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

entete_asalee("Statistiques : suivi d'activité suivis hebdomadaires");
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
<font face='times new roman'>Statistiques : suivi du trafic suivis hebdomadaires</font></i>";
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
<?

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
        etape_2($repete);
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
    global $message,$Dossier,$Cabinet, $deval, $self;

    echo "<b>Statistiques consolidées ";
    date_default_timezone_set('Europe/Paris');
    for ($i=2013; $i<date('Y'); $i++)
    {
        echo $i.'-';
    }

    echo date('Y')."</b><br>";

    $req="SELECT dossier.cabinet, count(*), nom_cab ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='irdes'  and dossier.cabinet!='ergo' and "./*dossier.cabinet!='zTest' and*/
        "dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
        "and dossier.cabinet=account.cabinet ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();

    $total_pat=0;

    while(list($cab, $pat, $ville) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tpat[$cab] = $pat;
        $total_pat+=$pat;
        $tville[$cab]=$ville;
    }

    ?>
    <br>
    <br>
    <table border=1 width='100%'>
        <tr>
            <td></td><td align="center"><b>total</b></td>
            <?php
            foreach($tville as $cab) {
                ?>
                <td align='center'><b><?php echo $cab; ?></b></td>
                <?php
            }
            ?>
        </tr>


        <?php


        // $req="SELECT cabinet, COUNT(*)
        // 	 FROM suivi_hebdomadaire as dep
        // 	 WHERE cabinet !='zTest' and cabinet!='irdes' and cabinet!='ergo' and cabinet!='jgomes'
        // 	 and cabinet!='sbirault'
        // 	 GROUP BY cabinet
        // 	 ORDER BY cabinet";

        // 	$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        // 	foreach ($tcabinet as $cab)
        // 	{
        // 	    $tpat_hebdo[$cab]="";
        // 	}

        // $total_hebdo=0;

        // 	while (list($cab_hebdo, $pat_hebdo) = mysql_fetch_row($res))
        // 	{
        // 	    $tcab_hebdo[]=$cab_hebdo;
        // 		$tpat_hebdo[$cab_hebdo]=$pat_hebdo;
        // 		$total_hebdo+=$pat_hebdo;
        // 	}


        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_hebdo_temps_passe as dep
	 WHERE cabinet!='irdes' and cabinet!='ergo' and cabinet!='jgomes'/*cabinet !='zTest' and */
	 and cabinet!='sbirault' 
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while (list($cab_hebdo, $pat_hebdo) = mysql_fetch_row($res))
        {
            $tpat_hebdo[$cab_hebdo]=$tpat_hebdo[$cab_hebdo]+$pat_hebdo;
            $total_hebdo+=$pat_hebdo;
        }
        ?>
        <tr>
            <td>Suivis Hebdomadaires</td><td  align='right'><?php echo $total_hebdo; ?></td>

            <?php

            foreach ($tville as $cab=>$ville)
            {
                ?>
                <td align='right'><?php echo $tpat_hebdo[$cab];?></td>
                <?php
            }
            ?>
        </tr>


    </table>
    <br><br>
    <b></b>
    <table border='0' width='100%'>
        <tr>
            <form action="<?php echo $self; ?>" method="post" name="form">
                <input type="hidden" name="etape" value="2">
                <td><input type="submit" name="submit" size='30' value="Retour aux statistiques mensuelles"></form></td>
        </tr>

    </table>

    <br><br>
    <b>statistiques annuelles</b>
    <table border='0'>
        <tr><?php
            date_default_timezone_set('Europe/Paris');
            for ($i=2013; $i<=date('Y'); $i++)
            {
                ?>

                <form action="<?php echo $self; ?>" method="post" name="form">
                    <input type="hidden" name="etape" value="3">
                    <input type="hidden" name="annee" value="<?php echo $i; ?>">
                    <td><input type="submit" name="submit" size='30' value="<?php echo "Statistiques ".$i;?>"></form></td>
                <?php
            }
            ?>

        </tr>
    </table>

    <?php
}

function etape_2(&$repete) {
    global $message, $Dossier, $Cabinet, $deval, $self, $doc;

    if(isset($_GET['mois']) && isset($_GET['annee']))
    {
        $num_mois=$_GET['mois'];
        $annee=$_GET['annee'];
    }
    elseif(isset($_POST['mois']) && isset($_POST['annee']))
    {
        $num_mois=$_POST['mois'];
        $annee=$_POST['annee'];
    }
    else
    {
        $num_mois=date('n');
        $annee=date('Y');
    }
//print_r($_POST);

    $mois=array(1=>"Janvier", 2=>"Février", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 7=>"Juillet",
        8=>"Août", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"Décembre");
    /*
    if (($num_mois=='8') && ($annee=='2004'))
    {
        echo "<b>Statistiques avant Septembre 2004</b>";
    }
    else*/
    {
        echo "<b>Statistiques pour ".$mois[$num_mois]." ".$annee."</b>";
    }

    /*$req="SELECT cabinet, count(*) ".
             "FROM inf79_patient ".
             "GROUP BY cabinet ".
             "ORDER BY cabinet ";
             */
    $req="SELECT dossier.cabinet, COUNT(*), nom_cab
	 FROM dossier, account
	 WHERE dossier.cabinet!='irdes' and dossier.cabinet!='ergo' and dossier.cabinet!='jgomes'/*cabinet !='zTest' and */
	 and dossier.cabinet=account.cabinet
	 GROUP BY nom_cab
	 ORDER BY nom_cab";


    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();


    while(list($cab, $pat, $ville) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tpat[$cab] = $pat;
        $tville[$cab]=$ville;
    }

    ?>
    <br>
    <br>
    <?php

# boutons pour faire varier le moins des statistiques

    $mois_moins=$num_mois-1;
    $mois_plus=$num_mois+1;
    $annee_moins=$annee_plus=$annee;

    if ($num_mois==1)
    {
        $mois_moins=12;
        $annee_moins=$annee-1;
    }

    if ($num_mois==12)
    {
        $mois_plus=1;
        $annee_plus=$annee+1;
    }

    if (($mois_moins=='2') && ($annee=='2013'))
    {
        echo '<table border=0><tr><td align="right">'.
            '<img src="../img/left.gif" border=0 alt="mois précédents" width=13 height=12>';
        echo ' <a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_plus.'&annee='.$annee_plus.'">'.
            '<img src="../img/right.gif" border=0 alt="mois suivant" width=13 height=12></a></td></tr>';
    }

    elseif (($num_mois==date('n')) && ($annee==date('Y')))
    {
        echo '<table border=0><tr><td align="right"><a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_moins.'&annee='.$annee_moins.'">'.
            '<img src="../img/left.gif" border=0 alt="mois précédents" width=13 height=12></a>';
        echo ' <img src="../img/right.gif" border=0 alt="mois suivants" width=13 height=12></td></tr>';
    }
    else
    {
        echo '<table border=0><tr><td align="right"><a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_moins.'&annee='.$annee_moins.'">'.
            '<img src="../img/left.gif" border=0 alt="mois précédents" width=13 height=12></a>';
        echo ' <a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_plus.'&annee='.$annee_plus.'">'.
            '<img src="../img/right.gif" border=0 alt="mois suivants" width=13 height=12></a></td></tr>';
    }
    ?>


    <table border=1 width='100%'>
        <tr>
            <td></td><td align="center"><b>total</b></td>
            <?php
            foreach($tville as $cab) {
                ?>
                <td align='center'><b><?php echo $cab; ?></b></td>
                <?php
            }
            ?>
        </tr>

        <?php


        // $req="SELECT cabinet, COUNT(*)
        // 	 FROM suivi_hebdomadaire as dep
        // 	 WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo' and cabinet!='jgomes' and cabinet!='sbirault' ";

        // 	    $date_dep=$annee."-".$num_mois."-1";
        // 	    $req.="AND DATEDIFF(dep.dmaj,'$date_dep')>=0 ";
        // 	    if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
        // 	        ($num_mois=="10") || ($num_mois=="12"))
        // 	        $req.="AND DATEDIFF(dep.dmaj,'$date_dep')<=30 ";
        // 		elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
        // 	        $req.="AND DATEDIFF(dep.dmaj,'$date_dep')<=29 ";
        // 		elseif ($num_mois=="2" && (($annee%4)==0))
        // 		    $req.="AND DATEDIFF(dep.dmaj,'$date_dep')<=28 ";
        //         elseif ($num_mois=="2" && (($annee%4)!=0))
        // 			$req.="AND DATEDIFF(dep.dmaj,'$date_dep')<=27 ";

        // 	 $req.="GROUP BY cabinet
        // 	 ORDER BY cabinet";

        // 	$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        //    foreach($tcabinet as $cab) {
        //     $tpat_hebdo[$cab]="";
        //    }

        // $total_hebdo=0;

        // 	while (list($cab_hebdo, $pat_hebdo) = mysql_fetch_row($res))
        // 	{
        // 	    $tcab_hebdo[]=$cab_hebdo;
        // 		$tpat_hebdo[$cab_hebdo]=$pat_hebdo;
        // 		$total_hebdo+=$pat_hebdo;
        // 	}


        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_hebdo_temps_passe as dep
	 WHERE cabinet!='irdes'  and cabinet!='ergo' and cabinet!='jgomes' and cabinet!='sbirault' ";/*cabinet !='zTest' and */

        $date_dep=$annee."-".$num_mois."-1";
        $req.="AND DATEDIFF(dep.date,'$date_dep')>=0 ";
        if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
            ($num_mois=="10") || ($num_mois=="12"))
            $req.="AND DATEDIFF(dep.date,'$date_dep')<=30 ";
        elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
            $req.="AND DATEDIFF(dep.date,'$date_dep')<=29 ";
        elseif ($num_mois=="2" && (($annee%4)==0))
            $req.="AND DATEDIFF(dep.date,'$date_dep')<=28 ";
        elseif ($num_mois=="2" && (($annee%4)!=0))
            $req.="AND DATEDIFF(dep.date,'$date_dep')<=27 ";

        $req.="GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while (list($cab_hebdo, $pat_hebdo) = mysql_fetch_row($res))
        {
            if(!isset($tpat_hebdo[$cab_hebdo]) ) //EA 29-04-2014  pb initialisation
                $tpat_hebdo[$cab_hebdo]= 0;
            $tpat_hebdo[$cab_hebdo]=$tpat_hebdo[$cab_hebdo]+$pat_hebdo;


            $total_hebdo+=$pat_hebdo;
        }
        ?>
        <tr>
            <td>Suivis Hebdomadaires</td><td  align='right'><?php echo $total_hebdo;?></td>

            <?php

            foreach ($tville as $cab=>$ville)
            {
                ?>
                <td align='right'><?php echo $tpat_hebdo[$cab];?></td>
                <?php
            }
            ?>
        </tr>


    </table>
    <br><br>
    <b>statistiques annuelles</b>
    <table border='0'>
        <tr><?php
            date_default_timezone_set('Europe/Paris');
            for ($i=2013; $i<=date('Y'); $i++)
            {
                ?>

                <form action="<?php echo $self; ?>" method="post" name="form">
                    <input type="hidden" name="etape" value="3">
                    <input type="hidden" name="annee" value="<?php echo $i; ?>">
                    <td><input type="submit" name="submit" size='30' value="<?php echo "Statistiques ".$i;?>"></form></td>
                <?php
            }
            ?>

        </tr>
    </table>
    <br><br>
    <b>statistiques globales</b>
    <table border='0'>
        <tr>

            <form action="<?php echo $self; ?>" method="post" name="form">
                <input type="hidden" name="etape" value="1">
                <td><input type="submit" name="submit" size='30' value="Statistiques globales"></form></td>

        </tr>
    </table>
    <?php

}



//stats annuelles
function etape_3(&$repete) {
    global $message, $Dossier, $Cabinet, $deval, $self, $doc;

    if(isset($_GET['annee']))
    {
        $annee=$_GET['annee'];
    }
    else
    {
        $annee=$_POST['annee'];
    }
//print_r($_POST);

    /*
    if (($num_mois=='8') && ($annee=='2004'))
    {
        echo "<b>Statistiques avant Septembre 2004</b>";
    }
    else*/
    {
        echo "<b>Statistiques pour ".$annee."</b><br>";
    }

    /*$req="SELECT cabinet, count(*) ".
             "FROM inf79_patient ".
             "GROUP BY cabinet ".
             "ORDER BY cabinet ";
             */
    $req="SELECT dossier.cabinet, COUNT(*), nom_cab
	 FROM dossier, account
	 WHERE dossier.cabinet!='irdes' and dossier.cabinet!='ergo' 
	 and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' 
	 and dossier.cabinet=account.cabinet 
	 GROUP BY nom_cab
	 ORDER BY nom_cab";


    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();

    while(list($cab, $pat,$ville) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tpat[$cab] = $pat;
        $tville[$cab]=$ville;
    }

    ?>
    <br>
    <br>
    <?php

    ?>

    <table border=1 width='100%'>
        <tr>
            <td></td><td align="center"><b>total</b></td>
            <?php
            foreach($tville as $cab) {
                ?>
                <td align='center'><b><?php echo $cab; ?></b></td>
                <?php
            }
            ?>
        </tr>


        <?php


        // $req="SELECT cabinet, COUNT(*)
        // 	 FROM suivi_hebdomadaire as dep ";

        // 	    $req.="WHERE date_format(dep.date, '%Y')='$annee'";
        // 		$req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' and cabinet!='sbirault' ";
        // 		 $req.="GROUP BY cabinet ".
        // 		 "ORDER BY cabinet ";

        // 	$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        //    foreach($tcabinet as $cab) {
        //     $tpat_hebdo[$cab]="";
        //    }

        //    $total_hebdo=0;

        // 	while (list($cab_hebdo, $pat_hebdo) = mysql_fetch_row($res))
        // 	{
        // 	    $tcab_hebdo[]=$cab_hebdo;
        // 		$tpat_hebdo[$cab_hebdo]=$pat_hebdo;
        // 		$total_hebdo+=$pat_hebdo;
        // 	}


        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_hebdo_temps_passe as dep ";

        $req.="WHERE date_format(dep.date, '%Y')='$annee'";
        $req.="AND cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' and cabinet!='sbirault' "; /*cabinet !='zTest' and */
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while (list($cab_hebdo, $pat_hebdo) = mysql_fetch_row($res))
        {
            $tpat_hebdo[$cab_hebdo]=$tpat_hebdo[$cab_hebdo]+$pat_hebdo;
            $total_hebdo+=$pat_hebdo;
        }
        ?>
        <tr>
            <td>Suivis Hebdomadaires</td><td  align='right'><?php echo $total_hebdo; ?></td>

            <?php

            foreach ($tville as $cab=>$ville)
            {
                ?>
                <td align='right'><?php echo $tpat_hebdo[$cab];?></td>
                <?php
            }
            ?>
        </tr>


    </table>
    <br><br>
    <b></b>
    <table border='0' width='100%'>

        <tr>
            <form action="<?php echo $self; ?>" method="post" name="form">
                <input type="hidden" name="etape" value="2">
                <td><input type="submit" name="submit" size='30' value="Retour aux statistiques mensuelles"></form></td>
        </tr>

    </table>

    <br><br>
    <b>statistiques annuelles</b>
    <table border='0'>
        <tr><?php

            for ($i=2013; $i<=date('Y'); $i++)
            {
                ?>

                <form action="<?php echo $self; ?>" method="post" name="form">
                    <input type="hidden" name="etape" value="3">
                    <input type="hidden" name="annee" value="<?php echo $i; ?>">
                    <td><input type="submit" name="submit" size='30' value="<?php echo "Statistiques ".$i;?>"></form></td>
                <?php
            }
            ?>

        </tr>
    </table>

    <br><br>
    <b>statistiques globales</b>
    <table border='0'>
        <tr>

            <form action="<?php echo $self; ?>" method="post" name="form">
                <input type="hidden" name="etape" value="1">
                <td><input type="submit" name="submit" size='30' value="Statistiques globales"></form></td>

        </tr>
    </table>
    <?php
}
?>
</body>
</html>
