<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $depistageCancerSein; ?>
<?php global $param; ?>

<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("DepistageCancerSeinControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","depistageCancerSein:date"); ?>

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
          <th align="left" scope="row" colspan=2>Dépistage sein</th> 
        </tr> 
        <tr> 
          <th align="left" scope="row" colspan=2>Antécédents familiaux</th> 
        </tr> 
        <tr <?php echo($depistageCancerSein->ant_fam_mere=="1"?"":"style='display:none'"); ?> >
          <td>&nbsp;</td> 
          <td>mère</td> 
        </tr> 
        <tr <?php echo($depistageCancerSein->ant_fam_soeur=="1"?"":"style='display:none'"); ?>>
          <td>&nbsp;</td> 
          <td>s&oelig;ur</td> 
        </tr> 
        <tr <?php echo($depistageCancerSein->ant_fam_fille=="1"?"":"style='display:none'"); ?>>
          <td>&nbsp;</td> 
          <td>fille</td> 
        </tr> 
        <tr <?php echo($depistageCancerSein->ant_fam_tante=="1"?"":"style='display:none'"); ?>>
          <td>&nbsp;</td> 
          <td>tante</td> 
        </tr> 
        <tr <?php echo($depistageCancerSein->ant_fam_grandmere=="1"?"":"style='display:none'"); ?>>
          <td>&nbsp;</td> 
          <td>grand-mère</td> 
        </tr> 
        <tr> 
          <th align="left" scope="row" colspan=2>Type de dépistage:</th>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><?php echo($depistageCancerSein->dep_type=="indiv"?"Individuel":"Collectif");
				?></td>
        </tr> 
        <tr> 
          <th valign='top'>Date de mammographie</th> 
          <td><?php echo($depistageCancerSein->mamograph_date); ?>&nbsp;</td> 
        </tr> 
        <tr>
          <th valign='top' align='left'>Date de rappel</th>
          <td><?php echo($depistageCancerSein->rappel_mammographie); ?>&nbsp;</td>
        </tr>
      </table>
      <br>
      <table border="1" <?php echo($depistageCancerSein->sortir_rappel=="1"?"":"style='display:none'")?>>
        <tr>
			<td colspan="2"><b>Pas de nouvelle mammographie</b></td>
		</tr>
		<tr>
		    <td><b>Raison</b></td>
		        <td><?php echo $depistageCancerSein->raison_sortie;?></td>
		</tr>
		</table>
		<br>
	  </td> 
  </tr> 
  <tr> 
    <td> <?php customSubmitWithAlert("value='Supprimer ce dépistage'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer cette réponse ?"); ?> </td> 
    <td> <?php customSubmit("value='Modifier ce dépistage'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
         <?php customSubmit("value='Faire un autre suivi'",ACTION_MANAGE,"",$param->controler); ?>

	</td> 
  </tr> 
</table> 
</form>
