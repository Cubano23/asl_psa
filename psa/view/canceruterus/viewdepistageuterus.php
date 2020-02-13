<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $depistageCancerUterus; ?>
<?php global $param; ?>

<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("DepistageCancerUterusControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","depistageCancerUterus:date"); ?>

<?php require("view/common/dossierresume.php");?>
	
<table  width='100%' align='center'> 
  <tr> 
    <td valign='top'>&nbsp; </td> 
    <td> 
      <table border='1' cellpadding='3'>
        <tr>
          <th>Question</th>
          <th>Réponse(s)</th>
        </tr>
        <tr>
          <th align="left" scope="row" colspan=2>Dépistage du cancer du col de l'utérus</th>
        </tr>
        <tr>
          <th align="left" scope="row">Date du frottis</th>
	          <td><?php echo ($depistageCancerUterus->date_frottis);?>&nbsp;</td>
        </tr>
        <tr>
          <th align="left" scope="row">Frottis : </th>
          <td><?php echo($depistageCancerUterus->frottis_normal=="oui"?"Normal":"Anormal");
				?>&nbsp;</td>
        </tr>
		<?php
        if ($depistageCancerUterus->frottis_normal=="non")
        {
        ?>
        <tr>
          <th align="left" scope="row">Avis du médecin : </th>
          <td><?php echo($depistageCancerUterus->avis_medecin); ?>&nbsp;</td>
        </tr>
        <?php
        }
  		?>
        <tr>
          <th align='left' scope="row">Date de rappel</th>
          <td><?php echo($depistageCancerUterus->date_rappel); ?>&nbsp;</td>
        </tr>
      </table>
      <br>
      <table border="1" <?php echo($depistageCancerUterus->sortir_rappel=="1"?"":"style='display:none'")?>>
        <tr>
			<td colspan="2"><b>Pas de nouveau dépistage</b></td>
		</tr>
		<tr>
		    <td><b>Raison</b></td>
		        <td><?php echo $depistageCancerUterus->raison_sortie;?></td>
		</tr>
		</table>
		<br>
	  </td>
  </tr> 
  <tr> 
    <td> <?php customSubmitWithAlert("value='Supprimer ce dépistage'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer cette réponse ?"); ?> </td> 
    <td> <?php customSubmit("value='Modifier ce dépistage'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?></td> 
  </tr> 
</table> 
</form>
