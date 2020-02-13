<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account ?>
<?php global $param ?>
<?php global $rowsList ?>
<?php global $outDateReference ?>

  <?php
  
    $nb_dossier=0;
    
	$dossierMapper = new DossierMapper(NULL);
	$depistageCancerUterusMapper = new depistageCancerUterusMapper(NULL);

  	for($i=0;$i<count($rowsList);$i++){
		$dossier = $dossierMapper->doLoadObject($rowsList[$i]);
		$dossier = $dossier->afterDeserialisation($account);
		$depistageCancerUterus = $depistageCancerUterusMapper->doLoadObject($rowsList[$i]);
		$depistageCancerUterus = $depistageCancerUterus->afterDeserialisation($account);

	 	$dateRef = $depistageCancerUterus->isOutdated($outDateReference->period);
		if($dateRef != false){

			$nb_dossier++;

		}
  }
?>
<table border="1" cellpadding='3'> 
  <CAPTION>
   <?php echo $nb_dossier; ?> dossiers avec examen échu dans moins de <?php echo $outDateReference->period; ?> mois
  </CAPTION> 
  <tr> 
    <th width="52">Dossier</th> 
    <th width="33">Sexe</th> 
    <th width="122">Date de naissance</th> 
    <th width="90">Date frottis</th>
    <th width="72">Echéance</th> 
<!--    <th width="141">Action</th>-->
  </tr> 
  <?php 
	$dossierMapper = new DossierMapper(NULL);
	$depistageCancerUterusMapper = new depistageCancerUterusMapper(NULL);

  	for($i=0;$i<count($rowsList);$i++){
		$dossier = $dossierMapper->doLoadObject($rowsList[$i]);
		$dossier = $dossier->afterDeserialisation($account);
		$depistageCancerUterus = $depistageCancerUterusMapper->doLoadObject($rowsList[$i]);
		$depistageCancerUterus = $depistageCancerUterus->afterDeserialisation($account);

	 	$dateRef = $depistageCancerUterus->isOutdated($outDateReference->period);
		if($dateRef != false){

			require("listcanceruterusalerterow.php");

		}
	 ?>

  <?php 
  }
  ?>
</table> 

