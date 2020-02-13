<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $AutreConsultCardio ?>
<?php global $complement;?>
<?php global $poids;?>
<?php global $systole ?>
<?php global $diastole ?>
<?php global $type_tension ?>
<?php global $HDL ?>
<?php global $LDL ?>

<script type="text/javascript" >
<?php
	compareDates();
	validateDate();
	dateInRange();
	validateNumeroDossier();
	$js = new JSValidation();	
	$js->startCheckFunction("validateInput","manage");
	$js->validateNumeroDossier("dossier:numero","Numéro de dossier");
//	$js->dateInRange("HyperTensionArterielle:date","Date du dépistage");
	$js->endCheckFunction();	
?>
</script>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("AutreConsultCardioControler"); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>
<?php hidden("","poids:type_exam");?>
<?php hidden("","systole:id");?>
<?php hidden("","systole:type_exam");?>
<?php hidden("","systole:numero");?>
<?php hidden("","diastole:id");?>
<?php hidden("","diastole:type_exam");?>
<?php hidden("","diastole:numero");?>
<?php hidden("","type_tension:id");?>
<?php hidden("","type_tension:type_exam");?>
<?php hidden("","type_tension:numero");?>
<?php hidden("","HDL:id");?>
<?php hidden("","HDL:type_exam");?>
<?php hidden("","HDL:numero");?>
<?php hidden("","LDL:id");?>
<?php hidden("","LDL:type_exam");?>
<?php hidden("","LDL:numero");?>

Ce formulaire permet d'assurer le suivi éducatif dans le protocole RCVA.<br><br>
Il s'appuie sur le diagnostic éducatif d'entrée dans le protocole RCVA et sur les données les plus récentes du patient (poids, résultats d'examens, etc...) 
permettant de calculer son Risque Cardio-Vasculaire Absolu.<br><br>
Il est également possible de renseigner ces données directement au cours de la consultation. <br><br>
Il est également possible d'y visualiser l'historique des données (poids, examens, RCV, etc...). <br><br>
  
  <table width="50%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="40%">Cabinet</td>
      <td width="60%"><?php typePropertyValue("account:cabinet"); ?></td>
    </tr>
    <tr>
      <td>Numéro de Dossier</td>
      <td><?php text("","dossier:numero"); ?></td>
    </tr>
	<tr>
      <td>Date de consultation</td>
      <td><?php text(" onkeyup='formate_date(this)'","AutreConsultCardio:date"); ?></td>
    </tr>
<!--    <tr>
      <td>Nom</td>
      <td><?php text("",""); ?></td>
    </tr>-->
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
	  	<?php customSubmit("value='Valider'",ACTION_NEW,"","","validateInput"); ?>
		<?php customSubmit("value='Modifier'",ACTION_FIND,array(PARAM_EDIT),"","validateInput"); ?>
<!--		<?php customSubmit("value='Liste'",ACTION_LIST,"","");?>-->
		
	  </td>	  
      <td>	  
	    <!-- <input name="button" type="button"  onclick="window.open('<?php echo getLink("$path/controler/ActionControler.php","DossierControler",ACTION_MANAGE,array("","",PARAM_STAND_ALONE),array("Dossier:dossier:numero"=>$dossier->numero)); ?>','', 'width=350,height=350,top=300,left=600,scrollbars=yes,resizable=yes')" value="Cr&eacute;er ou modifier un dossier"/>
	 --></td>
    </tr>
  </table>

</form>
