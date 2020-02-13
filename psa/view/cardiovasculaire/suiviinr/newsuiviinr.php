<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $CardioVasculaireDepart; ?>
<?php global $AutreConsultCardio; ?>
<?php global $EvaluationInfirmier; ?>
<?php global $rowsList;?>
<?php global $complement;?>
<?php global $autre_proto;?>
<?php global $suividiab;?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $CardioVasculaireDepart; ?>
<?php global $rowsList;?>
<?php global $complement;?>
<?php global $autre_proto;?>
<?php global $suividiab;?>
<?php global $ListConsult;?>

<script type="text/javascript" >

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
	
	$js->startCheckFunction("validateInput","saveForm");
?>
	
	sOk = validDateValuePair(document.getElementById("dChol").value,document.getElementById("chol").value,"Date du cholestérol total","cholestérol total");
	if(sOk == 1){
	<?php
		$js->dateInRange("CardioVasculaireDepart:dChol","Date du cholestérol total");
		$js->validatePositiveNumeric("CardioVasculaireDepart:Chol","Cholestérol total");?>
		if(compareDates("01/01/2000",document.getElementById("dChol").value)>0){
			if(!confirm("Confirmez-vous une date de cholestérol total le "+document.getElementById("dChol").value+" ? ")){
				submitOk=0;
			}
		}
		
	}
	if(sOk==0)
	{
	    submitOk=0;
	}


	sOk = validDateValuePair(document.getElementById("dHDL").value,document.getElementById("HDL").value,"Date du HDL","HDL");
	if(sOk == 1){
	<?php
		$js->dateInRange("CardioVasculaireDepart:dHDL","Date du HDL");
		$js->validatePositiveNumeric("CardioVasculaireDepart:HDL","HDL");
	?>
		if(compareDates("01/01/2000",document.getElementById("dHDL").value)>0){
			if(!confirm("Confirmez-vous une date de HDL le "+document.getElementById("dHDL").value+" ? ")){
				submitOk=0;
			}
		}
		
	}
	if(sOk==0)
	{
	    submitOk=0;
	}


	sOk = validDateValuePair(document.getElementById("dLDL").value,document.getElementById("LDL").value,"Date du LDL","LDL");
	if(sOk == 1){
	<?php
		$js->dateInRange("CardioVasculaireDepart:dLDL","Date du LDL");
		$js->validatePositiveNumeric("CardioVasculaireDepart:LDL","LDL");
	?>
		if(compareDates("01/01/2000",document.getElementById("dLDL").value)>0){
			if(!confirm("Confirmez-vous une date de LDL le "+document.getElementById("dLDL").value+" ? ")){
				submitOk=0;
			}
		}
		
	}
	if(sOk==0)
	{
	    submitOk=0;
	}

	sOk = validDateValuePair(document.getElementById("dtriglycerides").value,document.getElementById("triglycerides").value,"Date des triglycerides","triglycerides");
	if(sOk == 1){
	<?php
		$js->dateInRange("CardioVasculaireDepart:dtriglycerides","Date des triglycérides");
		$js->validatePositiveNumeric("CardioVasculaireDepart:triglycerides","triglycérides");
	?>
		if(compareDates("01/01/2000",document.getElementById("dtriglycerides").value)>0){
			if(!confirm("Confirmez-vous une date de triglycerides le "+document.getElementById("dtriglycerides").value+" ? ")){
				submitOk=0;
			}
		}
		
	}
	if(sOk==0)
	{
	    submitOk=0;
	}


	if((document.getElementById("syst").value!='')||(document.getElementById('dias').value!='')||(document.getElementById("dTA").value!='')
		||(document.getElementById("TA_modeMan").checked==true)||(document.getElementById("TA_modeAuto").checked==true)
			||(document.getElementById("TA_modeMesure").checked==true)){
<?php
		$js->validateRange("CardioVasculaireDepart:TaSys",70,300,"Systole");
		$js->validateRange("CardioVasculaireDepart:TaDia",35,150,"Diastole");
		$js->dateInRange("CardioVasculaireDepart:dTA","Date de la tension");
?>
		if(compareDates("01/01/2000",document.getElementById("dTA").value)>0){
			if(!confirm("Confirmez-vous une date de tension le "+document.getElementById("dTA").value+" ? ")){
				submitOk=0;
			}
		}
		
		if((document.getElementById("TA_modeMan").checked==false)&&(document.getElementById("TA_modeAuto").checked==false)
			&&(document.getElementById("TA_modeMesure").checked==false)){
				alert("Veuillez préciser le type de tension (manuelle, automatique, automesure)");
				submitOk=0;
		}
		
	}



	sOk = validDateValuePair(document.getElementById("dsokolov").value,document.getElementById("sokolov").value,"Date du sokolov","sokolov");
	if(sOk == 1){
	<?php
		$js->dateInRange("CardioVasculaireDepart:dsokolov","Date du sokolov");
		$js->validatePositiveNumeric("CardioVasculaireDepart:sokolov","sokolov");
	?>
		if(compareDates("01/01/2000",document.getElementById("dsokolov").value)>0){
			if(!confirm("Confirmez-vous une date de sokolov le "+document.getElementById("dsokolov").value+" ? ")){
				submitOk=0;
			}
		}
		
	}
	if(sOk==0)
	{
	    submitOk=0;
	}

	sOk = validDateValuePair(document.getElementById("dCreat").value,document.getElementById("Creat").value,"Date de la Créatinine","Créatinine");
	if(sOk == 1){
	<?php
		$js->dateInRange("CardioVasculaireDepart:dCreat","Date de la créatinine");
		$js->validatePositiveNumeric("CardioVasculaireDepart:Creat","créatinine");
	?>
		if(compareDates("01/01/2000",document.getElementById("dCreat").value)>0){
			if(!confirm("Confirmez-vous une date de créatinine le "+document.getElementById("dCreat").value+" ? ")){
				submitOk=0;
			}
		}
		
	}
	if(sOk==0)
	{
	    submitOk=0;
	}

	sOk = validDateValuePair(document.getElementById("dkaliemie").value,document.getElementById("kaliemie").value,"Date de la kaliémie","kaliémie");
	if(sOk == 1){
	<?php
		$js->dateInRange("CardioVasculaireDepart:dkaliemie","Date de la kaliémie");
		$js->validatePositiveNumeric("CardioVasculaireDepart:kaliemie","kaliémie");
	?>
		if(compareDates("01/01/2000",document.getElementById("dkaliemie").value)>0){
			if(!confirm("Confirmez-vous une date de kaliémie le "+document.getElementById("dkaliemie").value+" ? ")){
				submitOk=0;
			}
		}
		
	}
	if(sOk==0)
	{
	    submitOk=0;
	}


	if((document.getElementById('dproteinurie').value!='')||(document.getElementById('proteinurie').checked==true)){
		<?php
		$js->dateInRange("CardioVasculaireDepart:dproteinurie","Date de la proteinurie");
		?>
		if(compareDates("01/01/2000",document.getElementById("dproteinurie").value)>0){
			if(!confirm("Confirmez-vous une date de proteinurie le "+document.getElementById("dproteinurie").value+" ? ")){
				submitOk=0;
			}
		}
		
	}

	if((document.getElementById('dhematurie').value!='')||(document.getElementById('hematurie').checked==true)){
		<?php
		$js->dateInRange("CardioVasculaireDepart:dhematurie","Date de la hematurie");
		?>
		if(compareDates("01/01/2000",document.getElementById("dhematurie").value)>0){
			if(!confirm("Confirmez-vous une date d'hématurie le "+document.getElementById("dhematurie").value+" ? ")){
				submitOk=0;
			}
		}
		
	}

	if(document.getElementById('dFond').value!=''){
		<?php
		$js->dateInRange("CardioVasculaireDepart:dFond","Date de fond d'oeil");
		?>
		if(compareDates("01/01/2000",document.getElementById("dFond").value)>0){
			if(!confirm("Confirmez-vous une date de fond d'oeil le "+document.getElementById("dFond").value+" ? ")){
				submitOk=0;
			}
		}
		
	}


	if(document.getElementById('dECG').value!=''){
		<?php
		$js->dateInRange("CardioVasculaireDepart:dECG","Date d'ECG");
		?>
		if(compareDates("01/01/2000",document.getElementById("dECG").value)>0){
			if(!confirm("Confirmez-vous une date d'ECG le "+document.getElementById("dECG").value+" ? ")){
				submitOk=0;
			}
		}
		
	}


	if(document.getElementById('darret').value!=''){
		<?php
		$js->dateInRange("CardioVasculaireDepart:darret","Date d'arrêt du tabac");
		?>
	}


	sOk = validDateValuePair(document.getElementById("dpoids").value,document.getElementById("poids").value,"Date du poids","poids");
	if(sOk == 1){
	<?php
		$js->dateInRange("CardioVasculaireDepart:dpoids","Date du poids");
		$js->validateRange("CardioVasculaireDepart:poids",30,200,"Poids");
	?>
		if(compareDates("01/01/2000",document.getElementById("dpoids").value)>0){
			if(!confirm("Confirmez-vous une date de poids le "+document.getElementById("dpoids").value+" ? ")){
				submitOk=0;
			}
		}
		
	}
	if(sOk==0)
	{
	    submitOk=0;
	}

	if(document.getElementById("activite").value!=""){
		<?php
		$js->validatePositiveNumeric("CardioVasculaireDepart:activite","activite");
		?>
	}


	sOk = validDateValuePair(document.getElementById("dpouls").value,document.getElementById("pouls").value,"Date de la fréquence cardiaque","fréquence cardiaque");
	if(sOk == 1){
	<?php
		$js->validateRange("CardioVasculaireDepart:pouls",30,300,"fréquence cardiaque");
		$js->dateInRange("CardioVasculaireDepart:dpouls","Date de la fréquence cardiaque");
	?>
		if(compareDates("01/01/2000",document.getElementById("dpouls").value)>0){
			if(!confirm("Confirmez-vous une date de pouls le "+document.getElementById("dpouls").value+" ? ")){
				submitOk=0;
			}
		}
		
	}
	if(sOk==0)
	{
	    submitOk=0;
	}


	sOk = validDateValuePair(document.getElementById("dgly").value,document.getElementById("glycemie").value,"Date de la glycémie","glycémie");
	if(sOk == 1){
	<?php
		$js->dateInRange("CardioVasculaireDepart:dgly","Date de la glycémie");
		$js->validatePositiveNumeric("CardioVasculaireDepart:glycemie","glycémie");
	?>
		if(compareDates("01/01/2000",document.getElementById("dgly").value)>0){
			if(!confirm("Confirmez-vous une date de glycemie le "+document.getElementById("dgly").value+" ? ")){
				submitOk=0;
			}
		}
		
	}
	if(sOk==0)
	{
	    submitOk=0;
	}

	if(document.getElementById("exam_cardio").value!=""){
	<?php
		$js->dateInRange("CardioVasculaireDepart:exam_cardio","Date de l'examen cardio-vasculaire");
	?>
		if(compareDates("01/01/2000",document.getElementById("exam_cardio").value)>0){
			if(!confirm("Confirmez-vous une date d'examen cardio-vasculaire le "+document.getElementById("exam_cardio").value+" ? ")){
				submitOk=0;
			}
		}
		
	}


