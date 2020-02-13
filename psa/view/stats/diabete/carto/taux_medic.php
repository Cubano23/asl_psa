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
    <title>Taux de patients diabétiques disposant d'un médicament</title>
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

entete_asalee("Taux de patients diabétiques disposant d'un médicament");

//echo $loc;
?>

<br><br>
<?

# boucle principale
do {
    $repete=false;

    # étape 1 : valeurs à la date du jour
    if (!isset($_POST['etape'])) {
        etape_1($repete);
        exit;
    }

    if (isset($_POST['etape'])) {
        switch($_POST['etape']) {

            //valeurs à la date du jour
            case 1:
                etape_1($repete);
                break;

        }
    }
} while($repete);

# fin de traitement principal

//valeurs à la date du jour
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

    }


//Patients avec au moins un suivi
    $req="SELECT cabinet, count(*) ".
        "FROM suivi_diabete, dossier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
        "and dossier.cabinet!='sbirault' ".
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

    }


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
            <td align='center'><b> Moyenne </b></td>
            <!--			<td align='center'><b>Moyenne eval</b></td>
                            <td align='center'><b>Moyenne cab 2005</b></td>
                                <td align='center'><b>Moyenne cab 2006</b></Td>-->
            <?php
            foreach($tcabinet as $cab) {
                ?>
                <td align='center'><b><?php echo $tville[$cab]; ?></b></td>
                <?php
            }
            ?>
        </tr>

        <?php

        //taux diab avec médicament
        $req="SELECT cabinet, dossier_id, max(ADO), min(ADO), max(InsulReq) ".
            "FROM `suivi_diabete`, dossier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND actif='oui' ".
            "AND dossier_id=id ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet, dossier_id ";
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
        $id_prec="";

        while(list($cab, $dossier_id, $maxADO, $minADO, $InsulReq) = mysql_fetch_row($res)) {
            if((($maxADO!="NULL")&&($maxADO!='')&&($maxADO!="aucun"))||(($minADO!="NULL")&&($minADO!='')&&($minADO!="aucun"))||
                ($InsulReq=='1')){
                $tpat[$cab] = $tpat[$cab]+1;
                $tpat['tot']=$tpat['tot']+1;


            }
        }



        ?>

        <tr>
            <td>Taux de patients diabétiques disposant d'un médicament par rapport au potentiel<sup>1</sup></td>
            <td align='right'><?php echo round($tpat['tot']/$potentielsaisi['tot']*100, 0); ?>%</td>
            <?php

            foreach($tcabinet as $cab) {
                if ($potentielsaisi[$cab]==0)
                    $taux="ND";
                else
                {
                    $taux=$tpat[$cab]/$potentielsaisi[$cab]*100;
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
            <td>Taux de patients diabétiques disposant d'un médicament par au nb de dossiers<sup>2</sup></td>
            <td align='right'><?php echo round($tpat['tot']/$nbsuivis['tot']*100, 0); ?>%</td>
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
                <td align='right'><?php echo $taux; ?></td>
                <?php
            }
            ?>
        </tr>




        <tr>
            <td>Nombre de patients ayant eu au moins 1 suivi</td>
            <td align='right'><?php echo $nbsuivis['tot']; ?></td>
            <!--			<td align='right'><?php echo $nbsuivis['eval'];?></Td>
			    <td align='right'><?php echo $nbsuivis['eval2'];?></td>
			        <td align='right'><?php echo $nbsuivis['eval3'];?></td>-->
            <?php

            foreach($tcabinet as $cab) {

                ?>
                <td align='right'><?php echo $nbsuivis[$cab]; ?></td>
                <?php
            }
            ?>
        </tr>


        <tr>
            <td>Potentiel du cabinet</td>
            <td align='right'><?php echo $potentielsaisi['tot']; ?></td>
            <!--		    <td align='right'><?php echo $potentielsaisi['eval'];?></td>
		        <td align='right'><?php echo $potentielsaisi['eval2'];?></td>
		            <td align='right'><?php echo $potentielsaisi['eval3'];?></td>-->
            <?php

            foreach($tcabinet as $cab) {

                ?>
                <td align='right'><?php echo $potentielsaisi[$cab]; ?></td>
                <?php
            }
            ?>
        </tr>


    </table>
    <br><br>
    <?php

    ?>
    <sup>1</sup>Nombre de personnes ayant eu au moins un suivi du diabète et un médicament ou insuline lors d'un suivi/potentiel du cabinet<br>
    <sup>2</sup>Nombre de personnes ayant eu au moins un suivi du diabète et un médicament ou insuline lors d'un suivi/Nombre de dossiers ayant au moins 1 suivi
    <?
}


?>
</body>
</html>
