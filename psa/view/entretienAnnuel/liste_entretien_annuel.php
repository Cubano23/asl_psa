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
            Le formulaire saisi est accessible uniquement sur PSA par le rédacteur et la personne avec laquelle l'entretien
            a été réalisé (celle-ci étant identifiée par son certificat PSA).
            <br>
            Toutes les entreprises doivent mettre en place les entretiens professionnels avec tous leurs salariés, et ce,
            quel que soit leur effectif.
            <br>
            Cet entretien est centré sur le salarié et son parcours professionnel. Il établit le bilan de l'année écoulée
            (missions et activités réalisées au regard des objectifs visés, difficultés rencontrées, points à améliorer, etc.) et
            fixe les objectifs pour l¿année à venir.
            <br>
            Il permet de mieux l'accompagner dans ses perspectives d'évolution professionnelle (changement de poste,
            promotion, etc.), et d'identifier ses besoins de formation.
            <br>
            L'entretien professionnel doit être mené tous les 2 ans à compter de son entrée dans l'entreprise. Au bout de 6
            ans de présence, cet entretien permet de faire un état des lieux récapitulatifs du parcours professionnel du
            salarié. » PV de la DUP du 26 Mars 2018
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
            Infirmière ajout
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

