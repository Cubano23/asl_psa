<?php global $dossier;?>
<?php global $EvalContinue;?>
<?php global $account;?>
<?php global $liste_eval_continue;?>

<script language="javascript">
	function checkContinue(aForm){
		var i;
		var submitOk = 1;
		var sOk;
		<?php
		$js = new JSValidation();		

		$nb_eval=count($liste_eval_continue);
		
		for($i=0;$i<$nb_eval;$i++){
			$js->dateInRange("EvalContinue$i:date","Date évaluation continue d'éducation T$i");
		}
		
?>
		if(document.getElementById("dateevalcontinue").value!=''){
			<?php $js->dateInRange("EvalContinue:date","Date évaluation continue d'éducation");?>
		}

		return submitOk;
		}
</script>

<h1><?php echo $item."- ";?>Evaluation continue d'éducation</h1>

Légende : A=Acquis &nbsp;&nbsp;&nbsp; AC=A conforter &nbsp;&nbsp;&nbsp; NA=Non acquis<br>
SO=Sans objet &nbsp;&nbsp;&nbsp; SI = Séances individuelles &nbsp;&nbsp;&nbsp; SC = Séances collectives<br>
<table border='1'>
	<tr><td colspan='2' rowspan='3'>&nbsp;</td>
		<td>Séance</td>
		<?php
		if($liste_eval_continue!=""){
			for($i=0;$i<=count($liste_eval_continue);$i++){
				$EvalContinuei="EvalContinue$i";
				global $$EvalContinuei;
				
				echo "<td>T$i</td>";
			}
		}
		else{
			echo "<td>T0</td>";
		}
?>
	</tr>
	<tr><td>Date</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".mysqlDateTodate($liste_eval_continue[$i]["date"])."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:date");
			echo "</td>";
			hidden("","EvalContinue$i:numero_eval");
		}
	}
	?>
	<td><?php text("size='10' id='dateevalcontinue' onkeyup='formate_date(this)'","EvalContinue:date"); ?></td>
	</tr>
	<tr><td>Suivi</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".$liste_eval_continue[$i]["suivi"]."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:suivi");
			echo "</td>";
		}
	}
	?>
	
	<td><?php text("size='10'","EvalContinue:suivi"); ?></td>
	</tr>
	<tr><td rowspan='2'>Connaissance de la maladie</td>
		<td colspan='2'>Comprendre les causes et mécanismes de la maladie</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".$liste_eval_continue[$i]["causes"]."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:causes");
			echo "</td>";
		}
	}
	?>
		<td><?php text("size='10'","EvalContinue:causes"); ?></td>
	</tr>
	<tr><td colspan='2'>Comprendre la terminologie</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".$liste_eval_continue[$i]["terminologie"]."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:terminologie");
			echo "</td>";
			
		}
	}
	?>
		<td><?php text("size='10'","EvalContinue:terminologie"); ?></td>
	</tr>
	<tr><td rowspan='2'>Gestion du traitement</td>
		<td colspan='2'>Comprendre son traitement</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".$liste_eval_continue[$i]["comprendre_traitement"]."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:comprendre_traitement");
			echo "</td>";
		}
	}
	?>
		<td><?php text("size='10'","EvalContinue:comprendre_traitement"); ?></td>
	</tr>
	<tr><td colspan='2'>Appliquer son traitement</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".$liste_eval_continue[$i]["appliquer_traitement"]."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:appliquer_traitement");
			echo "</Td>";
		}
	}
	?>
		<td><?php text("size='10'","EvalContinue:appliquer_traitement"); ?></td>
	</tr>
	<tr><td rowspan='4'>Prévention et gestion des risques</td>
		<td colspan='2'>Reconnaitre les risques</Td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".$liste_eval_continue[$i]["risques"]."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:risques");
			echo "</td>";
		}
	}
	?>
		<td><?php text("size='10'","EvalContinue:risques"); ?></td>
	</tr>
	<tr><td colspan='2'>Reconnaitre les signes de gravité</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".$liste_eval_continue[$i]["gravite"]."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:gravite");
			echo "</td>";
		}
	}
	?>
		<td><?php text("size='10'","EvalContinue:gravite"); ?></td>
	</tr>
	<tr><td colspan='2'>Connaître les mesures en fonction de la situation</Td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".$liste_eval_continue[$i]["mesures"]."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:mesures");
			echo "</td>";
		}
	}
	?>
		<td><?php text("size='10'","EvalContinue:mesures"); ?></td>
	</tr>
	</tr><td colspan='2'>Les appliquer</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".$liste_eval_continue[$i]["appliquer"]."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:appliquer");
			echo "</td>";
		}
	}
	?>
		<td><?php text("size='10'","EvalContinue:appliquer"); ?></td>
	</tr>
	<tr><td rowspan='2'>Alimentation</td>
		<td colspan='2'>Connaître les principes de l'équilibre alimentaire</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".$liste_eval_continue[$i]["connaitre_equilibre"]."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:connaitre_equilibre");
			echo "</td>";
		}
	}
	?>
		<td><?php text("size='10'","EvalContinue:connaitre_equilibre"); ?></td>
	</tr>
	<tr><td colspan='2'>Appliquer l'équilibre alimentaire</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".$liste_eval_continue[$i]["appliquer_equilibre"]."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:appliquer_equilibre");
			echo "</td>";
		}
	}
	?>
		<td><?php text("size='10'","EvalContinue:appliquer_equilibre"); ?></td>
	</tr>
	<tr><td rowspan='2'>Vie quotidienne</td>
		<td colspan='2'>Pratiquer une activité physique régulière</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".$liste_eval_continue[$i]["activite"]."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:activite");
			echo "</td>";
		}
	}
	?>
		<td><?php text("size='10'","EvalContinue:activite"); ?></td>
	</tr>
	<tr><td colspan='2'>Autres</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			// echo "<td>".$liste_eval_continue[$i]["autre"]."</td>";
			echo "<td>";
			text("size='10'","EvalContinue$i:autre");
			echo "</td>";
		}
	}
	?>
		<td><?php text("size='10'","EvalContinue:autre"); ?></td>
	</tr>
	</table>
	<br>
<br><br>

