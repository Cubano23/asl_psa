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
			<td>Ce formulaire permet d'assurer le suivi �ducatif dans le protocole Diab�tique de type 2.
			<br><br>
			Il s'appuie sur les donn�es les plus r�centes du patient (poids, r�sultats d'examens, etc...) dont il est possible de visualiser l'historique.
			<br><br>
			Il est �galement possible de modifier directement ces donn�es, dans ce formulaire.
			</td>
		</tr>
	</table>
<!-- <h1>0- Synth�se des donn�es les plus r�centes et historique</h1> -->

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
 	<?php echo "<a href='javascript://' onclick=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=EvaluationInfirmierControler&controlerparams:param:action=AL&controlerparams:param:param1=PLISDOSSTIP&Dossier:dossier:numero=$dossier->numero',this);return false\" ;>Consultations pass�es </a><br>";
 	
	?>
 <table width='880' border="1" cellpadding='3'>
    <tr>
      <td>Degr� de satisfaction:</td>
      <td colspan='2'><?php selectv("","EvaluationInfirmier:degre_satisfaction",$satisfaction); ?></td>
    </tr>
    <tr>
      <td>Dur�e approximative en minutes ("� 5 minutes pr�s")</td>
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
		<td>A chaque fois qu�une action de nature d�rogatoire est effectu�e, au titre du protocole de coop�ration ASALEE,
			agr�� par la Haute Autorit� de Sant� le 22 mars 2012, et sous r�serve de l�autorisation de l�Agence R�gionale de Sant�  
			et de la notification de l��quipe ASALEE (m�decins-infirmi�res), <br>
			cocher le ou les actions concern�es.<br><br>
		<?php
		
			checkBox("","EvaluationInfirmier:hba","1"); echo "Prescription d�examen(s) pour le patient diab�tique type 2 <br>";
			checkBox("","EvaluationInfirmier:exapied","1"); echo "Prescription, r�alisation, interpr�tation examen des pieds<br>";
			checkBox("","EvaluationInfirmier:monofil","1"); echo "Prescription, r�alisation, interpr�tation examen des pieds et monofilament<br>";
			checkBox("","EvaluationInfirmier:ecg","1"); echo "Prescription et r�alisation d�ECG<br>";
			checkBox("","EvaluationInfirmier:ecg_seul","1"); echo "R�alisation d�ECG seul � non d�rogatoire<br>";
			checkBox("","EvaluationInfirmier:spirometre","1"); echo "Prescription, r�alisation d�une spirom�trie <br>";
			checkBox("","EvaluationInfirmier:spirometre_seul","1"); echo "R�alisation d�une spirom�trie seule - non d�rogatoire <br>";
			checkBox("","EvaluationInfirmier:t_cognitif","1"); echo "Prescription et r�alisation d�un rep�rage troubles cognitifs <br>";
			/*checkBox("","evaluationInfirmier:tension","1"); echo "Tension <br>";*/
			checkBox("","EvaluationInfirmier:autre","1"); echo "Autre. Pr�cisez : ";
			text("","EvaluationInfirmier:prec_autre"); echo "<br>";
		
		?>
		</td>
    </tr>
    <tr>
      <td valign='top'>Points positifs :<br>

<div style="font-size:9px">
	Besoins du patient pris en compte<br>
	Objectifs pr�vus atteints<br>
	Objectifs  non  pr�vus atteints<br>
	Outil(s), support (s), m�thodes  utilis�s </div>
</td>
      <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","EvaluationInfirmier:points_positifs"); ?></td>
    </tr>
    <tr>
      <td valign='top'>Points � am�liorer : <br>
      	<div style="font-size:9px">
    Besoins du patient non pris en compte<br>
	Objectifs pr�vus non  atteints<br>
	Objectifs per�us � atteindre<br>
	M�thodes envisag�es prochaine s�ance</div>
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