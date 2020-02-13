<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $diageduc; ?>
<?php global $param; ?>

 
<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("diageducControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","diageduc:date"); ?>

<?php require("view/common/dossierresume.php");?>


<br>
	<table border='1'>
	<tr>
		<td width='300'><b>Objectifs retenus pour la 1ère consultation</b></td>
			<td><b>Détail</b></td>
	</tr>
	<?php 
		if($diageduc->objectif_poids=="1"){
		?>
	<tr>
		<td width='300'>Poids <img OnClick="javascript:window.open('objectif_poids.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
			<td><?php echo stripslashes($diageduc->commentaire_obj_poids);?></Td>
	</tr>
	<?php
	}
		if($diageduc->objectif_alcool=="1"){
	?>
	<tr>
		<td width='300'>Alcool <img OnClick="javascript:window.open('objectif_alcool.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</td>
			<td><?php echo stripslashes($diageduc->commentaire_obj_alcool);?></Td>
	</Tr>
	<?php
	}
	
		if($diageduc->objectif_tabac=="1"){
	?>
	<tr>
		<td width='300'>Tabac <img OnClick="javascript:window.open('objectif_tabac.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</Td>
			<td><?php echo stripslashes($diageduc->commentaire_obj_tabac);?></Td>
	</Tr>
	<?php
	}
	
		if($diageduc->objectif_tension=="1"){
	?>
	<tr>
		<td width='300'>Tension <img OnClick="javascript:window.open('objectif_tension.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</td>
			<td><?php echo stripslashes($diageduc->commentaire_obj_tension);?></Td>
	</tr>
	<?php
	}
	?>
	</table>
  
  <br><br>


<table border="0">
  <tr>
    <td> 
		<?php customSubmitWithAlert("value='Supprimer ce diagnostic'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer cette consultation ?"); ?>
	 </td> 
    <td> <?php customSubmit("value='Modifier ce diagnostic'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
         <?php customSubmit("value='Faire un autre diagnostic'",ACTION_MANAGE,"",$param->controler); ?></td>
  </tr> 
</table> 

</form>
