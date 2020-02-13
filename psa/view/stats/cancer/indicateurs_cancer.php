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
    <title>Indicateurs d'évaluation Asalée : taux de dépistage des cancers</title>
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

entete_asalee("Indicateurs d'évaluation Asalée : taux de dépistage des cancers");
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
<font face='times new roman'>Indicateurs d'évaluation Asalée : taux de dépistage des cancers</font></i>";
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
    global $message,$Dossier,$Cabinet, $deval, $self,$tcabinet, $tville, $t_sein, $t_colon, $t_uterus;

    $req="SELECT cabinet, total_sein, total_colon, total_uterus, nom_cab ".
        "FROM account ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and cabinet!='jgomes' ".
        "and cabinet!='sbirault' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $t_sein['tot']=$t_colon['tot']=$t_uterus['tot']=0;
    $t_sein['eval']=$t_colon['eval']=$t_uterus['eval']=0;
    $t_sein['eval2']=$t_colon['eval2']=$t_uterus['eval2']=0;
    $t_sein['eval3']=$t_colon['eval3']=$t_uterus['eval3']=0;

    $tcabinet=array();

    while(list($cab, $total_sein, $total_colon, $total_uterus, $ville) = mysql_fetch_row($res)) {
        $t_sein[$cab]=$total_sein;
        $t_colon[$cab]=$total_colon;
        $t_uterus[$cab]=$total_uterus;
        $tville[$cab]=$ville;
        $t_sein['tot']=$t_sein['tot']+$total_sein;
        $t_colon['tot']=$t_colon['tot']+$total_colon;
        $t_uterus['tot']=$t_uterus['tot']+$total_uterus;
        $tcabinet[] = $cab;

        if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $t_sein['eval']=$t_sein['eval']+$total_sein;
            $t_colon['eval']=$t_colon['eval']+$total_colon;
            $t_uterus['eval']=$t_uterus['eval']+$total_uterus;
        }
        else//if((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
