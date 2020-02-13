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
    <title>Evolution du HBA1c après cinq consultations infirmière</title>
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

$titre="Evolution du HBA1c après cinq consultations infirmière <br/> examen pris en compte : postérieur au 01/01/2009";


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

//affichage tableau
function etape_1(&$repete) {
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet;



    $req="SELECT account.cabinet, count(*), nom_cab ".
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


    while(list($cab, $pat, $ville) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
        //$tville[]=$ville;
        $avantsup7[$cab]=0;
        $nb_dossierssup7[$cab]=0;
        $apressup7[$cab]=0;
        $delta_avantsup7[$cab]=0;
        $delta_apressup7[$cab]=0;

        $avantinf7[$cab]=0;
        $nb_dossiersinf7[$cab]=0;
        $apresinf7[$cab]=0;
        $delta_avantinf7[$cab]=0;
        $delta_apresinf7[$cab]=0;

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
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $cab; ?></b></td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td rowspan='6'>Pour les patients avec HBA1c>7 avant la 1ère consultation</td>
        </tr>


        <?php
        //Mise dans un tableau des lignes comprenant, pour chaque consultation, la liste des HBA.
        //Classement par id, dHBA pour pouvoir garder le dernier HBA avant consultation, et le premier après
        //Le tri sera fait ensuite.

        $req= "SELECT cabinet, dossier.id, date_exam, resultat1, ".
            "evaluation_infirmier.date as date_consult, ".
            "DATEDIFF(date_exam, evaluation_infirmier.date) as deltaj ".
            "FROM dossier, suivi_diabete, evaluation_infirmier, liste_exam ".
            "WHERE liste_exam.id=dossier.id AND evaluation_infirmier.id=dossier.id and liste_exam.date_exam > '2008-12-31' and ".
            "dossier.cabinet!='jgomes' and ".
            "cabinet!='irdes' and cabinet!='ergo'  and dossier.cabinet!='jgomes' and ".
            "dossier.cabinet!='sbirault' and type_exam='HBA1c' ".
            "and suivi_diabete.dossier_id=liste_exam.id ".
            "ORDER BY cabinet, dossier.id, date_consult, date_exam";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $id_prec="";
        $cabinet_prec="";

        while(list($cabinet, $dossier_id, $dHBA, $ResHBA, $date_consult, $deltaj)=mysql_fetch_row($res)){
            if(isset($nb_dossierssup7[$cabinet_prec])){
                if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                    if($id_prec!=""){
                        if(($hba_suiv!=0)&&($nb_consult==5)){
                            if($hba_prec>7){
                                $nb_dossierssup7[$cabinet_prec]=$nb_dossierssup7[$cabinet_prec]+1;
                                $avantsup7[$cabinet_prec]=$avantsup7[$cabinet_prec]+$hba_prec;
                                $apressup7[$cabinet_prec]=$apressup7[$cabinet_prec]+$hba_suiv;
                                $delta_avantsup7[$cabinet_prec]=$delta_avantsup7[$cabinet_prec]+$deltaj_prec;
                                $delta_apressup7[$cabinet_prec]=$delta_apressup7[$cabinet_prec]+$deltaj_suiv;
                            }
                            else{
                                $nb_dossiersinf7[$cabinet_prec]=$nb_dossiersinf7[$cabinet_prec]+1;
                                $avantinf7[$cabinet_prec]=$avantinf7[$cabinet_prec]+$hba_prec;
                                $apresinf7[$cabinet_prec]=$apresinf7[$cabinet_prec]+$hba_suiv;
                                $delta_avantinf7[$cabinet_prec]=$delta_avantinf7[$cabinet_prec]+$deltaj_prec;
                                $delta_apresinf7[$cabinet_prec]=$delta_apresinf7[$cabinet_prec]+$deltaj_suiv;
                            }
                        }
                    }
                    $nb_consult=1;
                    $date_consult_prec=$date_consult;
                    $cabinet_prec=$cabinet;
                    $hba_prec=$ResHBA;
                    $hba_suiv=0;
                    $id_prec=$dossier_id;
                    $deltaj_prec=$deltaj;
                }
                else{//On a déjà changé de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                    if($date_consult_prec==$date_consult){
                        if($deltaj<0){//Le HBA est avant la 1ère consult
                            if($nb_consult==1){
                                $hba_prec=$ResHBA;
                                $deltaj_prec=$deltaj;
                            }
                        }
                        else{//Un HBA après la 3ème consult => on regarde s'il a déjà été enregistré
                            if($hba_suiv==0){
                                if($nb_consult==5){
                                    $hba_suiv=$ResHBA;
                                    $deltaj_suiv=$deltaj;
                                }
                            }
                        }
                    }
                    else{ //On est sur une nieme consult de ce dossier
                        if($nb_consult<5){ //C'est la deuxième consult ou 3eme
                            $nb_consult++;
                            $date_consult_prec=$date_consult;
                        }
                        else{ //On est sur une consultation suivante
                            if(($hba_suiv!=0)&&($nb_consult==5)){
                                $nb_consult++;
                                if($hba_prec>7){
                                    $nb_dossierssup7[$cabinet_prec]=$nb_dossierssup7[$cabinet_prec]+1;
                                    $avantsup7[$cabinet_prec]=$avantsup7[$cabinet_prec]+$hba_prec;
                                    $apressup7[$cabinet_prec]=$apressup7[$cabinet_prec]+$hba_suiv;
                                    $delta_avantsup7[$cabinet_prec]=$delta_avantsup7[$cabinet_prec]+$deltaj_prec;
                                    $delta_apressup7[$cabinet_prec]=$delta_apressup7[$cabinet_prec]+$deltaj_suiv;
                                }
                                else{
                                    $nb_dossiersinf7[$cabinet_prec]=$nb_dossiersinf7[$cabinet_prec]+1;
                                    $avantinf7[$cabinet_prec]=$avantinf7[$cabinet_prec]+$hba_prec;
                                    $apresinf7[$cabinet_prec]=$apresinf7[$cabinet_prec]+$hba_suiv;
                                    $delta_avantinf7[$cabinet_prec]=$delta_avantinf7[$cabinet_prec]+$deltaj_prec;
                                    $delta_apresinf7[$cabinet_prec]=$delta_apresinf7[$cabinet_prec]+$deltaj_suiv;
                                }
                            }
                        }
                    }
                }
            }
            else{
                $date_consult_prec=$date_consult;
                $cabinet_prec=$cabinet;
                $hba_prec=$ResHBA;
                $hba_suiv=0;
                $id_prec=$dossier_id;
                $deltaj_prec=$deltaj;
                $nb_consult=1;
            }
        }

        if(isset($nb_dossierssup7[$cabinet_prec])){
            if(($hba_suiv!=0)&&($nb_consult==5)){
                if($hba_prec>7){
                    $nb_dossierssup7[$cabinet_prec]=$nb_dossierssup7[$cabinet_prec]+1;
                    $avantsup7[$cabinet_prec]=$avantsup7[$cabinet_prec]+$hba_prec;
                    $apressup7[$cabinet_prec]=$apressup7[$cabinet_prec]+$hba_suiv;
                    $delta_avantsup7[$cabinet_prec]=$delta_avantsup7[$cabinet_prec]+$deltaj_prec;
                    $delta_apressup7[$cabinet_prec]=$delta_apressup7[$cabinet_prec]+$deltaj_suiv;
                }
                else{
                    $nb_dossiersinf7[$cabinet_prec]=$nb_dossiersinf7[$cabinet_prec]+1;
                    $avantinf7[$cabinet_prec]=$avantinf7[$cabinet_prec]+$hba_prec;
                    $apresinf7[$cabinet_prec]=$apresinf7[$cabinet_prec]+$hba_suiv;
                    $delta_avantinf7[$cabinet_prec]=$delta_avantinf7[$cabinet_prec]+$deltaj_prec;
                    $delta_apresinf7[$cabinet_prec]=$delta_apresinf7[$cabinet_prec]+$deltaj_suiv;
                }
            }
        }

        $avanttot=array_sum($avantsup7);
        $aprestot=array_sum($apressup7);
        $delta_avanttot=array_sum($delta_avantsup7);
        $delta_aprestot=array_sum($delta_apressup7);
        $nb_dossierstot=array_sum($nb_dossierssup7);

        ?>
        <tr>
            <td>Moyenne du dernier HBA1c avant la 1ère consultation</td>
            <td><?php echo round($avanttot/$nb_dossierstot, 2);?></td>
            <?php
            foreach($tcabinet as $cab){
                if($nb_dossierssup7[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($avantsup7[$cab]/$nb_dossierssup7[$cab], 2)."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne du premier HBA1c après la 5ème consultation</Td>
            <td><?php echo round($aprestot/$nb_dossierstot, 2);?></td>
            <?php
            foreach($tcabinet as $cab){
                if($nb_dossierssup7[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($apressup7[$cab]/$nb_dossierssup7[$cab], 2)."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps avant la 1ère consultation</Td>
            <td><?php echo round($delta_avanttot/$nb_dossierstot);?></td>
            <?php
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
            <td>Moyenne en jour du temps après la 5ème consultation</Td>
            <td><?php echo round($delta_aprestot/$nb_dossierstot);?></td>
            <?php
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
            <td>Nombre de patients concernés</Td>
            <td><?php echo $nb_dossierstot;?></td>
            <?php
            foreach($tcabinet as $cab){
                echo "<td>".$nb_dossierssup7[$cab]."</td>";
            }
            ?>
        </tr>


        <tr>
            <td rowspan='6'>Pour les patients avec HBA1c<=7 avant la 1ère consultation</td>
        </tr>
        <?php


        $avanttot=array_sum($avantinf7);
        $aprestot=array_sum($apresinf7);
        $delta_avanttot=array_sum($delta_avantinf7);
        $delta_aprestot=array_sum($delta_apresinf7);
        $nb_dossierstot=array_sum($nb_dossiersinf7);

        ?>
        <tr>
            <td>Moyenne du dernier HBA1c avant la 1ère consultation</td>
            <td><?php echo round($avanttot/$nb_dossierstot, 2);?></td>
            <?php
            foreach($tcabinet as $cab){
                if($nb_dossiersinf7[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($avantinf7[$cab]/$nb_dossiersinf7[$cab], 2)."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne du premier HBA1c après la 5ème consultation</Td>
            <td><?php echo round($aprestot/$nb_dossierstot, 2);?></td>
            <?php
            foreach($tcabinet as $cab){
                if($nb_dossiersinf7[$cab]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($apresinf7[$cab]/$nb_dossiersinf7[$cab], 2)."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps avant la 1ère consultation</Td>
            <td><?php echo round($delta_avanttot/$nb_dossierstot);?></td>
            <?php
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
            <td>Moyenne en jour du temps après la 5ème consultation</Td>
            <td><?php echo round($delta_aprestot/$nb_dossierstot);?></td>
            <?php
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
        <tr>
            <td>Nombre de patients concernés</Td>
            <td><?php echo $nb_dossierstot;?></td>
            <?php
            foreach($tcabinet as $cab){
                echo "<td>".$nb_dossiersinf7[$cab]."</td>";
            }
            ?>
        </tr>

    </table>
    <?php

}


?>
</body>
</html>
