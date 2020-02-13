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
	$js->validateNumeroDossier("dossier:numero","Num�ro de dossier");
//	$js->dateInRange("HyperTensionArterielle:date","Date du d�pistage");
	$js->endCheckFunction();	
?>
</script>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("PremiereConsultCardioControler"); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>

Ce formulaire permet de tenir la premi�re consultation �ducative du protocole RCVA par l'infirmi�re 
d�l�gu�e � la sant� populationnelle
<br><br>
Il s'appuie sur les objectifs fix�s lors du diagnostic �ducatif.
 <br><br>
Il est �galement possible de renseigner les donn�es les plus r�centes du patient 
(poids, r�sultats d'examens, etc...qui n'auraient pas �t� collect�s pr�c�demment), 
directement au cours de la consultation, et de mettre � jour en cons�quence son 
Risque Cardio-Vasculaire Absolu.
 <br><br>
C'est au cours de cette premi�re consultation que les objectifs �tablis dans le diagnostic pourront �tre 
affin�s, r�-expliqu�s et les premiers conseils et conduites � tenir discut�s et valid�s avec le patient 
b�n�ficiaire.
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
