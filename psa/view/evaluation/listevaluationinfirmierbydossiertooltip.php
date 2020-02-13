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


<div style="float:right;color:#fff;"><?php echo "<a style='color:#fff;' title='fermer' href='javascript://' onclick=\"ajax_hideTooltip()\">X</a><br>";?></div>
<table width="100%"  border="1" cellspacing="0" cellpadding="0">
 
  <CAPTION  ><?php echo(count($rowsList)) ?> enregistrements trouv&eacute;s</CAPTION>
  
  <tr>
    <th scope="col">&nbsp;Date consultation</th>
	<th scope="col">&nbsp;Satisfaction</th>
	<th scope="col">&nbsp;Type de consultation</th>
	<th scope="col">&nbsp;Examens r&eacute;alis&eacute;s par d&eacute;l&eacute;gation</th>
	<th scope="col">&nbsp;Points positifs</th>
	<th scope="col">&nbsp;Points &agrave; am&eacute;liorer</th>	
	<!-- <th scope="col">&nbsp;Aspects limitant</th>	
	<th scope="col">&nbsp;Aspects facilitant</th>	
	<th scope="col">&nbsp;Objectifs patient</th>	 -->
  </tr>
  <?php for($i=0;$i<count($rowsList);$i++){ ?>
  <tr>
    <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($rowsList,$i,"date"))); ?></td>	
	<td>&nbsp;<?php echo htmlentities($satisfaction[getDoubleArrayElement($rowsList,$i,"degre_satisfaction")]); ?></td>
	<td>&nbsp;<?php $type = getDoubleArrayElement($rowsList,$i,"type_consultation");
	                $type = explode(",",$type);
	                foreach($type as $t)
						echo htmlentities($type_consult[$t]).', '; ?></td>
	<td>&nbsp;<?php if(getDoubleArrayElement($rowsList, $i, "ecg")==1){ echo "ECG, ";}
					if(getDoubleArrayElement($rowsList, $i, "ecg_seul")==1){ echo "ECG seul, ";}
					if(getDoubleArrayElement($rowsList, $i, "monofil")==1){ echo "Monofilament, ";}
					if(getDoubleArrayElement($rowsList, $i, "exapied")==1){ echo "Examen des pieds, ";}
					if(getDoubleArrayElement($rowsList, $i, "hba")==1){ echo "Presciption HBA1c, ";}
					if(getDoubleArrayElement($rowsList, $i, "spirometre")==1){ echo "Spirométrie, ";}
					if(getDoubleArrayElement($rowsList, $i, "spirometre_seul")==1){ echo "Spirométrie seule, ";}
					if(getDoubleArrayElement($rowsList, $i, "hba")==1){ echo "Presciption HBA1c, ";}
					// if(getDoubleArrayElement($rowsList, $i, "tension")==1){ echo "Tension, ";}
					if(getDoubleArrayElement($rowsList, $i, "autre")==1){ echo "Autre : ".htmlentities(getDoubleArrayElement($rowsList, $i, "prec_autre"));}?></td>
	<td>&nbsp;<?php echo htmlentities(getDoubleArrayElement($rowsList,$i,"points_positifs")); ?></td>
	<td>&nbsp;<?php echo htmlentities(getDoubleArrayElement($rowsList,$i,"points_ameliorations")); ?></td>
<!-- 	<td>&nbsp;<?php echo htmlentities(getDoubleArrayElement($rowsList,$i,"aspects_limitant")); ?></td>
	<td>&nbsp;<?php echo htmlentities(getDoubleArrayElement($rowsList,$i,"aspects_facilitant")); ?></td>
	<td>&nbsp;<?php echo htmlentities(getDoubleArrayElement($rowsList,$i,"objectifs_patient")); ?></td> -->
  </tr>
  <?php }?>
</table>



