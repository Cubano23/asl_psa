
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier;?>
<?php global $tensionArterielleManagement; ?>
<script type="text/javascript" >
<?php
	validateDate();
	validateNumeroDossier();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","saveForm");
	$js->validateNumeroDossier("dossier:numero","numéro de dossier");
	$js->validateDate("tensionArterielleManagement:dateDebut","Date de début de mesure");	
	$js->endCheckFunction();	
?>
</script>

<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'> 
  <?php hiddenControler("TensionArterielleControler"); ?>
  <?php hiddenAction(ACTION_NEW); ?>
  
  <table border="0"> 
    <tr> 
      <td>Cabinet</td> 
      <td>&nbsp;<?php typePropertyValue("account:cabinet"); ?></td> 
    </tr> 
    <tr> 
      <td>Numéro de dossier</td> 
      <td>&nbsp;<?php text("size='10'","dossier:numero") ?></td> 
    </tr> 
    <tr> 
      <td>Date de début de mesure</td> 
      <td>&nbsp;<?php text("size='10' onkeyup='formate_date(this)'","tensionArterielleManagement:dateDebut");?></td> 
    </tr> 
    <tr> 
      <td>Nombre de jours</td> 
      <td>
	  	&nbsp;<select name="<?php typePropertyName("tensionArterielleManagement:nombreJours")?>"> 
          <option>3</option> 
          <option>4</option> 
          <option selected>5</option> 
        </select>
	</td> 
    <tr> 
      <td colspan=2> <br><input type="button" onclick='validateInput()' name="Valider" value="Créer une automesure">
<!--       <input name="button" type="button"  onclick="window.open('<?php echo getLink("$path/controler/ActionControler.php","DossierControler",ACTION_MANAGE,array("","",PARAM_STAND_ALONE),array("Dossier:dossier:numero"=>$dossier->numero)); ?>','', 'width=350,height=350,top=300,left=600,scrollbars=yes,resizable=yes')" value="Cr&eacute;er ou modifier un dossier"/> </td> 
  -->
 
  </td> 
  <td colspan=2> <br></td> 
    </tr> 
  </table> 
</form>

 <form action=<?php echo("'$path/controler/ActionControler.php'");  ?> method='post' >
                    <?php hiddenControler("TensionArterielleControler"); ?>
                    <?php hiddenAction(ACTION_MAIN); ?>
                    <?php hidden("","dossier:numero"); ?>
                    <input type="submit" value="Liste">
                </form>
