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
    <title>Evolution du LDL après une consultation infirmière pour les patients inclus en RCVA</title>
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

$titre="Evolution du LDL après une consultation infirmière pour les patients inclus dans le protocole RCVA";


entete_asalee($titre);
//echo $loc;
?>
<br><br>
<?php

# boucle principale
do {
    $repete=false;

    # étape 1 : affichage tableau
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {
            //affichage tableau
            case 1:
                etape_1($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal


function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet;



    $req="SELECT account.cabinet, count(*), nom_cab ".
        "FROM dossier, account ".
        "WHERE infirmiere!='' and region!='' ".
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
        $avantsup13[$cab]=0;
        $nb_dossierssup13[$cab]=0;
        $apressup13[$cab]=0;
        $delta_avantsup13[$cab]=0;
        $delta_apressup13[$cab]=0;

        $avantinf13[$cab]=0;
        $nb_dossiersinf13[$cab]=0;
        $apresinf13[$cab]=0;
        $delta_avantinf13[$cab]=0;
        $delta_apresinf13[$cab]=0;

//	 $tpat[$cab] = $pat;
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
            foreach($tville as $cab) {
                ?>
                <td align='center'><b><?php echo $cab; ?></b></td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td rowspan='5'>Pour les patients avec LDL>1.3 avant la consultation</td>
        </tr>


        <?php
        //Mise dans un tableau des lignes comprenant, pour chaque consultation, la liste des HBA.
        //Classement par id, dHBA pour pouvoir garder le dernier HBA avant consultation, et le premier après
        //Le tri sera fait ensuite.

        $req= "SELECT cabinet, dossier.id, date_exam, resultat1, ".
            "evaluation_infirmier.date as date_consult, DATEDIFF(date_exam, evaluation_infirmier.date) as deltaj ".
            "FROM dossier, liste_exam, evaluation_infirmier, cardio_vasculaire_depart ".
            "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and cabinet!='ztest' and ".
            "cabinet!='irdes' and cabinet!='ergo'  and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' and ".
            "type_exam='LDL' and cardio_vasculaire_depart.id=dossier.id ".
            "ORDER BY cabinet, dossier.id, date_consult, date_exam";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $id_prec="";
        $cabinet_prec="";

        while(list($cabinet, $dossier_id, $dLDL, $LDL, $date_consult, $deltaj)=mysql_fetch_row($res)){
            if(isset($nb_dossierssup13[$cabinet_prec])){
                if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                    if($id_prec!=""){
                        if($LDL_suiv!=0){
                            if($LDL_prec>1.3){
                                $nb_dossierssup13[$cabinet_prec]=$nb_dossierssup13[$cabinet_prec]+1;
                                $avantsup13[$cabinet_prec]=$avantsup13[$cabinet_prec]+$LDL_prec;
                                $apressup13[$cabinet_prec]=$apressup13[$cabinet_prec]+$LDL_suiv;
                                $delta_avantsup13[$cabinet_prec]=$delta_avantsup13[$cabinet_prec]+$deltaj_prec;
                                $delta_apressup13[$cabinet_prec]=$delta_apressup13[$cabinet_prec]+$deltaj_suiv;
                            }
                            else{
                                $nb_dossiersinf13[$cabinet_prec]=$nb_dossiersinf13[$cabinet_prec]+1;
                                $avantinf13[$cabinet_prec]=$avantinf13[$cabinet_prec]+$LDL_prec;
                                $apresinf13[$cabinet_prec]=$apresinf13[$cabinet_prec]+$LDL_suiv;
                                $delta_avantinf13[$cabinet_prec]=$delta_avantinf13[$cabinet_prec]+$deltaj_prec;
                                $delta_apresinf13[$cabinet_prec]=$delta_apresinf13[$cabinet_prec]+$deltaj_suiv;
                            }
                        }
                    }
                    $date_consult_prec=$date_consult;
                    $cabinet_prec=$cabinet;
                    $LDL_prec=$LDL;
                    $LDL_suiv=0;
                    $id_prec=$dossier_id;
                    $deltaj_prec=$deltaj;
                }
                else{//On a déjà changé de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                    if($date_consult_prec==$date_consult){
                        if($deltaj<0){//Le HBA est avant la consult
                            $LDL_prec=$LDL;
                            $deltaj_prec=$deltaj;
                        }
                        else{//Un HBA après la consult => on regarde s'il a déjà été enregistré
                            if($LDL_suiv==0){
                                $LDL_suiv=$LDL;
                                $deltaj_suiv=$deltaj;
                            }
                        }
                    }
                    else{
                        if($LDL_suiv!=0){
                            if($LDL_prec>1.3){
                                $nb_dossierssup13[$cabinet_prec]=$nb_dossierssup13[$cabinet_prec]+1;
                                $avantsup13[$cabinet_prec]=$avantsup13[$cabinet_prec]+$LDL_prec;
                                $apressup13[$cabinet_prec]=$apressup13[$cabinet_prec]+$LDL_suiv;
                                $delta_avantsup13[$cabinet_prec]=$delta_avantsup13[$cabinet_prec]+$deltaj_prec;
                                $delta_apressup13[$cabinet_prec]=$delta_apressup13[$cabinet_prec]+$deltaj_suiv;
                            }
                            else{
                                $nb_dossiersinf13[$cabinet_prec]=$nb_dossiersinf13[$cabinet_prec]+1;
                                $avantinf13[$cabinet_prec]=$avantinf13[$cabinet_prec]+$LDL_prec;
                                $apresinf13[$cabinet_prec]=$apresinf13[$cabinet_prec]+$LDL_suiv;
                                $delta_avantinf13[$cabinet_prec]=$delta_avantinf13[$cabinet_prec]+$deltaj_prec;
                                $delta_apresinf13[$cabinet_prec]=$delta_apresinf13[$cabinet_prec]+$deltaj_suiv;
                            }
                        }
                        $LDL_prec=$LDL;
                        $LDL_suiv=0;
                        $date_consult_prec=$date_consult;
                    }
                }
            }
            else{
                $date_consult_prec=$date_consult;
                $cabinet_prec=$cabinet;
                $LDL_prec=$LDL;
                $LDL_suiv=0;
                $id_prec=$dossier_id;
                $deltaj_prec=$deltaj;
            }
        }

        if(isset($nb_dossierssup13[$cabinet_prec])){
            if($LDL_suiv!=0){
                if($LDL_prec>1.3){
                    $nb_dossierssup13[$cabinet_prec]=$nb_dossierssup13[$cabinet_prec]+1;
                    $avantsup13[$cabinet_prec]=$avantsup13[$cabinet_prec]+$LDL_prec;
                    $apressup13[$cabinet_prec]=$apressup13[$cabinet_prec]+$LDL_suiv;
                    $delta_avantsup13[$cabinet_prec]=$delta_avantsup13[$cabinet_prec]+$deltaj_prec;
                    $delta_apressup13[$cabinet_prec]=$delta_apressup13[$cabinet_prec]+$deltaj_suiv;
                }
                else{
                    $nb_dossiersinf13[$cabinet_prec]=$nb_dossiersinf13[$cabinet_prec]+1;
                    $avantinf13[$cabinet_prec]=$avantinf13[$cabinet_prec]+$LDL_prec;
                    $apresinf13[$cabinet_prec]=$apresinf13[$cabinet_prec]+$LDL_suiv;
                    $delta_avantinf13[$cabinet_prec]=$delta_avantinf13[$cabinet_prec]+$deltaj_prec;
                    $delta_apresinf13[$cabinet_prec]=$delta_apresinf13[$cabinet_prec]+$deltaj_suiv;
                }
            }
        }


        $avanttot=array_sum($avantsup13);
        $aprestot=array_sum($apressup13);
        $delta_avanttot=array_sum($delta_avantsup13);
        $delta_aprestot=array_sum($delta_apressup13);
        $nb_dossierstot=array_sum($nb_dossierssup13);

        ?>
        <tr>
            <td>Moyenne du dernier LDL avant la consultation</td>
            <td><?php echo round($avanttot/$nb_dossierstot, 2);?></td>
            <?php
            foreach($tcabinet as $cab){
                if($nb_dossierssup13[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($avantsup13[$cab]/$nb_dossierssup13[$cab], 2)."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne du premier LDL après la consultation</Td>
            <td><?php echo round($aprestot/$nb_dossierstot, 2);?></td>
            <?php
            foreach($tcabinet as $cab){
                if($nb_dossierssup13[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($apressup13[$cab]/$nb_dossierssup13[$cab], 2)."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps avant la consultation</Td>
            <td><?php echo round($delta_avanttot/$nb_dossierstot);?></td>
            <?php
            foreach($tcabinet as $cab){
                if($nb_dossierssup13[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_avantsup13[$cab]/$nb_dossierssup13[$cab])."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps après la consultation</Td>
            <td><?php echo round($delta_aprestot/$nb_dossierstot);?></td>
            <?php
            foreach($tcabinet as $cab){
                if($nb_dossierssup13[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_apressup13[$cab]/$nb_dossierssup13[$cab])."</td>";
                }
            }
            ?>
        </tr>


        <tr>
            <td rowspan='5'>Pour les patients avec LDL<=1.3 avant la consultation</td>
        </tr>
        <?php


        $avanttot=array_sum($avantinf13);
        $aprestot=array_sum($apresinf13);
        $delta_avanttot=array_sum($delta_avantinf13);
        $delta_aprestot=array_sum($delta_apresinf13);
        $nb_dossierstot=array_sum($nb_dossiersinf13);

        ?>
        <tr>
            <td>Moyenne du dernier LDL avant la consultation</td>
            <td><?php echo round($avanttot/$nb_dossierstot, 2);?></td>
            <?php
            foreach($tcabinet as $cab){
                if($nb_dossiersinf13[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($avantinf13[$cab]/$nb_dossiersinf13[$cab], 2)."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne du premier LDL après la consultation</Td>
            <td><?php echo round($aprestot/$nb_dossierstot, 2);?></td>
            <?php
            foreach($tcabinet as $cab){
                if($nb_dossiersinf13[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($apresinf13[$cab]/$nb_dossiersinf13[$cab], 2)."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps avant la consultation</Td>
            <td><?php echo round($delta_avanttot/$nb_dossierstot);?></td>
            <?php
            foreach($tcabinet as $cab){
                if($nb_dossiersinf13[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_avantinf13[$cab]/$nb_dossiersinf13[$cab])."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps après la consultation</Td>
            <td><?php echo round($delta_aprestot/$nb_dossierstot);?></td>
            <?php
            foreach($tcabinet as $cab){
                if($nb_dossiersinf13[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_apresinf13[$cab]/$nb_dossiersinf13[$cab])."</td>";
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
