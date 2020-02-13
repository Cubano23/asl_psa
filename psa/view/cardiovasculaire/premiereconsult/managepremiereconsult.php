<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $PremiereConsultCardio ?>
<?php global $complement;?>

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
<?php hiddenControler("PremiereConsultCardioControler"); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>

Ce formulaire permet de tenir la première consultation éducative du protocole RCVA par l'infirmière 
déléguée à la santé populationnelle
<br><br>
Il s'appuie sur les objectifs fixés lors du diagnostic éducatif.
 <br><br>
Il est également possible de renseigner les données les plus récentes du patient 
(poids, résultats d'examens, etc...qui n'auraient pas été collectés précédemment), 
directement au cours de la consultation, et de mettre à jour en conséquence son 
Risque Cardio-Vasculaire Absolu.
 <br><br>
C'est au cours de cette première consultation que les objectifs établis dans le diagnostic pourront être 
affinés, ré-expliqués et les premiers conseils et conduites à tenir discutés et validés avec le patient 
bénéficiaire.
<br><br>  
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
      <td><?php text("","PremiereConsultCardio:date"); ?></td>
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
	    <input name="button" type="button"  onclick="window.open('<?php echo getLink("$path/controler/ActionControler.php","DossierControler",ACTION_MANAGE,array("","",PARAM_STAND_ALONE),array("Dossier:dossier:numero"=>$dossier->numero)); ?>','', 'width=350,height=350,top=300,left=600,scrollbars=yes,resizable=yes')" value="Cr&eacute;er ou modifier un dossier"/>
	</td>
    </tr>
  </table>

</form>
