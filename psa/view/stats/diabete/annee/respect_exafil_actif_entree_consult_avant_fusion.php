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
    <title>Taux de respect de l'examen au monofilament / dossiers actifs entre -14 et -2 mois par date d'entrée ayant au moins 1 consultation</title>
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

entete_asalee("Taux de respect de l'examen au monofilament / dossiers actifs entre -14 et -2 mois par date d'entrée ayant au moins 1 consultation");
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
<font face='times new roman'>Taux de patients disposant d'au moins une mise à jour dans l'année</font></i>";
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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;

    $req="SELECT cabinet, total_diab2, nom_cab, region ".
        "FROM account ".
        "WHERE region!='' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $t_diab['tot']=0;
    $t_diab["eval"]=$t_diab["eval2"]=$t_diab["eval3"]=$t_diab["eval4"]=$t_diab["eval5"]=$t_diab["eval6"]=0;

    while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {

        if($region!=""){
            $t_diab[$cab]=0;

            $tville[$cab]=$ville;

            $regions[$cab]=$region;

        }

    }

    $exclu=array();


//Patients avec au moins un suivi
    $req="SELECT cabinet, dossier.id, min(dsuivi), count(*) ".
        "FROM suivi_diabete, dossier, evaluation_infirmier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND actif='oui' ".
        "AND evaluation_infirmier.id=dossier.id ".
        "AND suivi_diabete.dossier_id=dossier.id ".
        "GROUP BY cabinet, dossier_id ".
        "ORDER BY cabinet ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    while(list($cab, $id, $dsuivi) = mysql_fetch_row($res)) {

        $req="SELECT sortie FROM suivi_diabete WHERE dossier_id='$id' order by dsuivi DESC limit 0,1 ";
        $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        list($sortie)=mysql_fetch_row($res2);

        if($sortie!=1){
            if($_SESSION["national"]==1){
                if($dsuivi<='2005-06-30'){
                    $avjuin2005[$id]=1;

                    if(isset($regions[$cab])){
                        $t_diab['eval']=$t_diab['eval']+1;
                    }
                }
                elseif(($dsuivi<='2006-06-30')&&($dsuivi>'2005-06-30')){
                    $avjuin2006[$id]=1;

                    if(isset($regions[$cab])){
                        $t_diab['eval2']=$t_diab['eval2']+1;
                    }
                }
                elseif(($dsuivi<='2007-06-30')&&($dsuivi>'2006-06-30')){
                    $avjuin2007[$id]=1;

                    if(isset($regions[$cab])){
                        $t_diab['eval3']=$t_diab['eval3']+1;
                    }
                }
                elseif(($dsuivi<='2008-06-30')&&($dsuivi>'2007-06-30')){
                    $avjuin2008[$id]=1;

                    if(isset($regions[$cab])){
                        $t_diab['eval4']=$t_diab['eval4']+1;
                    }
                }
                else{
                    $apjuin2008[$id]=1;

                    if(isset($regions[$cab])){
                        $t_diab['eval5']=$t_diab['eval5']+1;
                    }
                }
            }
            elseif($_SESSION["region"]==1){
                if($regions[$cab]==$_SESSION["nom_region"]){
                    if($dsuivi<='2005-06-30'){
                        $avjuin2005[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval']=$t_diab['eval']+1;
                        }
                    }
                    elseif(($dsuivi<='2006-06-30')&&($dsuivi>'2005-06-30')){
                        $avjuin2006[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval2']=$t_diab['eval2']+1;
                        }
                    }
                    elseif(($dsuivi<='2007-06-30')&&($dsuivi>'2006-06-30')){
                        $avjuin2007[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval3']=$t_diab['eval3']+1;
                        }
                    }
                    elseif(($dsuivi<='2008-06-30')&&($dsuivi>'2007-06-30')){
                        $avjuin2008[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval4']=$t_diab['eval4']+1;
                        }
                    }
                    else{
                        $apjuin2008[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval5']=$t_diab['eval5']+1;
                        }
                    }
                }
            }
        }

    }



    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND actif='oui' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
