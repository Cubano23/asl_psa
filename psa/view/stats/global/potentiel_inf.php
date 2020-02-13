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
    <title>Potentiel par infirmière</title>
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

entete_asalee("Potentiel par infirmière");
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
<font face='times new roman'>Potentiel par infirmière</font></i>";
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


    # étape 1 : tableau à la date du jour
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1://tableau à la date du jour
                etape_1($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//Tableau à la date du jour
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tinfirm;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    $req="SELECT infirmiere, total_pat, total_sein, total_cogni, total_colon, total_uterus, total_diab2, total_HTA, cabinet ".
        "FROM account WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='jgomes' and cabinet!='saint-varent' ".
        " and cabinet!='ergo' and cabinet!='sbirault' and region!='' ORDER BY infirmiere";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tinfirm=array();

    $inf_pre="";

    $tot_pat['tot']=$tot_sein['tot']=$tot_cogni['tot']=$tot_colon['tot']=$tot_uterus['tot']=$tot_diab['tot']=$tot_HTA['tot']=0;
    while(list($infirmiere, $total_pat, $total_sein, $total_cogni, $total_colon, $total_uterus, $total_diab2, $total_HTA, $cabinet)
        =mysql_fetch_row($res))
    {
        $tot_pat['tot']=$tot_pat['tot']+$total_pat;
        $tot_sein['tot']=$tot_sein['tot']+$total_sein;
        $tot_cogni['tot']=$tot_cogni['tot']+$total_cogni;
        $tot_colon['tot']=$tot_colon['tot']+$total_colon;
        $tot_uterus['tot']=$tot_uterus['tot']+$total_uterus;
        $tot_diab['tot']=$tot_diab['tot']+$total_diab2;
        $tot_HTA['tot']=$tot_HTA['tot']+$total_HTA;

        if($inf_pre!=$infirmiere)
        {
            $tinfirm[]=$infirmiere;
            $tot_pat[$infirmiere]=$total_pat;
            $tot_sein[$infirmiere]=$total_sein;
            $tot_cogni[$infirmiere]=$total_cogni;
            $tot_colon[$infirmiere]=$total_colon;
            $tot_uterus[$infirmiere]=$total_uterus;
            $tot_diab[$infirmiere]=$total_diab2;
            $tot_HTA[$infirmiere]=$total_HTA;
            $inf_pre=$infirmiere;
        }
        else
        {
            $tot_pat[$infirmiere]=$tot_pat[$infirmiere]+$total_pat;
            $tot_sein[$infirmiere]=$tot_sein[$infirmiere]+$total_sein;
            $tot_cogni[$infirmiere]=$tot_cogni[$infirmiere]+$total_cogni;
            $tot_colon[$infirmiere]=$tot_colon[$infirmiere]+$total_colon;
            $tot_uterus[$infirmiere]=$tot_uterus[$infirmiere]+$total_uterus;
            $tot_diab[$infirmiere]=$tot_diab[$infirmiere]+$total_diab2;
            $tot_HTA[$infirmiere]=$tot_HTA[$infirmiere]+$total_HTA;
        }
    }

    ?>
    <table border='1' width='1000'>
        <tr>
            <td><b>Nombre total de patient(e)s éligibles</b></td><td align= 'center'><b>Total</b></td>
            <?php
            foreach($tinfirm as $inf)
            {
                echo "<td align='center'><b>$inf</b></td>";
            }
            ?>
        </tr>
        <tr>
            <td>Nombre total de patients <sup>1</sup></td>
            <td align='right' nowrap><?php echo number_format($tot_pat['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                echo "<td align='right'>".number_format($tot_pat[$inf], 0, '.', ' ')."</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Dépistage du cancer du sein <sup>2</sup></td>
            <td align='right' nowrap><?php echo number_format($tot_sein['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                echo "<td align='right' nowrap>".number_format($tot_sein[$inf], 0, '.', ' ')."</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Dépistage des troubles cognitifs <sup>3</sup></td>
            <td align='right' nowrap><?php echo number_format($tot_cogni['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                echo "<td align='right' nowrap>".number_format($tot_cogni[$inf], 0, '.', ' ')."</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Dépistage du cancer du colon <sup>4</sup></td>
            <td align='right' nowrap><?php echo number_format($tot_colon['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                echo "<td align='right' nowrap>".number_format($tot_colon[$inf], 0, '.', ' ')."</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Dépistage du cancer du col de l'utérus <sup>5</sup></td>
            <td align='right' nowrap><?php echo number_format($tot_uterus['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                echo "<td align='right' nowrap>".number_format($tot_uterus[$inf], 0, '.', ' ')."</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Diabétiques de type 2 <sup>6</sup></td>
            <td align='right' nowrap><?php echo number_format($tot_diab['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                echo "<td align='right' nowrap>".number_format($tot_diab[$inf], 0, '.', ' ')."</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Suivi RVCA <sup>7</sup></td>
            <td align='right' nowrap><?php echo number_format($tot_HTA['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                echo "<td align='right' nowrap>".number_format($tot_HTA[$inf], 0, '.', ' ')."</td>";
            }
            ?>
        </tr>
    </table>



    <br><br>
    <?php
    $annee0=2006;
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
    <table border=0>
        <tr>
            <td><br></td>
        </tr>
        <tr>
            <td><sup>1</sup> Nombre de patients dont un des médecins du cabinet est médecin traitant</td>
        </tr>
        <tr>
            <td><sup>2</sup> Femmes de 50 à 74 ans sans facteur de risque et dont un des médecins du cabinet est médecin traitant</td>
        </tr>
        <tr>
            <td><sup>3</sup> Patients de plus de 75 ans vivant à domicile + patients proposés par les médecins</td>
        </tr>
        <tr>
            <td><sup>4</sup> Patients entre 50 et 74 ans sans facteur de risques</td>
        </tr>
        <tr>
            <td><sup>5</sup> Patientes de 20 à 65 ans sauf patientes à haut risque ni hystérectomisées ni vierges</td>
        </tr>
        <tr>
            <td><sup>6</sup> Nombre de diabétiques de type 2 dont un des médecins est médecin traitant</td>
        </tr>
        <tr>
            <td><sup>7</sup> Nombre femmes éligibles au suivi RVCA dont un des médecins est médecin traitant</td>
        </tr>
    </table>
    <?php
}

//Affichage des arrêtés trimestriels
function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tinfirm;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=explode('-', $date);  // EA split par explode   26-12-2014

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";


    $req="SELECT infirmiere, histo_account.total_pat, histo_account.total_sein, histo_account.total_cogni, ".
        "histo_account.total_colon, histo_account.total_uterus, histo_account.total_diab2, histo_account.total_HTA, account.cabinet ".
        "FROM histo_account, account WHERE account.cabinet!='zTest' and account.cabinet!='irdes'   ".
        "and account.cabinet!='sbirault' and account.cabinet!='jgomes' and account.cabinet!='ergo' and ".
        "account.cabinet=histo_account.cabinet and histo_account.dmaj<='$date 23:59:59' and region!=''";

    if($date>"2008-01-01"){
        $req.=" AND account.cabinet!='saint-varent' ";
    }
    $req.="ORDER BY infirmiere, ".
        "account.cabinet, histo_account.dmaj";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }

    foreach($tinfirm as $infirmiere)
    {
        $tot_pat[$infirmiere]=0;
        $tot_sein[$infirmiere]=0;
        $tot_cogni[$infirmiere]=0;
        $tot_colon[$infirmiere]=0;
        $tot_uterus[$infirmiere]=0;
        $tot_diab[$infirmiere]=0;
        $tot_HTA[$infirmiere]=0;

    }
    $inf_pre="";

    $tot_pat['tot']=$tot_sein['tot']=$tot_cogni['tot']=$tot_colon['tot']=$tot_uterus['tot']=$tot_diab['tot']=$tot_HTA['tot']=0;
    while(list($infirmiere, $total_pat, $total_sein, $total_cogni, $total_colon, $total_uterus, $total_diab2, $total_HTA, $cabinet)
        =mysql_fetch_row($res))
    {

        if(($inf_pre!=$infirmiere)&&($inf_pre==''))
        {
//	    $tinfirm[]=$infirmiere;
            $total_pat_sav=$total_pat;
            $total_sein_sav=$total_sein;
            $total_cogni_sav=$total_cogni;
            $total_colon_sav=$total_colon;
            $total_uterus_sav=$total_uterus;
            $total_diab2_sav=$total_diab2;
            $total_HTA_sav=$total_HTA;
            $inf_pre=$infirmiere;
            $cab_pre=$cabinet;
        }
        elseif(($inf_pre!=$infirmiere)&&($inf_pre!=''))
        {

            $tot_pat[$inf_pre]=$tot_pat[$inf_pre]+$total_pat_sav;
            $tot_sein[$inf_pre]=$tot_sein[$inf_pre]+$total_sein_sav;
            $tot_cogni[$inf_pre]=$tot_cogni[$inf_pre]+$total_cogni_sav;
            $tot_colon[$inf_pre]=$tot_colon[$inf_pre]+$total_colon_sav;
            $tot_uterus[$inf_pre]=$tot_uterus[$inf_pre]+$total_uterus_sav;
            $tot_diab[$inf_pre]=$tot_diab[$inf_pre]+$total_diab2_sav;
            $tot_HTA[$inf_pre]=$tot_HTA[$inf_pre]+$total_HTA_sav;

            $total_pat_sav=$total_pat;
            $total_sein_sav=$total_sein;
            $total_cogni_sav=$total_cogni;
            $total_colon_sav=$total_colon;
            $total_uterus_sav=$total_uterus;
            $total_diab2_sav=$total_diab2;
            $total_HTA_sav=$total_HTA;

            $inf_pre=$infirmiere;
            $cab_pre=$cabinet;
        }
        else
        {
            if($cab_pre==$cabinet)
            {
                $total_pat_sav=$total_pat;
                $total_sein_sav=$total_sein;
                $total_cogni_sav=$total_cogni;
                $total_colon_sav=$total_colon;
                $total_uterus_sav=$total_uterus;
                $total_diab2_sav=$total_diab2;
                $total_HTA_sav=$total_HTA;


            }
            else
            {
                $tot_pat[$infirmiere]=$tot_pat[$infirmiere]+$total_pat_sav;
                $tot_sein[$infirmiere]=$tot_sein[$infirmiere]+$total_sein_sav;
                $tot_cogni[$infirmiere]=$tot_cogni[$infirmiere]+$total_cogni_sav;
                $tot_colon[$infirmiere]=$tot_colon[$infirmiere]+$total_colon_sav;
                $tot_uterus[$infirmiere]=$tot_uterus[$infirmiere]+$total_uterus_sav;
                $tot_diab[$infirmiere]=$tot_diab[$infirmiere]+$total_diab2_sav;
                $tot_HTA[$infirmiere]=$tot_HTA[$infirmiere]+$total_HTA_sav;


                $total_pat_sav=$total_pat;
                $total_sein_sav=$total_sein;
                $total_cogni_sav=$total_cogni;
                $total_colon_sav=$total_colon;
                $total_uterus_sav=$total_uterus;
                $total_diab2_sav=$total_diab2;
                $total_HTA_sav=$total_HTA;
                $cab_pre=$cabinet;
            }
        }
    }

    $tot_pat[$inf_pre]=$tot_pat[$inf_pre]+$total_pat_sav;
    $tot_sein[$inf_pre]=$tot_sein[$inf_pre]+$total_sein_sav;
    $tot_cogni[$inf_pre]=$tot_cogni[$inf_pre]+$total_cogni_sav;
    $tot_colon[$inf_pre]=$tot_colon[$inf_pre]+$total_colon_sav;
    $tot_uterus[$inf_pre]=$tot_uterus[$inf_pre]+$total_uterus_sav;
    $tot_diab[$inf_pre]=$tot_diab[$inf_pre]+$total_diab2_sav;
    $tot_HTA[$inf_pre]=$tot_HTA[$inf_pre]+$total_HTA_sav;


    foreach($tinfirm as $inf)
    {
        $tot_pat['tot']=$tot_pat['tot']+$tot_pat[$inf];
        $tot_sein['tot']=$tot_sein['tot']+$tot_sein[$inf];
        $tot_cogni['tot']=$tot_cogni['tot']+$tot_cogni[$inf];
        $tot_colon['tot']=$tot_colon['tot']+$tot_colon[$inf];
        $tot_uterus['tot']=$tot_uterus['tot']+$tot_uterus[$inf];
        $tot_diab['tot']=$tot_diab['tot']+$tot_diab[$inf];
        $tot_HTA['tot']=$tot_HTA['tot']+$tot_HTA[$inf];
    }

    ?>
    <table border='1' width='1000'>
        <tr>
            <td><b>Nombre total de patient(e)s éligibles</b></td><td align= 'center'><b>Total</b></td>
            <?php
            foreach($tinfirm as $inf)
            {
                echo "<td align='center'><b>$inf</b></td>";
            }
            ?>
        </tr>
        <tr>
            <td>Nombre total de patients <sup>1</sup></td>
            <td align='right' nowrap><?php echo number_format($tot_pat['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                if($tot_pat[$inf]==0)
                {
                    echo '<td align="right" nowrap> ND</td>';
                }
                else
                {
                    echo "<td align='right' nowrap>".number_format($tot_pat[$inf], 0, '.', ' ')."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Dépistage du cancer du sein <sup>2</sup></td>
            <td align='right' nowrap><?php echo number_format($tot_sein['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                if($tot_sein[$inf]==0)
                {
                    echo '<td align="right"> ND</td>';
                }
                else
                {
                    echo "<td align='right' nowrap>".number_format($tot_sein[$inf], 0, '.', ' ')."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Dépistage des troubles cognitifs <sup>3</sup></td>
            <td align='right' nowrap><?php echo number_format($tot_cogni['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                if($tot_cogni[$inf]==0)
                {
                    echo '<td align="right"> ND</td>';
                }
                else
                {
                    echo "<td align='right' nowrap>".number_format($tot_cogni[$inf], 0, '.', ' ')."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Dépistage du cancer du colon <sup>4</sup></td>
            <td align='right'><?php echo number_format($tot_colon['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                if($tot_colon[$inf]==0)
                {
                    echo '<td align="right"> ND</td>';
                }
                else
                {
                    echo "<td align='right' nowrap>".number_format($tot_colon[$inf], 0, '.', ' ')."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Dépistage du cancer du col de l'utérus <sup>5</sup></td>
            <td align='right' nowrap><?php echo number_format($tot_uterus['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                if($tot_uterus[$inf]==0)
                {
                    echo '<td align="right"> ND</td>';
                }
                else
                {
                    echo "<td align='right' nowrap>".number_format($tot_uterus[$inf], 0, '.', ' ')."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Diabétiques de type 2 <sup>6</sup></td>
            <td align='right' nowrap><?php echo number_format($tot_diab['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                if($tot_diab[$inf]==0)
                {
                    echo '<td align="right"> ND</td>';
                }
                else
                {
                    echo "<td align='right' nowrap>".number_format($tot_diab[$inf], 0, '.', ' ')."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Suivi RVCA <sup>7</sup></td>
            <td align='right' nowrap><?php echo number_format($tot_HTA['tot'], 0, '.', ' '); ?></td>
            <?php
            foreach($tinfirm as $inf)
            {
                if($tot_HTA[$inf]==0)
                {
                    echo '<td align="right"> ND</td>';
                }
                else
                {
                    echo "<td align='right' nowrap>".number_format($tot_HTA[$inf], 0, '.', ' ')."</td>";
                }
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
