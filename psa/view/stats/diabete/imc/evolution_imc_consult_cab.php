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
    <title>Evolution de l'IMC après une consultation infirmière - cabinets actifs</title>
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

$titre="Evolution de l'IMC après une consultation infirmière - cabinets actifs";


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



    $req="SELECT account.cabinet, count(*), nom_cab, region ".
        "FROM dossier, account ".
        "WHERE region!='' ".
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

    $liste_reg=array();

    while(list($cab, $pat, $ville, $region) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        $tville[$cab]=$ville;
        $avantsup7[$cab]=0;
        $nb_dossierssup7[$cab]=0;
        $apressup7[$cab]=0;
        $delta_avantsup7[$cab]=0;
        $delta_apressup7[$cab]=0;

        $regions[$cab]=$region;
        $avantinf7[$cab]=0;
        $nb_dossiersinf7[$cab]=0;
        $apresinf7[$cab]=0;
        $delta_avantinf7[$cab]=0;
        $delta_apresinf7[$cab]=0;

        if(!in_array($region, $liste_reg)){
            $liste_reg[]=$region;
            $avantsup7_reg[$region]=0;
            $nb_dossierssup7_reg[$region]=0;
            $apressup7_reg[$region]=0;
            $delta_avantsup7_reg[$region]=0;
            $delta_apressup7_reg[$region]=0;
            $avantinf7_reg[$region]=0;
            $nb_dossiersinf7_reg[$region]=0;
            $apresinf7_reg[$region]=0;
            $delta_avantinf7_reg[$region]=0;
            $delta_apresinf7_reg[$region]=0;
        }
//	 $tpat[$cab] = $pat;
    }

    sort($liste_reg);

    foreach($tville as $cab=>$ville){
        $tcabinet_util[$cab]=0;
    }

    $date3mois=date("Y-m-d", mktime(1, 1, 1, date("m")-3, date('d'), date("Y")));
    $req="SELECT cabinet from evaluation_infirmier, dossier where ".
        "dossier.id=evaluation_infirmier.id and date>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        if(isset($tville[$cab])){
            $tcabinet_util[$cab]=1;
        }
    }
    $req="SELECT cabinet from suivi_diabete, dossier where ".
        "dossier.id=dossier_id and dsuivi>='$date3mois' ".
        "group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        if(isset($tville[$cab])){
            $tcabinet_util[$cab]=1;
        }
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');


//echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>

    <table border=1 width='100%'>
        <tr>
            <td colspan='2'></td><td><b>Total</b></td>
            <?php
            foreach($liste_reg as $reg){
                echo "<td align='center'><b>$reg</b></td>";
            }

            foreach($tville as $cab) {
                ?>
                <td align='center'><b><?php echo $cab; ?></b></td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td rowspan='5'>Pour les patients avec IMC>30 avant la consultation</td>
        </tr>


        <?php
        //Mise dans un tableau des lignes comprenant, pour chaque consultation, la liste des HBA.
        //Classement par id, dHBA pour pouvoir garder le dernier HBA avant consultation, et le premier après
        //Le tri sera fait ensuite.

        $req= "SELECT cabinet, dossier.id, date_exam, resultat1, `date` as date_consult, ".
            "DATEDIFF(date_exam, `date`) as deltaj, taille ".
            "FROM dossier, liste_exam, evaluation_infirmier ".
            "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and ".
            "cabinet!='ztest' and type_exam='poids' and ".
            "cabinet!='irdes' and cabinet!='ergo'  and dossier.cabinet!='jgomes' and ".
            "dossier.cabinet!='sbirault' and resultat1>0 AND taille>0 ".
            "ORDER BY cabinet, dossier.id, date_consult, date_exam";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $id_prec="";
        $cabinet_prec="";

        while(list($cabinet, $dossier_id, $dsuivi, $poids, $date_consult, $deltaj, $taille)=mysql_fetch_row($res)){
            if((isset($tcabinet_util[$cabinet_prec]))&&($tcabinet_util[$cabinet_prec]==1)){
                if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                    if($id_prec!=""){
                        if($imc_suiv!=0){
                            if($imc_prec>30){
                                $nb_dossierssup7[$cabinet_prec]=$nb_dossierssup7[$cabinet_prec]+1;
                                $avantsup7[$cabinet_prec]=$avantsup7[$cabinet_prec]+$imc_prec;
                                $apressup7[$cabinet_prec]=$apressup7[$cabinet_prec]+$imc_suiv;
                                $delta_avantsup7[$cabinet_prec]=$delta_avantsup7[$cabinet_prec]+$deltaj_prec;
                                $delta_apressup7[$cabinet_prec]=$delta_apressup7[$cabinet_prec]+$deltaj_suiv;

                                $nb_dossierssup7_reg[$regions[$cabinet_prec]]=$nb_dossierssup7_reg[$regions[$cabinet_prec]]+1;
                                $avantsup7_reg[$regions[$cabinet_prec]]=$avantsup7_reg[$regions[$cabinet_prec]]+$imc_prec;
                                $apressup7_reg[$regions[$cabinet_prec]]=$apressup7_reg[$regions[$cabinet_prec]]+$imc_suiv;
                                $delta_avantsup7_reg[$regions[$cabinet_prec]]=$delta_avantsup7_reg[$regions[$cabinet_prec]]+$deltaj_prec;
                                $delta_apressup7_reg[$regions[$cabinet_prec]]=$delta_apressup7_reg[$regions[$cabinet_prec]]+$deltaj_suiv;
                            }
                            else{
                                $nb_dossiersinf7[$cabinet_prec]=$nb_dossiersinf7[$cabinet_prec]+1;
                                $avantinf7[$cabinet_prec]=$avantinf7[$cabinet_prec]+$imc_prec;
                                $apresinf7[$cabinet_prec]=$apresinf7[$cabinet_prec]+$imc_suiv;
                                $delta_avantinf7[$cabinet_prec]=$delta_avantinf7[$cabinet_prec]+$deltaj_prec;
                                $delta_apresinf7[$cabinet_prec]=$delta_apresinf7[$cabinet_prec]+$deltaj_suiv;

                                $nb_dossiersinf7_reg[$regions[$cabinet_prec]]=$nb_dossiersinf7_reg[$regions[$cabinet_prec]]+1;
                                $avantinf7_reg[$regions[$cabinet_prec]]=$avantinf7_reg[$regions[$cabinet_prec]]+$imc_prec;
                                $apresinf7_reg[$regions[$cabinet_prec]]=$apresinf7_reg[$regions[$cabinet_prec]]+$imc_suiv;
                                $delta_avantinf7_reg[$regions[$cabinet_prec]]=$delta_avantinf7_reg[$regions[$cabinet_prec]]+$deltaj_prec;
                                $delta_apresinf7_reg[$regions[$cabinet_prec]]=$delta_apresinf7_reg[$regions[$cabinet_prec]]+$deltaj_suiv;
                            }
                        }
                    }
                    $date_consult_prec=$date_consult;
                    $cabinet_prec=$cabinet;
                    $imc_prec=$poids/($taille*$taille/10000);
                    $imc_suiv=0;
                    $id_prec=$dossier_id;
                    $deltaj_prec=$deltaj;
                }
                else{//On a déjà changé de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                    if($date_consult_prec==$date_consult){
                        if($deltaj<0){//Le HBA est avant la consult
                            $imc_prec=$poids/($taille*$taille/10000);
                            $deltaj_prec=$deltaj;
                        }
                        else{//Un HBA après la consult => on regarde s'il a déjà été enregistré
                            if($imc_suiv==0){
                                $imc_suiv=$poids/($taille*$taille/10000);
                                $deltaj_suiv=$deltaj;
                            }
                        }
                    }
                    else{
                        if($imc_suiv!=0){
                            if($imc_prec>30){
                                $nb_dossierssup7[$cabinet_prec]=$nb_dossierssup7[$cabinet_prec]+1;
                                $avantsup7[$cabinet_prec]=$avantsup7[$cabinet_prec]+$imc_prec;
                                $apressup7[$cabinet_prec]=$apressup7[$cabinet_prec]+$imc_suiv;
                                $delta_avantsup7[$cabinet_prec]=$delta_avantsup7[$cabinet_prec]+$deltaj_prec;
                                $delta_apressup7[$cabinet_prec]=$delta_apressup7[$cabinet_prec]+$deltaj_suiv;

                                $nb_dossierssup7_reg[$regions[$cabinet_prec]]=$nb_dossierssup7_reg[$regions[$cabinet_prec]]+1;
                                $avantsup7_reg[$regions[$cabinet_prec]]=$avantsup7_reg[$regions[$cabinet_prec]]+$imc_prec;
                                $apressup7_reg[$regions[$cabinet_prec]]=$apressup7_reg[$regions[$cabinet_prec]]+$imc_suiv;
                                $delta_avantsup7_reg[$regions[$cabinet_prec]]=$delta_avantsup7_reg[$regions[$cabinet_prec]]+$deltaj_prec;
                                $delta_apressup7_reg[$regions[$cabinet_prec]]=$delta_apressup7_reg[$regions[$cabinet_prec]]+$deltaj_suiv;
                            }
                            else{
                                $nb_dossiersinf7[$cabinet_prec]=$nb_dossiersinf7[$cabinet_prec]+1;
                                $avantinf7[$cabinet_prec]=$avantinf7[$cabinet_prec]+$imc_prec;
                                $apresinf7[$cabinet_prec]=$apresinf7[$cabinet_prec]+$imc_suiv;
                                $delta_avantinf7[$cabinet_prec]=$delta_avantinf7[$cabinet_prec]+$deltaj_prec;
                                $delta_apresinf7[$cabinet_prec]=$delta_apresinf7[$cabinet_prec]+$deltaj_suiv;

                                $nb_dossiersinf7_reg[$regions[$cabinet_prec]]=$nb_dossiersinf7_reg[$regions[$cabinet_prec]]+1;
                                $avantinf7_reg[$regions[$cabinet_prec]]=$avantinf7_reg[$regions[$cabinet_prec]]+$imc_prec;
                                $apresinf7_reg[$regions[$cabinet_prec]]=$apresinf7_reg[$regions[$cabinet_prec]]+$imc_suiv;
                                $delta_avantinf7_reg[$regions[$cabinet_prec]]=$delta_avantinf7_reg[$regions[$cabinet_prec]]+$deltaj_prec;
                                $delta_apresinf7_reg[$regions[$cabinet_prec]]=$delta_apresinf7_reg[$regions[$cabinet_prec]]+$deltaj_suiv;
                            }
                        }
                        $imc_prec=$poids/($taille*$taille/10000);
                        $imc_suiv=0;
                        $date_consult_prec=$date_consult;
                    }
                }
            }
            else{
                $date_consult_prec=$date_consult;
                $cabinet_prec=$cabinet;
                $imc_prec=$poids/($taille*$taille/10000);
                $imc_suiv=0;
                $id_prec=$dossier_id;
                $deltaj_prec=$deltaj;
            }
        }

        if(($imc_suiv!=0)&&($tcabinet_util[$cabinet_prec]==1)){
            if($imc_prec>30){
                $nb_dossierssup7[$cabinet_prec]=$nb_dossierssup7[$cabinet_prec]+1;
                $avantsup7[$cabinet_prec]=$avantsup7[$cabinet_prec]+$imc_prec;
                $apressup7[$cabinet_prec]=$apressup7[$cabinet_prec]+$imc_suiv;
                $delta_avantsup7[$cabinet_prec]=$delta_avantsup7[$cabinet_prec]+$deltaj_prec;
                $delta_apressup7[$cabinet_prec]=$delta_apressup7[$cabinet_prec]+$deltaj_suiv;

                $nb_dossierssup7_reg[$regions[$cabinet_prec]]=$nb_dossierssup7_reg[$regions[$cabinet_prec]]+1;
                $avantsup7_reg[$regions[$cabinet_prec]]=$avantsup7_reg[$regions[$cabinet_prec]]+$imc_prec;
                $apressup7_reg[$regions[$cabinet_prec]]=$apressup7_reg[$regions[$cabinet_prec]]+$imc_suiv;
                $delta_avantsup7_reg[$regions[$cabinet_prec]]=$delta_avantsup7_reg[$regions[$cabinet_prec]]+$deltaj_prec;
                $delta_apressup7_reg[$regions[$cabinet_prec]]=$delta_apressup7_reg[$regions[$cabinet_prec]]+$deltaj_suiv;
            }
            else{
                $nb_dossiersinf7[$cabinet_prec]=$nb_dossiersinf7[$cabinet_prec]+1;
                $avantinf7[$cabinet_prec]=$avantinf7[$cabinet_prec]+$imc_prec;
                $apresinf7[$cabinet_prec]=$apresinf7[$cabinet_prec]+$imc_suiv;
                $delta_avantinf7[$cabinet_prec]=$delta_avantinf7[$cabinet_prec]+$deltaj_prec;
                $delta_apresinf7[$cabinet_prec]=$delta_apresinf7[$cabinet_prec]+$deltaj_suiv;

                $nb_dossiersinf7_reg[$regions[$cabinet_prec]]=$nb_dossiersinf7_reg[$regions[$cabinet_prec]]+1;
                $avantinf7_reg[$regions[$cabinet_prec]]=$avantinf7_reg[$regions[$cabinet_prec]]+$imc_prec;
                $apresinf7_reg[$regions[$cabinet_prec]]=$apresinf7_reg[$regions[$cabinet_prec]]+$imc_suiv;
                $delta_avantinf7_reg[$regions[$cabinet_prec]]=$delta_avantinf7_reg[$regions[$cabinet_prec]]+$deltaj_prec;
                $delta_apresinf7_reg[$regions[$cabinet_prec]]=$delta_apresinf7_reg[$regions[$cabinet_prec]]+$deltaj_suiv;
            }
        }


        $avanttot=array_sum($avantsup7);
        $aprestot=array_sum($apressup7);
        $delta_avanttot=array_sum($delta_avantsup7);
        $delta_aprestot=array_sum($delta_apressup7);
        $nb_dossierstot=array_sum($nb_dossierssup7);

        ?>
        <tr>
            <td>Moyenne de l'IMC avant la consultation</td>
            <td><?php echo round($avanttot/$nb_dossierstot, 1);?></td>
            <?php

            foreach($liste_reg as $reg){
                if($nb_dossierssup7_reg[$reg]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($avantsup7_reg[$reg]/$nb_dossierssup7_reg[$reg], 1)."</td>";
                }
            }

            foreach($tcabinet as $cab){
                if($nb_dossierssup7[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($avantsup7[$cab]/$nb_dossierssup7[$cab], 1)."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne de l'IMC après la consultation</Td>
            <td><?php echo round($aprestot/$nb_dossierstot, 1);?></td>
            <?php
            foreach($liste_reg as $reg){
                if($nb_dossierssup7_reg[$reg]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($apressup7_reg[$reg]/$nb_dossierssup7_reg[$reg], 1)."</td>";
                }
            }

            foreach($tcabinet as $cab){
                if($nb_dossierssup7[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($apressup7[$cab]/$nb_dossierssup7[$cab], 1)."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps avant la consultation</Td>
            <td><?php echo round($delta_avanttot/$nb_dossierstot);?></td>
            <?php
            foreach($liste_reg as $reg){
                if($nb_dossierssup7_reg[$reg]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_avantsup7_reg[$reg]/$nb_dossierssup7_reg[$reg])."</td>";
                }
            }

            foreach($tcabinet as $cab){
                if($nb_dossierssup7[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_avantsup7[$cab]/$nb_dossierssup7[$cab])."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps après la consultation</Td>
            <td><?php echo round($delta_aprestot/$nb_dossierstot);?></td>
            <?php
            foreach($liste_reg as $reg){
                if($nb_dossierssup7_reg[$reg]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_apressup7_reg[$reg]/$nb_dossierssup7_reg[$reg])."</td>";
                }
            }

            foreach($tcabinet as $cab){
                if($nb_dossierssup7[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_apressup7[$cab]/$nb_dossierssup7[$cab])."</td>";
                }
            }
            ?>
        </tr>


        <tr>
            <td rowspan='5'>Pour les patients avec IMC<=30 avant la consultation</td>
        </tr>
        <?php


        $avanttot=array_sum($avantinf7);
        $aprestot=array_sum($apresinf7);
        $delta_avanttot=array_sum($delta_avantinf7);
        $delta_aprestot=array_sum($delta_apresinf7);
        $nb_dossierstot=array_sum($nb_dossiersinf7);

        ?>
        <tr>
            <td>Moyenne de l'IMC avant la consultation</td>
            <td><?php echo round($avanttot/$nb_dossierstot, 1);?></td>
            <?php
            foreach($liste_reg as $reg){
                if($nb_dossiersinf7_reg[$reg]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($avantinf7_reg[$reg]/$nb_dossiersinf7_reg[$reg], 1)."</td>";
                }
            }

            foreach($tcabinet as $cab){
                if($nb_dossiersinf7[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($avantinf7[$cab]/$nb_dossiersinf7[$cab], 1)."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne de l'IMC après la consultation</Td>
            <td><?php echo round($aprestot/$nb_dossierstot, 1);?></td>
            <?php
            foreach($liste_reg as $reg){
                if($nb_dossiersinf7_reg[$reg]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($apresinf7_reg[$reg]/$nb_dossiersinf7_reg[$reg], 1)."</td>";
                }
            }

            foreach($tcabinet as $cab){
                if($nb_dossiersinf7[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($apresinf7[$cab]/$nb_dossiersinf7[$cab], 1)."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps avant la consultation</Td>
            <td><?php echo round($delta_avanttot/$nb_dossierstot);?></td>
            <?php
            foreach($liste_reg as $reg){
                if($nb_dossiersinf7_reg[$reg]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_avantinf7_reg[$reg]/$nb_dossiersinf7_reg[$reg])."</td>";
                }
            }

            foreach($tcabinet as $cab){
                if($nb_dossiersinf7[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_avantinf7[$cab]/$nb_dossiersinf7[$cab])."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps après la consultation</Td>
            <td><?php echo round($delta_aprestot/$nb_dossierstot);?></td>
            <?php
            foreach($liste_reg as $reg){
                if($nb_dossiersinf7_reg[$reg]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_apresinf7_reg[$reg]/$nb_dossiersinf7_reg[$reg])."</td>";
                }
            }

            foreach($tcabinet as $cab){
                if($nb_dossiersinf7[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_apresinf7[$cab]/$nb_dossiersinf7[$cab])."</td>";
                }
            }
            ?>
        </tr>

    </table>
    <?php

}


?>
</body>
</html>
