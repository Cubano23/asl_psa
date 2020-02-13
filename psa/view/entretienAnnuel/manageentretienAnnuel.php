<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 02/07/18
 * Time: 20:05
 */
?>

<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php //global $dossier; ?>
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
    <?php hiddenControler("EntretienAnnuelControler"); ?>
    <?php hiddenAction(ACTION_CREATE); ?>
    <?php hiddenParam1(""); ?>

    <table border="1" cellspacing="1" width="99%" >
        <tr>
            <td>
                <center>
                    <h1><u>Entretien Annuel</u></h1>
                </center>
                <center>
                    <table border="0" cellspacing="20" cellpadding="0">
                        <tr>
                            <td colspan='2' style="text-align: center">
                                <input type="submit" value="Continuer ">
                            </td>
                        </tr>
                    </table>
                </center>
            </td>
        </tr>
    </table>
    <br>

</form>
