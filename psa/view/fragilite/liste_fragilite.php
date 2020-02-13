<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 04/05/18
 * Time: 16:48
 */

global $listeFrag;

?>
<table border="1" cellspacing="1" width="99%" >
    <tr>
        <td>
            L'objectif de ce formulaire est de poursuivre et am&eacute;liorer la prise en charge des patients fragiles par les infirmi&egrave;res Asal&eacute;e.
            <br>
            <br>
            Pour cela nous vous invitons &agrave; renseigner ce formulaire pour le prochain patient que vous rencontrez et pour lequel un accompagnement suppl&eacute;mentaire et/ou la mobilisation de ressources externes suppl&eacute;mentaires sont mis en place pour faire face &agrave; la situation de votre patient.
        </td>
    </tr>
</table>
    <br />
    <br />


<!--button><a href="../controler/ActionControler.php?controlerparams:param:controler=FragiliteControler&controlerparams:param:action=AMEN" style="color: black"><b>Nouveau formulaire</b></a></button-->

<form action="<?php echo "$path/controler/ActionControler.php"?>" method="post">
    <?php
        hiddenControler("FragiliteControler");
        hiddenAction(ACTION_MAIN);
    ?>
    <input type="submit" value="Nouveau formulaire">
</form>

<br /><br />

<table style="text-align: center; border-spacing: 0px;" cellspacing="40" border="1">
    <thead>
    <tr>

        <th>
            Num dossier
        </th>
        <th>
            Infirmi&egrave;re ajout
        </th>
        <th>
            Date
        </th>
        <th colspan="2"></th>
    </tr>
    </thead>

    <tbody>
    <?php
    foreach ($listeFrag as $frag)
    {
        ?>

        <tr>
            <td>
                <?php
                    if ($frag->dossier_numero == -1)
                    {
                        echo "NA";
                    }
                    else
                    {
                        echo $frag->dossier_numero;
                    }
                ?>
            </td>
            <td>
                <?php echo $frag->infAjout ?>
            </td>
            <td>
                <?php echo $frag->dateAjout ?>
            </td>
            <form action="<?php echo "$path/controler/ActionControler.php"?>" method="post" name="formulaireFragilite">
                <?php
                    hiddenControler("FragiliteControler");
                    hiddenAction(ACTION_NEW);
                ?>
                <input type="hidden" name="Fragilite:Fragilite:id" value="<?= $frag->id ?>">
                <td>
                    <input type="submit" value="Consulter">
                </td>
            </form>
            <form action="<?php echo "$path/controler/ActionControler.php"?>" method="post" name="formulaireFragilite">
                <?php
                hiddenControler("FragiliteControler");
                hiddenAction(ACTION_HARD);
                ?>
                <input type="hidden" name="Fragilite:Fragilite:id" value="<?= $frag->id ?>">
                <td>
                    <input type="submit" value="Supprimer">
                </td>
            </form>
        </tr>

        <?php
    }
    ?>
    </tbody>
</table>

