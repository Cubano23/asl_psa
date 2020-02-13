<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $FicheCabinet; ?>
<?php global $param;?>

<script type="text/javascript" >
<?php

	validatePositiveInteger();

	$js = new JSValidation();
	$js->startCheckFunction("validateInput","saveForm");
	$js->endCheckFunction();
	
?>

</script>

<br> 
<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'> 
  <?php hiddenControler("PoserQuestionControler"); ?>
  <?php hiddenAction(ACTION_SAVE); ?>
  <?php hiddenParamN($param->param3,3); ?>

	
Vous souhaitez une évolution particulière,<br>
Vous rencontrez un problème technique,<br>
Vous avez un problème d'utilisation<br>
Envoyez-nous un message, nous vous re-contacterons dans les plus bref délais

<div align='center'><br><br><br>


<table border='0'>
<tr>
	<td>Sujet : </td>
	    <td><?php text("size='80' ","PoserQuestion:titre"); ?></td>
</tr>
<tr>
	<td valign="top">Question : </td>
		<td><?php textArea("rows=\"10\" cols=\"60\"","PoserQuestion:corps"); ?></td>
</tr>
<tr>
	<td><br></td>
</tr>
<tr>
	<td colspan="2" align="center">
  <input type='button' value='Valider' onClick="validateInput()">
  <input type='reset' value='Recommencer'>
	</td>
</tr>
</form>
</table>
</div>

</form> 
