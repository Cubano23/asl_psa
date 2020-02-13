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
    <title>Suivi des scores EPICES</title>
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

entete_asalee("Suivi des scores EPICES");
//echo $loc;
?>
<!--<table cellpadding="2" cellspacing="2" border="0"
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
<font face='times new roman'>Statistiques : suivi du trafic �valuation infirmiers</font></i>";
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
global $message,$Dossier,$Cabinet, $deval, $self;


$req="SELECT date_format(date, '%d/%m/%Y'), travailleur_social, ".
    "complementaire, couple, proprietaire, ".
    "difficulte, sport, spectacle, vacances, famille, hebergement, materiel, dossier.id from ".
    "epices, dossier where dossier.id=epices.id and cabinet!='ztest' ".
    "and travailleur_social!='' and complementaire!='' and couple!='' and ".
    "proprietaire!='' and difficulte!='' and sport!='' and spectacle!='' ".
    "and vacances!='' and famille!='' and hebergement!='' and materiel!='' order by date ";
$res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


?>
<br>
<br>
<table border=1>
    <tr>
        <th>Date du questionnaire</th><th>Dossier</th><th>Valeur du score</th>
    </tr>

    <?php

    $moyenne=$nb=0;

    while(list($date, $travailleur_social, $complementaire, $couple, $proprietaire,
        $difficulte, $sport, $spectacle, $vacances, $famille, $hebergement,
        $materiel,$dossier)=mysql_fetch_row($res)){

        $score=calcul_epices($travailleur_social, $complementaire, $couple, $proprietaire, $difficulte,
            $sport, $spectacle, $vacances, $famille, $hebergement, $materiel);

        echo "<tr><td>$date</td><td>$dossier</td><td align='right'>$score</td></tr>";
        $moyenne=$moyenne+$score;
        $nb++;
    }

    $moyenne=round($moyenne/$nb, 2);

    echo "<tr><td>Moyenne</td><td>&nbsp;</td><td align='right'>$moyenne</td></tr>";
    echo "</table><br><br>";

    //Calcul pour les formulaires incomplets

    echo "Taux de questionnaires pour lesquels la vuln�rabilit� est av�r�e � partir du nombre de questions renseign�es.<br><br>".
        "Une v�n�rabilit� est av�r�e � partir du renseignement de 4 questions attestant chacune d'une vuln�rabilit� particuli�re";

    $req="SELECT travailleur_social, ".
        "complementaire, couple, proprietaire, ".
        "difficulte, sport, spectacle, vacances, famille, hebergement, materiel from ".
        "epices, dossier where dossier.id=epices.id and cabinet!='ztest' ".
        "and (travailleur_social='' or complementaire='' or couple='' or ".
        "proprietaire='' or difficulte='' or sport='' or spectacle!='' ".
        "and vacances='' or famille='' or hebergement='' or materiel!='') order by date ";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb_questionnaire=$nb_vulnerable=0;

    while(list($travailleur_social, $complementaire, $couple, $proprietaire,
        $difficulte, $sport, $spectacle, $vacances, $famille, $hebergement,
        $materiel)=mysql_fetch_row($res)){


        $vulnerable=0;
        $nb_rep=0;

        if($travailleur_social!=''){
            $nb_rep++;

            if($travailleur_social=="oui"){
                $vulnerable++;
            }
        }

        if($complementaire!=''){
            $nb_rep++;

            if($complementaire=='non'){
                $vulnerable++;
            }
        }

        if($couple!=''){
            $nb_rep++;

            if($couple=='non'){
                $vulnerable++;
            }
        }

        if($proprietaire!=''){
            $nb_rep++;

            if($proprietaire=='non'){
                $vulnerable++;
            }
        }

        if($difficulte!=''){
            $nb_rep++;

            if($difficulte=='oui'){
                $vulnerable++;
            }
        }

        if($sport!=''){
            $nb_rep++;

            if($sport=='non'){
                $vulnerable++;
            }
        }

        if($spectacle!=''){
            $nb_rep++;

            if($spectacle=='non'){
                $vulnerable++;
            }
        }

        if($vacances!=''){
            $nb_rep++;

            if($vacances=='non'){
                $vulnerable++;
            }
        }

        if($famille!=''){
            $nb_rep++;

            if($famille=='non'){
                $vulnerable++;
            }
        }

        if($hebergement!=''){
            $nb_rep++;

            if($hebergement=='non'){
                $vulnerable++;
            }
        }

        if($materiel!=''){
            $nb_rep++;

            if($materiel=='non'){
                $vulnerable++;
            }

        }

        if($nb_rep>=4){
            $nb_questionnaire++;

            if($vulnerable>=4){
                $nb_vulnerable++;
            }
        }


    }

    echo "<table border='1'><tr><td>Nb questionnaires incomplets avec au moins 4 questions remplies</td><td align='right'>$nb_questionnaire</td></Tr>".
        "<tr><td>Nb questionnaires incomplets avec au moins 4 questions attestant d'une vuln�rabilit�</td><td align='right'>$nb_vulnerable</Td></tr>".
        "<tr><td>Taux de vuln�rabilit� pour les questionnaires incomplets</td><td align='right'>".round($nb_vulnerable/$nb_questionnaire*100)." %</td></Tr>".
        "</table>";
    die;
    }

    function calcul_epices($travailleur_social, $complementaire, $couple, $proprietaire, $difficulte,
                           $sport, $spectacle, $vacances, $famille, $hebergement, $materiel){

        $total=75.14;



        if($travailleur_social=="oui"){
            $total=$total+10.06;
        }
        if($complementaire=="oui"){
            $total=$total-11.83;
        }
        if($couple=="oui"){
            $total=$total-8.28;
        }
        if($proprietaire=="oui"){
            $total=$total-8.28;
        }
        if($difficulte=="oui"){
            $total=$total+14.80;
        }
        if($sport=="oui"){
            $total=$total-6.51;
        }
        if($spectacle=="oui"){
            $total=$total-7.10;
        }
        if($vacances=="oui"){
            $total=$total-7.10;
        }
        if($famille=="oui"){
            $total=$total-9.47;
        }
        if($hebergement=="oui"){
            $total=$total-9.47;
        }
        if($materiel=="oui"){
            $total=$total-7.10;
        }

        return $total;

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

        $mois=array(1=>"Janvier", 2=>"F�vrier", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 7=>"Juillet",
            8=>"Ao�t", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"D�cembre");
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
	 WHERE infirmiere!='' and region!='' and dossier.cabinet=account.cabinet
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

        if (($mois_moins=='2') && ($annee=='2004'))
        {
            echo '<table border=0><tr><td align="right">'.
                '<img src="../img/left.gif" border=0 alt="mois pr�c�dents" width=13 height=12>';
            echo ' <a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_plus.'&annee='.$annee_plus.'">'.
                '<img src="../img/right.gif" border=0 alt="mois suivant" width=13 height=12></a></td></tr>';
        }

        elseif (($num_mois==date('n')) && ($annee==date('Y')))
        {
            echo '<table border=0><tr><td align="right"><a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_moins.'&annee='.$annee_moins.'">'.
                '<img src="../img/left.gif" border=0 alt="mois pr�c�dents" width=13 height=12></a>';
            echo ' <img src="../img/right.gif" border=0 alt="mois suivants" width=13 height=12></td></tr>';
        }
        else
        {
            echo '<table border=0><tr><td align="right"><a href="'.$_SERVER['PHP_SELF'].'?mois='.$mois_moins.'&annee='.$annee_moins.'">'.
                '<img src="../img/left.gif" border=0 alt="mois pr�c�dents" width=13 height=12></a>';
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
            /*$req="SELECT cabinet, COUNT(*)
                 FROM inf79_reponses
                 WHERE doc='evaluation_infirmier' ";
            */

            $req="SELECT cabinet, COUNT(*)
	 FROM liste_exam as dep, dossier
	 WHERE dep.id=dossier.id ";

            $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
                "and dossier.cabinet!='sbirault' ";
            /*	if (($num_mois=='8') && ($annee=='2004'))
                {
                    $req.="AND DATEDIFF(dmaj, '2004-09-01')<0 ";
                }
                else*/
            {
                $date_dep=$annee."-".$num_mois."-1";
                $req.="AND dep.dmaj>='$date_dep' ";
                if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
                    ($num_mois=="10") || ($num_mois=="12")){
                    $date_fin=$annee."-".$num_mois."-31";
                }
                elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
                    $date_fin=$annee."-".$num_mois."-30";
                elseif ($num_mois=="2" && (($annee%4)==0))
                    $date_fin=$annee."-".$num_mois."-29";
                elseif ($num_mois=="2" && (($annee%4)!=0))
                    $date_fin=$annee."-".$num_mois."-28";

                $req.="AND dep.dmaj<='$date_fin' ";
            }
            $req.="GROUP BY cabinet
	 ORDER BY cabinet";

            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            foreach($tcabinet as $cab) {
                $tpat_infir[$cab]="";
            }

            $total_inf=0;

            while (list($cab_infir, $pat_infir) = mysql_fetch_row($res))
            {
                if(isset($tville[$cab_infir])){
                    $tcab_infir[]=$cab_infir;
                    $tpat_infir[$cab_infir]=$pat_infir;
                    $total_inf+=$pat_infir;
                }
            }

            ?>



            <tr>
                <td>Nb examens saisis ou int�gr�s</td><td  align='right'><?php echo $total_inf;?></td>

                <?php

                foreach ($tville as $cab=>$ville)
                {
                    ?>
                    <td align='right'><?php echo $tpat_infir[$cab];?></td>
                    <?php
                }
                ?>
            </tr>

            <?php
            $req="SELECT cabinet, COUNT(*)
	 FROM liste_exam as dep, dossier
	 WHERE dep.id=dossier.id ";

            $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
                "and dossier.cabinet!='sbirault' ";
            /*	if (($num_mois=='8') && ($annee=='2004'))
                {
                    $req.="AND DATEDIFF(dmaj, '2004-09-01')<0 ";
                }
                else*/
            {
                $date_dep=$annee."-".$num_mois."-1";
                $req.="AND dep.date_exam>='$date_dep' ";
                if (($num_mois=="1") || ($num_mois=="3") || ($num_mois=="5")||($num_mois=="7") || ($num_mois=="8") ||
                    ($num_mois=="10") || ($num_mois=="12")){
                    $date_fin=$annee."-".$num_mois."-31";
                }
                elseif (($num_mois=="4") || ($num_mois=="6") || ($num_mois=="9")||($num_mois=="11"))
                    $date_fin=$annee."-".$num_mois."-30";
                elseif ($num_mois=="2" && (($annee%4)==0))
                    $date_fin=$annee."-".$num_mois."-29";
                elseif ($num_mois=="2" && (($annee%4)!=0))
                    $date_fin=$annee."-".$num_mois."-28";

                $req.="AND dep.date_exam<='$date_fin' ";
            }
            $req.="GROUP BY cabinet
	 ORDER BY cabinet";

            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            foreach($tcabinet as $cab) {
                $tpat_infir[$cab]="";
            }

            $total_inf=0;

            while (list($cab_infir, $pat_infir) = mysql_fetch_row($res))
            {
                if(isset($tville[$cab_infir])){
                    $tcab_infir[]=$cab_infir;
                    $tpat_infir[$cab_infir]=$pat_infir;
                    $total_inf+=$pat_infir;
                }
            }

            ?>



            <tr>
                <td>Nb examens r�alis�s</td><td  align='right'><?php echo $total_inf;?></td>

                <?php

                foreach ($tville as $cab=>$ville)
                {
                    ?>
                    <td align='right'><?php echo $tpat_infir[$cab];?></td>
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
	 WHERE infirmiere!='' and region!='' 
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
	 FROM liste_exam as dep, dossier
	 WHERE dep.id=dossier.id ";

            $req.="AND date_format(dep.dmaj, '%Y')='$annee'";
            $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  ".
                "and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ";
            $req.="GROUP BY cabinet ".
                "ORDER BY cabinet ";

            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            foreach($tcabinet as $cab) {
                $tpat_infir[$cab]="";
            }

            $total_inf=0;
            while (list($cab_infir, $pat_infir) = mysql_fetch_row($res))
            {
                if(isset($tville[$cab_infir])){
                    $tcab_infir[]=$cab_infir;
                    $tpat_infir[$cab_infir]=$pat_infir;
                    $total_inf+=$pat_infir;
                }
            }

            ?>



            <tr>
                <td>Nb examens saisis ou int�gr�s</td><td  align='right'><?php echo $total_inf; ?></td>

                <?php

                foreach ($tville as $cab=>$ville)
                {
                    ?>
                    <td align='right'><?php echo $tpat_infir[$cab];?></td>
                    <?php
                }
                ?>
            </tr>
            <?php

            $req="SELECT cabinet, COUNT(*)
	 FROM liste_exam as dep, dossier
	 WHERE dep.id=dossier.id ";

            $req.="AND date_format(dep.date_exam, '%Y')='$annee'";
            $req.="AND cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  ".
                "and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ";
            $req.="GROUP BY cabinet ".
                "ORDER BY cabinet ";

            $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            foreach($tcabinet as $cab) {
                $tpat_infir[$cab]="";
            }

            $total_inf=0;
            while (list($cab_infir, $pat_infir) = mysql_fetch_row($res))
            {
                if(isset($tville[$cab_infir])){
                    $tcab_infir[]=$cab_infir;
                    $tpat_infir[$cab_infir]=$pat_infir;
                    $total_inf+=$pat_infir;
                }
            }

            ?>



            <tr>
                <td>Nb examens r�alis�s</td><td  align='right'><?php echo $total_inf; ?></td>

                <?php

                foreach ($tville as $cab=>$ville)
                {
                    ?>
                    <td align='right'><?php echo $tpat_infir[$cab];?></td>
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
