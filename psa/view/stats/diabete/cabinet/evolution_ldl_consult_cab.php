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
    <title>Evolution du LDL après une consultation infirmière</title>
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

$titre="Evolution du LDL après une consultation infirmière";


entete_asalee($titre);
//echo $loc;
?>
<br><br>
<?

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
        $tville[$cab]=$ville;
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



    foreach($tville as $cab=>$ville){
        $tcabinet_util[$cab]=0;
    }

    $date3mois=date("Y-m-d", mktime(1, 1, 1, date("m")-3, date('d'), date("Y")));
    $req="SELECT cabinet from liste_exam, dossier where ".
        "dossier.id=liste_exam.id and date_exam>='$date3mois' group by cabinet";
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
    <table border=1>
        <tr>
            <td colspan='2'></td><td><b>Moyenne</b></td>
            <td align='center'><b><?php echo $tville[$_SESSION['nom']]; ?></b></td>
            <td align='center'><b>Borne basse</b></Td>
            <td align='center'><b>Borne haute</b></td>
        </tr>
        <tr>
            <td rowspan='3' width='100'>Pour les patients avec LDL>1.3 avant la consultation</td>
        </tr>


        <?
        //Mise dans un tableau des lignes comprenant, pour chaque consultation, la liste des HBA.
        //Classement par id, dHBA pour pouvoir garder le dernier HBA avant consultation, et le premier après
        //Le tri sera fait ensuite.

        $req= "SELECT cabinet, dossier_id, date_exam, resultat1, `date` as date_consult, DATEDIFF(date_exam, `date`) as deltaj ".
            "FROM dossier, suivi_diabete, evaluation_infirmier, liste_exam ".
            "WHERE dossier_id=dossier.id AND evaluation_infirmier.id=dossier_id and cabinet!='ztest' and ".
            "cabinet!='irdes' and cabinet!='ergo'  and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
            "and dossier.id=liste_exam.id and type_exam='LDL' ".
            "ORDER BY cabinet, dossier_id, date_consult, date_exam";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        $id_prec="";
        $cabinet_prec="";

        while(list($cabinet, $dossier_id, $dLDL, $LDL, $date_consult, $deltaj)=mysql_fetch_row($res)){
            if(isset($tcabinet_util[$cabinet_prec])&&($tcabinet_util[$cabinet_prec]==1)){
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

        if(isset($tcabinet_util[$cabinet_prec])&&($tcabinet_util[$cabinet_prec]==1)){
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


        $avanttot=$aprestot=$delta_avanttot=$delta_aprestot=$nb_dossierstot=0;

        foreach($tcabinet_util as $cab=>$val){
            if($val==1){
                $avanttot=$avanttot+$avantsup13[$cab];
                $aprestot=$aprestot+$apressup13[$cab];
                $delta_avanttot=$delta_avanttot+$delta_avantsup13[$cab];
                $delta_aprestot=$delta_aprestot+$delta_apressup13[$cab];
                $nb_dossierstot=$nb_dossierstot+$nb_dossierssup13[$cab];
            }
        }
        $deltamin=50;
        $deltamax=-50;

        ?>
        <tr>
            <td>Moyenne du dernier LDL avant la consultation</td>
            <td><?php echo round($avanttot/$nb_dossierstot, 2);?></td>
            <?
            if($nb_dossierssup13[$_SESSION['nom']]==0){
                echo "<td>ND</td>";
            }
            else{
                echo "<td>".round($avantsup13[$_SESSION['nom']]/$nb_dossierssup13[$_SESSION['nom']], 1)."</td>";
            }

            foreach($tcabinet_util as $cab=>$val){
                if($val==1){
                    if($nb_dossierssup13[$cab]!=0){
                        if((($avantsup13[$cab]/$nb_dossierssup13[$cab])-($apressup13[$cab]/$nb_dossierssup13[$cab]))<$deltamin){
                            $deltamin=($avantsup13[$cab]/$nb_dossierssup13[$cab])-($apressup13[$cab]/$nb_dossierssup13[$cab]);
                            $miniavant=round($avantsup13[$cab]/$nb_dossierssup13[$cab],2);
                            $miniapres=round($apressup13[$cab]/$nb_dossierssup13[$cab],2);
                        }
                        if((($avantsup13[$cab]/$nb_dossierssup13[$cab])-($apressup13[$cab]/$nb_dossierssup13[$cab]))>$deltamax){
                            $deltamax=($avantsup13[$cab]/$nb_dossierssup13[$cab])-($apressup13[$cab]/$nb_dossierssup13[$cab]);
                            $maxavant=round($avantsup13[$cab]/$nb_dossierssup13[$cab],2);
                            $maxapres=round($apressup13[$cab]/$nb_dossierssup13[$cab],2);
                        }
                    }
                }
            }
            ?>
            <td><?php echo $miniavant;?></td>
            <td><?php echo $maxavant;?></td>


        </tr>
        <tr>
            <td>Moyenne du premier LDL après la consultation</Td>
            <td><?php echo round($aprestot/$nb_dossierstot, 2);?></td>
            <?
            if($nb_dossierssup13[$_SESSION['nom']]==0){
                echo "<td>ND</td>";
            }
            else{
                echo "<td>".round($apressup13[$_SESSION['nom']]/$nb_dossierssup13[$_SESSION['nom']], 2)."</td>";
            }

            ?>
            <td><?php echo $miniapres;?></td>
            <td><?php echo $maxapres;?></td>

        </tr>

        <tr>
            <td rowspan='3' width='100'>Pour les patients avec LDL<=1.3 avant la consultation</td>
        </tr>
        <?



        $avanttot=$aprestot=$delta_avanttot=$delta_aprestot=$nb_dossierstot=0;

        foreach($tcabinet_util as $cab=>$val){
            $avanttot=$avanttot+$avantinf13[$cab];
            $aprestot=$aprestot+$apresinf13[$cab];
            $delta_avanttot=$delta_avanttot+$delta_avantinf13[$cab];
            $delta_aprestot=$delta_aprestot+$delta_apresinf13[$cab];
            $nb_dossierstot=$nb_dossierstot+$nb_dossiersinf13[$cab];
        }

        $deltamin=50;
        $deltamax=-50;

        ?>
        <tr>
            <td>Moyenne du dernier LDL avant la consultation</td>
            <td><?php echo round($avanttot/$nb_dossierstot, 2);?></td>
            <?
            if($nb_dossiersinf13[$_SESSION['nom']]==0){
                echo "<td>ND</td>";
            }
            else{
                echo "<td>".round($avantinf13[$_SESSION['nom']]/$nb_dossiersinf13[$_SESSION['nom']], 2)."</td>";
            }

            foreach($tcabinet_util as $cab =>$val){
                if($val==1){
                    if($nb_dossiersinf13[$cab]!=0){
                        if((($avantinf13[$cab]/$nb_dossiersinf13[$cab])-($apresinf13[$cab]/$nb_dossiersinf13[$cab]))<$deltamin){
                            $deltamin=($avantinf13[$cab]/$nb_dossiersinf13[$cab])-($apresinf13[$cab]/$nb_dossiersinf13[$cab]);
                            $miniavant=round($avantinf13[$cab]/$nb_dossiersinf13[$cab],2);
                            $miniapres=round($apresinf13[$cab]/$nb_dossiersinf13[$cab],2);
                        }
                        if((($avantinf13[$cab]/$nb_dossiersinf13[$cab])-($apresinf13[$cab]/$nb_dossiersinf13[$cab]))>$deltamax){
                            $deltamax=($avantinf13[$cab]/$nb_dossiersinf13[$cab])-($apresinf13[$cab]/$nb_dossiersinf13[$cab]);
                            $maxavant=round($avantinf13[$cab]/$nb_dossiersinf13[$cab],2);
                            $maxapres=round($apresinf13[$cab]/$nb_dossiersinf13[$cab],2);
                        }
                    }
                }
            }
            ?>
            <td><?php echo $miniavant;?></td>
            <td><?php echo $maxavant;?></td>
        </tr>
        <tr>
            <td width='100'>Moyenne du premier LDL après la consultation</Td>
            <td><?php echo round($aprestot/$nb_dossierstot, 2);?></td>
            <?
            if($nb_dossiersinf13[$_SESSION['nom']]==0){
                echo "<td>ND</td>";
            }
            else{
                echo "<td>".round($apresinf13[$_SESSION['nom']]/$nb_dossiersinf13[$_SESSION['nom']], 2)."</td>";
            }

            ?>
            <td><?php echo $miniapres;?></td>
            <td><?php echo $maxapres;?></td>
        </tr>


    </table>
    <?

}


?>
</body>
</html>
