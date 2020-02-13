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
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","aForm");
	$js->validateNumeroDossier("dossier:numero","Numéro de dossier");
	$js->endCheckFunction();	
?>
</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm"> 
  <?php hiddenControler("SuiviDiabeteControler"); ?> 
  <?php hiddenAction(""); ?> 
  <?php hiddenParam1(""); ?>
  <?php hiddenParamN("",2); ?>

<style type="text/css">
.btn{
width:100%;
}
</style>

<table border="0">
      <tr>
        <td>Cabinet</td>
        <td><?php typePropertyValue("account:cabinet"); ?></td>
      </tr>
      <tr>
        <td>Numéro de dossier</td>
        <td><?php text("size='10'","dossier:numero"); ?></td>
      </tr>  
	  
      <tr>
        <td colspan="2"><br>
			<?php customSubmit("value='Chercher les suivis du dossier' class='btn'",ACTION_LIST,array(PARAM_ANY),"","validateInput"); ?><br>		
			<?php customSubmit("value='Liste des dossiers' class='btn'",ACTION_LIST,array(PARAM_LIST_BY_CABINET),""); ?>
			
        </td>
      </tr>
  </table>
</form>

