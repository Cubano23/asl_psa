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
    <title>Indicateurs d'�valuation Asal�e : taux de suivi des diab�tiques</title>
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
require("../../global/entete.php");
//echo $loc;

entete_asalee("Indicateurs d'�valuation Asal�e taux de suivi des diab�tiques");

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
<font face='times new roman'>Indicateurs d'�valuation Asal�e taux de suivi des diab�tiques</font></i>";
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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet,$tville, $t_diab;

    $req="SELECT cabinet ".
        "FROM account ".
        "WHERE cabinet='".$_SESSION["nom"]."' ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
    list($cabinet)=mysql_fetch_row($res);

    $_SESSION["nom"]=$cabinet;

    $req="SELECT cabinet, total_diab2, nom_cab ".
        "FROM account ".
        "WHERE infirmiere!='' and region!='' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $t_diab['tot']=0;

    while(list($cab, $total_diab2, $ville) = mysql_fetch_row($res)) {
        $t_diab[$cab]=$total_diab2;
        $tville[$cab]=$ville;
        $t_diab['tot']=$t_diab['tot']+$total_diab2;
    }


//Patients avec au moins un suivi
    $req="SELECT cabinet, count(*) ".
        "FROM suivi_diabete, dossier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND actif='oui' ".
        "AND suivi_diabete.dossier_id=dossier.id ".
        "GROUP BY cabinet, dossier_id ".
        "ORDER BY cabinet ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    foreach ($t_diab as $cab =>$potentiel)
    {
        $nbsuivis[$cab]=0;
    }


    while(list($cab, $pat) = mysql_fetch_row($res)) {
        if(isset($nbsuivis[$cab])){
            $nbsuivis[$cab] = $nbsuivis[$cab]+1;
        }
    }


    $t_diab['tot']=0;

    foreach($tville as $cab=>$ville){
        if($nbsuivis[$cab]>$potentiel){
            $t_diab[$cab]=$nbsuivis[$cab];
        }
        $t_diab['tot']=$t_diab['tot']+$t_diab[$cab];

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
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
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
        if(isset($t_diab[$cab])){
            $tcabinet[] = $cab;
            $tpat[$cab] = $pat;
        }
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
            <td align='center'><b>Moyenne asal�e</b></td><td align='center'><b><?php echo $tville[$_SESSION['nom']]; ?></b></td>
            <td align='center'><b>Borne basse</b></td><td align='center'><b>Borne haute</b></td>
        </tr>

        <?php

        //taux diab 2 suivis dans asal�e
        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND actif='oui' ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "and ((dsuivi is not NULL and DATE_ADD(dsuivi, ".
            "INTERVAL 5 MONTH) >= CURDATE())) ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tot_pat=0;

        while(list($cab, $pat) = mysql_fetch_row($res)) {
            if(isset($t_diab[$cab])){
                $tpat[$cab] = $tpat[$cab]+1;
                $tot_pat=$tot_pat+1;
            }
        }


        ?>

        <tr>
            <td>Taux de patients diab�tiques suivis dans Asal�e <sup>1</sup></td>

            <?php

            $min=100;
            $max=0;

            foreach($tcabinet as $cab) {
                if(isset($t_diab[$cab])){
                    if ($t_diab[$cab]==0)
                        $taux="ND";
                    else
                    {
                        $taux=$tpat[$cab]/$t_diab[$cab]*100;
                        $taux=round($taux, 0);

                        if($taux<$min)
                            $min=$taux.'%';
                        if($taux>$max)
                            $max=$taux.'%';

                        $taux.="%";
                    }

                    $taux_cab[$cab]=$taux;
                }
            }
            ?>
            <td align='right'><?php echo round($tot_pat/$t_diab['tot']*100,0); ?>%</td>
            <td align='right'><?php echo $taux_cab[$_SESSION['nom']]; ?></td>
            <td align='right'><?php echo $min; ?></td>
            <td align='right'><?php echo $max; ?></td>
        </tr>




        <?php

        ///taux de diab�tiques 2 vus en consult : pas ok � priori

        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND dossier.actif='oui' ".
            "AND suivi_diabete.dossier_id=evaluation_infirmier.id ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tot_pat=0;
        while(list($cab, $pat) = mysql_fetch_row($res)) {
            if(isset($t_diab[$cab])){
                $tpat[$cab] = $tpat[$cab]+1;
                $tot_pat=$tot_pat+1;
            }
        }


        ?>

        <tr>
            <td>Taux de patients diab�tiques type 2 vus en consultation <sup>2</sup></td>

            <?php

            $min=100;
            $max=0;

            foreach($tcabinet as $cab) {
                if ($t_diab[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_diab[$cab]*100;
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
            <td align='right'><?php echo round($tot_pat/$t_diab['tot']*100,0); ?>%</td>
            <td align='right'><?php echo $taux_cab[$_SESSION['nom']]; ?></td>
            <td align='right'><?php echo $min; ?></td>
            <td align='right'><?php echo $max; ?></td>

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
    <sup>1</sup>Nombre de personnes "actives" ayant eu au moins un suivi diab�te dans les 5 derniers mois/potentiel du cabinet<br>
    <sup>2</sup>Nombre de personnes "actives" ayant eu au moins un suivi du diab�te et au moins une consultation/potentiel du cabinet
    <?
}

function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet,$tville, $t_diab;

    $mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'D�cembre');

    $tab_date=split('-', $date);

    echo "<b>Donn�es au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    foreach($tcabinet as $cab) {
        $t_diab[$cab]=0;
    }


    $req="SELECT cabinet, total_diab2 ".
        "FROM histo_account ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes' AND cabinet!='ergo' AND cabinet!='jgomes' ".
        "and cabinet!='sbirault' ".
        "AND dmaj<='$date 23:59:59' ".
        "ORDER BY cabinet, dmaj ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");



    while(list($cab, $total_diab2) = mysql_fetch_row($res)) {
        $t_diab[$cab]=$total_diab2;
    }


