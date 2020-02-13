<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 11/05/18
 * Time: 11:42
 */

global $form_class;
global $depistage_aomi;
global $liste_historique;
?>

<script type="text/javascript" src="<?php echo $path;?>/view/login/_js/dynCont/ajax.js"></script>

<script type="text/javascript" src="<?php echo $path;?>/view/login/_js/tooltip/ajax-dynamic-content.js"></script>

<script type="text/javascript" src="<?php echo $path;?>/view/login/_js/tooltip/ajax-tooltip2.js"></script>

<br />
<br />

<input type="hidden" name="DepistageAOMI:DepistageAOMI:provenance" value="<?= $form_class ?>">

<?php
if (!empty($depistage_aomi)) {
    ?>
    <input type="hidden" name="DepistageAOMI:DepistageAOMI:id" value="<?= $depistage_aomi['id'] ?>">
    <input type="hidden" name="DepistageAOMI:DepistageAOMI:dossier_id" value="<?= $depistage_aomi['dossier_id'] ?>">
    <input type="hidden" name="DepistageAOMI:DepistageAOMI:dossier_numero" value="<?= $depistage_aomi['dossier_numero'] ?>">
    <?php
}
?>

<h3>Dépistage de l'AOMI (Artériopathie Obstructive des Membres Inférieurs)</h3>

<br /> <br />

<table cellspacing="2" style="margin-left: 35px">
    <tr>
        <td colspan="2">
        Indications:
        <a href='javascript://' onmouseover="ajax_showTooltip('/psa/view/depistage/aide_saisie_aomi_indications.html',this);return false" >
            <img src='/psa/view/login/img/puces/aide.gif'>
        </a>
        </td>
    <tr>
        <td>
            <input type="checkbox" name="DepistageAOMI:DepistageAOMI:dt1plus20" <?php if ($depistage_aomi['dt1plus20'] == 1) echo "checked"; ?> >
            <label>Diabète type 1 avéré depuis plus de 20 ans:</label>
        </td>
    </tr>
    <tr>
        <td>
            <input type="checkbox" name="DepistageAOMI:DepistageAOMI:dt2" <?php if ($depistage_aomi['dt2'] == 1) echo "checked"; ?> >
            <label>Diabète type 2</label>
        </td>
    </tr>
    <tr>

        <td>
            <input type="checkbox" name="DepistageAOMI:DepistageAOMI:tabacActifOuCorrige" <?php if ($depistage_aomi['tabacActifOuCorrige'] == 1) echo "checked"; ?> >
            <label>Tabagisme Actif ou Corrigé (20 PA pour les hommes 15 PA pour les femmes) :</label>
        </td>
    </tr>
    <tr>
        <td>
            <input type="checkbox" name="DepistageAOMI:DepistageAOMI:htaPermanente" <?php if ($depistage_aomi['htaPermanente'] == 1) echo "checked"; ?> >
            <label>HTA permanente</label>
        </td>
    </tr>
    <tr>

        <td>
            <input type="checkbox" name="DepistageAOMI:DepistageAOMI:dyslipidemies" <?php if ($depistage_aomi['dyslipidemies'] == 1) echo "checked"; ?> >
            <label>Dyslipidémies(LDL > 1.9 ou HDL < 0.40)</label>
        </td>
    </tr>
    <tr>

        <td>
            <input type="checkbox" name="DepistageAOMI:DepistageAOMI:pathoCVASansAOMIAvere" <?php if ($depistage_aomi['pathoCVASansAOMIAvere'] == 1) echo "checked"; ?> >
            <label>Pathologie cardio vasculaire sans AOMI identifi?e</label>
        </td></tr>
    <tr>


        <td>
            <input type="checkbox" name="DepistageAOMI:DepistageAOMI:antecedantsFamiliaux" <?php if ($depistage_aomi['antecedantsFamiliaux'] == 1) echo "checked"; ?> >
            <label>Antécédents familiaux</label>
        </td></tr>
    <tr>

        <td>
            <input type="checkbox" name="DepistageAOMI:DepistageAOMI:SOASAveree" <?php if ($depistage_aomi['SOASAveree'] == 1) echo "checked"; ?> >
            <label>SOAS avéré </label>
        </td>
    </tr>
    </tr>