//	 $tpat[$cab] = $pat;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 align='center'>
        <?php

        ///////////////Respect des examens //////////////////////////


        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }


        $tpat['tot']=0;
        $tpat['eval']=0;
        $tpat['eval2']=0;
        $tpat['eval3']=0;
        $tpat['eval4']=0;
        $tpat['eval5']=0;



        //Patients avec au moins un suivi
        $req="SELECT cabinet, dossier.id, min(dsuivi), count(*) ".
            "FROM suivi_diabete, dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND actif='oui' ".
            "AND evaluation_infirmier.id=dossier.id ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($cab, $dossier_id, $dsuivi)=mysql_fetch_row($res)){
            $req3="SELECT sortie FROM suivi_diabete WHERE dossier_id='$dossier_id' order by dsuivi DESC limit 0,1";

            $res3=mysql_query($req3) or die("erreur SQL:".mysql_error()."<br>$req3");

            list($sortie)=mysql_fetch_row($res3);

            if($sortie!=1){
                if($_SESSION["national"]==1){
                    $req2="SELECT dHBA FROM suivi_diabete WHERE dossier_id='$dossier_id' AND DATE_ADD(dExaFil, INTERVAL 14 MONTH)>=CURDATE() ".
                        "AND DATE_ADD(dExaFil, INTERVAL 2 MONTH)<=CURDATE() AND ExaFil='oui'";

                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    if(mysql_num_rows($res2)>=1){
                        if(isset($avjuin2005[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval']=$tpat['eval']+1;
                            }
                        }
                        elseif(isset($avjuin2006[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval2']=$tpat['eval2']+1;
                            }
                        }
                        elseif(isset($avjuin2007[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval3']=$tpat['eval3']+1;
                            }
                        }
                        elseif(isset($avjuin2008[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval4']=$tpat['eval4']+1;
                            }
                        }
                        elseif(isset($apjuin2008[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval5']=$tpat['eval5']+1;
                            }
                        }
                    }
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        $req2="SELECT dHBA FROM suivi_diabete WHERE dossier_id='$dossier_id' AND DATE_ADD(dExafil, INTERVAL 14 MONTH)>=CURDATE() ".
                            "AND DATE_ADD(dExafil, INTERVAL 2 MONTH)<=CURDATE() and ExaFil='oui'";

                        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                        if(mysql_num_rows($res2)>=1){
                            if(isset($avjuin2005[$dossier_id]))
                            {
                                if(isset($regions[$cab])){
                                    $tpat['eval']=$tpat['eval']+1;
                                }
                            }
                            elseif(isset($avjuin2006[$dossier_id]))
                            {
                                if(isset($regions[$cab])){
                                    $tpat['eval2']=$tpat['eval2']+1;
                                }
                            }
                            elseif(isset($avjuin2007[$dossier_id]))
                            {
                                if(isset($regions[$cab])){
                                    $tpat['eval3']=$tpat['eval3']+1;
                                }
                            }
                            elseif(isset($avjuin2008[$dossier_id]))
                            {
                                if(isset($regions[$cab])){
                                    $tpat['eval4']=$tpat['eval4']+1;
                                }
                            }
                            elseif(isset($apjuin2008[$dossier_id]))
                            {
                                if(isset($regions[$cab])){
                                    $tpat['eval5']=$tpat['eval5']+1;
                                }
                            }
                        }
                    }
                }
            }
        }




        ?>

        <tr>
            <td>Taux de respect des examens &nbsp;</td>
            <td> <b>&nbsp;Total</b> &nbsp;</td>
            <td align="center"> <b>&nbsp;avant le 30/06/2005</b>	 &nbsp;</td>
            <td align="center"><b>&nbsp; entre le 01/07/2005 et le 30/06/2006</b> &nbsp;</td>
            <td align="center"><b>&nbsp; entre le 01/07/2006 et le 30/06/2007</b> &nbsp;</td>
            <td align="center"><b>&nbsp; entre le 01/07/2007 et le 30/06/2008</b> &nbsp;</td>
            <td align="center"><b>&nbsp;après le 30/06/2008</b> &nbsp;</td>


            <?php


            ?>
        </tr>
        <?php

        $t_diab['total']=$t_diab['eval']+$t_diab['eval2']+$t_diab['eval3']+$t_diab['eval4']+$t_diab['eval5'];
        $taux_hba['total']=round(($tpat['eval']+$tpat['eval2']+$tpat['eval3']+$tpat['eval4']+$tpat['eval5'])/$t_diab['total']*100);
        $taux_hba['total'].="%";


        if ($t_diab['eval']==0)
        {
            $taux_hba['eval']="ND";
        }
        else
        {
            $taux_hba['eval']=round($tpat['eval']/$t_diab['eval']*100);
            $taux_hba['eval'].="%";

        }


        if ($t_diab['eval2']==0)
        {
            $taux_hba['eval2']="ND";
        }
        else
        {
            $taux_hba['eval2']=round($tpat['eval2']/$t_diab['eval2']*100);
            $taux_hba['eval2'].="%";
        }


        if ($t_diab['eval3']==0)
        {
            $taux_hba['eval3']="ND";
        }
        else
        {
            $taux_hba['eval3']=round($tpat['eval3']/$t_diab['eval3']*100);
            $taux_hba['eval3'].="%";
        }

        if ($t_diab['eval4']==0)
        {
            $taux_hba['eval4']="ND";
        }
        else
        {
            $taux_hba['eval4']=round($tpat['eval4']/$t_diab['eval4']*100);
            $taux_hba['eval4'].="%";
        }

        if ($t_diab['eval5']==0)
        {
            $taux_hba['eval5']="ND";
        }
        else
        {
            $taux_hba['eval5']=round($tpat['eval5']/$t_diab['eval5']*100);
            $taux_hba['eval5'].="%";
        }

        ?>
        <tr>
            <td>1 examen au monofilament dans l'année écoulée<sup>1</sup></td>
            <td align='right'><?php echo $taux_hba['total']; ?></td>
            <td align='right'><?php echo $taux_hba['eval']; ?></td>
            <td align='right'><?php echo $taux_hba['eval2']; ?></td>
            <td align='right'><?php echo $taux_hba['eval3']; ?></td>
            <td align='right'><?php echo $taux_hba['eval4']; ?></td>
            <td align='right'><?php echo $taux_hba['eval5']; ?></td>
        </tr>

    </table>
    <br><br>
    <?php

    $annee0=2004;
    $mois0=3;

    $annee=date('Y');
    $mois=date('m');

    $mois--;


    if($mois<3)
    {
        $annee--;
        $mois=12;
    }
    elseif(($mois>=3)&&($mois<6))
    {
        $mois=3;
    }
    elseif(($mois>=6)&&($mois<9))
    {
        $mois=6;
    }
    elseif(($mois>=9)&&($mois<12))
    {
        $mois=9;
    }

    $jour[3]=$jour[12]=31;
    $jour[6]=$jour[9]=30;

    while(($annee>$annee0)||(($annee==$annee0)&&($mois>=$mois0)))
    {
        if($mois<10)
        {
            $date=$annee.'-0'.$mois.'-'.$jour[$mois];
        }
        else
        {
            $date=$annee.'-'.$mois.'-'.$jour[$mois];
        }
        tableau($date, $regions);

        $mois=$mois-3;

        if($mois<=0)
        {
            $mois=$mois+12;
            $annee--;
        }
    }
    ?>
    <sup>1</sup>Nombre de patients ayant eu au moins 1 examen au monofilament entre -14 et -2 mois/nb dossiers actifs avec un suivi et une consultation<br>

    <?
}

function tableau($date, $regions){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";





    $req="SELECT cabinet, total_diab2, nom_cab, region ".
        "FROM account ".
        "WHERE region!='' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $t_diab['tot']=0;
    $t_diab['eval']=0;
    $t_diab['eval2']=0;
    $t_diab['eval3']=0;
    $t_diab['eval4']=0;
    $t_diab['eval5']=0;

    while(list($cab, $total_diab2, $ville, $region) = mysql_fetch_row($res)) {
        if($region!=""){
            $t_diab[$cab]=0;

            $tville[$cab]=$ville;
        }
    }

    $exclu=array();

    /*
    $req="SELECT cabinet, count(*) ".
             "FROM dossier ".
             "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  ".
             "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
             "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
             "GROUP BY cabinet ".
             "ORDER BY cabinet, numero ";

    */

//Patients avec au moins un suivi
    $req="SELECT cabinet, dossier.id, min(dsuivi), count(*) ".
        "FROM suivi_diabete, dossier, evaluation_infirmier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
        "AND dsuivi<='$date' AND evaluation_infirmier.date<='$date' ".
        "AND evaluation_infirmier.id=dossier.id ".
        "AND suivi_diabete.dossier_id=dossier.id ".
        "GROUP BY cabinet, dossier_id ".
        "ORDER BY cabinet ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    while(list($cab, $id, $dsuivi) = mysql_fetch_row($res)) {

        $req2="SELECT sortie FROM suivi_diabete WHERE dsuivi<='$date' AND dossier_id='$id' ORDER BY dsuivi DESC limit 0,1";
        $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

        list($sortie)=mysql_fetch_row($res2);

        if($sortie!=1){
            if($_SESSION["national"]==1){
                if($dsuivi<='2005-06-30')
                {
                    $avjuin2005[$id]=1;

                    if(isset($regions[$cab])){
                        $t_diab['eval']=$t_diab['eval']+1;
                    }
                }
                elseif(($dsuivi<='2006-06-30')&&($dsuivi>'2005-06-30'))
                {
                    $avjuin2006[$id]=1;

                    if(isset($regions[$cab])){
                        $t_diab['eval2']=$t_diab['eval2']+1;
                    }
                }
                elseif(($dsuivi<='2007-06-30')&&($dsuivi>'2006-06-30'))
                {
                    $avjuin2007[$id]=1;

                    if(isset($regions[$cab])){
                        $t_diab['eval3']=$t_diab['eval3']+1;
                    }
                }
                elseif(($dsuivi<='2008-06-30')&&($dsuivi>'2007-06-30'))
                {
                    $avjuin2008[$id]=1;

                    if(isset($regions[$cab])){
                        $t_diab['eval4']=$t_diab['eval4']+1;
                    }
                }
                else
                {
                    $apjuin2008[$id]=1;

                    if(isset($regions[$cab])){
                        $t_diab['eval5']=$t_diab['eval5']+1;
                    }
                }
            }
            elseif($_SESSION["region"]==1){
                if($regions[$cab]==$_SESSION["nom_region"]){
                    if($dsuivi<='2005-06-30')
                    {
                        $avjuin2005[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval']=$t_diab['eval']+1;
                        }
                    }
                    elseif(($dsuivi<='2006-06-30')&&($dsuivi>'2005-06-30'))
                    {
                        $avjuin2006[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval2']=$t_diab['eval2']+1;
                        }
                    }
                    elseif(($dsuivi<='2007-06-30')&&($dsuivi>'2006-06-30'))
                    {
                        $avjuin2007[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval3']=$t_diab['eval3']+1;
                        }
                    }
                    elseif(($dsuivi<='2008-06-30')&&($dsuivi>'2007-06-30'))
                    {
                        $avjuin2008[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval4']=$t_diab['eval4']+1;
                        }
                    }
                    else
                    {
                        $apjuin2008[$id]=1;

                        if(isset($regions[$cab])){
                            $t_diab['eval5']=$t_diab['eval5']+1;
                        }
                    }
                }
            }
        }


    }



    ?>
    <br>
    <br>
    <table border=1 align='center'>
        <?php

        ///////////////Respect des examens //////////////////////////




        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }


        $tpat['tot']=0;
        $tpat['eval']=0;
        $tpat['eval2']=0;
        $tpat['eval3']=0;
        $tpat['eval4']=0;
        $tpat['eval5']=0;

        $req="SELECT cabinet, dossier.id ".
            "FROM suivi_diabete, dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND dsuivi<='$date' AND evaluation_infirmier.date<='$date' ".
            "AND evaluation_infirmier.id=dossier.id ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($cab, $dossier_id) = mysql_fetch_row($res)) {

            $req3="SELECT sortie FROM suivi_diabete WHERE dossier_id='$dossier_id' AND dsuivi='$date' ORDER BY dsuivi DESC limit 0,1";

            $res3=mysql_query($req3) or die("erreur SQL:".mysql_error()."<br>$req3");

            list($sortie)=mysql_fetch_row($res3);

            if($sortie!=1){
                $req2="SELECT dHBA FROM suivi_diabete WHERE dossier_id='$dossier_id' AND DATE_ADD(dExafil, INTERVAL 14 MONTH)>='$date' ".
                    "AND DATE_ADD(dExafil, INTERVAL 2 MONTH)<='$date' and Exafil='oui'";

                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                if(mysql_num_rows($res2)>=1){
                    if($_SESSION["national"]==1){
                        if(isset($avjuin2005[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval']=$tpat['eval']+1;
                            }
                        }
                        elseif(isset($avjuin2006[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval2']=$tpat['eval2']+1;
                            }
                        }
                        elseif(isset($avjuin2007[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval3']=$tpat['eval3']+1;
                            }
                        }
                        elseif(isset($avjuin2008[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval4']=$tpat['eval4']+1;
                            }
                        }
                        elseif(isset($apjuin2008[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval5']=$tpat['eval5']+1;
                            }
                        }
                    }
                    elseif($_SESSION["region"]==1){
                        if(isset($avjuin2005[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval']=$tpat['eval']+1;
                            }
                        }
                        elseif(isset($avjuin2006[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval2']=$tpat['eval2']+1;
                            }
                        }
                        elseif(isset($avjuin2007[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval3']=$tpat['eval3']+1;
                            }
                        }
                        elseif(isset($avjuin2008[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval4']=$tpat['eval4']+1;
                            }
                        }
                        elseif(isset($apjuin2008[$dossier_id]))
                        {
                            if(isset($regions[$cab])){
                                $tpat['eval5']=$tpat['eval5']+1;
                            }
                        }
                    }
                }
            }
        }


        ?>

        <tr>
            <td>Taux de respect des examens &nbsp;</td>
            <td align="center"> <b>&nbsp;Total</b>	 &nbsp;</td>
            <td align="center"> <b>&nbsp;avant le 30/06/2005</b>	 &nbsp;</td>
            <td align="center"> <b>&nbsp;entre le 01/07/2005 et le 30/06/2006</b>	 &nbsp;</td>
            <td align="center"> <b>&nbsp;entre le 01/07/2006 et le 30/06/2007</b>	 &nbsp;</td>
            <td align="center"> <b>&nbsp;entre le 01/07/2007 et le 30/06/2008</b>	 &nbsp;</td>
            <td align="center"> <b>&nbsp;après le 30/06/2008</b>	 &nbsp;</td>


            <?php



            ?>
        </tr>
        <?php

        $t_diab['total']=$t_diab['eval']+$t_diab['eval2']+$t_diab['eval3']+$t_diab['eval4']+$t_diab['eval5'];
        $taux_hba['total']=round(($tpat['eval']+$tpat['eval2']+$tpat['eval3']+$tpat['eval4']+$tpat['eval5'])/$t_diab['total']*100);
        $taux_hba['total'].="%";


        if ($t_diab['eval']==0)
        {
            $taux_hba['eval']="ND";
        }
        else
        {
            $taux_hba['eval']=round($tpat['eval']/$t_diab['eval']*100);
            $taux_hba['eval'].="%";

        }


        if ($t_diab['eval2']==0)
        {
            $taux_hba['eval2']="ND";
        }
        else
        {
            $taux_hba['eval2']=round($tpat['eval2']/$t_diab['eval2']*100);
            $taux_hba['eval2'].="%";

        }


        if ($t_diab['eval3']==0)
        {
            $taux_hba['eval3']="ND";
        }
        else
        {
            $taux_hba['eval3']=round($tpat['eval3']/$t_diab['eval3']*100);
            $taux_hba['eval3'].="%";
        }

        if ($t_diab['eval4']==0)
        {
            $taux_hba['eval4']="ND";
        }
        else
        {
            $taux_hba['eval4']=round($tpat['eval4']/$t_diab['eval4']*100);
            $taux_hba['eval4'].="%";
        }

        if ($t_diab['eval5']==0)
        {
            $taux_hba['eval5']="ND";
        }
        else
        {
            $taux_hba['eval5']=round($tpat['eval5']/$t_diab['eval5']*100);
            $taux_hba['eval5'].="%";
        }

        ?>
        <tr>
            <td>1 examen au monofilament dans l'année écoulée<sup>1</sup></td>
            <td align='right'><?php echo $taux_hba['total']; ?></td>
            <td align='right'><?php echo $taux_hba['eval']; ?></td>
            <td align='right'><?php echo $taux_hba['eval2']; ?></td>
            <td align='right'><?php echo $taux_hba['eval3']; ?></td>
            <td align='right'><?php echo $taux_hba['eval4']; ?></td>
            <td align='right'><?php echo $taux_hba['eval5']; ?></td>
        </tr>


    </table>
    <br>
    <br>
    <?php

}

# calcul de la différence en mois à partir d'un timestamp MySQL
function diffmois($date, $ref=false) {

    list($a,$m,$j)= explode('-',$date,3);

    if($ref===false)//aucune date de référence
    {
        $diff_mois = (date('Y')-$a)*12;
        $diff_mois=$diff_mois+ date('m')-$m;
        /*  if(date('m') < $m) $age--;*/
        if(date('d') < $j) $diff_mois--;
    }
    else //une date de référence au format 'yyyy-mm-dd-
    {
        list($aref, $mref, $jref)=explode('-', $ref, 3);
        $diff_mois = ($aref-$a)*12;
        $diff_mois=$diff_mois+ $mref-$m;
        /*  if(date('m') < $m) $age--;*/
        if($jref < $j) $diff_mois--;
    }
    return $diff_mois;
}

?>
</body>
</html>
