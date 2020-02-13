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
    <title>Nombre de patients ayant eu un suivi RCVA</title>
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

entete_asalee("Nombre de patients ayant eu un suivi RCVA");
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
    global $message,$Dossier,$Cabinet, $deval, $self;


//Dossiers pour lesquels on peu calculer le RCVA au 30/06/2008
    $req="SELECT dossier.id, max(date)  FROM  `cardio_vasculaire_depart`  AS c, dossier WHERE dossier.id = c.id AND ".
        " cabinet !=  'ztest' AND ( ( tabac =  'oui' OR tabac =  'non' ) AND TaSys >  '0' AND dchol >  '0000-00-00' AND ".
        "dHDL >  '0000-00-00' AND ( HVG =  'oui' OR HVG =  'non' OR surcharge_ventricule =  'oui' OR surcharge_ventricule =  'non' ) ) ".
        "AND c.date <  '2009-01-01' and dossier.actif='oui' GROUP  BY dossier.id ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $nb_dossier=0;
    $nb_temoin=0;

    while(list($id, $date1, $nb)=mysql_fetch_row($res)){
        $req2="SELECT max(date) FROM cardio_vasculaire_depart as c WHERE ( ( tabac =  'oui' OR tabac =  'non' ) AND TaSys >  '0' ".
            "AND dchol >  '0000-00-00' AND dHDL >  '0000-00-00' AND ( HVG =  'oui' OR HVG =  'non' OR surcharge_ventricule =  'oui' ".
            "OR surcharge_ventricule =  'non' ) ) AND c.date <  '2009-06-30' and c.date>'2009-01-01' and id='$id' group by id";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

        if(mysql_num_rows($res2)>0){//Il y a un suivi avec un RCVA calculable
            list($date2)=mysql_fetch_row($res2);
            $req2="SELECT * FROM evaluation_infirmier WHERE date<'$date2' and date>'$date1' and id='$id' ";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            if(mysql_num_rows($res2)>0){//Il y a au moins une consult entre les 2 RCVA
                $nb_dossier++;

                $req2="SELECT * FROM suivi_diabete WHERE dsuivi<'2009-01-01' and dossier_id='$id' order by dsuivi DESC limit 0,1";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                if(mysql_num_rows($res2)>0){
                    $diab=1;
                }
                else{
                    $diab=0;
                }

                $req2="SELECT tabac, TaSys, chol, HDL, HVG, surcharge_ventricule, dnaiss, sexe FROM cardio_vasculaire_depart as c, dossier as d ".
                    "WHERE d.id='$id' and c.id=d.id and date='$date1'";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                list($tabac, $TaSys, $chol, $HDL, $HVG, $surcharge_ventricule, $dnaiss, $sexe)=mysql_fetch_row($res2);

                $age=calculage($dnaiss);

                $rcva=get_rcva($id, $sexe, $age, $diab, $tabac, $TaSys, $chol, $HDL, $HVG, $surcharge_ventricule);

                $valeurs2008[$id]=$rcva;


                $req2="SELECT * FROM suivi_diabete WHERE dossier_id='$id' order by dsuivi DESC limit 0,1";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                if(mysql_num_rows($res2)>0){
                    $diab=1;
                }
                else{
                    $diab=0;
                }

                $req2="SELECT tabac, TaSys, chol, HDL, HVG, surcharge_ventricule, dnaiss, sexe FROM cardio_vasculaire_depart as c, dossier as d ".
                    "WHERE d.id='$id' and c.id=d.id and date='$date2'";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                list($tabac, $TaSys, $chol, $HDL, $HVG, $surcharge_ventricule, $dnaiss, $sexe)=mysql_fetch_row($res2);

                $age=calculage($dnaiss);

                $rcva=get_rcva($id, $sexe, $age, $diab, $tabac, $TaSys, $chol, $HDL, $HVG, $surcharge_ventricule);

                $valeurs2009[$id]=$rcva;
            }
            else{
                $req2="SELECT * FROM cardio_premiere_consult WHERE date<'$date2' and date>'$date1' and id='$id' ";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                if(mysql_num_rows($res2)>0){//Il y a une consult dans le nouveau suivi
                    $nb_dossier++;

                    $req2="SELECT * FROM suivi_diabete WHERE dsuivi<'2009-01-01' and dossier_id='$id' order by dsuivi DESC limit 0,1";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    if(mysql_num_rows($res2)>0){
                        $diab=1;
                    }
                    else{
                        $diab=0;
                    }

                    $req2="SELECT tabac, TaSys, chol, HDL, HVG, surcharge_ventricule, dnaiss, sexe FROM cardio_vasculaire_depart as c, dossier as d ".
                        "WHERE d.id='$id' and c.id=d.id and date='$date1'";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    list($tabac, $TaSys, $chol, $HDL, $HVG, $surcharge_ventricule, $dnaiss, $sexe)=mysql_fetch_row($res2);

                    $age=calculage($dnaiss);

                    $rcva=get_rcva($id, $sexe, $age, $diab, $tabac, $TaSys, $chol, $HDL, $HVG, $surcharge_ventricule);

                    $valeurs2008[$id]=$rcva;


                    $req2="SELECT * FROM suivi_diabete WHERE dossier_id='$id' order by dsuivi DESC limit 0,1";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    if(mysql_num_rows($res2)>0){
                        $diab=1;
                    }
                    else{
                        $diab=0;
                    }

                    $req2="SELECT tabac, TaSys, chol, HDL, HVG, surcharge_ventricule, dnaiss, sexe FROM cardio_vasculaire_depart as c, dossier as d ".
                        "WHERE d.id='$id' and c.id=d.id and date='$date2'";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    list($tabac, $TaSys, $chol, $HDL, $HVG, $surcharge_ventricule, $dnaiss, $sexe)=mysql_fetch_row($res2);

                    $age=calculage($dnaiss);

                    $rcva=get_rcva($id, $sexe, $age, $diab, $tabac, $TaSys, $chol, $HDL, $HVG, $surcharge_ventricule);

                    $valeurs2009[$id]=$rcva;
                }
                else{//Pas de consult entre les 2 RCVA
                    $nb_temoin++;
                    $req2="SELECT * FROM suivi_diabete WHERE dsuivi<'2009-01-01' and dossier_id='$id' order by dsuivi DESC limit 0,1";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    if(mysql_num_rows($res2)>0){
                        $diab=1;
                    }
                    else{
                        $diab=0;
                    }

                    $req2="SELECT tabac, TaSys, chol, HDL, HVG, surcharge_ventricule, dnaiss, sexe FROM cardio_vasculaire_depart as c, dossier as d ".
                        "WHERE d.id='$id' and c.id=d.id and date='$date1'";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    list($tabac, $TaSys, $chol, $HDL, $HVG, $surcharge_ventricule, $dnaiss, $sexe)=mysql_fetch_row($res2);

                    $age=calculage($dnaiss);

                    $rcva=get_rcva($id, $sexe, $age, $diab, $tabac, $TaSys, $chol, $HDL, $HVG, $surcharge_ventricule);

                    $temoin2008[$id]=$rcva;


                    $req2="SELECT * FROM suivi_diabete WHERE dossier_id='$id' order by dsuivi DESC limit 0,1";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    if(mysql_num_rows($res2)>0){
                        $diab=1;
                    }
                    else{
                        $diab=0;
                    }

                    $req2="SELECT tabac, TaSys, chol, HDL, HVG, surcharge_ventricule, dnaiss, sexe FROM cardio_vasculaire_depart as c, dossier as d ".
                        "WHERE d.id='$id' and c.id=d.id and date='$date2'";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    list($tabac, $TaSys, $chol, $HDL, $HVG, $surcharge_ventricule, $dnaiss, $sexe)=mysql_fetch_row($res2);

                    $age=calculage($dnaiss);

                    $rcva=get_rcva($id, $sexe, $age, $diab, $tabac, $TaSys, $chol, $HDL, $HVG, $surcharge_ventricule);

                    $temoin2009[$id]=$rcva;
                }
            }

        }
    }

    echo $nb_dossier;

    $moyenne2008=round(array_sum($valeurs2008)/$nb_dossier, 2);
    $moyenne2009=round(array_sum($valeurs2009)/$nb_dossier, 2);
    $nb2008=$nb_dossier;

    $tem2008=round(array_sum($temoin2008)/$nb_temoin, 2);
    $tem2009=round(array_sum($temoin2009)/$nb_temoin, 2);

    echo "<table border='1'><tr><td></Td><td>Patients avec consultation</td><td>Patients sans consultation</Td></Tr>".
        "<tr><td>Au 30/06/2008</Td><td>$moyenne2008</Td><td>$tem2008</Td></Tr>".
        "<tr><td>Aujourd'hui</Td><td>$moyenne2009</Td><td>$tem2009</Td></Tr>".
        "<tr><td>Nb patients</Td><td>$nb2008</Td><td>$nb_temoin</Td></tr></table>";

    sort($valeurs2008);
    sort($valeurs2009);
    sort($temoin2008);
    sort($temoin2009);
    echo "<table border='1'><tr><td>Avec consult 2008</Td>";
    foreach($valeurs2008 as $val){
        echo "<td>$val</td>";
    }
    echo "</tr><tr><td>Avec consult 2009</td>";
    foreach($valeurs2009 as $val){
        echo "<td>$val</td>";
    }

    echo "</tr><tr><td>Sans consult 2008</td>";
    foreach($temoin2008 as $val){
        echo "<td>$val</td>";
    }

    echo "</tr><tr><td>Sans consult 2009</td>";
    foreach($temoin2009 as $val){
        echo "<td>$val</td>";
    }

}


