<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $param; ?>

<?php $ctrlSelect = array("DepistageCancerColonControler"=>"D�pistage Colon","DepistageCancerSeinControler"=>"D�pistage Sein",
					"DepistageCancerUterusControler"=>"D�pistage col de l'ut�rus",
					"DepistageDiabeteControler"=>"D�pistage Diab�te","HyperTensionArterielleControler"=>"Suivi HTA",
					"TroubleCognitifControler"=>"D�pistage troubles cognitifs",
          "EvaluationInfirmierControler"=>"Evaluation Infirmi�re", "CardioVasculaireDepartControler"=>"Rep�rage BPCO par spirom�trie",); 
          
          // "SevrageTabacControler"=>"Sevrage tabac",

          ?>


<form action="<?php echo("$path/controler/ActionControler.php"); ?>" method="post" name="manage">

<?php hiddenAction(ACTION_LIST); ?>
<?php hiddenParam1(PARAM_SPIRO); ?>
  <br>
  <table border="0">
      <tr>
        <td>Cabinet</td>
        <td>&nbsp;<?php typePropertyValue("account:cabinet"); ?></td>
      </tr>
      <tr>
        <td>Protocole</td>
        <td>&nbsp;<?php selectv("","param:controler",$ctrlSelect); ?></td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" name="submit" value="Valider"> 
        </td>
      </tr>
  </table>
</form>
<br>
