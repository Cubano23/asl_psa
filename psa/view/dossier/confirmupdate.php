<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $param ?>

<form action=<?php echo ("$path/controler/ActionControler.php"); ?> method="post" name="manage" >
	<?php hiddenControler("DossierControler"); ?>
	<?php hiddenAction(""); ?>	
	<?php hiddenParam1(""); ?>	
	<?php hiddenParamN($param->param2,2); ?>	
	<?php hiddenParamN($param->param3,3); ?>
	<?php hidden("","dossier:id"); ?>
	<?php hidden("","dossier:numero"); ?>
	<?php hidden("","dossier:sexe"); ?>
	<?php hidden("","dossier:dnaiss"); ?>
  <?php hidden("","dossier:dconsentement"); ?>
	<?php hidden("","dossier:taille"); ?>
	
  <table width="50%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="42%">Cabinet</td>
      <td width="58%"><?php typePropertyValue("account:cabinet"); ?></td>
    </tr>
    <tr>
      <td>Numero de Dossier</td>
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
      <td>Date de consentement</td>
      <td><?php typePropertyValue("dossier:dconsentement"); ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td> Ce dossier à des documents associés, etes vous sur de vouloir le modifier ? &nbsp; <?php customSubmit("value='Oui'",ACTION_SAVE,array(PARAM_FORCE_UPDATE,"",$param->param3),"","submit"); ?><?php customSubmit("value='Non'",ACTION_MANAGE,array("","",$param->param3),"","submit"); ?></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
