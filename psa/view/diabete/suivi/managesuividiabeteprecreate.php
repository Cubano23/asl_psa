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
	$js->validateNumeroDossier("dossier:numero","Numéro de dossier");
	$js->validateDate("suiviDiabete:dsuivi","Date du suivi");
	$js->endCheckFunction();	
?>
</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm"> 
  <?php hiddenControler("SuiviDiabeteControler"); ?> 
  <?php /*hiddenAction(ACTION_MANAGE); ?> 
  <?php hiddenParam1(PARAM_CREATE);*/ ?> 
   <?php hiddenAction(""); ?> 
  <?php hiddenParam1(""); ?>
  <?php hiddenParamN("",2); ?>

<?php
	$liste_exam=array("creat", "albu", "fond", "ECG", 
					  "dent", "pied", "monofil", "poids", "systole", 
					  "diastole", "type_tension", "HDL", "LDL", "HBA1c");	
    
	foreach($_ENV['liste_exam_diabete'] as $exam){
		hidden("","$exam:type_exam");
	}
?>	
    
<style type="text/css">
.btn{
width:100%;
}
</style>

Ce formulaire permet à tout instant de collecter des données utiles au protocole suivi du diabétique de type 2.<br><br>
Il s'appuie sur les données les plus récentes du patient (poids, résultats d'examens, etc...)<br><br>
Il est également possible d'y visualiser l'historique des données (poids, examens, etc...).<br><br>
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
      <td>Date du suivi</td> 
      <td><?php text("size='10' onkeyup='formate_date(this)'","suiviDiabete:dsuivi"); ?></td> 
    </tr> 
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
	
	<?php
	echo "<td colspan='2'>";
			  	customSubmit("value='Valider'",ACTION_NEW,"","","validateInput"); echo "&nbsp;";
				customSubmit("value='Modifier'",ACTION_FIND,array(PARAM_EDIT, PARAM_VIEW),""); echo "&nbsp;";
				customSubmit("value='Liste'",ACTION_LIST,array(PARAM_LIST_BY_CABINET),"");?>

<!--      <td colspan="2"><br> <input type="button" class='btn' onClick="validateInput()" name="valider" value="Créer un suivi"> <input type="button" class='btn' onClick="validateInput()" name="modifier" value="Modifier un suivi">--><br><br>
     <!--  <input name="button" type="button" class='btn' onclick="window.open('<?php echo getLink("$path/controler/ActionControler.php","DossierControler",ACTION_MANAGE,array("","",PARAM_STAND_ALONE),array("Dossier:dossier:numero"=>$dossier->numero)); ?>','', 'width=350,height=350,top=300,left=600,scrollbars=yes,resizable=yes')" value="Cr&eacute;er ou modifier un dossier"/> --> </td> 
    </tr> 
  </table> 
</form> 

