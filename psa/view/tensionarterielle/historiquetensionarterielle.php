<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account ?>
<?php global $dossier ?>
<?php global $param ?>
<?php global $tensionArterielleMoyenneList;?>


<table border="1" cellpadding='3'>
    <CAPTION>Liste des <?php echo(count($tensionArterielleMoyenneList)); ?> suivis trouv�s</CAPTION>
    <tr>
        <th>D�but</th>
        <th>Dur�e</th>
        <th>Matin</th>
        <th>Soir</th>
        <th>Moyenne</th>
        <th>D�tail</th>
    </tr>
    <?php for($i=0;$i<count($tensionArterielleMoyenneList);$i++){
        $tmp = $tensionArterielleMoyenneList[$i]; ?>

        <tr>
            <td align="center"><?php echo($tmp->date_debut) ?></td>
            <td align="center"><?php echo $tmp->nombre_jours."j";	?></td>
            <td align="center"><?php echo $tmp->moyenne_sys_matin."/".$tmp->moyenne_dia_matin; ?></td>
            <td align="center"><?php echo $tmp->moyenne_sys_soir."/".$tmp->moyenne_dia_soir; ?></td>
            <td align="center"><?php echo $tmp->moyenne_sys."/".$tmp->moyenne_dia; ?></td>
            <td align="center"><?php

                $dt=preg_split('`[-/]`', $tmp->date_debut, 3);
                //print_r($dt);
                $date_deb=$dt[2]."-".$dt[1].'-'.$dt[0];
                $req="select date_debut, date_add(date_debut, interval (nombre_jours-1) day) as date_fin".
                    " from tension_arterielle_moyenne where id = '".$dossier->id."' and date_debut = '$date_deb'";
                //echo $req;

                $res=mysql_query($req) or die("erreur SQL:".mysql_error()."<br />$req");
                $ligne=mysql_fetch_assoc($res);

                $additionalParams = array("Dossier:dossier:id"=>$tmp->id,
                    "tensionArterielleManagement:tensionArterielleManagement:dateDebut"=>$date_deb,
                    "tensionArterielleManagement:tensionArterielleManagement:dateFin"=>$ligne['date_fin'],
                    "tensionArterielleManagement:tensionArterielleManagement:nombreJours"=>$tmp->nombre_jours);
                buildLink("","d�tail","$path/controler/ActionControler.php","TensionArterielleControler",ACTION_FIND, "", $additionalParams);
                ?>
            </td>
        </tr>   

    <?php } ?>
</table>

