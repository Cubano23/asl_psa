<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $dossier; ?>
<?php global $suiviDiabete ?>
<?php global $param ?>

<script type="text/javascript" >
<?php
	validateNumeroDossier();
	validateDate();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","aForm");
	$js->validateDate("suiviDiabete:dsuivi","Date du suivi");
	$js->endCheckFunction();	
?>
</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="get" name="aForm"> 
  <?php hiddenControler("SuiviDiabeteControler"); ?> 
  <?php hiddenAction(ACTION_NEW); ?> 
  <?php hidden("","dossier:numero"); ?>
 
  <b>Suivi du jour:
  <table border=0 cellspacing=14> 
    <tr> 
      <td>date du suivi:</td> 
      <td><?php text("","suiviDiabete:dsuivi"); ?></td> 
    </tr> 
 
    <tr> 
      <td></td> 
      <td><table border=0> 
          <tr> 
            <td><font color='blue'><b>4 mois</b></font></td> 
            <td><?php checkBox("","suiviDiabete:suivi_type","4"); ?></td> 
          </tr> 
<?php /*          <tr>
            <td><font color='green'><b>semestriel</b></font></td> 
            <td><?php checkBox("","suiviDiabete:suivi_type","s"); ?></td> 
          </tr> */ ?>
          <tr>
            <td><font color='brown'><b>annuel</b></font></td> 
            <td><?php checkBox("","suiviDiabete:suivi_type","a"); ?></td>
          </tr> 
        </table></td> 
    </tr> 
  </table>
  <input type='button' onclick="validateInput()" value='Suivant'> 
</form> 
