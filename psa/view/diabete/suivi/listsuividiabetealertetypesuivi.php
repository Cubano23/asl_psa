<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account ?>
<?php global $param ?>
<?php global $rowsList ?>
<?php global $outDateReference ?>

<table border="1" cellpadding='3'> 
  <CAPTION>
   <?php echo(count($rowsList)) ?> dossiers avec examen échu dans moins de <?php echo $outDateReference->period; ?> mois
  </CAPTION> 
  <tr> 
    <th width="52">Dossier</th> 
    <th width="33">Sexe</th> 
    <th width="122">Date de naissance</th> 
    <th width="90">Type d'examen</th>
    <th width="72">Echéance</th> 
    <th width="141">Action</th> 
  </tr> 
  <?php 
	$dossierMapper = new DossierMapper(NULL);
	$suiviDiabeteMapper = new SuiviDiabeteMapper(NULL);
  	for($i=0;$i<count($rowsList);$i++){
		$dossier = $dossierMapper->doLoadObject($rowsList[$i]);
		$dossier = $dossier->afterDeserialisation($account);
		$suiviDiabete = $suiviDiabeteMapper->doLoadObject($rowsList[$i]);
		$suiviDiabete = $suiviDiabete->afterDeserialisation($account);

	 	for($j=0;$j<count($suiviDiabete->suivi_type);$j++){ 
		 	$dateRef = $suiviDiabete->isOutdated($outDateReference->period,$suiviDiabete->suivi_type[$j]);
			if($dateRef != false){
				require("listsuividiabetealerterow.php");
			}
		}
	 ?>

  <?php 
  }
  ?>
</table> 

