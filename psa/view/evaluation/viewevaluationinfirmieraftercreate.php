<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $evaluationInfirmier; ?>
<?php global $EvalContinue; ?>
<?php global $Epices; ?>
<?php global $param; ?>
<?php global $liste_eval_continue;?>

 
<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("EvaluationInfirmierControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","evaluationInfirmier:date"); ?>

<?php require("view/common/dossierresume.php");?>


<h1>1- Diagnostic �ducatif - synth�se</h1>
 <table width='880' border="1" cellpadding='3'>
    <tr>
      <td valign='top'>Aspects limitants:</td>
      <td  width='70%' colspan='2'><?php echo nl2br($evaluationInfirmier->aspects_limitant); ?></td>
	</Tr>
    <tr>
      <td valign='top'>Aspects facilitants:</td>
      <td  width='70%' colspan='2'><?php echo nl2br($evaluationInfirmier->aspects_facilitant); ?></td>
	</Tr>
    <tr>
      <td valign='top'>Objectifs du patient:</td>
      <td  width='70%' colspan='2'><?php echo nl2br($evaluationInfirmier->objectifs_patient); ?></td>
	</Tr>
	</table>
	<br>

<h1>2- Evaluation continue d'�ducation</h1>
L�gende : A=Acquis &nbsp;&nbsp;&nbsp; AC=A conforter &nbsp;&nbsp;&nbsp; NA=Non acquis<br>
SO=Sans objet &nbsp;&nbsp;&nbsp; SI = S�ances individuelles &nbsp;&nbsp;&nbsp; SC = S�ances collectives<br>
<table border='1'>
	<tr><td colspan='2' rowspan='3'>&nbsp;</td>
		<td>S�ance</td>
		<?php
		if($liste_eval_continue!=""){
			for($i=0;$i<count($liste_eval_continue);$i++){
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
			echo "<td>".mysqlDateTodate($liste_eval_continue[$i]["date"])."</td>";
		}
	}
	?>
	<!-- <td><?php $EvalContinue->date;?></td> -->
	</tr>
	<tr><td>Suivi</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["suivi"]."</td>";
		}
	}
	?>
	
	<!-- <td><?php echo $EvalContinue->suivi; ?></td> -->
	</tr>
	<tr><td rowspan='2'>Connaissance de la maladie</td>
		<td colspan='2'>Comprendre les causes et m�canismes de la maladie</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["causes"]."</td>";
		}
	}
	?>
		<!-- <td><?php echo $EvalContinue->causes; ?></td> -->
	</tr>
	<tr><td colspan='2'>Comprendre la terminologie</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["terminologie"]."</td>";
		}
	}
	?>
		<!-- <td><?php echo $EvalContinue->terminologie; ?></td> -->
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
		<!-- <td><?php echo $EvalContinue->comprendre_traitement; ?></td> -->
	</tr>
	<tr><td colspan='2'>Appliquer son traitement</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["appliquer_traitement"]."</td>";
		}
	}
	?>
		<!-- <td><?php echo $EvalContinue->appliquer_traitement; ?></td> -->
	</tr>
	<tr><td rowspan='4'>Pr�vention et gestion des risques</td>
		<td colspan='2'>Reconnaitre les risques</Td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["risques"]."</td>";
		}
	}
	?>
		<!-- <td><?php echo $EvalContinue->risques; ?></td> -->
	</tr>
	<tr><td colspan='2'>Reconnaitre les signes de gravit�</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["gravite"]."</td>";
		}
	}
	?>
		<!-- <td><?php echo $EvalContinue->gravite; ?></td> -->
	</tr>
	<tr><td colspan='2'>Conna�tre les mesures en fonction de la situation</Td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["mesures"]."</td>";
		}
	}
	?>
		<!-- <td><?php echo $EvalContinue->mesures; ?></td> -->
	</tr>
	</tr><td colspan='2'>Les appliquer</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["appliquer"]."</td>";
		}
	}
	?>
		<!-- <td><?php echo $EvalContinue->appliquer; ?></td> -->
	</tr>
	<tr><td rowspan='2'>Alimentation</td>
		<td colspan='2'>Conna�tre les principes de l'�quilibre alimentaire</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["connaitre_equilibre"]."</td>";
		}
	}
	?>
		<!-- <td><?php echo $EvalContinue->connaitre_equilibre; ?></td> -->
	</tr>
	<tr><td colspan='2'>Appliquer l'�quilibre alimentaire</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["appliquer_equilibre"]."</td>";
		}
	}
	?>
		<!-- <td><?php echo $EvalContinue->appliquer_equilibre; ?></td> -->
	</tr>
	<tr><td rowspan='2'>Vie quotidienne</td>
		<td colspan='2'>Pratiquer une activit� physique r�guli�re</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["activite"]."</td>";
		}
	}
	?>
		<!-- <td><?php echo $EvalContinue->activite; ?></td> -->
	</tr>
	<tr><td colspan='2'>Autres</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["autre"]."</td>";
		}
	}
	?>
		<!-- <td><?php echo $EvalContinue->autre; ?></td> -->
	</tr>
	</table>

	<br>
  <br>
	<h1>3- Bilan de consultation</h1>
   <table width='850' border="1" cellpadding='3'>
    <tr>
      <td>Degr� de satisfaction:</td>
      <td colspan='2'><?php echo($satisfaction[getPropertyValue("evaluationInfirmier:degre_satisfaction")]) ?></td>
    </tr>
    <tr>
      <td>Dur�e approximative en minutes ("� 5 minutes pr�s")</td>
      <td><?php echo $evaluationInfirmier->duree; ?></td>
      <td><?php 
      if($evaluationInfirmier->consult_domicile==1){echo '&agrave; domicile';} 
      if($evaluationInfirmier->consult_tel==1){echo 'au t&eacute;l&eacute;phone';}
      if($evaluationInfirmier->consult_collective==1){echo 'collective';} ?></td>
    </tr>
    <tr>
      <td>Type de consultation:</td>
      <td><?php 
		  			foreach($evaluationInfirmier->type_consultation as $consult){
		  				echo $type_consult[$consult].', ';
		  			}
			 ?></td>
		<td>Examens r�alis�s par d�l�gation : <br><br>
				<?php 
					if($evaluationInfirmier->hba==1){ echo "Prescription d'examen(s) pour le patient diab�tique type 2 ";}//Prescription HbA1c
            		if($evaluationInfirmier->exapied==1){ echo "Prescription, r�alisation, interpr�tation examen des pieds ";}//Examen des pieds
		            if($evaluationInfirmier->monofil==1){ echo "Prescription, r�alisation, interpr�tation examen des pieds et monofilament ";}//Monofilament
		            if($evaluationInfirmier->ecg==1){ echo "Prescription, r�alisation d'ECG, ";}//nouveau ajout ECG
		            if($evaluationInfirmier->ecg_seul==1){ echo "R�alisation d'ECG seul - non d�rogatoire, ";}// ECG seul
					  /*if($evaluationInfirmier->tension==1){ echo "Tension, ";}*/
					if($evaluationInfirmier->spirometre==1){ echo "Prescription, r�alisation d\'une spirom�tre ";}//Spirom�tre
		            if($evaluationInfirmier->spirometre_seul==1){ echo "R�alisation d�une spirom�trie seule - non d�rogatoire, ";}
		            if($evaluationInfirmier->t_cognitif==1){ echo "Prescription, r�alisation d\'un rep�rage troubles cognitifs ";}//Nouveau ajout Cognitif
					if($evaluationInfirmier->autre==1){ echo "Autre : ".$evaluationInfirmier->prec_autre;}?>
		</td>
    </tr>
     <tr>
      <td valign='top'>Points positifs:</td>
      <td  width='70%' colspan='2'><?php echo nl2br(stripslashes($evaluationInfirmier->points_positifs)); ?></td>
    </tr>
    <tr>
      <td valign='top'>Points � am�liorer:</td>
      <td  width='70%' colspan='2'><?php echo nl2br(stripslashes($evaluationInfirmier->points_ameliorations)); ?></td>
    </tr>
  </table>
    
  <br><br>

