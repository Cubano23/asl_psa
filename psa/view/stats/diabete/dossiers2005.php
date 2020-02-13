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
    <title>Nombre d'examens du HBA1c réalisés lors des 12 derniers mois</title>
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

require("../global/entete.php");
//echo $loc;

entete_asalee("Nombre de HBA1c réalisés lors des 12 derniers mois");

//echo $loc;
?>
<!--
<table cellpadding="2" cellspacing="2" border="0"
 style="text-align: left; width: 100%;">
  <tbody>
    <tr>
      <td style="width: 20%; vertical-align: top;">
      <br>
      <img src="<?php echo $loc; ?>/images/inf79.gif" alt="logo informed79"><br>
        <a href="mailto:contact@asalee.fr"><font size="-1">contact</font></a>
      </td>
      <td style="text-align: center; vertical-align: top;">
	       <span style="font-family: arial; font-weight: bold;">
 <?php
echo "
<i><font face='times new roman' size='32'>Asalée</font><br>
<font face='times new roman'>Indicateurs d'évaluation Asalée taux de suivi des diabétiques</font></i>";
?>
           </span><br>
 <?php if(isset($_SESSION['nom'])) echo '<font size="-1"><i>'.$_SESSION['nom'].'</i></font>'; ?>
      </td>
      <td style="width: 15%; text-align: right; vertical-align: middle;">
      <img src="<?php echo $loc; ?>/images/urml.jpg" alt="logo urml"><br>
      </td>
    </tr>
  </tbody>
</table>
-->
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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville;

    $req="SELECT cabinet, nom_cab ".
        "FROM account ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet ";

    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    $t_diab['tot']=0;

    $dossiers=array();
    while(list($cab, $ville) = mysql_fetch_row($res)) {
        $t_diab[$cab]=array();
        $tville[$cab]=$ville;
        $plus3[$cab]=0;
        $moins3[$cab]=0;
    }

    $req="SELECT cabinet, count(*) ".
        "FROM dossier ".
        "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
        "AND actif='oui' ".
        "GROUP BY cabinet ".
        "ORDER BY cabinet, numero ";
//echo $req;
//die;
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    if (mysql_num_rows($res)==0) {
        exit ("<p align='center'>Aucun cabinet n'est actif</p>");
    }
    $tcabinet=array();

    while(list($cab, $pat) = mysql_fetch_row($res)) {
        $tcabinet[] = $cab;
    }

    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";


    ?>
    <br>
    <br>
    <table border=1 width='100%'>
        <!--<tr>
	<td></td>

<?php
        foreach($tcabinet as $cab) {
            ?>
	<td align='center'><b><?php echo $tville[$cab]; ?></b></td>
<?php
        }
        ?>
</tr>
-->
        <tr>
            <td>cabinet</Td>
            <td>nom</td>
            <td>prénom</td>
            <td>N° SS</td>
            <td>id</Td>
            <td>numéro</td>
            <td>dnaiss</Td>
            <td>annee naiss</td>
            <td>sexe</Td>
            <td>zone</Td>
            <td>date 1er suivi</Td>
            <td>consultation</Td>
            <td>HBA1c avant 1er suivi (0=non)</Td>
            <td>ADO (0=non)</Td>
            <td>Date 1er HBA</Td>
        </Tr>
        <?php

        $max_pat=0;
        $plus3tot=0;
        $moins3tot=0;
        $plus3eval=0;
        $moins3eval=0;
        $plus3eval2=0;
        $moins3eval2=0;
        $plus3eval3=0;
        $moins3eval3=0;

        //Patients avec au moins un suivi
        $req="SELECT cabinet, id, numero, dnaiss, sexe, min(dsuivi), min(dHBA), min(ADO), max(ADO) ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest'  and cabinet!='irdes'  and cabinet!='ergo' and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "AND actif='oui' ".
            "GROUP BY cabinet, dossier_id ".
            "ORDER BY cabinet, dossier_id ";
        //echo $req;
        //die;
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        while(list($cab, $id,$numero, $dnaiss, $sexe, $dsuivi, $dHBA, $maxADO, $minADO) = mysql_fetch_row($res)) {

            $req="SELECT * FROM evaluation_infirmier WHERE id='$id'";
            $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            if(mysql_num_rows($res2)>0){
                $consultation='2';
            }
            else{
                $consultation='1';
            }

            list($anaiss, $mois_naiss, $jnaiss)=explode("-", $dnaiss);

            $req="SELECT min(dHBA), dossier_id FROM suivi_diabete WHERE dossier_id='$id' AND dHBA>'1900-01-01' GROUP by dossier_id";
            $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

            $HBA=0;
            if(mysql_num_rows($res2)>0){
                list($dHBA)=mysql_fetch_row($res2);
                if($dHBA<=$dsuivi){
                    $HBA=1;
                }
                else{
                    $dHBA="";
                    $HBA=0;
                }
            }
            else{
                $dHBA="";
                $HBA=0;
            }

            if(($maxADO!='aucun')&&($maxADO!='')&&($maxADO!='NULL')){
                $ADO=1;
            }
            else{

                if(($minADO!='aucun')&&($minADO!='')&&($minADO!='NULL')){
                    $ADO=1;
                }
                else{
                    $ADO=0;
                }
            }

            $req="SELECT ADO FROM suivi_diabete WHERE dossier_id='$id' AND ADO!='aucun' AND ADO!='' AND ADO is not NULL";
            $res2=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");
            if(mysql_num_rows($res2)>0){
                $ADO=1;
            }

            $dossiers[]=array("cabinet"=>$cab, "id"=>$id, "numero"=>$numero, "dnaiss"=>$dnaiss, "anaiss"=>$anaiss, "sexe"=>$sexe,
                "dsuivi"=>$dsuivi, "consultation"=>$consultation, "HBA"=>$HBA, "ADO"=>$ADO, "dHBA"=>$dHBA);

        }


        foreach($dossiers as $dossier){
            echo "<tr><td>".$dossier['cabinet']."</Td><td></Td><td></Td><td></td>
					<td>".$dossier['id']."</td>
						<td>".$dossier['numero']."</td>
							<td>".$dossier['dnaiss']."</Td>
								<td>".$dossier['anaiss']."</Td>
									<td>".$dossier['sexe']."</td><td></td>
										<td>".$dossier['dsuivi']."</Td>
											<td>".$dossier['consultation']."</td>
												<td>".$dossier['HBA']."</td>
													<td>".$dossier['ADO']."</td>
														<td>".$dossier['dHBA']."</td>
			</tr>";
        }


        ?>

    </table>
    <br><br>
    <?php


}


?>
</body>
</html>
