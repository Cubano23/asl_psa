<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 16/10/18
 * Time: 12:01
 */


global $listeActivitePhysique;

?>
<table border="1" cellspacing="1" width="99%" >
    <tr>
        <td>
            FORMULAIRE PROVISOIRE
            <br>
            le formulaire est un premier jet pour la consutruction d'un protocole exp&eacute;rimental centr&eacute; autour du th&egrave;me de l'activit&eacute; physique.
            <br>
        </td>
    </tr>
</table>
    <br />
    <br />


<!--button><a href="../controler/ActionControler.php?controlerparams:param:controler=ActivitePhysiqueControler&controlerparams:param:action=AMEN" style="color: black"><b>Nouveau formulaire</b></a></button-->

<form action="<?php echo "$path/controler/ActionControler.php"?>" method="post">
    <?php
        hiddenControler("ActivitePhysiqueControler");
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
        <th  colspan="2"><i class="fas fa-user-edit fa-2x"></i></th>
    </tr>
    </thead>

    <tbody>
    <?php
    foreach ($listeActivitePhysique as $listeActPhys)
    {
        ?>

        <tr>
            <td>
                <?php
                    if ($listeActPhys->dossier_numero == -1)
                    {
                        echo "NA";
                    }
                    else
                    {
                        echo $listeActPhys->dossier_numero;
                    }
                ?>
            </td>
            <td>
                <?php echo $listeActPhys->infAjout ?>
            </td>
            <td>
                <?php 
                    $date_en = $listeActPhys->date_maj;
                    $tabDate = explode('-' , $date_en);
                    $date_fr  = $tabDate[2].'/'.$tabDate[1].'/'.$tabDate[0];
                    echo $date_fr; 
                ?>              
                
            </td>
            <form action="<?php echo "$path/controler/ActionControler.php"?>" method="post" name="formulaireActivitePhysique">
                <?php
                    hiddenControler("ActivitePhysiqueControler");
                    hiddenAction(ACTION_NEW);
                ?>
                <input type="hidden" name="ActivitePhysique:ActivitePhysique:id" value="<?= $listeActPhys->id ?>">
                <td>
                    
                    <input type="submit" value="Consulter/Modifier">
                </td>
            </form>
            <form action="<?php echo "$path/controler/ActionControler.php"?>" method="post" name="formulaireActivitePhysique">
                <?php
                hiddenControler("ActivitePhysiqueControler");
                hiddenAction(ACTION_HARD);
                ?>
                <input type="hidden" name="ActivitePhysique:ActivitePhysique:id" value="<?= $listeActPhys->id ?>">
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

