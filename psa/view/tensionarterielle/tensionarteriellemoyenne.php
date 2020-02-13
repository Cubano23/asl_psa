<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $tensionArterielleManagement; ?>
<?php global $tensionArterielleMoyenne; ?>

<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("TensionArterielleControler"); ?>

<?php require("view/common/dossierresume.php");?>

<pre>
Moyenne du matin   <?php typePropertyValue("tensionArterielleMoyenne:moyenne_sys_matin"); ?>/<?php typePropertyValue("tensionArterielleMoyenne:moyenne_dia_matin");?> 
Moyenne du soir    <?php typePropertyValue("tensionArterielleMoyenne:moyenne_sys_soir"); ?>/<?php typePropertyValue("tensionArterielleMoyenne:moyenne_dia_soir");?> 
Moyenne Générale   <?php typePropertyValue("tensionArterielleMoyenne:moyenne_sys"); ?>/<?php typePropertyValue("tensionArterielleMoyenne:moyenne_dia");?> 
Nombre de tensions <?php echo(6*$tensionArterielleManagement->nombreJours); ?> 
</pre>

<?php customSubmit("value='Saisir un autre questionnaire'",ACTION_MANAGE,"",$param->controler); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","tensionArterielleMoyenne:date"); ?>
</form>
