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
    <title>Taux de conformité au dépistage du cancer du sein</title>
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

entete_asalee("Taux de confortmité au dépistage du cancer du sein");
//echo $loc;

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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_sein, $total;



    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and cabinet!='jgomes' ".
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
        $tpat[$cab] = $pat;
    }







    $req="SELECT cabinet, total_sein, nom_cab ".
        "FROM account ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'   and cabinet!='ergo' and cabinet!='jgomes' ".
        "and cabinet!='sbirault' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $total=0;
    $total_eval=0;
    $total_eval2=0;
    $total_eval3=0;

    while(list($cab, $total_sein, $ville) = mysql_fetch_row($res)) {
        $t_sein[$cab]=$total_sein;
        $total+=$total_sein;
        $tville[$cab]=$ville;

        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $total_eval+=$total_sein;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $total_eval2+=$total_sein;
        }
        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
        {
            $total_eval3+=$total_sein;
        }
    }



//////////////TAUX DE CONFORMITE CANCER DU SEIN///////////////////////


///////////2 ans////////////////
    $req="SELECT cabinet, count(*) ".
        "FROM depistage_sein, dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'   and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND dossier.actif='oui' ".
        "AND depistage_sein.id=dossier.id ".
        "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) > CURDATE() ".
        "and (mamograph_date is not NULL and DATE_ADD(mamograph_date,INTERVAL 24 MONTH) >= CURDATE()) ".
        "GROUP BY cabinet, dossier.id ".
        "ORDER BY cabinet ";
//echo $req;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    foreach ($tcabinet as $cab)
    {
        $tsein[$cab][0]=0;
        $tsein[$cab][1]=0;
        $tsein[$cab][2]=0;
    }
    $moyenne[0]=$moyenne[1]=$moyenne[2]=0;
    $moyenne_eval[0]=$moyenne_eval[1]=$moyenne_eval[2]=0;
    $moyenne_eval2[0]=$moyenne_eval2[1]=$moyenne_eval2[2]=0;
    $moyenne_eval3[0]=$moyenne_eval3[1]=$moyenne_eval3[2]=0;

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $tsein[$cab][0] = $tsein[$cab][0]+1;
        $moyenne[0]=$moyenne[0]+1;

        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $moyenne_eval[0]=$moyenne_eval[0]+1;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $moyenne_eval2[0]=$moyenne_eval2[0]+1;
        }
        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
        {
            $moyenne_eval3[0]=$moyenne_eval3[0]+1;
        }
    }

///////////2 ans et 3 mois////////////////
    $req="SELECT cabinet, count(*) ".
        "FROM depistage_sein, dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'   and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND dossier.actif='oui' ".
        "AND depistage_sein.id=dossier.id ".
        "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) > CURDATE() ".
        "and (mamograph_date is not NULL and DATE_ADD(mamograph_date,INTERVAL 27 MONTH) >= CURDATE()) ".
        "GROUP BY cabinet, dossier.id ".
        "ORDER BY cabinet ";
//echo $req;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $tsein[$cab][1] = $tsein[$cab][1]+1;
        $moyenne[1]=$moyenne[1]+1;

        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $moyenne_eval[1]=$moyenne_eval[1]+1;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $moyenne_eval2[1]=$moyenne_eval2[1]+1;
        }
        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
        {
            $moyenne_eval3[1]=$moyenne_eval3[1]+1;
        }
    }


///////////2 ans et 6 mois////////////////
    $req="SELECT cabinet, count(*) ".
        "FROM depistage_sein, dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'   and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND dossier.actif='oui' ".
        "AND depistage_sein.id=dossier.id ".
        "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) > CURDATE() ".
        "and (mamograph_date is not NULL and DATE_ADD(mamograph_date,INTERVAL 30 MONTH) >= CURDATE()) ".
        "GROUP BY cabinet, dossier.id ".
        "ORDER BY cabinet ";
