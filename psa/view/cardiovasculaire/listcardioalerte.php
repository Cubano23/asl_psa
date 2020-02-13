<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account ?>
<?php global $param ?>
<?php global $rowsList ?>
<?php global $outDateReference ?>
<?php $tabexamannuel=array(0=>"Examen Cardio-Vasculaire",
						   1=>"ECG",
						   2=>"Fond d'oeil",
						   3=>"Protéinurie",
						   4=>"Créatinine",
						   5=>"Cholestérol",
						   6=>"HDL",
						   7=>"LDL",
						   8=>"Triglycérides",
						   9=>"Glycémie",
						   10=>"Kaliémie");?>

<?php

$nb_exam=$nb_suivis=0;

$tabalerte=array();//Liste des alertes dans un tableau dans lequel il y aura l'ensemble des données à afficher
$tabtri=array();//Tableau permettant de faire le tri

	$dossierMapper = new DossierMapper(NULL);
	$CardioVasculaireMapper = new CardioVasculaireDepartMapper(NULL);

  	for($i=0;$i<count($rowsList);$i++){//On parcours la liste des dossiers pour vérifier : 1/ si le dossier n'est pas sorti, 2/ s'il y a des exams en alerte
		$dossier = $dossierMapper->doLoadObject($rowsList[$i]);
		$dossier = $dossier->afterDeserialisation($account);
		$CardioVasculaire = $CardioVasculaireMapper->doLoadObject($rowsList[$i]);
		$CardioVasculaire = $CardioVasculaire->afterDeserialisation($account);

		$test=0;

		if($CardioVasculaire->sortir_rappel!='1'){
    	 	for($j=0;$j<count($tabexamannuel);$j++){

			    $dateRef = $CardioVasculaire->isOutdated($outDateReference->period,$tabexamannuel[$j]);
			    if($dateRef!=false)//Un exam annuel en alerte
			    {
					if(!isset($dossiers[$dossier->numero]))
					{
						$dossiers[$dossier->numero]=1;
					}
					
			        $nb_exam++;

					$tabalerte[]=array("numero"=>$dossier->numero, "sexe"=>$dossier->sexe, "dnaiss"=>$dossier->dnaiss, 
									"type_exam"=>$tabexamannuel[$j], "echeance"=>$dateRef);

					if((!isset($_GET["tri"]))||($_GET["tri"]=="dossierasc")||($_GET["tri"]=="dossierdesc")){
						$tabtri[]=$dossier->numero;
					}
					elseif(($_GET["tri"]=="sexeasc")||($_GET["tri"]=="sexedesc")){
						$tabtri[]=$dossier->sexe;
					}
					elseif(($_GET["tri"]=="dnaissasc")||($_GET["tri"]=="dnaissdesc")){
						$dnaiss=explode("/", $dossier->dnaiss);
						$dnaiss=$dnaiss[2].$dnaiss[1].$dnaiss[0];
						$tabtri[]=$dnaiss;
					}
					elseif(($_GET["tri"]=="examasc")||($_GET["tri"]=="examdesc")){
						$tabtri[]="HBA1c";
					}
					elseif(($_GET["tri"]=="echeanceasc")||($_GET["tri"]=="echeancedesc")){
						$echeance=explode("/", $dateRef);
						if(!isset($echeance[2])){
							$echeance=$echeance[0];
						}
						else{
							$echeance=$echeance[2].$echeance[1].$echeance[0];
						}
						$tabtri[]=$echeance;
					}

			    }
    	 	}
			
		}

  }
  
  
  //Tri des alertes en fonction de ce qui est demandé
	
	
	if((!isset($_GET["tri"]))||($_GET["tri"]=="dossierasc")||($_GET["tri"]=="sexeasc")||($_GET["tri"]=="dnaissasc")||
		($_GET["tri"]=="examasc")||($_GET["tri"]=="echeanceasc")){
		
		natsort($tabtri);
	}
	else{
		natsort($tabtri);
		
		$tabtmp=array();
		
		$j=0;
		
		foreach($tabtri as $i=>$val){
			$tabtmp[$i]=$j;
			$j++;
		}
		arsort($tabtmp);
		$tabtri=$tabtmp;
	}
?>

<table border="1" cellpadding='3'> 
  <CAPTION>
   <?php echo count($dossiers); ?> dossiers avec au total <?php echo $nb_exam; ?> examens échus
   			 dans moins de <?php echo $outDateReference->period; ?> mois
  </CAPTION> 
  <tr> 
    <th width="52">Dossier
    <th width="33">Sexe
    <th width="122">Date de naissance
    <th width="90">Type d'examen
    <th width="72">Echéance
<!--    <th width="141">Action</th>-->
  </tr> 
  <?php 
  
	foreach($tabtri as $key=>$val){
		echo "<tr><td>".$tabalerte[$key]["numero"]."</td><td>".$tabalerte[$key]["sexe"]."</Td><td>".$tabalerte[$key]["dnaiss"].
			 "</Td><td>".$tabalerte[$key]["type_exam"]."</Td><td>".$tabalerte[$key]["echeance"]."</Td></Tr>";
	}

  ?>
</table> 

