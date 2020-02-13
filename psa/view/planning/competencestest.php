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

if($_POST){

	$filtre = CompetencesControler::getFiltreFromPost();
	echo $filtre;
}

$liste = CompetencesInfirmieres::getAllCompetences($filtre);


$userCurrent = $_SESSION['id.login'];
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

		<h2>Compétences infirmiers/infirmières<br>&nbsp;</h2>
		<h3 align="left" style="height:30px;"><a href="planning.php"><< Retour au planning</a></h3>
		
		<div style="text-align:left;">
			<button class="bouton7" id="affrech" style="text-align:left;cursor:pointer;text-align:center">Fitrer par compétences</button>
		</div>
		<div id="rech" style="text-align:left;margin-top:10px;">
			<form action='#' method="POST">
			
			<p>NB : Les critères de sélection se cumulent.</p>
			

			<div id="protocoles" style="float:left;width:270px;">
				<b>Protocoles :</b> <br>
				<input type="checkbox" name="crit[]" value="diabete" <?php if(in_array('diabete',$_POST['crit'])){echo ' checked="checked"';} ?>> Diabète<br />
				<input type="checkbox" name="crit[]" value="rcva" <?php if(in_array('rcva',$_POST['crit'])){echo ' checked="checked"';} ?>> RCV<br />
				<input type="checkbox" name="crit[]" value="bpco_spiro" <?php if(in_array('bpco_spiro',$_POST['crit'])){echo ' checked="checked"';} ?>> BPCO/Spirométrie<br />
				<input type="checkbox" name="crit[]" value="cognitif" <?php if(in_array('cognitif',$_POST['crit'])){echo ' checked="checked"';} ?>> Troubles cognitifs<br />
				<input type="checkbox" name="crit[]" value="cancer" <?php if(in_array('cancer',$_POST['crit'])){echo ' checked="checked"';} ?>> Dépistage cancer<br />
				<br/>
			</div>

			<div id="actes" style="float:left;width:270px;margin-bottom:10px;">
				<b>Actes dérogatoires :</b> <br>
				<input type="checkbox" name="crit[]" value="pied_diabetique" <?php if(in_array('pied_diabete',$_POST['crit'])){echo ' checked="checked"';} ?>> Examen du pied diabétique<br />
				<input type="checkbox" name="crit[]" value="mms" <?php if(in_array('mms',$_POST['crit'])){echo ' checked="checked"';} ?>> Réalisation et interprétation du MMS<br />
				<input type="checkbox" name="crit[]" value="rea_spiro" <?php if(in_array('rea_spiro',$_POST['crit'])){echo ' checked="checked"';} ?>> Réalisation de la spirométrie<br />
				
			</div>
				
			<div id="domaine" style="float:left;width:270px;margin-bottom:10px;">
				<b>Domaine médical :</b> <br>
				<input type="checkbox" name="crit[]" value="nutrition" <?php if(in_array('nutrition',$_POST['crit'])){echo ' checked="checked"';} ?>> Nutrition<br />
				<input type="checkbox" name="crit[]" value="act_physique" <?php if(in_array('act_physique',$_POST['crit'])){echo ' checked="checked"';} ?>> Activité physique<br />
				<input type="checkbox" name="crit[]" value="vigilance2" <?php if(in_array('vigilance2',$_POST['crit'])){echo ' checked="checked"';} ?>> Vigilance 2 (télésurveillance)<br />
				<input type="checkbox" name="crit[]" value="obesite" <?php if(in_array('obesite',$_POST['crit'])){echo ' checked="checked"';} ?>> Obésité<br />
				<input type="checkbox" name="crit[]" value="apnees_sommeil" <?php if(in_array('apnees_sommeil',$_POST['crit'])){echo ' checked="checked"';} ?>> Apnée du sommeil<br />
				<input type="checkbox" name="crit[]" value="tabac_addict" <?php if(in_array('tabac_addict',$_POST['crit'])){echo ' checked="checked"';} ?>> Tabactologie, Addictologie<br />
				<input type="checkbox" name="crit[]" value="coord_geronto" <?php if(in_array('coord_geronto',$_POST['crit'])){echo ' checked="checked"';} ?>> Coordination - évaluation gérontologique à domicile<br />
			</div>

			<div id="etp" style="float:left;width:270px;margin-bottom:10px;">
				<b>ETP :</b> <br>
				<input type="checkbox" name="crit[]" value="evaluer_pps" <?php if(in_array('evaluer_pps',$_POST['crit'])){echo ' checked="checked"';} ?>> Négocier-construire-évaluer le PPS avec le patient<br />
				<input type="checkbox" name="crit[]" value="anim_etp_collec" <?php if(in_array('anim_etp_collec',$_POST['crit'])){echo ' checked="checked"';} ?>> Animation de séances ETP collectives<br />
				<input type="checkbox" name="crit[]" value="programme_etp_collec" <?php if(in_array('programme_etp_collec',$_POST['crit'])){echo ' checked="checked"';} ?>> Elaboration de programmes ETP collectifs<br />
				<input type="checkbox" name="crit[]" value="formation_etp" <?php if(in_array('formation_etp',$_POST['crit'])){echo ' checked="checked"';} ?>> Animation de formations ETP auprès de soignants<br />
				<input type="checkbox" name="crit[]" value="amelioration_formation_etp" <?php if(in_array('amelioration_formation_etp',$_POST['crit'])){echo ' checked="checked"';} ?>> Elaboration/amélioration de formations ETP auprès des soignants<br/>
				<input type="checkbox" name="crit[]" value="entretien_etp" <?php if(in_array('entretien_etp',$_POST['crit'])){echo ' checked="checked"';} ?>> Entretien motivationnel<br/>
			</div>

			<div id="contrib" style="float:left;width:270px;margin-bottom:10px;">
				<b>Contribution Asalée :</b> <br>
				<input type="checkbox" name="crit[]" value="coord_reu_secteur" <?php if(in_array('coord_reu_secteur',$_POST['crit'])){echo ' checked="checked"';} ?>> Coordination des réunions de secteur<br />
				<input type="checkbox" name="crit[]" value="orga_reu_secteur" <?php if(in_array('orga_reu_secteur',$_POST['crit'])){echo ' checked="checked"';} ?>> Organisation/animation de réunion de secteur<br />
				<input type="checkbox" name="crit[]" value="coord_compagnonnage" <?php if(in_array('coord_compagnonnage',$_POST['crit'])){echo ' checked="checked"';} ?>> Coordination des compagnonnages<br />
				<input type="checkbox" name="crit[]" value="rea_compagnonnage" <?php if(in_array('rea_compagnonnage',$_POST['crit'])){echo ' checked="checked"';} ?>> Réalisation de compagnonnages<br />
				<input type="checkbox" name="crit[]" value="recrutement" <?php if(in_array('recrutement',$_POST['crit'])){echo ' checked="checked"';} ?>> Recrutement<br />
				<input type="checkbox" name="crit[]" value="elaboration_analyse_pratiques" <?php if(in_array('elaboration_analyse_pratiques',$_POST['crit'])){echo ' checked="checked"';} ?>> Elaboration analyse des pratiques<br />
				<input type="checkbox" name="crit[]" value="animation_analyse_pratiques" <?php if(in_array('animation_analyse_pratiques',$_POST['crit'])){echo ' checked="checked"';} ?>> Animation analyse des pratiques<br />
				<input type="checkbox" name="crit[]" value="aide_installation" <?php if(in_array('aide_installation',$_POST['crit'])){echo ' checked="checked"';} ?>> Aide à l'installation<br />
				<input type="checkbox" name="crit[]" value="support_exercice_mixte" <?php if(in_array('support_exercice_mixte',$_POST['crit'])){echo ' checked="checked"';} ?>> Support exercice mixte Asalée/libéral<br />
			</div>

			<div id="outils" style="float:left;width:270px;margin-bottom:10px;">
				<b>Outils informatiques :</b> <br>
				<input type="checkbox" name="crit[]" value="utilisation_portail_psa" <?php if(in_array('utilisation_portail_psa',$_POST['crit'])){echo ' checked="checked"';} ?>> Utilisation du portail PSA<br />
				<input type="checkbox" name="crit[]" value="integration_donnees" <?php if(in_array('integration_donnees',$_POST['crit'])){echo ' checked="checked"';} ?>> Intégration des données<br />
				<input type="checkbox" name="crit[]" value="informatique" <?php if(in_array('informatique',$_POST['crit'])){echo ' checked="checked"';} ?>> Informatique (certificats, identifiants, adresses email...)<br />
				<input type="checkbox" name="crit[]" value="bureautique" <?php if(in_array('bureautique',$_POST['crit'])){echo ' checked="checked"';} ?>> Bureautique (Excel, Word, PowerPoint)<br />
				<input type="checkbox" name="crit[]" value="communication" <?php if(in_array('communication',$_POST['crit'])){echo ' checked="checked"';} ?>> Communication (Skype, TeamMeeting)<br />
			</div>

			<div id="logiciels" style="float:left;width:270px;margin-bottom:10px;">
				<b>Logiciels :</b> <br>
				<input type="checkbox" name="crit[]" value="almapro" <?php if(in_array('almapro',$_POST['crit'])){echo ' checked="checked"';} ?>> Almapro<br />
			    <input type="checkbox" name="crit[]" value="axisante4" <?php if(in_array('axisante4',$_POST['crit'])){echo ' checked="checked"';} ?>> Axisanté 4<br />
			    <input type="checkbox" name="crit[]" value="axisante5" <?php if(in_array('axisante5',$_POST['crit'])){echo ' checked="checked"';} ?>> Axisanté 5<br />
			    <input type="checkbox" name="crit[]" value="clinidoc" <?php if(in_array('clinidoc',$_POST['crit'])){echo ' checked="checked"';} ?>> Clinidoc<br />
			    <input type="checkbox" name="crit[]" value="crossway" <?php if(in_array('crossway',$_POST['crit'])){echo ' checked="checked"';} ?>> Crossway<br />
			    <input type="checkbox" name="crit[]" value="dbmed" <?php if(in_array('dbmed',$_POST['crit'])){echo ' checked="checked"';} ?>> DBmed<br />
			    <input type="checkbox" name="crit[]" value="docware" <?php if(in_array('docware',$_POST['crit'])){echo ' checked="checked"';} ?>> Docware<br />
			    <input type="checkbox" name="crit[]" value="easyprat" <?php if(in_array('easyprat',$_POST['crit'])){echo ' checked="checked"';} ?>> Easyprat<br />
			    <input type="checkbox" name="crit[]" value="eomed" <?php if(in_array('eomed',$_POST['crit'])){echo ' checked="checked"';} ?>> Eomed<br />
			    <input type="checkbox" name="crit[]" value="hellodoc" <?php if(in_array('hellodoc',$_POST['crit'])){echo ' checked="checked"';} ?>> Hellodoc<br />
			    <input type="checkbox" name="crit[]" value="hellodoc5_55" <?php if(in_array('hellodoc5_55',$_POST['crit'])){echo ' checked="checked"';} ?>> Hellodoc v5.55<br />
			    <input type="checkbox" name="crit[]" value="hellodoc5_6" <?php if(in_array('hellodoc5_6',$_POST['crit'])){echo ' checked="checked"';} ?>> Hellodoc v5.6<br />
			   </div>
			<div id="logiciels2" style="float:left;width:270px;margin-bottom:10px;">
			    <input type="checkbox" name="crit[]" value="hypermed" <?php if(in_array('hypermed',$_POST['crit'])){echo ' checked="checked"';} ?>> Hypermed<br />
			    <input type="checkbox" name="crit[]" value="ict" <?php if(in_array('ict',$_POST['crit'])){echo ' checked="checked"';} ?>> ICT<br />
			    <input type="checkbox" name="crit[]" value="maldis" <?php if(in_array('maldis',$_POST['crit'])){echo ' checked="checked"';} ?>> Maldis<br />
			    <input type="checkbox" name="crit[]" value="medi" <?php if(in_array('medi',$_POST['crit'])){echo ' checked="checked"';} ?>> Medi + 4000<br />
			    <input type="checkbox" name="crit[]" value="medicawin" <?php if(in_array('medicawin',$_POST['crit'])){echo ' checked="checked"';} ?>> Médicawin<br />
			    <input type="checkbox" name="crit[]" value="mediclick" <?php if(in_array('mediclick',$_POST['crit'])){echo ' checked="checked"';} ?>> Mediclick<br />
			    <input type="checkbox" name="crit[]" value="mediclick5" <?php if(in_array('mediclick5',$_POST['crit'])){echo ' checked="checked"';} ?>> Mediclick 5<br />
			    <input type="checkbox" name="crit[]" value="medimust" <?php if(in_array('medimust',$_POST['crit'])){echo ' checked="checked"';} ?>> Medimust<br />
			    <input type="checkbox" name="crit[]" value="medistory" <?php if(in_array('medistory',$_POST['crit'])){echo ' checked="checked"';} ?>> Medistory<br />
			    <input type="checkbox" name="crit[]" value="mediwin" <?php if(in_array('mediwin',$_POST['crit'])){echo ' checked="checked"';} ?>> MediWin<br />
			    <input type="checkbox" name="crit[]" value="shaman" <?php if(in_array('shaman',$_POST['crit'])){echo ' checked="checked"';} ?>> Shaman<br />
			    <input type="checkbox" name="crit[]" value="weda" <?php if(in_array('weda',$_POST['crit'])){echo ' checked="checked"';} ?>> Weda<br />
			    <input type="checkbox" name="crit[]" value="xmed" <?php if(in_array('xmed',$_POST['crit'])){echo ' checked="checked"';} ?>> XMed<br />
			</div>
			<div style="clear:left;text-align:center">&nbsp;</div>
			<input type="submit" value="Rechercher">

		</form>
		</div>
		<div style="clear:left">&nbsp;</div>
		
		<h2><?php echo count($liste);?> Résultats</h2>
		<table width="98%" border="1" align="center" cellspacing="0">
			<tr>
					
				<td align="center">
					<b>Infirmièr(e)</b>
				</td>
				<td>
					<b>Protocoles</b></td>
				<td>
					<b>Actes dérogatoires</b></td>
				<td>
					<b>Domaine médical</b></td>
				<td>
					<b>ETP</b></td>
				<td>
					<b>Contribution Asalée</b></td>
				<td>
					<b>Outils informatiques</b></td>
				<td>
					<b>Logiciels médicaux</b></td>
				</tr>

				<?php 
				
					foreach($liste as $comp){ 
					
					$infirmiere = GetInfosByLogin($comp['login'], $status);
					$inf = current($infirmiere);
					$cabs = GetCabsByLogin($comp['login'], $status);

					$cabinets = '';
					foreach($cabs as $cab){
						#echo $cab;
						$cabinet = AccountMapper::getFullInfosByCab($cab);
						#var_dump($cabinet);exit;
						$cabinets .= utf8_encode($cabinet['ville'])	.', ';
					}

					$cabinets = substr($cabinets,0,-2);
					if($userCurrent=='hplozner'){
						#echo var_dump($cabs);exit;
					}
					
					$col1 = CompetencesControler::formatColonne(array("diabete"=>$comp['diabete'],"rcva"=>$comp['rcva'],"bpco_spiro"=>$comp['bpco_spiro'],"cognitif"=>$comp['cognitif'],"cancer"=>$comp['cancer']));
					$col2 = CompetencesControler::formatColonne(array("pied_diabetique"=>$comp['pied_diabetique'],"mms"=>$comp['mms'],"rea_spiro"=>$comp['rea_spiro']));
					$col3 = CompetencesControler::formatColonne(array("nutrition"=>$comp['nutrition'],"act_physique"=>$comp['act_physique'],"vigilance2"=>$comp['vigilance2'],"obesite"=>$comp['obesite'],"apnee_sommeil"=>$comp['apnee_sommeil'],"tabac_addict"=>$comp['tabac_addict'],"coord_geronto"=>$comp['coord_geronto']));
					if(trim($comp['autre_domaine'])!='0'){
						$col3_autre = '<br>- Autres domaines :'.utf8_encode($comp['autre_domaine']); 
					}else{$col3_autre = '';}
					$col4 = CompetencesControler::formatColonne(array("evaluer_pps"=>$comp['evaluer_pps'],"anim_etp_collec"=>$comp['anim_etp_collec'],"programme_etp_collec"=>$comp['programme_etp_collec'],"formation_etp"=>$comp['formation_etp'],"amelioration_formation_etp"=>$comp['amelioration_formation_etp'],"entretien_etp"=>$comp['entretien_etp']));
					$col5 = CompetencesControler::formatColonne(array("coord_reu_secteur"=>$comp['coord_reu_secteur'],"orga_reu_secteur"=>$comp['orga_reu_secteur'],"coord_compagnonnage"=>$comp['coord_compagnonnage'],"rea_compagnonnage"=>$comp['rea_compagnonnage'],"recrutement"=>$comp['recrutement'],"elaboration_analyse_pratiques"=>$comp['elaboration_analyse_pratiques'],"animation_analyse_pratiques"=>$comp['animation_analyse_pratiques'],"aide_installation"=>$comp['aide_installation'],"suport_exercice_mixte"=>$comp['suport_exercice_mixte']));
					$col6 = CompetencesControler::formatColonne(array("utilisation_portail_psa"=>$comp['utilisation_portail_psa'],"integration_données"=>$comp['integration_données'],"informatique"=>$comp['informatique'],"bureautique"=>$comp['bureautique'],"communication"=>$comp['communication']));
					$col7 = CompetencesControler::formatColonne(array("amalpro"=>$comp['amalpro'],"axisante4"=>$comp['axisante4'],"axisante5"=>$comp['axisante5'],"clinidoc"=>$comp['clinidoc'],"crossway"=>$comp['crossway'],"dbmed"=>$comp['dbmed'],"docware"=>$comp['docware'],"easyprat"=>$comp['easyprat'],"eomed"=>$comp['eomed'],"hellodoc"=>$comp['hellodoc'],"hellodoc5_55"=>$comp['hellodoc5_55'],"hellodoc5_6"=>$comp['hellodoc5_6'],"hypermed"=>$comp['hypermed'],"ict"=>$comp['ict'],"maldis"=>$comp['maldis'],"medi"=>$comp['medi'],"medicawin"=>$comp['medicawin'],"mediclick"=>$comp['mediclick'],"mediclick5"=>$comp['mediclick5'],"medimust"=>$comp['medimust'],"medistory"=>$comp['medistory'],"mediwin"=>$comp['mediwin'],"shaman"=>$comp['shaman'],"weda"=>$comp['weda'],"xmed"=>$comp['xmed']));
					if(trim($comp['autre_logiciel'])!='0'){
						$col7_autre = '<br>- '.utf8_encode($comp['autre_logiciel']); 
					}
					else{
						$col7_autre='';
					}
					#var_dump($comp);
					?>
					<tr>
						
						<td align="center">
							<?php echo $inf['prenom'].' '.$inf['nom'];?><br /><?php echo $inf['telephone'];?>
							<br /><a href="mailto:<?php echo $comp['email'];?>"><?php echo $comp['email'];?></a><br /><?php echo $cabinets;?></td>
						<td>
							<?php echo $col1;?></td>
						<td>
							<?php echo $col2;?></td>
						<td>
							<?php echo $col3.$col3_autre;?></td>
						<td>
							<?php echo $col4;?></td>
						<td>
							<?php echo $col5;?></td>
						<td>
							<?php echo $col6;?></td>
						<td>
							<?php echo $col7.$col7_autre;?></td>
					</tr>
				
				<?php #exit;
			}  ?>

				
			<tr style="border:0">
				<td  style="border:0;height:40px" colspan="8" align="center">&nbsp;</td>
			</tr>

			
		</table>





		
			


	
		</table>
	
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
