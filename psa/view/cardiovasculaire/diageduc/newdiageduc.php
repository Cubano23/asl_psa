<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $CardioVasculaireDepart; ?>
<?php global $diageduc; ?>
<?php global $EvaluationInfirmier; ?>
<?php global $rowsList;?>
<?php global $complement;?>
<?php global $autre_proto;?>
<?php global $suividiab;?>
<?php global $poids ?>
<?php global $systole ?>
<?php global $diastole ?>
<?php global $type_tension ?>
<?php global $HDL ?>
<?php global $LDL ?>
<?php
$liste_exam=array("Chol", "triglycerides", "creat", "kaliemie", 
				  "proteinurie", "hematurie", "fond", "ECG", 
				  "pouls", "glycemie");	

foreach($liste_exam as $exam){
	global $$exam;
}
?>
<?php global $account;?>
<?php global $dossier ?>
<?php global $CardioVasculaireDepart; ?>
<?php global $rowsList;?>
<?php global $complement;?>
<?php global $autre_proto;?>
<?php global $suividiab;?>

<script language="javascript">
	
	function remplacevirgule(valeur){
		return valeur.replace(",",".");
	}
	
	function remplacevirgule2(valeur){
		donnee=document.getElementById(valeur);
		donnee.value=donnee.value.replace(",",".");
		return true;
	}

	function update_date(date_exam, exam){
		remplacevirgule2(exam);
		
		dChol=document.getElementById("dChol");

		if((dChol.value!="")&&(document.getElementById(date_exam).value!="")){
			document.getElementById(date_exam).value=dChol.value;
		}
	}
	
	function computeCleanrance(sexe,age){
		var clearance = document.getElementById("clearance");
		var poids = document.getElementById("poids").value;
		var creatininemie = document.getElementById("Creat").value;
		var clearanceVal;
		var objRegExp  =  /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/

		creatininemie=remplacevirgule(creatininemie);
		document.getElementById("Creat").value=creatininemie;

		if(creatininemie == "0" || !objRegExp.test(creatininemie)){
			clearanceVal = "";
		}
		else{
			clearanceVal = (140-parseInt(age))*parseInt(poids)/(7.2*parseInt(creatininemie));
			if(sexe == "F") clearanceVal *= 0.85;
			if(isNaN(clearanceVal)) clearanceVal = "";
		}

		clearance.innerHTML = Math.round(clearanceVal) + " ml/mn";
	}

	function displayIMC(taille,poids){

		if(taille == 0)
			document.getElementById("IMC").innerHTML = "&nbsp;La taille est invalide IMC ne peut etre calcul�e";
		else{
			poidsValue = parseFloat(document.getElementById("poids").value);
			if(isNaN(poidsValue) || poidsValue <30 || poidsValue >200){
				document.getElementById("IMC").innerHTML = "IMC : ";
				return;
			}
			var imc = Math.round(poidsValue/Math.pow(taille/100, 2));
			obj = document.getElementById("IMC").innerHTML="IMC : "+imc;
		}
		
		if(document.getElementById("Creat").value!=""){
			computeCleanrance("<?php echo $dossier->sexe; ?>","<?php echo $dossier->getAge(); ?>");		
		}
	}

	function arret_tabac(){
		formate_date(document.getElementById("darret"));
		var tabac_oui=document.getElementById("tabac_oui");
		var tabac_non=document.getElementById("tabac_non");
		var date_tabac=document.getElementById("darret");
		
		if(date_tabac.value.length==10){
			tab_tabac=date_tabac.value.split('/');
			date_jour=new Date();

			tab_tabac[2]=parseInt(tab_tabac[2])+3;
			tab_tabac[1]=parseInt(tab_tabac[1])-1;

			date_tabac3=new Date(tab_tabac[2], tab_tabac[1], tab_tabac[0]);

			if(date_tabac3.getTime()>date_jour.getTime()){
				tabac_oui.checked=true;
			}
			else{
				tabac_non.checked=true;
			}
		}

	}
	
	function display_hypertenseur(){
		var tab_hypertenseur1=document.getElementById("tab_hypertenseur1");
		var tab_hypertenseur2=document.getElementById("tab_hypertenseur2");
		var hypertenseur_oui=document.getElementById("hypertenseur_oui");
		var hypertenseur_non=document.getElementById("hypertenseur_non");
		var hypertenseur_nsp=document.getElementById("hypertenseur_nsp");
		var automesure_oui=document.getElementById("automesure_oui");
		var automesure_non=document.getElementById("automesure_non");
		var automesure_nsp=document.getElementById("automesure_nsp");
		var diuretique_oui=document.getElementById("diuretique_oui");
		var diuretique_non=document.getElementById("diuretique_non");
		var diuretique_nsp=document.getElementById("diuretique_nsp");

		if(hypertenseur_oui.checked==true){
			tab_hypertenseur1.style.display='';
			tab_hypertenseur2.style.display='';
		}
		if(hypertenseur_non.checked==true){
			tab_hypertenseur1.style.display='none';
			tab_hypertenseur2.style.display='none';
			automesure_oui.checked=false;
			automesure_non.checked=false;
			automesure_nsp.checked=false;
			diuretique_oui.checked=false;
			diuretique_non.checked=false;
			diuretique_nsp.checked=false;
		}
		if(hypertenseur_nsp.checked==true){
			tab_hypertenseur1.style.display='none';
			tab_hypertenseur2.style.display='none';
			automesure_oui.checked=false;
			automesure_non.checked=false;
			automesure_nsp.checked=false;
			diuretique_oui.checked=false;
			diuretique_non.checked=false;
			diuretique_nsp.checked=false;
		}
		
	}
	
	function calcul_hta(){
		var hta_oui=document.getElementById('HTA_oui');
		var hta_non=document.getElementById('HTA_non');
		var syst=document.getElementById("syst");
		var dias=document.getElementById("dias");
		
		if((syst.value>140)||(dias.value>90)){
			hta_oui.checked=true;
		}
		else{
			hta_non.checked=true;
		}
		
		display_complementhta(); 
	}
	
	function display_complementhta(){
		var hta_oui=document.getElementById("HTA_oui");
		var hta_non=document.getElementById("HTA_non");
		var info_complementaire=document.getElementById("info_complementaire");

		if(hta_oui.checked==true){
			info_complementaire.style.display='';
		}
		if(hta_non.checked==true){
			info_complementaire.style.display='none';
		}
	}
	
	function update_surcharge(){
		var ventricule_oui=document.getElementById("surcharge_ventricule_oui");
		var ventricule_non=document.getElementById("surcharge_ventricule_non");
		var sokolov=document.getElementById("sokolov");
		
		sokolov.value=remplacevirgule(sokolov.value);


		if(sokolov.value>35){
			ventricule_oui.checked=true;
		}
		else{
			ventricule_non.checked=true;
		}
	} 

	function calcul_rcva(diab, sexe, age)
	{
		var tabacoui=document.getElementById("tabac_oui");
		var tabacnon=document.getElementById("tabac_non");
		var tension=document.getElementById("syst");
		var choltot=document.getElementById("chol");
		var hdl=document.getElementById("HDL");
		var ventriculeoui=document.getElementById("HVG_oui");
		var ventriculenon=document.getElementById("HVG_non");
		var surcharge_ventricule_oui=document.getElementById("surcharge_ventricule_oui");
		var surcharge_ventricule_non=document.getElementById("surcharge_ventricule_non");
//		var horizon=document.getElementById("horizon");
		var rcva=document.getElementById("rcva");
	
		var ok='1';

		if((!tabacoui.checked)&&(!tabacnon.checked)){
		    alert("Veuillez pr�ciser si la personne fume");
			ok="0";
		}
		if(tension.value==''){
			alert("Veuillez saisir la tension");
			ok="0";
		}
		if(choltot.value==''){
			alert("Veuillez saisir la valeur du cholest�rol total");
			ok="0";
		}
		if(hdl.value==''){
			alert("Veuillez saisir la valeur du HDL");
			ok="0";
		}
		if((!ventriculeoui.checked)&&(!ventriculenon.checked)&&(!surcharge_ventricule_oui.checked)&&(!surcharge_ventricule_non.checked)){
		    alert("Veuillez pr�ciser si la personne a une hypertrophie ventriculaire gauche (ou surcharge ventriculaire gauche)");
			ok="0";
		}
		
/*		if(horizon.value==''){
		    alert("Veuillez pr�ciser � quel horizon vous souhaitez calculer le risque");
		    ok="0";
		}
*/		
		if(ok=='0'){
		    return false;
		}
	
	
	
	var e1 = -0.9119;
	var e2 = -0.2767;
	var e3 = -0.7181;
	var e4 = -0.5865;
	var l = 11.1122;
	var m0 = 4.4181 ;
	var s0 = -0.3155 ;
	var s1 = -0.2784;
	var c1 = -1.4792 ;
	var c2 = -0.1759 ;
	var d1 = -5.8549;
	var d2 = 1.8515 ;
	var d3 = -0.3758 ;
	var horizon=10;
	
	var pas=tension.value;
	var tabac=0;
	var hvg=0;
	var chol=choltot.value;
	var HDL=hdl.value;
	
	if(tabacoui.checked){
		tabac=1;
	}
	if((!ventriculeoui.checked)&&(!ventriculenon.checked)&&(surcharge_ventricule_oui.checked)){
		hvg=1;
	}
	if(ventriculeoui.checked){
		hvg=1;
	}

	var a = l + e1*Math.log(pas) + e2*tabac + e3*Math.log(chol/HDL) + e4*hvg;

	if(sexe=="M"){
		var m = a + c1*Math.log (age) + c2*diab;
	}
	if(sexe=='F'){
		var m = a + d1 + d2*Math.pow((Math.log (age/74)), 2) + d3*diab;
	}

	var m_calc = m0 + m;
	var s = Math.exp(s0 + s1*m);

	var u = (Math.log (horizon) - m_calc ) / s ;

	var pt = 1- Math.exp(-Math.exp(u));

	rcva.innerHTML=Math.round(pt*10000, 2)/100+"%";
	
	}

