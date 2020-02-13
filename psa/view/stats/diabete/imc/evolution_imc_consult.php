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
    <title>Evolution de l'IMC apr�s une consultation infirmi�re</title>
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

$titre="Evolution de l'IMC apr�s une consultation infirmi�re";


entete_asalee($titre);
//echo $loc;
?>
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
        $tville[]=$ville;
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


        <?
        //Mise dans un tableau des lignes comprenant, pour chaque consultation, la liste des HBA.
        //Classement par id, dHBA pour pouvoir garder le dernier HBA avant consultation, et le premier apr�s
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

        while(list($cabinet, $dossier_id, $dsuivi, $poids, $date_consult, $deltaj, $taille)=mysql_fetch_row($res)){
            if($dossier_id!=$id_prec){//On regarde ce qu'il se passe pour un nouveau dossier
                if($id_prec!=""){
                    if($imc_suiv!=0){
                        if($imc_prec>30){
                            $nb_dossierssup7[$cabinet_prec]=$nb_dossierssup7[$cabinet_prec]+1;
                            $avantsup7[$cabinet_prec]=$avantsup7[$cabinet_prec]+$imc_prec;
                            $apressup7[$cabinet_prec]=$apressup7[$cabinet_prec]+$imc_suiv;
                            $delta_avantsup7[$cabinet_prec]=$delta_avantsup7[$cabinet_prec]+$deltaj_prec;
                            $delta_apressup7[$cabinet_prec]=$delta_apressup7[$cabinet_prec]+$deltaj_suiv;
                        }
                        else{
                            $nb_dossiersinf7[$cabinet_prec]=$nb_dossiersinf7[$cabinet_prec]+1;
                            $avantinf7[$cabinet_prec]=$avantinf7[$cabinet_prec]+$imc_prec;
                            $apresinf7[$cabinet_prec]=$apresinf7[$cabinet_prec]+$imc_suiv;
                            $delta_avantinf7[$cabinet_prec]=$delta_avantinf7[$cabinet_prec]+$deltaj_prec;
                            $delta_apresinf7[$cabinet_prec]=$delta_apresinf7[$cabinet_prec]+$deltaj_suiv;
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
            else{//On a d�j� chang� de dossier, on regarde donc ce qu'il se passe pour ce nouveau dossier
                if($date_consult_prec==$date_consult){
                    if($deltaj<0){//Le HBA est avant la consult
                        $imc_prec=$poids/($taille*$taille/10000);
                        $deltaj_prec=$deltaj;
                    }
                    else{//Un HBA apr�s la consult => on regarde s'il a d�j� �t� enregistr�
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
                        }
                        else{
                            $nb_dossiersinf7[$cabinet_prec]=$nb_dossiersinf7[$cabinet_prec]+1;
                            $avantinf7[$cabinet_prec]=$avantinf7[$cabinet_prec]+$imc_prec;
                            $apresinf7[$cabinet_prec]=$apresinf7[$cabinet_prec]+$imc_suiv;
                            $delta_avantinf7[$cabinet_prec]=$delta_avantinf7[$cabinet_prec]+$deltaj_prec;
                            $delta_apresinf7[$cabinet_prec]=$delta_apresinf7[$cabinet_prec]+$deltaj_suiv;
                        }
                    }
                    $imc_prec=$poids/($taille*$taille/10000);
                    $imc_suiv=0;
                    $date_consult_prec=$date_consult;
                }
            }
        }

        if($imc_suiv!=0){
            if($imc_prec>30){
                $nb_dossierssup7[$cabinet_prec]=$nb_dossierssup7[$cabinet_prec]+1;
                $avantsup7[$cabinet_prec]=$avantsup7[$cabinet_prec]+$imc_prec;
                $apressup7[$cabinet_prec]=$apressup7[$cabinet_prec]+$imc_suiv;
                $delta_avantsup7[$cabinet_prec]=$delta_avantsup7[$cabinet_prec]+$deltaj_prec;
                $delta_apressup7[$cabinet_prec]=$delta_apressup7[$cabinet_prec]+$deltaj_suiv;
            }
            else{
                $nb_dossiersinf7[$cabinet_prec]=$nb_dossiersinf7[$cabinet_prec]+1;
                $avantinf7[$cabinet_prec]=$avantinf7[$cabinet_prec]+$imc_prec;
                $apresinf7[$cabinet_prec]=$apresinf7[$cabinet_prec]+$imc_suiv;
                $delta_avantinf7[$cabinet_prec]=$delta_avantinf7[$cabinet_prec]+$deltaj_prec;
                $delta_apresinf7[$cabinet_prec]=$delta_apresinf7[$cabinet_prec]+$deltaj_suiv;
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
            <?
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
            <td>Moyenne de l'IMC apr�s la consultation</Td>
            <td><?php echo round($aprestot/$nb_dossierstot, 1);?></td>
            <?
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
            <?
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
            <td>Moyenne en jour du temps apr�s la consultation</Td>
            <td><?php echo round($delta_aprestot/$nb_dossierstot);?></td>
            <?
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
        <?


        $avanttot=array_sum($avantinf7);
        $aprestot=array_sum($apresinf7);
        $delta_avanttot=array_sum($delta_avantinf7);
        $delta_aprestot=array_sum($delta_apresinf7);
        $nb_dossierstot=array_sum($nb_dossiersinf7);

        ?>
        <tr>
            <td>Moyenne de l'IMC avant la consultation</td>
            <td><?php echo round($avanttot/$nb_dossierstot, 1);?></td>
            <?
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
            <td>Moyenne de l'IMC apr�s la consultation</Td>
            <td><?php echo round($aprestot/$nb_dossierstot, 1);?></td>
            <?
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
            <?
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
            <td>Moyenne en jour du temps apr�s la consultation</Td>
            <td><?php echo round($delta_aprestot/$nb_dossierstot);?></td>
            <?
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
    <?

}


?>
</body>
</html>
