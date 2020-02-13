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
    <title>Tous les exams de tous les dossiers</title>
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

entete_asalee("Tous les exams de tous les dossiers");

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


$tab_arrivee=array("Chatillon"=>'2004-06-01', "Dominault"=>"2005-04-01", "Lucquin"=>"2005-04-01", "Niort"=>"2004-09-27",
    "Paquereau"=>"2005-06-01", "Brioux"=>"2004-11-02", "Chizé"=>"2005-04-01", "Argenton"=>"2006-03-06",
    "Saint-Varent"=>"2006-02-06", "La-Mothe"=>"2006-05-01", "Lezay"=>"2006-05-01", "Lezay2"=>"2006-05-01",
    "Frontenay"=>"2006-07-01", "Mauzé"=>'2006-07-01', "Couture"=>"2006-09-10",
    "Chef-boutonne1"=>"2006-09-10", "Chef-boutonne2"=>"2006-09-10", "Bouille"=>"2006-10-31");


$mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
    '11'=>'Novembre', '12'=>'Décembre');

echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";


?>
<br>
<br>
<table border=1 width='100%'>


    <?php
    $dossier=array();
    $hba=array();
    $fil=array();
    $pied=array();
    $hdl=array();
    $ldl=array();
    $creat=array();
    $albu=array();
    $fond=array();
    $ecg=array();
    $Poids=array();
    $tension=array();
    $consult=array();

    $req="SELECT dossier_id, dsuivi, dHBA, ResHBA, dExaFil, ExaFil, dExaPieds, ExaPieds, dChol, HDL, dLDL, LDL, ".
        "suivi_diabete.dCreat, Creat, dAlbu, iAlbu, dFond, iFond, dECG, iECG, poids, TaSys, TaDia, cabinet, dnaiss, ".
        "sexe, actif, date_format(dossier.dmaj, '%d/%m/%Y'), taille ".
        "from suivi_diabete, dossier WHERE dossier_id=id and cabinet!='ztest' and cabinet!='irdes' and cabinet!='sbirault' ".
        "and cabinet!='jgomes' and cabinet!='ergo' and cabinet!='clamecy' and cabinet!='varzy' order by dossier_id, dsuivi";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($id, $dsuivi, $dHBA, $ResHBA, $dExaFil, $ExaFil, $dExaPieds, $ExaPieds, $dChol, $HDL, $dLDL, $LDL, $dCreat,
        $Creat, $dAlbu, $iAlbu, $dFond, $iFond, $dECG, $iECG, $poids, $TaSys, $TaDia, $cabinet, $dnaiss, $sexe,
        $actif, $dmaj, $taille)=mysql_fetch_row($res)){

        if(!isset($dossier[$id])){
            $dossier[$id]=array("id"=>$id, "dnaiss"=>$dnaiss, "cabinet"=>$cabinet, "sexe"=>$sexe, "actif"=>$actif,
                "dmaj"=>$dmaj, "dsuivi"=>$dsuivi, "taille"=>$taille);
        }

        if($dHBA>'0000-00-00')
            $hba[$id][$dHBA]=$ResHBA;

        if($ExaFil=='oui'){
            $fil[$id][$dExaFil]=$ExaFil;
        }

        if($ExaPieds=='oui'){
            $pied[$id][$dExaPieds]=$ExaPieds;
        }

        if($dChol>'0000-00-00')
            $hdl[$id][$dChol]=$HDL;

        if($dLDL>'0000-00-00')
            $ldl[$id][$dLDL]=$LDL;

        if($dCreat>'0000-00-00')
            $creat[$id][$dCreat]=$Creat;

        if($dAlbu>'0000-00-00')
            $albu[$id][$dAlbu]=$iAlbu;

        if($dFond>'0000-00-00')
            $fond[$id][$dFond]=$iFond;

        if($dECG>'0000-00-00')
            $ecg[$id][$dECG]=$iECG;

        if($poids>0){
            $Poids[$id][$dsuivi]=$poids;
        }
        if(($TaSys>0)&&($TaDia>0)){
            $tension[$id][$dsuivi]=array("sys"=>$TaSys, "dia"=>$TaDia);
        }

        if(!isset($consult[$id])){
            $consult[$id]=array();

            $req2="SELECT date FROM evaluation_infirmier where id='$id' ORDER BY date";
            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

            while(list($date)=mysql_fetch_row($res2)){
                $consult[$id][]=$date;
            }
        }
    }


    $nb_hba=$nb_fil=$nb_pied=$nb_hdl=$nb_ldl=$nb_creat=$nb_albu=$nb_fond=$nb_ecg=$nb_poids=$nb_tension=$nb_consult=0;
    foreach($dossier as $id=>$dossier2){

        if(isset($hba[$id])){
            if(count($hba[$id])>$nb_hba){
                $nb_hba=count($hba[$id]);
            }
        }

        if(isset($fil[$id])){
            if(count($fil[$id])>$nb_fil){
                $nb_fil=count($fil[$id]);
            }
        }

        if(isset($pied[$id])){
            if(count($pied[$id])>$nb_pied){
                $nb_pied=count($pied[$id]);
            }
        }

        if(isset($hdl[$id])){
            if(count($hdl[$id])>$nb_hdl){
                $nb_hdl=count($hdl[$id]);
            }
        }

        if(isset($ldl[$id])){
            if(count($ldl[$id])>$nb_ldl){
                $nb_ldl=count($ldl[$id]);
            }
        }

        if(isset($creat[$id])){
            if(count($creat[$id])>$nb_creat){
                $nb_creat=count($creat[$id]);
            }
        }

        if(isset($albu[$id])){
            if(count($albu[$id])>$nb_albu){
                $nb_albu=count($albu[$id]);
            }
        }

        if(isset($fond[$id])){
            if(count($fond[$id])>$nb_fond){
                $nb_fond=count($fond[$id]);
            }
        }

        if(isset($ecg[$id])){
            if(count($ecg[$id])>$nb_ecg){
                $nb_ecg=count($ecg[$id]);
            }
        }

        if(isset($Poids[$id])){
            if(count($Poids[$id])>$nb_poids){
                $nb_poids=count($Poids[$id]);
            }
        }

        if(isset($tension[$id])){
            if(count($tension[$id])>$nb_tension){
                $nb_tension=count($tension[$id]);
            }
        }

        if(isset($consult[$id])){
            if(count($consult[$id])>$nb_consult){
                $nb_consult=count($consult[$id]);
            }
        }
    }

    $nb_hba=2*$nb_hba;
    $nb_hdl=2*$nb_hdl;
    $nb_ldl=2*$nb_ldl;
    $nb_creat=2*$nb_creat;
    $nb_albu=2*$nb_albu;
    $nb_fond=2*$nb_fond;
    $nb_ecg=2*$nb_ecg;
    $nb_poids=2*$nb_poids;
    $nb_tension=2*$nb_tension;

    ?>

    <tr>
        <td>id</Td>
        <td>date 1er suivi</td>
        <td>entrée cabinet</td>
        <td>sexe</td>
        <td>taille</td>
        <td>age au 30/06/07</Td>
        <td>actif</td>
        <td>date inactivation</Td>
        <td colspan="<?php echo $nb_consult;?>">Dates consult</td>
        <td colspan="<?php echo $nb_hba;?>">HBA</Td>
        <td colspan="<?php echo $nb_poids;?>">Poids</Td>
        <td colspan="<?php echo $nb_tension;?>">Tension</td>
        <td colspan="<?php echo $nb_hdl;?>">HDL</Td>
        <td colspan="<?php echo $nb_ldl;?>">LDL</Td>
        <td colspan="<?php echo $nb_creat;?>">Créatinine</Td>
        <td colspan="<?php echo $nb_albu;?>">Albuminurie</Td>
        <td colspan="<?php echo $nb_fond;?>">Fond d'oeil</Td>
        <td colspan="<?php echo $nb_ecg;?>">ECG</Td>
        <td colspan="<?php echo $nb_fil;?>">Monofilament</td>
        <td colspan="<?php echo $nb_pied;?>">Examen des Pieds</td>
    </Tr>
    <?php

    ?>
    <br><br>
    <?php

    foreach($dossier as $id=>$dossier2){
        echo "<tr><td>".$dossier2["id"]."</td><td>".$dossier2["dsuivi"]."</Td><td>".$tab_arrivee[$dossier2["cabinet"]]."</td>";
        echo "<td>".$dossier2["sexe"]."</td><td>".$dossier2["taille"]."</Td>";

        $tab_naiss=explode("-", $dossier2["dnaiss"]);

        $age=2007-$tab_naiss[0];
        if($tab_naiss[1]>="07")
            $age--;

        echo "<td>$age</Td><td>".$dossier2["actif"]."</td><td>";
        if($dossier2["actif"]=="non"){
            echo $dossier2["dmaj"];
        }
        echo "</Td>";

        /*		$req="SELECT * FROM evaluation_infirmier WHERE id='".$dossier2["id"]."'";
                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

                echo "<td>".mysql_num_rows($res)."</td>";
        */
        $i=0;
        if(isset($consult[$id])){
            foreach($consult[$id] as $dconsult){
                echo "<td>$dconsult</td>";
                $i=$i+1;
            }
        }

        while($i<$nb_consult){
            echo "<td></td>";
            $i++;
        }


        $i=0;
        if(isset($hba[$id])){
            foreach($hba[$id] as $dhba=>$ResHBA){
                echo "<td>$dhba</td><td>$ResHBA</td>";
                $i=$i+2;
            }
        }

        while($i<$nb_hba){
            echo "<td></td>";
            $i++;
        }

        $i=0;
        if(isset($Poids[$id])){
            foreach($Poids[$id] as $dpoids=>$poids){
                echo "<td>$dpoids</td><td>$poids</td>";
                $i=$i+2;
            }
        }

        while($i<$nb_poids){
            echo "<td></td>";
            $i++;
        }

        $i=0;
        if(isset($tension[$id])){
            foreach($tension[$id] as $dta=>$ta){
                echo "<td>$dta</td><td>".$ta["sys"]."/".$ta["dia"]."</td>";
                $i=$i+2;
            }
        }

        while($i<$nb_tension){
            echo "<td></td>";
            $i++;
        }


        $i=0;
        if(isset($hdl[$id])){
            foreach($hdl[$id] as $dHDL=>$HDL){
                echo "<td>$dHDL</td><td>$HDL</td>";
                $i=$i+2;
            }
        }

        while($i<$nb_hdl){
            echo "<td></td>";
            $i++;
        }

        $i=0;
        if(isset($ldl[$id])){
            foreach($ldl[$id] as $dLDL=>$LDL){
                echo "<td>$dLDL</td><td>$LDL</td>";
                $i=$i+2;
            }
        }

        while($i<$nb_ldl){
            echo "<td></td>";
            $i++;
        }


        $i=0;
        if(isset($creat[$id])){
            foreach($creat[$id] as $dCreat=>$Creat){
                echo "<td>$dCreat</td><td>$Creat</td>";
                $i=$i+2;
            }
        }

        while($i<$nb_creat){
            echo "<td></td>";
            $i++;
        }


        $i=0;
        if(isset($albu[$id])){
            foreach($albu[$id] as $dAlbu=>$Albu){
                echo "<td>$dAlbu</td><td>$Albu</td>";
                $i=$i+2;
            }
        }

        while($i<$nb_albu){
            echo "<td></td>";
            $i++;
        }

        $i=0;
        if(isset($fond[$id])){
            foreach($fond[$id] as $dFond=>$Fond){
                echo "<td>$dFond</td><td>$Fond</td>";
                $i=$i+2;
            }
        }

        while($i<$nb_fond){
            echo "<td></td>";
            $i++;
        }


        $i=0;
        if(isset($ecg[$id])){
            foreach($ecg[$id] as $dECG=>$ECG){
                echo "<td>$dECG</td><td>$ECG</td>";
                $i=$i+2;
            }
        }

        while($i<$nb_ecg){
            echo "<td></td>";
            $i++;
        }

        $i=0;
        if(isset($fil[$id])){
            foreach($fil[$id] as $dExaFil=>$ExaFil){
                echo "<td>$dExaFil</td>";
                $i=$i+1;
            }
        }

        while($i<$nb_fil){
            echo "<td></td>";
            $i++;
        }

        $i=0;
        if(isset($pied[$id])){
            foreach($pied[$id] as $dExaPieds=>$ExaPieds){
                echo "<td>$dExaPieds</td>";
                $i=$i+1;
            }
        }

        while($i<$nb_pied){
            echo "<td></td>";
            $i++;
        }


    }

    echo "</table>";
    }


    ?>
</body>
</html>
