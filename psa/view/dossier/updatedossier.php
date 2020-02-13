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


 
<br>

<br>
  <table width="93%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="15%">Cabinet</td>
      <td width="15%"><?php typePropertyValue("account:cabinet"); ?></td>
      <td></td>
    </tr>

    <tr>
      <td width="15%">Num&eacute;ro de Dossier</td>
      <!-- <td><?php typePropertyValue("dossier:numero"); ?></td> -->
      <td width="15%"><?php text("size='10'","dossier:numero"); ?></td>
      <td style="color:red;font-size:10px;">Attention, le Num&eacute;ro de Dossier n'est &agrave; modifier ici que si vous souhaitez modifier le num&eacute;ro de dossier du patient dont le num&eacute;ro figure actuellement dans cette zone</td>
  
      <input type="hidden" name="numerorigine" value="<?php echo $dossier->numero;?>"/> 
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
  </table>
  <table width="93%"  border="0" cellspacing="0" cellpadding="0">
    <!-- <tr>
      <td width="15%">Nom</td>
      <td width="30%"><?php text("size='25' maxlenght='40'",""); ?></td>
      <td></td>
    </tr>
    <tr>
      <td>Pr&eacute;nom</td>
      <td><?php text("size='25' maxlenght='40'",""); ?></td>
      <td></td>
    </tr>
    <tr>
        <td colspan="3" ><p style="font-size:10px;">La fonction d'enregistrement du nom et du pr&eacute;nom n'est pas ouverte, <br/>
          il est cependant possible de saisir un nom ou un pr&eacute;nom pour se rendre compte du fonctionnement futur</p></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr> -->
    <tr>
      <td width="15%">Sexe</td>
      <td><?php selectv("","dossier:sexe",$sexe); ?></td>
      <td></td>
    </tr>
    <tr>
      <td>Date de naissance</td>
      <td><?php text("size='10' onkeyup='formate_date(this)'","dossier:dnaiss"); ?></td>
      <td></td>
    </tr>
    <tr>
      <td>Taille</td>
      <td><?php text("size='3'","dossier:taille"); ?>&nbsp; cm</td>
      <td></td>
    </tr>
    <tr>
      <td>Statut</td>
      <td><?php selectv("","dossier:actif",$actif); ?></td>
      <td></td>
    </tr>
    <tr>
      <td>Date de consentement</td>
      <td><?php text("size='10' onkeyup='formate_date(this)'","dossier:dconsentement"); ?></td>
      <td></td>
    </tr>
    <tr>
        <td colspan="3" ><p style="font-size:10px;">L&acute;original du consentement du patient est conserv&eacute; dans le dossier m&eacute;dical <br/>(contexte : ce consentement est d&eacute;sormais obligatoire pour la prise en charge ASALEE,
pour le nouveau protocole)</p></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td><input type="button"  value="Valider la saisie" onClick="validateInput()"></td>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
</form>


</script> 
