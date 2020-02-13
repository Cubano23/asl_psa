<?php
/**
 * Created by PhpStorm.
 * User: gisgo
 * Date: 25/06/18
 * Time: 17:47
 */

global $account;
global $dossier;
global $param;
global $depistage_aomi;
global $form_class;
global $dateSaisie;
$form_class = "Dépistage de l'AOMI";

?>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post">


    <input type="hidden" name="DepistageAOMI:DepistageAOMI:dateSaisie" value="<?php echo $dateSaisie ?>">
    <?php
        hiddenControler("DepistageAOMIControler");
        hiddenAction(ACTION_SAVE);

        require("view/depistage/depistage_aomi.php");
    ?>

</form>

<script language="JavaScript" type="text/javascript">
    function affiche_detail(element){
        var element=document.getElementById(element);

        if(element.style.display=='none')
        {
            element.style.display='';
        }
        else
        {
            element.style.display='none';
        }
    }
</script>
