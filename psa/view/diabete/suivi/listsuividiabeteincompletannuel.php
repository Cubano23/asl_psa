<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>
<?php require_once("persistence/DossierMapper.php"); ?>
<?php require_once("persistence/SuiviDiabeteMapper.php"); ?>

<?php global $account ?>
<?php global $param ?>
<?php global $rowsList ?>

<table border="1" align='center'> 
  <CAPTION>
   Liste des <?php echo(count($rowsList)); ?> suivis <?php if ($param->param3 == 'PINCOMP') echo "incomplets";
   							     elseif ($param->param3 == 'PCOMP') echo "complets";
   							     elseif ($param->param3 == 'PTOUS') echo "complets et incompets";?>
   		de type annuel pour le cabinet <?php echo($account->cabinet); ?> 
  </CAPTION> 
  <tr> 
    <th>Dossier</th> 
    <th>Date</th> 
    <th>Modifier</th> 
<!--    <th><font size='-1'>Examen des pieds au filament</font></th>-->
    <th><font size='-1'>Date examen au filament</font></th>
<!--    <th><font size='-1'>Examen des pieds</font></th>-->
    <th><font size='-1'>Date examen des pieds</font></th>
    <th><font size='-1'>Mesure Cholestérol HDL</font></th>
    <th><font size='-1'>Date Cholestérol HDL</font></th>
    <th><font size='-1'>Mesure LDL</font></th>
    <th><font size='-1'>Date Cholestérol LDL</font></th> 
    <th><font size='-1'>Mesure Créatininémie</font></th> 
    <th><font size='-1'>Clearance calculée</font></th> 
    <th><font size='-1'>Date créatinine</font></th> 
    <th><font size='-1'>Date Micro-Albuminurie</font></th> 
    <th><font size='-1'>Date Fond d'&#339;il</font></th> 
    <th><font size='-1'>Date ECG de repos</font></th> 
  </tr> 
  
  <?php 
  	$dossierMapper = new DossierMapper();
  	$suiviDiabeteMapper = new SuiviDiabeteMapper();
  ?>
  <?php for($i=0;$i<count($rowsList);$i++){ 
   			$dossier = $dossierMapper->doLoadObject($rowsList[$i]);
			$dossier = $dossier->afterDeserialisation($account);
   			$suiviDiabete = $suiviDiabeteMapper->doLoadObject($rowsList[$i]);
			$suiviDiabete = $suiviDiabete->afterDeserialisation($account);
  ?>
  <tr> 
    <td>&nbsp;<?php echo($dossier->numero); ?></td> 
    <td>&nbsp;<?php echo($suiviDiabete->dsuivi); ?></td> 
    <td>&nbsp; 
      <?php
			$additionalParams = array("Dossier:dossier:numero"=>getDoubleArrayElement($rowsList,$i,"numero"),
					"SuiviDiabete:suiviDiabete:dsuivi"=>mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"dsuivi")));
/*			$additionalParams = array(getPropertyName("dossier:numero")=>$dossier->numero,
						getPropertyName("suiviDiabete:dsuivi")=>$suiviDiabete->dsuivi);
*/			buildLink("","Modifier","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_EDIT),$additionalParams); 
			
			
		?> </td> 
<!--    <td align='center'>&nbsp;<?php echo($suiviDiabete->ExaFil); ?>-->
    <td align='center'>&nbsp;<?php echo($suiviDiabete->dExaFil); ?>
    <!--<td align='center'>&nbsp;<?php echo($suiviDiabete->ExaPieds); ?>-->
    <td align='center'>&nbsp;<?php echo($suiviDiabete->dExaPieds); ?>
    <td align='center'>&nbsp;<?php echo($suiviDiabete->HDL); ?>
    <td align='center'>&nbsp;<?php echo($suiviDiabete->dChol); ?>
    <td align='center'>&nbsp;<?php echo($suiviDiabete->LDL); ?>
    <td align='center'>&nbsp;<?php echo($suiviDiabete->dLDL); ?>
    <td align='center'>&nbsp;<?php echo($suiviDiabete->Creat); ?>
    <td align='center'>&nbsp;<?php echo($suiviDiabete->getClearance($dossier)); ?>
    <td align='center'>&nbsp;<?php echo($suiviDiabete->dCreat); ?>
    <td align='center'>&nbsp;<?php echo($suiviDiabete->dAlbu); ?>
    <td align='center'>&nbsp;<?php echo($suiviDiabete->dFond); ?> 
    <td align='center'>&nbsp;<?php echo($suiviDiabete->dECG); ?> 
  </tr> 
  
  <?php }?>
</table> 
