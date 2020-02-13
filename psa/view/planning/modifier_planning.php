<?php

require_once("persistence/ConnectionFactory.php");
require_once("bean/PlanningInfirmieres.php");
require_once("bean/Account.php");	
require_once("persistence/AccountMapper.php");
require_once("controler/UtilityControler.php");
require_once("controler/PlanningControler.php");
require_once("../stats/patients/cab/logiciel_getname.php");


$path = 'https://'.$_SERVER['HTTP_HOST'];
#echo $path;
$conn = new ConnectionFactory();
$conn->getConnection();
session_start();
#var_dump($_POST);

$cab = $_GET['cab'];

// recupération des infos de l'infirmère current et de celles du cabinet
require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;



# force login herve en local sinon marche pas au cause des habilitations
if($_SERVER['APPLICATION_ENV']=='dev-herve'){
	$_SESSION['id.login'] = 'arizk';
	$_SESSION['id.nom'] = 'Rizk';
	$_SESSION['id.prenom'] = 'Antoine';
	$_SESSION['id.email'] = 'antoine.rizk@gisgo.fr';
	$_SESSION['id.telephone'] = '0680118013';
	$path = 'http://'.$_SERVER['HTTP_HOST']. $config->psa_path;
}


$userCurrent = $_SESSION['id.login'];

if($userCurrent=='arizk'){
	$userCurrent = 'asravier';
}

if($_POST){

	$result = PlanningControler::postModifPlanning();

	if($result){
		$message = '<div style="color:green;font-weight:bold;margin-bottom:10px;">Les informations ont bien été enregistrées</div>';
	}

}


$planning = PlanningInfirmieres::getPlanningByInfirmiereAndCab($userCurrent,$cab);

?>





<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Planning des infirmières</title>
<meta name="robots" content="noindex,nofollow">
<link href="<?php echo $path;?>/view/login/css/psp5.css" rel="stylesheet" type="text/css">
<link href="<?php echo $path;?>/view/login/css/psp_nicetitle.css" rel="stylesheet" type="text/css">
</head>



<?php
if($_SERVER['HTTP_HOST'] == 'psatest.asalee.fr') {
	$bodycolor = 'style="background-color:green"';
}
else{
	#$bodycolor = 'style="background-color:white"';
}
?>
<body <?php echo $bodycolor;?>>


<!-- PAGE -->
<div align="center">
<!-- ZONE IDENTITAIRE | Header -->
		<div id="header" style="width:100%">
			<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center" background-color="#FFF">
            	<tr>
            		<td bgcolor="white">
					<a href='<?php echo $path;?>/controler/ActionControler.php?controlerparams:param:controler=UtilityControler&controlerparams:param:action=AMEN' ><img src="<?php echo $path;?>/view/login/img/habillage/header_psa.gif" alt='Portail Services Asal&eacute;e' title='Retour accueil du Portail Services Asal&eacute;e' width='355' height='130' border='0'></a>
            		<td align="right" bgcolor="white"><img src="<?php echo $path;?>/view/login/img/logos/LogoAsalee2013.png" alt="Services propos&eacute; par&hellip;" width="190" height="68" hspace="20" vspace="0"></td>
            		<td width="10"><img src="<?php echo $path;?>/view/login/img/habillage/header_right.gif" width="10" height="130"></td>
           		</tr>
           	
           		<tr>
           			<td colspan="3"><hr style="width:90%"></td>
           		</tr>
           	</table>
           
		</div>
		<div style="background-color:#FFF;width:87%;margin:auto;padding:10px;">

		<!-- CONTENU -->

		<h2>Modifier mon planning dans le cabinet <?php echo $cab;?></h2>

		<br><br>
		
		<a href="planning.php"><- Retour planning</a>
		<br><br>
		<?php echo $message;?>
		<div>
			<form action="#" method="POST">
				<input type="hidden" name="cab" value="<?php echo $cab;?>">
			<table width="500" border="1" cellpadding="5">
				<tr>
					<td><b>Jour</b></td>
					<td><b>Informations</b></td>
				</tr>
				<tr>
					<td><b>Lundi</b></td>
					<td><textarea name="lundi" style="width:300px;height:50px"><?php echo stripslashes($planning['lundi']);?></textarea></td>
				</tr>
				<tr>
					<td><b>Mardi</b></td>
					<td><textarea name="mardi" style="width:300px;height:50px"><?php echo stripslashes($planning['mardi']);?></textarea></td>
				</tr>
				<tr>
					<td><b>Mercredi</b></td>
					<td><textarea name="mercredi" style="width:300px;height:50px"><?php echo stripslashes($planning['mercredi']);?></textarea></td>
				</tr>
				<tr>
					<td><b>Jeudi</b></td>
					<td><textarea name="jeudi" style="width:300px;height:50px"><?php echo stripslashes($planning['jeudi']);?></textarea></td>
				</tr>
				<tr>
					<td><b>Vendredi</b></td>
					<td><textarea name="vendredi" style="width:300px;height:50px"><?php echo stripslashes($planning['vendredi']);?></textarea></td>
				</tr>
				<tr>
					<td><b>Samedi</b></td>
					<td><textarea name="samedi" style="width:300px;height:50px"><?php echo stripslashes($planning['samedi']);?></textarea></td>
				</tr>
				<tr>
					
					<td colspan="2" style="text-align:center"><input type="submit" value="Enregistrer"> <input type="button" value="Retour" onClick="javascript:history.back();"></td>
				</tr>
			</table>
		</div>
		
			


		<?php #} ?>
		
	</div>
</div>

