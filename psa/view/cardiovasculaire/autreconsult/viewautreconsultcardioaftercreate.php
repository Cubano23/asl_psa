<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $AutreConsultCardio; ?>
<?php global $EvalContinue; ?>
<?php global $param; ?>
<?php global $liste_eval_continue;?>


<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("AutreConsultCardioControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","AutreConsultCardio:date"); ?>

<?php require("view/common/dossierresume.php");?>

<h1>1- Diagnostic éducatif - synthèse</h1>
 <table width='880' border="1" cellpadding='3'>
    <tr>
      <td valign='top'>Aspects limitants:</td>
      <td  width='70%' colspan='2'><?php echo nl2br($AutreConsultCardio->aspects_limitant); ?></td>
	</Tr>
    <tr>
      <td valign='top'>Aspects facilitants:</td>
      <td  width='70%' colspan='2'><?php echo nl2br($AutreConsultCardio->aspects_facilitant); ?></td>
	</Tr>
    <tr>
      <td valign='top'>Objectifs du patient:</td>
      <td  width='70%' colspan='2'><?php echo nl2br($AutreConsultCardio->objectifs_patient); ?></td>
	</Tr>
	</table>
	<br>

<h1>2- Evaluation continue d'éducation</h1>
Légende : A=Acquis &nbsp;&nbsp;&nbsp; AC=A conforter &nbsp;&nbsp;&nbsp; NA=Non acquis<br>
SO=Sans objet &nbsp;&nbsp;&nbsp; SI = Séances individuelles &nbsp;&nbsp;&nbsp; SC = Séances collectives<br>
<table border='1'>
	<tr><td colspan='2' rowspan='3'>&nbsp;</td>
		<td>Séance</td>
		<?php
		if($liste_eval_continue!=""){
			for($i=0;$i<count($liste_eval_continue);$i++){
				// $EvalContinuei="EvalContinue$i";
				// global $$EvalContinuei;

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
			echo "<td>".mysqlDateTodate($liste_eval_continue[$i]["date"])."</td>";
		}
	}
	?>
	</tr>
	<tr><td>Suivi</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["suivi"]."</td>";
		}
	}
	?>

	</tr>
	<tr><td rowspan='2'>Connaissance de la maladie</td>
		<td colspan='2'>Comprendre les causes et mécanismes de la maladie</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["causes"]."</td>";
		}
	}
	?>
	</tr>
	<tr><td colspan='2'>Comprendre la terminologie</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["terminologie"]."</td>";
		}
	}
	?>
	</tr>
	<tr><td rowspan='2'>Gestion du traitement</td>
		<td colspan='2'>Comprendre son traitement</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["comprendre_traitement"]."</td>";
		}
	}
	?>
	</tr>
	<tr><td colspan='2'>Appliquer son traitement</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["appliquer_traitement"]."</td>";
		}
	}
	?>
	</tr>
	<tr><td rowspan='4'>Prévention et gestion des risques</td>
		<td colspan='2'>Reconnaitre les risques</Td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["risques"]."</td>";
		}
	}
	?>
	</tr>
	<tr><td colspan='2'>Reconnaitre les signes de gravité</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["gravite"]."</td>";
		}
	}
	?>
	</tr>
	<tr><td colspan='2'>Connaître les mesures en fonction de la situation</Td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["mesures"]."</td>";
		}
	}
	?>
	</tr>
	</tr><td colspan='2'>Les appliquer</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["appliquer"]."</td>";
		}
	}
	?>
	</tr>
	<tr><td rowspan='2'>Alimentation</td>
		<td colspan='2'>Connaître les principes de l'équilibre alimentaire</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["connaitre_equilibre"]."</td>";
		}
	}
	?>
	</tr>
	<tr><td colspan='2'>Appliquer l'équilibre alimentaire</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["appliquer_equilibre"]."</td>";
		}
	}
	?>
	</tr>
	<tr><td rowspan='2'>Vie quotidienne</td>
		<td colspan='2'>Pratiquer une activité physique régulière</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["activite"]."</td>";
		}
	}
	?>
	</tr>
	<tr><td colspan='2'>Autres</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["autre"]."</td>";
		}
	}
	?>
	</tr>
	</table>


	<br>