<?
	$js->endCheckFunction();
?>

</script>

<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'> 
	<?php hiddenControler("SuiviINRControler"); ?>
	<?php hiddenAction(ACTION_SAVE); ?>
	<?php hidden("","dossier:numero");?>
	<?php hidden("","AutreConsultCardio:date");?>

	<?php require("view/common/dossierresume.php");?>

  <br>
<!--  <table border='1'>
	<tr><td rowspan='2'>Date INR</td><td rowspan='2'>Dose de l'AVK avant INR (comprimé ou milligrammes)</td>
		<td colspan='6'>INR</td><td rowspan='2'>Dose de l'AVK après INR (comprimé ou milligrammes)</td>
		<td rowspan='2'>Date du prochain INR</td></tr>
	<tr><td align='left'>1</td><td align='left'>1.8</td><td align='left'>2</Td>
		<td align='left'>3</td><td align='left'>4</td><td align='left'>&lt;6</td></Tr>
	<tr><td><input type='text' size='10'></td>
		<td><input type='text' size='10'></td>
		<td><input type='text' size='10' style='background-color:blue'></td>
		<td><input type='text' size='10' style='background-color:cyan'></td>
		<td><input type='text' size='10' style='background-color:green'></td>
		<td><input type='text' size='10' style='background-color:yellow'></td>
		<td><input type='text' size='10' style='background-color:orange'></td>
		<td><input type='text' size='10' style='background-color:red'></td>
		<td><input type='text' size='10'></td>
		<td><input type='text' size='10'></td>
	</tr>
	<tr><td><input type='text' size='10'></td>
		<td><input type='text' size='10'></td>
		<td><input type='text' size='10' style='background-color:blue'></td>
		<td><input type='text' size='10' style='background-color:cyan'></td>
		<td><input type='text' size='10' style='background-color:green'></td>
		<td><input type='text' size='10' style='background-color:yellow'></td>
		<td><input type='text' size='10' style='background-color:orange'></td>
		<td><input type='text' size='10' style='background-color:red'></td>
		<td><input type='text' size='10'></td>
		<td><input type='text' size='10'></td>
	</tr>
	<tr><td><input type='text' size='10'></td>
		<td><input type='text' size='10'></td>
		<td><input type='text' size='10' style='background-color:blue'></td>
		<td><input type='text' size='10' style='background-color:cyan'></td>
		<td><input type='text' size='10' style='background-color:green'></td>
		<td><input type='text' size='10' style='background-color:yellow'></td>
		<td><input type='text' size='10' style='background-color:orange'></td>
		<td><input type='text' size='10' style='background-color:red'></td>
		<td><input type='text' size='10'></td>
		<td><input type='text' size='10'></td>
	</tr>
	<tr><td><input type='text' size='10'></td>
		<td><input type='text' size='10'></td>
		<td><input type='text' size='10' style='background-color:blue'></td>
		<td><input type='text' size='10' style='background-color:cyan'></td>
		<td><input type='text' size='10' style='background-color:green'></td>
		<td><input type='text' size='10' style='background-color:yellow'></td>
		<td><input type='text' size='10' style='background-color:orange'></td>
		<td><input type='text' size='10' style='background-color:red'></td>
		<td><input type='text' size='10'></td>
		<td><input type='text' size='10'></td>
	</tr>
	<tr><td><input type='text' size='10'></td>
		<td><input type='text' size='10'></td>
		<td><input type='text' size='10' style='background-color:blue'></td>
		<td><input type='text' size='10' style='background-color:cyan'></td>
		<td><input type='text' size='10' style='background-color:green'></td>
		<td><input type='text' size='10' style='background-color:yellow'></td>
		<td><input type='text' size='10' style='background-color:orange'></td>
		<td><input type='text' size='10' style='background-color:red'></td>
		<td><input type='text' size='10'></td>
		<td><input type='text' size='10'></td>
	</tr>
	<tr><td><input type='text' size='10'></td>
		<td><input type='text' size='10'></td>
		<td><input type='text' size='10' style='background-color:blue'></td>
		<td><input type='text' size='10' style='background-color:cyan'></td>
		<td><input type='text' size='10' style='background-color:green'></td>
		<td><input type='text' size='10' style='background-color:yellow'></td>
		<td><input type='text' size='10' style='background-color:orange'></td>
		<td><input type='text' size='10' style='background-color:red'></td>
		<td><input type='text' size='10'></td>
		<td><input type='text' size='10'></td>
	</tr>
	</table>-->
	
	<table border='1'>
		<tr><td>Cible INR. Entre : </td>
		<td><input type='text' maxlength='3' size='2' value='2.0' STYLE="background-color:red;text-align:right;padding-right:1px"></td>
		<td>Et : </td>
		<td><input type='text' maxlength='3' size='2' value='3.0' STYLE="background-color:red;text-align:right;padding-right:1px"></td>
		</tr>
	</table>
	<br><br>
	<table border='1'>
		<tr><td>Traitement : </td><td><input type='text' value='Préviscan'></td></tr>
		<tr><td>Date début du traitement : </td><td><input type='text' size='10' value='15/02/2011'></td></tr>
		<tr><td>Forme existante pour ce traitement : </Td><td>Comprimé quadrisécable de couleur crème</td></tr>
		<tr><td>Forme habituellement utilisée pour ce patient : </Td><td>Comprimé quadrisécable de couleur crème</td></Tr>
	</table>
	
	<br><br>
	<table border='1'>
		<tr><td rowspan='2'>Date contrôle</td>
			<td colspan='7'>Traitements avant INR (cp)</td>
			<td rowspan='2'>INR mesuré</td>
			<td colspan='7'>Traitements après INR (cp)</td>
			<td rowspan='2'>Date du prochain contrôle</td>
		</tr>
		<tr><td>Lun.</td>
			<td>Mar.</td>
			<td>Mer.</td>
			<td>Jeu.</td>
			<td>Ven.</td>
			<td>Sam.</td>
			<td>Dim.</td>
			<td>Lun.</td>
			<td>Mar.</td>
			<td>Mer.</td>
			<td>Jeu.</td>
			<td>Ven.</td>
			<td>Sam.</td>
			<td>Dim.</td>
		</tr>
		<tr>
			<td><input type='text' size='10' value='09/04/2011'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='2' value='2.1' STYLE="text-align:right;padding-right:1px"></Td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='10' value='14/04/2011'></td>
		</tr>
		<tr>
			<td><input type='text' size='10' value='14/04/2011'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='2' value='2.6' STYLE="text-align:right;padding-right:1px"></Td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='10' value='30/04/2011'></td>
		</Tr>
		<tr>
			<td><input type='text' size='10' value='30/04/2011'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='2' value='1.8' STYLE="text-align:right;padding-right:1px"></Td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/2'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='10' value='05/05/2011'></td>
		</Tr>
		<tr>
			<td><input type='text' size='10' value='05/05/2011'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/2'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='7' value='1 cp + 1/4'></td>
			<td><input type='text' size='2' value='2.3' STYLE="text-align:right;padding-right:1px"><br>
			<input type='button' value='Calculer le traitement' onclick='alert("Fonction inactive")' style='width:100%'></Td>
			<td><input type='text' size='7' value=''></td>
			<td><input type='text' size='7' value=''></td>
			<td><input type='text' size='7' value=''></td>
			<td><input type='text' size='7' value=''></td>
			<td><input type='text' size='7' value=''></td>
			<td><input type='text' size='7' value=''></td>
			<td><input type='text' size='7' value=''></td>
			<td><input type='text' size='10' value=''><br>
			<input type='button' value='Valider le traitement' onclick='alert("Fonction inactive")' style='width:100%'></td>
		</Tr>
	</table>
	<br><br>
	Evénements exceptionnels : <br>
	<TEXTAREA cols='70' rows='7'></TEXTAREA><br><br>

	Le calcul automatique correspond à celui du programme "Vous n'AVK".<br>
	Attention, le calcul n'est valable que pour le Préviscan
	
	<br><br><br>
</form> 


</body>