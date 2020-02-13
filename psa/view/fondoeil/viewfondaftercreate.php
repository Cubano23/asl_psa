<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $FondOeil; ?>
<?php global $param; ?>

 
<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("FondOeilControler"); ?>

Les Données ont été enregistrées
<?php require("view/common/dossierresume.php");?>



  <br>
  <table border=1 width='670'>
	<tr>
		<td>Numero de dossier </td>
		<td>&nbsp; <?php echo $dossier->numero; ?></td>
	</tr>
	<tr>
		<td>Date de l'examen </td>
		<td>&nbsp; <?php echo $FondOeil->date; ?></td>
	</tr>
	<tr>
		<td>Oeil</td>
		<td><?php echo $FondOeil->oeil=="G"?"Gauche":"Droit";?></td>
    </tr>
	</tr>
	<tr>
		<td>Fichier:</td>
		<td><?php echo $FondOeil->fichier;?>
		</td>
	</tr>
</table> 

</form>
