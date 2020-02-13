<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account ?>
<?php global $param ?>
<?php global $rowsList; ?>
<?php global $outDateReference ?>

<?php

$num_dossier=0;

	$dossierMapper = new DossierMapper(NULL);
	$depistageCancerColonMapper = new depistageCancerColonMapper(NULL);

$numero_dossier="";

  	for($i=0;$i<count($rowsList);$i++){
		$dossier = $dossierMapper->doLoadObject($rowsList[$i]);
		$dossier = $dossier->afterDeserialisation($account);
		$depistageCancerColon = $depistageCancerColonMapper->doLoadObject($rowsList[$i]);
		$depistageCancerColon = $depistageCancerColon->afterDeserialisation($account);

		if(strcasecmp($numero_dossier, $depistageCancerColon->id)==0){
			$dateRef=false;
		}
		else{
		 	$dateRef = $depistageCancerColon->isOutdated($outDateReference->period);
		 	$numero_dossier=$depistageCancerColon->id;
		}


		if($dateRef != false){

			$num_dossier++;

		}
	 ?>

  <?php
  }
?>
<table border="1" cellpadding='3'> 
  <CAPTION>
   <?php echo($num_dossier) ?> dossiers avec examen échu dans moins de <?php echo $outDateReference->period; ?> mois
  </CAPTION> 
  <tr> 
    <th width="52">Dossier</th> 
    <th width="33">Sexe</th> 
    <th width="122">Date de naissance</th> 
    <th width="90">Date coloscopie</th>
    <th width="72">Echéance</th> 
<!--    <th width="141">Action</th>-->
  </tr> 
  <?php 
	$dossierMapper = new DossierMapper(NULL);
	$depistageCancerColonMapper = new depistageCancerColonMapper(NULL);

	$numero_dossier="";

  	for($i=0;$i<count($rowsList);$i++){
		$dossier = $dossierMapper->doLoadObject($rowsList[$i]);
		$dossier = $dossier->afterDeserialisation($account);
		$depistageCancerColon = $depistageCancerColonMapper->doLoadObject($rowsList[$i]);
		$depistageCancerColon = $depistageCancerColon->afterDeserialisation($account);

		if(strcasecmp($numero_dossier, $depistageCancerColon->id)==0){
			$dateRef=false;
		}
		else{
		 	$dateRef = $depistageCancerColon->isOutdated($outDateReference->period);
		 	$numero_dossier=$depistageCancerColon->id;
		}

		if($dateRef != false){

			require("listcancercolonalerterow.php");

		}
	 ?>

  <?php 
  }
  ?>
</table> 

