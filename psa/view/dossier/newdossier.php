<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $param ?>

<script type="text/javascript" >
<?php
	
	validateSexe();
	compareDates();
	dateInRange();
	validateDate();
	validateTaille();
  dateInRangeNaiss();
	
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","manage");
	$js->validateSexe("dossier:sexe","Sexe");
	$js->dateInRangeNaiss("dossier:dnaiss","Date de naissance");	
  #$js->validateDate("dossier:dconsentement","Date de consentement"); 
	$js->validateTaille("dossier:taille","Taille");
	$js->endCheckFunction();	

?>
</script>


<form action=<?php echo ("$path/controler/ActionControler.php"); ?> method="post" name="manage" >
	<?php hiddenControler("DossierControler"); ?>
	<?php hiddenParamN($param->param3,3); ?>
	<?php hiddenAction(ACTION_SAVE); ?>	
	<?php hidden("","dossier:id"); ?>
	<?php hidden("","dossier:numero"); ?>


<br>
<br>
  <table width="50%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="42%">Cabinet</td>
      <td width="58%"><?php typePropertyValue("account:cabinet"); ?></td>
    </tr>

    <tr>
      <td>Num&eacute;ro de Dossier ici</td>
     <!--  <td><?php typePropertyValue("dossier:numero"); ?></td> -->
     <td><?php text("size='10'","dossier:numero"); ?></td>
    </tr>
        <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <!-- <tr>
      <td>Nom</td>
      <td><?php text("size='40' maxlenght='40'",""); ?></td>
    </tr>
    <tr>
      <td>Prénom</td>
      <td><?php text("size='40' maxlenght='40'",""); ?></td>
    </tr>
    <tr>
        <td colspan="2" ><p style="font-size:10px;">La fonction d'enregistrement du nom et du prénom n'est pas ouverte, <br/>
          il est cependant possible de saisir un nom ou un prénom pour se rendre compte du fonctionnement futur</p></td>
      
    </tr>
        <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr> -->
    <tr>
      <td>Sexe</td>
      <td><?php selectv("","dossier:sexe",$sexe); ?></td>
    </tr>
    <tr>
      <td>Date de naissance</td>
      <td><?php text("size='10' onkeyup='formate_date(this)'","dossier:dnaiss"); ?></td>
    </tr>
    <tr>
      <td>Taille</td>
      <td><?php text("size='3'","dossier:taille"); ?>&nbsp; cm</td>
    </tr>
    <tr>
      <td>Statut</td>
      <td><?php selectv("","dossier:actif",$actif); ?></td>
    </tr>
    <tr>
      <td>Date de consentement</td>
      <td><?php text("size='10' onkeyup='formate_date(this)'","dossier:dconsentement"); ?></td>

    </tr>
    <tr>
        <td colspan="2" ><p style="font-size:10px;">L&acute;original du consentement du patient est conserv&eacute; dans le dossier m&eacute;dical <br/>(contexte : ce consentement est d&eacute;sormais obligatoire pour la prise en charge ASALEE,
pour le nouveau protocole)</p></td>
      
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><input type="button"  value="Valider la saisie" onClick="validateInput()"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
