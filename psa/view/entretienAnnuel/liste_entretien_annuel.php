<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 02/07/18
 * Time: 20:05
 */
global $listeEntretienAnnuel;

?>
<table border="1" cellspacing="1" width="99%" >
    <tr>
        <td>
            Le formulaire saisi est accessible uniquement sur PSA par le r�dacteur et la personne avec laquelle l'entretien
            a �t� r�alis� (celle-ci �tant identifi�e par son certificat PSA).
            <br>
            Toutes les entreprises doivent mettre en place les entretiens professionnels avec tous leurs salari�s, et ce,
            quel que soit leur effectif.
            <br>
            Cet entretien est centr� sur le salari� et son parcours professionnel. Il �tablit le bilan de l'ann�e �coul�e
            (missions et activit�s r�alis�es au regard des objectifs vis�s, difficult�s rencontr�es, points � am�liorer, etc.) et
            fixe les objectifs pour l�ann�e � venir.
            <br>
            Il permet de mieux l'accompagner dans ses perspectives d'�volution professionnelle (changement de poste,
            promotion, etc.), et d'identifier ses besoins de formation.
            <br>
            L'entretien professionnel doit �tre men� tous les 2 ans � compter de son entr�e dans l'entreprise. Au bout de 6
            ans de pr�sence, cet entretien permet de faire un �tat des lieux r�capitulatifs du parcours professionnel du
            salari�. � PV de la DUP du 26 Mars 2018
            <br>
            <br>
        </td>
    </tr>
</table>
    <br />
    <br />


<!--<button><a href="../controler/ActionControler.php?controlerparams:param:controler=EntretienAnnuelControler&controlerparams:param:action=AMEN" style="color: black"><b>Nouveau formulaire</b></a></button>-->
<a href="../controler/ActionControler.php?controlerparams:param:controler=EntretienAnnuelControler&controlerparams:param:action=AMEN" style="color: black"><b>Nouveau formulaire</b></a>

<br /><br />

<table style="text-align: center; border-spacing: 0px;" cellspacing="40" border="1">
    <thead>
    <tr>

        
        <th>
            Infirmi�re ajout
        </th>
        <th>
            Date
        </th>
        <th colspan="2"></th>
    </tr>
    </thead>

    <tbody>
    <?php
    foreach ($listeEntretienAnnuel as $entretienAnnuel)
    {
        ?>

        <tr>
        
            <td>
                <?php echo $entretienAnnuel->infAjout ?>
            </td>
            <td>
                <?php echo $entretienAnnuel->dateAjout ?>
            </td>
            <form action="<?php echo "$path/controler/ActionControler.php"?>" method="post" name="formulaireEntretienAnnuel">
                <?php
                    hiddenControler("EntretienAnnuelControler");
                    hiddenAction(ACTION_NEW);
                ?>
                <input type="hidden" name="EntretienAnnuel:EntretienAnnuel:id" value="<?= $entretienAnnuel->id ?>">
                <td>
                    <input type="submit" value="Consulter">
                </td>
            </form>
            <form action="<?php echo "$path/controler/ActionControler.php"?>" method="post" name="formulaireEntretienAnnuel">
                <?php
                hiddenControler("EntretienAnnuelControler");
                hiddenAction(ACTION_HARD);
                ?>
                <input type="hidden" name="EntretienAnnuel:EntretienAnnuel:id" value="<?= $entretienAnnuel->id ?>">
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