//echo $req;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");



    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $tsein[$cab][2] = $tsein[$cab][2]+1;
        $moyenne[2]=$moyenne[2]+1;

        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $moyenne_eval[2]=$moyenne_eval[2]+1;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $moyenne_eval2[2]=$moyenne_eval2[2]+1;
        }
        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
        {
            $moyenne_eval3[2]=$moyenne_eval3[2]+1;
        }
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>

    <br>
    <br>
    <table border=1 width='100%'>

        <tr>
            <td></td><td align='center'>moins de 2 ans <sup>1</sup></td>
            <td align='center'>moins de 2 ans et 3 mois <sup>2</sup></td>
            <td align='center'>moins de 2 ans et 6 mois <sup>3</sup></td>
        </tr>

        <?php

        foreach($tville as $cab =>$ville) {
            if ($t_sein[$cab]==0)
                $taux[0]=$taux[1]=$taux[2]="ND";
            else
            {
                $taux[0]=$tsein[$cab][0]/$t_sein[$cab]*100;
                $taux[0]=round($taux[0], 0);
                $taux[0].="%";


                $taux[1]=$tsein[$cab][1]/$t_sein[$cab]*100;
                $taux[1]=round($taux[1], 0);
                $taux[1].="%";


                $taux[2]=$tsein[$cab][2]/$t_sein[$cab]*100;
                $taux[2]=round($taux[2], 0);
                $taux[2].="%";
            }

            ?>
            <tr>
                <td align='left'><?php echo $tville[$cab]; ?></td>
                <td align='right'><?php echo $taux[0]; ?></td>
                <td align='right'><?php echo $taux[1]; ?></td>
                <td align='right'><?php echo $taux[2]; ?></td>
            </tr>
            <?php
        }
        $moyenne[0]=round($moyenne[0]/$total*100);
        $moyenne[1]=round($moyenne[1]/$total*100);
        $moyenne[2]=round($moyenne[2]/$total*100);

        ?>
        <tr>
            <td align="left">Moyenne pondérée</td>
            <td align="right"><?php echo $moyenne[0]; ?>%</td>
            <td align="right"><?php echo $moyenne[1]; ?>%</td>
            <td align="right"><?php echo $moyenne[2]; ?>%</td>

        </tr>

        <?php
        $moyenne_eval[0]=round($moyenne_eval[0]/$total_eval*100);
        $moyenne_eval[1]=round($moyenne_eval[1]/$total_eval*100);
        $moyenne_eval[2]=round($moyenne_eval[2]/$total_eval*100);

        ?>
        <tr>
            <td align="left">Moyenne eval</td>
            <td align="right"><?php echo $moyenne_eval[0]; ?>%</td>
            <td align="right"><?php echo $moyenne_eval[1]; ?>%</td>
            <td align="right"><?php echo $moyenne_eval[2]; ?>%</td>

        </tr>
        <?php
        $moyenne_eval2[0]=round($moyenne_eval2[0]/$total_eval2*100);
        $moyenne_eval2[1]=round($moyenne_eval2[1]/$total_eval2*100);
        $moyenne_eval2[2]=round($moyenne_eval2[2]/$total_eval2*100);

        ?>
        <tr>
            <td align="left">Moyenne cab 2005</td>
            <td align="right"><?php echo $moyenne_eval2[0]; ?>%</td>
            <td align="right"><?php echo $moyenne_eval2[1]; ?>%</td>
            <td align="right"><?php echo $moyenne_eval2[2]; ?>%</td>

        </tr>
        <?php
        $moyenne_eval3[0]=round($moyenne_eval3[0]/$total_eval3*100);
        $moyenne_eval3[1]=round($moyenne_eval3[1]/$total_eval3*100);
        $moyenne_eval3[2]=round($moyenne_eval3[2]/$total_eval3*100);

        ?>
        <tr>
            <td align="left">Moyenne cab 2006</td>
            <td align="right"><?php echo $moyenne_eval3[0]; ?>%</td>
            <td align="right"><?php echo $moyenne_eval3[1]; ?>%</td>
            <td align="right"><?php echo $moyenne_eval3[2]; ?>%</td>

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

    <sup>1</sup> Nombre de personnes ayant eu une mammographie il y a moins de 2 ans/potentiel du cabinet<br>
    <sup>2</sup> Nombre de personnes ayant eu une mammographie il y a moins de 2 ans et 3 mois/potentiel du cabinet<br>
    <sup>3</sup> Nombre de personnes ayant eu une mammographie il y a moins de 2 ans et 6 mois/potentiel du cabinet<br>
    <?php
}

function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_sein, $total;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    /*
    foreach($tcabinet as $cab){
         $t_sein[$cab] = 0;
    }







    $req="SELECT cabinet, total_sein ".
             "FROM histo_account ".
             "WHERE cabinet!='zTest' and cabinet!='irdes'  ".
             "AND dmaj<='$date 23:59:59' ";
             "ORDER BY cabinet, dmaj ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $total=0;

    while(list($cab, $total_sein) = mysql_fetch_row($res)) {
         $t_sein[$cab]=$total_sein;
    //	 $total+=$total_sein;
    }

    foreach($tcabinet as $cab)
    {
        $total+=$t_sein[$cab];
    }
    */
