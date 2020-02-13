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
    <title>Taux de patients n'ayant pas eu de consultation disposant d'au moins une mise à jour entre -14 et -2 mois / dossiers actifs suivant date d'entrée</title>
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

entete_asalee("Taux de patients actifs n'ayant pas eu de consultation disposant d'examens entre -14 et -2mois classés par date d'entrée");
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

    $req="SELECT cabinet, total_diab2, nom_cab ".
        "FROM account ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' ".
        "and cabinet!='sbirault' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $t_diab['tot']=0;
    $t_diab['eval']=0;
    $t_diab['eval2']=0;
    $t_diab['eval3']=0;

    while(list($cab, $total_diab2, $ville) = mysql_fetch_row($res)) {
        $t_diab[$cab]=0;

        $tville[$cab]=$ville;

    }

    $exclu=array();


//Patients avec au moins un suivi
    $req="SELECT cabinet, dossier.id, min(dsuivi), count(*) ".
        "FROM suivi_diabete, dossier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND actif='oui' ".
        "AND suivi_diabete.dossier_id=dossier.id ".
        "GROUP BY cabinet, dossier_id ".
        "ORDER BY cabinet ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    while(list($cab, $id, $dsuivi) = mysql_fetch_row($res)) {


        $req="SELECT * FROM evaluation_infirmier WHERE id='$id' ";
        $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        if(mysql_num_rows($res2)==0){



            if($dsuivi<='2005-06-30'){
                $avjuin2005[$id]=1;
                $t_diab['eval']=$t_diab['eval']+1;
            }
            elseif(($dsuivi<='2006-06-30')&&($dsuivi>'2005-06-30')){
                $avjuin2006[$id]=1;
                $t_diab['eval2']=$t_diab['eval2']+1;
            }
            else{
                $apjuin2006[$id]=1;
                $t_diab['eval3']=$t_diab['eval3']+1;
            }

        }
    }



    $req="SELECT dossier.cabinet, count(*) ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='zTest' and dossier.cabinet!='irdes'  and dossier.cabinet!='ergo'  and ".
        "dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' and dossier.cabinet=account.cabinet and region!='' ".
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

        $req="SELECT cabinet, dossier_id, DATE_ADD(max(dExaFil), INTERVAL 12 MONTH),".
            " DATE_ADD(max(dExaPieds), INTERVAL 12 MONTH), DATE_ADD(max(dChol), INTERVAL 12 MONTH), ".
            "DATE_ADD(max(suivi_diabete.dCreat), INTERVAL 12 MONTH), DATE_ADD(max(dAlbu),INTERVAL 12 MONTH), ".
            "DATE_ADD(max(dFond), INTERVAL 12 MONTH), DATE_ADD(max(dECG), INTERVAL 12 MONTH) ".
            "from suivi_diabete,dossier where cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  ".
            "and dossier.cabinet!='sbirault' and cabinet!='jgomes' and suivi_diabete".
            ".dossier_id = dossier.id  AND dossier.actif='oui' and ".
            "(((dExaFil is not NULL and DATE_ADD(dExaFil, ".
            "INTERVAL 14 MONTH) >= CURDATE() AND DATE_ADD(dExaFil, INTERVAL 2 MONTH)<=CURDATE()) or (dExaPieds is not NULL and ".
            "DATE_ADD(dExaPieds, INTERVAL 14 MONTH) >= CURDATE() and ".
            "DATE_ADD(dExaPieds, INTERVAL 2 MONTH) <= CURDATE())) or ".
            "((dChol is not NULL and DATE_ADD(dChol, INTERVAL 14 MONTH) >= CURDATE() AND DATE_ADD(dChol, INTERVAL 2 MONTH)<=CURDATE()) ".
            " or (dLDL is not NULL and DATE_ADD(dLDL, INTERVAL 14 MONTH) >= CURDATE() AND DATE_ADD(dLDL, INTERVAL 2 MONTH)<=CURDATE())  ".
            "or (suivi_diabete.dCreat is not NULL and DATE_ADD(suivi_diabete.dCreat, INTERVAL 14 MONTH) >= CURDATE() ".
            "AND DATE_ADD(suivi_diabete.dCreat, INTERVAL 2 MONTH)<=CURDATE()) or (dAlbu is not NULL ".
            "and DATE_ADD(dAlbu, INTERVAL 14 MONTH) >= CURDATE() AND DATE_ADD(dAlbu, INTERVAL 2 MONTH) <=CURDATE()) or ".
            "(dFond is not NULL and DATE_ADD(dFond, INTERVAL 14 MONTH) >= CURDATE() AND DATE_ADD(dFond, INTERVAL 2 MONTH)<=CURDATE())".
            " or (dECG is not NULL and DATE_ADD(dECG, INTERVAL 14 MONTH) >= ".
            "CURDATE() AND DATE_ADD(dECG, INTERVAL 2 MONTH)>=CURDATE()))) GROUP by dossier_id order by dossier_id";
        /*$req="SELECT cabinet, count(*) ".
                 "FROM suivi_diabete, dossier ".
                 "WHERE cabinet!='zTest' and cabinet!='irdes'  ".
                 "AND actif='oui' ".
                 "AND suivi_diabete.dossier_id=dossier.id ".
                 "and ((dsuivi is not NULL and DATE_ADD(dsuivi, ".
                 "INTERVAL 1 YEAR) >= CURDATE())) ".
                 "GROUP BY cabinet, dossier_id ".
                 "ORDER BY cabinet ";*/
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]['hba']=0;
            $tpat[$cab]['exafil']=0;
            $tpat[$cab]['pied']=0;
            $tpat[$cab]['chol']=0;
            $tpat[$cab]['creat']=0;
            $tpat[$cab]['albu']=0;
            $tpat[$cab]['fond']=0;
            $tpat[$cab]['ecg']=0;
        }


        $tpat['tot']['hba']=0;
        $tpat['tot']['exafil']=0;
        $tpat['tot']['pied']=0;
        $tpat['tot']['chol']=0;
        $tpat['tot']['creat']=0;
        $tpat['tot']['albu']=0;
        $tpat['tot']['fond']=0;
        $tpat['tot']['ecg']=0;

        $tpat['eval']['hba']=0;
        $tpat['eval']['exafil']=0;
        $tpat['eval']['pied']=0;
        $tpat['eval']['chol']=0;
        $tpat['eval']['creat']=0;
        $tpat['eval']['albu']=0;
        $tpat['eval']['fond']=0;
        $tpat['eval']['ecg']=0;

        $tpat['eval2']['hba']=0;
        $tpat['eval2']['exafil']=0;
        $tpat['eval2']['pied']=0;
        $tpat['eval2']['chol']=0;
        $tpat['eval2']['creat']=0;
        $tpat['eval2']['albu']=0;
        $tpat['eval2']['fond']=0;
        $tpat['eval2']['ecg']=0;

        $tpat['eval3']['hba']=0;
        $tpat['eval3']['exafil']=0;
        $tpat['eval3']['pied']=0;
        $tpat['eval3']['chol']=0;
        $tpat['eval3']['creat']=0;
        $tpat['eval3']['albu']=0;
        $tpat['eval3']['fond']=0;
        $tpat['eval3']['ecg']=0;

        while(list($cabinet, $dossier_id, $dExaFil, $dExaPieds, $dChol, $dCreat, $dAlbu,
            $dFond, $dECG) = mysql_fetch_row($res)) {


            $req2="SELECT dHBA FROM suivi_diabete WHERE dossier_id='$dossier_id' AND DATE_ADD(dHBA, INTERVAL 14 MONTH)>=CURDATE() ".
                "AND DATE_ADD(dHBA, INTERVAL 2 MONTH)<=CURDATE()";

            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            if(mysql_num_rows($res2)>=3){
                if(isset($avjuin2005[$dossier_id]))
                {
                    $tpat['eval']['hba']=$tpat['eval']['hba']+1;
                }
                elseif(isset($avjuin2006[$dossier_id]))
                {
                    $tpat['eval2']['hba']=$tpat['eval2']['hba']+1;
                }
                elseif(isset($apjuin2006[$dossier_id]))
                {
                    $tpat['eval3']['hba']=$tpat['eval3']['hba']+1;
                }
            }


            if(($dExaFil!='')&&($dExaFil!='NULL'))
            {
//		 if(diffmois($dExaFil)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['exafil']=$tpat['eval']['exafil']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['exafil']=$tpat['eval2']['exafil']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['exafil']=$tpat['eval3']['exafil']+1;
                    }
                }
            }

            if(($dExaPieds!='')&&($dExaPieds!='NULL'))
            {
//		 if(diffmois($dExaPieds)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['pied']=$tpat['eval']['pied']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['pied']=$tpat['eval2']['pied']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['pied']=$tpat['eval3']['pied']+1;
                    }
                }
            }

            if(($dChol!='')&&($dChol!='NULL'))
            {
//		 if(diffmois($dChol)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['chol']=$tpat['eval']['chol']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['chol']=$tpat['eval2']['chol']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['chol']=$tpat['eval3']['chol']+1;
                    }
                }
            }

            if(($dCreat!='')&&($dCreat!='NULL'))
            {
//		 if(diffmois($dCreat)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['creat']=$tpat['eval']['creat']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['creat']=$tpat['eval2']['creat']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['creat']=$tpat['eval3']['creat']+1;
                    }
                }
            }

            if(($dAlbu!='')&&($dAlbu!='NULL'))
            {
//		 if(diffmois($dAlbu)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['albu']=$tpat['eval']['albu']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['albu']=$tpat['eval2']['albu']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['albu']=$tpat['eval3']['albu']+1;
                    }
                }
            }

            if(($dFond!='')&&($dFond!='NULL'))
            {
//		 if(diffmois($dFond)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['fond']=$tpat['eval']['fond']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['fond']=$tpat['eval2']['fond']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['fond']=$tpat['eval3']['fond']+1;
                    }
                }
            }

            if(($dECG!='')&&($dECG!='NULL'))
            {
//		 if(diffmois($dECG)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['ecg']=$tpat['eval']['ecg']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['ecg']=$tpat['eval2']['ecg']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['ecg']=$tpat['eval3']['ecg']+1;
                    }
                }
            }


        }


        ?>

        <tr>
            <td>Taux de respect des examens &nbsp;</td>
            <td> <b>&nbsp;Total</b> &nbsp;</td>
            <td align="center"> <b>&nbsp;Patients inclus avant le 30/06/2005</b>	 &nbsp;</td>
            <td align="center"><b>&nbsp; Patients inclus entre le 01/07/2005 et le 30/06/2006</b> &nbsp;</td>
            <td align="center"><b>&nbsp; Patients inclus après le 30/06/2006</b> &nbsp;</td>


            <?php


            ?>
        </tr>
        <?php

        $t_diab['total']=$t_diab['eval']+$t_diab['eval2']+$t_diab['eval3'];
        $taux_hba['total']=round(($tpat['eval']['hba']+$tpat['eval2']['hba']+$tpat['eval3']['hba'])/$t_diab['total']*100);
        $taux_hba['total'].="%";

        $taux_exafil['total']=round(($tpat['eval']['exafil']+$tpat['eval2']['exafil']+$tpat['eval3']['exafil'])/$t_diab['total']*100);
        $taux_exafil['total'].="%";

        $taux_pied['total']=round(($tpat['eval']['pied']+$tpat['eval2']['pied']+$tpat['eval3']['pied'])/$t_diab['total']*100);
        $taux_pied['total'].="%";

        $taux_chol['total']=round(($tpat['eval']['chol']+$tpat['eval2']['chol']+$tpat['eval3']['chol'])/$t_diab['total']*100);
        $taux_chol['total'].="%";

        $taux_creat['total']=round(($tpat['eval']['creat']+$tpat['eval2']['creat']+$tpat['eval3']['creat'])/$t_diab['total']*100);
        $taux_creat['total'].="%";

        $taux_albu['total']=round(($tpat['eval']['albu']+$tpat['eval2']['albu']+$tpat['eval3']['albu'])/$t_diab['total']*100);
        $taux_albu['total'].="%";

        $taux_fond['total']=round(($tpat['eval']['fond']+$tpat['eval2']['fond']+$tpat['eval3']['fond'])/$t_diab['total']*100);
        $taux_fond['total'].="%";

        $taux_ecg['total']=round(($tpat['eval']['ecg']+$tpat['eval2']['ecg']+$tpat['eval3']['ecg'])/$t_diab['total']*100);
        $taux_ecg['total'].="%";

        if ($t_diab['eval']==0)
        {
            $taux_hba['eval']=$taux_exafil['eval']=$taux_pied['eval']=$taux_chol['eval']=$taux_creat['eval']='ND';
            $taux_albu['eval']=$taux_fond['eval']=$taux_ecg['eval']="ND";
        }
        else
        {
            $taux_hba['eval']=round($tpat['eval']['hba']/$t_diab['eval']*100);
            $taux_hba['eval'].="%";

            $taux_exafil['eval']=round($tpat['eval']['exafil']/$t_diab['eval']*100);
            $taux_exafil['eval'].="%";

            $taux_pied['eval']=round($tpat['eval']['pied']/$t_diab['eval']*100);
            $taux_pied['eval'].="%";

            $taux_chol['eval']=round($tpat['eval']['chol']/$t_diab['eval']*100);
            $taux_chol['eval'].="%";

            $taux_creat['eval']=round($tpat['eval']['creat']/$t_diab['eval']*100);
            $taux_creat['eval'].="%";

            $taux_albu['eval']=round($tpat['eval']['albu']/$t_diab['eval']*100);
            $taux_albu['eval'].="%";

            $taux_fond['eval']=round($tpat['eval']['fond']/$t_diab['eval']*100);
            $taux_fond['eval'].="%";

            $taux_ecg['eval']=round($tpat['eval']['ecg']/$t_diab['eval']*100);
            $taux_ecg['eval'].="%";
        }


        if ($t_diab['eval2']==0)
        {
            $taux_hba['eval2']=$taux_exafil['eval2']=$taux_pied['eval2']=$taux_chol['eval2']=$taux_creat['eval2']='ND';
            $taux_albu['eval2']=$taux_fond['eval2']=$taux_ecg['eval2']="ND";
        }
        else
        {
            $taux_hba['eval2']=round($tpat['eval2']['hba']/$t_diab['eval2']*100);
            $taux_hba['eval2'].="%";

            $taux_exafil['eval2']=round($tpat['eval2']['exafil']/$t_diab['eval2']*100);
            $taux_exafil['eval2'].="%";

            $taux_pied['eval2']=round($tpat['eval2']['pied']/$t_diab['eval2']*100);
            $taux_pied['eval2'].="%";

            $taux_chol['eval2']=round($tpat['eval2']['chol']/$t_diab['eval2']*100);
            $taux_chol['eval2'].="%";

            $taux_creat['eval2']=round($tpat['eval2']['creat']/$t_diab['eval2']*100);
            $taux_creat['eval2'].="%";

            $taux_albu['eval2']=round($tpat['eval2']['albu']/$t_diab['eval2']*100);
            $taux_albu['eval2'].="%";

            $taux_fond['eval2']=round($tpat['eval2']['fond']/$t_diab['eval2']*100);
            $taux_fond['eval2'].="%";

            $taux_ecg['eval2']=round($tpat['eval2']['ecg']/$t_diab['eval2']*100);
            $taux_ecg['eval2'].="%";
        }


        if ($t_diab['eval3']==0)
        {
            $taux_hba['eval3']=$taux_exafil['eval3']=$taux_pied['eval3']=$taux_chol['eval3']=$taux_creat['eval3']='ND';
            $taux_albu['eval3']=$taux_fond['eval3']=$taux_ecg['eval3']="ND";
        }
        else
        {
            $taux_hba['eval3']=round($tpat['eval3']['hba']/$t_diab['eval3']*100);
            $taux_hba['eval3'].="%";

            $taux_exafil['eval3']=round($tpat['eval3']['exafil']/$t_diab['eval3']*100);
            $taux_exafil['eval3'].="%";

            $taux_pied['eval3']=round($tpat['eval3']['pied']/$t_diab['eval3']*100);
            $taux_pied['eval3'].="%";

            $taux_chol['eval3']=round($tpat['eval3']['chol']/$t_diab['eval3']*100);
            $taux_chol['eval3'].="%";

            $taux_creat['eval3']=round($tpat['eval3']['creat']/$t_diab['eval3']*100);
            $taux_creat['eval3'].="%";

            $taux_albu['eval3']=round($tpat['eval3']['albu']/$t_diab['eval3']*100);
            $taux_albu['eval3'].="%";

            $taux_fond['eval3']=round($tpat['eval3']['fond']/$t_diab['eval3']*100);
            $taux_fond['eval3'].="%";

            $taux_ecg['eval3']=round($tpat['eval3']['ecg']/$t_diab['eval3']*100);
            $taux_ecg['eval3'].="%";
        }

        ?>
        <tr>
            <td>3 HBA1c <sup>1</sup></td>
            <td align='right'><?php echo $taux_hba['total']; ?></td>
            <td align='right'><?php echo $taux_hba['eval']; ?></td>
            <td align='right'><?php echo $taux_hba['eval2']; ?></td>
            <td align='right'><?php echo $taux_hba['eval3']; ?></td>
        </tr>
        <tr>
            <td>Examen au monofilament<sup>2</sup></td>
            <td align='right'><?php echo $taux_exafil['total']; ?></td>
            <td align='right'><?php echo $taux_exafil['eval']; ?></td>
            <td align='right'><?php echo $taux_exafil['eval2']; ?></td>
            <td align='right'><?php echo $taux_exafil['eval3']; ?></td>

        </tr>
        <tr>
            <td>Examen des pieds<sup>3</Sup></td>
            <td align='right'><?php echo $taux_pied['total']; ?></td>
            <td align='right'><?php echo $taux_pied['eval']; ?></td>
            <td align='right'><?php echo $taux_pied['eval2']; ?></td>
            <td align='right'><?php echo $taux_pied['eval3']; ?></td>

        </tr>
        <tr>
            <td>Dosage du HDL - Cholestérol<sup>4</sup></td>
            <td align='right'><?php echo $taux_chol['total']; ?></td>
            <td align='right'><?php echo $taux_chol['eval']; ?></td>
            <td align='right'><?php echo $taux_chol['eval2']; ?></td>
            <td align='right'><?php echo $taux_chol['eval3']; ?></td>
        </tr>
        <td>Créatinémie<sup>5</sup></td>
        <td align='right'><?php echo $taux_creat['total']; ?></td>
        <td align='right'><?php echo $taux_creat['eval']; ?></td>
        <td align='right'><?php echo $taux_creat['eval2']; ?></td>
        <td align='right'><?php echo $taux_creat['eval3']; ?></td>
        </tr>
        <tr>
            <td>Micro Albuminurie<sup>6</sup></td>
            <td align='right'><?php echo $taux_albu['total']; ?></td>
            <td align='right'><?php echo $taux_albu['eval']; ?></td>
            <td align='right'><?php echo $taux_albu['eval2']; ?></td>
            <td align='right'><?php echo $taux_albu['eval3']; ?></td>
        </tr>
        <tr>
            <td>Fond d'oeil<sup>7</sup></td>
            <td align='right'><?php echo $taux_fond['total']; ?></td>
            <td align='right'><?php echo $taux_fond['eval']; ?></td>
            <td align='right'><?php echo $taux_fond['eval2']; ?></td>
            <td align='right'><?php echo $taux_fond['eval3']; ?></td>
        </tr>
        <tr>
            <td>ECG<sup>8</sup></td>
            <td align='right'><?php echo $taux_ecg['total']; ?></td>
            <td align='right'><?php echo $taux_ecg['eval']; ?></td>
            <td align='right'><?php echo $taux_ecg['eval2']; ?></td>
            <td align='right'><?php echo $taux_ecg['eval3']; ?></td>
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
        tableau($date);

        $mois=$mois-3;

        if($mois<=0)
        {
            $mois=$mois+12;
            $annee--;
        }
    }
    ?>
    <sup>1</sup>Nombre de patients ayant eu au moins 3 résultats de HBA1c entre -14 et -2 mois/nb dossiers actifs avec un suivi<br>
    <sup>2</sup>Nombre de patients ayant eu un résultat d'examen au monofilament entre -14 et -2 mois/nb de dossiers actifs avec un suivi<br>
    <sup>3</sup>Nombre de patients ayant eu un résultat d'examen des pieds entre -14 et -2 mois/nb de dossiers actifs avec un suivi<br>
    <sup>4</sup>Nombre de patients ayant eu un résultat de dosage du HDL/Cholesterol entre -14 et -2 mois/nb de dossiers actifs avec un suivi<br>
    <sup>5</sup>Nombre de patients ayant eu un résultat de créatinémie entre -14 et -2 mois/nb de dossiers actifs avec un suivi<br>
    <sup>6</sup>Nombre de patients ayant eu un résultat de Micro-Albuminurie entre -14 et -2 mois/nb de dossiers actifs avec un suivi<br>
    <sup>7</sup>Nombre de patients ayant eu un résultat de fond d'oeil entre -14 et -2 mois/nb de dossiers actifs avec un suivi<br>
    <sup>8</sup>Nombre de patients ayant eu un résultat d'ECG entre -14 et -2 mois/nb de dossiers actifs avec un suivi<br>

    <?
}

