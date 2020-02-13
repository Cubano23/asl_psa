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
    <title>Indicateurs d'évaluation Asalée : nombre de patients vus en consultation</title>
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

entete_asalee("Indicateurs d'évaluation Asalée : nombre de patients vus en consultation");
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
<font face='times new roman'>Indicateurs d'évaluation Asalée : nombre de patients vus en consultation</font></i>";
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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_tot;

    $req="SELECT cabinet, total_pat ".
        "FROM account ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' and cabinet!='sbirault' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $t_tot['tot']=0;

    while(list($cab, $total_pat) = mysql_fetch_row($res)) {
        $t_tot[$cab]=$total_pat;
        $t_tot['tot']=$t_tot['tot']+$total_pat;
    }

    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */
    $req="SELECT dossier.cabinet, count(*), nom_cab ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='zTest' and dossier.cabinet!='irdes'  and dossier.cabinet!='ergo'  ".
        "and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
        "and dossier.cabinet=account.cabinet ".
        "AND actif='oui' ".
        "GROUP BY dossier.cabinet ".
        "ORDER BY dossier.cabinet, numero ";
//echo $req;
//die;
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

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 width='100%'>
        <!--<tr>
            <td></td>-->
        <?php
        /*   foreach($tcabinet as $cab) {*/
        ?>
        <!--	<td align='center'><b>--><?php /*echo $cab; */
        ?><!--</b></td>-->
        <?php
        // }
        ?>
        <!--</tr>
        -->
        <tr>
            <td></td><td align='center'><b>Moyenne asalée</td><td align='center'><b><?php echo $tville[$_SESSION['nom']]; ?></b></td>
            <td align='center'><b>Borne basse</b></td><td align='center'><b>Borne haute</b></td>
        </tr>
        <?php

        ////////////////////EVALUATION INFIRMIER////////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND dossier.actif='oui' ".
            "AND evaluation_infirmier.id =dossier.id ".
            "GROUP BY cabinet, dossier.id ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tot_pat=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tot_pat=$tot_pat+1;
        }


        ?>

        <tr>
            <td>Taux de patients vus en consultation<sup>1</sup></td>

            <?php

            $min=100;
            $max=0;

            foreach($tcabinet as $cab) {
                if ($t_tot[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_tot[$cab]*100;
                    $taux=round($taux, 0);

                    if($taux<$min)
                        $min=$taux.'%';
                    if($taux>$max)
                        $max=$taux.'%';

                    $taux.="%";

                }

                $taux_cab[$cab]=$taux;

            }
            ?>
            <td align='right'><?php echo round($tot_pat/$t_tot['tot']*100,0); ?>%</td>
            <td align='right'><?php echo $taux_cab[$_SESSION['nom']]; ?></td>
            <td align='right'><?php echo $min; ?></td>
            <td align='right'><?php echo $max; ?></td>
        </tr>

    </table>
    <br>
    <br>
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
    <sup>1</sup>Nombre de personnes ayant eu au moins une consultation/potentiel du cabinet
    <?php

}

function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_tot;

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    /*
    foreach($tcabinet as $cab) {
         $t_tot[$cab]=0;
    }

    $req="SELECT cabinet, total_pat ".
             "FROM histo_account ".
             "WHERE cabinet!='zTest' and cabinet!='irdes'  ".
             "AND dmaj<='$date 23:59:59' ".
             "ORDER BY cabinet, dmaj ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $t_tot['tot']=0;

    while(list($cab, $total_pat) = mysql_fetch_row($res)) {
         $t_tot[$cab]=$total_pat;
         $t_tot['tot']=$t_tot['tot']+$total_pat;
    }

    */
    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
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
    $t_tot['tot']=0;
    $cab_prec="";

    foreach ($tcabinet as $cab)
    {
        $tcabinet_util[$cab]=0;

    }


    while(list($cab, $pat) = mysql_fetch_row($res)) {
//	 $tcabinet[] = $cab;

        if($cab!=$cab_prec)
        {
            $t_tot['tot']=$t_tot['tot']+$t_tot[$cab];
            $cab_prec=$cab;
            $tcabinet_util[$cab]=$t_tot[$cab];
        }
    }


    ?>

    <table border=1 width='100%'>
        <tr>
            <td></td>
            <td align='center'><b>Moyenne asalée</b></td><td align='center'><b><?php echo $tville[$_SESSION['nom']]; ?></b></td>
            <td align='center'><b>Borne basse</b></td><td align='center'><b>Borne haute</b></td>
        </tr>


        <?php

        ////////////////////EVALUATION INFIRMIER////////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND evaluation_infirmier.id =dossier.id ".
            "AND evaluation_infirmier.date<'$date' ".
            "GROUP BY cabinet, dossier.id ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tot_pat=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tot_pat=$tot_pat+1;
        }


        ?>

        <tr>
            <td>Taux de patients vus en consultation<sup>1</sup></td>

            <?php

            $min=100;
            $max=0;

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_tot[$cab]*100;
                    $taux=round($taux, 0);

                    if($taux<$min)
                        $min=$taux;
                    if($taux>$max)
                        $max=$taux;

//		$taux.="%";

                }

                $taux_cab[$cab]=$taux;

            }
            ?>
            <td align='right'><?php echo round($tot_pat/$t_tot['tot']*100,0); ?>%</td>
            <td align='right'><?php echo $taux_cab[$_SESSION['nom']].'%'; ?></td>
            <td align='right'><?php echo $min.'%'; ?></td>
            <td align='right'><?php echo $max.'%'; ?></td>
        </tr>

    </table>
    <br>
    <br>
    <?php

}

?>
</body>
</html>
