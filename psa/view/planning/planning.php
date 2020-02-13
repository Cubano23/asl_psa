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

#echo $_SERVER['DOCUMENT_ROOT'].'/rest/GetCabsAndLogins.php';
#exit;
// recupération des infos de l'infirmère current et de celles du cabinet
require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;

$infirmieresCurrent = GetLoginsByCab($_SESSION['cabinet'], $status);
  
	foreach($infirmieresCurrent as  $key => $inf){
    if($inf['login'] == $userCurrent){
    	$infirmieresCurrent[$key]['current'] = 1 ;
    }
    else{
    	$infirmieresCurrent[$key]['current'] = 0 ;
    }
    
  }

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



#$myPlanning = PlanningInfirmieres::getRecordsByInfirmiere($_SESSION['id.login']);

$mesCabinets = GetCabsByLogin($userCurrent, $status);

#var_dump($mesCabinets);
#var_dump($_SESSION['account']);
/**
 * affiche la ligne entete de colonne qui est affichée plusieurs fois dans la page
 */
function HTML_entete_colonne($first,$action){

	$html='<tr>';

	if($first=='1'){
		$html .='<td align="center" width="16%"><b>Nom</b></td>';
	}
	$html .='			
		<td align="center" width="12%"><b>Cabinet<br><i>(logiciel)</i></b></td>
		<td align="center" width="12%"><b>Lundi</b></td>
		<td align="center" width="12%"><b>Mardi</b></td>
		<td align="center" width="12%"><b>Mercredi</b></td>
		<td align="center" width="12%"><b>Jeudi</b></td>
		<td align="center" width="12%"><b>Vendredi</b></td>
		<td align="center" width="12%"><b>Samedi</b></td>';
	if($action=='1'){
		$html .='<td align="center" width="16%"><b>Action</b></td>';
	}

	$html .='</tr>';
	return $html;
}

