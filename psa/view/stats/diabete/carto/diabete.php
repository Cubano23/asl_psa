<?php
session_start();
if(!isset($_SESSION['nom'])) {
    # pas pass� par l'identification
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
    <title>Statistiques Asal�e Diab�te</title>
</head>
<body bgcolor=#FFE887>
<?php
date_default_timezone_set('Europe/Paris'); //EA 08-01-2015
require_once "Config.php";
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php");

# connexion aux donn�es
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter � la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../../global/entete.php");
//echo $loc;

entete_asalee("Statistiques : suivi d'activit� pour les protocoles du diab�te");
//echo $loc;
?>

<br><br>
<?

# boucle principale
do {
    $repete=false;

    # fen�tre glissante: donn�es mensuelles
    if (isset($_GET['mois']) && isset($_GET['annee']))
    {
        etape_2($repete);
        exit;
    }

    # �tape 1 : affichage des donn�es mensuelles
    if (!isset($_POST['etape'])) {
        etape_2($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1://donn�es depuis le d�but
                etape_1($repete);
                break;

            # �tape 2  : donn�es mensuelles
            case 2:
                etape_2($repete);
                break;

            # �tape 3  : donn�es annuelles
            case 3:
                etape_3($repete);
                break;
        }
    }
} while($repete);

# fin de traitement principal

//arr�t� depuis le d�but
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self;


    echo "<b>Statistiques consolid�es ";

    for ($i=2004; $i<date('Y'); $i++)
    {
        echo $i.'-';
    }

    echo date('Y')."</b><br>";

    $req="SELECT dossier.cabinet, count(*), nom_cab ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='zTest' and dossier.cabinet!='irdes'   and dossier.cabinet!='ergo' ".
        "and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
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
        $tville[]=$ville;
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

        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_diabete, dossier
	 WHERE suivi_diabete.dossier_id=dossier.id
	 AND cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and dossier.cabinet!='jgomes'
	 and dossier.cabinet!='sbirault' 
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat_sdiab[$cab]="";
        }

        $total_sdiab=0;

        while (list($cab_sdiab, $pat_sdiab) = mysql_fetch_row($res))
        {
            $tcab_sdiab[]=$cab_sdiab;
            $tpat_sdiab[$cab_sdiab]=$pat_sdiab;
            $total_sdiab+=$pat_sdiab;
        }

        ?>
        <tr>
            <td>Suivis diab�te</td><td align='right'><?php echo $total_sdiab; ?></td>

            <?php

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align='right'><?php echo $tpat_sdiab[$cab];?></td>
                <?php
            }
            ?>
        </tr>

        <?php

        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_diabete as dep, dossier
	 WHERE dep.id=dossier.id
	 AND cabinet !='zTest' and cabinet!='irdes' and cabinet!='ergo' and dossier.cabinet!='jgomes'
	 and dossier.cabinet!='sbirault' 
	 GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat_diab[$cab]="";
        }

        $total_diab=0;

        while (list($cab_diab, $pat_diab) = mysql_fetch_row($res))
        {
            $tcab_diab[]=$cab_diab;
            $tpat_diab[$cab_diab]=$pat_diab;
            $total_diab+=$pat_diab;
        }

        ?>
        <tr>
            <td>D�pistages diab�te</td><td align='right'><?php echo $total_diab; ?></td>

            <?php

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align='right'><?php echo $tpat_diab[$cab];?></td>
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

            for ($i=2004; $i<=date('Y'); $i++)
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

