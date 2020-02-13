<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account ?>
<?php global $param ?>
<?php global $rowsList ?>
<?php global $outDateReference ?>
<?php $tabexamannuel=array(0=>"Monofilament",
						   1=>"Examen des pieds",
						   2=>"HDL/LDL",
						   3=>"Créatininémie",
						   4=>"Albuminurie",
						   5=>"Fond oeil",
						   6=>"ECG",
						   7=>"Dentiste",
						   8=>"Triglycerides",
						   9=>"Kaliemie");?>

<?php

$nb_exam=$nb_suivis=0;

$tabalerte=array();//Liste des alertes dans un tableau dans lequel il y aura l'ensemble des données à afficher
$tabtri=array();//Tableau permettant de faire le tri

	$dossierMapper = new DossierMapper(NULL);
	$suiviDiabeteMapper = new SuiviDiabeteMapper(NULL);

  	for($i=0;$i<count($rowsList);$i++){//On parcours la liste des dossiers pour vérifier : 1/ si le dossier n'est pas sorti, 2/ s'il y a des exams en alerte
		$dossier = $dossierMapper->doLoadObject($rowsList[$i]);
		$dossier = $dossier->afterDeserialisation($account);
		$suiviDiabete = $suiviDiabeteMapper->doLoadObject($rowsList[$i]);
		$suiviDiabete = $suiviDiabete->afterDeserialisation($account);

		$test=0;

		if($suiviDiabete->sortie!='1'){
		    $dateRef = $suiviDiabete->isOutdated($outDateReference->period,"4");
			if($dateRef!=false)//Le HBA1c est en alerte
			{
				if(!isset($dossiers[$dossier->numero]))
				{
					$dossiers[$dossier->numero]=1;
				}
			    $nb_exam++;
				$test=1;
			    $nb_suivis++;
				
				$tabalerte[]=array("numero"=>$dossier->numero, "sexe"=>$dossier->sexe, "dnaiss"=>$dossier->dnaiss, "type_exam"=>"HBA1c",
								   "echeance"=>$dateRef);
								   
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
			$test=0;
    	 	for($j=0;$j<count($tabexamannuel);$j++){

			    $dateRef = $suiviDiabete->isOutdated($outDateReference->period,$tabexamannuel[$j]);
			    if($dateRef!=false)//Un exam annuel en alerte
			    {
					if(!isset($dossiers[$dossier->numero]))
					{
						$dossiers[$dossier->numero]=1;
					}
					
					if($test==0){
						$nb_suivis++;
						$test=1;
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
						$tabtri[]=$tabexamannuel[$j];
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
   <?php echo $nb_suivis; ?> suivis (pour <?php echo count($dossiers); ?> dossiers) avec au total <?php echo $nb_exam; ?> examens échus
   			 dans moins de <?php echo $outDateReference->period; ?> mois
  </CAPTION> 
  <tr> 
    <th width="52">Dossier<br>
		<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&tri=dossierasc&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>", "_top")'>
		<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&tri=dossierdesc&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>", "_top")'></th> 
    <th width="33">Sexe<br>
		<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&tri=sexeasc&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>", "_top")'>
		<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&tri=sexedesc&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>", "_top")'></th> 
    <th width="122">Date de naissance<br>
		<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&tri=dnaissasc&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>", "_top")'>
		<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&tri=dnaissdesc&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>", "_top")'></th> 
    <th width="90">Type d'examen<br>
		<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&tri=examasc&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>", "_top")'>
		<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&tri=examdesc&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>", "_top")'></th> 
    <th width="72">Echéance<br>
		<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&tri=echeanceasc&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>", "_top")'>
		<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=SuiviDiabeteControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&tri=echeancedesc&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>", "_top")'></th> 
<!--    <th width="141">Action</th>-->
  </tr> 
  <?php 
  
	foreach($tabtri as $key=>$val){
		echo "<tr><td>".$tabalerte[$key]["numero"]."</td><td>".$tabalerte[$key]["sexe"]."</Td><td>".$tabalerte[$key]["dnaiss"].
			 "</Td><td>".$tabalerte[$key]["type_exam"]."</Td><td>".$tabalerte[$key]["echeance"]."</Td></Tr>";
	}

  ?>
</table> 

