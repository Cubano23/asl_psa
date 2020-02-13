 <tr align='center' valign='baseline'> 
  <td>&nbsp;<?php echo($dossier->numero); ?></td> 
  <td>&nbsp;<?php echo($sexe[$dossier->sexe]); ?></td> 
  <td>&nbsp;<?php echo($dossier->dnaiss); ?></td> 
  	<?php if($suiviDiabete->suivi_type[$j] == "4") { ?><td><font color='blue'>4 mois</font></td> <?php }?>
  	<?php if(($suiviDiabete->suivi_type[$j] == "s") || /* { ?><td><font color='green'>semestriel</font></td> <?php }?>
  	<?php if */($suiviDiabete->suivi_type[$j] == "a")) { ?><td><font color='red'>annuel</font></td> <?php }?>
  <td><?php echo($dateRef); ?></td> 
<!--  <td valign='bottom'>&nbsp;
  		<?php
/*			$additionalParams = array("Dossier:dossier:numero"=>"$dossier->numero",
									"SuiviDiabete:suiviDiabete:dsuivi"=>"$suiviDiabete->dsuivi");
			buildLink("","Modifier","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_EDIT),$additionalParams);
*/		?>
  </td>
  	
  </td> -->
</tr> 
