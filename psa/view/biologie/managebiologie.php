<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account; ?>
<?php global $dossier; ?>
<?php global $suiviDiabete; ?>
<?php global $param; ?>


<script type="text/javascript" >
<?php
	validateNumeroDossier();
	validateDate();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","aForm");
	$js->validateNumeroDossier("dossier:numero","Num&eacute;ro de dossier");
	$js->validateDate("suiviDiabete:dsuivi","Date du suivi");
	$js->endCheckFunction();	
?>
</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm"> 
	<?php hiddenControler("HistoBiologieControler"); ?> 
	<?php /*hiddenAction(ACTION_MANAGE); ?> 
	<?php hiddenParam1(PARAM_CREATE);*/ ?> 
	<?php hiddenAction(ACTION_LIST); ?> 
	<?php hiddenParam1(PARAM_ANY); ?>
	<?php hiddenParamN("",2); ?>

    
<style type="text/css">
.btn{
width:100%;
}
</style>

Ce formulaire permet &agrave; tout instant de visualiser l'ensemble des examens d&eacute;j&agrave; saisis.<br><br>
  <table border="0"> 
    <tr> 
      <td>Cabinet</td> 
      <td><?php typePropertyValue("account:cabinet"); ?></td> 
    </tr> 
    <tr> 
      <td style="width:200px;">Num&eacute;ro de dossier</td>
      <td><?php text("size='10'","dossier:numero"); ?></td> 
    </tr> 
    <tr> 
	
	<?php
	echo "<td colspan='2'>";
			 customSubmit("value='Liste des examens' class='btn'",ACTION_LIST,array(PARAM_ANY),"","validateInput"); ?><br>		

            <br><br>
    </tr>
  </table> 
</form> 

