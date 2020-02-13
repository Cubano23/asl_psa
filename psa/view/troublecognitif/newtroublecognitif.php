<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php global $account ?>
<?php global $dossier; ?>
<?php global $TroubleCognitif ?>
<?php global $EvaluationInfirmier ?>
<?php global $EvalContinue;?>
<?php global $param ?>
<?php global $currentObjectName;
	$currentObjectName="EvaluationInfirmier";
?>
<script type="text/javascript" >


<?php
	compareDates();
	validateDate();	
	dateInRange();
	validatePositiveNumeric();
	validateNumeric();
	
	$js = new JSValidation();
	
		
	$js->startCheckFunction("validateInput","aForm"); ?>	
	var submitMmse = 1;
	var submitGds = 1;
	var submitIadl = 1;
	var submitHorl = 1;
	var submitDubois = 1;


	<?php if(in_array("mmse",$TroubleCognitif->suivi_type)) {?>
		submitMmse = checkMmse("aForm");
	<?php }
	 	 if(in_array("gds",$TroubleCognitif->suivi_type)) { ?>
	     submitGds = checkGds("aForm");
	<?php }
		if(in_array("iadl",$TroubleCognitif->suivi_type)) {?>
	     submitIadl = checkIadl("aForm");
	<?php } 
	 	 if(in_array("horl",$TroubleCognitif->suivi_type)) { ?>
	     submitHorl = checkHorl("aForm");
	<?php } 
	 	 if(in_array("dubois",$TroubleCognitif->suivi_type)) { ?>
	     submitDubois = checkDubois("aForm");
	<?php } ?>

		if(submitMmse == 0 || submitGds == 0 || submitIadl == 0 || submitHorl == 0 || submitDubois == 0)
			submitOk = 0;
	<?php 

	$js->dateInRange("dossier:dnaiss","Date de naissance");	
	#$js->validateDate("dossier:dconsentement","Date de consentement"); 
	$js->endCheckFunction();
?>
</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm"> 
<table border='0'><tr><td>
<?php require("view/common/dossierresume_modif.php");?>
</td><td width='20'>&nbsp;</td>
<td><font style='color:red'><b>Attention ! Nouveau<br><br>

Dor&eacute;navant, il est possible de saisir directement l'&eacute;valuation de cette consultationau bas 
du pr&eacute;sent formulaire. au moment o&ugrave; vous 
enregistrez les r&eacute;sultats des tests.</b></font><br><br>
Ce formulaire permet &agrave; tout instant de faire passer un test de d&eacute;pistage des troubles cognitifs &agrave; un 
patient.<br><br>
Il s'appuie sur les r&eacute;ponses donn&eacute;es lors du test.<br><br>
</td></tr></table>
<br>

  <?php hiddenControler("TroubleCognitifControler"); ?>
  <?php hiddenAction(ACTION_SAVE); ?> 
  <?php hidden("id='date_dep'","TroubleCognitif:date");?>
  <?php hidden("","dossier:numero"); ?>
  <?php hidden("","dossier:id"); ?>
  <?php hidden("","dossier:cabinet"); ?>
  <?php hidden("","EvaluationInfirmier:date");?>
   
  <?php for($i=0;$i<count($TroubleCognitif->suivi_type);$i++){
  		hidden("","TroubleCognitif:suivi_type",$TroubleCognitif->suivi_type[$i]);
  } ?>
				
  <br> 
  <b>Saisie d'un d&eacute;pistage au <?php echo($TroubleCognitif->date); ?></b><br>
  <table border=0 cellspacing=14> 
	  <tr> 
		<td>Type de suivi</td> 
		<td colspan=2>
		<?php 
			if(count ($TroubleCognitif->suivi_type) == 0) echo("Aucun");
			else{

				if(in_array("mmse",$TroubleCognitif->suivi_type)) {?>	<font color="blue">MMSE</font> <?php }
				if(in_array("gds",$TroubleCognitif->suivi_type)) {?> <font color="green">GDS</font> <?php }
				if(in_array("iadl",$TroubleCognitif->suivi_type)) {?> <font color="#FF00FF">IADL</font> <?php }
				if(in_array("horl",$TroubleCognitif->suivi_type)) {?> <font color="brown">Horloge</font> <?php }
				if(in_array("dubois",$TroubleCognitif->suivi_type)) {?> <font color="orange">5 mots de Dubois</font> <?php }
			}
		?>
		</td> 
	  </tr> 
  </table> 
  
	  <?php require("view/troublecognitif/newtroublecognitifsystematique.php"); ?><br><br>
	  <?php if(in_array("mmse",$TroubleCognitif->suivi_type)) require("view/troublecognitif/newtroublecognitifmmse.php"); ?>
	  <?php if(in_array("gds",$TroubleCognitif->suivi_type)) require("view/troublecognitif/newtroublecognitifgds.php"); ?>
	  <?php if(in_array("iadl",$TroubleCognitif->suivi_type)) require("view/troublecognitif/newtroublecognitifiadl.php"); ?>
	  <?php if(in_array("horl",$TroubleCognitif->suivi_type)) require("view/troublecognitif/newtroublecognitifhorl.php"); ?>
	  <?php if(in_array("dubois",$TroubleCognitif->suivi_type)) require("view/troublecognitif/newtroublecognitifdubois.php"); ?>
