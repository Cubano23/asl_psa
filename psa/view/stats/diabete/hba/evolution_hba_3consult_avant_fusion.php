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
    <title>Evolution du HBA1c apr�s trois consultations infirmi�re</title>
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

$titre="Evolution du HBA1c apr�s trois consultations infirmi�re";


entete_asalee($titre);
//echo $loc;
?>
<br><br>
<?php

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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet;

    $req="SELECT cabinet, nom_cab, region FROM account WHERE region!='' order by nom_cab";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $tcabinet=array();

    $reg=array();

    while(list($cab, $ville, $region)=mysql_fetch_row($res)){
        if($region!=''){
            $tcabinet[] = $cab;
            $tville[$cab]=$ville;
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

            $avantsup7[$region]=0;
            $nb_dossierssup7[$region]=0;
            $apressup7[$region]=0;
            $delta_avantsup7[$region]=0;
            $delta_apressup7[$region]=0;

            $avantinf7[$region]=0;
            $nb_dossiersinf7[$region]=0;
            $apresinf7[$region]=0;
            $delta_avantinf7[$region]=0;
            $delta_apresinf7[$region]=0;

            $regions[$cab]=$region;

            if(!in_array($region, $reg)){
                $reg[]=$region;
            }
        }
    }

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

    while(list($cab, $pat, $ville, $region) = mysql_fetch_row($res)) {
        if($region!=''){
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

            $avantsup7[$region]=0;
            $nb_dossierssup7[$region]=0;
            $apressup7[$region]=0;
            $delta_avantsup7[$region]=0;
            $delta_apressup7[$region]=0;

            $avantinf7[$region]=0;
            $nb_dossiersinf7[$region]=0;
            $apresinf7[$region]=0;
            $delta_avantinf7[$region]=0;
            $delta_apresinf7[$region]=0;

        }
//	 $tpat[$cab] = $pat;
    }

    sort($reg);

    $mois=array('01'=>'Janvier', '02'=>'F�vrier', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Ao�t', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'D�cembre');


//echo '<b>Donn�es � la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>

    <table border=1 width='100%'>
        <tr>
            <td colspan='2'></td><td><b>Total</b></td>
            <?php
            foreach($reg as $region){
                echo "<td align='center'><b>moyenne $region</b></td>";
            }
            foreach($tville as $cab=>$nom_cab) {
                if($_SESSION["national"]==1){
                    echo "<td align='center'><b>$nom_cab</b></td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td align='center'><b>$nom_cab</b></td>";
                    }
                }
            }
            ?>
        </tr>
        <tr>
            <td rowspan='6'>Pour les patients avec HBA1c>7 avant la 1�re consultation</td>
        </tr>


        <?php
        //Mise dans un tableau des lignes comprenant, pour chaque consultation, la liste des HBA.
        //Classement par id, dHBA pour pouvoir garder le dernier HBA avant consultation, et le premier apr�s
        //Le tri sera fait ensuite.

        $req= "SELECT cabinet, dossier_id, dHBA, ResHBA, `date` as date_consult, DATEDIFF(dHBA, `date`) as deltaj ".
            "FROM dossier, suivi_diabete, evaluation_infirmier ".
            "WHERE dossier_id=dossier.id AND evaluation_infirmier.id=dossier_id and cabinet!='ztest' and ".
            "cabinet!='irdes' and cabinet!='ergo'  and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' and ResHBA>0 ".
            "ORDER BY cabinet, dossier_id, date_consult, dHBA";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $id_prec="";

        while(list($cabinet, $dossier_id, $dHBA, $ResHBA, $date_consult, $deltaj)=mysql_fetch_row($res)){

            if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                if($id_prec!=""){
                    if(($hba_suiv!=0)&&($nb_consult==3)){
                        if($hba_prec>7){
                            if(isset($regions[$cabinet_prec])){
                                $nb_dossierssup7[$cabinet_prec]=$nb_dossierssup7[$cabinet_prec]+1;
                                $avantsup7[$cabinet_prec]=$avantsup7[$cabinet_prec]+$hba_prec;
                                $apressup7[$cabinet_prec]=$apressup7[$cabinet_prec]+$hba_suiv;
                                $delta_avantsup7[$cabinet_prec]=$delta_avantsup7[$cabinet_prec]+$deltaj_prec;
                                $delta_apressup7[$cabinet_prec]=$delta_apressup7[$cabinet_prec]+$deltaj_suiv;

                                $nb_dossierssup7[$regions[$cabinet_prec]]=$nb_dossierssup7[$regions[$cabinet_prec]]+1;
                                $avantsup7[$regions[$cabinet_prec]]=$avantsup7[$regions[$cabinet_prec]]+$hba_prec;
                                $apressup7[$regions[$cabinet_prec]]=$apressup7[$regions[$cabinet_prec]]+$hba_suiv;
                                $delta_avantsup7[$regions[$cabinet_prec]]=$delta_avantsup7[$regions[$cabinet_prec]]+$deltaj_prec;
                                $delta_apressup7[$regions[$cabinet_prec]]=$delta_apressup7[$regions[$cabinet_prec]]+$deltaj_suiv;
                            }
                        }
                        else{
                            if(isset($regions[$cabinet_prec])){
                                $nb_dossiersinf7[$cabinet_prec]=$nb_dossiersinf7[$cabinet_prec]+1;
                                $avantinf7[$cabinet_prec]=$avantinf7[$cabinet_prec]+$hba_prec;
                                $apresinf7[$cabinet_prec]=$apresinf7[$cabinet_prec]+$hba_suiv;
                                $delta_avantinf7[$cabinet_prec]=$delta_avantinf7[$cabinet_prec]+$deltaj_prec;
                                $delta_apresinf7[$cabinet_prec]=$delta_apresinf7[$cabinet_prec]+$deltaj_suiv;

                                $nb_dossiersinf7[$regions[$cabinet_prec]]=$nb_dossiersinf7[$regions[$cabinet_prec]]+1;
                                $avantinf7[$regions[$cabinet_prec]]=$avantinf7[$regions[$cabinet_prec]]+$hba_prec;
                                $apresinf7[$regions[$cabinet_prec]]=$apresinf7[$regions[$cabinet_prec]]+$hba_suiv;
                                $delta_avantinf7[$regions[$cabinet_prec]]=$delta_avantinf7[$regions[$cabinet_prec]]+$deltaj_prec;
                                $delta_apresinf7[$regions[$cabinet_prec]]=$delta_apresinf7[$regions[$cabinet_prec]]+$deltaj_suiv;
                            }
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
            else{//On a d�j� chang� de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                if($date_consult_prec==$date_consult){
                    if($deltaj<0){//Le HBA est avant la 1�re consult
                        if($nb_consult==1){
                            $hba_prec=$ResHBA;
                            $deltaj_prec=$deltaj;
                        }
                    }
                    else{//Un HBA apr�s la 3�me consult => on regarde s'il a d�j� �t� enregistr�
                        if($hba_suiv==0){
                            if($nb_consult==3){
                                $hba_suiv=$ResHBA;
                                $deltaj_suiv=$deltaj;
                            }
                        }
                    }
                }
                else{ //On est sur une nieme consult de ce dossier
                    if($nb_consult<3){ //C'est la deuxi�me consult ou 3eme
                        $nb_consult++;
                        $date_consult_prec=$date_consult;
                    }
                    else{ //On est sur une consultation suivante
                        if(($hba_suiv!=0)&&($nb_consult==3)){
                            $nb_consult++;
                            if($hba_prec>7){
                                if(isset($regions[$cabinet_prec])){
                                    $nb_dossierssup7[$cabinet_prec]=$nb_dossierssup7[$cabinet_prec]+1;
                                    $avantsup7[$cabinet_prec]=$avantsup7[$cabinet_prec]+$hba_prec;
                                    $apressup7[$cabinet_prec]=$apressup7[$cabinet_prec]+$hba_suiv;
                                    $delta_avantsup7[$cabinet_prec]=$delta_avantsup7[$cabinet_prec]+$deltaj_prec;
                                    $delta_apressup7[$cabinet_prec]=$delta_apressup7[$cabinet_prec]+$deltaj_suiv;

                                    $nb_dossierssup7[$regions[$cabinet_prec]]=$nb_dossierssup7[$regions[$cabinet_prec]]+1;
                                    $avantsup7[$regions[$cabinet_prec]]=$avantsup7[$regions[$cabinet_prec]]+$hba_prec;
                                    $apressup7[$regions[$cabinet_prec]]=$apressup7[$regions[$cabinet_prec]]+$hba_suiv;
                                    $delta_avantsup7[$regions[$cabinet_prec]]=$delta_avantsup7[$regions[$cabinet_prec]]+$deltaj_prec;
                                    $delta_apressup7[$regions[$cabinet_prec]]=$delta_apressup7[$regions[$cabinet_prec]]+$deltaj_suiv;
                                }
                            }
                            else{
                                if(isset($regions[$cabinet_prec])){
                                    $nb_dossiersinf7[$cabinet_prec]=$nb_dossiersinf7[$cabinet_prec]+1;
                                    $avantinf7[$cabinet_prec]=$avantinf7[$cabinet_prec]+$hba_prec;
                                    $apresinf7[$cabinet_prec]=$apresinf7[$cabinet_prec]+$hba_suiv;
                                    $delta_avantinf7[$cabinet_prec]=$delta_avantinf7[$cabinet_prec]+$deltaj_prec;
                                    $delta_apresinf7[$cabinet_prec]=$delta_apresinf7[$cabinet_prec]+$deltaj_suiv;

                                    $nb_dossiersinf7[$regions[$cabinet_prec]]=$nb_dossiersinf7[$regions[$cabinet_prec]]+1;
                                    $avantinf7[$regions[$cabinet_prec]]=$avantinf7[$regions[$cabinet_prec]]+$hba_prec;
                                    $apresinf7[$regions[$cabinet_prec]]=$apresinf7[$regions[$cabinet_prec]]+$hba_suiv;
                                    $delta_avantinf7[$regions[$cabinet_prec]]=$delta_avantinf7[$regions[$cabinet_prec]]+$deltaj_prec;
                                    $delta_apresinf7[$regions[$cabinet_prec]]=$delta_apresinf7[$regions[$cabinet_prec]]+$deltaj_suiv;
                                }
                            }
                        }
                    }
                }
            }
        }

        if(($hba_suiv!=0)&&($nb_consult==3)){
            if($hba_prec>7){
                if(isset($regions[$cabinet_prec])){
                    $nb_dossierssup7[$cabinet_prec]=$nb_dossierssup7[$cabinet_prec]+1;
                    $avantsup7[$cabinet_prec]=$avantsup7[$cabinet_prec]+$hba_prec;
                    $apressup7[$cabinet_prec]=$apressup7[$cabinet_prec]+$hba_suiv;
                    $delta_avantsup7[$cabinet_prec]=$delta_avantsup7[$cabinet_prec]+$deltaj_prec;
                    $delta_apressup7[$cabinet_prec]=$delta_apressup7[$cabinet_prec]+$deltaj_suiv;

                    $nb_dossierssup7[$regions[$cabinet_prec]]=$nb_dossierssup7[$regions[$cabinet_prec]]+1;
                    $avantsup7[$regions[$cabinet_prec]]=$avantsup7[$regions[$cabinet_prec]]+$hba_prec;
                    $apressup7[$regions[$cabinet_prec]]=$apressup7[$regions[$cabinet_prec]]+$hba_suiv;
                    $delta_avantsup7[$regions[$cabinet_prec]]=$delta_avantsup7[$regions[$cabinet_prec]]+$deltaj_prec;
                    $delta_apressup7[$regions[$cabinet_prec]]=$delta_apressup7[$regions[$cabinet_prec]]+$deltaj_suiv;
                }
            }
            else{
                if(isset($regions[$cabinet_prec])){
                    $nb_dossiersinf7[$cabinet_prec]=$nb_dossiersinf7[$cabinet_prec]+1;
                    $avantinf7[$cabinet_prec]=$avantinf7[$cabinet_prec]+$hba_prec;
                    $apresinf7[$cabinet_prec]=$apresinf7[$cabinet_prec]+$hba_suiv;
                    $delta_avantinf7[$cabinet_prec]=$delta_avantinf7[$cabinet_prec]+$deltaj_prec;
                    $delta_apresinf7[$cabinet_prec]=$delta_apresinf7[$cabinet_prec]+$deltaj_suiv;

                    $nb_dossiersinf7[$regions[$cabinet_prec]]=$nb_dossiersinf7[$regions[$cabinet_prec]]+1;
                    $avantinf7[$regions[$cabinet_prec]]=$avantinf7[$regions[$cabinet_prec]]+$hba_prec;
                    $apresinf7[$regions[$cabinet_prec]]=$apresinf7[$regions[$cabinet_prec]]+$hba_suiv;
                    $delta_avantinf7[$regions[$cabinet_prec]]=$delta_avantinf7[$regions[$cabinet_prec]]+$deltaj_prec;
                    $delta_apresinf7[$regions[$cabinet_prec]]=$delta_apresinf7[$regions[$cabinet_prec]]+$deltaj_suiv;
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
            <td>Moyenne du dernier HBA1c avant la 1�re consultation</td>
            <td><?php echo round($avanttot/$nb_dossierstot, 2);?></td>
            <?php

            foreach($reg as $region){
                if($nb_dossierssup7[$region]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($avantsup7[$region]/$nb_dossierssup7[$region], 2)."</td>";
                }
            }

            foreach($tcabinet as $cab){
                if($_SESSION["national"]==1){
                    if($nb_dossierssup7[$cab]==0){
                        echo "<td>ND</td>";
                    }
                    else{
                        echo "<td>".round($avantsup7[$cab]/$nb_dossierssup7[$cab], 2)."</td>";
                    }
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        if($nb_dossierssup7[$cab]==0){
                            echo "<td>ND</td>";
                        }
                        else{
                            echo "<td>".round($avantsup7[$cab]/$nb_dossierssup7[$cab], 2)."</td>";
                        }
                    }
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne du premier HBA1c apr�s la 3�me consultation</Td>
            <td><?php echo round($aprestot/$nb_dossierstot, 2);?></td>
            <?php
            foreach($reg as $region){
                if($nb_dossierssup7[$region]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($apressup7[$region]/$nb_dossierssup7[$region], 2)."</td>";
                }
            }

            foreach($tcabinet as $cab){
                if($_SESSION["national"]==1){
                    if($nb_dossierssup7[$cab]==0){
                        echo "<td>ND</td>";
                    }
                    else{
                        echo "<td>".round($apressup7[$cab]/$nb_dossierssup7[$cab], 2)."</td>";
                    }
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        if($nb_dossierssup7[$cab]==0){
                            echo "<td>ND</td>";
                        }
                        else{
                            echo "<td>".round($apressup7[$cab]/$nb_dossierssup7[$cab], 2)."</td>";
                        }
                    }
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps avant la 1�re consultation</Td>
            <td><?php echo round($delta_avanttot/$nb_dossierstot);?></td>
            <?php
            foreach($reg as $region){
                if($nb_dossierssup7[$region]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_avantsup7[$region]/$nb_dossierssup7[$region])."</td>";
                }
            }
            foreach($tcabinet as $cab){
                if($_SESSION["national"]==1){
                    if($nb_dossierssup7[$cab]==0){
                        echo "<td>ND</td>";
                    }
                    else{
                        echo "<td>".round($delta_avantsup7[$cab]/$nb_dossierssup7[$cab])."</td>";
                    }
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        if($nb_dossierssup7[$cab]==0){
                            echo "<td>ND</td>";
                        }
                        else{
                            echo "<td>".round($delta_avantsup7[$cab]/$nb_dossierssup7[$cab])."</td>";
                        }
                    }
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps apr�s la 3�me consultation</Td>
            <td><?php echo round($delta_aprestot/$nb_dossierstot);?></td>
            <?php
            foreach($reg as $region){
                if($nb_dossierssup7[$region]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_apressup7[$region]/$nb_dossierssup7[$region])."</td>";
                }
            }

            foreach($tcabinet as $cab){
                if($_SESSION["national"]==1){
                    if($nb_dossierssup7[$cab]==0){
                        echo "<td>ND</td>";
                    }
                    else{
                        echo "<td>".round($delta_apressup7[$cab]/$nb_dossierssup7[$cab])."</td>";
                    }
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        if($nb_dossierssup7[$cab]==0){
                            echo "<td>ND</td>";
                        }
                        else{
                            echo "<td>".round($delta_apressup7[$cab]/$nb_dossierssup7[$cab])."</td>";
                        }
                    }
                }
            }
            ?>
        </tr>
        <tr>
            <td>Nombre de patients concern�s</Td>
            <td><?php echo $nb_dossierstot;?></td>
            <?php
            foreach($reg as $region){
                echo "<td>".$nb_dossierssup7[$region]."</td>";
            }
            foreach($tcabinet as $cab){
                if($_SESSION["national"]==1){
                    echo "<td>".$nb_dossierssup7[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td>".$nb_dossierssup7[$cab]."</td>";
                    }
                }
            }
            ?>
        </tr>


        <tr>
            <td rowspan='6'>Pour les patients avec HBA1c<=7 avant la 1�re consultation</td>
        </tr>
        <?php


        $avanttot=array_sum($avantinf7);
        $aprestot=array_sum($apresinf7);
        $delta_avanttot=array_sum($delta_avantinf7);
        $delta_aprestot=array_sum($delta_apresinf7);
        $nb_dossierstot=array_sum($nb_dossiersinf7);

        ?>
        <tr>
            <td>Moyenne du dernier HBA1c avant la 1�re consultation</td>
            <td><?php echo round($avanttot/$nb_dossierstot, 2);?></td>
            <?php
            foreach($reg as $region){
                if($nb_dossiersinf7[$region]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($avantinf7[$region]/$nb_dossiersinf7[$region], 2)."</td>";
                }
            }
            foreach($tcabinet as $cab){
                if($_SESSION["national"]==1){
                    if($nb_dossiersinf7[$cab]==0){
                        echo "<td>ND</td>";
                    }
                    else{
                        echo "<td>".round($avantinf7[$cab]/$nb_dossiersinf7[$cab], 2)."</td>";
                    }
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        if($nb_dossiersinf7[$cab]==0){
                            echo "<td>ND</td>";
                        }
                        else{
                            echo "<td>".round($avantinf7[$cab]/$nb_dossiersinf7[$cab], 2)."</td>";
                        }
                    }
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne du premier HBA1c apr�s la 3�me consultation</Td>
            <td><?php echo round($aprestot/$nb_dossierstot, 2);?></td>
            <?php
            foreach($reg as $region){
                if($nb_dossiersinf7[$region]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($apresinf7[$region]/$nb_dossiersinf7[$region], 2)."</td>";
                }
            }
            foreach($tcabinet as $cab){
                if($_SESSION["national"]==1){
                    if($nb_dossiersinf7[$cab]==0){
                        echo "<td>ND</td>";
                    }
                    else{
                        echo "<td>".round($apresinf7[$cab]/$nb_dossiersinf7[$cab], 2)."</td>";
                    }
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        if($nb_dossiersinf7[$cab]==0){
                            echo "<td>ND</td>";
                        }
                        else{
                            echo "<td>".round($apresinf7[$cab]/$nb_dossiersinf7[$cab], 2)."</td>";
                        }
                    }
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps avant la 1�re consultation</Td>
            <td><?php echo round($delta_avanttot/$nb_dossierstot);?></td>
            <?php
            foreach($reg as $region){
                if($nb_dossiersinf7[$region]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_avantinf7[$region]/$nb_dossiersinf7[$region])."</td>";
                }
            }
            foreach($tcabinet as $cab){
                if($_SESSION["national"]==1){
                    if($nb_dossiersinf7[$cab]==0){
                        echo "<td>ND</td>";
                    }
                    else{
                        echo "<td>".round($delta_avantinf7[$cab]/$nb_dossiersinf7[$cab])."</td>";
                    }
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        if($nb_dossiersinf7[$cab]==0){
                            echo "<td>ND</td>";
                        }
                        else{
                            echo "<td>".round($delta_avantinf7[$cab]/$nb_dossiersinf7[$cab])."</td>";
                        }
                    }
                }
            }
            ?>
        </tr>
        <tr>
            <td>Moyenne en jour du temps apr�s la 3�me consultation</Td>
            <td><?php echo round($delta_aprestot/$nb_dossierstot);?></td>
            <?php
            foreach($reg as $region){
                if($nb_dossiersinf7[$region]==0){
                    echo "<td>ND</td>";
                }
                else{
                    echo "<td>".round($delta_apresinf7[$region]/$nb_dossiersinf7[$region])."</td>";
                }
            }
            foreach($tcabinet as $cab){
                if($_SESSION["national"]==1){
                    if($nb_dossiersinf7[$cab]==0){
                        echo "<td>ND</td>";
                    }
                    else{
                        echo "<td>".round($delta_apresinf7[$cab]/$nb_dossiersinf7[$cab])."</td>";
                    }
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        if($nb_dossiersinf7[$cab]==0){
                            echo "<td>ND</td>";
                        }
                        else{
                            echo "<td>".round($delta_apresinf7[$cab]/$nb_dossiersinf7[$cab])."</td>";
                        }
                    }
                }
            }
            ?>
        </tr>
        <tr>
            <td>Nombre de patients concern�s</Td>
            <td><?php echo $nb_dossierstot;?></td>
            <?php
            foreach($reg as $region){
                echo "<td>".$nb_dossiersinf7[$region]."</td>";
            }
            foreach($tcabinet as $cab){
                if($_SESSION["national"]==1){
                    echo "<td>".$nb_dossiersinf7[$cab]."</td>";
                }
                elseif($_SESSION["region"]==1){
                    if($regions[$cab]==$_SESSION["nom_region"]){
                        echo "<td>".$nb_dossiersinf7[$cab]."</td>";
                    }
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
