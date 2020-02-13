<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>


<?php global $account;?>
<?php global $dossier; ?>
<?php global $sevragetabac;?>
<?php global $param ?>

<?php #var_dump($sevrageTabac);?>
<script type="text/javascript">
    <?php
    validateDate();
    compareDates();
    dateInRange();
    validateNumeroDossier();
    $js = new JSValidation();
    $js->startCheckFunction("validateInput","manage");
    $js->validateNumeroDossier("dossier:numero","Numéro de dossier");
    $js->dateInRange("sevrage:date","Date de consultation");
    $js->endCheckFunction();
    ?>
</script>
<?php //if(isset($_GET['debug'])): ?>
<?php if(true): ?>
Ce formulaire permet de saisir un suivi consultation sevrage tabagique du patient.<br><br>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage">
    <?php hiddenControler("SevrageTabacControler"); ?>
    <?php hiddenAction("AM"); ?>
    <?php hiddenParam1(""); ?>

    <table width="50%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="40%">Cabinet</td>
            <td width="60%"><?php typePropertyValue("account:cabinet"); ?></td>
        </tr>
        <tr>
            <td>Numéro de dossier</td>
            <td><?php text("size='10'","dossier:numero"); ?></td>
        </tr>
        <tr>
            <td>Date de la consultation </td>
            <td><?php text("size='10' onkeyup='formate_date(this)'","sevragetabac:date"); ?></td>

        </tr>

        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>

        <tr>
            <td>
                <?php customSubmit("value='Créer'",ACTION_NEW,array(""),"","validateInput"); ?>
                <?php customSubmit("value='Modifier'",ACTION_NEW,array(PARAM_EDIT),"","validateInput"); ?>
                <?php customSubmit("value='Liste'",ACTION_LIST, array(PARAM_VIEW),""); ?>
            </td>
        </tr>
    </table>
    <?php else: ?>
        <br /><br />
        Rubrique en cours de maintenance, merci de saisir votre évaluation ultérieument.
        <br />
        Veuillez nous excuser pour la gêne occasionnée.
        <br />
        L'équipe Asalée
        <br /><br />
    <?php endif ?>
</form>