//////////////TAUX DE CONFORMITE CANCER DU SEIN///////////////////////

////détermination des cabinets qui étaient actifs à la période pour ne pas les prendre en compte pour le calcul de la moyenne
    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes' and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
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
    foreach ($tcabinet as $cab)
    {
        $actif[$cab]='non';
    }
    $total=0;
    $total_eval=0;
    $total_eval2=0;
    $total_eval3=0;

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $total=$total+$t_sein[$cab];
        $actif[$cab]='oui';

        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $total_eval=$total_eval+$t_sein[$cab];
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $total_eval2=$total_eval2+$t_sein[$cab];
        }
        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
        {
            $total_eval3=$total_eval3+$t_sein[$cab];
        }
    }


///////////2 ans////////////////
    $req="SELECT cabinet, count(*) ".
        "FROM depistage_sein, dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'   and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
        "AND depistage_sein.id=dossier.id ".
        "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) > '$date' ".
        "and (mamograph_date is not NULL and DATE_ADD(mamograph_date,INTERVAL 24 MONTH) >= '$date') ".
        "and depistage_sein.date<='$date' ".
        "GROUP BY cabinet, dossier.id ".
        "ORDER BY cabinet ";
//echo $req;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    foreach ($tcabinet as $cab)
    {
        $tsein[$cab][0]=0;
        $tsein[$cab][1]=0;
        $tsein[$cab][2]=0;
    }
    $moyenne[0]=$moyenne[1]=$moyenne[2]=0;
    $moyenne_eval[0]=$moyenne_eval[1]=$moyenne_eval[2]=0;
    $moyenne_eval2[0]=$moyenne_eval2[1]=$moyenne_eval2[2]=0;
    $moyenne_eval3[0]=$moyenne_eval3[1]=$moyenne_eval3[2]=0;

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $tsein[$cab][0] = $tsein[$cab][0]+1;
        $moyenne[0]=$moyenne[0]+1;

        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $moyenne_eval[0]=$moyenne_eval[0]+1;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $moyenne_eval2[0]=$moyenne_eval2[0]+1;
        }
        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
        {
            $moyenne_eval3[0]=$moyenne_eval3[0]+1;
        }
    }

///////////2 ans et 3 mois////////////////
    $req="SELECT cabinet, count(*) ".
        "FROM depistage_sein, dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'   and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date 23:59:59' AND dossier.dcreat<='$date')) ".
        "AND depistage_sein.id=dossier.id ".
        "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) > '$date' ".
        "and (mamograph_date is not NULL and DATE_ADD(mamograph_date,INTERVAL 27 MONTH) >= '$date') ".
        "and depistage_sein.date<='$date' ".
        "GROUP BY cabinet, dossier.id ".
        "ORDER BY cabinet ";
//echo $req;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $tsein[$cab][1] = $tsein[$cab][1]+1;
        $moyenne[1]=$moyenne[1]+1;

        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $moyenne_eval[1]=$moyenne_eval[1]+1;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $moyenne_eval2[1]=$moyenne_eval2[1]+1;
        }
        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
        {
            $moyenne_eval3[1]=$moyenne_eval3[1]+1;
        }
    }


///////////2 ans et 6 mois////////////////
    $req="SELECT cabinet, count(*) ".
        "FROM depistage_sein, dossier ".
        "WHERE cabinet!='zTest' and cabinet!='irdes'   and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
        "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
        "OR (dossier.actif='non' AND dossier.dmaj>'$date 23:59:59' AND dossier.dcreat<='$date')) ".
        "AND depistage_sein.id=dossier.id ".
        "AND DATE_ADD(dnaiss,INTERVAL 75 YEAR) > '$date' ".
        "and (mamograph_date is not NULL and DATE_ADD(mamograph_date,INTERVAL 30 MONTH) >= '$date') ".
        "and depistage_sein.date<='$date' ".
        "GROUP BY cabinet, dossier.id ".
        "ORDER BY cabinet ";
