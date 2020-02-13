
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier; ?>
<?php global $param; ?>
<?php global $currentObjectName; ?>

<script type="text/javascript" >
<?php
	validateNumeroDossier();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","manage");
	$js->validateNumeroDossier("dossier:numero","Numéro de dossier");
	$js->endCheckFunction();	
?>
</script>


<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
<?php hiddenControler("ActivitePhysiqueControler"); ?>
<?php hiddenAction(ACTION_NEW); ?>
<?php hiddenParam1(""); ?>

<table border="1" cellspacing="1" width="99%" >
    <tr>
        <td>
            <center>
                <h1><u>Formulaire Activité Physique</u></h1>
            </center>
            <center>
                <table border="0" cellspacing="20" cellpadding="0">
                    <tr>
                        <td>Numéro de dossier</td>
                        <td><input type="text" name="Dossier:Dossier:numero" maxlength="16" minlength="1"></td>
                    </tr>
                    <tr>
                        <td colspan='2' style="text-align: center">
                            <input type="submit" value="Continuer">
                        </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
</table>
<br>

<!--</form>-->
<!---->
<!--<form action="--><?php //echo("$path/controler/ActionControler.php");?><!--" method="post" name="manage">-->
<!--    --><?php //hiddenControler("ActivitePhysiqueControler"); ?>
<!--    --><?php //hiddenAction(ACTION_CREATE); ?>
<!--    --><?php //hiddenParam1(""); ?>
<!---->
<!--    <table border="1" cellspacing="1" width="99%" >-->
<!--        <tr>-->
<!--            <td>-->
<!--                <center>-->
<!--                    <h1><u>Formulaire Activité Physique</u></h1>-->
<!--                </center>-->
<!--                <center>-->
<!--                    <table border="0" cellspacing="20" cellpadding="0">-->
<!--                        <tr>-->
<!--                            <td colspan='2' style="text-align: center">-->
<!--                                <input type="submit" value="Continuer sans numéro de dossier">-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                    </table>-->
<!--                </center>-->
<!--            </td>-->
<!--        </tr>-->
<!--    </table>-->
<!--    <br>-->
<!---->
<!--</form>-->
