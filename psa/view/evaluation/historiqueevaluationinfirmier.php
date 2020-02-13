 <?php 	global $EvaluationInfirmierList; ?>
 <?php global $dossier ?>

<a href='#evalinf' onclick="affiche_detail('evalinf')">Afficher/masquer les évaluations</a>

<table width="75%"  border="1" cellspacing="0" cellpadding="0" style="display:none" id="evalinf">


  <tr>
    <th scope="col">&nbsp;Date</th>
	<th scope="col">&nbsp;Satisfaction</th>
	<th scope="col">&nbsp;Type de consultation</th>
	<th scope="col">&nbsp;Points positifs</th>
	<th scope="col">&nbsp;Points à améliorer</th>
	<th scope="col">&nbsp;Examens réalisés par délégation</th>
  </tr>
  <?php
    for($i=0;$i<count($EvaluationInfirmierList);$i++){
		  		$tmphisto = $EvaluationInfirmierList[$i]; 
?>

	  <tr>
    <td>&nbsp;<?php echo $tmphisto->date; ?></td>
	<td>&nbsp;<?php if ($tmphisto->degre_satisfaction == 'a+') echo "Très bon";
					if ($tmphisto->degre_satisfaction == 'a') echo "Bon";
					if ($tmphisto->degre_satisfaction == 'b') echo "Moyen";
					if ($tmphisto->degre_satisfaction == 'c') echo "Mauvais";
					if ($tmphisto->degre_satisfaction == 'd') echo "Très mauvais";
			 ?></td>
	<td>&nbsp;<?php foreach($tmphisto->type_consultation as $consult) echo $type_consult[$consult].", ";
			 ?></td>
	<td>&nbsp;<?php echo $tmphisto->points_positifs ?></td>
	<td>&nbsp;<?php echo $tmphisto->points_ameliorations ?></td>
	<td>&nbsp;<?php if($tmphisto->ecg==1){ echo "ECG, ";}
					if($tmphisto->ecg_seul==1){ echo "ECG seul, ";}
					if($tmphisto->monofil==1){ echo "Monofilament, ";}
					if($tmphisto->exapied==1){ echo "Examen des pieds, ";}
					if($tmphisto->spirometre==1){ echo "Spirometre, ";}
					if($tmphisto->spirometre_seul==1){ echo "Spirometre seule, ";}
					if($tmphisto->hba==1){ echo "Prescription HBA1c, ";}
					if($tmphisto->t_cognitif==1){ echo "Prescription repérage trouble cognitif, ";}
					// if($tmphisto->tension==1){ echo "Tension, ";}
					if($tmphisto->autre==1){ echo "Autre : ".$tmphisto->prec_autre;
				

			}?></td>

  </tr>
  <!--<tr><td colspan="6"><?php var_dump($tmphisto);?></td></tr>-->
  <?php }?>
</table>