</script>

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
	
	sOk = validDateValuePair(document.getElementById("dChol").value,document.getElementById("chol").value,"Date du cholest�rol total","cholest�rol total");
	if(sOk == 1){
	<?php
		$js->dateInRange("Chol:date_exam","Date du cholest�rol total");
		$js->validatePositiveNumeric("Chol:resultat1","Cholest�rol total");?>
		if(compareDates("01/01/2000",document.getElementById("dChol").value)>0){
			if(!confirm("Confirmez-vous une date de cholest�rol total le "+document.getElementById("dChol").value+" ? ")){
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
		$js->dateInRange("HDL:date_exam","Date du HDL");
		$js->validatePositiveNumeric("HDL:resultat1","HDL");
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
		$js->dateInRange("LDL:date_exam","Date du LDL");
		$js->validatePositiveNumeric("LDL:resultat1","LDL");
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
		$js->dateInRange("triglycerides:date_exam","Date des triglyc�rides");
		$js->validatePositiveNumeric("triglycerides:resultat1","triglyc�rides");
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
		$js->validateRange("systole:resultat1",50,300,"Systole");
		$js->validateRange("diastole:resultat1",15,150,"Diastole");
		$js->dateInRange("systole:date_exam","Date de la tension");
?>
		if(compareDates("01/01/2000",document.getElementById("dTA").value)>0){
			if(!confirm("Confirmez-vous une date de tension le "+document.getElementById("dTA").value+" ? ")){
				submitOk=0;
			}
		}
		
		if((document.getElementById("TA_modeMan").checked==false)&&(document.getElementById("TA_modeAuto").checked==false)
			&&(document.getElementById("TA_modeMesure").checked==false)){
				alert("Veuillez pr�ciser le type de tension (manuelle, automatique, automesure)");
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

	sOk = validDateValuePair(document.getElementById("dCreat").value,document.getElementById("Creat").value,"Date de la Cr�atinine","Cr�atinine");
	if(sOk == 1){
	<?php
		$js->dateInRange("creat:date_exam","Date de la cr�atinine");
		$js->validatePositiveNumeric("creat:resultat1","cr�atinine");
	?>
		if(compareDates("01/01/2000",document.getElementById("dCreat").value)>0){
			if(!confirm("Confirmez-vous une date de cr�atinine le "+document.getElementById("dCreat").value+" ? ")){
				submitOk=0;
			}
		}
		
	}
	if(sOk==0)
	{
	    submitOk=0;
	}

	sOk = validDateValuePair(document.getElementById("dkaliemie").value,document.getElementById("kaliemie").value,"Date de la kali�mie","kali�mie");
	if(sOk == 1){
	<?php
		$js->dateInRange("kaliemie:date_exam","Date de la kali�mie");
		$js->validatePositiveNumeric("kaliemie:resultat1","kali�mie");
	?>
		if(compareDates("01/01/2000",document.getElementById("dkaliemie").value)>0){
			if(!confirm("Confirmez-vous une date de kali�mie le "+document.getElementById("dkaliemie").value+" ? ")){
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
		$js->dateInRange("proteinurie:date_exam","Date de la proteinurie");
		?>
		if(compareDates("01/01/2000",document.getElementById("dproteinurie").value)>0){
			if(!confirm("Confirmez-vous une date de proteinurie le "+document.getElementById("dproteinurie").value+" ? ")){
				submitOk=0;
			}
		}
		
	}

	if((document.getElementById('dhematurie').value!='')||(document.getElementById('hematurie').checked==true)){
		<?php
		$js->dateInRange("hematurie:date_exam","Date de la hematurie");
		?>
		if(compareDates("01/01/2000",document.getElementById("dhematurie").value)>0){
			if(!confirm("Confirmez-vous une date d'h�maturie le "+document.getElementById("dhematurie").value+" ? ")){
				submitOk=0;
			}
		}
		
	}

	if(document.getElementById('dFond').value!=''){
		<?php
		$js->dateInRange("fond:date_exam","Date de fond d'oeil");
		?>
		if(compareDates("01/01/2000",document.getElementById("dFond").value)>0){
			if(!confirm("Confirmez-vous une date de fond d'oeil le "+document.getElementById("dFond").value+" ? ")){
				submitOk=0;
			}
		}
		
	}


	if(document.getElementById('dECG').value!=''){
		<?php
		$js->dateInRange("ECG:date_exam","Date d'ECG");
		?>
		if(compareDates("01/01/2000",document.getElementById("dECG").value)>0){
			if(!confirm("Confirmez-vous une date d'ECG le "+document.getElementById("dECG").value+" ? ")){
				submitOk=0;
			}
		}
		
	}


	if(document.getElementById('darret').value!=''){
		<?php
		$js->dateInRange("CardioVasculaireDepart:darret","Date d'arr�t du tabac");
		?>
	}


	sOk = validDateValuePair(document.getElementById("dpoids").value,document.getElementById("poids").value,"Date du poids","poids");
	if(sOk == 1){
	<?php
		$js->dateInRange("poids:date_exam","Date du poids");
		$js->validateRange("poids:resultat1",30,200,"Poids");
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


	sOk = validDateValuePair(document.getElementById("dpouls").value,document.getElementById("pouls").value,"Date de la fr�quence cardiaque","fr�quence cardiaque");
	if(sOk == 1){
	<?php
		$js->validateRange("pouls:resultat1",30,300,"fr�quence cardiaque");
		$js->dateInRange("pouls:date_exam","Date de la fr�quence cardiaque");
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


	sOk = validDateValuePair(document.getElementById("dgly").value,document.getElementById("glycemie").value,"Date de la glyc�mie","glyc�mie");
	if(sOk == 1){
	<?php
		$js->dateInRange("glycemie:date_exam","Date de la glyc�mie");
		$js->validatePositiveNumeric("glycemie:resultat1","glyc�mie");
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


<?php
	$js->endCheckFunction();
?>

</script>

<?php
$hypolemiantArray = array("Aucun"=>"Aucun",
				  "Atorvastatine"=>"Atorvastatine",
				  "atorvastatine_ezetimibe" => "Atorvastatine, Ezetimibe",
				  "Benfluorex"=>"Benfluorex",
				  "Bezafibrate"=>"Bezafibrate",
				  "Cholestyramine"=>"Cholestyramine",
				  "Ciprofibrate"=>"Ciprofibrate", 
				  "Colestipol"=>"Colestipol",
				  "Ezetimibe"=>"Ezetimibe",
				  "Fenofibrate"=>"Fenofibrate",
				  "Fluvastatine"=>"Fluvastatine",
				  "Gemfibrozil"=>"Gemfibrozil",
				  "Pravastatine"=>"Pravastatine",
				  "Rosuvastatine"=>"Rosuvastatine",
				  "Simvastatine"=>"Simvastatine",
				  "Simvastatine_ezetimibe"=>"Simvastatine + ezetimibe",
				  "Tiadenol"=>"Tiadenol"); ?> 
 
<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'> 
	<?php hiddenControler("diageducControler"); ?>
	<?php hiddenAction(ACTION_SAVE); ?>
	<?php hidden("","dossier:numero");?>
	<?php hidden("","diageduc:date");?>
	<?php hidden("","poids:id");?>
	<?php hidden("","poids:type_exam");?>
	<?php hidden("","poids:numero");?>
	<?php hidden("","systole:id");?>
	<?php hidden("","systole:type_exam");?>
	<?php hidden("","systole:numero");?>
	<?php hidden("","diastole:id");?>
	<?php hidden("","diastole:type_exam");?>
	<?php hidden("","diastole:numero");?>
	<?php hidden("","type_tension:id");?>
	<?php hidden("","type_tension:type_exam");?>
	<?php hidden("","type_tension:numero");?>
	<?php hidden("","HDL:id");?>
	<?php hidden("","HDL:type_exam");?>
	<?php hidden("","HDL:numero");?>
	<?php hidden("","LDL:id");?>
	<?php hidden("","LDL:type_exam");?>
	<?php hidden("","LDL:numero");?>
	
<?php
	foreach($liste_exam as $exam){

		hidden("","$exam:id");
		hidden("","$exam:type_exam");
		hidden("","$exam:numero");
	}
?>	
	<?php //require("view/common/dossierresumecardio.php");?>
Ce formulaire permet d'�tablir le diagnostic �ducatif d'entr�e dans le protocole RCVA.
<br><br>
Il s'appuie sur les donn�es les plus r�centes du patient (poids, r�sultats d'examens, etc...) 
permettant de calculer son Risque Cardio-Vasculaire Absolu.
<br><br>
Il est �galement possible de renseigner ces donn�es directement au cours de l'�laboration du 
diagnostic �ducatif proprement dit.
<br><br>
Ce diagnostic d�termine in fine des objectifs prioritaire � retenir pour la premi�re consultation infirmi�re. 	
<br><br>
 	<table border='0'><tr><td>
	<?php require("view/common/dossierresume_cardio.php");?>
</td><td width='20'>&nbsp;</td><td>

<font style='color:orange'>Figurent en orange, les donn�es arriv�es � �ch�ance dans le suivi courant d'un patient � la date du jour.</font><br><br>

<font  style=" border-bottom:solid  ; border-color:green ;   " >Sont soulign�es en vert, les zones utilis�es dans le calcul du Risque Cardio-Vasculaire Absolu.</font>
</td></tr></table>


<h1>1- Prendre connaissance des donn�es du patients (facteurs de risque, mode de vie, bilan lipidique, tension)
afin de pouvoir calculer le RCVA</h1>

  <b>Facteurs de risque non modifiables</b>
  <table border=1 width='880'>
  	<tr>
  		<td width='400'>Ant�c�dents familiaux du premier degr� (accident vasculaire avant 55 ans chez les hommes et 
		  	65 ans chez les femmes)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>def_cardio.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt='D�finition accident cardio-vasculaire' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
		  	<td colspan='2'>
			  <?php
				if($CardioVasculaireDepart->antecedants=="")
					$color='style="background:orange"';
				else
					$color="";
			  ?>
			  				<?php radioButton("id='antecedants_oui' $color","CardioVasculaireDepart:antecedants","oui"); ?>Oui&nbsp;&nbsp;
		  					<?php radioButton("id='antecedants_non' $color","CardioVasculaireDepart:antecedants","non"); ?>Non&nbsp;&nbsp;
		  					<?php radioButton("id='antecedants_nsp' $color","CardioVasculaireDepart:antecedants","nsp"); ?>Nsp
		</td>
	</tr>
  </table>
  <br>
  <b>Mode de vie</b>
  <table border='1' width='880'>
  	<tr>
  		<td width='400'><font  style=" border-bottom:solid  ; border-color:green ;" >Tabagisme</font><img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>tabac.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Tabac' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
			  <?php
				if($CardioVasculaireDepart->tabac=="")
					$color='style="background:orange"';
				else
					$color="";
			  ?>

  			<td colspan='2'><font  style=" border-bottom:solid  ; border-color:green ;   " >
			<?php radioButton("id='tabac_oui' $color","CardioVasculaireDepart:tabac","oui"); ?>Oui &nbsp;&nbsp;
			  			<?php radioButton("id='tabac_non' $color","CardioVasculaireDepart:tabac","non"); ?>Non &nbsp;&nbsp;
			  			<?php radioButton("id='tabac_nsp' $color","CardioVasculaireDepart:tabac","nsp"); ?>Nsp</font>
			  &nbsp;&nbsp;Date d'arr�t jj/mm/aaaa 
			  <?php text("id='darret' onkeyup='arret_tabac();' $color size='10' maxlength='10'","CardioVasculaireDepart:darret");?>
			</Td>
  	</tr>
  	<tr>
  		<td width='400'>Poids  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=poids&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></td>
			  <?php
				if($CardioVasculaireDepart->dpoids=="")
					$color='style="background:orange"';
				else
					$color="";
			  ?>
			<td>
			<?php text("id='poids' $color onkeyup='displayIMC(\"$dossier->taille\",\"poids\")' size='4' maxlength='4'","poids:resultat1");?>kg. &nbsp; 
			le <?php text("id='dpoids' $color size='10' maxlength='10' onkeyup='formate_date(this)'","poids:date_exam");?>(jj/mm/aaaa)&nbsp;&nbsp; 
			Taille : <?php echo $dossier->taille;?>
				<table><tr><td id='IMC'>IMC : <?php echo($CardioVasculaireDepart->getIMC($dossier->taille));?></td></tr></table> </Td>
  	</tr>
	<tr>
		<td width='400'>Activit� physique (heures par semaine. 2h30=2.5h)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>activite.html','','width=250,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='D�finition activit� physique' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
			  <?php
				if($CardioVasculaireDepart->activite=="")
					$color='style="background:orange"';
				else
					$color="";
			  ?>
			<td colspan='2'><?php text("id='activite' $color onchange='remplacevirgule2(\"activite\")' size='4' maxlength='4'","CardioVasculaireDepart:activite");?>h</Td>
	</Tr>
	<tr>
		<td width='400'>Alcool (>20g/j)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>alcool.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Alcool' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
			<td colspan='2'>
			  <?php

				if($CardioVasculaireDepart->alcool=="")
					$color='style="background:orange"';
				else
					$color="";
			  ?>
			<?php radioButton("id='alcool_oui' $color ","CardioVasculaireDepart:alcool","oui"); ?>Oui &nbsp;&nbsp;
			  			<?php radioButton("id='alcool_non' $color ","CardioVasculaireDepart:alcool","non"); ?>Non &nbsp;&nbsp;
			  			<?php radioButton("id='alcool_nsp' $color ","CardioVasculaireDepart:alcool","nsp"); ?>Nsp</td>
	</tr>
	</table>
<br>
	<b>Bilan lipidique</b>
  <table border='1' width='880'>
  	<tr>
  		<td width='400'><font  style=" border-bottom:solid  ; border-color:green ;   " >Cholest�rol total  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=Chol&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></font></td>
  			<td colspan='2'><font  style=" border-bottom:solid  ; border-color:green ;   " >
			  
			  <?php

				if($CardioVasculaireDepart->isOutdatedChol())
					$color='style="background:orange"';
				else
					$color="";
			  ?>
			  
			  				<?php text("id='chol' size='4' $color onchange='remplacevirgule2(\"chol\")' maxlength='4'","Chol:resultat1");?>g/l &nbsp;
  							<?php text("id='dChol' size='10' $color maxlength='10' onkeyup='formate_date(this)'","Chol:date_exam");?>jj/mm/aaaa</font></td>
  	</tr>
  	<tr>
  		<td width='400'><font  style=" border-bottom:solid  ; border-color:green ;   " >HDL Cholest�rol  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=HDL&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></font></td>
  			<td colspan='2'><font  style=" border-bottom:solid  ; border-color:green ;   " >
			  
			  <?php

				if($CardioVasculaireDepart->isOutdatedHDL())
					$color='style="background:orange"';
				else
					$color="";
			  ?>
			  				<?php text("id='HDL' size='4' $color onchange='remplacevirgule2(\"HDL\")' maxlength='4'","HDL:resultat1");?>g/l &nbsp;
  							<?php text("id='dHDL' size='10' $color maxlength='10' onkeyup='formate_date(this)'","HDL:date_exam");?>jj/mm/aaaa</font></td>
  	</tr>
	<tr>
		<td width='400'>LDL Cholest�rol  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=LDL&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></Td>
  			<td colspan='2'>
			  
			  <?php

				if($CardioVasculaireDepart->isOutdatedLDL())
					$color='style="background:orange"';
				else
					$color="";
			  ?>
			  
			  				<?php text("id='LDL' size='4' $color onchange='remplacevirgule2(\"LDL\")' maxlength='4'","LDL:resultat1");?>g/l &nbsp;
  							<?php text("id='dLDL' size='10' $color maxlength='10' onkeyup='formate_date(this)'","LDL:date_exam");?>jj/mm/aaaa</td>
	</tr>
  	<tr>
  		<td width='400'>Triglyc�rides  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=triglycerides&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></Td>
  			<td colspan='2'>
			  
			  <?php

				if($CardioVasculaireDepart->isOutdatedTriglycerides())
					$color='style="background:orange"';
				else
					$color="";
			  ?>
			  
			  				<?php text("id='triglycerides' $color size='4' onchange='remplacevirgule2(\"triglycerides\")' maxlength='4'","triglycerides:resultat1");?>g/l &nbsp;
  							<?php text("id='dtriglycerides' $color size='10' maxlength='10' onkeyup='formate_date(this)'","triglycerides:date_exam");?>jj/mm/aaaa</td>
  	</tr>
	<tr>
		<td width='400'>Glyc�mie  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=glycemie&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></td>
			  <?php

				if($CardioVasculaireDepart->isOutdatedGlycemie())
					$color='style="background:orange"';
				else
					$color="";
			  ?>
			<td><?php text("id='glycemie' size='4' $color onchange='remplacevirgule2(\"glycemie\")' maxlength='4'","glycemie:resultat1");?>g/l &nbsp; 
			<?php text("id='dgly' size='10' $color maxlength='10' onkeyup='formate_date(this)'","glycemie:date_exam");?>jj/mm/aaaa</td>
	</tr>
	</table>
	<br>
  <table border="1" width='880'  <?php if(($CardioVasculaireDepart->HTA=='non')||($CardioVasculaireDepart->HTA=='')) echo "style='display:none'";?> id='info_complementaire'>
  <tr>
    <td width='400'>Cr�atinine  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=creat&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></td>
    <td>
			  <?php

				if($CardioVasculaireDepart->isOutdatedCreat())
					$color='style="background:orange"';
				else
					$color="";
			  ?>
	<?php text("id='Creat' $color onchange ='computeCleanrance(\"$dossier->sexe\",".$dossier->getAge().")' size='3'","creat:resultat1");?>mg
	
	<img OnClick="javascript:window.open('<?php echo($path)?>/view/diabete/suivi/equivalence_creat.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Equivalence �mol/mg' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
	<br>
	<table border="0">
	<tr>
	    <td>Clearance calcul�e : </td>
	    <td id='clearance'>&nbsp;<?php echo($CardioVasculaireDepart->getClearance($dossier));?> ml/mn</td>
	</tr>
 </table>
    <td><?php text("id='dCreat' $color size='10' maxlength='10' onkeyup='formate_date(this)'","creat:date_exam");?>jj/mm/aaaa</td>
  </tr>
  <tr>
    <td width='400'>Kali�mie  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=kaliemie&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></td>
	  <?php

		if($CardioVasculaireDepart->dkaliemie=="")
			$color='style="background:orange"';
		else
			$color="";
	  ?>
    <td><?php text("id='kaliemie' $color onchange='remplacevirgule2(\"kaliemie\")' size='3'","kaliemie:resultat1");?>mmol/l</td>
    <td><?php text("id='dkaliemie' $color size='10' maxlength='10' onkeyup='formate_date(this)'","kaliemie:date_exam");?>jj/mm/aaaa</td>
  </tr>
  <tr>
    <td width='400'>Prot�inurie  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=proteinurie&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></td>
		  <?php

			if($CardioVasculaireDepart->IsOutdatedProteinurie())
				$color='style="background:orange"';
			else
				$color="";
		  ?>
    <td><?php checkBox("id='proteinurie' $color","proteinurie:resultat1","1"); ?>Positive
		</td>
    <td><?php text("id='dproteinurie' size='10' $color maxlength='10' onkeyup='formate_date(this)'","proteinurie:date_exam");?>jj/mm/aaaa</td>
  </tr>
  <tr>
    <td width='400'>H�maturie  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=hematurie&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></td>
	  <?php

		if($CardioVasculaireDepart->dhematurie=="")
			$color='style="background:orange"';
		else
			$color="";
	  ?>
    <td><?php checkBox("id='hematurie' $color","hematurie:resultat1","1"); ?>Positive
		</td>
    <td><?php text("id='dhematurie' size='10' $color maxlength='10' onkeyup='formate_date(this)'","hematurie:date_exam");?>jj/mm/aaaa</td>
  </tr>
  <tr>
    <td width='400'>Fond d'&oelig;il  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=fond&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></td>
	  <?php

		if($CardioVasculaireDepart->IsOutdateddFond())
			$color='style="background:orange"';
		else
			$color="";
	  ?>
    <td><?php text("id='dFond' size='10' $color maxlength='10' onkeyup='formate_date(this)'","fond:date_exam");?>jj/mm/aaaa</td>
  </tr>
  </table>
  
  <br>
	<table width='880' border='1'>
  <tr>
		<td width='400'>Traitement hypolipid�miant m�dicamenteux<br><br>
		<i>Pour s�lectionner/d�s�lectionner une ou plusieurs mol�cules, maintenir la touche contr�le (ctrl) enfonc�e</i></td>
			<td colspan='2'>
				<table border='0'>
					<tr>
						<td valign='top'>nom de mol�cule <br>
			 <?php selectv("id='hypolemiant' size='16' multiple","CardioVasculaireDepart:traitement",$hypolemiantArray) ?> 
			 <img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>liste_traitement.html','','width=350,height=650,top=60,left=500,scrollbars=yes,resizable=yes')" alt='Correspondance m�dicament / mol�cule' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
						
										<td>dosage :
			<?php text("id='dosage' size='10' maxlength='10'","CardioVasculaireDepart:dosage");?></Td>
					</tr>
				</table>
	
			</td>
	</Tr>
  </table>
  <br>
  <b>Tension</b>
  <table border='1' width='880'>
	<tr>
		<td width='400'>Fr�quence cardiaque  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=pouls&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></td>
			  <?php

				if($CardioVasculaireDepart->dpouls=="")
					$color='style="background:orange"';
				else
					$color="";
			  ?>
			<td colspan='2'><?php text("id='pouls' $color size='4' maxlength='4'","pouls:resultat1");?>/min &nbsp; 
			le <?php text("id='dpouls' size='10' $color maxlength='10' onkeyup='formate_date(this)'","pouls:date_exam");?>jj/mm/aaaa</td>
	</Tr>
  	<tr>
  		<td width='400'>HTA (<font  style=" border-bottom:solid  ; border-color:green ;" >Derniers chiffres de tension</font>)  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=systole&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></Td>
  			<td colspan='2'>
			  
			  <?php

				if($CardioVasculaireDepart->dTA=="")
					$color='style="background:orange"';
				else
					$color="";
			  ?>
			  
			  			<?php radioButton("id='HTA_oui' $color onclick='display_complementhta();' ","CardioVasculaireDepart:HTA","oui"); ?>Oui &nbsp;&nbsp;
			  			<?php radioButton("id='HTA_non' $color onclick='display_complementhta();' ","CardioVasculaireDepart:HTA","non"); ?>Non 
  				(<font  style=" border-bottom:solid  ; border-color:green ;" ><?php text("id='syst' size='4' $color maxlength='4'","systole:resultat1");?>/
			<?php text("id='dias' size='4' $color maxlength='4'","diastole:resultat1");?>mmHg 
				&nbsp;le 
			<?php text("id='dTA' size='10' $color maxlength='10' onkeyup='formate_date(this)'","systole:date_exam");?>jj/mm/aaaa </font>)<br>
        <?php radioButton("id='TA_modeMan' $color","type_tension:resultat1","manuel"); ?> 
        manuel
        <?php radioButton("id='TA_modeAuto' $color","type_tension:resultat1","automatique"); ?>
        automatique
        <?php radioButton("id='TA_modeMesure' $color","type_tension:resultat1","automesure"); ?>
        automesure</td>
  	</tr>
	<tr>
		<td width='400' valign='top'>Trois Traitements hypertenseurs ou plus ?
			<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>hta_resistante.html','','width=250,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='D�finition HTA r�sistante' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		
		 <br>
			<table border="0" cellpadding="0" cellspacing="0" id='tab_hypertenseur1' <?php if(($CardioVasculaireDepart->hypertenseur3=='non')||($CardioVasculaireDepart->hypertenseur3=='')) echo "style='display:none'";?>>
			<tr>
				<td>Si oui (hta s�v�re) pr�sence d'une automesure</td>
			</tr>
			<tr>
				<td>et pr�sence d'un diur�tique</td>
			</tr>
			</table>
			</td>
			<td colspan='2'>
			  <?php

				if($CardioVasculaireDepart->hypertenseur3=="")
					$color='style="background:orange"';
				else
					$color="";
			  ?>
						<?php radioButton("id='hypertenseur_oui' $color onclick='display_hypertenseur();' ","CardioVasculaireDepart:hypertenseur3","oui"); ?>Oui &nbsp;&nbsp;
			  			<?php radioButton("id='hypertenseur_non' $color onclick='display_hypertenseur();' ","CardioVasculaireDepart:hypertenseur3","non"); ?>Non &nbsp;&nbsp;
			  			<?php radioButton("id='hypertenseur_nsp' $color onclick='display_hypertenseur();' ","CardioVasculaireDepart:hypertenseur3","nsp"); ?>Nsp<br>
			  			<br>
			<table border="0" cellpadding="0" cellspacing="0" id='tab_hypertenseur2'  <?php if(($CardioVasculaireDepart->hypertenseur3=='non')||($CardioVasculaireDepart->hypertenseur3=='')) echo "style='display:none'";?>>
			<tr>
				<td>
			  <?php

				if($CardioVasculaireDepart->automesure=="")
					$color='style="background:orange"';
				else
					$color="";
			  ?>

				<?php radioButton("id='automesure_oui' $color ","CardioVasculaireDepart:automesure","oui"); ?>Oui &nbsp;&nbsp;
			  			<?php radioButton("id='automesure_non' $color ","CardioVasculaireDepart:automesure","non"); ?>Non&nbsp;&nbsp;
			  			<?php radioButton("id='automesure_nsp' $color ","CardioVasculaireDepart:automesure","nsp"); ?>Nsp
				</td>
			</tr>
			<tr>
				<td>
			  <?php

				if($CardioVasculaireDepart->diuretique=="")
					$color='style="background:orange"';
				else
					$color="";
			  ?>
			<?php radioButton("id='diuretique_oui' $color ","CardioVasculaireDepart:diuretique","oui"); ?>Oui &nbsp;&nbsp;
			  			<?php radioButton("id='diuretique_non' $color ","CardioVasculaireDepart:diuretique","non"); ?>Non&nbsp;&nbsp;
			  			<?php radioButton("id='diuretique_nsp' $color ","CardioVasculaireDepart:diuretique","nsp"); ?>Nsp</td>
			</table>
			</td>
	</tr>
  	<tr>
  		<td width='400'><font style="border-bottom:solid; border-color:green ;" >Echocardiogramme Hypertrophie Ventriculaire Gauche</font></td>
  			<td colspan='2'><font  style=" border-bottom:solid  ; border-color:green ;   " >
			  <?php

				if($CardioVasculaireDepart->HVG=="")
						$color='style="background:orange"';
					else
						$color="";
			  ?>
			<?php radioButton("id='HVG_oui' $color","CardioVasculaireDepart:HVG","oui"); ?>Oui &nbsp;&nbsp;
			  			<?php radioButton("id='HVG_non' $color","CardioVasculaireDepart:HVG","non"); ?>Non&nbsp;&nbsp;
			  			<?php radioButton("id='HVG_nsp' $color","CardioVasculaireDepart:HVG","nsp"); ?>Nsp</font></td>
  	</tr>
  <tr>
  <td width='400'>ECG  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=ECG&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?></td>
	  <?php

		if($CardioVasculaireDepart->isOutdatedECG())
			$color='style="background:orange"';
		else
			$color="";
	  ?>
    <td><?php text("id='dECG' size='10' $color maxlength='10' onkeyup='formate_date(this)'","ECG:date_exam");?>jj/mm/aaaa</td>
  </tr>
  	<tr>
  		<td width='400'><font  style=" border-bottom:solid  ; border-color:green ;   " >A d�faut Surcharge ventriculaire gauche</font></td>
  			<td colspan='2'><font  style=" border-bottom:solid  ; border-color:green ;   " >
			  <?php

			/*	if($CardioVasculaireDepart->surcharge_ventricule=="")
					$color='style="background:orange"';
				else
					$color="";*/
			  ?>
			<?php radioButton("id='surcharge_ventricule_oui' $color","CardioVasculaireDepart:surcharge_ventricule","oui"); ?>Oui &nbsp;&nbsp;
			  			<?php radioButton("id='surcharge_ventricule_non' $color","CardioVasculaireDepart:surcharge_ventricule","non"); ?>Non&nbsp;&nbsp;
			  			<?php radioButton("id='surcharge_ventricule_nsp' $color","CardioVasculaireDepart:surcharge_ventricule","nsp"); ?>Nsp</font>&nbsp;&nbsp;

			  <?php

			/*	if($CardioVasculaireDepart->dsokolov=="")
					$color='style="background:orange"';
				else
					$color="";*/
			  ?>

			  				Sokolov : <?php text("id='sokolov' $color onchange='update_surcharge();' size='4' maxlength='10'","CardioVasculaireDepart:sokolov");?>mm &nbsp; 
							  le <?php text("id='dsokolov' size='10' $color maxlength='10' onkeyup='formate_date(this)'","CardioVasculaireDepart:dsokolov");?>jj/mm/aaaa</td>
  	</tr>
	<tr>
		<td width='400'>Examen cardio-vasculaire   <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoRCVAControler&controlerparams:param:action=AL&HistoRCVA:HistoRCVA:type_exam=exam_cardio&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?><img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>def_examcardio.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Examen cardio-vasculaire' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
			<td>
			  <?php

				if($CardioVasculaireDepart->isOutdatedExamCardio())
					$color='style="background:orange"';
				else
					$color="";
			  ?>
			<?php text("id='exam_cardio' size='10' $color maxlength='10' onkeyup='formate_date(this)'","CardioVasculaireDepart:exam_cardio");?>jj/mm/aaaa</td>
	</tr>
  </table>
  <br>

<h1>2- Calculez le RCVA</h1>
  <b>Indicateurs d'objectifs</b>
  <table border='1' width='700'>
  	<tr>
  		<td width='300'><input type='button' onclick='javascript:calcul_rcva(<?php if($suividiab) echo "1"; else echo "0";?>, 
		  					"<?php echo $dossier->sexe;?>", "<?php echo $dossier->getAge();?>");' value='calculer le RCVA'>
							
						  <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoRCVAControler&controlerparams:param:action=AL&HistoRCVA:HistoRCVA:type_exam=RCVA&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">R�sultats pass�s</a>";?><br>
		Le calcul du RCVA est fait avec les derni�res donn�es disponibles m�me si ces donn�es sont arriv�es � �ch�ance au regard du suivi m�dical						  
							 <img OnClick="javascript:window.open('<?php echo $path;?>/view/cardiovasculaire/loupe_rcva.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
							
							</td>
  			<td id='rcva'>Valeur RCVA : </td>
  	</Tr>
  </Table>
  
<br>	
<h1>3- Indiquez les objectifs � retenir pour la 1�re consultation</h1>
	<table border='1'>
	<tr>
		<td width='300'><b>Objectifs retenus pour la 1�re consultation</b></td>
			<td><b>D�tail</b></td>
	</tr>
	<tr>
		<td width='300'><?php checkBox("id='objectif_poids' ","diageduc:objectif_poids","1");?>
			Poids <img OnClick="javascript:window.open('<?php echo $path;?>/view/cardiovasculaire/objectif_poids.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="D�tail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
			<td><?php text("id='commentaire_obj_poids' maxlength='255'","diageduc:commentaire_obj_poids");?></Td>
	</tr>
	<tr>
		<td width='300'><?php checkBox("id='objectif_alcool' ","diageduc:objectif_alcool","1");?>
			Alcool <img OnClick="javascript:window.open('<?php echo $path;?>/view/cardiovasculaire/objectif_alcool.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="D�tail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</td>
			<td><?php text("id='commentaire_obj_alcool' maxlength='255'","diageduc:commentaire_obj_alcool");?></Td>
	</Tr>
	<tr>
		<td width='300'><?php checkBox("id='objectif_tabac' ","diageduc:objectif_tabac","1");?>
			Tabac <img OnClick="javascript:window.open('<?php echo $path;?>/view/cardiovasculaire/objectif_tabac.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="D�tail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</Td>
			<td><?php text("id='commentaire_obj_tabac' maxlength='255'","diageduc:commentaire_obj_tabac");?></Td>
	</Tr>
	<tr>
		<td width='300'><?php checkBox("id='objectif_tension' ","diageduc:objectif_tension","1");?>
			Tension <img OnClick="javascript:window.open('<?php echo $path;?>/view/cardiovasculaire/objectif_tension.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt="D�tail de l'objectif" border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		</td>
			<td><?php text("id='commentaire_obj_tension' maxlength='255'","diageduc:commentaire_obj_tension");?></Td>
	</tr>
	</table>

  <br><br>



  <input type='button' value='Valider la saisie' onClick="validateInput();">
  <input type='reset' value='Recommencer'> 
</form> 


</body>