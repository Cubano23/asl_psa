<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $dossier; ?>
<?php global $suiviDiabete ?>
<?php global $param ?>

 <form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler(""); ?>
<?php hiddenAction(""); ?>
<?php hiddenParam1(""); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","suiviDiabete:dsuivi"); ?>

<?php require("view/common/dossierresume.php");?>

<table border='0' width='100%' align='center'> 
  <tr> 
    <td valign='top'>  
      <table border='1' cellpadding='3'>  
        <tr> 
          <td>Type de bilan</td> 
          <td><font color='blue'><b>4 mois</b></font></td> 
        </tr> 
    </table></td> 
    <td><table border='1' cellpadding='3'> 
        <tr> 
          <th>Test</th> 
          <th>R�sultat(s)</th> 
        </tr> 
        <tr> 
          <td>R�sultat analyse HBA1c</td> 
          <td><?php if($suiviDiabete->ResHBA) { echo($suiviDiabete->ResHBA) ?> %<?php } ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>Date analyse HBA1C</td> 
          <td><?php if($suiviDiabete->dHBA) echo($suiviDiabete->dHBA) ?>&nbsp</td> 
        </tr> 
      </table> 
      <br>
	          <?php
				$additionalParams = array("Dossier:dossier:id"=>$suiviDiabete->dossier_id,
						"SuiviDiabete:suiviDiabete:dsuivi"=>$suiviDiabete->dsuivi);
				buildLink("","Retour","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_VIEW,PARAM_SYSTEMATIQUE),$additionalParams); 
			?>
	  </td> 
  </tr> 
</table> 
</form>
