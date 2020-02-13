<?php 

error_reporting(E_ERROR); // EA. Les script ne traite pas des valeurs initiales ce qui g�n�re les Notices 22-12-2014
require_once("bean/beanparser/htmltags.php");
require_once("view/jsgenerator/jsgenerator.php");
require_once("view/common/vars.php");
 ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $EvaluationInfirmier; ?>
<?php global $EvalContinue; ?>
<?php global $param; ?>
<?php global $liste_eval_continue;?>

 
<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("ConsultDiabeteControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","EvaluationInfirmier:date"); ?>

<?php require("view/common/dossierresume.php");?>

<h1>1- Diagnostic �ducatif - synth�se</h1>
 <table width='880' border="1" cellpadding='3'>
    <tr>
      <td valign='top'>Aspects limitants:</td>
      <td  width='70%' colspan='2'><?php echo nl2br($EvaluationInfirmier->aspects_limitant); ?></td>
	</Tr>
    <tr>
      <td valign='top'>Aspects facilitants:</td>
      <td  width='70%' colspan='2'><?php echo nl2br($EvaluationInfirmier->aspects_facilitant); ?></td>
	</Tr>
    <tr>
      <td valign='top'>Objectifs du patient:</td>
      <td  width='70%' colspan='2'><?php echo nl2br($EvaluationInfirmier->objectifs_patient); ?></td>
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
			echo "<td>".mysqlDateTodate($liste_eval_continue[$i]["date"])."</td>";
		}
	}
	?>
	<td><?php $EvalContinue->date;?></td>
	</tr>
	<tr><td>Suivi</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["suivi"]."</td>";
		}
	}
	?>
	
	<td><?php echo $EvalContinue->suivi; ?></td>
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
		<td><?php echo $EvalContinue->causes; ?></td>
	</tr>
	<tr><td colspan='2'>Comprendre la terminologie</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["terminologie"]."</td>";
		}
	}
	?>
		<td><?php echo $EvalContinue->terminologie; ?></td>
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
		<td><?php echo $EvalContinue->comprendre_traitement; ?></td>
	</tr>
	<tr><td colspan='2'>Appliquer son traitement</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["appliquer_traitement"]."</td>";
		}
	}
	?>
		<td><?php echo $EvalContinue->appliquer_traitement; ?></td>
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
		<td><?php echo $EvalContinue->risques; ?></td>
	</tr>
	<tr><td colspan='2'>Reconnaitre les signes de gravit�</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["gravite"]."</td>";
		}
	}
	?>
		<td><?php echo $EvalContinue->gravite; ?></td>
	</tr>
	<tr><td colspan='2'>Conna�tre les mesures en fonction de la situation</Td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["mesures"]."</td>";
		}
	}
	?>
		<td><?php echo $EvalContinue->mesures; ?></td>
	</tr>
	</tr><td colspan='2'>Les appliquer</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["appliquer"]."</td>";
		}
	}
	?>
		<td><?php echo $EvalContinue->appliquer; ?></td>
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
		<td><?php echo $EvalContinue->connaitre_equilibre; ?></td>
	</tr>
	<tr><td colspan='2'>Appliquer l'�quilibre alimentaire</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["appliquer_equilibre"]."</td>";
		}
	}
	?>
		<td><?php echo $EvalContinue->appliquer_equilibre; ?></td>
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
		<td><?php echo $EvalContinue->activite; ?></td>
	</tr>
	<tr><td colspan='2'>Autres</td>
	<?php
	if($liste_eval_continue!=""){
		for($i=0;$i<count($liste_eval_continue);$i++){
			echo "<td>".$liste_eval_continue[$i]["autre"]."</td>";
		}
	}
	?>
		<td><?php echo $EvalContinue->autre; ?></td>
	</tr>
	</table>


	<br>
  <br>
	<h1>3-Bilan de consultation</h1>
   <table width='850' border="1" cellpadding='3'>
    <tr>
      <td>Degr� de satisfaction:</td>
      <td colspan='2'><?php echo($satisfaction[getPropertyValue("EvaluationInfirmier:degre_satisfaction")]) ?></td>
    </tr>
    <tr>
      <td>Dur�e approximative en minutes ("� 5 minutes pr�s")</td>
      <td><?php echo $EvaluationInfirmier->duree; ?></td>
      <td><?php if($EvaluationInfirmier->consult_domicile==1){echo '&agrave; domicile';} 
      			if($EvaluationInfirmier->consult_tel==1){echo 'au t&eacute;l&eacute;phone';} 
      			if($EvaluationInfirmier->consult_collective==1){echo 'collective';} 
      		?>
      </td>
    </tr>
    <tr>
      <td>Type de consultation:</td>
      <td><?php 
		  			foreach($EvaluationInfirmier->type_consultation as $consult){
		  				echo $type_consult[$consult].', ';
		  			}
			 ?></td>
		<td>Examens r�alis�s par d�l�gation : <br><br>
				<!-- <?php var_dump($EvaluationInfirmier); ?>-->
				<?php
					if($EvaluationInfirmier->hba==1){ echo "Prescription d'examen(s) pour le patient diab�tique type 2 ";}//Prescription HbA1c
            		if($EvaluationInfirmier->exapied==1){ echo "Prescription, r�alisation, interpr�tation examen des pieds ";}//Examen des pieds
		            if($EvaluationInfirmier->monofil==1){ echo "Prescription, r�alisation, interpr�tation examen des pieds et monofilament ";}//Monofilament
		            if($EvaluationInfirmier->ecg==1){ echo "Prescription, r�alisation d'ECG, ";}//nouveau ajout ECG
		            if($EvaluationInfirmier->ecg_seul==1){ echo "R�alisation d'ECG seul - non d�rogatoire, ";}// ECG seul
							  /*if($evaluationInfirmier->tension==1){ echo "Tension, ";}*/
					if($EvaluationInfirmier->spirometre==1){ echo "Prescription, r�alisation d'une spirom�tre ";}//Spirom�tre
		            if($EvaluationInfirmier->spirometre_seul==1){ echo "R�alisation d�une spirom�trie seule - non d�rogatoire, ";}
		            if($EvaluationInfirmier->t_cognitif==1){ echo "Prescription, r�alisation d'un rep�rage troubles cognitifs ";}//Nouveau ajout Cognitif
					if($EvaluationInfirmier->autre==1){ echo "Autre : ".$evaluationInfirmier->prec_autre;}
				?>
		</td>
    </tr>
     <tr>
      <td valign='top'>Points positifs:</td>
      <td  width='70%' colspan='2'><?php echo nl2br(stripslashes($EvaluationInfirmier->points_positifs)); ?></td>
    </tr>
    <tr>
      <td valign='top'>Points � am�liorer:</td>
      <td  width='70%' colspan='2'><?php echo nl2br(stripslashes($EvaluationInfirmier->points_ameliorations)); ?></td>
    </tr>
  </table>
    
  <br><br>


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
