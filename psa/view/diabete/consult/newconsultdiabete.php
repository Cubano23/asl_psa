<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("global/config.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $CardioVasculaireDepart; ?>
<?php global $EvaluationInfirmier; ?>
<?php global $rowsList;?>
<?php global $complement;?>
<?php global $autre_proto;?>
<?php global $suividiab;?>
<?php global $EvalContinue;?>
<?php global $suiviDiabete;?>
<?php global $dernier_suivi;?>
<?php

	// foreach($_ENV['liste_exam_diabete'] as $exam){
	// 	global $$exam;
	//}
	?>



<?php global $ListConsult;?>
<?php global $currentObjectName;
	$currentObjectName="EvaluationInfirmier";
// print_r($EvaluationInfirmier);	
	
	?>

<script type="text/javascript" >

	function remplacevirgule(valeur){
		return valeur.replace(",",".");
	}

	function validDateValuePair(date,value,dateLabel,valueLabel){
			if(value == false) value="";
			if(value == true) value ="true";
			if(date.length==0 && value.length== 0){
				return -1;
			}

			if(date.length!=0 && value.length== 0){
				alert("Entrer une valeur pour "+dateLabel);
				return 0;
			}
			if(date.length==0 && value.length!= 0){
				alert("Entrer une date pour "+valueLabel);
				return 0;
			}

		return 1;
	}

<?php
	compareDates();
	dateInRange();
	validateDate();	
	validatePositiveNumeric();
	validateNumeric();
	
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","aForm");
	?>
	var submitContinue = 1;
	
	submitContinue = checkContinue("aForm");
	
	if(submitContinue==0){
		submitOk=0;
	}
	<?php
	$js->endCheckFunction();
?>

</script>

<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='aForm'> 
	<?php hiddenControler("ConsultDiabeteControler"); ?>
	<?php hiddenAction(ACTION_SAVE); ?>
	<?php hidden("","Epices:id");?>
	<?php hidden("","dossier:numero");?>
	<?php hidden("","EvaluationInfirmier:date");?>

	<?php
	// foreach($liste_exam as $exam){
	// 	hidden("","$exam:id");
	// 	hidden("","$exam:type_exam");
	// 	hidden("","$exam:numero");
	//}

	?>
	<table border='0'>
		<tr>
			<td align='top'>
			<?php require("view/common/dossierresume.php");?>
			</td>
			<td width='20'>&nbsp;</td>
			<td>Ce formulaire permet d'assurer le suivi éducatif dans le protocole Diabétique de type 2.
			<br><br>
			Il s'appuie sur les données les plus récentes du patient (poids, résultats d'examens, etc...) dont il est possible de visualiser l'historique.
			<br><br>
			Il est également possible de modifier directement ces données, dans ce formulaire.
			</td>
		</tr>
	</table>
<!-- <h1>0- Synthèse des données les plus récentes et historique</h1> -->

<?php
#print_r($poids);
  	#require("view/diabete/suivi/newsuividiabetesystematique1.php"); ?><br>
	  <?php #if(in_array("4",$suiviDiabete->suivi_type)){ 
	  		#require("view/diabete/suivi/newsuividiabete4mois.php"); echo "<br>";}?>
	  <?php #if(in_array("a",$suiviDiabete->suivi_type)||in_array("s",$suiviDiabete->suivi_type)){ 
	  		#require("view/diabete/suivi/newsuividiabetesemestriel.php");
			#require("view/diabete/suivi/newsuividiabeteannuel.php");} ?>
  <?php #require("view/diabete/suivi/newsuividiabetesystematique2.php"); 
echo "<br><br>";
$item=1;
 require("view/common/diag_educ.php");?>
 
<?php
$item=2;
 require("view/common/eval_continue.php");?>
 
