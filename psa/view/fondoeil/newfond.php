<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $FondOeil; ?>


<script type="text/javascript" >


<?php
	compareDates();
	dateInRange();
	validateDate();	
	validatePositiveNumeric();
	validateNumeric();
	
	$js = new JSValidation();
	
	$js->startCheckFunction("validateInput","saveForm");
?>
	


<?php
	$js->endCheckFunction();
?>

</script>

<form enctype="multipart/form-data" action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'>
	<?php hiddenControler("FondOeilControler"); ?>
	<?php hiddenAction(ACTION_SAVE); ?>
<input type="hidden" name="MAX_FILE_SIZE" value="10000000">



  <br>
  <table border=1 width='700'>
	<tr>
		<td>Numero de dossier </td>
		<td>&nbsp; <?php text("size='10'","dossier:numero"); ?></td>
	</tr>
	<tr>
		<td>Date de l'examen </td>
		<td>&nbsp; <?php text("size='10'","FondOeil:date"); ?></td>
	</tr>
	<tr>
		<td>Oeil</td>
		<td><?php radioButton("id='oeilgauche'","FondOeil:oeil","G"); ?>Gauche &nbsp;&nbsp;&nbsp;&nbsp;
		<?php radioButton("id='oeildroit'","FondOeil:oeil","D"); ?>Droit</td>
    </tr>
	</tr>
	<tr>
		<td>Fichier:</td>
		<td><input type="file" name="fichier_joint">
		</td>
	</tr>
  </table>
  <br>


  <br><br>
  <input type='button' value='Valider la saisie' onClick="validateInput();">
  <input type='reset' value='Recommencer'> 
</form> 

