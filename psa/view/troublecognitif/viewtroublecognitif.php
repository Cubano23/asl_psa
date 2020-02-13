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
<br>

				
  <br> 
  <b>Saisie d'un dépistage au <?php echo($TroubleCognitif->date); ?></b><br>
  <table border=0 cellspacing=14> 
	  <tr> 
		<td>Type de suivi</td> 
		<td colspan=2>
		<?php 
			if(count ($TroubleCognitif->suivi_type) == 0) echo("Aucun");
			else{

				if(in_array("mmse",$TroubleCognitif->suivi_type)) {?>	<font color="blue">MMSE</font> <?php }
				if(in_array("gds",$TroubleCognitif->suivi_type)) {?> <font color="green">GDS</font> <?php }
				if(in_array("iadl",$TroubleCognitif->suivi_type)) {?> <font color="#FF00FF">IADL</font> <?php }
				if(in_array("horl",$TroubleCognitif->suivi_type)) {?> <font color="brown">Horloge</font> <?php }
			}
		?>
		</td> 
	  </tr> 
  </table> 
  
	  <?php require("view/troublecognitif/viewtroublecognitifsystematique.php"); ?><br><br>
	  <?php if(in_array("mmse",$TroubleCognitif->suivi_type)) require("view/troublecognitif/viewtroublecognitifmmse.php"); ?>
	  <?php if(in_array("gds",$TroubleCognitif->suivi_type)) require("view/troublecognitif/viewtroublecognitifgds.php"); ?>
	  <?php if(in_array("iadl",$TroubleCognitif->suivi_type)) require("view/troublecognitif/viewtroublecognitifiadl.php"); ?>
	  <?php if(in_array("horl",$TroubleCognitif->suivi_type)) require("view/troublecognitif/viewtroublecognitifhorl.php"); ?>


<table border="0" width='100%' align="center">
  <tr>
    <td>
        <?php customSubmitWithAlert("value='Supprimer ce suivi'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer ce suivi ?"); ?>
	</td>
    <td>
        <?php customSubmit("value='Modifier ce suivi'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
 
    </td>
  </tr>
</table>

</form> 
