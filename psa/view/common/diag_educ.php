<?php global $dossier;?>
<?php global $currentObjectName;?>
<?php global $$currentObjectName;?>
<?php global $account;?>

<h1><?php echo $item."- ";?>Diagnostic éducatif - synthèse</h1>
<?php echo "<a href='javascript://' onclick=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=EvaluationInfirmierControler&controlerparams:param:action=AL&controlerparams:param:param1=PLISDOSSTIP2&Dossier:dossier:numero=$dossier->numero',this);return false\">Consultations passées </a><br>";
  ?>
 <table width='880' border="1" cellpadding='3'>
    <tr>
      <td valign='top'>Aspects limitants:</td>
      <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","$currentObjectName:aspects_limitant"); ?></td>
	</Tr>
    <tr>
      <td valign='top'>Aspects facilitants:</td>
      <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","$currentObjectName:aspects_facilitant"); ?></td>
	</Tr>
    <tr>
      <td valign='top'>Objectifs du patient:</td>
      <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","$currentObjectName:objectifs_patient"); ?></td>
	</Tr>
	</table>
	<br>

<br><br>
