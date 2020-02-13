<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>
<?php require_once("global/config.php"); ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $suiviDiabete;?>
<?php global $param ?>
<?php global $dernier_suivi;?>

<?php
    global $form_class;
    $form_class = "SuiviDiabete";
?>

<?php
	foreach($_ENV['liste_exam_diabete'] as $exam){
		global $$exam;
	}
	?>
  <script language="JavaScript" type="text/javascript">

function affiche_detail(element){
	var element=document.getElementById(element);

	if(element.style.display=='none')
	{
		element.style.display='';
	}
	else
	{
	    element.style.display='none';
	}

}

</SCRIPT>
<script type="text/javascript" >

	function remplacevirgule(valeur){
		return valeur.replace(",",".");
	}
	
/*	function formate_date(zone){
		if(zone.value.length==2){
			zone.value=zone.value+"/";
		}
		if(zone.value.length==4){
			zone.value=zone.value.replace("//", "/");
		}
		if(zone.value.length==5){
			zone.value=zone.value+"/";
		}
		if(zone.value.length==7){
			zone.value=zone.value.replace("//", "/");
		}
	}
	*/
	function validDateValuePair(date,value,dateLabel,valueLabel){
		if ((valueLabel == "ECG") || (valueLabel == "fond d'oeil") || (valueLabel == "albuminurie"))
		{
			if(value == true) value ="true";
			if((date.length==0) && (value == false)){
				return -1;
			}
		
			if(date.length!=0 && value.length== 0){	
				alert("Entrer une valeur pour "+dateLabel);
				return 0;			
			}
			if(date.length==0 && value == "true"){
				alert("Entrer une valeur pour "+valueLabel);
				return 0;
			}
		}
		else
		{
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
				alert("Entrer une valeur pour "+valueLabel);
				return 0;
			}
		}
				
		return 1;	
	}

<?php
	compareDates();
	validateDate();	
	dateInRange();
	validatePositiveNumeric();
	validateNumeric();
	
	$js = new JSValidation();
	
		
	$js->startCheckFunction("validateInput","aForm"); ?>	
	var submit4Mois = 1;
	var submitSemestriel = 1;
	var submitAnnuel = 1;
	var submitSyst = 1;
	
	submitSyst = checkSystematique("aForm");
	
	
	<?php if(in_array("4",$suiviDiabete->suivi_type)) {?>
		submit4Mois = check4Mois("aForm");
	<?php }
	 	 if(in_array("s",$suiviDiabete->suivi_type)) { ?>
	     submitSemestriel = checkSemestriel("aForm");
	<?php }
		if(in_array("a",$suiviDiabete->suivi_type)) {?>
	     submitSemestriel = checkSemestriel("aForm");
	     
		 submitAnnuel = checkAnnuel("aForm");
		<?php } ?>
		//alert(submitSyst);
		//alert(submitSemestriel);
		//alert(submit4Mois);
		//alert(submitAnnuel);
		if(submitSyst == 0 || submit4Mois == 0 || submitSemestriel == 0 || submitAnnuel == 0)
			submitOk = 0;
	<?php 
	$js->dateInRange("dossier:dnaiss","Date de naissance");	
	// $js->dateInRange("suiviDiabete:dHBA","date HBA1C");
	/*$js->dateInRange("suiviDiabete:dChol","date Cholestérol HDL");
	$js->dateInRange("suiviDiabete:dLDL","date LDL");
	$js->dateInRange("suiviDiabete:dCreat","date Créatininémie");
	$js->dateInRange("suiviDiabete:dAlbu","date Albuminurie");	
	$js->dateInRange("suiviDiabete:dFond","date Fond d'oeil");		
	$js->dateInRange("suiviDiabete:dECG","date ECG de repos");
	$js->dateInRange("suiviDiabete:dExaFil","date examen au monofilament");
	$js->dateInRange("suiviDiabete:dExaPieds","date examen des pieds");*/
	//$js->validateRange("suiviDiabete:TaDias",0,80,"Diastole");
	//$js->validateRange("suiviDiabete:TaSys",0,135,"Systole");
	$js->endCheckFunction();
?>
</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm"> 
<table border='0'><tr><td>
<?php require("view/common/dossierresume_modif.php");?>
</td><td width='20'>&nbsp;</td>
<td>Ce formulaire permet à tout instant de collecter des données utiles au protocole suivi du diabétique de type 2.<br><br>
Il s'appuie sur les données les plus récentes du patient (poids, résultats d'examens, etc...)<br><br>
Il est également possible d'y visualiser l'historique des données (poids, examens, etc...).<br><br>
</td>
</Table>
<br>
<?php require("historiquesuividiabete.php") ?>

<br> 
  <?php hiddenControler("SuiviDiabeteControler"); ?> 
  <?php hiddenAction(ACTION_SAVE); ?> 
  <?php hidden("","suiviDiabete:dsuivi");?>
  <?php hidden("","dossier:numero"); ?>
  <?php hidden("","dossier:id"); ?>
  <?php hidden("","dossier:cabinet"); ?>
	
	<?php
	
	
	// $liste_exam=array("creat", "albu", "fond", "ECG", 
	// 				  "dent", "pied", "monofil", "poids", "systole", 
	// 				  "diastole", "type_tension", "HDL", "LDL", "HBA1c");	
    
	foreach($_ENV['liste_exam_diabete'] as $exam){
		hidden("","$exam:id");
		hidden("","$exam:type_exam");
		hidden("","$exam:numero");
	}
	
   for($i=0;$i<count($suiviDiabete->suivi_type);$i++){
  		hidden("","suiviDiabete:suivi_type",$suiviDiabete->suivi_type[$i]);
  } ?>
				
  <br> 
  <b>Saisie d'un suivi au <?php echo($suiviDiabete->dsuivi); ?></b><br> 
  <table border=0 cellspacing="14"> 
	  <tr> 
		<td>Type de suivi</td> 
		<td colspan=2>
		<?php 
			if(count ($suiviDiabete->suivi_type) == 0) echo("Aucun");
			else{			
				
				if(in_array("4",$suiviDiabete->suivi_type)) {?>	<font color="blue">4 mois</font> <?php }
				/*else    {?>
				    <input type='button' value="Ajouter un suivi 4 mois">
				    <?php }*/
				if(in_array("a",$suiviDiabete->suivi_type) ||
				   in_array("s",$suiviDiabete->suivi_type)) {?>	<font color="red">Annuel</font> <?php }
  				/*else    {?>
				    <input type='button' value="Ajouter un suivi annuel">
				    <?php }*/
			}
		?>
		</td> 
	  </tr> 
  </table> 
  
  <?php require("view/diabete/suivi/newsuividiabetesystematique1.php"); ?><br>
	  <?php if(in_array("4",$suiviDiabete->suivi_type)){ 
	  	require("view/diabete/suivi/newsuividiabete4mois.php"); echo "<br>";}?>
	  <?php if(in_array("a",$suiviDiabete->suivi_type)||in_array("s",$suiviDiabete->suivi_type)){ 
	  	require("view/diabete/suivi/newsuividiabetesemestriel.php");
		require("view/diabete/suivi/newsuividiabeteannuel.php");} ?>
  <?php require("view/diabete/suivi/newsuividiabetesystematique2.php"); ?>

  <?php
    if (in_array($account->cabinet, $liste_cabs_aut))
        require("view/depistage/depistage_aomi.php");
  ?>

  <table border='1' width='70%'> 
    <tr> 
      <td align='center'><input type='button' onclick="validateInput()" value='Valider la saisie'> 
        <input type='reset' value='Recommencer'></td> 
    </tr> 
  </table> 
</form> 

