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
    <title>Indicateurs d'�valuation Asal�e : taux de d�pistage des troubles cognitifs</title>
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

entete_asalee("Indicateurs d'�valuation Asal�e : taux de d�pistage des troubles cognitifs");
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
<font face='times new roman'>Indicateurs d'�valuation Asal�e : taux de d�pistage des troubles cognitifs</font></i>";
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

    # fen�tre glissante:
    if (isset($_GET['mois']) && isset($_GET['annee']))
    {
        etape_2($repete);
        exit;
    }

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
    global $message,$Dossier,$Cabinet, $deval, $self,$tcabinet, $tville, $t_cogni;

    $req="SELECT cabinet, total_cogni, nom_cab ".
        "FROM account ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and cabinet!='jgomes' and cabinet!='sbirault' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $t_cogni['tot']=0;
    $t_cogni['eval']=0;
    $t_cogni['eval2']=0;
    $t_cogni['eval3']=0;

    while(list($cab, $total_cogni, $ville) = mysql_fetch_row($res)) {
        $t_cogni[$cab]=$total_cogni;
        $tville[$cab]=$ville;
        $t_cogni['tot']=$t_cogni['tot']+$total_cogni;

        if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $t_cogni['eval']=$t_cogni['eval']+$total_cogni;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chiz�")==0))
        {
            $t_cogni['eval2']=$t_cogni['eval2']+$total_cogni;
        }
        /*	else
            {
                 $t_cogni['eval3']=$t_cogni['eval3']+$total_cogni;
            }
        */}

    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */
    $req="SELECT dossier.cabinet, count(*) ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='zTest'  and dossier.cabinet!='irdes' and dossier.cabinet!='ergo'  ".
        "and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
        "AND actif='oui' and account.cabinet=dossier.cabinet ".
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
        $tpat[$cab] = $pat;
    }

    $mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'D�cembre');

    echo '<b>Donn�es � la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 width='100%'>
        <tr>
            <td></td>
            <td align='center'><b> Moyenne g�n�rale </b></td>
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

        ///////////////////////////TROUBLES COGNITIFS//////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM trouble_cognitif, dossier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and dossier.Cabinet!='saint-varent' ".
            "AND dossier.actif='oui' ".
            "AND trouble_cognitif.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) <= CURDATE() ".
            "and ((trouble_cognitif.date_rappel is not NULL and ".
            "DATE_ADD(trouble_cognitif.date_rappel, INTERVAL 1 MONTH) >= CURDATE()) OR ".
            "(trouble_cognitif.date_rappel is NULL and DATE_ADD(trouble_cognitif.date, INTERVAL 13 MONTH) >= CURDATE())) ".
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

            if((strcasecmp($cab, "clamecy")!=0)||(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $tpat['eval']=$tpat['eval']+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chiz�")==0))
            {
                $tpat['eval2']=$tpat['eval2']+1;
            }
            /*	else
                {
                     $tpat['eval3']=$tpat['eval3']+1;
                }
            */}

        ?>

        <tr>
            <td>Taux de patients �ligibles au d�pistage des troubles cognitifs<sup>1</sup></td>
            <td align='right'><?php echo round(($tpat['tot']-$tpat['Saint-Varent'])/($t_cogni['tot']-$t_cogni['Saint-Varent'])*100,0);?>%</td>
            <td align='right'><?php echo round(($tpat['eval']-$tpat['Saint-Varent'])/($t_cogni['eval']-$t_cogni['Saint-Varent'])*100,0);?>%</td>
            <td align='right'><?php echo ($t_cogni['eval2']==0)?"0":round($tpat['eval2']/$t_cogni['eval2']*100,0);?>%</td>
            <td align='right'><?php echo ($t_cogni['eval2']==0)?"0":round($tpat['eval3']/$t_cogni['eval3']*100,0);?>%</td>

            <?php

            foreach($tcabinet as $cab) {
                if ($t_cogni[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_cogni[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab, 'Saint-varent')==0)?"ND":$taux; ?></td>
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
    <sup>1</sup>Nombre de troubles cognitifs effectu�s pour des personnes de plus de 75 ans, dont la date de rappel n'est pas �chue ou �chue depuis moins d'un mois/potentiel du cabinet
    <?

}

function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self,$tcabinet, $tville, $t_cogni;

    $mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'D�cembre');

    $tab_date=split('-', $date);

    echo "<b>Donn�es au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    /*foreach($tcabinet as $cab){
         $t_cogni[$cab]=0;
    }

    $req="SELECT cabinet, total_cogni ".
             "FROM histo_account ".
             "WHERE cabinet!='zTest'  and cabinet!='irdes' ".
             "AND dmaj<='$date 23:59:59' ".
             "ORDER BY cabinet, dmaj ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");



    while(list($cab, $total_cogni) = mysql_fetch_row($res)) {
         $t_cogni[$cab]=$total_cogni;
    }
    */

    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ";

    if($date>='2008-01-01'){
        $req.="and dossier.cabinet!='saint-varent' ";
    }

    $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
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
    $tpat['tot']=0;
    $t_cogni['tot']=0;
    $cab_prec="";
    $t_cogni['eval']=0;
    $t_cogni['eval2']=0;
    $t_cogni['eval3']=0;

    $tpat['eval']=0;
    $tpat['eval2']=0;
    $tpat['eval3']=0;

    foreach($tcabinet as $cab)
    {
        $tcabinet_util[$cab]=0;
    }

    while(list($cab, $pat) = mysql_fetch_row($res)) {

        if($cab!=$cab_prec)
        {
            $t_cogni['tot']=$t_cogni['tot']+$t_cogni[$cab];
            $tcabinet_util[$cab]=$t_cogni[$cab];
            $cab_prec=$cab;

            if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//			(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $t_cogni['eval']=$t_cogni['eval']+$t_cogni[$cab];
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chiz�")==0))
            {
                $t_cogni['eval2']=$t_cogni['eval2']+$t_cogni[$cab];
            }
            /*		else
                    {
                         $t_cogni['eval3']=$t_cogni['eval3']+$t_cogni[$cab];
                    }
            */	 }
    }

    ?>

    <table border=1 width='100%'>
        <tr>
            <td></td>
            <td align='center'><b> Moyenne g�n�rale</b></Td>
            <td align='center'><b> Moyenne cabinets 79 </b></Td>
            <td align='center'><b> Moyenne eval cab 2005 </b></Td>
            <td align='center'><b> Moyenne eval cab 2006 </b></Td>
            <?php
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }
            ?>
        </tr>


        <?php

        ///////////////////////////TROUBLES COGNITIFS//////////////////////////////////

        $req="SELECT cabinet, count(*) ".
            "FROM trouble_cognitif, dossier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.=" and dossier.cabinet!='saint-varent' ";
        }

        $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND trouble_cognitif.id=dossier.id ".
            "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) <= '$date' ".
            "AND trouble_cognitif.date<='$date' ".
            "and ((trouble_cognitif.date_rappel is not NULL and ".
            "DATE_ADD(trouble_cognitif.date_rappel, INTERVAL 1 MONTH) >= '$date') OR ".
            "(trouble_cognitif.date_rappel is NULL and DATE_ADD(trouble_cognitif.date, INTERVAL 13 MONTH) >= '$date')) ".
            "GROUP BY cabinet, numero ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tpat['tot']=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            $tpat[$cab] = $tpat[$cab]+1;
            $tpat['tot']=$tpat['tot']+1;

            if((strcasecmp($cab, "Clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $tpat['eval']=$tpat['eval']+1;
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chiz�")==0))
            {
                $tpat['eval2']=$tpat['eval2']+1;
            }
            /*	else
                {
                     $tpat['eval3']=$tpat['eval3']+1;
                }
            */}


        ?>

        <tr>
            <td>Taux de patients �ligibles au d�pistage des troubles cognitifs<sup>1</sup></td>
            <td align='right'><?php echo round($tpat['tot']/$t_cogni['tot']*100,0);?>%</td>
            <td align='right'><?php echo round($tpat['eval']/$t_cogni['eval']*100,0);?>%</td>
            <td align='right'><?php echo ($t_cogni['eval2']==0)?'ND':round($tpat['eval2']/$t_cogni['eval2']*100,0);?>%</td>
            <td align='right'><?php echo ($t_cogni['eval3']==0)?'ND':round($tpat['eval3']/$t_cogni['eval3']*100,0);?>%</td>

            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_cogni[$cab]*100;
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
