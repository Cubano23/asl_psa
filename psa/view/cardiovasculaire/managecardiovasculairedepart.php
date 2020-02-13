<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $CardioVasculaireDepart ?>
<?php global $complement;?>
<?php global $orga;?>

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
<?php hiddenControler("CardioVasculaireDepartControler"); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>
<?php hiddenParamN("","2"); ?>
<?php hidden("","poids:type_exam");?>
<?php hidden("","diastole:type_exam");?>
<?php hidden("","systole:type_exam");?>
<?php hidden("","type_tension:type_exam");?>
<?php hidden("","LDL:type_exam");?>
<?php hidden("","HDL:type_exam");?>

<?php

$liste_exam=array("Chol", "triglycerides", "creat", "kaliemie", 
				  "proteinurie", "hematurie", "fond", "ECG", 
				  "pouls", "glycemie");	

foreach($liste_exam as $exam){
	hidden("","$exam:type_exam");
}
?>
Ce formulaire permet à tout instant de collecter des données utiles au protocole RCVA.<br><br>
Ces données sont ensuite utilisées pour le calcul du Risque Cardio-Vasculaire Absolu au moment 
des rencontres du patient avec l'équipe Asalée.<br><br>
Il est également possible de renseigner ces données directement au cours d'une consultation :
lors de la 1ère consultation ou lors des consultations de suivi<br><br>
  
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
      <td>Date du suivi</td>
      <td><?php text(" onkeyup='formate_date(this)'","CardioVasculaireDepart:date"); ?></td>
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
	  	<?php if($complement){
				if($orga){
					customSubmit("value='Valider'",ACTION_NEW,array(PARAM_EDIT, PARAM_DEPART),"","validateInput"); 
				}
				else{
					customSubmit("value='Valider'",ACTION_NEW,array(PARAM_EDIT),"","validateInput"); 
				}
		  	  }
			  else{
			  	customSubmit("value='Valider'",ACTION_NEW,"","","validateInput"); 
			  }?>
		<?php customSubmit("value='Modifier'",ACTION_FIND,array(PARAM_EDIT),"","validateInput"); ?>
		<?php customSubmit("value='Liste'",ACTION_LIST,"","");?>
		
	  </td>	  
      <td>	  
	    <!-- <input name="button" type="button"  onclick="window.open('<?php echo getLink("$path/controler/ActionControler.php","DossierControler",ACTION_MANAGE,array("","",PARAM_STAND_ALONE),array("Dossier:dossier:numero"=>$dossier->numero)); ?>','', 'width=350,height=350,top=300,left=600,scrollbars=yes,resizable=yes')" value="Cr&eacute;er ou modifier un dossier"/>
	--></td> 
    </tr>
  </table>

</form>
