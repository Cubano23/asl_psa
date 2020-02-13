<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $param ?>
<?php global $Conges;  ?>

<script type="text/javascript" >
    <?php
    validateNumeroDossier();
    validateDate();
    $js = new JSValidation();
    $js->startCheckFunction("validateInput","aForm");
    $js->validateDate("suiviDiabete:dsuivi","Date du suivi");
    $js->endCheckFunction();
    ?>

    function affiche_autre(){
        var nature = document.getElementById("nature");
        var nat = nature.options[nature.selectedIndex].value;

        if(nat=="autres"){
            document.getElementById("texte_autre_conge").style.display="";
            document.getElementById("autre_conge").style.display="";
        }
        else{
            document.getElementById("texte_autre_conge").style.display="none";
            document.getElementById("autre_conge").style.display="none";
        }

    }
</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm">
    <?php hiddenControler("CongesControler"); ?>
    <?php /*hiddenAction(ACTION_MANAGE); ?>
	<?php hiddenParam1(PARAM_CREATE);*/ ?>
    <?php hiddenAction(ACTION_SAVE); ?>
    <?php hidden("","Conges:id");?>

    <?php
    $nature=array(""=>"",
        "paye"=>"Congés payés",
        "sanssolde"=>"Congés sans solde",
        "autres"=>"autres");
    ?>
    <style type="text/css">
        .btn{
            width:100%;
        }
    </style>

    <table border="0">
        <tr>
            <td>Nom de l'infirmiere : </td>
            <td><input type="hidden" name="Conges:Conges:nom" value="<?php echo utf8_decode($_SESSION['id.nom']);?>">
                <input type="hidden" name="Conges:Conges:prenom" value="<?php echo utf8_decode($_SESSION['id.prenom']);?>">
                <?php echo utf8_decode($_SESSION['id.prenom']). ' '.utf8_decode($_SESSION['id.nom']);?>
            <td>
                <!--Popup-->
                <a href='javascript://' onmouseover="ajax_showTooltip('<?= $path ?>/view/conges/aide_saisie.html',this);return false"><img src='<?= $path ?>/view/login/img/puces/aide.gif'></a>
            </td>
            </td>
        </tr>

        <tr>
            <td>Date du 1er jour d'absence</td>
            <td colspan='2'><?php text("size='10' onkeyup='formate_date(this)'","Conges:date_debut"); ?> Format jj/mm/aaaa</td>
        </tr>
        <tr>
            <td>Veille de la reprise ASALEE</td>
            <td colspan='2'><?php text("size='10' onkeyup='formate_date(this)'","Conges:date_fin"); ?> Format jj/mm/aaaa</td>
        </tr>
        <tr>
            <td>Nature du congé</td>
            <td><?php selectv("onchange='affiche_autre()' id='nature'","Conges:nature",$nature) ?>

            </td>
            <td style='display:none' id='texte_autre_conge'>Préciser le type de congés : </td>
            <td style='display:none' id='autre_conge'><?php text("","Conges:prec"); ?></td>

        <tr>
    </table><br>
    <?php

    customSubmit("value='Enregistrer' ",ACTION_SAVE,"", "","validateInput"); ?><br>

</form> 

