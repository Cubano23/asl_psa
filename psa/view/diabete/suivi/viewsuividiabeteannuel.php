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
      <table border='1'  cellpadding='3'>  
        <tr> 
          <td>Type de bilan</td> 
          <td><font color='brown'><b>annuel</b></font></td> 
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
        </tr>-->
        <tr>
          <td>Date examen au filament</td>
          <td><?php echo($suiviDiabete->dExaFil); ?>&nbsp;</td>
        </tr>
<!--        <tr>
          <td>Examen des pieds</td>
          <td><?php echo($suiviDiabete->ExaPieds); ?>&nbsp;</td>
        </tr>-->
        <tr>
          <td>Date examen des pieds</td>
          <td><?php echo($suiviDiabete->dExaPieds); ?>&nbsp;</td>
        </tr>

        <tr> 
          <td>Mesure Cholestérol HDL</td> 
          <td><?php echo($suiviDiabete->HDL); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>HDL pathologique</td> 
          <td><?php echo($suiviDiabete->iChol); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>Date Cholestérol HDL</td> 
          <td><?php echo($suiviDiabete->dChol); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>Mesure LDL</td> 
          <td><?php echo($suiviDiabete->LDL); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>LDL pathologique</td> 
          <td><?php echo($suiviDiabete->iLDL); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>Date Cholestérol LDL</td> 
          <td><?php echo($suiviDiabete->dLDL); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>Mesure Créatininémie</td> 
          <td><?php echo($suiviDiabete->Creat); ?>&nbsp;</td>
        </tr> 
        <tr> 
          <td>Clearance calculée</td> 
          <td><?php echo($suiviDiabete->getClearance($dossier))?></td>
        </tr> 
        <tr> 
          <td>Créatinine pathologique</td> 
          <td><?php echo($suiviDiabete->iCreat); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>Date créatinine</td> 
          <td><?php echo($suiviDiabete->dCreat); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>Micro Albuminurie</td> 
          <td><?php echo($suiviDiabete->iAlbu); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>Date Micro-Albuminurie</td> 
          <td><?php echo($suiviDiabete->dAlbu); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>Fond d'&#339;il</td> 
          <td><?php if($suiviDiabete->iFond) echo("Réalisé"); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>Date Fond d'&#339;il</td> 
          <td><?php echo($suiviDiabete->dFond); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>ECG de repos</td> 
          <td><?php if($suiviDiabete->iECG) echo("Réalisé"); ?>&nbsp;</td> 
        </tr> 
        <tr> 
          <td>Date ECG de repos</td> 
          <td><?php echo($suiviDiabete->dECG); ?>&nbsp;</td> 
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
