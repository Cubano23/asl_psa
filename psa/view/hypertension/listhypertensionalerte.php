<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account ?>
<?php global $param ?>
<?php global $rowsList ?>
<?php global $outDateReference ?>
<?php $tabexamannuel=array(0=>"Poids",
						   1=>"Tension",
						   2=>"Examen Cardio-vasculaire",
						   3=>"Auscultation des art�res",
						   4=>"Palpation des pouls p�riph�riques",
						   5=>"Recherche d'un souffle abdominal",
						   6=>"Cr�atinine",
						   7=>"Glyc�mie",
						   8=>"Kali�mie",
						   9=>"Cholest�rol HDL",
						   10=>"Cholest�rol LDL",
						   11=>"Prot�inurie",
						   12=>"H�maturie",
						   13=>"Fond d'oeil",
						   14=>"ECG");?>

<?php
$nb_dossier=0;
$nb_exam=0;
$dossier0='0';


	$dossierMapper = new DossierMapper(NULL);
	$HyperTensionArterielleMapper = new HyperTensionArterielleMapper(NULL);

  	for($i=0;$i<count($rowsList);$i++){
		$dossier = $dossierMapper->doLoadObject($rowsList[$i]);
		$dossier = $dossier->afterDeserialisation($account);
		$HyperTensionArterielle = $HyperTensionArterielleMapper->doLoadObject($rowsList[$i]);
		$HyperTensionArterielle = $HyperTensionArterielle->afterDeserialisation($account);

/*		if(($dossier->numero!=$dossier0)||(strlen($dossier->numero)!=strlen($dossier0)))
		{
			$nb_dossier++;
			$dossier0=$dossier->numero;
		}
	*/
    	 	for($j=0;$j<count($tabexamannuel);$j++){

			    $dateRef = $HyperTensionArterielle->isOutdated($outDateReference->period,$tabexamannuel[$j]);
			    if($dateRef!=false)
			    {
					if(($dossier->numero!=$dossier0)||(strlen($dossier->numero)!=strlen($dossier0)))
					{
						$nb_dossier++;
						$dossier0=$dossier->numero;
					}

			        $nb_exam++;
			    }
    	 	}

  }
?>

<table border="1" cellpadding='3'>
  <CAPTION>
   <?php echo $nb_exam; ?> examens �chus
   			 dans moins de <?php echo $outDateReference->period; ?> mois (pour <?php echo $nb_dossier; ?> dossiers)
  </CAPTION>
  <tr>
    <th width="52">Dossier</th>
    <th width="33">Sexe</th>
    <th width="122">Date de naissance</th>
    <th width="90">Type d'examen</th>
    <th width="72">Ech�ance</th>
<!--    <th width="141">Action</th>-->
  </tr>
  <?php
	$dossierMapper = new DossierMapper(NULL);
	$HyperTensionArterielleMapper = new HyperTensionArterielleMapper(NULL);

  	for($i=0;$i<count($rowsList);$i++){
		$dossier = $dossierMapper->doLoadObject($rowsList[$i]);
		$dossier = $dossier->afterDeserialisation($account);
		$HyperTensionArterielle = $HyperTensionArterielleMapper->doLoadObject($rowsList[$i]);
		$HyperTensionArterielle = $HyperTensionArterielle->afterDeserialisation($account);


    	 	for($j=0;$j<count($tabexamannuel);$j++){

				    $dateRef = $HyperTensionArterielle->isOutdated($outDateReference->period,$tabexamannuel[$j]);
				    if($dateRef!=false)
				    {
						?>
						<tr align='center' valign='baseline'>
						  <td>&nbsp;<?php echo($dossier->numero); ?></td>
						  <td>&nbsp;<?php echo($sexe[$dossier->sexe]); ?></td>
						  <td>&nbsp;<?php echo($dossier->dnaiss); ?></td>
						  <td><?php echo $tabexamannuel[$j]; ?></td>
						  <td><?php echo($dateRef); ?></td>
						</tr>
						<?php
				    }
    	 	}
  }
  ?>
</table>