$infosCab = AccountMapper::getFullInfosByCab($_SESSION['cabinet']);
#var_dump($infosCab);

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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
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

		<!--<p style="text-align:right"><a href="planning_search.php"><span class="fa fa-search fa-2x" >Rechercher </a></p>
	-->
		<h3><a href="planning_search.php">Rechercher dans le planning</a> | <a href="competences.php">Rechercher dans les compétences et activités transverses Asalée</a>

		<br><br>
		
		<h2 style="text-align:center;margin-bottom:15px">Planning de : <?php echo $_SESSION['id.prenom']. ' '.$_SESSION['id.nom']; ?> (<?php echo $userCurrent;?>) - Connecté(e) sur <i><?php echo $infosCab['nom_cab'];?></i></h2>

	
		<table width="98%" border="1" align="center" cellspacing="0">
			
				<?php echo HTML_entete_colonne('0','1');?>

				<?php
				// planning courant du cabinet logué
				$cp = PlanningInfirmieres::getPlanningByInfirmiereAndCab($userCurrent,strtolower($_SESSION['cabinet']));
				#var_dump($cp);
				if($userCurrent=='efrangne'){
					#var_dump($cp);
				}
				
				if(empty($cp['infirmiere'])){
					$nomCab = $infosCab['nom_cab'];
					$codeCab = $infosCab['cabinet'];#echo'1';
				}
				else{
					$nomCab = $cp['infosCab']['nom_cab'];
					$codeCab = $cp['infosCab']['cabinet'];#echo'2';
				}

				?>
			
				<tr>
						
					<td align="center">
						<?php echo $nomCab;?><br>
						<i>(<?php echo utf8_encode(logiciel_getname($cp['infosCab']['logiciel']));?>)</i>
					</td>
					
					<td align="center">
						<?php echo stripslashes($cp['lundi']);?></td>
					<td align="center">
						<?php echo stripslashes($cp['mardi']);?></td>
					<td align="center">
						<?php echo stripslashes($cp['mercredi']);?></td>
					<td align="center">
						<?php echo stripslashes($cp['jeudi']);?></td>
					<td align="center">
						<?php echo stripslashes($cp['vendredi']);?></td>
					<td align="center">
						<?php echo stripslashes($cp['samedi']);?></td>
					<td align="center">
						<a class="button" href="modifier_planning.php?cab=<?php echo $codeCab;?>"><span class="fa fa-pencil-square-o fa-2x"></span></a></td>
					

					</tr>


				<?php 
				
					foreach($mesCabinets as $myCab){ 
						if(strtolower($myCab)!=strtolower($_SESSION['cabinet'])) { 

							$pp = PlanningInfirmieres::getPlanningByInfirmiereAndCab($userCurrent,$myCab);
							
							if($pp){
								$pp['infosCab'] = AccountMapper::getFullInfosByCab($myCab);
							}
							#var_dump($pp);
							?>
					<tr>
						
						<td align="center">
							<?php echo $pp['infosCab']['nom_cab'];?><br><?php echo $pp['infosCab']['ville'];?><br><i>(<?php echo utf8_encode(logiciel_getname($pp['infosCab']['logiciel']));?>)</i></td>
						
						<td align="center">
							<?php echo $pp['lundi'];?></td>
						<td align="center">
							<?php echo $pp['mardi'];?></td>
						<td align="center">
							<?php echo $pp['mercredi'];?></td>
						<td align="center">
							<?php echo $pp['jeudi'];?></td>
						<td align="center">
							<?php echo $pp['vendredi'];?></td>
						<td align="center">
							<?php echo $pp['samedi'];?></td>
						<td align="center">
							<a class="button" href="modifier_planning.php?cab=<?php echo $myCab;?>"><span class="fa fa-pencil-square-o fa-2x"></span></a></td>
					</tr>
				
				<?php }
						} ?>

				
			<tr style="border:0">
				<td  style="border:0;height:40px" colspan="8" align="center">&nbsp;</td>
			</tr>

			<?php
			// liste des infirmières du cabinet (que je peux recup via le WS)
			// pour chaque infirmiere je récupére les infos planning
			if(count($infirmieresCurrent) > 1){
				// il y a plus d'une infirmiere dans la cabinet donc on affiche les autres infirmières
			?>

				<tr style="border:0">
					<td colspan="8" style="border:0" align="center"><h2 style="margin-bottom:10px;">Autres infirmières du cabinet <?php echo $_SESSION['cabinet'];?></h2></td>
				</tr>
				
				<?php echo HTML_entete_colonne('1','0');?>

				<?php
				// liting des autres inf
				foreach($infirmieresCurrent as $inf){
						if($inf['recordstatus'] == 0) {
						if($inf['current']!=1){
							
							// on récupere le planning de l'infirmiere en question
							$plan = PlanningInfirmieres::getPlanningByInfirmiereAndCab($inf['login'],$_SESSION['cabinet']);
					?>
							<tr>
								<td align="center">
									<b><?php echo $inf['prenom'].' '.$inf['nom'];?></b><br>
									<a href="mailto:<?php echo $inf['email'];?>"><?php echo $inf['email'];?></a><br>
									<?php echo $inf['telephone'];?>
								</td>
								<td align="center">&nbsp;</td>
								<td align="center"><?php echo $plan['lundi'];?></td>
								<td align="center"><?php echo $plan['mardi'];?></td>
								<td align="center"><?php echo $plan['mercredi'];?></td>
								<td align="center"><?php echo $plan['jeudi'];?></td>
								<td align="center"><?php echo $plan['vendredi'];?></td>
								<td align="center"><?php echo $plan['samedi'];?></td>
							</tr>
						<?php 
						}
						}
				}
			} ?>
			<tr>
				<td colspan="8" align="center">&nbsp;</td>
			</tr>
			<tr style="border:0">
				<td  style="border:0" colspan="8" align="center">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="8" style="border:0" align="center"><h2 style="margin-bottom:10px;">Dans les autres cabinets</h2><h3><a href="planning_search.php">Rechercher parmi les infirmières</a></h3><br>&nbsp;</td>
			</tr>
			<form method="POST" name="search">
				<?php
				$rech = false;
				if($rech){ ?>
				<tr>
					<td colspan="8" style="border:0;height:40px">
						<b>Filtre : </b> Logiciel 
						<?php $logiciels = AccountMapper::listeDistinctLogiciels();
						#var_dump($logiciels);
						?>
						<select name="logiciel">
							<option value=''>Sélectionner</option>
							<?php foreach($logiciels as $log){
								$log = current($log);
								if($log == $_POST['logiciel']){ $selected = " selected";}else{$selected = '';}
								echo '<option '.$selected.' value="$log">'.utf8_decode($log).'</option>';
							}
							?>
						</select>
						<input type="text" name="search-nom" placeholder="Nom"><input type="text" name="search-prenom" placeholder="Prénom"><input type="submit" value="Rechercher"></td>
				</tr>
				<?php } ?>
			</form>


			<?php 
			// listing des cabinets de la table account et récupération de chacune des infirmères associées
			
			// les cabinets on les prend dans account
			$listeCabinets = AccountMapper::listeAllCabs($filtreLogiciel);


			foreach ($listeCabinets as $cab){

				// récuparation des infirmieres associées au cabinet
				
				if($cab['cabinet']!=$_SESSION['cabinet'] && $cab['cabinet']!='zTest'){
					
					// on chope les infirmières du cabinet en question 
					$cab['infirmieres'] = GetLoginsByCab($cab['cabinet'], $status);
					$infirmieres = array();
					
					foreach($cab['infirmieres'] as $cabInf){

						// si on a filtré la liste, on checke si le nom/prenom correspond à la recherche, si c'est pas le cas on ignore
						// si filtreInf est à true alors on active le filtre d'affichage
					
						
						if(!is_array($infirmiere[$cabInf['login']]['cabinet'])){
						$infirmiere[$cabInf['login']]['cabinet'] = array();
						}
					
						$plan = PlanningInfirmieres::getPlanningByInfirmiereAndCab($cabInf['login'],$cab['cabinet']);
						$lesinfos = array(
							'login' => $cabInf['login'],
							'nom' => $cabInf['nom'],
							'prenom' => $cabInf['prenom'],
							'email' => $cabInf['email'],
							'telephone' => $cabInf['telephone'],
							'cab' => $cab['cabinet'],
							'cabVille' => $cab['ville'],
							'cabinet' => $cab['nom_cab'],
							'logiciel' => $cab['logiciel'],
							'lundi' => $plan['lundi'],
							'mardi' => $plan['mardi'],
							'mercredi' => $plan['mercredi'],
							'jeudi' => $plan['jeudi'],
							'vendredi' => $plan['vendredi'],
							'samedi' => $plan['samedi'],
							'recordstatus' => $cabInf['recordstatus']
						);

						array_push($infirmiere[$cabInf['login']]['cabinet'],$lesinfos);						
						$infirmiere[$cabInf['login']]['lastname'] = $cabInf['nom'];
					}
				}
			}

			// pour chacune infirmière restantes on affiche les donnés de la table planning_infirmieres
			?>
			
			<?php echo HTML_entete_colonne('1','0');?>
			
			<?php 
			$infirmiereASC = UtilityControler::array_msort($infirmiere,array('lastname'=>SORT_ASC));
			foreach ($infirmiereASC as $infirmiere){ 
				$lastname = $infirmiere['lastname'];
				$inf = $infirmiere['cabinet'];
				#echo '<pre>'.print_r($inf).'</pre>';exit;

				$str_html = '';
				$cpt = 0;
				//echo "<pre>";var_dump($inf);exit();
				foreach($inf as $ii){
					if($ii['recordstatus'] == 0) {
						$cpt++;
						if($ii['logiciel']!=''){
							$logiciel = $ii['logiciel'];
						}
						else{
							$logiciel = 'nc';
						}
				
						if($cpt != 1) { $str_html .= '</tr><tr>'; }
						$str_html .= '<td align="center" height="40">'.utf8_encode($ii['cabinet']).'<br><i>('.utf8_encode(logiciel_getname($logiciel)).') </i></td>';
						$str_html .= '<td align="center">'.$ii['lundi'].'</td>';
						$str_html .= '<td align="center">'.$ii['mardi'].'</td>';
						$str_html .= '<td align="center">'.$ii['mercredi'].'</td>';
						$str_html .= '<td align="center">'.$ii['jeudi'].'</td>';
						$str_html .= '<td align="center">'.$ii['vendredi'].'</td>';
						$str_html .= '<td align="center">'.$ii['samedi'].'</td>';
						$str_html .= '</tr>';
					}
				}
				?>
				<?php if($str_html != ''): ?>
					<tr>
						<td rowspan="<?php echo $cpt ?>" align="center">
							<b><?php echo $inf[0]['prenom'];?> <?php echo $inf[0]['nom'];?></b><br>
							<a href="mailto:<?php echo $inf[0]['email'];?>"><?php echo $inf[0]['email'];?></a><br>
							<?php echo $inf[0]['telephone'];?><br>
						</td>
						<?php echo $str_html ?>
					</tr>
				<?php endif ?>
			<?php 
			}
			?>

		</table>
	
	</div>
</div>

