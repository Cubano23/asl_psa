
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php global $dossiers ?>
<?php global $param ?>
<?php global $account ?>
<?php global $param ?>

<?php
for($i=0;$i<count($dossiers);$i++){
	if(!isset($_GET["tri"])||($_GET["tri"]=="dossierasc")||($_GET["tri"]=="dossierdesc")){
		$tab[$i]=$dossiers[$i]->numero;
	}
	elseif(($_GET["tri"]=="sexeasc")||($_GET["tri"]=="sexedesc")){
		$tab[$i]=$dossiers[$i]->sexe;
	}
	elseif(($_GET["tri"]=="dnaissasc")||($_GET["tri"]=="dnaissdesc")){
		$dnaiss=$dossiers[$i]->dnaiss;
		$dnaiss=explode("/", $dnaiss);
		$dnaiss=$dnaiss[2].$dnaiss[1].$dnaiss[0];
		$tab[$i]=$dnaiss;
	}
	elseif(($_GET["tri"]=="tailleasc")||($_GET["tri"]=="tailledesc")){
		$tab[$i]=$dossiers[$i]->taille;
	}
	elseif(($_GET["tri"]=="actifasc")||($_GET["tri"]=="actifdesc")){
		$tab[$i]=$dossiers[$i]->actif;
	}
	elseif(($_GET["tri"]=="dconsentementasc")||($_GET["tri"]=="dconsentementdesc")){
	$dconsentement=$dossiers[$i]->dconsentement;
	$dconsentement=explode("/", $dconsentement);
	$dconsentement=$dconsentement[2].$dconsentement[1].$dconsentement[0];
	$tab[$i]=$dconsentement;
	}
}

	natsort($tab);

if(isset($_GET["tri"])&&(($_GET["tri"]=="dossierdesc")||($_GET["tri"]=="sexedesc")||($_GET["tri"]=="dnaissdesc")||($_GET["tri"]=="tailledesc")||
		($_GET["tri"]=="actifdesc") || ($_GET["tri"]=="dconsentementdesc") )){
	
	$tabtmp=array();
	
	$j=0;
	
	foreach($tab as $i=>$val){
		$tabtmp[$i]=$j;
		$j++;
	}
	arsort($tabtmp);
	$tab=$tabtmp;
}

?>
<body>
<table width="50%"  border="1" cellspacing="0" cellpadding="0">
<?php /*<caption >Cabinet: <?php typePropertyValue("account:cabinet"); ?></caption>*/?>
<caption>Liste des <?php echo(count($dossiers)); ?> dossiers pour le cabinet <?php typePropertyValue("account:cabinet"); ?></caption>
  <tr>
    <th scope="col">Num. Dossier<br>
		<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AL&controlerparams:param:param1=PE&tri=dossierasc", "_top")'>
		<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AL&controlerparams:param:param1=PE&tri=dossierdesc", "_top")'></th>
    <th scope="col">Sexe<br>
		<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AL&controlerparams:param:param1=PE&tri=sexeasc", "_top")'>
		<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AL&controlerparams:param:param1=PE&tri=sexedesc", "_top")'></th>
	<th scope="col">Date de Naissance<br>
		<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AL&controlerparams:param:param1=PE&tri=dnaissasc", "_top")'>
		<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AL&controlerparams:param:param1=PE&tri=dnaissdesc", "_top")'></th>
	<th scope="col">Taille<br>
		<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AL&controlerparams:param:param1=PE&tri=tailleasc", "_top")'>
		<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AL&controlerparams:param:param1=PE&tri=tailledesc", "_top")'></th>
	<th scope="col">Actif<br>
		<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AL&controlerparams:param:param1=PE&tri=actifasc", "_top")'>
		<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AL&controlerparams:param:param1=PE&tri=actifdesc", "_top")'></th>
    <th scope="col">Date de consentement<br>
		<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AL&controlerparams:param:param1=PE&tri=dconsentementasc", "_top")'>
		<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AL&controlerparams:param:param1=PE&tri=dconsentementdesc", "_top")'></th>
    <th scope="col">&nbsp;</th>    
  </tr>
  <?php global $dossier ?>
  <?php 
  foreach ($tab as $i=>$val){ 
  $dossier=$dossiers[$i];
  ?>
  <tr>
  	<td><?php typePropertyValue("dossier:numero")?></td>
	<td><?php typePropertyValue("dossier:sexe")?></td>
	<td><?php typePropertyValue("dossier:dnaiss")?></td>
	<td><?php typePropertyValue("dossier:taille")?></td>
	<td><?php typePropertyValue("dossier:actif")?></td>
	<td><?php typePropertyValue("dossier:dconsentement")?></td>
	<td>
		<?php
				 $additionalParams = array(getPropertyName("dossier:numero")=>getPropertyValue("dossier:numero"));
				 buildLink("","Modifier","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_EDIT,"",$param->param3),$additionalParams); 
		?>	
  </tr>
  <?php }?>
</table>


