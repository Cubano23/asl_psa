<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $dossier; ?>
<?php global $TroubleCognitif; ?>
<?php global $param; ?>
 
<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler(""); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","TroubleCognitif:date"); ?>

<?php require("view/common/dossierresume.php");?>

  <b>Type de dépistage:</b><br>
  <table border=1>
<?php if($TroubleCognitif->dep_type=='coll'){?>

    <tr>
    	<td colspan='2'>Dépistage collectif</td>
	<tr>
		<td valign='top'>Date Rappel : </td><td valign='top'> <?php echo ($TroubleCognitif->date_rappel);?></td>
    </tr>
    </tr>
    <?php }

else
{?>
    <tr>
      <td valign='top' colspan="2">Dépistage individuel</td></tr>
      <tr>
	  		<td valign="top">Raison du dépistage : </td><td> <?php echo $TroubleCognitif->raison_dep ?></td>
		</tr>
	<tr>
	<td valign='top'>Date Rappel : </td><td valign='top'> <?php echo ($TroubleCognitif->date_rappel);?></td>
    </tr>
<?php }
?>  </table>
<br><br>
<?php

 if(in_array('mmse',$TroubleCognitif->suivi_type)) {?>
    <table border='1' width='100%' align='center'>
      <caption>
  <big><b><font color='blue'>MMSE</font></b></big>
  </caption>

        <tr>
          <td width="20%">Score :
          </td>
          <td><?php echo $TroubleCognitif->get_mmse(); ?></td>
  </tr>
</table>
<?php }

?>
<br><br>
<?php

 if(in_array('gds',$TroubleCognitif->suivi_type)) {?>
    <table border='1' width='100%' align='center'>
      <caption>
  <big><b><font color='green'>GDS</font></b></big>
  </caption>

        <tr>
          <td width="20%">Score :
          </td>
          <td><?php echo $TroubleCognitif->get_gds(); ?></td>
  </tr>
</table>
<?php }

?>
<br><br>
<?php

 if(in_array('iadl',$TroubleCognitif->suivi_type)) {?>
    <table border='1' width='100%' align='center'>
      <caption>
  <big><b><font color='#FF00FF'>IADL</font></b></big>
  </caption>

        <tr>
          <td width="20%">Score :
          </td>
          <td><?php echo $TroubleCognitif->get_iadl(); ?></td>
  </tr>
</table>
<?php }

?>
<br><br>

<?php if(in_array('horl',$TroubleCognitif->suivi_type)) {?>
    <table border='1' width='100%' align='center'> 
      <caption>
  <big><b><font color='brown'>Test de l'horloge</font></b></big>
  </caption>

        <tr>
          <td width="20%">Score :
          </td>
          <td><?php echo($TroubleCognitif->horloge); ?></td>
  </tr> 
</table> 
<?php }


?>

<table border="0" width='100%' align="center">
  <tr>
    <td>
        <?php customSubmitWithAlert("value='Supprimer ce suivi'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer ce suivi ?"); ?>
	</td>
    <td>
        <?php customSubmit("value='Modifier ce suivi'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
        <?php customSubmit("value='Faire un autre suivi'",ACTION_MANAGE,array(PARAM_PRE_CREATE),$param->controler); ?>

    </td>
  </tr>
</table>
</form>
