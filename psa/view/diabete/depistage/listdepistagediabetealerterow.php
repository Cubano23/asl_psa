<tr align='center' valign='baseline'>
  <td>&nbsp;<?php echo($dossier->numero); ?></td>
  <td>&nbsp;<?php echo($sexe[$dossier->sexe]); ?></td>
  <td>&nbsp;<?php echo($dossier->dnaiss); ?></td>
  <td>&nbsp;<?php /*if(compare($depistageDiabete->nouvelle_gly_date,$depistageDiabete->derniere_gly_date)>0)
  						echo($depistageDiabete->nouvelle_gly_date);
				  else*/
					  	echo($depistageDiabete->derniere_gly_date);?></td>
  <td><?php echo($dateRef); ?></td>
<!--  <td valign='bottom'>&nbsp;
  		<?php
/*			$additionalParams = array("Dossier:dossier:numero"=>"$dossier->numero",
									"depistageDiabete:depistageDiabete:date"=>"$depistageDiabete->date");
			buildLink("","Modifier","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_EDIT),$additionalParams);
*/		?>
  </td>

  </td>-->
</tr>