function get_rcva($id, $sexe, $age, $diab, $tab, $tension, $choltot, $hdl, $ventricule, $surcharge_ventricule){
    /*	$tab = $this->tabac;
        $tension=$this->TaSys;
        $choltot=$this->Chol;
        $hdl=$this->HDL;
        $ventricule=$this->HVG;
        $surcharge_ventricule=$this->surcharge_ventricule;
    */

    if(($tab!="oui")&&($tab!="non")){
        die("Calcul du RCV impossible : toutes les données nécessaires ne sont pas renseignées dossier $id (tabac)");
    }
    if($tension==''){
        die("Calcul du RCV impossible : toutes les données nécessaires ne sont pas renseignées dossier $id (tension)");
    }
    if($choltot==''){
        die("Calcul du RCV impossible : toutes les données nécessaires ne sont pas renseignées dossier $id (chol tot)");
    }
    if($hdl==''){
        die("Calcul du RCV impossible : toutes les données nécessaires ne sont pas renseignées dossier $id (hdl)");
    }
    if(($ventricule!="oui")&&($ventricule!="non")&&($surcharge_ventricule!="oui")&&($surcharge_ventricule!="non")){
        die("Calcul du RCV impossible : toutes les données nécessaires ne sont pas renseignées dossier $id (ventricul)");
    }

    $e1 = -0.9119;
    $e2 = -0.2767;
    $e3 = -0.7181;
    $e4 = -0.5865;
    $l = 11.1122;
    $m0 = 4.4181 ;
    $s0 = -0.3155 ;
    $s1 = -0.2784;
    $c1 = -1.4792 ;
    $c2 = -0.1759 ;
    $d1 = -5.8549;
    $d2 = 1.8515 ;
    $d3 = -0.3758 ;
    $horizon=10;

    $pas=$tension;
    $tabac=0;
    $hvg=0;
    $chol=$choltot;
    $HDL=$hdl;

    if($tab=="oui"){
        $tabac=1;
    }
    if(($ventricule!="oui")&&($ventricule!="non")&&($surcharge_ventricule=="oui")){
        $hvg=1;
    }
    if($ventricule=="oui"){
        $hvg=1;
    }

    $a = $l + $e1*log($pas) + $e2*$tabac + $e3*log($chol/$HDL) + $e4*$hvg;

    if($sexe=="M"){
        $m = $a + $c1*log($age) + $c2*$diab*1; //;=> on considère que le patient n'est pas diabétique
    }
    if($sexe=='F'){
        $m = $a + $d1 + $d2*(log($age/74)*log($age/74)) + $d3*$diab; //on considère que la patient n'est pas diabétique
    }


    $m_calc = $m0 + $m;
    $s = exp($s0 + $s1*$m);

    $u = (log($horizon) - $m_calc ) / $s ;

    $pt = 1- exp(-exp($u));

    $rcva=$pt*100;
//	$rcva=round($pt*100, 2);
//	$rcva=$rcva."%";

    return $rcva;

}

function calculage($dnaiss, $date=""){
    $dnaiss=explode("-", $dnaiss);

    if($date==""){
        $annee=date("Y");
        $mois=date("m");
        $jour=date("d");
    }
    else{
        $date=explode("-", $date);
        $annee=$date[0];
        $mois=$date[1];
        $jour=$date[2];
    }

    $age=$annee-$dnaiss[0];

    if($dnaiss[1]>$mois){
        $age--;
    }
    elseif($dnaiss[1]==$mois){
        if($dnaiss[2]>$jour){
            $age--;
        }
    }

    return($age);
}
?>
</body>
</html>
