<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/jsgenerator/jsdatefunctions.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $graphBean ?>
<?php global $param ?>

<script type="text/javascript" >	
function validateStartEndDates() {
	var stMonth = document.getElementById("stMonth");
	var stYear = document.getElementById("stYear");
	var endMonth = document.getElementById("endMonth");
	var endYear = document.getElementById("endYear");
			
	stDate = "00/"+stMonth.value+"/"+stYear.value;
	endDate = "00/"+endMonth.value+"/"+endYear.value;

	if(monthDiffDates(endDate,stDate)<0) {
		alert (" La date de début dépasse la date de fin ");
		return false;
	}
	else {
		return true;			
	}	
}

<?php
	monthDiffDates();		
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","aForm");
	?> if(!validateStartEndDates()) submitOk = 0; <?php
	$js->validateRange("graphBean:stepsNum",1,100,"Nombre d\'intervalles");
	$js->validateRange("graphBean:zoom",1,40,"Grossissement");
	$js->endCheckFunction();	
?>

</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm"> 
  <?php hiddenControler("HBAGraphControler"); ?> 
  <?php hiddenAction(ACTION_FIND); ?> 
  <?php hiddenParam1(PARAM_GRAPH2); ?>
  
  <table border="0"> 
    <tr> 
      <td>Cabinet:</td> 
      <td>
      	  	   <?php
			 if ($_SESSION['account']->cabinet=='admin')
	  				selectv("","graphBean:cabinets",$cabinets,false,array(""=>"Tous"));
			 else
					typePropertyValue("account:cabinet");/*selectv("","graphBean:cabinets",$cabinets,false,array(""=>"Tous"));*/
				?>
	  </td>
    </tr> 
    <tr> 
      <td>Nombre d'intervalles:</td> 
      <td><?php text("","graphBean:stepsNum"); ?></td> 
    </tr> 
    <tr> 
      <td>Grossissement:</td> 
      <td><?php text("","graphBean:zoom"); ?></td> 
    </tr> 
    <tr> 
      <td>Début</td> 
      <td>&nbsp;<?php selectv("id='stMonth'","graphBean:startMonth",$monthsArray); ?>/<?php selectv("id = 'stYear'","graphBean:startYear",$yearsArray); ?></td> 	  
    </tr> 
    <tr> 
      <td>Fin</td> 
      <td>&nbsp;<?php selectv("id='endMonth'","graphBean:endMonth",$monthsArray); ?>/<?php selectv("id = 'endYear'","graphBean:endYear",$yearsArray); ?></td> 
    </tr> 
    <tr> 
      <td colspan=2> <input type="button"  onClick="validateInput()" name="Valider" value="Valider"> </td> 
    </tr> 
  </table> 
</form>

<br/> 
<p align="left"> <b>Méthode de calcul:</b> 
<ul> 
  <li>Sélection des dépistages quadrimensuels du cabinet <?php if ($_SESSION['account']->cabinet=='admin') echo "(ou bien de tous les cabinets sauf celui de test)"; ?></li>
  <li>Sélection des examens d'hémoglobine glyquée dont le résultat est strictement positif</li> 
  <li>Exclusion des examens en dehors de la période donnée en paramètre
  <li>Calcul de la <a href="http://jellevy.yellis.net/Classes/2nde/Statistiques/Cours/Cours_statistique.htm#rep10"
 target="_blank">médiane</a> des résultats</li> 
  <li>Exclusion des résultats en deçà de <font size='-1'>(moyenne - ( écart-type &lowast; 20 &divide; grossissement ))</font> ou au delà de <font size='-1'>(moyenne + ( écart-type &lowast; 20 &divide; grossissement ))</font></li> 
  <li>Répartition des résultats en n tranches (n = nombre d'intervalles choisi)</li> 
  <li>Affichage du résultat en histogramme</li> 
</ul>
