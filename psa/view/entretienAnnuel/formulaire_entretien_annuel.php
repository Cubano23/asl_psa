<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 02/07/18
 * Time: 20:05
 */

require_once("bean/beanparser/htmltags.php");
require_once("view/jsgenerator/jsgenerator.php");
require_once("view/common/vars.php");

//global $dossier;
global $param;
global $entretienAnnuel;
//global $dossierId;
//global $dossierNumero;
?>
<script type="text/javascript" src="<?php echo $path;?>/view/login/_js/dynCont/ajax.js"></script>

<script type="text/javascript" src="<?php echo $path;?>/view/login/_js/tooltip/ajax-dynamic-content.js"></script>

<script type="text/javascript" src="<?php echo $path;?>/view/login/_js/tooltip/ajax-tooltip2.js"></script>

<div style="margin-left: 20px">
    <form action="<?php echo "$path/controler/ActionControler.php"?>" method="post" name="formulaireEntretienAnnuel">

        <?php
            hiddenControler("EntretienAnnuelControler");

            if (empty($entretienAnnuel))
                hiddenAction(ACTION_SAVE);
            else
                hiddenAction(ACTION_UPDATE);

            hidden("","EntretienAnnuel:id")
        ?>

        <br />

        <input type="hidden" name="EntretienAnnuel:EntretienAnnuel:id" value="<?= $entretienAnnuel['id'] ?>">

        <?php
            if (isset($entretienAnnuel)) {
        ?>
                <br />
                <p style="font-size: medium">Entretien annuel saisi par <em><b><?= $entretienAnnuel['infNom'] ?></b></em> et r�alis� avec <em><b><?= $entretienAnnuel['realiseAvecNom']. ' ' .$entretienAnnuel['realiseAvecPrenom'] ?></b></em></p>
        <?php
            }
        ?>

        <br /><br />



        <h3>Quelles sont les difficult�s rencontr�es ?</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    <textarea name="EntretienAnnuel:EntretienAnnuel:difficultesRencontrees" cols="100" rows="10"><?= $entretienAnnuel['difficultesRencontrees'] ?></textarea>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <h3>Quelles sont les ressources identifi�es ?</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    <textarea name="EntretienAnnuel:EntretienAnnuel:ressourcesIdentifiees" cols="100" rows="10"><?= $entretienAnnuel['ressourcesIdentifiees'] ?></textarea>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <h3>Quelles formations suivies ? </h3>
        <table cellspacing="30">
            <tr>
                <td>
                    <textarea name="EntretienAnnuel:EntretienAnnuel:formationsSuivies" cols="100" rows="10"><?= $entretienAnnuel['formationsSuivies'] ?></textarea>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <h3>Quelles ont �t� les r�alisations marquantes pour vous-m�me ? </h3>
        <table cellspacing="30">
            <tr>
                <td>
                    <textarea name="EntretienAnnuel:EntretienAnnuel:realisationsMarquantes" cols="100" rows="10"><?= $entretienAnnuel['realisationsMarquantes'] ?></textarea>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <h3>Quelles sont les perspectives professionnelles envisag�es ? </h3>
        <table cellspacing="30">
            <tr>
                <td>
                    <textarea name="EntretienAnnuel:EntretienAnnuel:perspectivesProEnvisagees" cols="100" rows="10"><?= $entretienAnnuel['perspectivesProEnvisagees'] ?></textarea>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <h3>Quels sont les besoins que vous souhaitez satisfaire ? </h3>
        <table cellspacing="30">
            <tr>
                <td>
                    <textarea name="EntretienAnnuel:EntretienAnnuel:besoinsASatisfaire" cols="100" rows="10"><?= $entretienAnnuel['besoinsASatisfaire'] ?></textarea>
                </td>
            </tr>
        </table>
        <br/>
        <br/>

        <table cellspacing="30">
            <tr>
                <td>
                    Projet Academique : <br /><br />

                    <div style="margin-left: 15px">
                        <input type="radio" name="EntretienAnnuel:EntretienAnnuel:projetAcademique" value="4" <?php if ($entretienAnnuel['projetAcademique'] == '4') echo "checked"; ?> required>
                        <label>DU</label>
                        <input type="radio" name="EntretienAnnuel:EntretienAnnuel:projetAcademique" value="3" <?php if ($entretienAnnuel['projetAcademique'] == '3') echo "checked"; ?> required>
                        <label>Master</label>
                        <input type="radio" name="EntretienAnnuel:EntretienAnnuel:projetAcademique" value="2" <?php if ($entretienAnnuel['projetAcademique'] == '2')echo "checked"; ?> required>
                        <label>DE IPA</label>
                        <input type="radio" name="EntretienAnnuel:EntretienAnnuel:projetAcademique" value="1" <?php if ($entretienAnnuel['projetAcademique'] == '1')echo "checked"; ?> required>
                        <label>Doctorat</label>
                        <input type="radio" name="EntretienAnnuel:EntretienAnnuel:projetAcademique" value="0" <?php if ($entretienAnnuel['projetAcademique'] == '0')echo "checked"; ?> required>
                        <label>Autre</label>
                        <input type="radio" name="EntretienAnnuel:EntretienAnnuel:projetAcademique" value="-1" <?php if ($entretienAnnuel['projetAcademique'] == '-1')echo "checked"; ?> required>
                        <label>Aucun</label>
                    </div>
                </td>

            </tr></table>
        <br/>
        <br/>
        <h3>Quelles sont les difficult�s rencontr�es ?</h3>
        <table cellspacing="30">
            <tr>
                <td>
                    Realis� avec Pr�nom <br /><br />
                    <textarea name="EntretienAnnuel:EntretienAnnuel:realiseAvecPrenom" cols="45" rows="1"><?= $entretienAnnuel['realiseAvecPrenom'] ?></textarea>
                </td>
            </tr>
            <tr>
            <td>
                Realis� avec Nom <br /><br />
                <textarea name="EntretienAnnuel:EntretienAnnuel:realiseAvecNom" cols="45" rows="1"><?= $entretienAnnuel['realiseAvecNom'] ?></textarea>
            </td>
            </tr>
            <tr>
            <td>
                Realis� avec login<br /><br />
                <textarea name="EntretienAnnuel:EntretienAnnuel:realiseAvecLoginAsalee" cols="45" rows="1"><?= $entretienAnnuel['realiseAvecLoginAsalee'] ?></textarea>
            </td>

        </table>
        <br/>
        <br/>



<!--        <p>[mettre un texte ici pour information</p>-->

        <input type="submit" value="Enregistrer">

    </form>
</div>
