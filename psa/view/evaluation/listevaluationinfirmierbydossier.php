<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>

<?php global $account; ?>
<?php global $rowsList; ?>
<?php global $param; ?>
<?php global $currentObjectName; ?>
<?php global $currentObjectClass; ?>
<?php global $signature; ?>



<table width="75%"  border="1" cellspacing="0" cellpadding="0">
 
  <CAPTION  ><?php echo(count($rowsList)) ?> enregistrements trouvés</CAPTION>
  
  <tr>
    <th scope="col">&nbsp;Dossier</th>
    <th scope="col">&nbsp;Sexe</th>
    <th scope="col">&nbsp;Date de naissance</th>
    <th scope="col">&nbsp;Réponse</th>
	<th scope="col">&nbsp;Satisfaction</th>
	<th scope="col">&nbsp;Type de consultation</th>
	<th scope="col">&nbsp;Examens réalisés par délégation</th>
	<th scope="col">&nbsp;Points positifs</th>
	<th scope="col">&nbsp;Points à améliorer</th>	
    <th scope="col">&nbsp;Consulter</th>
  </tr>
  <?php for($i=0;$i<count($rowsList);$i++){ ?>
  <tr>
    <td>&nbsp;<?php echo(getDoubleArrayElement($rowsList,$i,"numero")); ?></td>
    <td>&nbsp;<?php echo($sexe[getDoubleArrayElement($rowsList,$i,"sexe")]); ?></td>
    <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"dnaiss"))); ?></td>
    <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"date"))); ?></td>	
	<td>&nbsp;<?php echo($satisfaction[getDoubleArrayElement($rowsList,$i,"degre_satisfaction")]); ?></td>
	<td>&nbsp;<?php $type = getDoubleArrayElement($rowsList,$i,"type_consultation");
	                $type = explode(",",$type);
	                foreach($type as $t)
						echo($type_consult[$t]).', '; ?></td>
	<td>&nbsp;<?php if(getDoubleArrayElement($rowsList, $i, "ecg")==1){ echo "ECG, ";}
					if(getDoubleArrayElement($rowsList, $i, "ecg_seul")==1){ echo "ECG Seul, ";}
					if(getDoubleArrayElement($rowsList, $i, "monofil")==1){ echo "Monofilament, ";}
					if(getDoubleArrayElement($rowsList, $i, "exapied")==1){ echo "Examen des pieds, ";}
					if(getDoubleArrayElement($rowsList, $i, "hba")==1){ echo "Presciption HBA1c, ";}
					// if(getDoubleArrayElement($rowsList, $i, "tension")==1){ echo "Tension, ";}
					if(getDoubleArrayElement($rowsList, $i, "spirometre")==1){ echo "Spiromètre, ";}
					if(getDoubleArrayElement($rowsList, $i, "spirometre_seul")==1){ echo "Spiromètre seule, ";}
					if(getDoubleArrayElement($rowsList, $i, "autre")==1){ echo "Autre : ".getDoubleArrayElement($rowsList, $i, "prec_autre");}?></td>
	<td>&nbsp;<?php echo(getDoubleArrayElement($rowsList,$i,"points_positifs")); ?></td>
	<td>&nbsp;<?php echo(getDoubleArrayElement($rowsList,$i,"points_ameliorations")); ?></td>
    <td>&nbsp;<?php
				 $additionalParams = array("Dossier:dossier:numero"=>getDoubleArrayElement($rowsList,$i,"numero"),
				 						   "$currentObjectClass:$currentObjectName:date"=>mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"date")));
				 buildLink("","Consulter","$path/controler/ActionControler.php",$param->controler,ACTION_FIND,array(PARAM_VIEW),$additionalParams); 
			  ?>
	</td>
  </tr>
  <?php }?>
</table>



