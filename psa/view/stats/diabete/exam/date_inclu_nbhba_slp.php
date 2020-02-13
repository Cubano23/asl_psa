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
    <title>Tenue des examens HBA1c sur la période
        <?if(isset($_GET['cabinet']))
        {
            echo ' pour le cabinet '.$_GET['cabinet'];
        }
        ?>
    </title>
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

$titre="Tenue des examens HBA1c sur la période";

if(isset($_GET['cabinet']))
{
    $req="SELECT nom_cab FROM account where cabinet='".$_GET['cabinet']."'";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    list($nom_cab)=mysql_fetch_row($res);
    $titre.=' pour le cabinet '.$nom_cab;
}

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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet;


    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */

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
    <table border=1 width='100%'>

        <?php

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, id, dsuivi, dHBA ".
            "FROM suivi_diabete, dossier ".
            "WHERE ";
        if(isset($_GET['cabinet']))
        {
            $req.="cabinet='".$_GET['cabinet']."' ";
        }
        else
        {
            $req.="cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' and cabinet!='sbirault' ";
        }

        $req.="AND actif='oui' ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "ORDER BY cabinet, id, dsuivi ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        /*foreach ($tcabinet as $cab)
        {
            $tpat[$cab][0]=0;
            $tpat[$cab][4]=0;
            $tpat[$cab][8]=0;
            $tpat[$cab][12]=0;
            $tpat[$cab]['total']=0;
        }
        */
        for($i=0; $i<=10;$i++)
        {
            $tpat['tot'][0][$i]=0;
            $tpat['tot'][4][$i]=0;
            $tpat['tot'][8][$i]=0;
            $tpat['tot'][12][$i]=0;
            $tpat['tot'][16][$i]=0;
            $tpat['tot'][20][$i]=0;
            $tpat['tot'][24][$i]=0;

        }

        $total[0]=$total[4]=$total[8]=$total[12]=$total[16]=$total[20]=$total[24]=0;

        $id_prec='';
        $max_hba=0;

        while(list($cab, $id, $dsuivi, $dHBA) = mysql_fetch_row($res)) {
            if($id_prec!=$id)
            {

                if($id_prec!='')
                {
                    if($nb_hba_id>$max_hba)
                    {
                        $max_hba=$nb_hba_id;
                    }
                    $tpat['tot'][$entree][$nb_hba_id]=$tpat['tot'][$entree][$nb_hba_id]+1;
                    $id_prec=$id;

                    $nb_mois=diffmois($dsuivi);

                    if($nb_mois<4)
                    {
                        $entree=0;
                    }
                    elseif(($nb_mois>=4)&&($nb_mois<8))
                    {
                        $entree=4;
                    }
                    elseif(($nb_mois>=8)&&($nb_mois<12))
                    {
                        $entree=8;
                    }
                    elseif(($nb_mois>=12)&&($nb_mois<16))
                    {
                        $entree=12;
                    }
                    elseif(($nb_mois>=16)&&($nb_mois<20))
                    {
                        $entree=16;
                    }
                    elseif(($nb_mois>=20)&&($nb_mois<24))
                    {
                        $entree=20;
                    }
                    else
                    {
                        $entree=24;
                    }

                    if(($dHBA!='')&&($dHBA!='0000-00-00')&&($dHBA!='NULL')&&(diffmois($dHBA)<=$entree+4))
                    {
                        $nb_hba_id=1;

                    }
                    else
                    {
                        $nb_hba_id=0;
                    }
                    $total[$entree]=$total[$entree]+1;
                    $dinclu=$dsuivi;

                }
                else
                {

                    $nb_mois=diffmois($dsuivi);

                    if($nb_mois<4)
                    {
                        $entree=0;
                    }
                    elseif(($nb_mois>=4)&&($nb_mois<8))
                    {
                        $entree=4;
                    }
                    elseif(($nb_mois>=8)&&($nb_mois<12))
                    {
                        $entree=8;
                    }
                    elseif(($nb_mois>=12)&&($nb_mois<16))
                    {
                        $entree=12;
                    }
                    elseif(($nb_mois>=16)&&($nb_mois<20))
                    {
                        $entree=16;
                    }
                    elseif(($nb_mois>=20)&&($nb_mois<24))
                    {
                        $entree=20;
                    }
                    else
                    {
                        $entree=24;
                    }
                    $id_prec=$id;

                    if(($dHBA!='')&&($dHBA!='0000-00-00')&&($dHBA!='NULL')&&(diffmois($dHBA)<=$entree+4))
                    {
                        $nb_hba_id=1;
                    }
                    else
                    {
                        $nb_hba_id=0;
                    }

                    $total[$entree]=$total[$entree]+1;
                    $dinclu=$dsuivi;

                }
            }
            else
            {
                if(($dHBA!='')&&($dHBA!='0000-00-00')&&($dHBA!='NULL')&&(diffmois($dHBA)<=$entree+4))
                {
                    $nb_hba_id++;
                }

            }


        }

        $tpat['tot'][$entree][$nb_hba_id]=$tpat['tot'][$entree][$nb_hba_id]+1;

        ?>

        <tr>
            <td rowspan='2' width='20%'>Nombre de dosage HBA1c réalisés sur la période</td>
            <td colspan='7' align='center'>Durée de suivi des patients</td>
        </tr>
        <tr>
            <td align='center'>< 4 mois <sup>1</sup></td>
            <td align='center'>[4 - 8[ mois <sup>2</sup></td>
            <td align='center'>[8 - 12 [ mois <sup>3</sup></td>
            <td align='center'>[12 - 16 [mois <sup>4</sup></td>
            <td align='center'>[16 - 20 [mois <sup>5</sup></td>
            <td align='center'>[20 - 24 [mois <sup>6</sup></td>
            <td align='center'>>= 24 mois <sup>7</sup></td>
        </tr>
        <?php

        $pc_tot[0]=$pc_tot[4]=$pc_tot[8]=$pc_tot[12]=$pc_tot[16]=$pc_tot[20]=$pc_tot[24]=0;


        for($i=0; $i<=$max_hba; $i++)
        {
            if($total[0]>0)
            {
                $pc_tot[0]+=$tpat['tot'][0][$i]/$total[0]*100;
            }
            else
            {
                $pc_tot[0]='ND';
            }
            if($total[4]>0)
            {
                $pc_tot[4]+=$tpat['tot'][4][$i]/$total[4]*100;
            }
            else
            {
                $pc_tot[4]='ND';
            }
            if($total[8]>0)
            {
                $pc_tot[8]+=$tpat['tot'][8][$i]/$total[8]*100;
            }
            else
            {
                $pc_tot[8]='ND';
            }
            if($total[12]>0)
            {
                $pc_tot[12]+=$tpat['tot'][12][$i]/$total[12]*100;
            }
            else
            {
                $pc_tot[12]='ND';
            }
            if($total[16]>0)
            {
                $pc_tot[16]+=$tpat['tot'][16][$i]/$total[16]*100;
            }
            else
            {
                $pc_tot[16]='ND';
            }
            if($total[20]>0)
            {
                $pc_tot[20]+=$tpat['tot'][20][$i]/$total[20]*100;
            }
            else
            {
                $pc_tot[20]='ND';
            }
            if($total[24]>0)
            {
                $pc_tot[24]+=$tpat['tot'][24][$i]/$total[24]*100;
            }
            else
            {
                $pc_tot[24]='ND';
            }

            if($total[0]>0)
            {
                $pc[0][$i]=round($tpat['tot'][0][$i]/$total[0]*100, 1);
            }
            else
            {
                $pc[0][$i]='ND';
            }
            if($total[4]>0)
            {
                $pc[4][$i]=round($tpat['tot'][4][$i]/$total[4]*100, 1);
            }
            else
            {
                $pc[4][$i]='ND';
            }
            if($total[8]>0)
            {
                $pc[8][$i]=round($tpat['tot'][8][$i]/$total[8]*100, 1);
            }
            else
            {
                $pc[8][$i]='ND';
            }
            if($total[12]>0)
            {
                $pc[12][$i]=round($tpat['tot'][12][$i]/$total[12]*100, 1);
            }
            else
            {
                $pc[12][$i]='ND';
            }
            if($total[16]>0)
            {
                $pc[16][$i]=round($tpat['tot'][16][$i]/$total[16]*100, 1);
            }
            else
            {
                $pc[16][$i]='ND';
            }
            if($total[20]>0)
            {
                $pc[20][$i]=round($tpat['tot'][20][$i]/$total[20]*100, 1);
            }
            else
            {
                $pc[20][$i]='ND';
            }
            if($total[24]>0)
            {
                $pc[24][$i]=round($tpat['tot'][24][$i]/$total[24]*100, 1);
            }
            else
            {
                $pc[24][$i]='ND';
            }

            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td align='right'><?php echo number_format($pc[0][$i], 1, ',', ' '); ?>%</td>
                <td align='right'><?php echo number_format($pc[4][$i], 1, ',', ' '); ?>%</td>
                <td align='right'><?php echo number_format($pc[8][$i], 1, ',', ' '); ?>%</td>
                <td align='right'><?php echo number_format($pc[12][$i], 1, ',', ' '); ?>%</td>
                <td align='right'><?php echo number_format($pc[16][$i], 1, ',', ' '); ?>%</td>
                <td align='right'><?php echo number_format($pc[20][$i], 1, ',', ' '); ?>%</td>
                <td align='right'><?php echo number_format($pc[24][$i], 1, ',', ' '); ?>%</td>

            </tr>

            <?php

        }
        ?>
        <tr>
            <td>Total : </td>
            <td align='right'><?php echo number_format($pc_tot[0], 1, ',', ' ');?>%</td>
            <td align='right'><?php echo number_format($pc_tot[4], 1, ',', ' ');?>%</td>
            <td align='right'><?php echo number_format($pc_tot[8], 1, ',', ' ');?>%</td>
            <td align='right'><?php echo number_format($pc_tot[12], 1, ',', ' ');?>%</td>
            <td align='right'><?php echo number_format($pc_tot[16], 1, ',', ' ');?>%</td>
            <td align='right'><?php echo number_format($pc_tot[20], 1, ',', ' ');?>%</td>
            <td align='right'><?php echo number_format($pc_tot[24], 1, ',', ' ');?>%</td>

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

    <sup>1</sup>Proportion de personnes ayant eu 1, 2, 3... dosages du HBA1c réalisés depuis la date d'intégration pour les personnes intégrées il y a moins de 4 mois<br>
    <sup>2</sup>Proportion de personnes ayant eu 1, 2, 3... dosages du HBA1c réalisés depuis la date d'intégration pour personnes intégrées entre 4 et 8 mois<br>
    <sup>3</sup>Proportion de personnes ayant eu 1, 2, 3... dosages du HBA1c réalisés depuis la date d'intégration pour les personnes intégrées entre 8 et 12 mois<br>
    <sup>4</sup>Proportion de personnes ayant eu 1, 2, 3... dosages du HBA1c réalisés depuis la date d'intégration pour les personnes intégrées entre 12 et 16 mois<br>
    <sup>5</sup>Proportion de personnes ayant eu 1, 2, 3... dosages du HBA1c réalisés depuis la date d'intégration pour les personnes intégrées entre 16 et 20 mois<br>
    <sup>6</sup>Proportion de personnes ayant eu 1, 2, 3... dosages du HBA1c réalisés depuis la date d'intégration pour les personnes intégrées entre 20 et 24 mois<br>
    <sup>7</sup>Proportion de personnes ayant eu 1, 2, 3... dosages du HBA1c réalisés depuis la date d'intégration pour les personnes intégrées il y a plus de 24 mois<br>
    <?


}

function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */
    $tcabinet_util=1;

    if(isset($_GET['cabinet']))
    {
        $req="SELECT cabinet, count(*) ".
            "FROM dossier ".
            "WHERE cabinet='".$_GET['cabinet']."'  ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "GROUP BY cabinet ".
            "ORDER BY cabinet, numero ";
//echo $req;
//die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        if (mysql_num_rows($res)==0) {
            $tcabinet_util=0;
        }

    }


    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, id, dsuivi, dHBA ".
            "FROM suivi_diabete, dossier ".
            "WHERE ";

        if(isset($_GET['cabinet']))
        {
            $req.="cabinet='".$_GET['cabinet']."' ";
        }
        else
        {
            $req.="	cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo' and cabinet!='jgomes' and cabinet!='sbirault' ";
        }

        $req.= "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "and dHBA<='$date' ".
            "ORDER BY cabinet, id, dsuivi ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        /*foreach ($tcabinet as $cab)
        {
            $tpat[$cab][0]=0;
            $tpat[$cab][4]=0;
            $tpat[$cab][8]=0;
            $tpat[$cab][12]=0;
            $tpat[$cab]['total']=0;
        }
        */
        for($i=0; $i<=10;$i++)
        {
            $tpat['tot'][0][$i]=0;
            $tpat['tot'][4][$i]=0;
            $tpat['tot'][8][$i]=0;
            $tpat['tot'][12][$i]=0;
            $tpat['tot'][16][$i]=0;
            $tpat['tot'][20][$i]=0;
            $tpat['tot'][24][$i]=0;

        }

        $total[0]=$total[4]=$total[8]=$total[12]=$total[16]=$total[20]=$total[24]=0;

        $id_prec='';
        $max_hba=0;
        while(list($cab, $id, $dsuivi, $dHBA) = mysql_fetch_row($res)) {
            if($id_prec!=$id)
            {

                if($id_prec!='')
                {
                    if($nb_hba_id>$max_hba)
                    {
                        $max_hba=$nb_hba_id;
                    }

                    $tpat['tot'][$entree][$nb_hba_id]=$tpat['tot'][$entree][$nb_hba_id]+1;
                    $id_prec=$id;

                    $nb_mois=diffmois($dsuivi, $date);

                    if($nb_mois<4)
                    {
                        $entree=0;
                    }
                    elseif(($nb_mois>=4)&&($nb_mois<8))
                    {
                        $entree=4;
                    }
                    elseif(($nb_mois>=8)&&($nb_mois<12))
                    {
                        $entree=8;
                    }
                    elseif(($nb_mois>=12)&&($nb_mois<16))
                    {
                        $entree=12;
                    }
                    elseif(($nb_mois>=16)&&($nb_mois<20))
                    {
                        $entree=16;
                    }
                    elseif(($nb_mois>=20)&&($nb_mois<24))
                    {
                        $entree=20;
                    }
                    else
                    {
                        $entree=24;
                    }

                    if(($dHBA!='')&&($dHBA!='0000-00-00')&&($dHBA!='NULL')&&(diffmois($dHBA, $date)<=$entree+4))
                    {
                        $nb_hba_id=1;

                    }
                    else
                    {
                        $nb_hba_id=0;
                    }
                    $total[$entree]=$total[$entree]+1;
                    $dinclu=$dsuivi;


                }
                else
                {

                    $nb_mois=diffmois($dsuivi, $date);

                    if($nb_mois<4)
                    {
                        $entree=0;
                    }
                    elseif(($nb_mois>=4)&&($nb_mois<8))
                    {
                        $entree=4;
                    }
                    elseif(($nb_mois>=8)&&($nb_mois<12))
                    {
                        $entree=8;
                    }
                    elseif(($nb_mois>=12)&&($nb_mois<16))
                    {
                        $entree=12;
                    }
                    elseif(($nb_mois>=16)&&($nb_mois<20))
                    {
                        $entree=16;
                    }
                    elseif(($nb_mois>=20)&&($nb_mois<24))
                    {
                        $entree=20;
                    }
                    else
                    {
                        $entree=24;
                    }
                    $id_prec=$id;

                    if(($dHBA!='')&&($dHBA!='0000-00-00')&&($dHBA!='NULL')&&(diffmois($dHBA, $date)<=$entree+4))
                    {
                        $nb_hba_id=1;
                    }
                    else
                    {
                        $nb_hba_id=0;
                    }

                    $total[$entree]=$total[$entree]+1;
                    $dinclu=$dsuivi;

                }
            }
            else
            {
                if(($dHBA!='')&&($dHBA!='0000-00-00')&&($dHBA!='NULL')&&(diffmois($dHBA, $date)<=$entree+4))
                {
                    $nb_hba_id++;
                }

            }


        }

        if(isset($entree) && isset($nb_hba_id))
        {
            $tpat['tot'][$entree][$nb_hba_id]=$tpat['tot'][$entree][$nb_hba_id]+1;
        }

        ?>

        <tr>
            <td rowspan='2' width='20%'>Nombre de dosage HBA1c réalisés sur la période</td>
            <td colspan='7' align='center'>Durée de suivi des patients</td>
        </tr>
        <tr>
            <td align='center'>< 4 mois</td>
            <td align='center'>[4 - 8[ mois</td>
            <td align='center'>[8 - 12 [ mois</td>
            <td align='center'>[12 - 16 [mois</td>
            <td align='center'>[16 - 20 [mois</td>
            <td align='center'>[20 - 24 [mois</td>
            <td align='center'>>= 24 mois</td>
        </tr>
        <?php

        $pc_tot[0]=$pc_tot[4]=$pc_tot[8]=$pc_tot[12]=$pc_tot[16]=$pc_tot[20]=$pc_tot[24]=0;


        for($i=0; $i<=$max_hba; $i++)
        {

            if($total[0]>0)
            {
                $pc_tot[0]+=$tpat['tot'][0][$i]/$total[0]*100;
            }
            else
            {
                $pc_tot[0]='ND';
            }
            if($total[4]>0)
            {
                $pc_tot[4]+=$tpat['tot'][4][$i]/$total[4]*100;
            }
            else
            {
                $pc_tot[4]='ND';
            }
            if($total[8]>0)
            {
                $pc_tot[8]+=$tpat['tot'][8][$i]/$total[8]*100;
            }
            else
            {
                $pc_tot[8]='ND';
            }
            if($total[12]>0)
            {
                $pc_tot[12]+=$tpat['tot'][12][$i]/$total[12]*100;
            }
            else
            {
                $pc_tot[12]='ND';
            }
            if($total[16]>0)
            {
                $pc_tot[16]+=$tpat['tot'][16][$i]/$total[16]*100;
            }
            else
            {
                $pc_tot[16]='ND';
            }
            if($total[20]>0)
            {
                $pc_tot[20]+=$tpat['tot'][20][$i]/$total[20]*100;
            }
            else
            {
                $pc_tot[20]='ND';
            }
            if($total[24]>0)
            {
                $pc_tot[24]+=$tpat['tot'][24][$i]/$total[24]*100;
            }
            else
            {
                $pc_tot[24]='ND';
            }

            if($total[0]>0)
            {
                $pc[0][$i]=round($tpat['tot'][0][$i]/$total[0]*100, 1);
            }
            else
            {
                $pc[0][$i]='ND';
            }
            if($total[4]>0)
            {
                $pc[4][$i]=round($tpat['tot'][4][$i]/$total[4]*100, 1);
            }
            else
            {
                $pc[4][$i]='ND';
            }
            if($total[8]>0)
            {
                $pc[8][$i]=round($tpat['tot'][8][$i]/$total[8]*100, 1);
            }
            else
            {
                $pc[8][$i]='ND';
            }
            if($total[12]>0)
            {
                $pc[12][$i]=round($tpat['tot'][12][$i]/$total[12]*100, 1);
            }
            else
            {
                $pc[12][$i]='ND';
            }
            if($total[16]>0)
            {
                $pc[16][$i]=round($tpat['tot'][16][$i]/$total[16]*100, 1);
            }
            else
            {
                $pc[16][$i]='ND';
            }
            if($total[20]>0)
            {
                $pc[20][$i]=round($tpat['tot'][20][$i]/$total[20]*100, 1);
            }
            else
            {
                $pc[20][$i]='ND';
            }
            if($total[24]>0)
            {
                $pc[24][$i]=round($tpat['tot'][24][$i]/$total[24]*100, 1);
            }
            else
            {
                $pc[24][$i]='ND';
            }


            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td align='right'><?php if($tcabinet_util==0)
                    {
                        echo "ND</td>";
                    }
                    else
                    {
                    echo number_format($pc[0][$i], 1, ',', ' '); ?>%</td>
                <?
                }
                ?>
                <td align='right'><?php if($tcabinet_util==0)
                    {
                        echo "ND</td>";
                    }
                    else
                    {
                    echo number_format($pc[4][$i], 1, ',', ' '); ?>%</td>
            <?
            }
            ?>
                <td align='right'><?php if($tcabinet_util==0)
                    {
                        echo "ND</td>";
                    }
                    else
                    {
                    echo number_format($pc[8][$i], 1, ',', ' '); ?>%</td>
            <?
            }
            ?>
                <td align='right'><?php if($tcabinet_util==0)
                    {
                        echo "ND</td>";
                    }
                    else
                    {
                    echo number_format($pc[12][$i], 1, ',', ' '); ?>%</td>
            <?
            }
            ?>
                <td align='right'><?php if($tcabinet_util==0)
                    {
                        echo "ND</td>";
                    }
                    else
                    {
                    echo number_format($pc[16][$i], 1, ',', ' '); ?>%</td>
            <?
            }
            ?>
                <td align='right'><?php if($tcabinet_util==0)
                    {
                        echo "ND</td>";
                    }
                    else
                    {
                    echo number_format($pc[20][$i], 1, ',', ' '); ?>%</td>
            <?
            }
            ?>
                <td align='right'><?php if($tcabinet_util==0)
                    {
                        echo "ND</td>";
                    }
                    else
                    {
                    echo number_format($pc[24][$i], 1, ',', ' '); ?>%</td>
            <?
            }
            ?>

            </tr>

            <?php

        }
        ?>
        <tr>
            <td>Total : </td>
            <td align='right'><?php echo number_format($pc_tot[0], 1, ',', ' ');?>%</td>
            <td align='right'><?php echo number_format($pc_tot[4], 1, ',', ' ');?>%</td>
            <td align='right'><?php echo number_format($pc_tot[8], 1, ',', ' ');?>%</td>
            <td align='right'><?php echo number_format($pc_tot[12], 1, ',', ' ');?>%</td>
            <td align='right'><?php echo number_format($pc_tot[16], 1, ',', ' ');?>%</td>
            <td align='right'><?php echo number_format($pc_tot[20], 1, ',', ' ');?>%</td>
            <td align='right'><?php echo number_format($pc_tot[24], 1, ',', ' ');?>%</td>

        </tr>

    </table>
    <br><br>
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
