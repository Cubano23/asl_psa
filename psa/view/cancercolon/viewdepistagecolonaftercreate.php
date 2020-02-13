<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php
 $dysplasie=Array("aucun"=>'Pas de dysplasie', 'bas'=>'Dysplasie de bas grade', 'haut'=>'Dysplasie de haut grade',
                    'cancer'=>'Cancer du colon');
?>
<?php global $account ?>
<?php global $dossier; ?>
<?php global $depistageCancerColon; ?>
<?php global $param; ?>

 
<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("DepistageCancerColonControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","depistageCancerColon:date"); ?>

<?php require("view/common/dossierresume.php");?>

<table border='0' width='100%' align='center'> 
  <tr> 
    <td valign='top'>&nbsp; </td> 
    <td> <table border='1' cellpadding='3'> 
        <tr> 
          <th>Question</th> 
          <th>R�ponse(s)</th> 
        </tr> 
        <tr> 
          <th  align="left" scope="row" colspan=2>D�pistage colon</th> 
        </tr> 
        <tr> 
          <th  align="left" scope="row">Ant�c�dents:</th> 
		  <td  align="left" scope="row">&nbsp;</td>
        </tr> 
        
        <tr <?php if(!isset($depistageCancerColon->ant_pere_type[0])||($depistageCancerColon->ant_pere_type[0] == "aucun"))
						echo "style='display:none'";
				  else echo ""; ?> >
          <td valign='top'>p�re</td> 
          <td><?php /*echo($antFam["$depistageCancerColon->ant_pere_type"]); */
					for($i=0;$i<count($depistageCancerColon->ant_pere_type);$i++){
						echo($depistageCancerColon->ant_pere_type[$i]);
						echo("<br>");
					}
		  			?> <?php if(!empty($depistageCancerColon->ant_pere_age)) {?> � l'age de <?php  typePropertyValue("depistageCancerColon:ant_pere_age"); ?> ans <?php }?></td>
        </tr> 
        <tr <?php if(!isset($depistageCancerColon->ant_mere_type[0])||($depistageCancerColon->ant_mere_type[0] == "aucun"))
						echo "style='display:none'";
				  else echo ""; ?> >
          <td valign='top'>m�re</td> 
          <td><?php /*echo($antFam["$depistageCancerColon->ant_mere_type"])*/
		  			for($i=0;$i<count($depistageCancerColon->ant_mere_type);$i++){
						echo($depistageCancerColon->ant_mere_type[$i]);
						echo("<br>");
					}
			 ?><?php if(!empty($depistageCancerColon->ant_mere_age)) {?> � l'age de <?php  typePropertyValue("depistageCancerColon:ant_mere_age") ?> ans <?php }?></td>
        </tr> 
        <tr <?php if(!isset($depistageCancerColon->ant_fratrie_type[0])||($depistageCancerColon->ant_fratrie_type[0] == "aucun"))
						echo "style='display:none'";
				  else echo ""; ?> >
          <td valign='top'>fr�re ou s&oelig;ur</td> 
          <td><?php /*echo($antFam["$depistageCancerColon->ant_fratrie_type"])*/
					for($i=0;$i<count($depistageCancerColon->ant_fratrie_type);$i++){
						echo($depistageCancerColon->ant_fratrie_type[$i]);
						echo("<br>");
					}

		   ?><?php if(!empty($depistageCancerColon->ant_fratrie_age)) {?> � l'age de <?php  typePropertyValue("depistageCancerColon:ant_fratrie_age") ?> ans <?php }?></td>
        </tr> 
       <!-- <tr --><?php /*echo($depistageCancerColon->ant_collat_type[0] == "aucun"?"style='display:none'":"");*/ ?><!--
          <td valign='top'>oncle ou tante</td>
          <td>--><?php /*echo($antFam["$depistageCancerColon->ant_collat_type"])*/
				/*	for($i=0;$i<count($depistageCancerColon->ant_collat_type);$i++){
						echo($depistageCancerColon->ant_collat_type[$i]);
						echo("<br>");
					}
*/

		   ?><?php /*if(!empty($depistageCancerColon->ant_collat_age)) {?> � l'age de <?php  typePropertyValue("depistageCancerColon:ant_collat_age")*/ ?><!-- ans --><?php /*}*/?><!--</td>
        </tr> -->
        <tr <?php if(!isset($depistageCancerColon->ant_enfants_type[0])||($depistageCancerColon->ant_enfants_type[0] == "aucun"))
						echo "style='display:none'";
				  else echo ""; ?> >
          <td valign='top'>enfant</td> 
          <td><?php /*echo($antFam["$depistageCancerColon->ant_enfants_type"])*/
					for($i=0;$i<count($depistageCancerColon->ant_enfants_type);$i++){
						echo($depistageCancerColon->ant_enfants_type[$i]);
						echo("<br>");
					}


		   ?><?php if(!empty($depistageCancerColon->ant_enfants_age)) {?> � l'age de <?php  typePropertyValue("depistageCancerColon:ant_enfants_age") ?> ans <?php }?></td>
        </tr> 		
        <tr> 
          <th  align="left" scope="row">Justification du d�pistage:</th> 
		  <td  align="left" scope="row">&nbsp;</td>
        </tr> 
		<tr  <?php if (empty($depistageCancerColon->just_ant_fam)) echo "style='display:none'"; ?> >
          <td valign='top'>Ant�c�dent familiaux</td> 
          <td>oui</td> 
        </tr> 
        <tr  <?php if (empty($depistageCancerColon->just_ant_polype)) echo "style='display:none'"; ?> >
          <td valign='top'>Ant�c�dent personnel de polype</td> 
          <td>oui</td> 
        </tr> 
		<tr  <?php if (empty($depistageCancerColon->just_ant_cr_colique)) echo "style='display:none'"; ?> >
          <td valign='top'>Ant�c�dent personnel de cancer colique</td> 
          <td>oui</td> 
        </tr> 
		<tr  <?php if (empty($depistageCancerColon->just_ant_sg_selles)) echo "style='display:none'"; ?> >
          <td valign='top'>Sang dans les selles</td> 
          <td>oui</td> 
        </tr> 
        <tr> 
          <th  align="left" scope="row">Coloscopie:</th> 
		  <td  align="left" scope="row">&nbsp;</td>
        </tr> 
		<tr <?php echo($depistageCancerColon->colos_date == ""?"style='display:none'":""); ?> >
			<td>Date</td>
			<td><?php echo($depistageCancerColon->colos_date); ?></td>
		</tr>
		<tr <?php echo($depistageCancerColon->colos_polypes == ""?"style='display:none'":""); ?> >
			<td>Pr�sence de polypes</td>
			<td><?php echo($depistageCancerColon->colos_polypes=='1'?'Oui':'Non');?></td>
		</tr>
		<tr <?php echo($depistageCancerColon->colos_dysplasie == ""?"style='display:none'":""); ?> >
			<td>Dysplasie</td>
			<td><?php echo($dysplasie[$depistageCancerColon->colos_dysplasie]); ?></td>
		</tr>	
        <tr> 
          <th  align="left" scope="row">Rappel coloscopie:</th> 
		  <td  align="left" scope="row">&nbsp;</td>
        </tr> 
		
        <tr> 
          <td>&nbsp;</td> 
          <td><?php echo($depistageCancerColon->rappel_colos_period == 0 ?"pas de rappel":$depistageCancerColon->rappel_colos_period."an(s)"); ?></td> 
        </tr> 
      </table> 
      <br></td> 
  </tr> 
  <tr> 
    <td> 
		<?php customSubmitWithAlert("value='Supprimer ce d�pistage'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous r�ellement supprimer cette r�ponse ?"); ?>
	 </td> 
    <td> <?php customSubmit("value='Modifier ce d�pistage'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
         <?php customSubmit("value='Faire un autre suivi'",ACTION_MANAGE,"",$param->controler); ?></td>
  </tr> 
</table> 

</form>
