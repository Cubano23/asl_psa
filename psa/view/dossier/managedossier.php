<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $param ?>

<script type="text/javascript" >
<?php
	validateNumeroDossier();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","manage");
	$js->validateNumeroDossier("dossier:numero","Numéro de Dossier");
	$js->endCheckFunction();	
?>
</script>

<body>
<form action="<?php echo ("$path/controler/ActionControler.php"); ?>" method="post" name="manage">
<?php hiddenControler("DossierControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hiddenParamN($param->param3,3); ?>  
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td >Cabinet</td>
      <td ><?php typePropertyValue("account:cabinet"); ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td style="width:200px;">Numéro de dossier</td> 
      <td><?php text("size='16'","dossier:numero"); ?></td>
    </tr>

    <tr>
      <td colspan="2">
        <p>&nbsp;</p>
        <i style="font-size:10px;">"le numéro de dossier peut être constitué de chiffres et/ou de lettres<br/>
attention le dossier “01” (chiffre ZERO suivi d’un chiffre UN) sera considéré comme différent du dossier “1” (chiffre 1 seul)"</i>
      </td>
      
    </tr>
    <?php if($account->cabinet == "moissanstabac2017"): ?>
      <tr>
        <td colspan="2">
          <p>&nbsp;</p>
          <p style="color:red; font-weight:bold;">
            Dans le cabinet 'MOISSANSTABAC2017', les n° de dossier doivent être constitué de votre identifiant PSA puis d'un numéro d'ordre.
          </p>
        </td>
      </tr>
    <?php endif ?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan='2'>
	  	<?php customSubmit("value='Créer'",ACTION_NEW,"","","validateInput"); ?>
		<?php customSubmit("value='Modifier'",ACTION_FIND,array(PARAM_EDIT),"","validateInput"); ?>
      	<?php customSubmitWithAlert("value='Supprimer'",ACTION_DELETE,"","", NULL, "Etes-vous sûr de vouloir supprimer ce dossier?"); ?>
      	<?php customSubmit("value='Liste'",ACTION_LIST,"",""); ?></Td>
    </tr>
  </table>
</form>
<?php if($account->cabinet == "moissanstabac2017"): ?>
<script>
jQuery(document).on('ready', function(){
  jQuery('input[name="Dossier:dossier:numero"]').val("<?php echo $_SESSION['id.login'] ?>")
})
</script>
<?php endif ?>
