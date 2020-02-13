<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $PremiereConsultCardio; ?>
<?php global $param; ?>

 
<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("PremiereConsultCardioControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","PremiereConsultCardio:date"); ?>

<?php require("view/common/dossierresume.php");?>


<br>
	<table border='1'>
	<tr>
		<td width='300'><b>Objectifs retenus pour la 1ère consultation</b></td>
			<td><b>Détail</b></td>
	</tr>
	<?php 
		if($PremiereConsultCardio->objectif_poids=="1"){
		?>
	<tr>
		<td width='300'>Poids <img OnClick="javascript:window.open('objectif_poids.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
			<td><?php echo stripslashes($PremiereConsultCardio->commentaire_obj_poids);?></Td>
	</tr>
	<?php
	}
		if($PremiereConsultCardio->objectif_alcool=="1"){
	?>
	<tr>
		<td width='300'>Alcool <img OnClick="javascript:window.open('objectif_alcool.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</td>
			<td><?php echo stripslashes($PremiereConsultCardio->commentaire_obj_alcool);?></Td>
	</Tr>
	<?php
	}
	
		if($PremiereConsultCardio->objectif_tabac=="1"){
	?>
	<tr>
		<td width='300'>Tabac <img OnClick="javascript:window.open('objectif_tabac.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</Td>
			<td><?php echo stripslashes($PremiereConsultCardio->commentaire_obj_tabac);?></Td>
	</Tr>
	<?php
	}
	
		if($PremiereConsultCardio->objectif_tension=="1"){
	?>
	<tr>
		<td width='300'>Tension <img OnClick="javascript:window.open('objectif_tension.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</td>
			<td><?php echo stripslashes($PremiereConsultCardio->commentaire_obj_tension);?></Td>
	</tr>
	<?php
	}
	?>
	</table>
	<br>
	
	<table border='1'>
	<tr>
		<td><b>Conseils prodigués</b></td>
			<td><b>Documents remis</b></Td>
				<td><b>Commentaire</b></td>
	</tr>
	<?php
	
	if($PremiereConsultCardio->conseil_sel=="1"){
	?>
	<tr>
			<td width='300'>Consommation de sel</td>
			<td><?php	if($PremiereConsultCardio->brochure_sel1=="1"){?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br><?php }?>
				<?php 	if($PremiereConsultCardio->brochure_sel2=="1"){?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><?php }?>
				</td>
					<td><?php echo stripslashes($PremiereConsultCardio->commentaire_sel);?></td>
	</Tr>
	<?php
	}
	
	if($PremiereConsultCardio->conseil_alcool=="1"){
	?>
	<tr>
			<td width='300'>Consommation d'alcool < 2 verres/j pour une femme, 3 verres/j pour un homme</td>
			<td><?php 	if($PremiereConsultCardio->brochure_alcool1=="1"){?>Alcool : votre corps se souvient de tout <img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire"?>/docs/alcool2.pdf', '_blank');" alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br><?php }?>
				<?php 	if($PremiereConsultCardio->brochure_alcool2=="1"){?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><?php }?>
				</td>
					<td><?php echo stripslashes($PremiereConsultCardio->commentaire_alcool);?></td>
	</Tr>
	<?php
	}
	
	if($PremiereConsultCardio->conseil_activite=="1"){
	?>
	<tr>
			<td width='300'>Activité physique (équivalent de 30 min de marche rapide, 3 fois dans la semaine)</td>
			<td><?php 	if($PremiereConsultCardio->brochure_activite1=="1"){?>Brochure "Bouger c'est la santé" <img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire"?>/docs/ATT02695.pdf', '_blank');" alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br><?php }?>
				<?php 	if($PremiereConsultCardio->brochure_activite2=="1"){?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><?php }?>
				</td>
					<td><?php stripslashes($PremiereConsultCardio->commentaire_activite);?></td>
	</Tr>
	<?php
	}
	
	if($PremiereConsultCardio->conseil_tabac=="1"){
	?>
	<tr>
			<td width='300'>Sevrage tabagique</td>
				<td><?php 	if($PremiereConsultCardio->brochure_tabac1=="1"){?>La dépendance au tabac <img OnClick="window.open('<?php echo "$path/view/cardiovasculaire"?>/docs/ATT02697.pdf', '_blank');" alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br><?php }?>
					<?php 	if($PremiereConsultCardio->brochure_tabac2=="1"){?>Les risques du tabagisme et les bénéfices de l'arrêt <img OnClick="window.open('<?php echo "$path/view/cardiovasculaire"?>/docs/risque_tabac.pdf', '_blank');" alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><?php }?>
					</td>
					<td><?php echo stripslashes($PremiereConsultCardio->commentaire_tabac);?></td>
	</Tr>
	<?php
	}
	
	if($PremiereConsultCardio->conseil_poids=="1"){
	?>
	<tr>
			<td width='300'>Contrôle du poids</td>
				<td><?php 	if($PremiereConsultCardio->brochure_poids1=="1"){?>Pense-bête nutrition <img OnClick="window.open('<?php echo "$path/view/cardiovasculaire"?>/docs/ATT02699.pdf', '_blank');" alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br><?php }?>
					<?php 	if($PremiereConsultCardio->brochure_poids2=="1"){?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><?php }?>
					</td>
					<td><?php echo stripslashes($PremiereConsultCardio->commentaire_poids);?></td>
	</Tr>
	<?php
	}
	
	if($PremiereConsultCardio->conseil_alim=="1"){
	?>
	<tr>
			<td width='300'>Alimentation riche en fruits et légumes</td>
				<td><?php 	if($PremiereConsultCardio->brochure_alim1=="1"){?>La santé vient en mangeant <img OnClick="window.open('<?php echo "$path/view/cardiovasculaire"?>/docs/ATT02701.pdf', '_blank');" alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br><?php }?>
					<?php 	if($PremiereConsultCardio->brochure_alim2=="1"){?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><?php }?>
					</td>
					<td><?php echo stripslashes($PremiereConsultCardio->commentaire_alim);?></td>
	</Tr>
	<?php
	}
	
	if($PremiereConsultCardio->conseil_cafe=="1"){
	?>
	<tr>
			<td width='300'>Diminution des excitants (café, thé, réglisse)</td>
				<td><?php 	if($PremiereConsultCardio->brochure_cafe1=="1"){?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><br><?php }?>
					<?php 	if($PremiereConsultCardio->brochure_cafe2=="1"){?>... <img alt="Impression personnalisée" border=0 height="15" src='<?php echo "$path/view/"?>images/imprimer.gif' width="15"><?php }?>
					</td>
					<td><?php echo stripslashes($PremiereConsultCardio->commentaire_cafe);?></td>
	</Tr>
	<?php
	}
	?>
	</table>
	<br>
	<b>Bilan de consultation</b>
  <table width='850' border="1" cellpadding='3'>
    <tr>
      <td>Degré de satisfaction:</td>
      <td><?php echo($satisfaction[getPropertyValue("PremiereConsultCardio:degre_satisfaction")]) ?></td>
    </tr>
    <tr>
      <td>Durée approximative en minutes ("à 5 minutes près")</td>
      <td><?php echo $PremiereConsultCardio->duree; ?></td>
    </tr>
    <tr>
      <td valign='top'>Points positifs:</td>
      <td  width='70%'><?php echo nl2br(stripslashes($PremiereConsultCardio->points_positifs)); ?></td>
    </tr>
    <tr>
      <td valign='top'>Points à améliorer:</td>
      <td  width='70%'><?php echo nl2br(stripslashes($PremiereConsultCardio->points_ameliorations)); ?></td>
    </tr>
  </table>
  
  <br><br>


<table border="0">
  <tr>
    <td> 
		<?php customSubmitWithAlert("value='Supprimer cette consultation'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer cette consultation ?"); ?>
	 </td> 
    <td> <?php customSubmit("value='Modifier cette consultation'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
         <?php customSubmit("value='Faire une autre consultation'",ACTION_MANAGE,"",$param->controler); ?></td>
  </tr> 
</table> 

</form>