<h1>4- Questionnaire compl�mentaire</h1>

<table border='1'>
	<tr><td>Question</td><td>R�ponse</td></Tr>
	<tr><td>1- Rencontrez vous parfois un travailleur social ?</td><td nowrap><?php echo $Epices->travailleur_social;?></td></Tr>
	<tr><td>2- B�n�ficiez-vous d'une assurance maladie compl�mentaire ?</td><td><?php echo $Epices->complementaire;?></td></tr>
	<tr><td>3- Vivez-vous en couple ?</td><td><?php echo $Epices->couple;?></td></tr>
	<tr><td>4- Etes-vous propri�taire de votre logement ?</td><td><?php echo  $Epices->proprietaire;?></td></tr>
	<tr><td>5- Y-a-t'il des p�riodes dans le mois ou vous rencontrez de r�elles difficult�s financi�res � faire face � vos besoins(alimentation, loyer, EDF�) ?</td><td><?php echo $Epices->difficulte;?></td></tr>
	<tr><td>6- Vous est-il arriv� de faire du sport au cours des 12 derniers mois ?</td><td><?php echo $Epices->sport;?></td></Tr>
	<tr><td>7- Etes-vous all� au spectacle au cours des 12 derniers mois ?</td><td><?php echo $Epices->spectacle;?></td></Tr>
	<tr><td>8- Etes-vous parti en vacances au cours des 12 derniers mois ?</td><td><?php echo $Epices->vacances;?></td></Tr>
	<tr><td>9- Au cours des 6 derniers mois avez-vous eu des contacts avec des membres de votre famille autres que parents ou enfants ?</td><td><?php echo $Epices->famille;?></Td></Tr>
	<tr><td>10- En cas de difficult�s, il y a-t-il dans votre entourage des personnes sur qui vous puissiez compter pour vous h�berger quelques jours en cas de besoin ?</Td><td><?php echo $Epices->hebergement;?></td></Tr>
	<tr><td>11- En cas de difficult�s, il y a-t-il dans votre entourage des personnes sur qui vous puissiez compter pour vous apporter une aide mat�rielle ?</td><td><?php echo $Epices->materiel;?></td></Tr>
	</table>

<table border="0">
  <tr>
    <td> 
		<?php customSubmitWithAlert("value='Supprimer cette consultation'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous r�ellement supprimer cette consultation ?"); ?>
	 </td> 
    <td> <?php customSubmit("value='Modifier cette consultation'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
         <?php customSubmit("value='Faire une autre consultation'",ACTION_MANAGE,"",$param->controler); ?></td>
  </tr> 
</table> 

</form>
