<?php

require_once("persistence/ConnectionFactory.php");
require_once("bean/PlanningInfirmieres.php");
require_once("bean/Account.php");	
require_once("persistence/AccountMapper.php");
require_once("controler/UtilityControler.php");


require_once("../stats/patients/cab/logiciel_getname.php");

require_once "Config.php";
$config = new Config();

if($_SERVER['APPLICATION_ENV']=='dev-herve'){
	$path = 'http://'.$_SERVER['HTTP_HOST']. $config->psa_path .'/';
}
else{
	$path = 'https://'.$_SERVER['HTTP_HOST'];
}

#echo $path;
$conn = new ConnectionFactory();
$conn->getConnection();
session_start();
#var_dump($_POST);

$filtreInf = false;

#var_dump($_POST);

$regions = UtilityControler::getRegions();




if(isset($_POST['logiciel']) ){
	$filtreLogiciel = $_POST['logiciel'];
}	
	if($_POST['search-nom']!='' && $_POST['search-nom']!='Nom'){
		$filtre['nom'] = $_POST['search-nom'];$filtreInf = true;
	}else{$filtre['nom'] = false;}
	if($_POST['search-prenom']!='' && $_POST['search-prenom']!='Prénom'){
		$filtre['prenom'] = $_POST['search-prenom'];$filtreInf = true;
	}else{$filtre['prenom'] = false;}


if(isset($_GET['reset']) && !$_POST){
	
	$filtre['nom'] = '';$filtreInf = false;
	$filtre['prenom'] = '';$filtreInf = false;
}

if(isset($_POST['localisation'])){
	#echo '<p>'.$_POST['localisation'].'</p>';
	
	/* pierre : 13/09/17 : changement pour DOM-TOM => pourquoi boucle sur POST ? car il n'y a qu'une valeur normalement */
	//$filtreLocalisation = '(' . $_POST['localisation'] . ')';
	
	if(strlen($_POST['localisation']) < 4){
		$dpt_in = $_POST['localisation'];
		$dpt_in = "(".$dpt_in.")";
	}
	else{
		$dpt_in = '(';
		foreach($regions[$_POST['localisation']] as $key=>$dpt){
			$dpt_in .= "$key,";
		}
		$dpt_in = substr($dpt_in,0,-1);
		$dpt_in .= ')';
		//$dpt_in = '('.substr($dpt_in,0,-1).')';
	}
	$filtreLocalisation = $dpt_in;
 	// var_dump($filtreLocalisation);
}



// var_dump($regions[$_POST['localisation']]);

// recupération des infos de l'infirmère current et de celles du cabinet
require_once ("Config.php");
$config = new Config();
require_once($config->rest_path . '/GetCabsAndLogins.php') ;