//		(strcasecmp($cab, "chizé")==0))
        {
            $t_sein['eval2']=$t_sein['eval2']+$total_sein;
            $t_colon['eval2']=$t_colon['eval2']+$total_colon;
            $t_uterus['eval2']=$t_uterus['eval2']+$total_uterus;
        }
        /*	elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
            {
                 $t_sein['eval3']=$t_sein['eval3']+$total_sein;
                 $t_colon['eval3']=$t_colon['eval3']+$total_colon;
                 $t_uterus['eval3']=$t_uterus['eval3']+$total_uterus;
            }
        */
    }

    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */
    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'   and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' and cabinet!='saint-varent' ".
        "AND actif='oui' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $tpat[$cab] = $pat;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 width='100%'>
        <tr>
            <td>Taux de patients éligibles au dépistage du cancer</td>
            <td align='center'><b> Moyenne générale </b></td>
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

        <?php

        ///////////////////CANCER SEIN///////////////////////
        $req="SELECT cabinet, count(*), max(sortir_rappel) as sortir_rappel ".
            "FROM depistage_sein, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and cabinet!='saint-varent' ".
            "AND dossier.actif='oui' ".
            "AND depistage_sein.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) > CURDATE() ".
            "and ((mamograph_date is not NULL and DATE_ADD(mamograph_date,INTERVAL 25 MONTH) >= CURDATE()) ".
            "OR sortir_rappel='1') ".
            "GROUP BY cabinet, dossier.id ".
            "ORDER BY cabinet ";
        //echo $req;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['eval']=0;
        $tpat['eval2']=0;
        $tpat['eval3']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $tpat['eval']=$tpat['eval']+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $tpat['eval2']=$tpat['eval2']+1;
            }
            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
            {
                $tpat['eval3']=$tpat['eval3']+1;
            }
        }


        ?>

        <tr>
            <td>sein<sup>1</sup></td>
            <td align='right'><?php echo round(($tpat['tot']-$tpat['Saint-Varent'])/($t_sein['tot']-$t_sein['Saint-Varent'])*100,0);?>%</td>
            <td align='right'><?php echo round(($tpat['eval']-$tpat['Saint-Varent'])/($t_sein['eval']-$t_sein['Saint-Varent'])*100,0);?>%</td>
            <td align='right'><?php echo ($t_sein['eval2']==0)?"0":round($tpat['eval2']/$t_sein['eval2']*100,0);?>%</td>
            <td align='right'><?php echo ($t_sein['eval3']==0)?"0":round($tpat['eval3']/$t_sein['eval3']*100,0);?>%</td>

            <?php

            foreach($tcabinet as $cab) {
                if ($t_sein[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_sein[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab,'saint-varent')==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
        </tr>


        <?php

        ///////////////////PATIENTES AVEC UN DEPISTAGE///////////////////////
        $req="SELECT cabinet, count(*) ".
            "FROM depistage_sein, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and cabinet!='saint-varent' ".
            "AND dossier.actif='oui' ".
            "AND depistage_sein.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) > CURDATE() ".
            "GROUP BY cabinet, dossier.id ".
            "ORDER BY cabinet ";
        //echo $req;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['eval']=0;
        $tpat['eval2']=0;
        $tpat['eval3']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $tpat['eval']=$tpat['eval']+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $tpat['eval2']=$tpat['eval2']+1;
            }
            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
            {
                $tpat['eval3']=$tpat['eval3']+1;
            }
        }


        ?>

        <tr>
            <td>Nb patientes<sup>4</sup></td>
            <td align='right'><?php echo $tpat['tot']-$tpat['Saint-Varent'];?></td>
            <td align='right'><?php echo $tpat['eval']-$tpat['Saint-Varent'];?></td>
            <td align='right'><?php echo $tpat['eval2'];?></td>
            <td align='right'><?php echo $tpat['eval3'];?></td>

            <?php

            foreach($tcabinet as $cab) {

                ?>
                <td align='right'><?php echo (strcasecmp($cab,'saint-varent')==0)?"ND":$tpat[$cab]; ?></td>
                <?php
            }
            ?>
        </tr>


        <tr>
            <td>Potentiel<sup>5</sup></td>
            <td align='right'><?php echo $t_sein['tot']-$t_sein['Saint-Varent'];?></td>
            <td align='right'><?php echo $t_sein['eval']-$t_sein['Saint-Varent'];?></td>
            <td align='right'><?php echo $t_sein['eval2'];?></td>
            <td align='right'><?php echo $t_sein['eval3'];?></td>

            <?php

            foreach($tcabinet as $cab) {


                ?>
                <td align='right'><?php echo (strcasecmp($cab,'saint-varent')==0)?"ND":$t_sein[$cab]; ?></td>
                <?php
            }
            ?>
        </tr>

        <?php

        ////////////////////////dépistages colon////////////////////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM depistage_colon, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and cabinet!='saint-varent' ".
            "AND dossier.actif='oui' ".
            "AND depistage_colon.id=dossier.id ".
            "GROUP BY cabinet, numero ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['eval']=0;
        $tpat['eval2']=0;
        $tpat['eval3']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $tpat['eval']=$tpat['eval']+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $tpat['eval2']=$tpat['eval2']+1;
            }
            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
            {
                $tpat['eval3']=$tpat['eval3']+1;
            }
        }


        ?>

        <tr>
            <td>colon<sup>2</sup></td>
            <td align='right'><?php echo round(($tpat['tot']-$tpat['Saint-Varent'])/($t_colon['tot']-$t_colon['Saint-Varent'])*100,0);?>%</td>
            <td align='right'><?php echo round(($tpat['eval']-$tpat['Saint-Varent'])/($t_colon['eval']-$t_colon['Saint-Varent'])*100,0);?>%</td>
            <td align='right'><?php echo ($t_colon['eval2']==0)?"0":round($tpat['eval2']/$t_colon['eval2']*100,0);?>%</td>
            <td align='right'><?php echo ($t_colon['eval3']==0)?"0":round($tpat['eval3']/$t_colon['eval3']*100,0);?>%</td>

            <?php

            foreach($tcabinet as $cab) {
                if ($t_colon[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_colon[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab,'saint-varent')==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
        </tr>



        <?php

        /////////////////////DEPISTAGE UTERUS//////////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM depistage_uterus, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and cabinet!='saint-varent' ".
            "AND dossier.actif='oui' ".
            "AND depistage_uterus.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 65 YEAR) >= CURDATE() ".
            "and (depistage_uterus.date_rappel is not NULL and ".
            "DATE_ADD(depistage_uterus.date_rappel, INTERVAL 1 MONTH) >= CURDATE()) ".
            "GROUP BY cabinet, numero ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }
        $tpat['tot']=0;
        $tpat['eval']=0;
        $tpat['eval2']=0;
        $tpat['eval3']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $tpat['eval']=$tpat['eval']+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $tpat['eval2']=$tpat['eval2']+1;
            }
            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
            {
                $tpat['eval3']=$tpat['eval3']+1;
            }
        }


        ?>

        <tr>
            <td>col de l'utérus<sup>3</sup></td>
            <td align='right'><?php echo round(($tpat['tot']-$tpat['Saint-Varent'])/($t_uterus['tot']-$t_uterus['Saint-Varent'])*100,0);?>%</td>
            <td align='right'><?php echo round(($tpat['eval']-$tpat['Saint-Varent'])/($t_uterus['eval']-$t_uterus['Saint-Varent'])*100,0);?>%</td>
            <td align='right'><?php echo ($t_uterus['eval2']==0)?"0":round($tpat['eval2']/$t_uterus['eval2']*100,0);?>%</td>
            <td align='right'><?php echo ($t_uterus['eval3']==0)?"0":round($tpat['eval3']/$t_uterus['eval3']*100,0);?>%</td>

            <?php

            foreach($tcabinet as $cab) {
                if ($t_uterus[$cab]==0)
                    $taux="ND";
                else
                {
//	    $taux="ND";
                    $taux=$tpat[$cab]/$t_uterus[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab,'saint-varent')==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
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
    <sup>1</sup>Nombre de personnes ayant eu une mammographie il y a moins de 25 mois et agées de moins de 75ans/potentiel du cabinet<br>
    <sup>2</sup>Nombre de personnes ayant eu un dépistage colon/potentiel du cabinet<br>
    <sup>3</sup>Nombre de personnes ayant eu un frottis, avec rappel non échu ou échu depuis moins d'un mois et agées de 65 ans ou moins/potentiel du cabinet
    <sup>4</sup>Nombre de personnes âgées de moins de 75 ans ayant eu au moins une mammographie<br>
    <sup>5</sup>potentiel du cabinet
    <?php
}


function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_sein, $t_colon, $t_uterus;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";


    /*foreach($tcabinet as $cab) {
         $t_sein[$cab]=0;
         $t_colon[$cab]=0;
         $t_uterus[$cab]=0;
    }

    $req="SELECT cabinet, total_sein, total_colon, total_uterus ".
             "FROM histo_account ".
             "WHERE cabinet!='zTest' and cabinet!='irdes'  ".
             "AND dmaj<='$date 23:59:59' ".
             "ORDER BY cabinet,dmaj ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");



    while(list($cab, $total_sein, $total_colon, $total_uterus) = mysql_fetch_row($res)) {
         $t_sein[$cab]=$total_sein;
         $t_colon[$cab]=$total_colon;
         $t_uterus[$cab]=$total_uterus;
    }

    */


    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ";

    if($date>='2008-01-01'){
        $req.=" and cabinet!='saint-varent' ";
    }
    $req.=   "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
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
    $t_sein['tot']=$t_colon['tot']=$t_uterus['tot']=0;
    $t_sein['eval']=$t_colon['eval']=$t_uterus['eval']=0;
    $t_sein['eval2']=$t_colon['eval2']=$t_uterus['eval2']=0;
    $t_sein['eval3']=$t_colon['eval3']=$t_uterus['eval3']=0;
    $cab_prec="";

    foreach ($tcabinet as $cab)
    {
        $tcabinet_util[$cab]=0;

    }


    while(list($cab, $pat) = mysql_fetch_row($res)) {
//	 $tcabinet[] = $cab;

        if($cab!=$cab_prec)
        {
            if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//			(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $t_sein['eval']=$t_sein['eval']+$t_sein[$cab];
                $t_colon['eval']=$t_colon['eval']+$t_colon[$cab];
                $t_uterus['eval']=$t_uterus['eval']+$t_uterus[$cab];
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $t_sein['eval2']=$t_sein['eval2']+$t_sein[$cab];
                $t_colon['eval2']=$t_colon['eval2']+$t_colon[$cab];
                $t_uterus['eval2']=$t_uterus['eval2']+$t_uterus[$cab];
            }
            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
            {
                $t_sein['eval3']=$t_sein['eval3']+$t_sein[$cab];
                $t_colon['eval3']=$t_colon['eval3']+$t_colon[$cab];
                $t_uterus['eval3']=$t_uterus['eval3']+$t_uterus[$cab];
            }

            $t_sein['tot']=$t_sein['tot']+$t_sein[$cab];
            $t_colon['tot']=$t_colon['tot']+$t_colon[$cab];
            $t_uterus['tot']=$t_uterus['tot']+$t_uterus[$cab];
            $cab_prec=$cab;
            $tcabinet_util[$cab]=$t_sein[$cab];
        }
    }


    ?>

    <table border=1 width='100%'>
        <tr>
            <td>Taux de patients éligibles au dépistage cancer</td>
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

        <?php

        ///////////////////CANCER SEIN///////////////////////
        $req="SELECT cabinet, count(*), max(sortir_rappel) as sortir_rappel ".
            "FROM depistage_sein, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.=" and cabinet!='saint-varent' ";
        }

        $req.=  "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND depistage_sein.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) > '$date' AND mamograph_date <='$date' ".
            "and ((mamograph_date is not NULL and DATE_ADD(mamograph_date,INTERVAL 25 MONTH) >= '$date') ".
            "OR sortir_rappel='1') ".
            "GROUP BY cabinet, dossier.id ".
            "ORDER BY cabinet ";
        //echo $req;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['eval']=0;
        $tpat['eval2']=0;
        $tpat['eval3']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $tpat['eval']=$tpat['eval']+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $tpat['eval2']=$tpat['eval2']+1;
            }
            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
            {
                $tpat['eval3']=$tpat['eval3']+1;
            }
        }


        ?>

        <tr>
            <td>sein<sup>1</sup></td>
            <td align='right'><?php echo round($tpat['tot']/$t_sein['tot']*100,0);	?>%</td>
            <td align='right'><?php echo round($tpat['eval']/$t_sein['eval']*100,0); ?>%</td>
            <td align='right'><?php echo ($t_sein['eval2']==0)?"ND":round($tpat['eval2']/$t_sein['eval2']*100,0);?>%</td>
            <td align='right'><?php echo ($t_sein['eval3']==0)?"ND":round($tpat['eval3']/$t_sein['eval3']*100,0);?>%</td>

            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_sein[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>


        <?php

        ////////////////////////dépistages colon////////////////////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM depistage_colon, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.=" and cabinet!='saint-varent' ";
        }

        $req.= "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND depistage_colon.id=dossier.id ".
            "AND depistage_colon.date<'$date' ".
            "GROUP BY cabinet, numero ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['eval']=0;
        $tpat['eval2']=0;
        $tpat['eval3']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $tpat['eval']=$tpat['eval']+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $tpat['eval2']=$tpat['eval2']+1;
            }
            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
            {
                $tpat['eval3']=$tpat['eval3']+1;
            }
        }


        ?>

        <tr>
            <td>colon<sup>2</sup></td>
            <td align='right'><?php echo round($tpat['tot']/$t_colon['tot']*100,0);?>%</td>
            <td align='right'><?php echo round($tpat['eval']/$t_colon['eval']*100,0);?>%</td>
            <td align='right'><?php echo ($t_colon['eval2']==0)?"ND":round($tpat['eval2']/$t_colon['eval2']*100,0);?>%</td>
            <td align='right'><?php echo ($t_colon['eval3']==0)?"ND":round($tpat['eval3']/$t_colon['eval3']*100,0);?>%</td>

            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_colon[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>



        <?php

        /////////////////////DEPISTAGE UTERUS//////////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM depistage_uterus, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.=" and cabinet!='saint-varent' ";
        }

        $req.= "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND depistage_uterus.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 65 YEAR) >= '$date' ".
            "and (depistage_uterus.date_rappel is not NULL and ".
            "DATE_ADD(depistage_uterus.date_rappel, INTERVAL 1 MONTH) >= '$date') ".
            "GROUP BY cabinet, numero ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;
        $tpat['eval']=0;
        $tpat['eval2']=0;
        $tpat['eval3']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $tpat['eval']=$tpat['eval']+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $tpat['eval2']=$tpat['eval2']+1;
            }
            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
            {
                $tpat['eval3']=$tpat['eval3']+1;
            }
        }


        ?>

        <tr>
            <td>col de l'utérus<sup>3</sup></td>
            <td align='right'><?php echo round($tpat['tot']/$t_uterus['tot']*100,0);?>%</td>
            <td align='right'><?php echo round($tpat['eval']/$t_uterus['eval']*100,0);?>%</td>
            <td align='right'><?php echo ($t_uterus['eval2']==0)?"ND":round($tpat['eval2']/$t_uterus['eval2']*100,0);?>%</td>
            <td align='right'><?php echo ($t_uterus['eval3']==0)?"ND":round($tpat['eval3']/$t_uterus['eval3']*100,0);?>%</td>

            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
//	    $taux="ND";
                    $taux=$tpat[$cab]/$t_uterus[$cab]*100;
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