//total mensuel
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

    $mois=array(1=>"Janvier", 2=>"F�vrier", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 7=>"Juillet",
        8=>"Ao�t", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"D�cembre");

    echo "<b>Statistiques pour ".$mois[$num_mois]." ".$annee."</b>";

    $req="SELECT dossier.cabinet, COUNT(*), nom_cab
	 FROM dossier, account
	 WHERE dossier.cabinet !='zTest' and dossier.cabinet!='irdes' and dossier.cabinet!='ergo' 
	 and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' 
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
        $tville[]=$ville;
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

    if (($mois_moins=='2') && ($annee=='2004'))
    {
        echo '<table border=0><tr><td align="right">'.
            '<img src="../../img/left.gif" border=0 alt="mois pr�c�dents" width=13 height=12>';
        echo ' <a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_plus.'&annee='.$annee_plus.'">'.
            '<img src="../../img/right.gif" border=0 alt="mois suivant" width=13 height=12></a></td></tr>';
    }

    elseif (($num_mois==date('n')) && ($annee==date('Y')))
    {
        echo '<table border=0><tr><td align="right"><a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_moins.'&annee='.$annee_moins.'">'.
            '<img src="../../img/left.gif" border=0 alt="mois pr�c�dents" width=13 height=12></a>';
        echo ' <img src="../../img/right.gif" border=0 alt="mois suivants" width=13 height=12></td></tr>';
    }
    else
    {
        echo '<table border=0><tr><td align="right"><a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_moins.'&annee='.$annee_moins.'">'.
            '<img src="../../img/left.gif" border=0 alt="mois pr�c�dents" width=13 height=12></a>';
        echo ' <a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_plus.'&annee='.$annee_plus.'">'.
            '<img src="../../img/right.gif" border=0 alt="mois suivants" width=13 height=12></a></td></tr>';
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

        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ";


        $date_dep=$annee."-".$num_mois."-1";
        $req.="AND DATEDIFF(dep.dsuivi,'$date_dep')>=0 ";
        if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
            ($num_mois=="10") || ($num_mois=="12"))
            $req.="AND DATEDIFF(dep.dsuivi,'$date_dep')<=30 ";
        elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
            $req.="AND DATEDIFF(dep.dsuivi,'$date_dep')<=29 ";
        elseif ($num_mois=="2" && (($annee%4)==0))
            $req.="AND DATEDIFF(dep.dsuivi,'$date_dep')<=28 ";
        elseif ($num_mois=="2" && (($annee%4)!=0))
            $req.="AND DATEDIFF(dep.dsuivi,'$date_dep')<=27 ";


        $req.="AND cabinet !='zTest' and cabinet!='irdes'   and cabinet!='ergo' and dossier.cabinet!='jgomes' 
				and dossier.cabinet!='sbirault' ";
        $req.="GROUP BY cabinet
	 ORDER BY cabinet";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_sdiab[$cab]="";
        }


        $total_sdiab=0;

        while (list($cab_sdiab, $pat_sdiab) = mysql_fetch_row($res))
        {
            $tcab_sdiab[]=$cab_sdiab;
            $tpat_sdiab[$cab_sdiab]=$pat_sdiab;
            $total_sdiab+=$pat_sdiab;
        }

        ?>
        <tr>
            <td>Suivis diab�te</td><td  align='right'><?php echo $total_sdiab; ?></td>

            <?php

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align='right'><?php echo $tpat_sdiab[$cab];?></td>
                <?php
            }
            ?>
        </tr>

        <?php


        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_diabete as dep, dossier
	 WHERE dep.id=dossier.id ";
        $req.="AND cabinet !='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
		and dossier.cabinet!='sbirault' ";


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

        foreach($tcabinet as $cab) {
            $tpat_diab[$cab]="";
        }


        $total_diab=0;

        while (list($cab_diab, $pat_diab) = mysql_fetch_row($res))
        {
            $tcab_diab[]=$cab_diab;
            $tpat_diab[$cab_diab]=$pat_diab;
            $total_diab+=$pat_diab;
        }

        ?>
        <tr>
            <td>D�pistages diab�te</td><td  align='right'><?php echo $total_diab; ?></td>

            <?php

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align='right'><?php echo $tpat_diab[$cab];?></td>
                <?php
            }
            ?>
        </tr>


    </table>
    <br><br>
    <b>statistiques annuelles</b>
    <table border='0'>
        <tr><?php

            for ($i=2004; $i<=date('Y'); $i++)
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


    echo "<b>Statistiques pour ".$annee."</b><br>";


    $req="SELECT dossier.cabinet, COUNT(*), nom_cab
	 FROM dossier, account
	 WHERE dossier.cabinet !='zTest' and dossier.cabinet!='irdes' and dossier.cabinet!='ergo' 
	 and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' 
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
        $tville[]=$ville;
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


        $req="SELECT cabinet, COUNT(*)
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ";


        $req.="AND date_format(dep.dsuivi, '%Y')='$annee'";
        $req.="AND cabinet !='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' and cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_sdiab[$cab]="";
        }

        $total_sdiab=0;

        while (list($cab_sdiab, $pat_sdiab) = mysql_fetch_row($res))
        {
            $tcab_sdiab[]=$cab_sdiab;
            $tpat_sdiab[$cab_sdiab]=$pat_sdiab;
            $total_sdiab+=$pat_sdiab;
        }

        ?>
        <tr>
            <td>Suivis diab�te</td><td  align='right'><?php echo $total_sdiab; ?></td>

            <?php

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align='right'><?php echo $tpat_sdiab[$cab];?></td>
                <?php
            }
            ?>
        </tr>

        <?php



        $req="SELECT cabinet, COUNT(*)
	 FROM depistage_diabete as dep, dossier
	 WHERE dep.id=dossier.id ";

        $req.="AND date_format(dep.date, '%Y')='$annee'";
        $req.="AND cabinet !='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' 
				and dossier.cabinet!='sbirault' ";
        $req.="GROUP BY cabinet ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach($tcabinet as $cab) {
            $tpat_diab[$cab]="";
        }

        $total_diab=0;
        while (list($cab_diab, $pat_diab) = mysql_fetch_row($res))
        {
            $tcab_diab[]=$cab_diab;
            $tpat_diab[$cab_diab]=$pat_diab;
            $total_diab+=$pat_diab;
        }

        ?>
        <tr>
            <td>D�pistages diab�te</td><td  align='right'><?php echo $total_diab; ?></td>

            <?php

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align='right'><?php echo $tpat_diab[$cab];?></td>
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

            for ($i=2004; $i<=date('Y'); $i++)
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
