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
    <title>Indicateurs d'évaluation Asalée : nombre de patients suivis</title>
</head>
<body bgcolor=#FFE887>
<?php

error_reporting(E_ERROR); // EA. Les script ne traite pas des valeurs initiales ce qui génère les Notices 22-12-2014

require_once ("Config.php");
$config = new Config();

require($config->inclus_path . "/accesbase.inc.php") ;

# connexion aux données
mysql_connect($serveur,$idDB,$mdpDB) or
die("Impossible de se connecter au SGBD");
mysql_select_db($DB) or
die("Impossible de se connecter à la base");


$loc=str_repeat("../",substr_count($_SERVER['PHP_SELF'], '/')-1)."informed79";

require("../global/entete.php");
//echo $loc;

entete_asalee("Indicateurs d'évaluation Asalée : nombre de patients suivis");
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
<font face='times new roman'>Indicateurs d'évaluation Asalée : nombre de patients suivis</font></i>";
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


    # étape 1 : Affichage du tableau à la date du jour
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            case 1:
                etape_1($repete);
                break;


        }
    }
} while($repete);

# fin de traitement principal

//affichage du tableau à la date du jour
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_tot;

    $req="SELECT cabinet, total_pat,nom_cab ".
        "FROM account ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' ".
        "and cabinet!='sbirault' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $t_tot['tot']=0;
    $t_tot['eval']=0;
    $t_tot['eval2']=0;
    $t_tot['eval3']=0;

    while(list($cab, $total_pat, $ville) = mysql_fetch_row($res)) {
        $t_tot[$cab]=$total_pat;
        $tville[$cab]=$ville;
        $t_tot['tot']=$t_tot['tot']+$total_pat;

        if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0))//||(strcasecmp($cab, "saint-varent")==0))
        {
            $t_tot['eval']=$t_tot['eval']+$total_pat;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $t_tot['eval2']=$t_tot['eval2']+$total_pat;
        }
        else
        {
            $t_tot['eval3']=$t_tot['eval3']+$total_pat;
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
    $tpat['tot']=0;
    $tpat['eval']=0;
    $tpat['eval2']=0;
    $tpat['eval3']=0;

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tpat[$cab] = $pat;
        $tpat['tot']=$tpat['tot']+$pat;

        if((strcasecmp($cab, "clamcecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0))//||(strcasecmp($cab, "saint-varent")==0))
        {
            $tpat['eval']=$tpat['eval']+$pat;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $tpat['eval2']=$tpat['eval2']+$pat;
        }
        else
        {
            $tpat['eval3']=$tpat['eval3']+$pat;
        }
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>

    <table border=1 width='100%'>
        <tr>
            <td></td>
            <td align='center'><b> Moyenne générale</b></Td><td align='center'><b>Moyenne cabinets 79</b></td>
            <td align='center'><b>Moyenne cab 2005</b></td><td align='center'><b>Moyenne cab 2006</b></Td>
            <?php
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }
            ?>
        </tr>

        <tr>
            <td>Taux de patients suivis dans Asalée <sup>1</sup></td>
            <td align="right"><?php echo round(($tpat['tot']-$tpat['Saint-Varent'])/($t_tot['tot']-$t_tot['Saint-Varent'])*100, 0);?>%</td>
            <td align="right"><?php echo round(($tpat['eval']-$tpat['Saint-Varent'])/($t_tot['eval']-$t_tot['Saint-Varent'])*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['eval2']==0)?"0":round($tpat['eval2']/$t_tot['eval2']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['eval3']==0)?"0":round($tpat['eval3']/$t_tot['eval3']*100, 0);?>%</td>
            <?php
            foreach($tcabinet as $cab) {
                if ($t_tot[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_tot[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab, "saint-varent")==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
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

//Boucle pour l'affichage des tableaux arrêtés trimestriels
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
    <sup>1</sup>Nombre de patient ayant un dossier actif à la date/potentiel des cabinets actifs à la date du jour
    <?php
}

//Affichage de l'arrêté trimestriel à une date donnée
function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_tot;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=explode('-', $date);//EA 29-04-2014

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";


    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ";

    if($date>"2008-01-01"){
        $req.=" and dossier.cabinet!='saint-varent' ";
    }

    $req.= "GROUP BY cabinet ".
        "ORDER BY cabinet, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'était actif</p>");
    }
//$tcabinet=array();
    $tpat['tot']=0;
    $t_tot['tot']=0;

    $tpat['eval']=0;
    $t_tot['eval']=0;

    $tpat['eval2']=0;
    $t_tot['eval2']=0;

    $tpat['eval3']=0;
    $t_tot['eval3']=0;

    foreach($tcabinet as $cab) {
        $actif[$cab]='non';
    }

    while(list($cab, $pat) = mysql_fetch_row($res)) {
//	 $tcabinet[] = $cab;
        $tpat[$cab] = $pat;
        $tpat['tot']=$tpat['tot']+$pat;
        $t_tot['tot']=$t_tot['tot']+$t_tot[$cab];
        $actif[$cab]='oui';


        if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//			(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $tpat['eval']=$tpat['eval']+$pat;
            $t_tot['eval']=$t_tot['eval']+$t_tot[$cab];
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $tpat['eval2']=$tpat['eval2']+$pat;
            $t_tot['eval2']=$t_tot['eval2']+$t_tot[$cab];
        }

    }

    ?>

    <table border=1 width='100%'>
        <tr>
            <td></td>
            <td align='center'><b> Moyenne générale</b></td>
            <td align='center'><b> Moyenne cabinets 79 </b></td>
            <td align='center'><b> Moyenne cab 2005 </b></td>
            <td align='center'><b> Moyenne cab 2006 </b></td>
            <?php
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }
            ?>
        </tr>

        <tr>
            <td>Taux de patients suivis dans Asalée</td>
            <td align="right"><?php echo round($tpat['tot']/$t_tot['tot']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['eval']==0)?"ND":round($tpat['eval']/$t_tot['eval']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['eval2']==0)?"ND":round($tpat['eval2']/$t_tot['eval2']*100, 0);?>%</td>
            <td align="right"><?php echo ($t_tot['eval3']==0)?"ND":round($tpat['eval3']/$t_tot['eval3']*100, 0);?>%</td>
            <?php
            foreach($tcabinet as $cab) {
                if ($actif[$cab]=='non')
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_tot[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php

            }

            ?>
        </tr>

    </table>
    <br>
    <br>
    <?php

}

?>
</body>
</html>
