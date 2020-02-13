
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $despistageDiabete ?>
<?php global $poids ?>

<script type="text/javascript" >
<?php
	validateNumeroDossier();
	validateDate();
	compareDates();
	dateInRange();
	
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","manage");
	$js->validateNumeroDossier("dossier:numero","Num�ro de dossier");
	$js->dateInRange("depistageDiabete:date","Date de d�pistage");
	$js->endCheckFunction();	
?>
</script>



<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("DepistageDiabeteControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","poids:id");?>
<?php hidden("","poids:type_exam");?>
<?php hidden("","poids:numero");?>
<?php hidden("","glycemie:id");?>
<?php hidden("","glycemie:type_exam");?>
<?php hidden("","glycemie:numero");?>

<style type="text/css">
.btn{
width:100%;
}
</style>

Ce formulaire permet � tout instant de collecter des donn�es utiles au protocole d�pistage diab�te.<br><br>
Il s'appuie sur les donn�es les plus r�centes du patient (poids, glyc�mie, etc...)<br><br>
<table cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;Cabinet</td>
    <td>&nbsp;<?php typePropertyValue("account:cabinet"); ?></td>
  </tr>
  <tr>
    <td>&nbsp;Num�ro de dossier </td>
    <td>&nbsp;<?php text("size='10'","dossier:numero"); ?></td>
  </tr>
  <tr>
    <td>&nbsp;Date de d�pistage</td>
    <td>&nbsp;<?php text("size='10' onkeyup='formate_date(this)'","depistageDiabete:date"); ?></td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" scope="row">
		<?php customSubmit("value='&nbsp;&nbsp; Cr�er un d�pistage &nbsp;&nbsp;' class='btn'",ACTION_NEW,"","","validateInput"); ?></td>
	<td><?php customSubmit("value='&nbsp;&nbsp; Modifier un d�pistage existant &nbsp;&nbsp;' class='btn'",ACTION_FIND,array(PARAM_EDIT),"","validateInput"); ?>
	</td>
	</tr>
	</form>
	<!-- <tr>
		<td><br>
		<form action="<?php #echo("$path/controler/ActionControler.php");?>" method="post" name="list">
			<?php customSubmit("value='&nbsp;&nbsp; Visualiser un d�pistage existant &nbsp;&nbsp;' class='btn'",ACTION_FIND,array(PARAM_VIEW),"","validateInput"); ?></td>
		</form>  	</td>	
<td><br>
			&nbsp;&nbsp;<input name="button" type="button" class='btn' onclick="window.open('<?php #echo getLink("$path/controler/ActionControler.php","DossierControler",ACTION_MANAGE,array("","",PARAM_STAND_ALONE),array("Dossier:dossier:numero"=>$dossier->numero)); ?>','', 'width=350,height=350,top=300,left=600,scrollbars=yes,resizable=yes')" value="&nbsp;&nbsp; Cr&eacute;er ou modifier un dossier &nbsp;&nbsp;"/></td>
</tr> -->
</table>


