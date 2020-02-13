<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $param ?>

<form action=<?php echo ("$path/controler/ActionControler.php"); ?> method="post" name="manage" >
	<?php hiddenControler("DossierControler"); ?>
	<?php hidden("","dossier:id"); ?>
	<?php hidden("","dossier:numero"); ?>
	<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>


	
  <table width="50%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="42%">Cabinet</td>
      <td width="58%"><?php typePropertyValue("account:cabinet"); ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td><td>&nbsp;</td>
    </tr>
    <tr>
      <td>Num&eacute;ro de Dossier</td>
      <td><?php typePropertyValue("dossier:numero"); ?></td>
    </tr>
    <tr>
      <td>Sexe</td>
      <td><?php typePropertyValue("dossier:sexe"); ?></td>
    </tr>
    <tr>
      <td>Date de naissance</td>
      <td><?php typePropertyValue("dossier:dnaiss"); ?></td>
    </tr>
    <tr>
      <td>Taille</td>
      <td><?php typePropertyValue("dossier:taille"); ?></td>
    </tr>
    <tr>
      <td>Actif</td>
      <td><?php typePropertyValue("dossier:actif"); ?></td>
    </tr>
    <tr>
      <td>Date de consentement</td>
      <td><?php typePropertyValue("dossier:dconsentement"); ?></td>
      
    </tr>
        <td colspan="2" ><p style="font-size:10px;">L&acute;original du consentement du patient est conserv&eacute; dans le dossier m&eacute;dical <br/>(contexte : ce consentement est d&eacute;sormais obligatoire pour la prise en charge ASALEE,
pour le nouveau protocole)</p></td>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
		<?php customSubmit("value='Modifier'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
		<?php customSubmitWithAlert("value='Supprimer'",ACTION_DELETE,"","", NULL, "Etes-vous sûr de vouloir supprimer ce dossier?"); ?>
        <?php customSubmit("value='Créer un autre dossier'",ACTION_MANAGE,"",$param->controler); ?></td>

</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
