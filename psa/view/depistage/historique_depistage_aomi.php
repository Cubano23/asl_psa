<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 07/06/18
 * Time: 11:10
 */

require_once ("Config.php");
$config = new Config();
?>
<?php

if (!empty($liste_historique))
{

    ?>
    <h3 style="margin-left: 30px"><b>Liste des dépistages de l'AOMI (Artériopathie Obstructive des Membres Inférieurs)</b></h3>

    <br><br>

    <p style="margin-left: 30px"><a href='#historique_depistage' onclick="affiche_detail('historique_depistage')">Afficher/Masquer l'historique des dépistages de l'AOMI</a></p>

    <br><br>

    <div id="historique_depistage">


        <table border=1>
            <tr>
                <th>Date</th>
                <th>Dossier</th>
                <th>Initiateur</th>
                <th>Réalisateur</th>
                <th>IPS droit</th>
                <th>IPS gauche</th>
                <th>écho doppler artériel</th>
                <th>Réalisé dans le cadre d'un(e)</th>
                <th>SOAS</th>
                <th>Antécédents familiaux: </th>
                <th>Pathologie CVA sans AOMI connue</th>
                <th>Dys lipidémies</th>
                <th>HTA</th>
                <th>Tabac Actif ou Corrigé </th>
                <th>Dt2</th>
                <th>Dt1</th>

                <th>Commentaires</th>

                <?php
                if ($authorisation_suppression) {
                    echo "<th></th>" ;
                }
                echo"</tr>";

                foreach ($liste_historique as $entree)
                {
                ?>
            <tr>
                <td style="color: #DF8D00">
                    <?= date('d/m/Y',strtotime($entree->dateSaisie)) ?>
                </td>
                <td style="color: #0000BB; text-align: center">
                    <?= $entree->dossier_numero ?>
                </td>

                <td style="color: #0000BB; text-align: center">
                    <?= $entree->initiateurIPS ?>
                </td>
                <td style="color: #0000BB; text-align: center">
                    <?= $entree->realisateurIPS ?>
                </td>
                <td style="color: #0000BB; text-align: center">
                    <?= $entree->ipsd ?>
                </td>
                <td style="color: #0000BB; text-align: center">
                    <?= $entree->ipsg ?>
                </td>
                <td style="color: #0000BB; text-align: center">
                    <?php
                    if ($entree->eda == 0)
                        echo "Non";
                    elseif ($entree->eda == 1)
                        echo "Oui";
                    elseif ($entree->eda == -1)
                        echo "Ne sait pas";
                    else
                        echo "Ne s'applique pas";
                    ?>
                </td>
                <td style="color: #0000BB; text-align: center">
                    <?= $entree->provenance ?>
                </td>

                <td style="color: #0000BB; text-align: center"> <?php if ($entree->SOASAveree == "1") echo "Oui"; if ($entree->SOASAveree == "0") echo "Non"; if ($entree->SOASAveree == "-1") echo "NSP"; ?> </td>
                <td style="color: #0000BB; text-align: center"> <?php if ($entree->antecedantsFamiliaux == "1") echo "Oui"; if ($entree->antecedantsFamiliaux == "0") echo "Non"; if ($entree->antecedantsFamiliaux == "-1") echo "NSP"; ?> </td>
                <td style="color: #0000BB; text-align: center"> <?php if ($entree->pathoCVASansAOMIAvere == "1") echo "Oui"; if ($entree->pathoCVASansAOMIAvere == "0") echo "Non"; if ($entree->pathoCVASansAOMIAvere == "-1") echo "NSP"; ?> </td>
                <td style="color: #0000BB; text-align: center"> <?php if ($entree->dyslipidemies == "1") echo "Oui"; if ($entree->dyslipidemies == "0") echo "Non"; if ($entree->dyslipidemies == "-1") echo "NSP"; ?> </td>
                <td style="color: #0000BB; text-align: center"> <?php if ($entree->htaPermanente == "1") echo "Oui"; if ($entree->htaPermanente == "0") echo "Non"; if ($entree->htaPermanente == "-1") echo "NSP"; ?> </td>
                <td style="color: #0000BB; text-align: center"> <?php if ($entree->tabacActifOuCorrige == "1") echo "Oui"; if ($entree->tabacActifOuCorrige == "0") echo "Non"; if ($entree->tabacActifOuCorrige == "-1") echo "NSP"; ?> </td>
                <td style="color: #0000BB; text-align: center"> <?php if ($entree->dt2 == "1") echo "Oui"; if ($entree->dt2 == "0") echo "Non"; if ($entree->dt2 == "-1") echo "NSP"; ?> </td>
                <td style="color: #0000BB; text-align: center"> <?php if ($entree->dt1plus20 == "1") echo "Oui"; if ($entree->dt1plus20 == "0") echo "Non"; if ($entree->dt1plus20 == "-1") echo "NSP"; ?> </td>
                <td style="color: #0000BB; text-align: center"><?= $entree->commentaires ?></td>
                <?php
                if ($authorisation_suppression) {
                    ?>
                    <td>
                        <form action="<?= $config->psa_path ?>/controler/ActionControler.php?controlerparams:param:controler=DossierControler&controlerparams:param:action=AD&controlerparams:param:param1=SuppressionDepistage&controlerparams:param:param2=<?= $entree->id ?>" method="post">
                            <input type="hidden" name="dossier:dossier:numero" value="<?= $dossier->numero ?>">
                            <input type="submit" value="Supprimer la saisie">
                        </form>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>


    <?php
}
?>