function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";





    $req="SELECT cabinet, total_diab2, nom_cab ".
        "FROM account ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' ".
        "and cabinet!='sbirault' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $t_diab['tot']=0;
    $t_diab['eval']=0;
    $t_diab['eval2']=0;
    $t_diab['eval3']=0;

    while(list($cab, $total_diab2, $ville) = mysql_fetch_row($res)) {
        $t_diab[$cab]=0;

        $tville[$cab]=$ville;

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
        "FROM suivi_diabete, dossier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
        "AND dsuivi<='$date' ".
        "AND suivi_diabete.dossier_id=dossier.id ".
        "GROUP BY cabinet, dossier_id ".
        "ORDER BY cabinet ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    while(list($cab, $id, $dsuivi) = mysql_fetch_row($res)) {



        $req="SELECT * FROM evaluation_infirmier WHERE id='$id' AND date<='$date'";
        $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        if(mysql_num_rows($res2)==0){
            if($dsuivi<='2005-06-30')
            {
                $avjuin2005[$id]=1;
                $t_diab['eval']=$t_diab['eval']+1;
            }
            elseif(($dsuivi<='2006-06-30')&&($dsuivi>'2005-06-30'))
            {
                $avjuin2006[$id]=1;
                $t_diab['eval2']=$t_diab['eval2']+1;
            }
            else
            {
                $apjuin2006[$id]=1;
                $t_diab['eval3']=$t_diab['eval3']+1;
            }

        }

    }







    /*

    $req="SELECT cabinet, count(*) ".
             "FROM dossier ".
             "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  ".
             "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
             "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
             "GROUP BY cabinet ".
             "ORDER BY cabinet, numero ";
    //echo $req;
    //die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    //$tcabinet=array();
    $t_diab['tot']=0;
    $t_diab['eval']=0;
    $cab_prec="";

    foreach ($tcabinet as $cab)
    {
    //	$tpat[$cab]=0;
        $tcabinet_util[$cab]=0;

    }


    while(list($cab, $pat) = mysql_fetch_row($res)) {
    //	 $tcabinet[] = $cab;

         if($cab!=$cab_prec)
         {
             $t_diab['tot']=$t_diab['tot']+$t_diab[$cab];
             $cab_prec=$cab;
             $tcabinet_util[$cab]=$t_diab[$cab];

            if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $t_diab['eval']=$t_diab['eval']+ $t_diab[$cab]+1;
            }
         }
    }

    */
    ?>
    <br>
    <br>
    <table border=1 align='center'>
        <?php

        ///////////////Respect des examens //////////////////////////

        $req="SELECT cabinet, dossier_id, DATE_ADD(max(dExaFil), INTERVAL 12 MONTH),".
            " DATE_ADD(max(dExaPieds), INTERVAL 12 MONTH), DATE_ADD(max(dChol), INTERVAL 12 MONTH), ".
            "DATE_ADD(max(suivi_diabete.dCreat), INTERVAL 12 MONTH), DATE_ADD(max(dAlbu),INTERVAL 12 MONTH), ".
            "DATE_ADD(max(dFond), INTERVAL 12 MONTH), DATE_ADD(max(dECG), INTERVAL 12 MONTH) ".
            "from suivi_diabete,dossier where cabinet!='zTest'  and cabinet!='ergo' and cabinet!='irdes'  ".
            "and dossier.cabinet!='sbirault' and cabinet!='jgomes' and suivi_diabete".
            ".dossier_id = dossier.id  AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "and ( ((dExaFil is not NULL and dExaFil<='$date' and DATE_ADD(dExaFil, ".
            "INTERVAL 14 MONTH) >= '$date' AND DATE_ADD(dExaFil, INTERVAL 2 MONTH)<='$date') or (dExaPieds is not NULL and dExaPieds <='$date' and ".
            "DATE_ADD(dExaPieds, INTERVAL 14 MONTH) >= '$date' AND DATE_ADD(dExaFil, INTERVAL 2 MONTH)<='$date')) or ".
            "((dChol is not NULL and dChol<='$date' and DATE_ADD(dChol, INTERVAL 14 MONTH) >= '$date' AND ".
            "DATE_ADD(dChol, INTERVAL 2 MONTH)<='$date') ".
            " or (dLDL is not NULL and dLDL<='$date' and DATE_ADD(dLDL, INTERVAL 14 MONTH) >= '$date' AND ".
            "DATE_ADD(dLDL, INTERVAL 2 MONTH)<='$date')  ".
            "or (suivi_diabete.dCreat is not NULL and suivi_diabete.dCreat<='$date' and DATE_ADD(suivi_diabete.dCreat, ".
            "INTERVAL 14 MONTH) >= '$date' AND DATE_ADD(suivi_diabete.dCreat, INTERVAL 2 MONTH)<='$date') or (dAlbu is not NULL ".
            "and dAlbu<='$date' and DATE_ADD(dAlbu, INTERVAL 14 MONTH) >= '$date' AND DATE_ADD(dAlbu, INTERVAL 2 MONTH)<='$date')".
            " or (dFond is not NULL and ".
            "dFond<='$date' and DATE_ADD(dFond, INTERVAL 14 MONTH) >= '$date' AND DATE_ADD(dFond, INTERVAL 2 MONTH)<='$date') or (dECG is not NULL and ".
            "dECG<='$date' and DATE_ADD(dECG, INTERVAL 14 MONTH) >= ".
            "'$date' AND DATE_ADD(dECG, INTERVAL 2 MONTH)<='$date'))) GROUP by dossier_id order by cabinet, numero";
        /*$req="SELECT cabinet, count(*) ".
                 "FROM suivi_diabete, dossier ".
                 "WHERE cabinet!='zTest' and cabinet!='irdes'  ".
                 "AND actif='oui' ".
                 "AND suivi_diabete.dossier_id=dossier.id ".
                 "and ((dsuivi is not NULL and DATE_ADD(dsuivi, ".
                 "INTERVAL 1 YEAR) >= CURDATE())) ".
                 "GROUP BY cabinet, dossier_id ".
                 "ORDER BY cabinet ";*/
        //echo $req;
        //die;

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");



        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]['hba']=0;
            $tpat[$cab]['exafil']=0;
            $tpat[$cab]['pied']=0;
            $tpat[$cab]['chol']=0;
            $tpat[$cab]['creat']=0;
            $tpat[$cab]['albu']=0;
            $tpat[$cab]['fond']=0;
            $tpat[$cab]['ecg']=0;
        }


        $tpat['tot']['hba']=0;
        $tpat['tot']['exafil']=0;
        $tpat['tot']['pied']=0;
        $tpat['tot']['chol']=0;
        $tpat['tot']['creat']=0;
        $tpat['tot']['albu']=0;
        $tpat['tot']['fond']=0;
        $tpat['tot']['ecg']=0;

        $tpat['eval']['hba']=0;
        $tpat['eval']['exafil']=0;
        $tpat['eval']['pied']=0;
        $tpat['eval']['chol']=0;
        $tpat['eval']['creat']=0;
        $tpat['eval']['albu']=0;
        $tpat['eval']['fond']=0;
        $tpat['eval']['ecg']=0;

        $tpat['eval2']['hba']=0;
        $tpat['eval2']['exafil']=0;
        $tpat['eval2']['pied']=0;
        $tpat['eval2']['chol']=0;
        $tpat['eval2']['creat']=0;
        $tpat['eval2']['albu']=0;
        $tpat['eval2']['fond']=0;
        $tpat['eval2']['ecg']=0;

        $tpat['eval3']['hba']=0;
        $tpat['eval3']['exafil']=0;
        $tpat['eval3']['pied']=0;
        $tpat['eval3']['chol']=0;
        $tpat['eval3']['creat']=0;
        $tpat['eval3']['albu']=0;
        $tpat['eval3']['fond']=0;
        $tpat['eval3']['ecg']=0;


        while(list($cabinet, $dossier_id, $dExaFil, $dExaPieds, $dChol, $dCreat, $dAlbu,
            $dFond, $dECG) = mysql_fetch_row($res)) {

            $req2="SELECT dHBA FROM suivi_diabete WHERE dossier_id='$dossier_id' AND DATE_ADD(dHBA, INTERVAL 14 MONTH)>='$date' ".
                "AND DATE_ADD(dHBA, INTERVAL 2 MONTH)<='$date'";

            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            if(mysql_num_rows($res2)>=3){
                if(isset($avjuin2005[$dossier_id]))
                {
                    $tpat['eval']['hba']=$tpat['eval']['hba']+1;
                }
                elseif(isset($avjuin2006[$dossier_id]))
                {
                    $tpat['eval2']['hba']=$tpat['eval2']['hba']+1;
                }
                elseif(isset($apjuin2006[$dossier_id]))
                {
                    $tpat['eval3']['hba']=$tpat['eval3']['hba']+1;
                }
            }

            if(($dExaFil!='')&&($dExaFil!='NULL'))
            {
//		 if(diffmois($dExaFil, $date)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['exafil']=$tpat['eval']['exafil']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['exafil']=$tpat['eval2']['exafil']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['exafil']=$tpat['eval3']['exafil']+1;
                    }
                }
            }

            if(($dExaPieds!='')&&($dExaPieds!='NULL'))
            {
//		 if(diffmois($dExaPieds, $date)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['pied']=$tpat['eval']['pied']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['pied']=$tpat['eval2']['pied']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['pied']=$tpat['eval3']['pied']+1;
                    }
                }
            }

            if(($dChol!='')&&($dChol!='NULL'))
            {
//		 if(diffmois($dChol, $date)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['chol']=$tpat['eval']['chol']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['chol']=$tpat['eval2']['chol']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['chol']=$tpat['eval3']['chol']+1;
                    }
                }
            }

            if(($dCreat!='')&&($dCreat!='NULL'))
            {
//		 if(diffmois($dCreat, $date)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['creat']=$tpat['eval']['creat']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['creat']=$tpat['eval2']['creat']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['creat']=$tpat['eval3']['creat']+1;
                    }
                }
            }

            if(($dAlbu!='')&&($dAlbu!='NULL'))
            {
//		 if(diffmois($dAlbu, $date)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['albu']=$tpat['eval']['albu']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['albu']=$tpat['eval2']['albu']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['albu']=$tpat['eval3']['albu']+1;
                    }
                }
            }

            if(($dFond!='')&&($dFond!='NULL'))
            {
//		 if(diffmois($dFond, $date)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['fond']=$tpat['eval']['fond']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['fond']=$tpat['eval2']['fond']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['fond']=$tpat['eval3']['fond']+1;
                    }
                }
            }

            if(($dECG!='')&&($dECG!='NULL'))
            {
//		 if(diffmois($dECG, $date)<=0)
                {
                    if(isset($avjuin2005[$dossier_id]))
                    {
                        $tpat['eval']['ecg']=$tpat['eval']['ecg']+1;
                    }
                    elseif(isset($avjuin2006[$dossier_id]))
                    {
                        $tpat['eval2']['ecg']=$tpat['eval2']['ecg']+1;
                    }
                    elseif(isset($apjuin2006[$dossier_id]))
                    {
                        $tpat['eval3']['ecg']=$tpat['eval3']['ecg']+1;
                    }
                }
            }


        }


        ?>

        <tr>
            <td>Taux de respect des examens &nbsp;</td>
            <td align="center"> <b>&nbsp;Total</b>	 &nbsp;</td>
            <td align="center"> <b>&nbsp;Patients inclus avant le 30/06/2005</b>	 &nbsp;</td>
            <td align="center"> <b>&nbsp;Patients inclus entre le 01/07/2005 et le 30/06/2006</b>	 &nbsp;</td>
            <td align="center"> <b>&nbsp;Patients inclus après le 30/06/2006</b>	 &nbsp;</td>


            <?php



            ?>
        </tr>
        <?php

        $t_diab['total']=$t_diab['eval']+$t_diab['eval2']+$t_diab['eval3'];
        $taux_hba['total']=round(($tpat['eval']['hba']+$tpat['eval2']['hba']+$tpat['eval3']['hba'])/$t_diab['total']*100);
        $taux_hba['total'].="%";

        $taux_exafil['total']=round(($tpat['eval']['exafil']+$tpat['eval2']['exafil']+$tpat['eval3']['exafil'])/$t_diab['total']*100);
        $taux_exafil['total'].="%";

        $taux_pied['total']=round(($tpat['eval']['pied']+$tpat['eval2']['pied']+$tpat['eval3']['pied'])/$t_diab['total']*100);
        $taux_pied['total'].="%";

        $taux_chol['total']=round(($tpat['eval']['chol']+$tpat['eval2']['chol']+$tpat['eval3']['chol'])/$t_diab['total']*100);
        $taux_chol['total'].="%";

        $taux_creat['total']=round(($tpat['eval']['creat']+$tpat['eval2']['creat']+$tpat['eval3']['creat'])/$t_diab['total']*100);
        $taux_creat['total'].="%";

        $taux_albu['total']=round(($tpat['eval']['albu']+$tpat['eval2']['albu']+$tpat['eval3']['albu'])/$t_diab['total']*100);
        $taux_albu['total'].="%";

        $taux_fond['total']=round(($tpat['eval']['fond']+$tpat['eval2']['fond']+$tpat['eval3']['fond'])/$t_diab['total']*100);
        $taux_fond['total'].="%";

        $taux_ecg['total']=round(($tpat['eval']['ecg']+$tpat['eval2']['ecg']+$tpat['eval3']['ecg'])/$t_diab['total']*100);
        $taux_ecg['total'].="%";

        if ($t_diab['eval']==0)
        {
            $taux_hba['eval']=$taux_exafil['eval']=$taux_pied['eval']=$taux_chol['eval']=$taux_creat['eval']='ND';
            $taux_albu['eval']=$taux_fond['eval']=$taux_ecg['eval']="ND";
        }
        else
        {
            $taux_hba['eval']=round($tpat['eval']['hba']/$t_diab['eval']*100);
            $taux_hba['eval'].="%";

            $taux_exafil['eval']=round($tpat['eval']['exafil']/$t_diab['eval']*100);
            $taux_exafil['eval'].="%";

            $taux_pied['eval']=round($tpat['eval']['pied']/$t_diab['eval']*100);
            $taux_pied['eval'].="%";

            $taux_chol['eval']=round($tpat['eval']['chol']/$t_diab['eval']*100);
            $taux_chol['eval'].="%";

            $taux_creat['eval']=round($tpat['eval']['creat']/$t_diab['eval']*100);
            $taux_creat['eval'].="%";

            $taux_albu['eval']=round($tpat['eval']['albu']/$t_diab['eval']*100);
            $taux_albu['eval'].="%";

            $taux_fond['eval']=round($tpat['eval']['fond']/$t_diab['eval']*100);
            $taux_fond['eval'].="%";

            $taux_ecg['eval']=round($tpat['eval']['ecg']/$t_diab['eval']*100);
            $taux_ecg['eval'].="%";
        }


        if ($t_diab['eval2']==0)
        {
            $taux_hba['eval2']=$taux_exafil['eval2']=$taux_pied['eval2']=$taux_chol['eval2']=$taux_creat['eval2']='ND';
            $taux_albu['eval2']=$taux_fond['eval2']=$taux_ecg['eval2']="ND";
        }
        else
        {
            $taux_hba['eval2']=round($tpat['eval2']['hba']/$t_diab['eval2']*100);
            $taux_hba['eval2'].="%";

            $taux_exafil['eval2']=round($tpat['eval2']['exafil']/$t_diab['eval2']*100);
            $taux_exafil['eval2'].="%";

            $taux_pied['eval2']=round($tpat['eval2']['pied']/$t_diab['eval2']*100);
            $taux_pied['eval2'].="%";

            $taux_chol['eval2']=round($tpat['eval2']['chol']/$t_diab['eval2']*100);
            $taux_chol['eval2'].="%";

            $taux_creat['eval2']=round($tpat['eval2']['creat']/$t_diab['eval2']*100);
            $taux_creat['eval2'].="%";

            $taux_albu['eval2']=round($tpat['eval2']['albu']/$t_diab['eval2']*100);
            $taux_albu['eval2'].="%";

            $taux_fond['eval2']=round($tpat['eval2']['fond']/$t_diab['eval2']*100);
            $taux_fond['eval2'].="%";

            $taux_ecg['eval2']=round($tpat['eval2']['ecg']/$t_diab['eval2']*100);
            $taux_ecg['eval2'].="%";
        }


        if ($t_diab['eval3']==0)
        {
            $taux_hba['eval3']=$taux_exafil['eval3']=$taux_pied['eval3']=$taux_chol['eval3']=$taux_creat['eval3']='ND';
            $taux_albu['eval3']=$taux_fond['eval3']=$taux_ecg['eval3']="ND";
        }
        else
        {
            $taux_hba['eval3']=round($tpat['eval3']['hba']/$t_diab['eval3']*100);
            $taux_hba['eval3'].="%";

            $taux_exafil['eval3']=round($tpat['eval3']['exafil']/$t_diab['eval3']*100);
            $taux_exafil['eval3'].="%";

            $taux_pied['eval3']=round($tpat['eval3']['pied']/$t_diab['eval3']*100);
            $taux_pied['eval3'].="%";

            $taux_chol['eval3']=round($tpat['eval3']['chol']/$t_diab['eval3']*100);
            $taux_chol['eval3'].="%";

            $taux_creat['eval3']=round($tpat['eval3']['creat']/$t_diab['eval3']*100);
            $taux_creat['eval3'].="%";

            $taux_albu['eval3']=round($tpat['eval3']['albu']/$t_diab['eval3']*100);
            $taux_albu['eval3'].="%";

            $taux_fond['eval3']=round($tpat['eval3']['fond']/$t_diab['eval3']*100);
            $taux_fond['eval3'].="%";

            $taux_ecg['eval3']=round($tpat['eval3']['ecg']/$t_diab['eval3']*100);
            $taux_ecg['eval3'].="%";
        }

        ?>
        <tr>
            <td>3 HBA1c<sup>1</sup></td>
            <td align='right'><?php echo $taux_hba['total']; ?></td>
            <td align='right'><?php echo $taux_hba['eval']; ?></td>
            <td align='right'><?php echo $taux_hba['eval2']; ?></td>
            <td align='right'><?php echo $taux_hba['eval3']; ?></td>
        </tr>
        <tr>
            <td>Examen au monofilament<sup>2</sup></td>
            <td align='right'><?php echo $taux_exafil['total']; ?></td>
            <td align='right'><?php echo $taux_exafil['eval']; ?></td>
            <td align='right'><?php echo $taux_exafil['eval2']; ?></td>
            <td align='right'><?php echo $taux_exafil['eval3']; ?></td>
        </tr>
        <tr>
            <td>Examen des pieds<sup>3</sup></td>
            <td align='right'><?php echo $taux_pied['total']; ?></td>
            <td align='right'><?php echo $taux_pied['eval']; ?></td>
            <td align='right'><?php echo $taux_pied['eval2']; ?></td>
            <td align='right'><?php echo $taux_pied['eval3']; ?></td>
        </tr>
        <tr>
            <td>Dosage du HDL - Cholestérol<sup>4</sup></td>
            <td align='right'><?php echo $taux_chol['total']; ?></td>
            <td align='right'><?php echo $taux_chol['eval']; ?></td>
            <td align='right'><?php echo $taux_chol['eval2']; ?></td>
            <td align='right'><?php echo $taux_chol['eval3']; ?></td>
        </tr>
        <tr>
            <td>Créatinémie<sup>5</sup></td>
            <td align='right'><?php echo $taux_creat['total']; ?></td>
            <td align='right'><?php echo $taux_creat['eval']; ?></td>
            <td align='right'><?php echo $taux_creat['eval2']; ?></td>
            <td align='right'><?php echo $taux_creat['eval3']; ?></td>
        </tr>
        <tr>
            <td>Micro Albuminurie<sup>6</sup></td>
            <td align='right'><?php echo $taux_albu['total']; ?></td>
            <td align='right'><?php echo $taux_albu['eval']; ?></td>
            <td align='right'><?php echo $taux_albu['eval2']; ?></td>
            <td align='right'><?php echo $taux_albu['eval3']; ?></td>
        </tr>
        <tr>
            <td>Fond d'oeil<sup>7</sup></td>
            <td align='right'><?php echo $taux_fond['total']; ?></td>
            <td align='right'><?php echo $taux_fond['eval']; ?></td>
            <td align='right'><?php echo $taux_fond['eval2']; ?></td>
            <td align='right'><?php echo $taux_fond['eval3']; ?></td>
        </tr>
        <tr>
            <td>ECG<sup>8</sup></td>
            <td align='right'><?php echo $taux_ecg['total']; ?></td>
            <td align='right'><?php echo $taux_ecg['eval']; ?></td>
            <td align='right'><?php echo $taux_ecg['eval2']; ?></td>
            <td align='right'><?php echo $taux_ecg['eval3']; ?></td>
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
