<tr align='center' valign='baseline'>
  <td>&nbsp;<?php echo($dossier->numero); ?></td>
  <td>&nbsp;<?php echo($sexe[$dossier->sexe]); ?></td>
  <td>&nbsp;<?php echo($dossier->dnaiss); ?></td>
  <td>&nbsp;<?php echo($depistageCancerUterus->date_frottis);?></td>
  <td><?php echo($dateRef); ?></td>
<!--  <td valign='bottom'>&nbsp;
  		<?php
/*			$additionalParams = array("Dossier:dossier:numero"=>"$dossier->numero",
									"depistageCancerUterus:depistageCancerUterus:date"=>"$depistageCancerUterus->date");
			buildLink("","Modifier","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_EDIT),$additionalParams);
*/		?>
  </td>

  </td>-->
</tr>
