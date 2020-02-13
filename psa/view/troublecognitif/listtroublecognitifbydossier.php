<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>
<?php require_once("tools/formulas.php"); ?>

<?php global $account; ?>
<?php global $rowsList; ?>
<?php global $param; ?>
<?php global $signature; ?>
<?php global $TroubleCognitif; ?>



<table width="75%"  border="1" cellspacing="0" cellpadding="0">
 
  <CAPTION  ><?php echo(count($rowsList));?> enregistrements trouvés</CAPTION>
  
  <tr>
    <th scope="col">&nbsp;Dossier</th>
    <th scope="col">&nbsp;Sexe</th>
    <th scope="col">&nbsp;Date de naissance</th>
    <th scope="col">&nbsp;Réponse</th>
	<th scope="col">&nbsp;mmse</th>
	<th scope="col">&nbsp;gds</th>
	<th scope="col">&nbsp;iadl</th>
	<th scope="col">&nbsp;horloge</th>
    <th scope="col">&nbsp;Consulter</th>
  </tr>
  <?php for($i=0;$i<count($rowsList);$i++){    ?>

  <tr>
    <td>&nbsp;<?php echo(getDoubleArrayElement($rowsList,$i,"numero")); ?></td>
    <td>&nbsp;<?php echo($sexe[getDoubleArrayElement($rowsList,$i,"sexe")]); ?></td>
    <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"dnaiss"))); ?></td>
    <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"date"))); ?></td>	
	<td>&nbsp;<?php echo get_mmse($rowsList[$i]); ?></td>
	<td>&nbsp;<?php echo get_gds($rowsList[$i]); ?></td>
	<td>&nbsp;<?php echo get_iadl($rowsList[$i]); ?></td>
	<td>&nbsp;<?php echo $rowsList[$i]['horloge']; ?></td>
    <td>&nbsp;<?php
				 $additionalParams = array("Dossier:dossier:numero"=>getDoubleArrayElement($rowsList,$i,"numero"),
				 						   "TroubleCognitif:TroubleCognitif:date"=>mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"date")));
				 buildLink("","Consulter","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_VIEW),$additionalParams); 
			  ?>
	</td>
  </tr>
  <?php }?>
</table>



