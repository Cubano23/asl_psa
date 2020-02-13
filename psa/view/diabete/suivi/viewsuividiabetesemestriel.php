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
    <td valign='top'> <br> 
      <table border='1' cellpadding='3'>  
        <tr> 
          <td>Type de bilan</td> 
          <td><font color='green'><b>semestriel</b></font></td> 
        </tr> 
    </table></td> 
    <td><table border='1' cellpadding='3'> 
        <tr> 
          <th>Test</th> 
          <th>Résultat(s)</th> 
        </tr> 
<!--        <tr> 
          <td>Examen des pieds au filament</td> 
          <td><?php echo($suiviDiabete->ExaFil); ?>&nbsp;</td> 
        </tr> -->
        <tr> 
          <td>Date examen au filament</td> 
          <td><?php echo($suiviDiabete->dExaFil); ?>&nbsp;</td> 
        </tr> 
 <!--       <tr> 
          <td>Examen des pieds</td> 
          <td><?php echo($suiviDiabete->ExaPieds); ?>&nbsp;</td> 
        </tr> -->
        <tr> 
          <td>Date examen des pieds</td> 
          <td><?php echo($suiviDiabete->dExaPieds); ?>&nbsp;</td> 
        </tr> 
      </table> <br>
     	 <?php
				$additionalParams = array("Dossier:dossier:id"=>$suiviDiabete->dossier_id,
						"SuiviDiabete:suiviDiabete:dsuivi"=>$suiviDiabete->dsuivi);
				buildLink("","Retour","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_VIEW,PARAM_SYSTEMATIQUE),$additionalParams); 
			?>    
		 </td> 
  </tr> 
  
</table> 
</form>