function HTML_entete_colonne(){

	$html='<tr>
				<td align="center" width="16%"><b>Noms</b></td>
				<td align="center" width="12%"><b>Cabinet<br><i>(logiciel)</i></b></td>
				<td align="center" width="12%"><b>Lundi</b></td>
				<td align="center" width="12%"><b>Mardi</b></td>
				<td align="center" width="12%"><b>Mercredi</b></td>
				<td align="center" width="12%"><b>Jeudi</b></td>
				<td align="center" width="12%"><b>Vendredi</b></td>
				<td align="center" width="12%"><b>Samedi</b></td>
			</tr>';
	return $html;
}




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


		<h3 align="left" style="height:30px;"><a href="planning.php"><< Retour au planning</a></h3>
		
		<table width="98%" border="1" align="center" cellspacing="0">

			<form method="POST" name="search">
				<?php
				$rech = true;
				if($rech){ ?>
				<tr>
					<td colspan="8" style="border:0;height:40px;background-color:#efefef;padding-top:10px">
						<b>Filtre : </b> 
						<input type="text" name="search-nom" placeholder="Nom" value="<?php echo $_POST['search-nom'];?>">&nbsp;&nbsp;&nbsp;
						<input type="text" name="search-prenom" placeholder="Prénom" value="<?php echo $_POST['search-prenom'];?>">
						&nbsp;&nbsp;&nbsp;
						Logiciel 
						<?php $logiciels = AccountMapper::listeDistinctLogiciels();
						#var_dump($logiciels);
						?>
						<select name="logiciel">
							<option value=''>Sélectionner</option>
							<?php foreach($logiciels as $log){

								$log = current($log);
								$nomLogiciel = logiciel_getname($log);
								if($log!='monlogiciel'){
									if($log == $_POST['logiciel']){ $selected = " selected";}else{$selected = '';}
									echo '<option '.$selected.' value="'.$log.'">'.utf8_encode($nomLogiciel).'</option>';
								}
							}
							?>
						</select>&nbsp;&nbsp;
						<select name="localisation">
							<option value=''>Localisation</option>
						<?php

						foreach($regions as $reg => $dept){
							if($_POST['localisation']==$reg){
								$selected = " selected ";
							}else{$selected = '';}
							echo '<option value="'.$reg.'" '.$selected.'>'.$reg.'</option>';

							foreach($dept as $key=>$value){
								if($_POST['localisation']==$key){
									$selected = " selected ";
									}else{$selected = '';}
								echo '<option value="'.$key.'" '.$selected.'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;('.$key.') '.$value.'</option>';
							}

						}
						?>
						</select>
						<br /><br />
						<div style="margin-left:20px;margin-top:-10px"><input type="image" src="../images/bt-rechercher.png" value="Rechercher">
						&nbsp;&nbsp;&nbsp;
						<a href="planning_search.php?reset"><img src="../images/bt-reset.png"></a></div>
					</td>
				
				</tr>
				<?php } ?>
			</form>



			<?php 
			
			if($_POST || isset($_GET['reset'])){



				// les cabinets on les prend dans account
				$listeCabinets = AccountMapper::listeAllCabs($filtreLogiciel,$filtreLocalisation);

				#var_dump($listeCabinets);
				foreach ($listeCabinets as $cab){

					// récuparation des infirmieres associées au cabinet
					
					if($cab['cabinet']!='zTest'){
						$cab['infirmieres'] = GetLoginsByCab($cab['cabinet'], $status);

						$infirmieres = array();
						foreach($cab['infirmieres'] as $cabInf){

							#var_dump($cabInf);exit;
							$mode = 'accept';
							// si on a filtré la liste, on checke si le nom/prenom correspond à la recherche, si c'est pas le cas on ignore
							// si filtreInf est à true alors on active le filtre d'affichage
							if($filtreInf){
								$mode = 'ignore';
							}

							
							
							if(stripos(strtolower(' '.$cabInf['nom']),strtolower($filtre['nom']))){
								$mode = 'accept';
							}

							if(stripos(strtolower(' '.$cabInf['prenom']),strtolower($filtre['prenom']))){
								$mode = 'accept';
							}
							
							if(!is_array($infirmiere[$cabInf['login']])){
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
									'samedi' => $plan['samedi']
									);
								
								if($mode=='accept'){ // elle correspond à la recherche donc on la met dans le tableau de résultats
									array_push($infirmiere[$cabInf['login']]['cabinet'],$lesinfos);
									$infirmiere[$cabInf['login']]['lastname'] = $cabInf['nom'];
								}
								
							#echo $mode.' '.$cabInf['nom']. ' / ';
							
							
						}
					}
					
					
					
					
				}


				// pour chacune infirmière restantes on affiche les donnés de la table planning_infirmieres
				?>
				
				<?php  echo HTML_entete_colonne();?>
				
				
				<?php 

				
				$infirmiereASC = UtilityControler::array_msort($infirmiere,array('lastname'=>SORT_ASC));
				#var_dump($infirmiereASC);exit;
				$nbreAffiche = 0;
				foreach ($infirmiereASC as $infirmiere){ 
					$lastname = $infirmiere['lastname'];
					$inf = $infirmiere['cabinet'];
					#echo '<pre>'.print_r($inf).'</pre>';exit;
					#var_dump($inf);exit;
					#var_dump(count($inf));
					if(count($inf)!=0){
						$nbreAffiche = $nbreAffiche+1;
					?> 
					<tr>
						
						<?php if(count($inf) > 1){ $rowspan=' rowspan="'.count($inf).'"';$lignesTT = count($inf);}else{$rowspan='';} ?>

						<td <?php echo $rowspan;?> align="center">
							<b><?php echo $inf[0]['prenom'];?> <?php echo $inf[0]['nom'];?> <!--(<?php echo $inf[0]['login'];?>)--></b><br>
							<a href="mailto:<?php echo $inf[0]['email'];?>"><?php echo $inf[0]['email'];?></a><br>
							<?php echo $inf[0]['telephone'];?><br>

						</td>

						<?php 
						foreach($inf as $ii){
							if($ii['logiciel']!=''){
								$logiciel = $ii['logiciel'];
							}
							else{
								$logiciel = 'nc';
							}
						?>
							<td align="center" height="40">
								<?php echo utf8_encode($ii['cabinet']);?><br><i>(<?php echo utf8_encode(logiciel_getname($logiciel));?>) </i></td>
							
							<td align="center">
								<?php echo $ii['lundi'];?></td>
							<td align="center">
								<?php echo $ii['mardi'];?></td>
							<td align="center">
								<?php echo $ii['mercredi'];?></td>
							<td align="center">
								<?php echo $ii['jeudi'];?></td>
							<td align="center">
								<?php echo $ii['vendredi'];?></td>
							<td align="center">
								<?php echo $ii['samedi'];?></td>
						</tr>
						<?php
				  		} 

					}
					
				}

				if($nbreAffiche==0){
					echo '<tr><td colspan="8" height="30"><h4>Aucun résultat ne correspond à votre recherche</h4></td></tr>';
				}
			} // fin du if $_POST ?>
		</table>





		
			


		<?php #} ?>
		</table>
	
	</div>
</div>

