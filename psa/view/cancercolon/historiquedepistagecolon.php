 <?php 	global $DepistageCancerColonList; ?>
 <?php global $dossier ?>
 <?php
 $dysplasie=Array("aucun"=>'Pas de dysplasie', 'bas'=>'Dysplasie de bas grade', 'haut'=>'Dysplasie de haut grade',
                    'cancer'=>'Cancer du colon');
 ?>
 <table border=1>
  <tr align='center'> 
    <th>Date</td>
    <th>Réponses</td>
  </tr>
  </tr>
  <?php 

    for($j=0;$j<count($DepistageCancerColonList);$j++){
        
  		$tmphisto = $DepistageCancerColonList[$j]; ?>
	
	  <tr>
	    <td>
	        <?php echo $tmphisto->date;?>
		</td>
		<td><a href='#colon_<?php echo $tmphisto->date; ?>' onclick="affiche_detail('colon<?php echo $tmphisto->date; ?>')">Afficher/masquer les détails</a>
	  <tr style="display:none" id="colon<?php echo $tmphisto->date; ?>">
    <td>
		<?php echo $tmphisto->date; /*$additionalParams = array("Dossier:dossier:id"=>$tmphisto->id,
						"DepistageCancerSein:DepistageCancerSein:date"=>$tmphisto->date);
				buildLink("target='_blank'",$tmphisto->date,"$path/controler/ActionControler.php","DepistageCancerSeinControler",ACTION_FIND,array(PARAM_VIEW,PARAM_SYSTEMATIQUE),$additionalParams);*/
		?>
		&nbsp;
	</td> 
	<td>
    <table width="500" >
        <tr>
          <th width='400'>Question</th>
          <th>Réponse(s)</th>
        </tr>
        <tr>
          <th  align="left" scope="row">Antécédents:</th>
		  <td  align="left" scope="row">&nbsp;</td>
        </tr>

        <tr <?php echo($tmphisto->ant_pere_type[0] == "aucun"?"style='display:none'":""); ?> >
          <td valign='top'>père</td>
          <td><?php /*echo($antFam["$depistageCancerColon->ant_pere_type"]); */
					for($i=0;$i<count($tmphisto->ant_pere_type);$i++){
						echo($tmphisto->ant_pere_type[$i]);
						echo("<br>");
					}
		  			?> <?php if(!empty($tmphisto->ant_pere_age)) {?> à l'age de <?php  echo $tmphisto->ant_pere_age; ?> ans <?php }?></td>
        </tr>
        <tr <?php echo($tmphisto->ant_mere_type[0] == "aucun"?"style='display:none'":""); ?> >
          <td valign='top'>mère</td>
          <td><?php /*echo($antFam["$depistageCancerColon->ant_mere_type"])*/
		  			for($i=0;$i<count($tmphisto->ant_mere_type);$i++){
						echo($tmphisto->ant_mere_type[$i]);
						echo("<br>");
					}
			 ?><?php if(!empty($tmphisto->ant_mere_age)) {?> à l'age de <?php  echo $tmphisto->ant_mere_age; ?> ans <?php }?></td>
        </tr>
        <tr <?php echo($tmphisto->ant_fratrie_type[0] == "aucun"?"style='display:none'":""); ?>>
          <td valign='top'>frère ou s&oelig;ur</td>
          <td><?php /*echo($antFam["$depistageCancerColon->ant_fratrie_type"])*/
					for($i=0;$i<count($tmphisto->ant_fratrie_type);$i++){
						echo($tmphisto->ant_fratrie_type[$i]);
						echo("<br>");
					}

		   ?><?php if(!empty($tmphisto->ant_fratrie_age)) {?> à l'age de <?php  echo $tmphisto->ant_fratrie_age; ?> ans <?php }?></td>
        </tr>
        <tr <?php echo($tmphisto->ant_collat_type[0] == "aucun"?"style='display:none'":""); ?>>
          <td valign='top'>oncle ou tante</td>
          <td><?php /*echo($antFam["$depistageCancerColon->ant_collat_type"])*/
					for($i=0;$i<count($tmphisto->ant_collat_type);$i++){
						echo($tmphisto->ant_collat_type[$i]);
						echo("<br>");
					}


		   ?><?php if(!empty($tmphisto->ant_collat_age)) {?> à l'age de <?php  echo $tmphisto->ant_collat_age; ?> ans <?php }?></td>
        </tr>
        <tr <?php echo($tmphisto->ant_enfants_type[0] == "aucun"?"style='display:none'":""); ?>>
          <td valign='top'>enfant</td>
          <td><?php /*echo($antFam["$depistageCancerColon->ant_enfants_type"])*/
					for($i=0;$i<count($tmphisto->ant_enfants_type);$i++){
						echo($tmphisto->ant_enfants_type[$i]);
						echo("<br>");
					}


		   ?><?php if(!empty($tmphisto->ant_enfants_age)) {?> à l'age de <?php  echo $tmphisto->ant_enfants_age; ?> ans <?php }?></td>
        </tr>
        <tr>
          <th  align="left" scope="row">Justification du dépistage:</th>
		  <td  align="left" scope="row">&nbsp;</td>
        </tr>
		<tr  <?php if (empty($tmphisto->just_ant_fam)) echo "style='display:none'"; ?> >
          <td valign='top'>Antécédent familiaux</td>
          <td>oui</td>
        </tr>
        <tr  <?php if (empty($tmphisto->just_ant_polype)) echo "style='display:none'"; ?> >
          <td valign='top'>Antécédent personnel de polype</td>
          <td>oui</td>
        </tr>
		<tr  <?php if (empty($tmphisto->just_ant_cr_colique)) echo "style='display:none'"; ?> >
          <td valign='top'>Antécédent personnel de cancer colique</td>
          <td>oui</td>
        </tr>
		<tr  <?php if (empty($tmphisto->just_ant_sg_selles)) echo "style='display:none'"; ?> >
          <td valign='top'>Sang dans les selles</td>
          <td>oui</td>
        </tr>
        <tr>
          <th  align="left" scope="row">Coloscopie:</th>
		  <td  align="left" scope="row">&nbsp;</td>
        </tr>
		<tr <?php echo($tmphisto->colos_date == ""?"style='display:none'":""); ?> >
			<td>Date</td>
			<td><?php echo($tmphisto->colos_date); ?></td>
		</tr>
		<tr <?php echo($tmphisto->colos_polypes == ""?"style='display:none'":""); ?> >
			<td>Présence de polypes</td>
			<td><?php echo($tmphisto->colos_polypes);?></td>
		</tr>
		<tr <?php echo($tmphisto->colos_dysplasie == ""?"style='display:none'":""); ?> >
			<td>Dysplasie</td>
			<td><?php echo($dysplasie[$tmphisto->colos_dysplasie]); ?></td>
		</tr>
        <tr>
          <th  align="left" scope="row">Rappel coloscopie:</th>
		  <td  align="left" scope="row">&nbsp;</td>
        </tr>

        <tr>
          <td>&nbsp;</td>
          <td><?php echo($tmphisto->rappel_colos_period == 0 ?"pas de rappel":$tmphisto->rappel_colos_period."an(s)"); ?></td>
        </tr>
      </table>
      </td>
  </tr>
  <?php } ?>
</table>



			
			