//Patients avec au moins un suivi
    $req="SELECT cabinet, count(*) ".
        "FROM suivi_diabete, dossier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND ( ((dossier.actif='oui') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date')) ".
        "AND dossier.dcreat<='$date' ) ".
        "AND dsuivi<='$date' ".
        "AND suivi_diabete.dossier_id=dossier.id ".
        "GROUP BY cabinet, dossier_id ".
        "ORDER BY cabinet ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    foreach ($tcabinet as $cab)
    {
        $nbsuivis[$cab]=0;
    }

    $nbsuivis['tot']=0;

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        if(isset($nbsuivis[$cab])){
            $nbsuivis[$cab] = $nbsuivis[$cab]+1;
        }
    }


    foreach($t_diab as $cab=>$potentiel){
        if(isset($nbsuivis[$cab])){
            if($potentiel<$nbsuivis[$cab]){
                $t_diab[$cab]=$nbsuivis[$cab];
            }
        }
    }

    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'   and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
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
    $t_diab['tot']=0;
    $cab_prec="";

    foreach ($tcabinet as $cab)
    {
        if(isset($nbsuivis[$cab])){
            $tpat[$cab]=0;
            $tcabinet_util[$cab]=0;
        }

    }


    while(list($cab, $pat) = mysql_fetch_row($res)) {
//	 $tcabinet[] = $cab;

        if(isset($nbsuivis[$cab])){
            if($cab!=$cab_prec)
            {
                $t_diab['tot']=$t_diab['tot']+$t_diab[$cab];
                $cab_prec=$cab;
                $tcabinet_util[$cab]=$t_diab[$cab];
            }
        }
    }


    ?>
    <table border=1 width='100%'>
        <tr>
            <td></td>
            <td align='center'><b>Moyenne asal�e</b></td><td align='center'><b><?php echo $tville[$_SESSION['nom']]; ?></b></td>
            <td align='center'><b>Borne basse</b></td><td align='center'><b>Borne haute</b></td>
        </tr>

        <?php

        //taux diab 2 suivis dans asal�e
        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "and ((dsuivi is not NULL and DATE_ADD(dsuivi, ".
            "INTERVAL 5 MONTH) >= '$date') and (dsuivi<='$date')) ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;

        }

        $tot_pat=0;


        while(list($cab, $pat) = mysql_fetch_row($res)) {
            if(isset($nbsuivis[$cab])){
                $tpat[$cab] = $tpat[$cab]+1;
                $tot_pat=$tot_pat+1;
            }

        }


        ?>

        <tr>
            <td>Taux de patients diab�tiques suivis dans Asal�e <sup>1</sup></td>

            <?php

            $min=100;
            $max=0;

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_diab[$cab]*100;
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
            <td align='right'><?php echo round($tot_pat/$t_diab['tot']*100,0); ?>%</td>
            <td align='right'><?php echo $taux_cab[$_SESSION['nom']]; ?></td>
            <td align='right'><?php echo $min; ?></td>
            <td align='right'><?php echo $max; ?></td>
        </tr>




        <?php

        ///taux de diab�tiques 2 vus en consult : pas ok � priori

        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date')) ".
            "AND suivi_diabete.dossier_id=evaluation_infirmier.id ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "AND evaluation_infirmier.date<='$date' ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;
        }

        $tot_pat=0;
        while(list($cab, $pat) = mysql_fetch_row($res)) {
            if(isset($nbsuivis[$cab])){
                $tpat[$cab] = $tpat[$cab]+1;
                $tot_pat=$tot_pat+1;
            }
        }


        ?>

        <tr>
            <td>Taux de patients diab�tiques type 2 vus en consultation <sup>2</sup></td>

            <?php
            $min=100;
            $max=0;

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_diab[$cab]*100;
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
            <td align='right'><?php echo round($tot_pat/$t_diab['tot']*100,0); ?>%</td>
            <td align='right'><?php echo $taux_cab[$_SESSION['nom']]; ?></td>
            <td align='right'><?php echo $min; ?></td>
            <td align='right'><?php echo $max; ?></td>

        </tr>


    </table>
    <br>
    <br>

    <?php

}

?>
</body>
</html>