<br>
<h1>3- Voir les difficultés et les progrès par rapport aux objectifs et fixer de nouveaux objectifs</h1>
	<table border='1'>
	<tr>
		<td width='150'><b>Objectifs</b></td>
			<td><b>Consultations précédentes</b></td>
			<td><b>Progrès / difficultés</b></td>
			<td><b>Nouveaux objectifs</b></td>
	</tr>
	<tr>
		<td width='150'>Poids <img OnClick="javascript:window.open('../premiereconsult/objectif_poids.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
			<td><?php echo nl2br($AutreConsultCardio->progres_poids);?></Td>
			<td><?php echo $AutreConsultCardio->obj_poids;?></Td>
	</tr>
	<tr>
		<td width='150'>Alcool <img OnClick="javascript:window.open('objectif_alcool.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</td>
			<td><?php echo nl2br($AutreConsultCardio->progres_alcool);?></Td>
			<td><?php echo $AutreConsultCardio->obj_alcool;?></Td>
	</Tr>
	<tr>
		<td width='150'>Tabac <img OnClick="javascript:window.open('../premiereconsult/objectif_tabac.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</Td>
			<td><?php echo nl2br($AutreConsultCardio->progres_tabac);?></Td>
			<td><?php echo $AutreConsultCardio->obj_tabac;?></Td>
	</Tr>
	<tr>
		<td width='150'>Tension <img OnClick="javascript:window.open('objectif_tension.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="Détail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</td>
			<td><?php echo nl2br($AutreConsultCardio->progres_tension);?></Td>
			<td><?php echo $AutreConsultCardio->obj_tension;?></Td>
	</tr>
	</table>
	<br>
	<h1>4- Ajustez les conseils prodigués</h1>

	<table border='1'>
	<tr>
		<td><b>Conseils prodigués</b></td>
			<td><b>Documents remis</b></Td>
				<td><b>Commentaire</b></td>
	</tr>
	<tr>
			<td width='300'>Consommation de sel</td>
			<td><?php if($AutreConsultCardio->brochure_sel1=="1") echo "...<br>";
					  if($AutreConsultCardio->brochure_sel2=="1") echo "... ";?>
				</td>
					<td><?php echo $AutreConsultCardio->commentaire_sel;?></td>
	</Tr>
	<tr>
			<td width='300'>Consommation d'alcool &lt; 2 verres/j pour une femme, 3 verres/j pour un homme</td>
			<td><?php if($AutreConsultCardio->brochure_alcool1=="1") echo "Alcool : votre corps se souvient de tout <img OnClick=\"javascript:window.open('$path/view/cardiovasculaire/docs/alcool2.pdf', '_blank');\" border=0 height='15' src='$path/view/images/imprimer.gif' width='15'><br>";
					  if($AutreConsultCardio->brochure_alcool2=="1") echo "...";?>
				</td>
					<td><?php echo $AutreConsultCardio->commentaire_alcool;?></td>
	</Tr>
	<tr>
			<td width='300'>Activité physique (équivalent de 30 min de marche rapide, 3 fois dans la semaine)</td>
			<td><?php if($AutreConsultCardio->brochure_activite1=="1") echo "Brochure \"Bouger c'est la santé\" <img OnClick=\"javascript:window.open('$path/view/cardiovasculaire/docs/ATT02695.pdf', '_blank');\" border=0 height='15' src='$path/view/images/imprimer.gif' width='15'><br>";
				      if($AutreConsultCardio->brochure_activite2=="1") echo "...";?>
				</td>
					<td><?php echo $AutreConsultCardio->commentaire_activite;?></td>
	</Tr>
	<tr>
			<td width='300'>Sevrage tabagique</td>
				<td><?php if($AutreConsultCardio->brochure_tabac1=="1") echo "La dépendance au tabac <img OnClick=\"window.open('$path/view/cardiovasculaire/docs/ATT02697.pdf', '_blank');\" border=0 height=\"15\" src='$path/view/images/imprimer.gif' width=\"15\"><br>";
						  if($AutreConsultCardio->brochure_tabac2=="1") echo "Les risques du tabagisme et les bénéfices de l'arrêt <img OnClick=\"window.open('$path/view/cardiovasculaire/docs/risque_tabac.pdf', '_blank');\" border=0 height=\"15\" src='$path/view/images/imprimer.gif' width=\"15\">";?>
					</td>
					<td><?php echo $AutreConsultCardio->commentaire_tabac;?></td>
	</Tr>
	<tr>
			<td width='300'>Contrôle du poids</td>
				<td><?php if($AutreConsultCardio->brochure_poids1=="1") echo "Pense-bête nutrition <img OnClick=\"$path/view/cardiovasculaire/docs/ATT02699.pdf', '_blank');\" border=0 height=\"15\" src='$path/view/images/imprimer.gif' width=\"15\"><br>";
						  if($AutreConsultCardio->brochure_poids2=="1") echo "...";?>
					</td>
					<td><?php echo $AutreConsultCardio->commentaire_poids;?></td>
	</Tr>
	<tr>
			<td width='300'>Alimentation riche en fruits et légumes</td>
				<td><?php if($AutreConsultCardio->brochure_alim1=="1") echo "La santé vient en mangeant <img OnClick=\"window.open('$path/view/cardiovasculaire/docs/ATT02701.pdf', '_blank');\" border=0 height=\"15\" src='$path/view/images/imprimer.gif' width=\"15\"><br>";
						  if($AutreConsultCardio->brochure_alim2=="1") echo "...";?>
					</td>
					<td><?php echo $AutreConsultCardio->commentaire_alim;?></td>
	</Tr>
	<tr>
			<td width='300'>Diminution des excitants (café, thé, réglisse)</td>
				<td><?php if ($AutreConsultCardio->brochure_cafe1=="1") echo "...<br>";
						  if ($AutreConsultCardio->brochure_cafe2=="1") echo "...";?>
					</td>
					<td><?php echo $AutreConsultCardio->commentaire_cafe;?></td>
	</Tr>
	</table>
	<br>
	<h1>5- Indicateurs d'observance des traitements médicamenteux</h1>
	<table border='1'>
	  <tr>
	  	<td>Problèmes rencontrés</td>
	  		<td>Commentaire</td>
	  </tr>
	  <tr>
	    <td>Qualité de vie par rapport au traitement</td><td>
			<?php echo $AutreConsultCardio->detail_qualite_vie;?>
		</td>
	   </tr>
	   <tr>
	    <td>Effets secondaires</td><td>
			<?php echo $AutreConsultCardio->detail_secondaire;?>
		</td>
	   </tr>
	   <tr>
	    <td>Délivrance des traitements</td><td>
			<?php echo $AutreConsultCardio->detail_delivrance;?>
		</td>
	   </tr>
	   <tr>
	    <td>Régularité des prises</td><td>
			<?php echo $AutreConsultCardio->detail_regularite;?>
		</td>
	   </tr>
	</table>
  <br>
	<h1>6- Bilan de consultation</h1>
   <table width='850' border="1" cellpadding='3'>
    <tr>
      <td>Degré de satisfaction:</td>
      <td colspan='2'><?php echo($satisfaction[getPropertyValue("AutreConsultCardio:degre_satisfaction")]) ?></td>
    </tr>
    <tr>
      <td>Durée approximative en minutes ("à 5 minutes près")</td>
      <td><?php echo $AutreConsultCardio->duree; ?></td>
      <td><?php
      		if($AutreConsultCardio->consult_domicile==1){ echo 'Consultation à domicile';}
      		if($AutreConsultCardio->consult_tel==1){ echo 'Consultation au t&eacute;l&eacute;phone';}
      		if($AutreConsultCardio->consult_collective==1){ echo 'Consultation collective';}
      		?></td>
    </tr>
    <tr>
      <td>Type de consultation:</td>
      <td><?php
		  			foreach($AutreConsultCardio->type_consultation as $consult){
		  				echo $type_consult[$consult].', ';
		  			}
			 ?></td>
		<td>Examens réalisés par délégation : <br><br>

				<?php
					if($AutreConsultCardio->hba==1){ echo "Prescription d’examen (s) pour le patient diabétique type 2, ";}//Prescription HbA1c
					if($AutreConsultCardio->exapied==1){ echo "Prescription, réalisation, interprétation examen des pieds, ";}
					if($AutreConsultCardio->monofil==1){ echo "Prescription, réalisation, interprétation examen des pieds et monofilament, ";}
					if($AutreConsultCardio->ecg==1){ echo "Prescription et réalisation d’ECG, ";}
					if($AutreConsultCardio->ecg_seul==1){ echo "Réalisation d’ECG seul - non dérogatoire, ";}//ECG
					  // if($AutreConsultCardio->tension==1){ echo "Tension, ";}
					if($AutreConsultCardio->spirometre==1){ echo "Prescription, réalisation d’une spirométrie, ";}
					if($AutreConsultCardio->spirometre_seul==1){ echo "Réalisation d’une spirométrie seule - non dérogatoire, ";}
					if($AutreConsultCardio->t_cognitif==1){ echo "Prescription et réalisation d’un repérage troubles cognitifs, ";}
					if($AutreConsultCardio->autre==1){ echo "Autre : ".$AutreConsultCardio->prec_autre;}?>
		</td>
    </tr>
     <tr>
      <td valign='top'>Points positifs:</td>
      <td  width='70%' colspan='2'><?php echo nl2br(stripslashes($AutreConsultCardio->points_positifs)); ?></td>
    </tr>
    <tr>
      <td valign='top'>Points à améliorer:</td>
      <td  width='70%' colspan='2'><?php echo nl2br(stripslashes($AutreConsultCardio->points_ameliorations)); ?></td>
    </tr>
  </table>

  <br><br>


<table border="0">
  <tr>
    <td>
		<?php customSubmitWithAlert("value='Supprimer cette consultation'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer cette consultation ?"); ?>
	 </td>
    <td> <?php customSubmit("value='Modifier cette consultation'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
         <?php customSubmit("value='Faire une autre consultation'",ACTION_MANAGE,"",$param->controler); ?></td>
  </tr>
</table>

</form>
