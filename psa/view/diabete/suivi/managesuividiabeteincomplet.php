<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $dossier; ?>
<?php global $suiviDiabete ?>
<?php global $param ?>


<?php /*$biLanArray = array(PARAM_SYSTEMATIQUE=>"Systématique",PARAM_4MOIS=>"4 mois",PARAM_SEMESTRIEL=>"Semestriel",PARAM_ANNUEL=>"Annuel"); */ ?>
<?php $biLanArray = array(PARAM_SYSTEMATIQUE=>"Systématique",PARAM_4MOIS=>"4 mois",PARAM_ANNUEL=>"Annuel"); ?>
<?php $BilanArray2 = array(PARAM_INCOMPLET=>"Incomplet", PARAM_COMPLET=>"Complet", PARAM_TOUS=>"Tous"); ?>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm"> 
  <?php hiddenControler("SuiviDiabeteControler"); ?> 
  <?php hiddenAction(ACTION_LIST); ?> 
  <?php hiddenParam1(PARAM_INCOMPLETE); ?>
  <br> 
  <table border="0"> 

      <tr> 
        <td>Cabinet</td> 
        <td><?php typePropertyValue("account:cabinet");?></td> 
      </tr> 
      <tr> 
        <td>Type de bilan</td> 
        <td><?php selectv("","param:param2",$biLanArray);?></td> 
      </tr>
      <tr>
      	<td>Choix des bilans </td>
      	<td><?php selectv("","param:param3", $BilanArray2);?></td>
      </tr>
      <tr>
        <td colspan="2"><br><input type="submit" name="submit" value="Chercher"> </td> 
      </tr>
  </table> 
</form> 