<?php 
$item=3;
require("view/common/epices.php");?>
	

  <h1>4- Faire le bilan de la consultation</h1>
	<b>Bilan de consultation</b><br>
 	<?php echo "<a href='javascript://' onclick=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=EvaluationInfirmierControler&controlerparams:param:action=AL&controlerparams:param:param1=PLISDOSSTIP&Dossier:dossier:numero=$dossier->numero',this);return false\" ;>Consultations passées </a><br>";
 	
	?>
 <table width='880' border="1" cellpadding='3'>
    <tr>
      <td>Degré de satisfaction:</td>
      <td colspan='2'><?php selectv("","EvaluationInfirmier:degre_satisfaction",$satisfaction); ?></td>
    </tr>
    <tr>
      <td>Durée approximative en minutes ("à 5 minutes près")</td>
       <td><?php text("size='4'","EvaluationInfirmier:duree"); ?></td>
       <td>
      	<table>
      		<tr>
      			<td>En cas de consultation &agrave;<br/>domicile, cocher la case:<br/>
      				<?php checkBox("","EvaluationInfirmier:consult_domicile","1"); ?>
      			</td>
      			<td width="10">&nbsp;&nbsp;&nbsp;</td>
      			<td>En cas de consultation <br/>t&eacute;l&eacute;phonique, cocher la case:<br/>
      				<?php checkBox("","EvaluationInfirmier:consult_tel","1"); ?>
      			</td>
      			<td width="10">&nbsp;&nbsp;&nbsp;</td>
      			<td>En cas de consultation <br/>collective, cocher la case:<br/>
      				<?php checkBox("","EvaluationInfirmier:consult_collective","1"); ?>
      			</td>
      		
      		</tr>
      	</table>
      </td>
    </tr>
    <tr>
      <td>Type de consultation:</td>
      <td><?php selectv("multiple size='12'","EvaluationInfirmier:type_consultation",$type_consult); ?></td>
		<td>A chaque fois qu’une action de nature dérogatoire est effectuée, au titre du protocole de coopération ASALEE,
			agréé par la Haute Autorité de Santé le 22 mars 2012, et sous réserve de l’autorisation de l’Agence Régionale de Santé  
			et de la notification de l’équipe ASALEE (médecins-infirmières), <br>
			cocher le ou les actions concernées.<br><br>
		<?php
		
			checkBox("","EvaluationInfirmier:hba","1"); echo "Prescription d’examen(s) pour le patient diabétique type 2 <br>";
			checkBox("","EvaluationInfirmier:exapied","1"); echo "Prescription, réalisation, interprétation examen des pieds<br>";
			checkBox("","EvaluationInfirmier:monofil","1"); echo "Prescription, réalisation, interprétation examen des pieds et monofilament<br>";
			checkBox("","EvaluationInfirmier:ecg","1"); echo "Prescription et réalisation d’ECG<br>";
			checkBox("","EvaluationInfirmier:ecg_seul","1"); echo "Réalisation d’ECG seul – non dérogatoire<br>";
			checkBox("","EvaluationInfirmier:spirometre","1"); echo "Prescription, réalisation d’une spirométrie <br>";
			checkBox("","EvaluationInfirmier:spirometre_seul","1"); echo "Réalisation d’une spirométrie seule - non dérogatoire <br>";
			checkBox("","EvaluationInfirmier:t_cognitif","1"); echo "Prescription et réalisation d’un repérage troubles cognitifs <br>";
			/*checkBox("","evaluationInfirmier:tension","1"); echo "Tension <br>";*/
			checkBox("","EvaluationInfirmier:autre","1"); echo "Autre. Précisez : ";
			text("","EvaluationInfirmier:prec_autre"); echo "<br>";
		
		?>
		</td>
    </tr>
    <tr>
      <td valign='top'>Points positifs :<br>

<div style="font-size:9px">
	Besoins du patient pris en compte<br>
	Objectifs prévus atteints<br>
	Objectifs  non  prévus atteints<br>
	Outil(s), support (s), méthodes  utilisés </div>
</td>
      <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","EvaluationInfirmier:points_positifs"); ?></td>
    </tr>
    <tr>
      <td valign='top'>Points à améliorer : <br>
      	<div style="font-size:9px">
    Besoins du patient non pris en compte<br>
	Objectifs prévus non  atteints<br>
	Objectifs perçus à atteindre<br>
	Méthodes envisagées prochaine séance</div>
      </td>
      <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","EvaluationInfirmier:points_ameliorations"); ?></td>
    </tr>
  </table>
  
  <br><br>
  <br>



  <input type='button' value='Valider la saisie' onclick='validateInput()'>
  <input type='reset' value='Recommencer'> 
</form> 


</body>