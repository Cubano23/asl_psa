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
    <title>Evolution du LDL à 6, 12, 24 mois</title>
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

$titre="Evolution du LDL à 6, 12, 24 mois";


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


    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */

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
        $tville[]=$ville;
        $liste_patient[$cab]=array();
        $liste_patient_actif6mois[$cab]=array();
        $liste_patient_actif1an[$cab]=array();
        $liste_patient_actif2ans[$cab]=array();
        $sup13_6mois[$cab]=0;
        $sup13_1an[$cab]=0;
        $sup13_2ans[$cab]=0;

        /*	 $liste_patient_avant[$cab]=array();
             $liste_patient_avant1[$cab]=array();
             $liste_patient_avant1an[$cab]=array();
             $liste_patient_avant2ans[$cab]=array();
             $liste_patient_avant01[$cab]=array();
             $liste_patient9[$cab]=array();
             $liste_patient18[$cab]=array();
             $liste_patient24[$cab]=array();*/
//	 $tpat[$cab] = $pat;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    $tab_arrivee=array("claudie"=>'2004-06-01', 'marie-helene'=>'2004-07-27', 'marie-claire'=>'2004-11-02', 'karine'=>'2006-02-06',
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
            <td>nb dossiers avec LDL entre -3 et +1 mois</td>
            <?php



            foreach($tcabinet as $cab)
            {

                $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

                list($annee, $mois, $jour)=explode("-", $date_arrivee);

                $mois_fin=$mois+4;
                $annee_fin=$annee;

                if($mois_fin>12)
                {
                    $mois_fin=$mois_fin-12;
                    $annee_fin++;
                }

                if($mois_fin>=10)
                {
                    $borne_sup="$annee_fin-$mois_fin-$jour";
                }
                else
                {
                    $borne_sup="$annee_fin-0$mois_fin-$jour";
                }

                $mois_debut=$mois-3;
                $annee_debut=$annee;

                if($mois<=0)
                {
                    $mois_debut=$mois_debut+12;
                    $annee_debut--;
                }

                if($mois_debut>=10)
                {
                    $borne_inf="$annee_debut-$mois_debut-$jour";
                }
                else
                {
                    $borne_inf="$annee_debut-0$mois_debut-$jour";
                }

                $req="SELECT cabinet, id, LDL, dLDL
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                    "AND cabinet='$cab' ".
                    "AND dLDL!='0000-00-00' AND dLDL != 'null' ".
                    "AND dLDL>='$borne_inf' AND dLDL<='$borne_sup' ".
                    "AND LDL!='0' and LDL is not NULL ".
                    "GROUP BY dossier_id, dLDL ".
                    "ORDER BY dossier_id, LDL";

                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

                while(list($cabinet, $id, $LDL, $dLDL)=mysql_fetch_row($res))
                {
                    if(!isset($liste_patient[$cabinet][$id]))
                    {
                        $liste_patient[$cabinet][$id]=$LDL;
                    }

                }
            }



            $total_pat=0;
            $total_eval=0;
            $total_eval2=0;
            $total_eval3=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval=$total_eval+count($liste_patient[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2=$total_eval2+count($liste_patient[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3=$total_eval3+count($liste_patient[$cab]);
                }

            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient[$cab])."</td>";
            }

            ?>
        </tr>


        <tr>
            <td>ID actif 4 à 8 mois après</td>


            <?php
            foreach($tcabinet as $cab)
            {

                $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

                list($annee, $mois, $jour)=explode("-", $date_arrivee);

                $annee1=$annee;
                $mois1=$mois+4;

                if($mois1>12){
                    $mois1=$mois1-12;
                    $annee1++;
                }

                if($mois1<10)
                {
                    $borne_inf="$annee1-0$mois1-$jour";
                }
                else
                {
                    $borne_inf="$annee1-$mois1-$jour";
                }

                $annee1=$annee;
                $mois1=$mois+8;

                if($mois1>12){
                    $mois1=$mois1-12;
                    $annee1++;
                }

                if($mois1<10)
                {
                    $borne_sup="$annee1-0$mois1-$jour";
                }
                else
                {
                    $borne_sup="$annee1-$mois1-$jour";
                }


                $req="SELECT cabinet, id, LDL, dLDL
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                    "AND cabinet='$cab' ".
                    "AND LDL!='0' and LDL is not NULL ".
                    "AND dLDL!='0000-00-00' AND dLDL != 'null' ".
                    "AND dLDL>='$borne_inf' AND dLDL<='$borne_sup' ".
                    "ORDER BY cabinet, id, dLDL";

                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

                while(list($cabinet, $id, $LDL, $dLDL)=mysql_fetch_row($res))
                {
                    if(isset($liste_patient[$cab][$id])){
                        if(!isset($liste_patient_actif6mois[$cabinet][$id]))
                        {
                            $liste_patient_actif6mois[$cabinet][$id]=$LDL;

                            if($liste_patient[$cab][$id]>1.3){
                                $sup13_6mois[$cab]=$sup13_6mois[$cab]+1;
                            }
                        }
                        else
                        {
                            if($LDL<$liste_patient_actif6mois[$cabinet][$id])
                            {
                                $liste_patient_actif6mois[$cabinet][$id]=$LDL;
                            }
                        }
                    }

                }
            }

            $total_pat_6=0;
            $total_eval_6=0;
            $total_eval2_6=0;
            $total_eval3_6=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient_actif6mois[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_6=$total_eval_6+count($liste_patient_actif6mois[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_6=$total_eval2_6+count($liste_patient_actif6mois[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_6=$total_eval3_6+count($liste_patient_actif6mois[$cab]);
                }
            }

            echo "<td>$total_pat_6</td><td>$total_eval_6</td><td>$total_eval2_6</td><td>$total_eval3_6</Td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient_actif6mois[$cab])."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>
        </tr>














        <tr>
            <td>ID actif 10 à 14 mois après</td>


            <?php
            foreach($tcabinet as $cab)
            {

                $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

                list($annee, $mois, $jour)=explode("-", $date_arrivee);

                $annee1=$annee;
                $mois1=$mois+10;

                if($mois1>12){
                    $mois1=$mois1-12;
                    $annee1++;
                }

                if($mois1<10)
                {
                    $borne_inf="$annee1-0$mois1-$jour";
                }
                else
                {
                    $borne_inf="$annee1-$mois1-$jour";
                }

                $annee1=$annee+1;
                $mois1=$mois+2;

                if($mois1>12){
                    $mois1=$mois1-12;
                    $annee1++;
                }

                if($mois1<10)
                {
                    $borne_sup="$annee1-0$mois1-$jour";
                }
                else
                {
                    $borne_sup="$annee1-$mois1-$jour";
                }


                $req="SELECT cabinet, id, LDL, dLDL
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                    "AND cabinet='$cab' ".
                    "AND LDL!='0' and LDL is not NULL ".
                    "AND dLDL!='0000-00-00' AND dLDL != 'null' ".
                    "AND dLDL>='$borne_inf' AND dLDL<='$borne_sup' ".
                    "ORDER BY cabinet, id, dLDL";

                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

                while(list($cabinet, $id, $LDL, $dLDL)=mysql_fetch_row($res))
                {

                    if(isset($liste_patient[$cab][$id])){
                        if(!isset($liste_patient_actif1an[$cabinet][$id]))
                        {
                            $liste_patient_actif1an[$cabinet][$id]=$LDL;

                            if($liste_patient[$cab][$id]>1.3){
                                $sup13_1an[$cab]=$sup13_1an[$cab]+1;
                            }
                        }
                        else
                        {
                            if($LDL<$liste_patient_actif1an[$cabinet][$id])
                            {
                                $liste_patient_actif1an[$cabinet][$id]=$LDL;
                            }
                        }
                    }

                }
            }

            $total_pat_12=0;
            $total_eval_12=0;
            $total_eval2_12=0;
            $total_eval3_12=0;

            foreach($tcabinet as $cab)
            {
                $total_pat_12=$total_pat_12+count($liste_patient_actif1an[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_12=$total_eval_12+count($liste_patient_actif1an[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_12=$total_eval2_12+count($liste_patient_actif1an[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_12=$total_eval3_12+count($liste_patient_actif1an[$cab]);
                }
            }

            echo "<td>$total_pat_12</td><td>$total_eval_12</td><td>$total_eval2_12</td><td>$total_eval3_12</Td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient_actif1an[$cab])."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>
        </tr>











        <tr>
            <td>ID actif 22 à 26 mois après</td>


            <?php
            foreach($tcabinet as $cab)
            {

                $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

                list($annee, $mois, $jour)=explode("-", $date_arrivee);

                $annee1=$annee+1;
                $mois1=$mois+10;

                if($mois1>12){
                    $mois1=$mois1-12;
                    $annee1++;
                }

                if($mois1<10)
                {
                    $borne_inf="$annee1-0$mois1-$jour";
                }
                else
                {
                    $borne_inf="$annee1-$mois1-$jour";
                }

                $annee1=$annee+2;
                $mois1=$mois+2;

                if($mois1>12){
                    $mois1=$mois1-12;
                    $annee1++;
                }

                if($mois1<10)
                {
                    $borne_sup="$annee1-0$mois1-$jour";
                }
                else
                {
                    $borne_sup="$annee1-$mois1-$jour";
                }


                $req="SELECT cabinet, id, LDL, dLDL
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                    "AND cabinet='$cab' ".
                    "AND LDL!='0' and LDL is not NULL ".
                    "AND dLDL!='0000-00-00' AND dLDL != 'null' ".
                    "AND dLDL>='$borne_inf' AND dLDL<='$borne_sup' ".
                    "ORDER BY cabinet, id, dLDL";

                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

                while(list($cabinet, $id, $LDL, $dLDL)=mysql_fetch_row($res))
                {

                    if(isset($liste_patient[$cab][$id])){
                        if(!isset($liste_patient_actif2ans[$cabinet][$id]))
                        {
                            $liste_patient_actif2ans[$cabinet][$id]=$LDL;

                            if($liste_patient[$cab][$id]>1.3){
                                $sup13_2ans[$cab]=$sup13_2ans[$cab]+1;
                            }
                        }
                        else
                        {
                            if($LDL<$liste_patient_actif2ans[$cabinet][$id])
                            {
                                $liste_patient_actif2ans[$cabinet][$id]=$LDL;
                            }
                        }
                    }

                }
            }

            $total_pat_24=0;
            $total_eval_24=0;
            $total_eval2_24=0;
            $total_eval3_24=0;

            foreach($tcabinet as $cab)
            {
                $total_pat=$total_pat+count($liste_patient_actif2ans[$cab]);

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_24=$total_eval_24+count($liste_patient_actif2ans[$cab]);
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_24=$total_eval2_24+count($liste_patient_actif2ans[$cab]);
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_24=$total_eval3_24+count($liste_patient_actif2ans[$cab]);
                }
            }

            echo "<td>$total_pat_24</td><td>$total_eval_24</td><td>$total_eval2_24</td><td>$total_eval3_24</Td>";

            foreach($tcabinet as $cab)
            {
                echo "<td>".count($liste_patient_actif2ans[$cab])."</td>";
//		$liste_dossier[$cab]=array();
            }
            ?>
        </tr>




















        <tr>
            <td>LDL>1.3 entre -3 et +1mois</td>

            <?php

            $total_pat13=0;
            $total_eval13=0;
            $total_eval2_13=0;
            $total_eval3_13=0;

            foreach($tcabinet as $cab)
            {
                $total_pat_cab[$cab]=0;

                foreach($liste_patient[$cab] as $id=>$LDL)
                {
                    if($LDL>1.3){
                        $total_pat13=$total_pat13+1;

                        $total_pat_cab[$cab]=$total_pat_cab[$cab]+1;
                        $liste_dossier[$cab][$id]=$LDL;

                        if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                            (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                        {
                            $total_eval13=$total_eval13+1;
                        }
                        elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                            (strcasecmp($cab, "chizé")==0))
                        {
                            $total_eval2_13=$total_eval2_13+1;
                        }
                        elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                            (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                            (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                            (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                        {
                            $total_eval3_13=$total_eval3_13+1;
                        }
                    }

                }
            }

            echo "<td>$total_pat13</td><td>$total_eval13</td><td>$total_eval2_13</td><td>$total_eval3_13</Td>";


            foreach($tcabinet as $cab)
            {
                echo "<td>".$total_pat_cab[$cab]."</td>";
            }

            ?>
        </tr>
        <tr>
            <td>Rapport</td>
            <td><?php echo round(100*$total_pat13/$total_pat);?>%</td>
            <td><?php echo round(100*$total_eval13/$total_eval);?>%</td>
            <td><?php echo round(100*$total_eval2_13/$total_eval2);?>%</td>
            <td><?php echo round(100*$total_eval3_13/$total_eval3);?>%</td>

            <?php
            foreach($tcabinet as $cab)
            {
                if(count($liste_patient[$cab])==0)
                    $nb="ND";
                else
                    $nb=round(100*$total_pat_cab[$cab]/(count($liste_patient[$cab])));
                echo "<td>";
                echo $nb;
                echo "%</td>";
            }

            ?>
        </tr>





















        <tr>
            <td>Entre 4 et 8 mois</td>


            <?php

            foreach($tcabinet as $cab)
            {

                $nb_dossier_descendu[$cab]=0;

                $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

                list($annee, $mois, $jour)=explode("-", $date_arrivee);

                $mois_dep=$mois+4;
                $annee_dep=$annee;

                if($mois_dep>12)
                {
                    $mois_dep=$mois_dep-12;
                    $annee_dep=$annee+1;
                }

                if($mois_dep>=10)
                {
                    $date_dep="$annee_dep-$mois_dep-$jour";
                }
                else
                {
                    $date_dep="$annee_dep-0$mois_dep-$jour";
                }

                //8 mois
                $mois_fin=$mois+8;
                $annee_fin=$annee;

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


                $req="SELECT cabinet, id, LDL, dLDL
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                    "AND cabinet='$cab' ".
                    "AND dLDL!='0000-00-00' AND dLDL != 'null' ".
                    "AND dLDL>='$date_dep' AND dLDL<='$date_fin' ".
                    "AND LDL!='0' and LDL is not NULL ".
                    "ORDER by cabinet, id, dLDL";

                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


                while(list($cabinet, $id, $LDL, $dLDL)=mysql_fetch_row($res))
                {

                    if(!isset($liste_patient_6mois[$cabinet][$id]))
                    {
                        $liste_patient_6mois[$cabinet][$id]=$LDL;

                        if($LDL<=1.3)
                        {
                            if(isset($liste_dossier[$cabinet][$id]))
                            {
                                $nb_dossier_descendu[$cabinet]=$nb_dossier_descendu[$cabinet]+1;
                            }
                        }
                    }
                    else{
                        if($liste_patient_6mois[$cabinet][$id]>$LDL)
                        {
                            if($liste_patient_6mois[$cabinet][$id]>1.3){
                                if($LDL<=1.3)
                                {
                                    if(isset($liste_dossier[$cabinet][$id]))
                                    {
                                        $nb_dossier_descendu[$cabinet]=$nb_dossier_descendu[$cabinet]+1;
                                    }
                                }
                            }
                            $liste_patient_6mois[$cabinet][$id]=$LDL;
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
                if(isset($liste_patient_6mois[$cab])){
                    $total_pat=$total_pat+count($liste_patient_6mois[$cab]);

                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $total_eval=$total_eval+count($liste_patient_6mois[$cab]);
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $total_eval2=$total_eval2+count($liste_patient_6mois[$cab]);
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $total_eval3=$total_eval3+count($liste_patient_6mois[$cab]);
                    }
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</td>";

            foreach($tcabinet as $cab)
            {
                if(isset($liste_patient_6mois[$cab])){
                    echo "<td>".count($liste_patient_6mois[$cab])."</td>";
                }
                else
                    echo "<td>ND</td>";
            }

            ?>
        </tr>
        <?php


        ?><tr>
            <td>ID avec LDL <= 1.3</td>

            <?php
            $total_pat6mois_13=0;
            $total_eval6mois_13=0;
            $total_eval2_6mois_13=0;
            $total_eval3_6mois_13=0;

            foreach($tcabinet as $cab)
            {
                $total_pat_cab_13[$cab]=0;

                if(isset($liste_patient_6mois[$cab])){

                    foreach($liste_patient_6mois[$cab] as $id=>$LDL)
                    {
                        if($LDL<=1.3){
                            $total_pat6mois_13=$total_pat6mois_13+1;

                            $total_pat_cab_13[$cab]=$total_pat_cab_13[$cab]+1;

                            if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                                (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                            {
                                $total_eval6mois_13=$total_eval6mois_13+1;
                            }
                            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                                (strcasecmp($cab, "chizé")==0))
                            {
                                $total_eval2_6mois_13=$total_eval2_6mois_13+1;
                            }
                            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                            {
                                $total_eval3_6mois_13=$total_eval3_6mois_13+1;
                            }
                        }
                    }
                }
            }

            echo "<td>$total_pat6mois_13</td><td>$total_eval6mois_13</td><td>$total_eval2_6mois_13</td><td>$total_eval3_6mois_13</td>";


            foreach($tcabinet as $cab)
            {
                echo "<td>".$total_pat_cab_13[$cab]."</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Nb dossiers avec LDL >1.3 passant <= 1.3</Td>

            <?php
            $total_des=0;
            $total_eval_des=0;
            $total_eval2_des=0;
            $total_eval3_des=0;

            foreach($tcabinet as $cab)
            {
                $total_des=$total_des+$nb_dossier_descendu[$cab];

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_des=$total_eval_des+$nb_dossier_descendu[$cab];
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_des=$total_eval2_des+$nb_dossier_descendu[$cab];
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_des=$total_eval3_des+$nb_dossier_descendu[$cab];
                }
            }
            echo "<td>$total_des</td><td>$total_eval_des</td><td>$total_eval2_des</td><td>$total_eval3_des</td>";


            foreach($tcabinet as $cab)
            {
                echo "<td>".$nb_dossier_descendu[$cab]."</td>";
            }



            ?>
        </tr>
        <tr>
            <td>Rapport</td>

            <?php
            echo "<td>".round(100*$total_des/$total_pat13)."%</td>";
            echo "<td>".round(100*$total_eval_des/$total_eval13)."%</td>";
            echo "<td>".round(100*$total_eval2_des/$total_eval2_13)."%</td>";
            echo "<td>".round(100*$total_eval3_des/$total_eval3_13)."%</td>";

            foreach($tcabinet as $cab)
            {
                if($total_pat_cab[$cab]==0)
                {
                    echo "<td>ND</td>";
                }
                else
                    echo "<td>".round(100*$nb_dossier_descendu[$cab]/$total_pat_cab[$cab])."%</td>";
            }
            ?>

        </tr>


































        <tr>
            <td>Entre 10 et 14 mois</td>


            <?php

            foreach($tcabinet as $cab)
            {

                $nb_dossier_descendu[$cab]=0;

                $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

                list($annee, $mois, $jour)=explode("-", $date_arrivee);

                $mois_dep=$mois+10;
                $annee_dep=$annee;

                if($mois_dep>12)
                {
                    $mois_dep=$mois_dep-12;
                    $annee_dep=$annee_dep+1;
                }

                if($mois_dep>=10)
                {
                    $date_dep="$annee_dep-$mois_dep-$jour";
                }
                else
                {
                    $date_dep="$annee_dep-0$mois_dep-$jour";
                }

                //8 mois
                $mois_fin=$mois+2;
                $annee_fin=$annee+1;

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


                $req="SELECT cabinet, id, LDL, dLDL
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                    "AND cabinet='$cab' ".
                    "AND dLDL!='0000-00-00' AND dLDL != 'null' ".
                    "AND dLDL>='$date_dep' AND dLDL<='$date_fin' ".
                    "AND LDL!='0' and LDL is not NULL ".
                    "ORDER by cabinet, id, dLDL";

                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


                while(list($cabinet, $id, $LDL, $dLDL)=mysql_fetch_row($res))
                {

                    if(!isset($liste_patient_1an[$cabinet][$id]))
                    {
                        $liste_patient_1an[$cabinet][$id]=$LDL;

                        if($LDL<=1.3)
                        {
                            if(isset($liste_dossier[$cabinet][$id]))
                            {
                                $nb_dossier_descendu[$cabinet]=$nb_dossier_descendu[$cabinet]+1;
                            }
                        }
                    }
                    else{
                        if($liste_patient_1an[$cabinet][$id]>$LDL)
                        {
                            if($liste_patient_1an[$cabinet][$id]>1.3){
                                if($LDL<=1.3)
                                {
                                    if(isset($liste_dossier[$cabinet][$id]))
                                    {
                                        $nb_dossier_descendu[$cabinet]=$nb_dossier_descendu[$cabinet]+1;
                                    }
                                }
                            }
                            $liste_patient_1an[$cabinet][$id]=$LDL;
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
                if(isset($liste_patient_1an[$cab])){
                    $total_pat=$total_pat+count($liste_patient_1an[$cab]);

                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $total_eval=$total_eval+count($liste_patient_1an[$cab]);
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $total_eval2=$total_eval2+count($liste_patient_1an[$cab]);
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $total_eval3=$total_eval3+count($liste_patient_1an[$cab]);
                    }
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</td>";

            foreach($tcabinet as $cab)
            {
                if(isset($liste_patient_1an[$cab])){
                    echo "<td>".count($liste_patient_1an[$cab])."</td>";
                }
                else
                    echo "<td>ND</td>";
            }

            ?>
        </tr>
        <?php


        ?><tr>
            <td>ID avec LDL <= 1.3</td>

            <?php
            $total_pat1an_13=0;
            $total_eval1an_13=0;
            $total_eval2_1an_13=0;
            $total_eval3_1an_13=0;

            foreach($tcabinet as $cab)
            {
                $total_pat_cab_13[$cab]=0;

                if(isset($liste_patient_1an[$cab])){

                    foreach($liste_patient_1an[$cab] as $id=>$LDL)
                    {
                        if($LDL<=1.3){
                            $total_pat1an_13=$total_pat1an_13+1;

                            $total_pat_cab_13[$cab]=$total_pat_cab_13[$cab]+1;

                            if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                                (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                            {
                                $total_eval1an_13=$total_eval1an_13+1;
                            }
                            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                                (strcasecmp($cab, "chizé")==0))
                            {
                                $total_eval2_1an_13=$total_eval2_1an_13+1;
                            }
                            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                            {
                                $total_eval3_1an_13=$total_eval3_1an_13+1;
                            }
                        }
                    }
                }
            }

            echo "<td>$total_pat1an_13</td><td>$total_eval1an_13</td><td>$total_eval2_1an_13</td><td>$total_eval3_1an_13</td>";


            foreach($tcabinet as $cab)
            {
                echo "<td>".$total_pat_cab_13[$cab]."</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Nb dossiers avec LDL >1.3 passant <= 1.3 à 1 an</Td>

            <?php
            $total_des=0;
            $total_eval_des=0;
            $total_eval2_des=0;
            $total_eval3_des=0;

            foreach($tcabinet as $cab)
            {
                $total_des=$total_des+$nb_dossier_descendu[$cab];

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_des=$total_eval_des+$nb_dossier_descendu[$cab];
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_des=$total_eval2_des+$nb_dossier_descendu[$cab];
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_des=$total_eval3_des+$nb_dossier_descendu[$cab];
                }
            }
            echo "<td>$total_des</td><td>$total_eval_des</td><td>$total_eval2_des</td><td>$total_eval3_des</td>";


            foreach($tcabinet as $cab)
            {
                echo "<td>".$nb_dossier_descendu[$cab]."</td>";
            }



            ?>
        </tr>
        <tr>
            <td>Rapport</td>

            <?php
            echo "<td>".round(100*$total_des/$total_pat13)."%</td>";
            echo "<td>".round(100*$total_eval_des/$total_eval13)."%</td>";
            echo "<td>".round(100*$total_eval2_des/$total_eval2_13)."%</td>";
            echo "<td>".round(100*$total_eval3_des/$total_eval3_13)."%</td>";

            foreach($tcabinet as $cab)
            {
                if($total_pat_cab[$cab]==0)
                {
                    echo "<td>ND</td>";
                }
                else
                    echo "<td>".round(100*$nb_dossier_descendu[$cab]/$total_pat_cab[$cab])."%</td>";
            }
            ?>

        </tr>


























































        <tr>
            <td>Entre 22 et 24 mois</td>


            <?php

            foreach($tcabinet as $cab)
            {

                $nb_dossier_descendu[$cab]=0;

                $date_arrivee=$tab_arrivee[$tab_cab_inf[$cab]];

                list($annee, $mois, $jour)=explode("-", $date_arrivee);

                $mois_dep=$mois+10;
                $annee_dep=$annee+1;

                if($mois_dep>12)
                {
                    $mois_dep=$mois_dep-12;
                    $annee_dep=$annee+1;
                }

                if($mois_dep>=10)
                {
                    $date_dep="$annee_dep-$mois_dep-$jour";
                }
                else
                {
                    $date_dep="$annee_dep-0$mois_dep-$jour";
                }

                //8 mois
                $mois_fin=$mois+2;
                $annee_fin=$annee+2;

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


                $req="SELECT cabinet, id, LDL, dLDL
	 FROM suivi_diabete as dep, dossier
	 WHERE dep.dossier_id=dossier.id ".
                    "AND cabinet='$cab' ".
                    "AND dLDL!='0000-00-00' AND dLDL != 'null' ".
                    "AND dLDL>='$date_dep' AND dLDL<='$date_fin' ".
                    "AND LDL!='0' and LDL is not NULL ".
                    "ORDER by cabinet, id, dLDL";

                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


                while(list($cabinet, $id, $LDL, $dLDL)=mysql_fetch_row($res))
                {

                    if(!isset($liste_patient_2ans[$cabinet][$id]))
                    {
                        $liste_patient_2ans[$cabinet][$id]=$LDL;

                        if($LDL<=1.3)
                        {
                            if(isset($liste_dossier[$cabinet][$id]))
                            {
                                $nb_dossier_descendu[$cabinet]=$nb_dossier_descendu[$cabinet]+1;
                            }
                        }
                    }
                    else{
                        if($liste_patient_2ans[$cabinet][$id]>$LDL)
                        {
                            if($liste_patient_2ans[$cabinet][$id]>1.3){
                                if($LDL<=1.3)
                                {
                                    if(isset($liste_dossier[$cabinet][$id]))
                                    {
                                        $nb_dossier_descendu[$cabinet]=$nb_dossier_descendu[$cabinet]+1;
                                    }
                                }
                            }
                            $liste_patient_2ans[$cabinet][$id]=$LDL;
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
                if(isset($liste_patient_2ans[$cab])){
                    $total_pat=$total_pat+count($liste_patient_2ans[$cab]);

                    if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                        (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                    {
                        $total_eval=$total_eval+count($liste_patient_2ans[$cab]);
                    }
                    elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                        (strcasecmp($cab, "chizé")==0))
                    {
                        $total_eval2=$total_eval2+count($liste_patient_2ans[$cab]);
                    }
                    elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                        (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                        (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                        (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                    {
                        $total_eval3=$total_eval3+count($liste_patient_2ans[$cab]);
                    }
                }
            }

            echo "<td>$total_pat</td><td>$total_eval</td><td>$total_eval2</td><td>$total_eval3</td>";

            foreach($tcabinet as $cab)
            {
                if(isset($liste_patient_2ans[$cab])){
                    echo "<td>".count($liste_patient_2ans[$cab])."</td>";
                }
                else
                    echo "<td>ND</td>";
            }

            ?>
        </tr>
        <?php


        ?><tr>
            <td>ID avec LDL <= 1.3</td>

            <?php
            $total_pat2ans_13=0;
            $total_eval2ans_13=0;
            $total_eval2_2ans_13=0;
            $total_eval3_2ans_13=0;

            foreach($tcabinet as $cab)
            {
                $total_pat_cab_13[$cab]=0;

                if(isset($liste_patient_2ans[$cab])){

                    foreach($liste_patient_2ans[$cab] as $id=>$LDL)
                    {
                        if($LDL<=1.3){
                            $total_pat2ans_13=$total_pat2ans_13+1;

                            $total_pat_cab_13[$cab]=$total_pat_cab_13[$cab]+1;

                            if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                                (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                            {
                                $total_eval2ans_13=$total_eval2ans_13+1;
                            }
                            elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                                (strcasecmp($cab, "chizé")==0))
                            {
                                $total_eval2_2ans_13=$total_eval2_2ans_13+1;
                            }
                            elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                                (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                                (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                                (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                            {
                                $total_eval3_2ans_13=$total_eval3_2ans_13+1;
                            }
                        }
                    }
                }
            }

            echo "<td>$total_pat2ans_13</td><td>$total_eval2ans_13</td><td>$total_eval2_2ans_13</td><td>$total_eval3_2ans_13</td>";


            foreach($tcabinet as $cab)
            {
                echo "<td>".$total_pat_cab_13[$cab]."</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Nb dossiers avec LDL >1.3 passant <= 1.3</Td>

            <?php
            $total_des=0;
            $total_eval_des=0;
            $total_eval2_des=0;
            $total_eval3_des=0;

            foreach($tcabinet as $cab)
            {
                $total_des=$total_des+$nb_dossier_descendu[$cab];

                if((strcasecmp($cab, "chatillon")==0)||(strcasecmp($cab, "argenton")==0)||(strcasecmp($cab, 'brioux')==0)||
                    (strcasecmp($cab, "niort")==0)||(strcasecmp($cab, "saint-varent")==0))
                {
                    $total_eval_des=$total_eval_des+$nb_dossier_descendu[$cab];
                }
                elseif((strcasecmp($cab, "lucquin")==0)||(strcasecmp($cab, "dominault")==0)||(strcasecmp($cab, 'paquereau')==0)||
                    (strcasecmp($cab, "chizé")==0))
                {
                    $total_eval2_des=$total_eval2_des+$nb_dossier_descendu[$cab];
                }
                elseif((strcasecmp($cab, "couture")==0)||(strcasecmp($cab, "lezay")==0)||(strcasecmp($cab, 'lezay2')==0)||
                    (strcasecmp($cab, 'chef-boutonne1')==0)||(strcasecmp($cab, 'chef-boutonne2')==0)||
                    (strcasecmp($cab, 'bouille')==0)||(strcasecmp($cab, 'la-mothe')==0)||
                    (strcasecmp($cab, 'frontenay')==0)||(strcasecmp($cab, "mauzé")==0))
                {
                    $total_eval3_des=$total_eval3_des+$nb_dossier_descendu[$cab];
                }
            }
            echo "<td>$total_des</td><td>$total_eval_des</td><td>$total_eval2_des</td><td>$total_eval3_des</td>";


            foreach($tcabinet as $cab)
            {
                echo "<td>".$nb_dossier_descendu[$cab]."</td>";
            }



            ?>
        </tr>
        <tr>
            <td>Rapport</td>

            <?php
            echo "<td>".round(100*$total_des/$total_pat13)."%</td>";
            echo "<td>".round(100*$total_eval_des/$total_eval13)."%</td>";
            echo "<td>".round(100*$total_eval2_des/$total_eval2_13)."%</td>";
            echo "<td>".round(100*$total_eval3_des/$total_eval3_13)."%</td>";

            foreach($tcabinet as $cab)
            {
                if($total_pat_cab[$cab]==0)
                {
                    echo "<td>ND</td>";
                }
                else
                    echo "<td>".round(100*$nb_dossier_descendu[$cab]/$total_pat_cab[$cab])."%</td>";
            }
            ?>

        </tr>








    </table>
    <?php

}
?>
</body>
</html>