</table>
<table cellspacing="10">
    <tr>
        <td>
            <label>IPS Gauche</label>
            <input type="number" name="DepistageAOMI:DepistageAOMI:ipsg" min="0" max="50" step=".01" placeholder="IPS Gauche" value="<?= $depistage_aomi['ipsg'] ?>">
        </td>
        <td>
            <label>IPS Droit</label>
            <input type="number" name="DepistageAOMI:DepistageAOMI:ipsd" min="0" max="50" step=".01" placeholder="IPS Droit" value="<?= $depistage_aomi['ipsd'] ?>">
        </td>

    </tr>
    <tr>
        <td colspan="2">
            IPS à l'initiative de :
                <input type="radio" name="DepistageAOMI:DepistageAOMI:initiateurIPS" value="medecin" <?php if ($depistage_aomi['initiateurIPS'] != "infirmiere") echo "checked"; ?>>
                <label>Médecin</label>
                <input type="radio" name="DepistageAOMI:DepistageAOMI:initiateurIPS" value="infirmiere" <?php if ($depistage_aomi['initiateurIPS'] == "infirmiere") echo "checked"; ?>>
                <label>Infirmière</label>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            IPS réalisé par :
                <input type="radio" name="DepistageAOMI:DepistageAOMI:realisateurIPS" value="medecin" <?php if ($depistage_aomi['realisateurIPS'] != "infirmiere") echo "checked"; ?>>
                <label>Médecin</label>
                <input type="radio" name="DepistageAOMI:DepistageAOMI:realisateurIPS" value="infirmiere" <?php if ($depistage_aomi['realisateurIPS'] == "infirmiere") echo "checked"; ?>>
                <label>Infirmière</label>
        </td>
    </tr>
    <tr>
        <td>
            <br/>écho doppler artériel (case à cocher si pathologique)
            <a href='javascript://' onmouseover="ajax_showTooltip('/psa/view/depistage/aide_saisie_aomi_patho.html',this);return false" >
                <img src='/psa/view/login/img/puces/aide.gif'>
            </a>

            <br /><br />
            <div >
                <input type="radio" name="DepistageAOMI:DepistageAOMI:eda" value="1" <?php if ($depistage_aomi['eda'] == 1) echo "checked"; ?>>
                <label>Oui</label>
                <input type="radio" name="DepistageAOMI:DepistageAOMI:eda" value="0" <?php if ($depistage_aomi['eda'] == 0) echo "checked"; ?>>
                <label>Non</label>
                <input type="radio" name="DepistageAOMI:DepistageAOMI:eda" value="-1" <?php if ($depistage_aomi['eda'] == -1) echo "checked"; ?>>
                <label>Ne sait pas</label>
                <input type="radio" name="DepistageAOMI:DepistageAOMI:eda" value="-2" <?php if ($depistage_aomi['eda'] == -2) echo "checked"; ?>>
                <label>Ne s'applique pas</label>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            Commentaires :
            <a href='javascript://' onmouseover="ajax_showTooltip('/psa/view/depistage/aide_saisie_aomi_commentaire.html',this);return false" >
                <img src='/psa/view/login/img/puces/aide.gif'>
            </a>
            <textarea rows="1" cols="70" name="DepistageAOMI:DepistageAOMI:commentaires" placeholder="Quelques précisions supplémentaires"><?= $depistage_aomi['commentaires'] ?></textarea>
        </td>
    </tr>
</table>

<?php
if ($form_class == "Dépistage de l'AOMI")
    echo "<p style='margin-left: 20px'><br /><br /><input type=\"submit\" value=\"Valider la saisie\"></p>";
?>

<br /> <br />

<!--<p style="margin-left: 30px"><a href='#historique_depistage' onclick="affiche_detail('historique_depistage')">Afficher/Masquer l'historique des dépistages de l'AOMI</a></p>-->

<br /> <br />

<?php include_once "historique_depistage_aomi.php"; ?>

<br /> <br />

