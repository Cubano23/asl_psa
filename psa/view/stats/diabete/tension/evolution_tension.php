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
    <title>Tension moyenne à la date du jour et 1 an avant - cabinets actifs</title>
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

entete_asalee("Tension moyenne à la date du jour et 1 an avant - cabinets actifs");

//echo $loc;
?>
<!--<table cellpadding="2" cellspacing="2" border="0"
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
<font face='times new roman'>Taux de diabète équilibré</font></i>";
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
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $regions, $liste_reg;


    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */

    $req="SELECT dossier.cabinet, count(*), nom_cab, region ".
        "FROM dossier, account ".
        "WHERE dossier.cabinet!='zTest' and dossier.cabinet!='irdes'  and dossier.cabinet!='ergo'  ".
        "and dossier.cabinet!='jgomes' and dossier.cabinet!='sbirault' and region!='' ".
        "AND actif='oui' ".
        "and dossier.cabinet=account.cabinet ".
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
        $regions[$cab]=$region;

        if(!in_array($region, $liste_reg)){
            $liste_reg[]=$region;
        }

//	 $tpat[$cab] = $pat;
    }

    foreach($tville as $cab=>$ville){
        $tcabinet_util[$cab]=0;
    }

    $date3mois=date("Y-m-d", mktime(1, 1, 1, date("m")-3, date('d'), date("Y")));
    $req="SELECT cabinet from evaluation_infirmier, dossier where ".
        "dossier.id=evaluation_infirmier.id and date>='$date3mois' group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }
    $req="SELECT cabinet from suivi_diabete, dossier where ".
        "dossier.id=suivi_diabete.dossier_id and dsuivi>='$date3mois' ".
        "group by cabinet";
    $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

    while(list($cab)=mysql_fetch_row($res)){
        $tcabinet_util[$cab]=1;
    }

    sort($liste_reg);
    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre',
        '11'=>'Novembre', '12'=>'Décembre');

    echo '<b>Données à la date du jour : '.date('d')." ".$mois[date('m')]." ".date('Y')."</b>";

    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, id, dtension, TaSys, TaDia ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and dossier.cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND actif='oui' ".
            "AND suivi_diabete.dossier_id=dossier.id and dtension>'0000-00-00' ".
            "ORDER BY cabinet, id, dtension ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        foreach ($tcabinet as $cab)
        {
            $tpat[$cab]=0;//nb patients
            $syst[$cab]=0;//Valeur total systole
            $diast[$cab]=0;//Valeur total diastole
            $tpat_consult[$cab]=0;//nb patients consult
            $syst_consult[$cab]=0;//Valeur total systole
            $diast_consult[$cab]=0;//Valeur total diastole

            $syst1an[$cab]=0;//Valeur total systole 1 an avant
            $diast1an[$cab]=0;//Valeur total diastole 1 an avant
            $syst_consult1an[$cab]=0;//Valeur total systole 1 an avant
            $diast_consult1an[$cab]=0;//Valeur total diastole 1 an avant
        }

        $tpat['tot']=0;
        $syst['tot']=0;
        $diast['tot']=0;
        $tpat_consult['tot']=0;
        $syst_consult['tot']=0;
        $diast_consult['tot']=0;

        $syst1an['tot']=0;
        $diast1an['tot']=0;
        $syst_consult1an['tot']=0;
        $diast_consult1an['tot']=0;

        foreach($liste_reg as $reg){
            $tpat[$reg]=0;
            $syst[$reg]=0;
            $diast[$reg]=0;
            $tpat_consult[$reg]=0;
            $syst_consult[$reg]=0;
            $diast_consult[$reg]=0;

            $syst1an[$reg]=0;
            $diast1an[$reg]=0;
            $syst_consult1an[$reg]=0;
            $diast_consult1an[$reg]=0;
        }


        $id_prec='';
        $cab_prec="";
        $i=0;
        while(list($cab, $id, $dtension, $TaSys, $TaDia) = mysql_fetch_row($res)) {
            if(isset($regions[$cab_prec])){
                if($tcabinet_util[$cab_prec]==1){
                    $i++;
                    if($id_prec!=$id)
                    {

                        if($id_prec!='')
                        {
                            $tension_prec=explode("-", $tension_prec);
                            $tension_prec[0]=$tension_prec[0]-1;
                            //Vérif s'il y a eu une tension 1 an avant, +/- 2 mois
                            $req2="SELECT TaSys, TaDia from suivi_diabete WHERE ".
                                "DATEDIFF(dtension, '".$tension_prec[0]."-".
                                $tension_prec[1]."-".$tension_prec[2]."')>-60 and ".
                                "DATEDIFF(dtension, '".$tension_prec[0]."-".
                                $tension_prec[1]."-".$tension_prec[2]."')<60 and ".
                                "dossier_id='$id_prec' order by dtension DESC limit 0,1";
                            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                            if(mysql_num_rows($res2)>0){
                                list($sys1an, $dia1an)=mysql_fetch_row($res2);
                                //Vérif si le patient a bien eu une consult
                                $req2="SELECT date from evaluation_infirmier WHERE id='$id'";
                                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                                if(mysql_num_rows($res2)>0){
                                    $tpat_consult['tot']=$tpat_consult['tot']+1;
                                    $tpat_consult[$cab_prec]=$tpat_consult[$cab_prec]+1;
                                    $tpat_consult[$regions[$cab_prec]]=$tpat_consult[$regions[$cab_prec]]+1;

                                    $syst_consult['tot']=$syst_consult['tot']+$TaSys_prec;
                                    $syst_consult[$cab_prec]=$syst_consult[$cab_prec]+$TaSys_prec;
                                    $syst_consult[$regions[$cab_prec]]=$syst_consult[$regions[$cab_prec]]+$TaSys_prec;

                                    $diast_consult['tot']=$diast_consult['tot']+$TaDia_prec;
                                    $diast_consult[$cab_prec]=$diast_consult[$cab_prec]+$TaDia_prec;
                                    $diast_consult[$regions[$cab_prec]]=$diast_consult[$regions[$cab_prec]]+$TaDia_prec;

                                    $syst_consult1an[$cab_prec]=$syst_consult1an[$cab_prec]+$sys1an;
                                    $syst_consult1an[$regions[$cab_prec]]=$syst_consult1an[$regions[$cab_prec]]+$sys1an;
                                    $syst_consult1an['tot']=$syst_consult1an['tot']+$sys1an;

                                    $diast_consult1an[$cab_prec]=$diast_consult1an[$cab_prec]+$dia1an;
                                    $diast_consult1an[$regions[$cab_prec]]=$diast_consult1an[$regions[$cab_prec]]+$dia1an;
                                    $diast_consult1an['tot']=$diast_consult1an['tot']+$dia1an;

                                }
                                $tpat['tot']=$tpat['tot']+1;
                                $tpat[$cab_prec]=$tpat[$cab_prec]+1;
                                $tpat[$regions[$cab_prec]]=$tpat[$regions[$cab_prec]]+1;

                                $syst['tot']=$syst['tot']+$TaSys_prec;
                                $syst[$cab_prec]=$syst[$cab_prec]+$TaSys_prec;
                                $syst[$regions[$cab_prec]]=$syst[$regions[$cab_prec]]+$TaSys_prec;

                                $diast['tot']=$diast['tot']+$TaDia_prec;
                                $diast[$cab_prec]=$diast[$cab_prec]+$TaDia_prec;
                                $diast[$regions[$cab_prec]]=$diast[$regions[$cab_prec]]+$TaDia_prec;

                                $syst1an['tot']=$syst1an['tot']+$sys1an;
                                $syst1an[$cab_prec]=$syst1an[$cab_prec]+$sys1an;
                                $syst1an[$regions[$cab_prec]]=$syst1an[$regions[$cab_prec]]+$sys1an;

                                $diast1an['tot']=$diast1an['tot']+$dia1an;
                                $diast1an[$cab_prec]=$diast1an[$cab_prec]+$dia1an;
                                $diast1an[$regions[$cab_prec]]=$diast1an[$regions[$cab_prec]]+$dia1an;
                            }

                            $cab_prec=$cab;
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                            $id_prec=$id;
                            $tension_prec=$dtension;


                        }
                        else
                        {
                            $id_prec=$id;
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                            $cab_prec=$cab;
                            $tension_prec=$dtension;

                        }

                    }
                    else
                    {
                        if(($TaSys!=0)&&($TaSys!='NULL')&&($TaDia!=0)&&($TaDia!='NULL'))
                        {
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                            $tension_prec=$dtension;
                        }
                    }
                }
                else
                {
                    $id_prec=$id;
                    $TaSys_prec=$TaSys;
                    $TaDia_prec=$TaDia;
                    $cab_prec=$cab;
                    $tension_prec=$dtension;

                }
            }
            else
            {
                $id_prec=$id;
                $TaSys_prec=$TaSys;
                $TaDia_prec=$TaDia;
                $cab_prec=$cab;
                $tension_prec=$dtension;

            }
        }

        if(isset($regions[$cab_prec])){
            if($tcabinet_util[$cab_prec]==1){
                $tension_prec=explode("-", $tension_prec);
                $tension_prec[0]=$tension_prec[0]-1;
                //Vérif s'il y a eu une tension 1 an avant, +/- 2 mois
                $req2="SELECT TaSys, TaDia from suivi_diabete WHERE ".
                    "DATEDIFF(dtension, '".$tension_prec[0]."-".
                    $tension_prec[1]."-".$tension_prec[2]."')>-60 and ".
                    "DATEDIFF(dtension, '".$tension_prec[0]."-".
                    $tension_prec[1]."-".$tension_prec[2]."')<60 and ".
                    "dossier_id='$id_prec' order by dtension DESC limit 0,1";
                $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                if(mysql_num_rows($res2)>0){
                    list($sys1an, $dia1an)=mysql_fetch_row($res2);
                    //Vérif si le patient a bien eu une consult
                    $req2="SELECT date from evaluation_infirmier WHERE id='$id'";
                    $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                    if(mysql_num_rows($res2)>0){
                        $tpat_consult['tot']=$tpat_consult['tot']+1;
                        $tpat_consult[$cab_prec]=$tpat_consult[$cab_prec]+1;
                        $tpat_consult[$regions[$cab_prec]]=$tpat_consult[$regions[$cab_prec]]+1;

                        $syst_consult['tot']=$syst_consult['tot']+$TaSys_prec;
                        $syst_consult[$cab_prec]=$syst_consult[$cab_prec]+$TaSys_prec;
                        $syst_consult[$regions[$cab_prec]]=$syst_consult[$regions[$cab_prec]]+$TaSys_prec;

                        $diast_consult['tot']=$diast_consult['tot']+$TaDia_prec;
                        $diast_consult[$cab_prec]=$diast_consult[$cab_prec]+$TaDia_prec;
                        $diast_consult[$regions[$cab_prec]]=$diast_consult[$regions[$cab_prec]]+$TaDia_prec;

                        $syst_consult1an[$cab_prec]=$syst_consult1an[$cab_prec]+$sys1an;
                        $syst_consult1an[$regions[$cab_prec]]=$syst_consult1an[$regions[$cab_prec]]+$sys1an;
                        $syst_consult1an["tot"]=$syst_consult1an["tot"]+$sys1an;

                        $diast_consult1an[$cab_prec]=$diast_consult1an[$cab_prec]+$dia1an;
                        $diast_consult1an[$regions[$cab_prec]]=$diast_consult1an[$regions[$cab_prec]]+$dia1an;
                        $diast_consult1an["tot"]=$diast_consult1an["tot"]+$dia1an;

                    }
                    $tpat['tot']=$tpat['tot']+1;
                    $tpat[$cab_prec]=$tpat[$cab_prec]+1;
                    $tpat[$regions[$cab_prec]]=$tpat[$regions[$cab_prec]]+1;

                    $syst['tot']=$syst['tot']+$TaSys_prec;
                    $syst[$cab_prec]=$syst[$cab_prec]+$TaSys_prec;
                    $syst[$regions[$cab_prec]]=$syst[$regions[$cab_prec]]+$TaSys_prec;

                    $diast['tot']=$diast['tot']+$TaDia_prec;
                    $diast[$cab_prec]=$diast[$cab_prec]+$TaDia_prec;
                    $diast[$regions[$cab_prec]]=$diast[$regions[$cab_prec]]+$TaDia_prec;

                    $syst1an['tot']=$syst1an['tot']+$sys1an;
                    $syst1an[$cab_prec]=$syst1an[$cab_prec]+$sys1an;
                    $syst1an[$regions[$cab_prec]]=$syst1an[$regions[$cab_prec]]+$sys1an;

                    $diast1an['tot']=$diast1an['tot']+$dia1an;
                    $diast1an[$cab_prec]=$diast1an[$cab_prec]+$dia1an;
                    $diast1an[$regions[$cab_prec]]=$diast1an[$regions[$cab_prec]]+$dia1an;
                }
            }
        }

        echo "<br><br>";
        ?>

        <tr>
            <td colspan='2'></td>
            <td align="center"><b>Moyenne</b></td>
            <?php


            foreach($liste_reg as $reg){
                echo "<td align='center'><b>moyenne $reg</b></td>";
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="center"><b><?php echo $tville[$cab];?></b></td>
                <?php

            }
            ?>
        </tr>

        <tr>
            <td rowspan='3'>Tous patients confondus</td>
            <td>Valeur moyenne de la dernière tension</td>
            <?php

            echo "<td align='right'>".round($syst["tot"]/$tpat["tot"])."/".round($diast["tot"]/$tpat["tot"])."</td>";

            foreach($liste_reg as $reg){
                if($tpat[$reg]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst[$reg]/$tpat[$reg])."/".round($diast[$reg]/$tpat[$reg])."</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                if($tpat[$cab]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst[$cab]/$tpat[$cab])."/".round($diast[$cab]/$tpat[$cab])."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Valeur de la tension 1 an auparavant</td>
            <?php
            echo "<td align='right'>".round($syst1an["tot"]/$tpat["tot"])."/".round($diast1an["tot"]/$tpat["tot"])."</td>";

            foreach($liste_reg as $reg){
                if($tpat[$reg]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst1an[$reg]/$tpat[$reg])."/".round($diast1an[$reg]/$tpat[$reg])."</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                if($tpat[$cab]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst1an[$cab]/$tpat[$cab])."/".round($diast1an[$cab]/$tpat[$cab])."</td>";
                }
            }
            echo "</tr>";
            ?>
        <tr>
            <td>Nombre de patients concernés</td>
            <?php
            echo "<td align='right'>".$tpat["tot"]."</td>";

            foreach($liste_reg as $reg){
                echo "<td align='right'>".$tpat[$reg]."</td>";
            }

            foreach ($tcabinet as $cab)
            {
                echo "<td align='right'>".$tpat[$cab]."</td>";
            }
            echo "</tr>";/*
	?>
	<tr>
		<td>Tension totale à la date du jour</td>
		<?php
			echo "<td align='right'>".$syst["tot"]."/".$diast["tot"]."</td>";
			
		foreach($liste_reg as $reg){
			echo "<td align='right'>".$syst[$reg]."/".$diast[$reg]."</td>";
		}
	
	foreach ($tcabinet as $cab)
	{
		echo "<td align='right'>".$syst[$cab]."/".$diast[$cab]."</td>";
	}
	echo "</tr>";
	?>
	<tr>
		<td>Tension totale 1 an avant</td>
		<?php
			echo "<td align='right'>".$syst1an["tot"]."/".$diast1an["tot"]."</td>";
			
		foreach($liste_reg as $reg){
			echo "<td align='right'>".$syst1an[$reg]."/".$diast1an[$reg]."</td>";
		}
	
	foreach ($tcabinet as $cab)
	{
		echo "<td align='right'>".$syst1an[$cab]."/".$diast1an[$cab]."</td>";
	}
	echo "</tr>";*/

            ?>
        <tr>
            <td rowspan='3'>Patients vus en consultation</td>
            <td>Valeur moyenne de la dernière tension</td>
            <?php

            echo "<td align='right'>".round($syst_consult["tot"]/$tpat_consult["tot"])."/".round($diast_consult["tot"]/$tpat_consult["tot"])."</td>";

            foreach($liste_reg as $reg){
                if($tpat[$reg]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst_consult[$reg]/$tpat_consult[$reg])."/".round($diast_consult[$reg]/$tpat_consult[$reg])."</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                if($tpat_consult[$cab]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst_consult[$cab]/$tpat_consult[$cab])."/".round($diast_consult[$cab]/$tpat_consult[$cab])."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Valeur de la tension 1 an auparavant</td>
            <?php
            echo "<td align='right'>".round($syst_consult1an["tot"]/$tpat_consult["tot"])."/".round($diast_consult1an["tot"]/$tpat_consult["tot"])."</td>";

            foreach($liste_reg as $reg){
                if($tpat[$reg]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst_consult1an[$reg]/$tpat_consult[$reg])."/".round($diast_consult1an[$reg]/$tpat_consult[$reg])."</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                if($tpat_consult[$cab]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($syst_consult1an[$cab]/$tpat_consult[$cab])."/".round($diast_consult1an[$cab]/$tpat_consult[$cab])."</td>";
                }
            }
            echo "</tr>";
            ?>
        <tr>
            <td>Nombre de patients concernés</td>
            <?php
            echo "<td align='right'>".$tpat_consult["tot"]."</td>";

            foreach($liste_reg as $reg){
                echo "<td align='right'>".$tpat_consult[$reg]."</td>";
            }

            foreach ($tcabinet as $cab)
            {
                echo "<td align='right'>".$tpat_consult[$cab]."</td>";
            }
            echo "</tr>";/*
?>
	<tr>
		<td>Tension totale à la date du jour</td>
		<?php
			echo "<td align='right'>".$syst_consult["tot"]."/".$diast_consult["tot"]."</td>";
			
		foreach($liste_reg as $reg){
			echo "<td align='right'>".$syst_consult[$reg]."/".$diast_consult[$reg]."</td>";
		}
	
	foreach ($tcabinet as $cab)
	{
		echo "<td align='right'>".$syst_consult[$cab]."/".$diast_consult[$cab]."</td>";
	}
	echo "</tr>";
	?>
	<tr>
		<td>Tension totale 1 an avant</td>
		<?php
			echo "<td align='right'>".$syst_consult1an["tot"]."/".$diast_consult1an["tot"]."</td>";
			
		foreach($liste_reg as $reg){
			echo "<td align='right'>".$syst_consult1an[$reg]."/".$diast_consult1an[$reg]."</td>";
		}
	
	foreach ($tcabinet as $cab)
	{
		echo "<td align='right'>".$syst_consult1an[$cab]."/".$diast_consult1an[$cab]."</td>";
	}
	echo "</tr>";*/
            ?>
        <tr>
            <td rowspan='3'>Patients non vus en consultation</td>
            <td>Valeur moyenne de la dernière tension</td>
            <?php

            echo "<td align='right'>".round(($syst["tot"]-$syst_consult["tot"])/($tpat["tot"]-$tpat_consult["tot"]))."/".round(($diast["tot"]-$diast_consult["tot"])/($tpat["tot"]-$tpat_consult["tot"]))."</td>";

            foreach($liste_reg as $reg){
                if($tpat[$reg]==$tpat_consult[$reg]){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round(($syst[$reg]-$syst_consult[$reg])/($tpat[$reg]-$tpat_consult[$reg]))."/".round(($diast[$reg]-$diast_consult[$reg])/($tpat[$reg]-$tpat_consult[$reg]))."</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                if($tpat[$cab]==$tpat_consult[$cab]){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round(($syst[$cab]-$syst_consult[$cab])/($tpat[$cab]-$tpat_consult[$cab]))."/".round(($diast[$cab]-$diast_consult[$cab])/($tpat[$cab]-$tpat_consult[$cab]))."</td>";
                }
            }
            ?>
        </tr>
        <tr>
            <td>Valeur de la tension 1 an auparavant</td>
            <?php
            echo "<td align='right'>".round(($syst1an["tot"]-$syst_consult1an["tot"])/($tpat["tot"]-$tpat_consult["tot"]))."/".round(($diast1an["tot"]-$diast_consult1an["tot"])/($tpat["tot"]-$tpat_consult["tot"]))."</td>";

            foreach($liste_reg as $reg){
                if($tpat[$reg]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round(($syst1an[$reg]-$syst_consult1an[$reg])/($tpat[$reg]-$tpat_consult[$reg]))."/".round(($diast1an[$reg]-$diast_consult1an[$reg])/($tpat[$reg]-$tpat_consult[$reg]))."</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                if($tpat[$cab]==$tpat_consult[$cab]){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round(($syst1an[$cab]-$syst_consult1an[$cab])/($tpat[$cab]-$tpat_consult[$cab]))."/".round(($diast1an[$cab]-$diast_consult1an[$cab])/($tpat[$cab]-$tpat_consult[$cab]))."</td>";
                }
            }
            echo "</tr>";
            ?>
        <tr>
            <td>Nombre de patients concernés</td>
            <?php
            echo "<td align='right'>".($tpat["tot"]-$tpat_consult["tot"])."</td>";

            foreach($liste_reg as $reg){
                echo "<td align='right'>".($tpat[$reg]-$tpat_consult[$reg])."</td>";
            }

            foreach ($tcabinet as $cab)
            {
                echo "<td align='right'>".($tpat[$cab]-$tpat_consult[$cab])."</td>";
            }
            echo "</tr>";
            /*	?>
                <tr>
                    <td>Tension totale à la date du jour</td>
                    <?php
                        echo "<td align='right'>".($syst["tot"]-$syst_consult["tot"])."/".($diast["tot"]-$diast_consult["tot"])."</td>";

                    foreach($liste_reg as $reg){
                        echo "<td align='right'>".($syst[$reg]-$syst_consult[$reg])."/".($diast[$reg]-$diast_consult[$reg])."</td>";
                    }

                foreach ($tcabinet as $cab)
                {
                    echo "<td align='right'>".($syst[$cab]-$syst_consult[$cab])."/".($diast[$cab]-$diast_consult[$cab])."</td>";
                }
                echo "</tr>";
                ?>
                <tr>
                    <td>Tension totale 1 an avant</td>
                    <?php
                        echo "<td align='right'>".($syst1an["tot"]-$syst_consult1an["tot"])."/".($diast1an["tot"]-$diast_consult1an["tot"])."</td>";

                    foreach($liste_reg as $reg){
                        echo "<td align='right'>".($syst1an[$reg]-$syst_consult1an[$reg])."/".($diast1an[$reg]-$diast_consult1an[$reg])."</td>";
                    }

                foreach ($tcabinet as $cab)
                {
                    echo "<td align='right'>".($syst1an[$cab]-$syst_consult1an[$cab])."/".($diast1an[$cab]-$diast_consult1an[$cab])."</td>";
                }
                echo "</tr>";*/

            ?>
    </table>
    <br><br>
    <?php
    die;

    $annee0=2004;
    $mois0=3;

    $annee=date('Y');
    $mois=date('m');

    $mois--;


    if($mois<3)
    {
        $annee--;
        $mois=12;
    }
    elseif(($mois>=3)&&($mois<6))
    {
        $mois=3;
    }
    elseif(($mois>=6)&&($mois<9))
    {
        $mois=6;
    }
    elseif(($mois>=9)&&($mois<12))
    {
        $mois=9;
    }

    $jour[3]=$jour[12]=31;
    $jour[6]=$jour[9]=30;

    while(($annee>$annee0)||(($annee==$annee0)&&($mois>=$mois0)))
    {
        if($mois<10)
        {
            $date=$annee.'-0'.$mois.'-'.$jour[$mois];
        }
        else
        {
            $date=$annee.'-'.$mois.'-'.$jour[$mois];
        }
        tableau($date);

        $mois=$mois-3;

        if($mois<=0)
        {
            $mois=$mois+12;
            $annee--;
        }
    }
    ?>

    <sup>1</sup>Proportion de patients ayant eu au moins un suivi diabète et dont la dernière valeur de tension est inférieure à 140/90mmHg<br>
    <sup>2</sup>Proportion de patients ayant eu au moins un suivi diabète et dont la dernière valeur de tension est supérieure à 140/90mmHg<br>
    <sup>3</sup>Proportion de patients ayant eu au moins un suivi diabète et avec aucune valeur de tension<br>
    <sup>4</sup>Nb de patients ayant eu au moins un suivi diabète et dont la dernière valeur de tension est inférieure à 140/90mmHg<br>
    <sup>5</sup>Nb de patients ayant eu au moins un suivi diabète et dont la dernière valeur de tension est supérieure à 140/90mmHg<br>
    <sup>6</sup>Nb de patients ayant eu au moins un suivi diabète et avec aucune valeur de tension<br>
    <?
}


