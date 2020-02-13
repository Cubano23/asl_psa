
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $param ?>
<?php global $currentObjectName;?>

<script type="text/javascript" >
<?php
	validateNumeroDossier();
	validateDate();
	compareDates();
	dateInRange();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","manage");
	$js->validateNumeroDossier("dossier:numero","Numéro de dossier");
	$js->dateInRange("$currentObjectName:date","Date du dépistage");
	$js->endCheckFunction();	
?>
</script>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler(""); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>
  
Ce formulaire permet de saisir un compte rendu suite à une consultation, quel que soit le type de consultation.<br><br>
Il permet de saisir un diagnostic éducatif ainsi qu'une évaluation continue d'éducation.<br><br>

<table border="1" cellspacing="1" width="95%">
  <tr><td>
    <center><h1><u>Consultation individuelle</u></h1></center><br>&nbsp;
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Cabinet</td>
        <td>&nbsp;<?php typePropertyValue("account:cabinet"); ?></td>
      </tr>
      <tr>
        <td>Numéro de dossier</td>
        <td>&nbsp;<?php text("size='10'","dossier:numero"); ?></td>
      </tr>
  	<tr>
        <td>Date de l'évaluation </td>	  
        <td>&nbsp;<?php text("size='10' onkeyup='formate_date(this)'","$currentObjectName:date"); ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan='2'>
  	  	<?php customSubmit("value='Créer'",ACTION_NEW,"","$param->controler","validateInput"); ?>
  		  <?php customSubmit("value='Modifier'",ACTION_FIND,array(PARAM_EDIT),"$param->controler","validateInput"); ?>
  		  <?php customSubmit("value='Liste'",ACTION_LIST,"","$param->controler"); ?>
  	  </td>	  
       <!--  <td><input type="button" value="Créer ou modifier un dossier"  onclick="window.open('<?php echo getLink("$path/controler/ActionControler.php","DossierControler",ACTION_MANAGE,array("","",PARAM_STAND_ALONE),array("Dossier:dossier:numero"=>$dossier->numero)); ?>','', 'width=350,height=350,top=300,left=600,scrollbars=yes,resizable=yes')"/></td> -->
      </tr>
    </table>
  </td>
  <td valign="top">
    <center><h1><u>Consultation collective</u></h1><br>&nbsp;
    <br/>
    <a href="<?php echo $path;?>/controler/ActionControler.php?controlerparams:param:controler=GroupesControler&controlerparams:param:action=AM" style="font-size:1.2em;">Effectuer une consultation collective</a>
    <p><br /><a href="<?php echo $path;?>/view/evaluation/evaluation_collective_aide.php" style="font-family:arial;font-size:1.4em"> <i class="fa fa-2x fa-question-circle" aria-hidden="true"></i><br>Aide</a></p>
  </center>
  </td>
</tr>
</table>
<br>




</form>