<?php
echo "<br><br>";
$item=1;
 require("view/common/diag_educ.php");
 
$item=2;
 require("view/common/eval_continue.php");?>
 
	

  <h1>3- Faire le bilan de la consultation</h1>
	<b>Bilan de consultation</b><br>
 	<?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=EvaluationInfirmierControler&controlerparams:param:action=AL&controlerparams:param:param1=PLISDOSSTIP&Dossier:dossier:numero=$dossier->numero',this);return false\" onmouseout=\"ajax_hideTooltip()\">Consultations pass�es </a><br>";
	?>
 <table width='880' border="1" cellpadding='3'>
    <tr>
      <td>Degr&eacute; de satisfaction:</td>
      <td colspan='2'><?php selectv("","EvaluationInfirmier:degre_satisfaction",$satisfaction); ?></td>
    </tr>
    <tr>
    	<td>Dur&eacute;e approximative en minutes ("&agrave; 5 minutes pr&egrave;s")</td>
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
      <!-- <td>En cas de consultation &agrave;<br/>domicile, cocher la case:<?php checkBox("","EvaluationInfirmier:consult_domicile","1"); ?></td> -->
    </tr>
    <tr>
      <td>Type de consultation:</td>
      <td><?php selectv("multiple size='11'","EvaluationInfirmier:type_consultation",$type_consult); ?></td>
		<td>A chaque fois qu'une action de nature d&eacute;rogatoire est effectu&eacute;e, au titre du protocole de coop&eacute;ration ASALEE,
			agr&eacute;&eacute; par la Haute Autorit&eacute; de Sant&eacute; le 22 mars 2012, et sous r&eacute;serve de l'autorisation de l'Agence R&eacute;gionale de Sant&eacute;  
			et de la notification de l'&eacute;quipe ASALEE (m&eacute;decins-infirmi&egrave;res), <br>
			cocher le ou les actions concern&eacute;es.<br><br>
		<?php
		
			checkBox("","evaluationInfirmier:hba","1"); echo "Prescription d'examen(s) pour le patient diab&eacute;tique type 2 <br>";
			checkBox("","evaluationInfirmier:exapied","1"); echo "Prescription, r&eacute;alisation, interpr&eacute;tation examen des pieds<br>";
			checkBox("","evaluationInfirmier:monofil","1"); echo "Prescription, r&eacute;alisation, interpr&eacute;tation examen des pieds et monofilament<br>";
			checkBox("","evaluationInfirmier:ecg","1"); echo "Prescription et r&eacute;alisation d'ECG<br>";
			checkBox("","evaluationInfirmier:ecg_seul","1"); echo "R&eacute;alisation d'ECG seul - non d&eacute;rogatoire<br>";
			checkBox("","evaluationInfirmier:spirometre","1"); echo "Prescription, r&eacute;alisation d'une spirom&eacute;trie <br>";
			checkBox("","evaluationInfirmier:spirometre_seul","1"); echo "R&eacute;alisation d'une spirom&eacute;trie seule - non d&eacute;rogatoire<br>";
			checkBox("","evaluationInfirmier:t_cognitif","1"); echo "Prescription et r&eacute;alisation d'un rep&eacute;rage troubles cognitifs <br>";
			/*checkBox("","evaluationInfirmier:tension","1"); echo "Tension <br>";*/
			checkBox("","evaluationInfirmier:autre","1"); echo "Autre. Pr&eacute;cisez : ";
			text("","evaluationInfirmier:prec_autre"); echo "<br>";
		
		?>
		</td>
    </tr>
    <tr>
      <td valign='top'>Points positifs :
      <div style="font-size:9px">
	Besoins du patient pris en compte<br>
	Objectifs prévus atteints<br>
	Objectifs  non  prévus atteints<br>
	Outil(s), support (s), méthodes  utilisés </div>
	</td>
      <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","EvaluationInfirmier:points_positifs"); ?></td>
    </tr>
    <tr>
      <td valign='top'>Points &agrave; am&eacute;liorer :
      <div style="font-size:9px">
    Besoins du patient non pris en compte<br>
	Objectifs prévus non  atteints<br>
	Objectifs perçus à atteindre<br>
	Méthodes envisagées prochaine séance</div></td>
      <td  width='70%' colspan='2'><?php textArea("rows=\"8\" cols=\"60\"","EvaluationInfirmier:points_ameliorations"); ?></td>
    </tr>
  </table>
   <table border='1' width='100%'>
    <tr> 
      <td align='center'><input type='button' onclick="validateInput()" value='Valider la saisie'> 
        <input type='reset' value='Recommencer'></td> 
    </tr> 
  </table> 
</form> 
