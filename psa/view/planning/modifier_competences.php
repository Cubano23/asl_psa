<?php

require_once("persistence/ConnectionFactory.php");
require_once("bean/CompetencesInfirmieres.php");
require_once("bean/Account.php");	
require_once("persistence/AccountMapper.php");
require_once("controler/UtilityControler.php");
require_once("controler/CompetencesControler.php");
require_once("../stats/patients/cab/logiciel_getname.php");

require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;

$path = 'https://'.$_SERVER['HTTP_HOST'];
#echo $path;
$conn = new ConnectionFactory();
$conn->getConnection();
session_start();
#var_dump($_POST);


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


if($_POST){
	CompetencesControler::updateCompetences($userCurrent);
	$update = true;
}

$uc = CompetencesInfirmieres::getCompetencesByLogin($userCurrent);

#var_dump($uc);
?>





<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Compétences des infirmières</title>
<meta name="robots" content="noindex,nofollow">
<link href="<?php echo $path;?>/view/login/css/psp5.css" rel="stylesheet" type="text/css">
<link href="<?php echo $path;?>/view/login/css/psp_nicetitle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
</head>

<?php
if($_SERVER['HTTP_HOST'] == 'psatest.asalee.fr') {
	$bodycolor = 'style="background-color:green"';
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
		
		<h3 align="left" style="height:30px;"><a href="competences.php"><< Retour aux compétences et activités transverses Asalée</a></h3>
		
		<h2>Mise à jour de mes compétences et activités transverses Asalée<br>&nbsp;</h2>
		
		
		<div style="text-align:left;margin-top:10px;">
			
			<?php if($update){
				echo '<center><h3 style="color:green;margin-bottom:20px;">Merci vos informations ont bien été enregistrées !</h3></center>';
			}
			?>
			<form action='#' method="POST">
			<input type="hidden" name="email" value="<?php echo $_SESSION['id.email'];?>">
			<div id="infos" style="text-align:left;margin-bottom:20px;">

				<p>Informations : <?php echo $_SESSION['id.prenom'].' '.$_SESSION['id.nom'];?> - <?php echo $_SESSION['id.email'];?> - <?php echo $_SESSION['id.telephone'];?></p>

				<p>Dernière mise à jour : <?php echo UtilityControler::inverseDate($uc['datemaj'],'fr');?>

			</div>

			<div id="protocoles" style="float:left;width:45%;">
				<b>Protocoles :</b> <br>
				<input type="checkbox" value="1" name="diabete" <?php if($uc['diabete']=="1"){echo ' checked="checked"';} ?>> Diabète<br />
				<input type="checkbox" value="1" name="rcva" <?php if($uc['rcva']=="1"){echo ' checked="checked"';} ?>> RCV<br />
				<input type="checkbox" value="1" name="bpco_spiro" <?php if($uc['bpco_spiro']=="1"){echo ' checked="checked"';} ?>> BPCO/Spirométrie<br />
				<input type="checkbox" value="1" name="cognitif" <?php if($uc['cognitif']=="1"){echo ' checked="checked"';} ?>> Troubles cognitifs<br />
				<input type="checkbox" value="1" name="cancer" <?php if($uc['cancer']=="1"){echo ' checked="checked"';} ?>> Dépistage cancer<br />
				<br/>
			</div>

			<div id="actes" style="float:left;width:45%;margin-bottom:10px;">
				<b>Actes dérogatoires :</b> <br>
				<input type="checkbox" value="1" name="pied_diabetique" <?php if($uc['pied_diabetique']=="1"){echo ' checked="checked"';} ?>> Examen du pied diabétique<br />
				<input type="checkbox" value="1" name="mms" <?php if($uc['mms']=="1"){echo ' checked="checked"';} ?>> Réalisation et interprétation du MMS<br />
				<input type="checkbox" value="1" name="rea_spiro" <?php if($uc['rea_spiro']=="1"){echo ' checked="checked"';} ?>> Réalisation de la spirométrie<br />
				
			</div>
			
			<div style="clear:left;"><hr></div>

			<div id="domaine" style="float:left;width:45%;margin-bottom:10px;">
				<b>Domaine médical :</b> <br>
				<input type="checkbox" value="1" name="nutrition" <?php if($uc['nutrition']=="1"){echo ' checked="checked"';} ?>> Nutrition<br />
				<input type="checkbox" value="1" name="act_physique" <?php if($uc['act_physique']=="1"){echo ' checked="checked"';} ?>> Activité physique<br />
				<input type="checkbox" value="1" name="vigilance2" <?php if($uc['vigilance2']=="1"){echo ' checked="checked"';} ?>> Vigilance 2 (télésurveillance)<br />
				<input type="checkbox" value="1" name="obesite" <?php if($uc['obesite']=="1"){echo ' checked="checked"';} ?>> Obésité<br />
				<input type="checkbox" value="1" name="apnee_sommeil" <?php if($uc['apnee_sommeil']=="1"){echo ' checked="checked"';} ?>> Apnée du sommeil<br />
				<input type="checkbox" value="1" name="tabac_addict" <?php if($uc['tabac_addict']=="1"){echo ' checked="checked"';} ?>> Tabacologie, Addictologie<br />
				<input type="checkbox" value="1" name="coord_geronto" <?php if($uc['coord_geronto']=="1"){echo ' checked="checked"';} ?>> Coordination - évaluation gérontologique à domicile<br />
                <input type="checkbox" value="1" name="retinographie" <?php if($uc['retinographie']=="1"){echo ' checked="checked"';} ?>> Rétinographie<br />
				<div style="margin-top:5px;margin-bottom:15px;">Autre domaine médical : <br>
				<textarea name="autre_domaine" style="width:90%;height:50px"><?php echo stripslashes(utf8_encode($uc['autre_domaine']));?></textarea></div>

			</div>

			<div id="etp" style="float:left;width:45%;margin-bottom:10px;">
				<b>ETP :</b> <br>
				<input type="checkbox" value="1" name="evaluer_pps" <?php if($uc['evaluer_pps']=="1"){echo ' checked="checked"';} ?>> Négocier-construire-évaluer le PPS avec le patient<br />
				<input type="checkbox" value="1" name="anim_etp_collec" <?php if($uc['anim_etp_collec']=="1"){echo ' checked="checked"';} ?>> Animation de séances ETP collectives<br />
				<input type="checkbox" value="1" name="programme_etp_collec" <?php if($uc['programme_etp_collec']=="1"){echo ' checked="checked"';} ?>> Elaboration de programmes ETP collectifs<br />
				<input type="checkbox" value="1" name="formation_etp" <?php if($uc['formation_etp']=="1"){echo ' checked="checked"';} ?>> Animation de formations ETP auprès de soignants<br />
				<input type="checkbox" value="1" name="amelioration_formation_etp" <?php if($uc['amelioration_formation_etp']=="1"){echo ' checked="checked"';} ?>> Elaboration/amélioration de formations ETP auprès des soignants<br/>
				<input type="checkbox" value="1" name="entretien_etp" <?php if($uc['entretien_etp']=="1"){echo ' checked="checked"';} ?>> Entretien motivationnel<br/>
			</div>

			<div style="clear:left;"><hr></div>

			<!-- <div id="contrib" style="float:left;width:45%;margin-bottom:10px;">
				<b>Contribution Asalée :</b> <br>
				<input type="checkbox" value="1" name="coord_reu_secteur" <?php if($uc['coord_reu_secteur']=="1"){echo ' checked="checked"';} ?>> Coordination des réunions de secteur<br />
				<input type="checkbox" value="1" name="orga_reu_secteur" <?php if($uc['orga_reu_secteur']=="1"){echo ' checked="checked"';} ?>> Organisation/animation de réunion de secteur<br />
				<input type="checkbox" value="1" name="coord_compagnonnage" <?php if($uc['coord_compagnonnage']=="1"){echo ' checked="checked"';} ?>> Coordination des compagnonnages<br />
				<input type="checkbox" value="1" name="rea_compagnonnage" <?php if($uc['rea_compagnonnage']=="1"){echo ' checked="checked"';} ?>> Réalisation de compagnonnages<br />
				<input type="checkbox" value="1" name="recrutement" <?php if($uc['recrutement']=="1"){echo ' checked="checked"';} ?>> Recrutement<br />
				<input type="checkbox" value="1" name="elaboration_analyse_pratiques" <?php if($uc['elaboration_analyse_pratiques']=="1"){echo ' checked="checked"';} ?>> Elaboration analyse des pratiques<br />
				<input type="checkbox" value="1" name="animation_analyse_pratiques" <?php if($uc['animation_analyse_pratiques']=="1"){echo ' checked="checked"';} ?>> Animation analyse des pratiques<br />
				<input type="checkbox" value="1" name="aide_installation" <?php if($uc['aide_installation']=="1"){echo ' checked="checked"';} ?>> Aide à l'installation<br />
				<input type="checkbox" value="1" name="support_exercice_mixte" <?php if($uc['support_exercice_mixte']=="1"){echo ' checked="checked"';} ?>> Support exercice mixte Asalée/libéral<br />
			</div> -->
			<div id="activite_transverses" style="float:left;width:45%;margin-bottom:10px;">
				<b>Activités transverses Asalée :</b> <br>
				<input type="checkbox" value="1" name="transverses_compagnonnage" <?php if($uc['transverses_compagnonnage']=="1"){echo ' checked="checked"';} ?>> Référents "Compagnonnage"<br />
			    <input type="checkbox" value="1" name="transverses_reunion" <?php if($uc['transverses_reunion']=="1"){echo ' checked="checked"';} ?>> Référents  "Réunion sectorielle"<br />
			    <input type="checkbox" value="1" name="transverses_contact" <?php if($uc['transverses_contact']=="1"){echo ' checked="checked"';} ?>> Référents  "Contacts/Candidatures spontanées"<br />
			    <input type="checkbox" value="1" name="transverses_sevrage_tabac" <?php if($uc['transverses_sevrage_tabac']=="1"){echo ' checked="checked"';} ?>> Référents  "Sevrage tabagique"<br />
			    <input type="checkbox" value="1" name="transverses_apa" <?php if($uc['transverses_apa']=="1"){echo ' checked="checked"';} ?>> Référents "Activité Physique Adaptée (APA)"<br />
			</div>

			<div id="outils" style="float:left;width:45%;margin-bottom:10px;">
				<b>Outils informatiques :</b> <br>
				<input type="checkbox" value="1" name="utilisation_portail_psa" <?php if($uc['utilisation_portail_psa']=="1"){echo ' checked="checked"';} ?>> Utilisation du portail PSA<br />
				<input type="checkbox" value="1" name="integration_donnees" <?php if($uc['integration_donnees']=="1"){echo ' checked="checked"';} ?>> Intégration des données<br />
				<input type="checkbox" value="1" name="informatique" <?php if($uc['informatique']=="1"){echo ' checked="checked"';} ?>> Informatique (certificats, identifiants, adresses email...)<br />
				<input type="checkbox" value="1" name="bureautique" <?php if($uc['bureautique']=="1"){echo ' checked="checked"';} ?>> Bureautique (Excel, Word, PowerPoint)<br />
				<input type="checkbox" value="1" name="communication" <?php if($uc['communication']=="1"){echo ' checked="checked"';} ?>> Communication (Skype, TeamMeeting)<br />
			</div>

			<div style="clear:left;"><hr></div>

			<div id="logiciels" style="float:left;width:45%;margin-bottom:10px;">
				<b>Logiciels :</b> <br>
				<input type="checkbox" value="1" name="almapro" <?php if($uc['almapro']=="1"){echo ' checked="checked"';} ?>> Almapro<br />
			    <input type="checkbox" value="1" name="axisante4" <?php if($uc['axisante4']=="1"){echo ' checked="checked"';} ?>> Axisanté 4<br />
			    <input type="checkbox" value="1" name="axisante5" <?php if($uc['axisante5']=="1"){echo ' checked="checked"';} ?>> Axisanté 5<br />
			    <input type="checkbox" value="1" name="clinidoc" <?php if($uc['clinidoc']=="1"){echo ' checked="checked"';} ?>> Clinidoc<br />
			    <input type="checkbox" value="1" name="crossway" <?php if($uc['crossway']=="1"){echo ' checked="checked"';} ?>> Crossway<br />
			    <input type="checkbox" value="1" name="dbmed" <?php if($uc['dbmed']=="1"){echo ' checked="checked"';} ?>> DBmed<br />
			    <input type="checkbox" value="1" name="docware" <?php if($uc['docware']=="1"){echo ' checked="checked"';} ?>> Docware<br />
			    <input type="checkbox" value="1" name="easyprat" <?php if($uc['easyprat']=="1"){echo ' checked="checked"';} ?>> Easyprat<br />
			    <input type="checkbox" value="1" name="eomed" <?php if($uc['eomed']=="1"){echo ' checked="checked"';} ?>> Eomed<br />
			    <input type="checkbox" value="1" name="hellodoc" <?php if($uc['hellodoc']=="1"){echo ' checked="checked"';} ?>> Hellodoc<br />
			    <input type="checkbox" value="1" name="hellodoc5_55" <?php if($uc['hellodoc5_55']=="1"){echo ' checked="checked"';} ?>> Hellodoc v5.55<br />
			    <input type="checkbox" value="1" name="hellodoc5_6" <?php if($uc['hellodoc5_6']=="1"){echo ' checked="checked"';} ?>> Hellodoc v5.6<br />
			   </div>
			<div id="logiciels2" style="float:left;width:45%;margin-bottom:10px;">
			    <input type="checkbox" value="1" name="hypermed" <?php if($uc['hypermed']=="1"){echo ' checked="checked"';} ?>> Hypermed<br />
			    <input type="checkbox" value="1" name="ict" <?php if($uc['ict']=="1"){echo ' checked="checked"';} ?>> ICT<br />
			    <input type="checkbox" value="1" name="maldis" <?php if($uc['maldis']=="1"){echo ' checked="checked"';} ?>> Maldis<br />
			    <input type="checkbox" value="1" name="medi" <?php if($uc['medi']=="1"){echo ' checked="checked"';} ?>> Medi + 4000<br />
			    <input type="checkbox" value="1" name="medicawin" <?php if($uc['medicawin']=="1"){echo ' checked="checked"';} ?>> Médicawin<br />
			    <input type="checkbox" value="1" name="mediclick" <?php if($uc['mediclick']=="1"){echo ' checked="checked"';} ?>> Mediclick<br />
			    <input type="checkbox" value="1" name="mediclick5" <?php if($uc['mediclick5']=="1"){echo ' checked="checked"';} ?>> Mediclick 5<br />
			    <input type="checkbox" value="1" name="medimust" <?php if($uc['medimust']=="1"){echo ' checked="checked"';} ?>> Medimust<br />
			    <input type="checkbox" value="1" name="medistory" <?php if($uc['medistory']=="1"){echo ' checked="checked"';} ?>> Medistory<br />
			    <input type="checkbox" value="1" name="mediwin" <?php if($uc['mediwin']=="1"){echo ' checked="checked"';} ?>> MediWin<br />
			    <input type="checkbox" value="1" name="mlm" <?php if($uc['mlm']=="1"){echo ' checked="checked"';} ?>> MLM<br />
				<input type="checkbox" value="1" name="shaman" <?php if($uc['shaman']=="1"){echo ' checked="checked"';} ?>> Shaman<br />
			    <input type="checkbox" value="1" name="weda" <?php if($uc['weda']=="1"){echo ' checked="checked"';} ?>> Weda<br />
			    <input type="checkbox" value="1" name="xmed" <?php if($uc['xmed']=="1"){echo ' checked="checked"';} ?>> XMed<br />
			    
			    <div style="margin-top:5px;margin-bottom:15px;">Autre logiciel : <br>
				<textarea name="autre_logiciel" style="width:90%;height:50px"><?php echo stripslashes(utf8_encode($uc['autre_logiciel']));?></textarea></div>


			</div>
			
			<div style="clear:left;text-align:center">&nbsp;</div>
			<div style="text-align:center">
				<button type="submit" class="bouton7">Enregistrer</button>
			</div>

		</form>
		</div>
		<div style="clear:left">&nbsp;</div>
		<div style="clear:left">&nbsp;</div>
		
	
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script>

<?php 
if(!$_POST){
	echo "$('#rech').hide();";
}
?>


//$('#rech').hide();

$('#affrech').click(function(){
	$('#rech').toggle('slow');
});
</script>
