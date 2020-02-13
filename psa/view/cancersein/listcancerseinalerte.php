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

	$num_dossier=0;
	$dossierMapper = new DossierMapper(NULL);
	$depistageCancerSeinMapper = new depistageCancerSeinMapper(NULL);

  	for($i=0;$i<count($rowsList);$i++){
		$dossier = $dossierMapper->doLoadObject($rowsList[$i]);
		$dossier = $dossier->afterDeserialisation($account);
		
		$depistageCancerSein = $depistageCancerSeinMapper->doLoadObject($rowsList[$i]);
		$depistageCancerSein = $depistageCancerSein->afterDeserialisation($account);

	 	$dateRef = $depistageCancerSein->isOutdated($outDateReference->period);

		if($dateRef != false){
			$num_dossier++;
			
			if(!isset($_GET["tri"])||($_GET["tri"]=="dossierasc")){
				$tab[$i]=$rowsList[$i]["numero"];
			}
			elseif($_GET["tri"]=="dossierdesc"){
				$tab[$i]=$rowsList[$i]["numero"];
			}
			elseif($_GET["tri"]=="sexeasc"){
				$tab[$i]=$rowsList[$i]["sexe"].$rowsList[$i]["numero"];
			}
			elseif($_GET["tri"]=="sexedesc"){
				$tab[$i]=$rowsList[$i]["sexe"].$rowsList[$i]["numero"];
			}
			elseif($_GET["tri"]=="dnaissasc"){
				$tab[$i]=$rowsList[$i]["dnaiss"].$rowsList[$i]["numero"];
			}
			elseif($_GET["tri"]=="dnaissdesc"){
				$tab[$i]=$rowsList[$i]["dnaiss"].$rowsList[$i]["numero"];
			}
			elseif($_GET["tri"]=="mammoasc"){
				$tab[$i]=$rowsList[$i]["mamograph_date"].$rowsList[$i]["numero"];
			}
			elseif($_GET["tri"]=="mammodesc"){
				$tab[$i]=$rowsList[$i]["mamograph_date"].$rowsList[$i]["numero"];
			}
			elseif($_GET["tri"]=="echeanceasc"){
				$tab[$i]=$rowsList[$i]["rappel_mammographie"].$rowsList[$i]["numero"];
			}
			elseif($_GET["tri"]=="echeancedesc"){
				$tab[$i]=$rowsList[$i]["rappel_mammographie"].$rowsList[$i]["numero"];
			}
			else{
				$tab[$i]=$rowsList[$i]["numero"].$rowsList[$i]["numero"];
			}
		}
  	}

?>
<table border="1" cellpadding='3'> 
  <CAPTION>
   <?php echo $num_dossier ?> dossiers avec examen échu dans moins de <?php echo $outDateReference->period; ?> mois
  </CAPTION> 
  <tr> 
    <th width="52" align='center'>Dossier<br>
					<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>&tri=dossierasc", "_top")'>
					<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>&tri=dossierdesc", "_top")'></th> 
    <th align='center'>Sexe<br>
					<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>&tri=sexeasc", "_top")'>
					<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>&tri=sexedesc", "_top")'></th> 
    <th width="122" align='center'>Date de naissance<br>
					<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>&tri=dnaissasc", "_top")'>
					<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>&tri=dnaissdesc", "_top")'></th> 
    <th width="90" align='center'>Date mammographie<br>
					<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>&tri=mammoasc", "_top")'>
					<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>&tri=mammodesc", "_top")'></th>
    <th width="72" align='center'>Echéance<br>
					<img src='<?php echo $path;?>/view/images/triasc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>&tri=echeanceasc", "_top")'>
					<img src='<?php echo $path;?>/view/images/tridesc.gif' onclick='window.open("ActionControler.php?controlerparams:param:controler=DepistageCancerSeinControler&controlerparams:param:action=AL&controlerparams:param:param1=POUTD&OutdateReference:outDateReference:period=<?php  echo $outDateReference->period;?>&tri=echeancedesc", "_top")'></th> 
<!--    <th width="141">Action</th>-->
  </tr> 
  <?php 
	$dossierMapper = new DossierMapper(NULL);
	$depistageCancerSeinMapper = new depistageCancerSeinMapper(NULL);

	if(!isset($_GET["tri"])||($_GET["tri"]=="dossierasc")||($_GET["tri"]=="sexeasc")||($_GET["tri"]=="dnaissasc")||
		($_GET["tri"]=="mammoasc")||($_GET["tri"]=="echeanceasc")){
		asort($tab);
	}
	elseif(($_GET["tri"]=="dossierdesc")||($_GET["tri"]=="sexedesc")||($_GET["tri"]=="dnaissdesc")||
		($_GET["tri"]=="mammodesc")||($_GET["tri"]=="echeancedesc")){
		arsort($tab);
	}
	else{
		asort($tab);
	}
	
	
  	foreach($tab as $i=>$val){
		$dossier = $dossierMapper->doLoadObject($rowsList[$i]);
		$dossier = $dossier->afterDeserialisation($account);
		$depistageCancerSein = $depistageCancerSeinMapper->doLoadObject($rowsList[$i]);
		$depistageCancerSein = $depistageCancerSein->afterDeserialisation($account);

	 	$dateRef = $depistageCancerSein->isOutdated($outDateReference->period);

			if($dateRef != false){

			require("listcancerseinalerterow.php");

		}
	 ?>

  <?php 
  }
  ?>
</table> 

