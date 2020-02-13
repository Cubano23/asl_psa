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
    <title>Evolution de la moyenne IMC à 6, 12, 24 mois</title>
</head>
<body bgcolor=#FFE887>
<?php
require_once ("Config.php");
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

$titre="Evolution de la moyenne IMC à 6, 12, 24 mois";


entete_asalee($titre);
//echo $loc;
?>
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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet;



    $req="SELECT account.cabinet, count(*), nom_cab ".
        "FROM dossier, account ".
        "WHERE account.cabinet!='zTest' and account.cabinet!='irdes'   and account.cabinet!='ergo' ".
        "and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
        "AND dossier.cabinet=account.cabinet ".
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


    while(list($cab, $pat, $ville) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $liste_patient_avant1[$cab]=array();
        $liste_patient_actif6mois[$cab]=array();
        $liste_patient_actif1an[$cab]=array();
        $liste_patient_actif2ans[$cab]=array();
        $moy0[$cab]=array();
        $moy0_12[$cab]=array();
        $moy0_24[$cab]=array();
        $tville[]=$ville;
//	 $tpat[$cab] = $pat;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    $tab_arrivee=array("claudie"=>'2004-06-01', 'marie-helene'=>'2004-09-27', 'marie-claire'=>'2004-11-02', 'karine'=>'2006-02-06',
        'karine_arg'=>'2006-03-06', 'karine_bouille'=>'2006-10-31',
        'christelle'=>'2006-05-01', 'brigitte'=>'2006-07-01', 'magali'=>'2006-09-10', "claudie_tallud"=>'2005-04-01',
        "claudie_dom"=>"2005-04-01", "marie-helene_paq"=>"2005-06-01", "marie-claire_chiz"=>"2005-04-01");

    $tab_cab_inf=array("Chatillon"=>'claudie', "Dominault"=>"claudie_dom", "Lucquin"=>"claudie_tallud", "Niort"=>"marie-helene",
        "Paquereau"=>"marie-helene_paq", "Brioux"=>"marie-claire", "Chizé"=>"marie-claire_chiz", "Argenton"=>"karine_arg",
        "Saint-Varent"=>"karine", "La-Mothe"=>"christelle", "Lezay"=>"christelle", "Lezay2"=>"christelle",
        "Frontenay"=>"brigitte", "Mauzé"=>'brigitte', "Bessines"=>"brigitte", "Couture"=>"magali",
        "Chef-boutonne1"=>"magali", "Chef-boutonne2"=>"magali", "Bouille"=>"karine_bouille");

//echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>

    <table border=1 width='100%'>
        <tr>
            <td></td><td><b>Total</b></td><td><b>Moyenne eval</b></td><td><b>Moyenne cab 2005</b></Td><td><b>Moyenne cab 2006</b></Td>
            <?php
            foreach($tville as $cab) {
                ?>
                <td align='center'><b><?php echo $cab; ?></b></td>
                <?php
            }
            ?>
        </tr>






        <tr>
            <td>entre 0 et 4 mois après arrivée infirmière</td>
            <?php
            foreach($tcabinet as $cab)
            {

                $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

                list($annee, $mois, $jour)=explode("-", $date_arrivee);

                $mois_fin=$mois+4;
                $annee_dep=$annee_fin=$annee;

                if($mois_fin>12)
                {
                    $mois_fin=$mois_fin-12;
                    $annee_fin++;
                }

                if($mois_fin>=10)
                {
                    $date_fin="$annee_fin-$mois_fin-$jour";
                }
                else
                {
                    $date_fin="$annee_fin-0$mois_fin-$jour";
                }

                $mois_dep=$mois;

                if($mois_dep<=0)
                {
                    $mois_dep=$mois_dep+12;
                    $annee_dep--;
                }

                if($mois_dep>=10)
                {
                    $date_dep="$annee_dep-$mois_dep-$jour";
                }
                else
                {
                    $date_dep="$annee_dep-0$mois_dep-$jour";
                }

                $req="SELECT cabinet, id, poids, taille
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                    "AND cabinet='$cab' ".
                    "AND dsuivi!='0000-00-00' AND dsuivi != 'null' ".
                    "AND dsuivi<'$date_fin' and dsuivi>='$date_dep' ";

                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

                while(list($cabinet, $id, $poids, $taille)=mysql_fetch_row($res))
                {
                    $imc=get_imc($poids, $taille);

                    if($imc!='ND'){

                        if(!isset($liste_patient_avant1[$cabinet][$id]))
                        {
                            $liste_patient_avant1[$cabinet][$id]=$imc;
                        }
                        else
                        {
                            if($imc<$liste_patient_avant1[$cabinet][$id])
                            {
                                $liste_patient_avant1[$cabinet][$id]=$imc;
                            }
                        }
                    }

                }
            }

            $total_pat=0;
            $total_eval=0;
            $total_eval2=0;
            $total_eval3=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient_avant1[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval=$total_eval+count($liste_patient_avant1[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2=$total_eval2+count($liste_patient_avant1[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3=$total_eval3+count($liste_patient_avant1[$cab]);
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient_avant1[$cab])."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>
        </tr>




        <tr>
            <td>Moyenne de l'IMC</td>
            <?php

            $total_pat_moy=0;
            $total_eval_moy=0;
            $total_eval2_moy=0;
            $total_eval3_moy=0;

            foreach($tcabinet as $cab)
            {
                $total_pat_moy=$total_pat_moy+array_sum($liste_patient_avant1[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_moy=$total_eval_moy+array_sum($liste_patient_avant1[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_moy=$total_eval2_moy+array_sum($liste_patient_avant1[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_moy=$total_eval3_moy+array_sum($liste_patient_avant1[$cab]);
                }
            }

            echo "<td>".round($total_pat_moy/$total_pat, 1)."</td><td>".round($total_eval_moy/$total_eval, 1)."</td><td>".
                round($total_eval2_moy/$total_eval2, 1)."</td><td>".round($total_eval3_moy/$total_eval3, 1)."</td>";

            foreach($tcabinet as $cab)
            {
                if(count($liste_patient_avant1[$cab])==0){
                    echo "<td>ND</td>";
                }
                else
                    echo "<td>".round(array_sum($liste_patient_avant1[$cab])/count($liste_patient_avant1[$cab]), 1)."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>


        </tr>







        <tr>
            <td>ID avec IMC 6 à 10 mois après</td>
            <?php
            foreach($tcabinet as $cab)
            {

                $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

                list($annee, $mois, $jour)=explode("-", $date_arrivee);


                $mois_fin=$mois+10;
                $annee_dep=$annee_fin=$annee;

                if($mois_fin>12)
                {
                    $mois_fin=$mois_fin-12;
                    $annee_fin++;
                }

                if($mois_fin>=10)
                {
                    $date_fin="$annee_fin-$mois_fin-$jour";
                }
                else
                {
                    $date_fin="$annee_fin-0$mois_fin-$jour";
                }

                $mois_dep=$mois+6;

                if($mois_dep>12)
                {
                    $mois_dep=$mois_dep-12;
                    $annee_dep++;
                }

                if($mois_dep>=10)
                {
                    $date_dep="$annee_dep-$mois_dep-$jour";
                }
                else
                {
                    $date_dep="$annee_dep-0$mois_dep-$jour";
                }

                $req="SELECT cabinet, id, poids, taille
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                    "AND cabinet='$cab' ".
                    "AND dsuivi!='0000-00-00' AND dsuivi != 'null' ".
                    "AND dsuivi<'$date_fin' and dsuivi>='$date_dep' ".
//	 "AND ( (dossier.actif='oui') ".
//	 "OR (dossier.actif='non' AND dossier.dmaj>'$date6mois 00:00:00')) ";
                    "ORDER BY cabinet, id, dsuivi";

                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

                while(list($cabinet, $id, $poids, $taille)=mysql_fetch_row($res))
                {
                    $imc=get_imc($poids, $taille);

                    if($imc!='ND'){
                        if(isset($liste_patient_avant1[$cabinet][$id]))
                        {
                            if(!isset($liste_patient_actif6mois[$cabinet][$id]))
                            {
                                $liste_patient_actif6mois[$cabinet][$id]=$imc;
                            }
                            else
                            {
                                if($imc<$liste_patient_actif6mois[$cabinet][$id])
                                {
                                    $liste_patient_actif6mois[$cabinet][$id]=$imc;
                                }
                            }
                            $moy0[$cabinet][$id]=$liste_patient_avant1[$cabinet][$id];
                        }
                    }

                }
            }

            $total_pat=0;
            $total_eval=0;
            $total_eval2=0;
            $total_eval3=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient_actif6mois[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval=$total_eval+count($liste_patient_actif6mois[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2=$total_eval2+count($liste_patient_actif6mois[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3=$total_eval3+count($liste_patient_actif6mois[$cab]);
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</Td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient_actif6mois[$cab])."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>
        </tr>











        <tr>
            <td>Moyenne de leur IMC à -3 +1</td>
            <?php

            $total_pat_moy=0;
            $total_eval_moy=0;
            $total_eval2_moy=0;
            $total_eval3_moy=0;

            foreach($tcabinet as $cab)
            {
                $total_pat_moy=$total_pat_moy+array_sum($moy0[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_moy=$total_eval_moy+array_sum($moy0[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_moy=$total_eval2_moy+array_sum($moy0[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_moy=$total_eval3_moy+array_sum($moy0[$cab]);
                }
            }

            echo "<td>".round($total_pat_moy/$total_pat, 1)."</td><td>".round($total_eval_moy/$total_eval, 1)."</td><td>";
            if($total_eval2==0){
                echo "ND";
            }
            else{
                echo round($total_eval2_moy/$total_eval2, 1);
            }

            echo "</td><td>";

            if($total_eval3==0){
                echo "ND";
            }
            else{
                echo round($total_eval3_moy/$total_eval3, 1);
            }
            echo "</td>";

            foreach($tcabinet as $cab)
            {
                if(count($moy0[$cab])==0){
                    echo "<td>ND</td>";
                }
                else
                    echo "<td>".round(array_sum($moy0[$cab])/count($moy0[$cab]), 1)."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>


        </tr>











        <tr>
            <td>Moyenne de leur IMC entre 4 et 8 mois</td>
            <?php

            $total_pat_moy=0;
            $total_eval_moy=0;
            $total_eval2_moy=0;
            $total_eval3_moy=0;

            foreach($tcabinet as $cab)
            {
                $total_pat_moy=$total_pat_moy+array_sum($liste_patient_actif6mois[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_moy=$total_eval_moy+array_sum($liste_patient_actif6mois[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_moy=$total_eval2_moy+array_sum($liste_patient_actif6mois[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_moy=$total_eval3_moy+array_sum($liste_patient_actif6mois[$cab]);
                }
            }

            echo "<td>".round($total_pat_moy/$total_pat, 1)."</td><td>".round($total_eval_moy/$total_eval, 1)."</td><td>";

            if($total_eval2==0){
                echo "ND";
            }
            else{
                echo round($total_eval2_moy/$total_eval2, 1);
            }

            echo "</td><td>";

            if($total_eval3==0){
                echo "ND";
            }
            else{
                echo round($total_eval3_moy/$total_eval3, 1);
            }

            echo "</td>";

            foreach($tcabinet as $cab)
            {
                if(count($liste_patient_actif6mois[$cab])==0){
                    echo "<td>ND</td>";
                }
                else
                    echo "<td>".round(array_sum($liste_patient_actif6mois[$cab])/count($liste_patient_actif6mois[$cab]), 1)."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>


        </tr>





        <tr>
            <td>ID avec IMC 12 à 16 mois après</td>
            <?php
            foreach($tcabinet as $cab)
            {

                $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

                list($annee, $mois, $jour)=explode("-", $date_arrivee);


                $mois_fin=$mois+4;
                $annee_dep=$annee_fin=$annee;
                $annee_fin++;

                if($mois_fin>12)
                {
                    $mois_fin=$mois_fin-12;
                    $annee_fin++;
                }

                if($mois_fin>=10)
                {
                    $date_fin="$annee_fin-$mois_fin-$jour";
                }
                else
                {
                    $date_fin="$annee_fin-0$mois_fin-$jour";
                }

                $mois_dep=$mois;
                $annee_dep=$annee+1;

                if($mois_dep>12)
                {
                    $mois_dep=$mois_dep-12;
                    $annee_dep++;
                }

                if($mois_dep>=10)
                {
                    $date_dep="$annee_dep-$mois_dep-$jour";
                }
                else
                {
                    $date_dep="$annee_dep-0$mois_dep-$jour";
                }

                $req="SELECT cabinet, id, poids, taille
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                    "AND cabinet='$cab' ".
                    "AND dHBA!='0000-00-00' AND dHBA != 'null' ".
                    "AND dHBA<'$date_fin' and dHBA>='$date_dep' ".
//	 "AND ( (dossier.actif='oui') ".
//	 "OR (dossier.actif='non' AND dossier.dmaj>'$date6mois 00:00:00')) ";
                    "ORDER BY cabinet, id, dsuivi";

                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

                while(list($cabinet, $id, $poids, $taille)=mysql_fetch_row($res))
                {
                    $imc=get_imc($poids, $taille);
                    if($imc!="ND"){
                        if(isset($liste_patient_avant1[$cabinet][$id]))
                        {
                            if(!isset($liste_patient_actif1an[$cabinet][$id]))
                            {
                                $liste_patient_actif1an[$cabinet][$id]=$imc;
                            }
                            else
                            {
                                if($imc<$liste_patient_actif1an[$cabinet][$id])
                                {
                                    $liste_patient_actif1an[$cabinet][$id]=$imc;
                                }
                            }
                            $moy0_12[$cabinet][$id]=$liste_patient_avant1[$cabinet][$id];
                        }
                    }

                }
            }

            $total_pat=0;
            $total_eval=0;
            $total_eval2=0;
            $total_eval3=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient_actif1an[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval=$total_eval+count($liste_patient_actif1an[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2=$total_eval2+count($liste_patient_actif1an[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3=$total_eval3+count($liste_patient_actif1an[$cab]);
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</Td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient_actif1an[$cab])."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>
        </tr>















        <tr>
            <td>Moyenne de leur IMC à -3 +1</td>
            <?php

            $total_pat_moy=0;
            $total_eval_moy=0;
            $total_eval2_moy=0;
            $total_eval3_moy=0;

            foreach($tcabinet as $cab)
            {
                $total_pat_moy=$total_pat_moy+array_sum($moy0_12[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_moy=$total_eval_moy+array_sum($moy0_12[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_moy=$total_eval2_moy+array_sum($moy0_12[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_moy=$total_eval3_moy+array_sum($moy0_12[$cab]);
                }
            }

            echo "<td>".round($total_pat_moy/$total_pat, 1)."</td><td>".round($total_eval_moy/$total_eval, 1)."</td><td>";
            if($total_eval2==0){
                echo "ND";
            }
            else{
                echo round($total_eval2_moy/$total_eval2, 1);
            }
            echo "</td><td>";

            if($total_eval3==0){
                echo "ND";
            }
            else{
                echo round($total_eval3_moy/$total_eval3, 1);
            }

            echo "</td>";

            foreach($tcabinet as $cab)
            {
                if(count($moy0_12[$cab])==0){
                    echo "<td>ND</td>";
                }
                else
                    echo "<td>".round(array_sum($moy0_12[$cab])/count($moy0_12[$cab]), 1)."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>


        </tr>











        <tr>
            <td>Moyenne de leur IMC entre 12 et 16 mois</td>
            <?php

            $total_pat_moy=0;
            $total_eval_moy=0;
            $total_eval2_moy=0;
            $total_eval3_moy=0;

            foreach($tcabinet as $cab)
            {
                $total_pat_moy=$total_pat_moy+array_sum($liste_patient_actif1an[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_moy=$total_eval_moy+array_sum($liste_patient_actif1an[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_moy=$total_eval2_moy+array_sum($liste_patient_actif1an[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_moy=$total_eval3_moy+array_sum($liste_patient_actif1an[$cab]);
                }
            }

            echo "<td>".round($total_pat_moy/$total_pat, 1)."</td><td>".round($total_eval_moy/$total_eval, 1)."</td><td>";

            if($total_eval2==0){
                echo "ND";
            }
            else {
                echo round($total_eval2_moy/$total_eval2, 1);
            }
            echo "</td><td>";

            if($total_eval3==0){
                echo "ND";
            }
            else{
                echo round($total_eval3_moy/$total_eval3, 1);
            }
            echo "</td>";

            foreach($tcabinet as $cab)
            {
                if(count($liste_patient_actif1an[$cab])==0){
                    echo "<td>ND</td>";
                }
                else
                    echo "<td>".round(array_sum($liste_patient_actif1an[$cab])/count($liste_patient_actif1an[$cab]), 1)."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>


        </tr>








        <tr>
            <td>ID avec IMC 22 à 26 mois après</td>
            <?php
            foreach($tcabinet as $cab)
            {

                $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

                list($annee, $mois, $jour)=explode("-", $date_arrivee);


                $mois_fin=$mois+2;
                $annee_dep=$annee_fin=$annee;
                $annee_fin=$annee_fin+2;
                $annee_dep++;

                if($mois_fin>12)
                {
                    $mois_fin=$mois_fin-12;
                    $annee_fin++;
                }

                if($mois_fin>=10)
                {
                    $date_fin="$annee_fin-$mois_fin-$jour";
                }
                else
                {
                    $date_fin="$annee_fin-0$mois_fin-$jour";
                }

                $mois_dep=$mois+10;

                if($mois_dep>12)
                {
                    $mois_dep=$mois_dep-12;
                    $annee_dep++;
                }

                if($mois_dep>=10)
                {
                    $date_dep="$annee_dep-$mois_dep-$jour";
                }
                else
                {
                    $date_dep="$annee_dep-0$mois_dep-$jour";
                }

                $req="SELECT cabinet, id, poids, taille
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                    "AND cabinet='$cab' ".
                    "AND dHBA!='0000-00-00' AND dHBA != 'null' ".
                    "AND dHBA<'$date_fin' and dHBA>='$date_dep' ".
//	 "AND ( (dossier.actif='oui') ".
//	 "OR (dossier.actif='non' AND dossier.dmaj>'$date6mois 00:00:00')) ";
                    "ORDER BY cabinet, id, dsuivi";

                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

                while(list($cabinet, $id, $poids, $taille)=mysql_fetch_row($res))
                {
                    $imc=get_imc($poids, $taille);
                    if($imc!='ND'){
                        if(isset($liste_patient_avant1[$cabinet][$id]))
                        {
                            if(!isset($liste_patient_actif2ans[$cabinet][$id]))
                            {
                                $liste_patient_actif2ans[$cabinet][$id]=$imc;
                            }
                            else
                            {
                                if($imc<$liste_patient_actif2ans[$cabinet][$id])
                                {
                                    $liste_patient_actif2ans[$cabinet][$id]=$imc;
                                }
                            }
                            $moy0_24[$cabinet][$id]=$liste_patient_avant1[$cabinet][$id];
                        }
                    }

                }
            }

            $total_pat=0;
            $total_eval=0;
            $total_eval2=0;
            $total_eval3=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient_actif2ans[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval=$total_eval+count($liste_patient_actif2ans[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2=$total_eval2+count($liste_patient_actif2ans[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3=$total_eval3+count($liste_patient_actif2ans[$cab]);
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</Td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient_actif2ans[$cab])."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>
        </tr>













        <tr>
            <td>Moyenne de leur IMC à -3 +1</td>
            <?php

            $total_pat_moy=0;
            $total_eval_moy=0;
            $total_eval2_moy=0;
            $total_eval3_moy=0;

            foreach($tcabinet as $cab)
            {
                $total_pat_moy=$total_pat_moy+array_sum($moy0_24[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_moy=$total_eval_moy+array_sum($moy0_24[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_moy=$total_eval2_moy+array_sum($moy0_24[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_moy=$total_eval3_moy+array_sum($moy0_24[$cab]);
                }
            }

            echo "<td>".round($total_pat_moy/$total_pat, 1)."</td><td>".round($total_eval_moy/$total_eval, 1)."</td><td>";
            if($total_eval2==0){
                echo "ND";
            }
            else{
                echo round($total_eval2_moy/$total_eval2, 1);
            }
            echo "</td><td>";

            if($total_eval3==0){
                echo "ND";
            }
            else{
                echo round($total_eval3_moy/$total_eval3, 1);
            }

            echo "</td>";

            foreach($tcabinet as $cab)
            {
                if(count($moy0_24[$cab])==0){
                    echo "<td>ND</td>";
                }
                else
                    echo "<td>".round(array_sum($moy0_24[$cab])/count($moy0_24[$cab]), 1)."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>


        </tr>











        <tr>
            <td>Moyenne de leur IMC entre 22 et 26 mois</td>
            <?php

            $total_pat_moy=0;
            $total_eval_moy=0;
            $total_eval2_moy=0;
            $total_eval3_moy=0;

            foreach($tcabinet as $cab)
            {
                $total_pat_moy=$total_pat_moy+array_sum($liste_patient_actif2ans[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_moy=$total_eval_moy+array_sum($liste_patient_actif2ans[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_moy=$total_eval2_moy+array_sum($liste_patient_actif2ans[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_moy=$total_eval3_moy+array_sum($liste_patient_actif2ans[$cab]);
                }
            }

            echo "<td>".round($total_pat_moy/$total_pat, 1)."</td><td>".round($total_eval_moy/$total_eval, 1)."</td><td>";

            if($total_eval2==0){
                echo "ND";
            }
            else {
                echo round($total_eval2_moy/$total_eval2, 1);
            }
            echo "</td><td>";

            if($total_eval3==0){
                echo "ND";
            }
            else{
                echo round($total_eval3_moy/$total_eval3, 1);
            }
            echo "</td>";

            foreach($tcabinet as $cab)
            {
                if(count($liste_patient_actif2ans[$cab])==0){
                    echo "<td>ND</td>";
                }
                else
                    echo "<td>".round(array_sum($liste_patient_actif2ans[$cab])/count($liste_patient_actif2ans[$cab]), 1)."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>


        </tr>








    </table>
    <?php

}


function get_imc($poids, $taille){
    if(($taille==0)||($taille=='')||($taille=="NULL")){
        return 'ND';
    }

    return $poids/($taille*$taille/10000);
}


?>
</body>
</html>