function tableau($date){
    global $message,$Dossier,$Cabinet, $deval, $self, $tcabinet, $tville, $regions, $liste_reg;


    $mois=array('01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril', '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet',
        '08'=>'Août', '09'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');

    $tab_date=split('-', $date);

    echo "<b>Données au ".$tab_date[2]." ".$mois[$tab_date[1]]." ".$tab_date[0]."</b>";

    /*print_r($t_tot);echo "<br>";
    print_r($t_sein);echo "<br>";
    print_r($t_cogni);echo "<br>";
    print_r($t_colon);echo "<br>";
    print_r($t_uterus);echo "<br>";
    print_r($t_diab);echo "<br>";
    */



    ?>
    <br>
    <br>
    <table border=1 width='100%'>

        <?php

        foreach($tville as $cab=>$ville){
            $tcabinet_util[$cab]=0;
        }

        $date3mois=date("Y-m-d", mktime(1, 1, 1, $tab_date[1]-3, $tab_date[2], $tab_date[0]));
        $req="SELECT cabinet from evaluation_infirmier, dossier where ".
            "dossier.id=evaluation_infirmier.id and date>='$date3mois' ".
            "and date<='$date' ".
            "group by cabinet";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($cab)=mysql_fetch_row($res)){
            $tcabinet_util[$cab]=1;
        }
        $req="SELECT cabinet from suivi_diabete, dossier where ".
            "dossier.id=suivi_diabete.dossier_id and dsuivi>='$date3mois' ".
            "and dsuivi<='$date' ".
            "group by cabinet";
        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");

        while(list($cab)=mysql_fetch_row($res)){
            $tcabinet_util[$cab]=1;
        }

        //taux diab 2 suivis dans asalée
        $req="SELECT cabinet, id, dsuivi, TaSys, TaDia ".
            "FROM suivi_diabete, dossier ".
            "WHERE cabinet!='zTest' and cabinet!='irdes'  and cabinet!='ergo'  and dossier.cabinet!='jgomes' ".
            "and dossier.cabinet!='sbirault' ".
            "AND ( (dossier.actif='oui' AND dossier.dcreat<='$date') ".
            "OR (dossier.actif='non' AND dossier.dmaj>'$date' AND dossier.dcreat<='$date')) ".
            "AND suivi_diabete.dossier_id=dossier.id ".
            "AND dsuivi<='$date' ".
            "ORDER BY cabinet, id, dsuivi ";

        $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br>$req");


        foreach ($tcabinet as $cab)
        {
            $tpat[$cab][0]=0;//pas de tension
            $tpat[$cab][1]=0;//Ta<140/90
            $tpat[$cab][2]=0;//Ta>=140/90
            $total[$cab]=0;
        }

        $tpat['tot'][0]=0;
        $tpat['tot'][1]=0;
        $tpat['tot'][2]=0;
        $total['tot']=0;

        foreach($liste_reg as $reg){
            $tpat[$reg][0]=0;
            $tpat[$reg][1]=0;
            $tpat[$reg][2]=0;
            $total[$reg]=0;
        }



        $id_prec='';
        $cab_prec="";
        $i=0;
        while(list($cab, $id, $dsuivi, $TaSys, $TaDia) = mysql_fetch_row($res)) {
            if(isset($regions[$cab_prec])){
                if($tcabinet_util[$cab_prec]==1){
                    $i++;
                    if($id_prec!=$id)
                    {
                        if($id_prec!='')
                        {
                            //Vérif si le patient a bien eu une consult
                            $req2="SELECT date from evaluation_infirmier WHERE id='$id' ".
                                "and date<='$date'";
                            $res2=mysql_query($req2) or die("erreur SQL:".mysql_error()."<br>$req2");

                            if(mysql_num_rows($res2)>0){
                                if(($TaSys_prec<140)&&($TaDia_prec<90)&&($TaSys_prec>0)&&($TaDia_prec>0))
                                {
                                    $tpat['tot'][1]=$tpat['tot'][1]+1;
                                    $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                                    $tpat[$regions[$cab_prec]][1]=$tpat[$regions[$cab_prec]][1]+1;
                                }
                                elseif(($TaSys_prec>=140)||($TaDia_prec>=90))
                                {
                                    $tpat['tot'][2]=$tpat['tot'][2]+1;
                                    $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                                    $tpat[$regions[$cab_prec]][2]=$tpat[$regions[$cab_prec]][2]+1;
                                }
                                else //pas de mesure
                                {
                                    $tpat['tot'][0]=$tpat['tot'][0]+1;
                                    $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                                    $tpat[$regions[$cab_prec]][0]=$tpat[$regions[$cab_prec]][0]+1;
                                }


                                $total['tot']=$total['tot']+1;

                                $total[$regions[$cab_prec]]=$total[$regions[$cab_prec]]+1;

                                $total[$cab_prec]=$total[$cab_prec]+1;
                            }
                            $cab_prec=$cab;
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                            $id_prec=$id;

                        }
                        else
                        {
                            $id_prec=$id;
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                            $cab_prec=$cab;

                        }

                    }
                    else
                    {
                        if(($TaSys!=0)&&($TaSys!='NULL')&&($TaDia!=0)&&($TaDia!='NULL'))
                        {
                            $TaSys_prec=$TaSys;
                            $TaDia_prec=$TaDia;
                        }
                    }
                }
                else{
                    $id_prec=$id;
                    $TaSys_prec=$TaSys;
                    $TaDia_prec=$TaDia;
                    $cab_prec=$cab;
                }
            }
            else
            {
                $id_prec=$id;
                $TaSys_prec=$TaSys;
                $TaDia_prec=$TaDia;
                $cab_prec=$cab;

            }
        }

        if(isset($regions[$cab_prec])){
            if($tcabinet_util[$cab_prec]==1){
                if(($TaSys_prec<140)&&($TaDia_prec<90)&&($TaSys_prec>0)&&($TaDia_prec>0))
                {
                    $tpat['tot'][1]=$tpat['tot'][1]+1;
                    $tpat[$cab_prec][1]=$tpat[$cab_prec][1]+1;

                    $tpat[$regions[$cab_prec]][1]=$tpat[$regions[$cab_prec]][1]+1;
                }
                elseif(($TaSys_prec>=140)||($TaDia_prec>=90))
                {
                    $tpat['tot'][2]=$tpat['tot'][2]+1;
                    $tpat[$cab_prec][2]=$tpat[$cab_prec][2]+1;

                    $tpat[$regions[$cab_prec]][2]=$tpat[$regions[$cab_prec]][2]+1;
                }
                else //pas de mesure
                {
                    $tpat['tot'][0]=$tpat['tot'][0]+1;
                    $tpat[$cab_prec][0]=$tpat[$cab_prec][0]+1;

                    $tpat[$regions[$cab_prec]][0]=$tpat[$regions[$cab_prec]][0]+1;
                }



                $total['tot']=$total['tot']+1;

                $total[$regions[$cab_prec]]=$total[$regions[$cab_prec]]+1;

                $total[$cab_prec]=$total[$cab_prec]+1;
            }
        }

        ?>

        <tr>
            <td>Valeurs de la tension</td>
            <td align="center"><b>Moyenne</b></td>

            <?php

            foreach($liste_reg as $reg){
                echo "<td align='center'><b>moyenne $reg</b></td>";
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="center"><b><?php echo $tville[$cab];?></b></td>
                <?php

            }
            ?>
        </tr>

        <?php

        $intitule=array('Manquant', '<140/90<sup>1</sup>', '>=140/90<sup>2</sup>');


        for($i=1; $i<=2; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule[$i]; ?></td>
                <td align="right"><?php echo round($tpat['tot'][$i]/$total['tot']*100);?>%</Td>

                <?php

                foreach($liste_reg as $reg){
                    if($total[$reg]==0){
                        echo "<td align='right'>ND</td>";
                    }
                    else{
                        echo "<td align='right'>".round($tpat[$reg][$i]/$total[$reg]*100)."%</Td>";
                    }
                }

                foreach ($tcabinet as $cab)
                {
                    ?>
                    <td align="right"><?php if($total[$cab]==0)
                {
                    echo "ND</td>";
                }
                else
                {
                    echo round($tpat[$cab][$i]/$total[$cab]*100);?>%</td>
                    <?
                }


                }
                ?>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>Manquant<sup>3</sup></td>
            <td align='right'><?php echo round($tpat['tot'][0]/$total['tot']*100);?>%</td>

            <?php

            foreach($liste_reg as $reg){
                if($total[$reg]==0){
                    echo "<td align='right'>ND</td>";
                }
                else{
                    echo "<td align='right'>".round($tpat[$reg][0]/$total[$reg]*100)."%</td>";
                }
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="right"><?php if($total[$cab]==0)
            {
                echo "ND</td>";
            }
            else
            {
                echo round($tpat[$cab][0]/$total[$cab]*100);?>%</td>
                <?
            }

            }
            ?>

        </tr>



        <?
        $intitule2=array('Manquant', 'nb<140/90<sup>4</sup>', 'nb >=140/90<sup>5</sup>');

        for($i=1; $i<=2; $i++)
        {
            ?>
            <tr>
                <td><?php echo $intitule2[$i]; ?></td>
                <td align="right"><?php echo $tpat['tot'][$i];?></Td>

                <?php

                foreach($liste_reg as $reg){
                    echo "<td align='right'>".$tpat[$reg][$i]."</Td>";
                }

                foreach ($tcabinet as $cab)
                {
                    ?>
                    <td align="right"><?php
                        echo $tpat[$cab][$i];?></td>
                    <?


                }
                ?>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>nb Manquant<sup>6</sup></td>
            <td align='right'><?php echo $tpat['tot'][0];?></td>

            <?php

            foreach($liste_reg as $reg){
                echo "<td align='right'>".$tpat[$reg][0]."</td>";
            }

            foreach ($tcabinet as $cab)
            {
                ?>
                <td align="right"><?php
                    echo $tpat[$cab][0];?></td>
                <?


            }
            ?>

        </tr>

    </table>
    <br><br>
    <?php

}


?>
</body>
</html>
