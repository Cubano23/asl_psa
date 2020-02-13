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
    <title>Indicateurs d'évaluation Asalée : taux de suivi des diabétiques</title>
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

entete_asalee("Indicateurs d'évaluation Asalée : taux de suivi des diabétiques");

//echo $loc;
?>

<br><br>
<?

# boucle principale
do {
    $repete=false;

    # étape 1 : talbeua à la date du jour
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            //tableau à la date du jour
            case 1:
                etape_1($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//tableau à la date ud jour
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;

    $req="SELECT cabinet, total_diab2, nom_cab ".
        "FROM account ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and cabinet!='jgomes' ".
        "and cabinet!='sbirault' ".
        "GROUP BY nom_cab ".
        "ORDER BY nom_cab ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


    $potentielsaisi['tot']=0;
    $potentielsaisi['eval']=0;
    $potentielsaisi['eval2']=0;
    $potentielsaisi['eval3']=0;

    while(list($cab, $total_diab2, $ville) = mysql_fetch_row($res)) {
        $potentielsaisi[$cab]=$total_diab2;
        $t_diab[$cab]=$total_diab2;
        $tville[$cab]=$ville;
        $potentielsaisi['tot']=$potentielsaisi['tot']+$total_diab2;

        if((strcasecmp($cab, "clamecy")!=0)||(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $potentielsaisi['eval']=$potentielsaisi['eval']+$total_diab2;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $potentielsaisi['eval2']=$potentielsaisi['eval2']+$total_diab2;
        }
        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
        {
            $potentielsaisi['eval3']=$potentielsaisi['eval3']+$total_diab2;
        }
    }


//Patients avec au moins un suivi
    $req="SELECT cabinet, count(*) ".
        "FROM suivi_diabete, dossier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' and cabinet!='Saint-Varent' ".
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

    $nbsuivis['tot']=0;
    $nbsuivis['eval']=0;
    $nbsuivis['eval2']=0;
    $nbsuivis['eval3']=0;

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $nbsuivis[$cab] = $nbsuivis[$cab]+1;
        $nbsuivis['tot']=$nbsuivis['tot']+1;

        if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $nbsuivis['eval']=$nbsuivis['eval']+1;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $nbsuivis['eval2']=$nbsuivis['eval2']+1;
        }
        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
        {
            $nbsuivis['eval3']=$nbsuivis['eval3']+1;
        }
    }


    $t_diab['tot']=0;
    $t_diab['eval']=0;
    $t_diab['eval2']=0;
    $t_diab['eval3']=0;

    foreach($tville as $cab=>$ville){
        if($nbsuivis[$cab]>$potentielsaisi[$cab]){
            $t_diab[$cab]=$nbsuivis[$cab];
        }
        $t_diab['tot']=$t_diab['tot']+$t_diab[$cab];

        if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $t_diab['eval']=$t_diab['eval']+$t_diab[$cab];
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $t_diab['eval2']=$t_diab['eval2']+$t_diab[$cab];
        }
        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
        {
            $t_diab['eval3']=$t_diab['eval3']+$t_diab[$cab];
        }
    }

    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */
    $req="SELECT dossier.cabinet, count(*) ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='zTest'  and dossier.cabinet!='irdes'  and dossier.cabinet!='ergo' and ".
        "dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' and dossier.cabinet=account.cabinet ".
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
            <td></td>
            <td align='center'><b> Moyenne générale</b></td>
            <td align='center'><b>Moyenne cabinets 79</b></td>
            <td align='center'><b>Moyenne cab 2005</b></td>
            <td align='center'><b>Moyenne cab 2006</b></Td>
            <?php
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }
            ?>
        </tr>

        <?php

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and cabinet!='saint-varent' ".
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
            <td>Taux de patients diabétiques suivis dans Asalée<sup>1</sup></td>
            <td align='right'><?php echo round(($tpat['tot']-$tpat['Saint-Varent'])/($t_diab['tot']-$t_diab['Saint-Varent'])*100, 0); ?>%</td>
            <td align='right'><?php echo round(($tpat['eval']-$tpat['Saint-Varent'])/($t_diab['eval']-$t_diab['Saint-Varent'])*100, 0); ?>%</td>
            <td align='right'><?php echo ($t_diab['eval2']==0)?"0":round($tpat['eval2']/$t_diab['eval2']*100,0);?>%</td>
            <td align='right'><?php echo ($t_diab['eval2']==0)?"0":round($tpat['eval3']/$t_diab['eval3']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($t_diab[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_diab[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab,"saint-varent")==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
        </tr>




        <?php

        ///taux de diabétiques 2 vus en consult : pas ok à priori

        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' and cabinet!='saint-varent' ".
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
            <td>Taux de patients diabétiques type 2 vus en consultation<sup>2</sup></td>
            <td align='right'><?php echo round(($tpat['tot']-$tpat['Saint-Varent'])/($nbsuivis['tot']-$nbsuivis['Saint-Varent'])*100, 0); ?>%</td>
            <td align='right'><?php echo round(($tpat['eval']-$tpat['Saint-Varent'])/($nbsuivis['eval']-$nbsuivis['Saint-Varent'])*100, 0);?>%</td>
            <td align='right'><?php echo ($nbsuivis['eval2']==0)?"0":round($tpat['eval2']/$nbsuivis['eval2']*100, 0);?>%</td>
            <td align='right'><?php echo ($nbsuivis['eval3']==0)?"0":round($tpat['eval3']/$nbsuivis['eval3']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($nbsuivis[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$nbsuivis[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo (strcasecmp($cab, "saint-varent")==0)?"ND":$taux; ?></td>
                <?php
            }
            ?>
        </tr>







        <tr>
            <td>Nombre de patients ayant eu au moins 1 suivi</td>
            <td align='right'><?php echo $nbsuivis['tot']-$nbsuivis["Saint-Varent"]; ?></td>
            <td align='right'><?php echo $nbsuivis['eval']-$nbsuivis['Saint-Varent'];?></Td>
            <td align='right'><?php echo $nbsuivis['eval2'];?></td>
            <td align='right'><?php echo $nbsuivis['eval3'];?></td>
            <?php

            foreach($tcabinet as $cab) {

                ?>
                <td align='right'><?php echo (strcasecmp($cab, "Saint-Varent")==0)?"ND":$nbsuivis[$cab]; ?></td>
                <?php
            }
            ?>
        </tr>


        <tr>
            <td>Potentiel du cabinet</td>
            <td align='right'><?php echo $potentielsaisi['tot']-$potentielsaisi['Saint-Varent']; ?></td>
            <td align='right'><?php echo $potentielsaisi['eval']-$potentielsaisi['Saint-Varent'];?></td>
            <td align='right'><?php echo $potentielsaisi['eval2'];?></td>
            <td align='right'><?php echo $potentielsaisi['eval3'];?></td>
            <?php

            foreach($tcabinet as $cab) {

                ?>
                <td align='right'><?php echo (strcasecmp($cab, "Saint-Varent")==0)?"ND":$potentielsaisi[$cab]; ?></td>
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
    <sup>1</sup>Nombre de personnes ayant eu au moins un suivi du diabète dans les 5 derniers mois/potentiel du cabinet<br>
    <sup>2</sup>Nombre de personnes ayant eu au moins un suivi du diabète et une évaluation infirmière/nb personnes avec au moins un suivi
    <?
}

//arrêtés trimestriels
function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $t_diab;

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    foreach($tcabinet as $cab) {
        $t_diab[$cab]=0;
        $potentielsaisi[$cab]=0;
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
        $potentielsaisi[$cab]=$total_diab2;
    }

//Patients avec au moins un suivi
    $req="SELECT cabinet, count(*) ".
        "FROM suivi_diabete, dossier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ";

    if($date>='2008-01-01'){
        $req.=" and dossier.cabinet!='Saint-Varent' ";
    }

    $req.=	 "AND ( ((dossier.actif='oui') ".
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
    $nbsuivis['eval']=0;
    $nbsuivis['eval2']=0;
    $nbsuivis['eval3']=0;

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $nbsuivis[$cab] = $nbsuivis[$cab]+1;
        $nbsuivis['tot']=$nbsuivis['tot']+1;

        if((strcasecmp($cab, "varzy")!=0)&&(strcasecmp($cab, "clamecy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//		(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
        {
            $nbsuivis['eval']=$nbsuivis['eval']+1;
        }
        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
            (strcasecmp($cab, "chizé")==0))
        {
            $nbsuivis['eval2']=$nbsuivis['eval2']+1;
        }
        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
        {
            $nbsuivis['eval3']=$nbsuivis['eval3']+1;
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
        "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ";

    if($date>='2008-01-01'){
        $req.="AND cabinet!='Saint-Varent' ";
    }

    $req.=	 " AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
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
    $t_diab['eval2']=0;
    $t_diab['eval3']=0;
    $potentielsaisi['tot']=0;
    $potentielsaisi['eval']=0;
    $potentielsaisi['eval2']=0;
    $potentielsaisi['eval3']=0;
    $cab_prec="";

    foreach ($tcabinet as $cab)
    {
        $tpat[$cab]=0;
        $tcabinet_util[$cab]=0;

    }


    while(list($cab, $pat) = mysql_fetch_row($res)) {
//	 $tcabinet[] = $cab;

        if($cab!=$cab_prec)
        {
            $t_diab['tot']=$t_diab['tot']+$t_diab[$cab];
            $potentielsaisi['tot']=$potentielsaisi['tot']+$potentielsaisi[$cab];
            $cab_prec=$cab;
            $tcabinet_util[$cab]=$t_diab[$cab];

            if((strcasecmp($cab, "clamecy")!=0)&&(strcasecmp($cab, "varzy")!=0))//||(strcasecmp($cab, 'brioux')==0)||
//			(strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
            {
                $t_diab['eval']=$t_diab['eval']+$t_diab[$cab];
                $potentielsaisi['eval']=$potentielsaisi['eval']+$potentielsaisi[$cab];
            }
            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                (strcasecmp($cab, "chizé")==0))
            {
                $t_diab['eval2']=$t_diab['eval2']+$t_diab[$cab];
                $potentielsaisi['eval2']=$potentielsaisi['eval2']+$potentielsaisi[$cab];
            }
            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
            {
                $t_diab['eval3']=$t_diab['eval3']+$t_diab[$cab];
                $potentielsaisi['eval3']=$potentielsaisi['eval3']+$potentielsaisi[$cab];
            }
        }
    }



    ?>
    <table border=1 width='100%'>
        <tr>
            <td></td>
            <td align='center'><b> Moyenne générale</b></Td>
            <td align='center'><b>Moyenne cabinets 79</b></td>
            <td align='center'><b>Moyenne cab 2005</b></td>
            <td align='center'><b>Moyenne cab 2006</b></td>

            <?php
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }
            ?>
        </tr>

        <?php

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.=" AND dossier.cabinet!='Saint-Varent' ";
        }

        $req.=	 "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
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
        //print_r($t_diab);
        ?>

        <tr>
            <td>Taux de patients diabétiques suivis dans Asalée<sup>1</sup></td>
            <td align='right'><?php echo round($tpat['tot']/$t_diab['tot']*100, 0); ?>%</td>
            <td align='right'><?php echo round($tpat['eval']/$t_diab['eval']*100, 0);?>%</td>
            <td align='right'><?php if($t_diab['eval2']==0) echo "ND"; else echo round($tpat['eval2']/$t_diab['eval2']*100, 0);?>%</td>
            <td align='right'><?php if($t_diab['eval3']==0) echo "ND"; else echo round($tpat['eval3']/$t_diab['eval3']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$t_diab[$cab]*100;
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

        ///taux de diabétiques 2 vus en consult : pas ok à priori

        $req="SELECT cabinet, count(*) ".
            "FROM suivi_diabete, dossier, evaluation_infirmier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'   and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ";

        if($date>='2008-01-01'){
            $req.=" AND dossier.cabinet!='saint-varent' ";
        }

        $req.=	 "AND ( ((dossier.actif='oui') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date')) and dossier.dcreat<='$date') ".
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
            <td>Taux de patients diabétiques type 2 vus en consultation<sup>2</sup></td>
            <td align='right'><?php echo round($tpat['tot']/$nbsuivis['tot']*100, 0); ?>%</td>
            <td align='right'><?php echo round($tpat['eval']/$nbsuivis['eval']*100, 0);?>%</td>
            <td align='right'><?php if($t_diab['eval2']==0) echo "ND"; else echo round($tpat['eval2']/$nbsuivis['eval2']*100, 0);?>%</td>
            <td align='right'><?php if($t_diab['eval3']==0) echo "ND"; else echo round($tpat['eval3']/$nbsuivis['eval3']*100, 0);?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($tcabinet_util[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$nbsuivis[$cab]*100;
                    $taux=round($taux, 0);
                    $taux.="%";
                }


                ?>
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>









        <tr>
            <td>Nombre de patients ayant eu au moins 1 suivi</td>
            <td align='right'><?php echo $nbsuivis['tot']; ?></td>
            <td align='right'><?php echo $nbsuivis['eval'];?></td>
            <td align='right'><?php echo $nbsuivis['eval2'];?></td>
            <td align='right'><?php echo $nbsuivis['eval3'];?></td>
            <?
            foreach($tcabinet as $cab) {

                ?>
                <td align='right'><?php
                    if ($tcabinet_util[$cab]==0)
                        echo "ND";
                    else
                    {
                        echo $nbsuivis[$cab];
                    }
                    ?>
                </td>
                <?
            }
            ?>
        </tr>


        <tr>
            <td>Potentiel du cabinet</td>
            <td align='right'><?php echo $potentielsaisi['tot']; ?></td>
            <td align='right'><?php echo $potentielsaisi['eval'];?></Td>
            <td align='right'><?php echo $potentielsaisi['eval2'];?></td>
            <td align='right'><?php echo $potentielsaisi['eval3'];?></td>
            <?php

            foreach($tcabinet as $cab) {

                ?>
                <td align='right'><?php if ($tcabinet_util[$cab]==0)
                        echo "ND";
                    else
                    {
                        echo $potentielsaisi[$cab]; 									}
                    ?>
                </td>
                <?
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