//echo $req;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");



    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $tsein[$cab][2] = $tsein[$cab][2]+1;
        $moyenne[2]=$moyenne[2]+1;

        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $moyenne_eval[2]=$moyenne_eval[2]+1;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $moyenne_eval2[2]=$moyenne_eval2[2]+1;
        }
        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
        {
            $moyenne_eval3[2]=$moyenne_eval3[2]+1;
        }
    }

    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <tr>
            <td></td><td align='center'>moins de 2 ans <sup>1</sup></td>
            <td align='center'>moins de 2 ans et 3 mois <sup>2</sup></td>
            <td align='center'>moins de 2 ans et 6 mois <sup>3</sup></td>
        </tr>

        <?php

        foreach($tville as $cab=>$ville) {
            if ($actif[$cab]=='non')
                $taux[0]=$taux[1]=$taux[2]="ND";
            else
            {
                if($t_sein[$cab]!=0)
                {
                    $taux[0]=$tsein[$cab][0]/$t_sein[$cab]*100;
                    $taux[0]=round($taux[0], 0);
                    $taux[0].="%";


                    $taux[1]=$tsein[$cab][1]/$t_sein[$cab]*100;
                    $taux[1]=round($taux[1], 0);
                    $taux[1].="%";


                    $taux[2]=$tsein[$cab][2]/$t_sein[$cab]*100;
                    $taux[2]=round($taux[2], 0);
                    $taux[2].="%";
                }
                else
                {
                    $taux[0]=$taux[1]=$taux[0]='ND';
                }
            }

            ?>
            <tr>
                <td align='left'><?php echo $tville[$cab]; ?></td>
                <td align='right'><?php echo $taux[0]; ?></td>
                <td align='right'><?php echo $taux[1]; ?></td>
                <td align='right'><?php echo $taux[2]; ?></td>
            </tr>
            <?php
        }
        $moyenne[0]=round($moyenne[0]/$total*100);
        $moyenne[1]=round($moyenne[1]/$total*100);
        $moyenne[2]=round($moyenne[2]/$total*100);

        ?>
        <tr>
            <td align="left">Moyenne pondérée</td>
            <td align="right"><?php echo $moyenne[0]; ?>%</td>
            <td align="right"><?php echo $moyenne[1]; ?>%</td>
            <td align="right"><?php echo $moyenne[2]; ?>%</td>

        </tr>

        <?php
        $moyenne_eval[0]=round($moyenne_eval[0]/$total_eval*100);
        $moyenne_eval[1]=round($moyenne_eval[1]/$total_eval*100);
        $moyenne_eval[2]=round($moyenne_eval[2]/$total_eval*100);

        ?>
        <tr>
            <td align="left">Moyenne eval</td>
            <td align="right"><?php echo $moyenne_eval[0]; ?>%</td>
            <td align="right"><?php echo $moyenne_eval[1]; ?>%</td>
            <td align="right"><?php echo $moyenne_eval[2]; ?>%</td>

        </tr>

        <?php
        if($total_eval2==0){
            $moyenne_eval2[0]=$moyenne_eval2[1]=$moyenne_eval2[2]="ND";
        }
        else{
            $moyenne_eval2[0]=round($moyenne_eval2[0]/$total_eval2*100);
            $moyenne_eval2[1]=round($moyenne_eval2[1]/$total_eval2*100);
            $moyenne_eval2[2]=round($moyenne_eval2[2]/$total_eval2*100);
        }

        ?>
        <tr>
            <td align="left">Moyenne cab 2005</td>
            <td align="right"><?php echo $moyenne_eval2[0]; ?>%</td>
            <td align="right"><?php echo $moyenne_eval2[1]; ?>%</td>
            <td align="right"><?php echo $moyenne_eval2[2]; ?>%</td>

        </tr>

        <?php
        if($total_eval3==0){
            $moyenne_eval3[0]=$moyenne_eval3[1]=$moyenne_eval3[2]="ND";
        }
        else{
            $moyenne_eval3[0]=round($moyenne_eval3[0]/$total_eval3*100);
            $moyenne_eval3[1]=round($moyenne_eval3[1]/$total_eval3*100);
            $moyenne_eval3[2]=round($moyenne_eval3[2]/$total_eval3*100);
        }

        ?>
        <tr>
            <td align="left">Moyenne cab 2006</td>
            <td align="right"><?php echo $moyenne_eval3[0]; ?>%</td>
            <td align="right"><?php echo $moyenne_eval3[1]; ?>%</td>
            <td align="right"><?php echo $moyenne_eval3[2]; ?>%</td>

        </tr>


    </table>
    <br><br>
    <?php

}

?>
</body>
</html>
