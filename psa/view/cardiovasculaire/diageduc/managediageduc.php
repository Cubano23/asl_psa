<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $diageduc ?>
<?php global $complement;?>
<?php global $poids ?>
<?php global $systole ?>
<?php global $diastole ?>
<?php global $type_tension ?>
<?php global $HDL ?>
<?php global $LDL ?>
<?php
$liste_exam=array("Chol", "triglycerides", "creat", "kaliemie", 
				  "proteinurie", "hematurie", "fond", "ECG", 
				  "pouls", "glycemie");	

foreach($liste_exam as $exam){
	global $$exam;
}
?>
<script type="text/javascript" >
<?php
	compareDates();
	validateDate();
	dateInRange();
	validateNumeroDossier();
	$js = new JSValidation();	
	$js->startCheckFunction("validateInput","manage");
	$js->validateNumeroDossier("dossier:numero","Num�ro de dossier");
//	$js->dateInRange("HyperTensionArterielle:date","Date du d�pistage");
	$js->endCheckFunction();	
?>
</script>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("diageducControler"); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>
<?php hidden("","poids:id");?>
<?php hidden("","poids:type_exam");?>
<?php hidden("","poids:numero");?>
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

<?php
foreach($liste_exam as $exam){
	hidden("","$exam:id");
	hidden("","$exam:type_exam");
	hidden("","$exam:numero");
}
?>
Ce formulaire permet d'�tablir le diagnostic �ducatif d'entr�e dans le protocole RCVA.
<br><br>
Il s'appuie sur les donn�es les plus r�centes du patient (poids, r�sultats d'examens, etc...) 
permettant de calculer son Risque Cardio-Vasculaire Absolu.
<br><br>
Il est �galement possible de renseigner ces donn�es directement au cours de l'�laboration du 
diagnostic �ducatif proprement dit.
<br><br>
Ce diagnostic d�termine in fine des objectifs prioritaire � retenir pour la premi�re consultation infirmi�re. 	
<br><br>
  
  <table width="50%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="40%">Cabinet</td>
      <td width="60%"><?php typePropertyValue("account:cabinet"); ?></td>
    </tr>
    <tr>
      <td>Num�ro de Dossier</td>
      <td><?php text("","dossier:numero"); ?></td>
    </tr>
	<tr>
      <td>Date de consultation</td>
      <td><?php text(" onkeyup='formate_date(this)'","diageduc:date"); ?></td>
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
	   <!--  <input name="button" type="button"  onclick="window.open('<?php echo getLink("$path/controler/ActionControler.php","DossierControler",ACTION_MANAGE,array("","",PARAM_STAND_ALONE),array("Dossier:dossier:numero"=>$dossier->numero)); ?>','', 'width=350,height=350,top=300,left=600,scrollbars=yes,resizable=yes')" value="Cr&eacute;er ou modifier un dossier"/> -->
	</td>
    </tr>
  </table>

</form>
