<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $HyperTensionArterielle; ?>
<?php global $rowsList;?>
<?php

		$HyperTensionArterielleMapper = new HyperTensionArterielleMapper(NULL);

		$dernierHTA = $HyperTensionArterielleMapper->doLoadObject($rowsList);
		$dernierHTA = $dernierHTA->afterDeserialisation($account);
?>
<script language="javascript">

	function remplacevirgule(valeur){
		return valeur.replace(",",".");
	}
	
	function remplacevirgule2(valeur){
		donnee=document.getElementById(valeur);
		donnee.value=donnee.value.replace(",",".");
	}

	function displayIMC(taille,poids){

		if(taille == 0)
			document.getElementById("IMC").innerHTML = "&nbsp;La taille est invalide IMC ne peut etre calculée";
		else{
			document.getElementById("poids").value=remplacevirgule(document.getElementById("poids").value);
			poidsValue = parseFloat(document.getElementById("poids").value);
			if(isNaN(poidsValue) || poidsValue <30 || poidsValue >200){
				document.getElementById("IMC").innerHTML = "IMC : ";
				return;
			}
			var imc = Math.round(poidsValue/Math.pow(taille/100, 2));
			obj = document.getElementById("IMC").innerHTML="IMC : "+imc;
		}
	}

	function computeCleanrance(sexe,age){
		var clearance = document.getElementById("clearance");
		var poids = document.getElementById("poids").value;
		var creatininemie = document.getElementById("Creat").value;
		var creat = document.getElementById("Creat");
		var clearanceVal;
		var objRegExp  =  /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/

		creatininemie=remplacevirgule(creatininemie);
		creat.value=creatininemie;

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

	function verifie_ta(){
	    var systole = document.getElementById("TaSys");
	    var diastole = document.getElementById("TaDia");
	    var obj_tension_oui = document.getElementById("obj_tension_oui");
	    var obj_tension_non = document.getElementById("obj_tension_non");
//	    var TA_modeMan = document.getElementById("TA_modeMan");
//	    var TA_modeAuto = document.getElementById("TA_modeAuto");
	    var TA_modeMesure = document.getElementById("TA_modeMesure");

	    if(TA_modeMesure.checked==true)
	    {
		    if((systole.value<=135)&&(diastole.value<=80)&&(systole.value>=70)&&(diastole.value>=35))
		    {
		        obj_tension_oui.checked=true;
		    }
		    else
		    {
		        if((systole.value>=70)&&(diastole.value>=35))
			    {
					obj_tension_non.checked=true;
			    }
			    else
			    {
			        obj_tension_non.checked=false;
			        obj_tension_oui.checked=false;
			    }
		    }
	    }
	    else
		{
		    if((systole.value<=140)&&(diastole.value<=90)&&(systole.value>=70)&&(diastole.value>=35))
		    {
		        obj_tension_oui.checked=true;
		    }
		    else
		    {
		        if((systole.value>=70)&&(diastole.value>=35))
			    {
					obj_tension_non.checked=true;
			    }
			    else
			    {
			        obj_tension_non.checked=false;
			        obj_tension_oui.checked=false;
			    }
		    }
		}
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
				alert("Entrer une valeur pour "+valueLabel);
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
	sOk = validDateValuePair(document.getElementById("dpoids").value,document.getElementById("poids").value,"Date du poids","poids");
	if(sOk == 1){
	<?php
	$js->dateInRange("HyperTensionArterielle:dpoids","Date du poids");
	$js->validateRange("HyperTensionArterielle:poids",30,200,"Poids");
	?>
	}
	if(sOk==0)
	{
	    submitOk=0;
	}
	<?php
	
	
	$js->validateRange("HyperTensionArterielle:TaSys",70,300,"Systole");
	$js->validateRange("HyperTensionArterielle:TaDia",35,150,"Diastole");
	?>
 	var TA_modeMan = document.getElementById('TA_modeMan').checked;
	var TA_modeAuto = document.getElementById('TA_modeAuto').checked;
	var TA_modeMesure = document.getElementById('TA_modeMesure').checked;

	if(!TA_modeMan && !TA_modeAuto && !TA_modeMesure){
		 alert("Selectionnez le type de la tension artérielle");
	    submitOk=0;
	}


 	var obj_tension_oui = document.getElementById('obj_tension_oui').checked;
	var obj_tension_non = document.getElementById('obj_tension_non').checked;

	if(!obj_tension_oui && !obj_tension_non){
		 alert("Indiquez si l'objectif tensionnel est atteint");
	    submitOk=0;
	}
<?php
	$js->dateInRange("HyperTensionArterielle:dtension","Date de la tension");

?>
		if(document.getElementById('dcoeur').value != '')
		{
	    <?php
			$js->dateInRange("HyperTensionArterielle:dcoeur","Date d'auscultation du coeur");
		?>
		}
		if(document.getElementById('dartere').value != '')
		{
	    <?php
			$js->dateInRange("HyperTensionArterielle:dartere","Date d'auscultation des artères");
		?>
		}
		if(document.getElementById('dpouls').value != '')
		{
	    <?php
			$js->dateInRange("HyperTensionArterielle:dpouls","Date de palpation des pouls périphériques");
		?>
		}
		if(document.getElementById('dsouffle').value != '')
		{
	    <?php
			$js->dateInRange("HyperTensionArterielle:dsouffle","Date de recherche du souffle abdominal");
		?>
		}

	sOk = validDateValuePair(document.getElementById("dcreat").value,document.getElementById("Creat").value,"Créatinine","Date de la créatinine");
	if(sOk == 1){
	<?php
		$js->dateInRange("HyperTensionArterielle:dcreat","Date de la créatinine");
		$js->validatePositiveNumeric("HyperTensionArterielle:Creat","Créatinine");
	?>
	}
	if(sOk==0)
	{
	    submitOk=0;
	}

	sOk = validDateValuePair(document.getElementById("dglycemie").value,document.getElementById("glycemie").value,"glycémie","Date de la glycémie");
	if(sOk == 1){
	<?php
		$js->dateInRange("HyperTensionArterielle:dglycemie","Date de la glycemie");
		$js->validatePositiveNumeric("HyperTensionArterielle:glycemie","glycémie");
	?>
	}
	if(sOk==0)
	{
	    submitOk=0;
	}

	sOk = validDateValuePair(document.getElementById("dkaliemie").value,document.getElementById("kaliemie").value,"kaliémie","Date de la kaliémie");
	if(sOk == 1){
	<?php
		$js->dateInRange("HyperTensionArterielle:dkaliemie","Date de la kaliémie");
		$js->validatePositiveNumeric("HyperTensionArterielle:kaliemie","kaliémie");
	?>
	}
	if(sOk==0)
	{
	    submitOk=0;
	}

	sOk = validDateValuePair(document.getElementById("dChol").value,document.getElementById("HDL").value,"HDL","Date du HDL");
	if(sOk == 1){
	<?php
		$js->dateInRange("HyperTensionArterielle:dChol","Date du HDL");
		$js->validatePositiveNumeric("HyperTensionArterielle:HDL","HDL");
	?>
	}
	if(sOk==0)
	{
	    submitOk=0;
	}

	sOk = validDateValuePair(document.getElementById("dLDL").value,document.getElementById("LDL").value,"LDL","Date du LDL");
	if(sOk == 1){
	<?php
		$js->dateInRange("HyperTensionArterielle:dLDL","Date du LDL");
		$js->validatePositiveNumeric("HyperTensionArterielle:LDL","LDL");
	?>
	}
	if(sOk==0)
	{
	    submitOk=0;
	}

	if(document.getElementById('dproteinurie').value != '')
	{
	<?php
		$js->dateInRange("HyperTensionArterielle:dproteinurie","Date de la protéinurie");
	?>
	}

	if(document.getElementById('dhematurie').value != '')
	{
	<?php
		$js->dateInRange("HyperTensionArterielle:dhematurie","Date de l'hématurie");
	?>
	}

	if(document.getElementById('dfond').value != '')
	{
	<?php
		$js->dateInRange("HyperTensionArterielle:dfond","Date du fond d'oeil");
	?>
	}
	if(document.getElementById('dECG').value != '')
	{
	<?php
		$js->dateInRange("HyperTensionArterielle:dECG","Date de l'ECG");
	?>
	}
	if(document.getElementById('dconsult').value != '')
	{
	<?php
		$js->dateInRange("HyperTensionArterielle:dconsult","Date de la consultation");
	?>
	}
	
<?php
	$js->endCheckFunction();
?>

</script>

 
<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'> 
	<?php hiddenControler("HyperTensionArterielleControler"); ?>
	<?php hiddenAction(ACTION_SAVE); ?>
	<?php hidden("","HyperTensionArterielle:date");?>
	<?php hidden("","dossier:numero");?>
	
	<?php require("view/common/dossierresume.php");?>
	
  <b>Indicateurs cliniques</b>
  <table border=1>
    <tr>
        <td colspan="2">Indicateur</td>
            <td>Valeur</td>
                <td>Date (jj/mm/aaaa)</td>
                    <td>Fréquence</td>
						<td>Dernière valeur saisie</td>
						    <td>Date d'enregistrement</td>
    </tr>
    <tr > 
      <td colspan="2">Poids <br>(Le poids doit être compris entre 30 et 200Kg)</td>
      <td><?php $dateref=$dernierHTA->IsOutDatedPoids(0);
                
	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}
	  			text("id='poids' size='3' $color onkeyup=\"displayIMC('$dossier->taille','poids')\"","HyperTensionArterielle:poids"); ?>kg<br>
      <table border='0'>
      <tr>
	      <td id='IMC'>IMC:&nbsp;<?php echo($HyperTensionArterielle->getIMC($dossier->taille)==0?"":$HyperTensionArterielle->getIMC($dossier->taille)); ?></td>
      </tr>
      </table>
      <td><?php text("id='dpoids' size='10' $color maxlength='10'","HyperTensionArterielle:dpoids");?></td>
      <td>6 mois</td>
      <td><?php echo $dernierHTA->poids; ?></td>
      <td><?php echo $dernierHTA->dpoids; ?></td>
    </tr>
    <tr>
      <td  colspan="2">Chiffres tensionnels<br><br>
	  				   Objectif tensionnel atteint</td>
      <td colspan="2" nowrap>
        <?php

		 $dateref=$dernierHTA->IsOutDatedtension(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}

		text("id='TaSys' $color onkeyup='verifie_ta()' size='3'","HyperTensionArterielle:TaSys");?>/<?php text("id='TaDia' $color onkeyup='verifie_ta()' size='3'","HyperTensionArterielle:TaDia"); ?>&nbsp;&nbsp;
  <?php text("id='dtension' size='10' $color maxlength='10'","HyperTensionArterielle:dtension");?>
  <br>
        <?php radioButton("id='TA_modeMan' onclick='verifie_ta()' $color ","HyperTensionArterielle:TA_mode","manuel"); ?>
        Manuel
        <?php radioButton("id='TA_modeAuto' onclick='verifie_ta()' $color","HyperTensionArterielle:TA_mode","automatique"); ?>
        Automatique
        <?php radioButton("id='TA_modeMesure' onclick='verifie_ta()' $color","HyperTensionArterielle:TA_mode","automesure"); ?>
        Automesure<br>
        <?php radioButton("id='obj_tension_oui' $color","HyperTensionArterielle:obj_tension","oui"); ?>Oui &nbsp;&nbsp;&nbsp;
		<?php radioButton("id='obj_tension_non' $color","HyperTensionArterielle:obj_tension","non"); ?>Non
	  </td>
	  <td>3 mois</td>
      <td><?php echo $dernierHTA->TaSys."/".$dernierHTA->TaDia; ?></td>
      <td><?php echo $dernierHTA->dtension; ?></td>
	</tr>
	<tr>
	    <td rowspan="4">Examen cardiovasculaire</td>
	        <td>Auscultation du coeur</td>
		<?php

		 $dateref=$dernierHTA->IsOutDatedcoeur(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}


?>
	                <td colspan="2" align="center"><?php text("id='dcoeur' $color size='10' maxlength='10'","HyperTensionArterielle:dcoeur");?></td>
	                    <td>12 mois</td>

      <td colspan="2" align="center"><?php echo $dernierHTA->dcoeur; ?></td>
	</tr>
	<tr>
	        <td>Auscultation des artères</td>
	            <?php

		 $dateref=$dernierHTA->IsOutDatedartere(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}


 ?>

                <td colspan="2" align="center"><?php text("id='dartere' $color size='10' maxlength='10'","HyperTensionArterielle:dartere");?></td>
	                    <td>12 mois</td>
      <td colspan="2" align="center"><?php echo $dernierHTA->dartere; ?></td>
	</tr>
	<tr>
	        <td>Palpation des pouls périphériques</td>
	            <?php

		 $dateref=$dernierHTA->IsOutDatedpouls(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}

?>
	                <td colspan="2" align="center"><?php text("id='dpouls' $color size='10' maxlength='10'","HyperTensionArterielle:dpouls");?></td>
	                    <td>12 mois</td>
      <td colspan="2" align="center"><?php echo $dernierHTA->dpouls; ?></td>
	</tr>
	<tr>
	        <td>Recherche d'un souffle abdominal</td>
	            <?php

		 $dateref=$dernierHTA->IsOutDatedsouffle(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}

?>
	                <td colspan="2" align="center"><?php text("id='dsouffle' $color size='10' maxlength='10'","HyperTensionArterielle:dsouffle");?></td>
	                    <td>12 mois</td>
      <td colspan="2" align="center"><?php echo $dernierHTA->dsouffle; ?></td>
    </tr>

  </table> 
  <br> 
  <br>
  <b>Indicateurs biologiques</b><br>
  <table border=1>
  <tr>
    <td>&nbsp;</td>
   		<td>Valeur</td>
		    <td>Date (jj/mm/aaaa)</td>
    		    <td>Fréquence</td>
					<td>Dernière valeur saisie</td>
					    <td>Date d'enregistrement</td>
  </tr>
  <tr>
    <td>Glycémie à jeun</td>
    <td><?php
		 $dateref=$dernierHTA->IsOutDatedglycemie(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}


	text("id='glycemie' $color onkeyup='remplacevirgule2(\"glycemie\")' size='3'","HyperTensionArterielle:glycemie"); ?>g/l</td>
    <td><?php text("id='dglycemie' $color size='10'","HyperTensionArterielle:dglycemie"); ?></td>
    <td>12 mois</td>
      <td><?php echo $dernierHTA->glycemie; ?></td>
      <td><?php echo $dernierHTA->dglycemie; ?></td>
  </tr>
  <tr valign='top'>
    <td>Cholestérol HDL</td>
    <td><?php

		 $dateref=$dernierHTA->IsOutDatedHDL(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}


	text("id='HDL' size='3' $color onkeyup='remplacevirgule2(\"HDL\")'","HyperTensionArterielle:HDL"); ?> g/l</td>
    <td><?php text("id='dChol' $color size='10'","HyperTensionArterielle:dChol"); ?></td>
    <td rowspan="2">Tous les 36 mois si bilan initial normal</td>
      <td><?php echo $dernierHTA->HDL; ?></td>
      <td><?php echo $dernierHTA->dChol; ?></td>
  </tr>
  <tr>
    <td valign='top'>LDL</td>
    <td><?php

		 $dateref=$dernierHTA->IsOutDatedLDL(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}


	text("id='LDL' size='3' $color onkeyup='remplacevirgule2(\"LDL\")' ","HyperTensionArterielle:LDL"); ?> g/l</td>
    <td><?php text("id='dLDL' $color size='10'","HyperTensionArterielle:dLDL"); ?>
	</td>
      <td><?php echo $dernierHTA->LDL; ?></td>
      <td><?php echo $dernierHTA->dLDL; ?></td>
  </tr>
  <tr>
    <td>Créatinine</td>
    <td><?php

		 $dateref=$dernierHTA->IsOutDatedcreat(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}


	text("id='Creat' size='3' $color onKeyUp ='computeCleanrance(\"$dossier->sexe\",".$dossier->getAge().")' ","HyperTensionArterielle:Creat"); ?> mg<br>
	<table border="0">
	<tr>
	    <td>Clearance calculée : </td>
	    <td id='clearance'>&nbsp;<?php echo($HyperTensionArterielle->getClearance($dossier)); ?>ml/mn</td>
	</tr>
 </table>
    <td><?php text("id='dcreat' $color size='10'","HyperTensionArterielle:dcreat"); ?></td>
    <td>12 mois</td>
      <td><?php echo $dernierHTA->Creat; ?></td>
      <td><?php echo $dernierHTA->dcreat; ?></td>
  </tr>
  <tr>
    <td>Kaliémie</td>
    <td><?php

		 $dateref=$dernierHTA->IsOutDatedkaliemie(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}


	text("id='kaliemie' $color size='3' onkeyup='remplacevirgule2(\"kaliemie\")'","HyperTensionArterielle:kaliemie"); ?>mmol/l</td>
    <td><?php text("id='dkaliemie' $color size='10'","HyperTensionArterielle:dkaliemie"); ?></td>
    <td>12 mois</td>
      <td><?php echo $dernierHTA->kaliemie; ?></td>
      <td><?php echo $dernierHTA->dkaliemie; ?></td>
  </tr>
  <tr>
    <td>Protéinurie</td>
    <td><?php

		 $dateref=$dernierHTA->IsOutDatedproteinurie(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}


		 checkBox("$color id='proteinurie'","HyperTensionArterielle:proteinurie","1"); ?>Positive
		</td>
    <td><?php text("id='dproteinurie' $color size='10'","HyperTensionArterielle:dproteinurie"); ?></td>
    <td>12 mois</td>
      <td><?php if($dernierHTA->dproteinurie!=''){echo ($dernierHTA->proteinurie=="1"?"Positive":"Négative");} ?></td>
      <td><?php echo $dernierHTA->dproteinurie; ?></td>
  </tr>
  <tr>
    <td>Hématurie</td>
    <td><?php 

		 $dateref=$dernierHTA->IsOutDatedhematurie(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}


	 checkBox("$color id='hematurie'","HyperTensionArterielle:hematurie","1"); ?>Positive

		</td>
    <td><?php text("id='dhematurie' $color size='10'","HyperTensionArterielle:dhematurie"); ?></td>
    <td>A l'initialisation du protocole</td>
      <td><?php if($dernierHTA->dhematurie!=""){echo ($dernierHTA->hematurie=="1"?"Positive":"Négative");} ?></td>
      <td><?php echo $dernierHTA->dhematurie; ?></td>
  </tr>
  </table>
  <br>
  <br>
  <b>Indicateurs para-cliniques</b><br>
  <table border=1>
  <tr>
    <td>&nbsp;</td>
        <td>Date</td>
                <td>Fréquence</td>
					    <td>Date d'enregistrement</td>
  </tr>
  <tr>
    <td>Fond d'&oelig;il</td>
<?php

		 $dateref=$dernierHTA->IsOutDatedfond(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}

?>
    <td><?php text("id='dfond' $color size='10'","HyperTensionArterielle:dfond"); ?></td>
    <td>12 mois si HTA sévère ou co-morbidité</td>
      <td><?php echo $dernierHTA->dfond; ?></td>
  </tr>
  <td>ECG</td>
<?php

		 $dateref=$dernierHTA->IsOutDatedECG(0);

	  			if($dateref===false)
	  			{
	  			    $color="";
	  			}
	  			else
	  			{
	  			    $color='style="background:orange"';
	  			}


?>
    <td><?php text("id='dECG' $color size='10'","HyperTensionArterielle:dECG"); ?></td>
    <td>En cas de signe d'appel ou en fonction de traitement (3 ans)</td>
      <td><?php echo $dernierHTA->dECG; ?></td>
  </tr>
  </table>
  <br>
  <br>
  <b>Indicateurs de gravité</b><br>
  <table border='1'>
  <tr>
    <td><?php checkBox("","HyperTensionArterielle:hta_instable","1"); ?>
      HTA non stabilisée ou instable</td>
    <td><?php checkBox("","HyperTensionArterielle:hta_tritherapie","1"); ?>
      HTA sous trithérapie ou plus</td>
	<td><?php checkBox("","HyperTensionArterielle:hta_complique","1"); ?>
      HTA compliquée</td>
  </tr>
  <tr>
    <td><?php checkBox("","HyperTensionArterielle:tabac","1"); ?>
      Tabac</td>
    <td><?php checkBox("","HyperTensionArterielle:hyperlipidemie","1"); ?>
      Hyperlipidémie</td>
	<td><?php checkBox("","HyperTensionArterielle:alcool","1"); ?>
      Alcool</td>
  </tr>
  </table>
  <br>
  <br>
  <b>S'il y a eu consultation infirmière</b><br>
  <table border='1'>
 <tr>
    <td>Date de la consultation</td>
        <td><?php text("id='dconsult' size='10'","HyperTensionArterielle:dconsult"); ?></td>
  </tr>
  <tr>
      <td>Degré de satisfaction:</td>
      <td><?php selectv("","HyperTensionArterielle:degre_satisfaction",$satisfaction); ?></td>
  </tr>
  <tr>
    <td valign="top" rowspan="4" width='50%'>Indicateurs d'observance du traitement<br>
								Liste provisoire, à affiner. La mention "fait" indique que le sujet a été abordé en consultation</td>
    <td>Evaluation de la qualité de vie par rapport au traitement<br>
		<?php radioButton("id='qualite_vie_oui'","HyperTensionArterielle:qualite_vie","oui"); ?>Fait &nbsp;&nbsp;
		<?php radioButton("id='qualite_vie_non'","HyperTensionArterielle:qualite_vie","non"); ?>Pas fait
	</td>
   </tr>
   <tr>
    <td>Recherche de la iatrogénie et des effets secondaires<br>
		<?php radioButton("id='iatrogenie_oui'","HyperTensionArterielle:iatrogenie","oui"); ?>Fait &nbsp;&nbsp;
		<?php radioButton("id='iatrogenie_non'","HyperTensionArterielle:iatrogenie","non"); ?>Pas fait
	</td>
   </tr>
   <tr>
    <td>Délivrance des traitements<br>
		<?php radioButton("id='deliv_trait_oui'","HyperTensionArterielle:deliv_trait","oui"); ?>Fait &nbsp;&nbsp;
		<?php radioButton("id='deliv_trait_non'","HyperTensionArterielle:deliv_trait","non"); ?>Pas fait
	</td>
   </tr>
   <tr>
    <td>Régularité des prises<br>
		<?php radioButton("id='regul_prises_oui'","HyperTensionArterielle:regul_prises","oui"); ?>Fait &nbsp;&nbsp;
		<?php radioButton("id='regul_prises_non'","HyperTensionArterielle:regul_prises","non"); ?>Pas fait
	</td>
   </tr>
   <tr>
    <td>Compte-rendu de la consultation (soulignez principalement les manques)</td>
      <td><?php textArea("rows=\"4\" cols=\"30\"","HyperTensionArterielle:cpt_rendu"); ?></td>
    </td>
   </tr>
  </table>
  <br><br>
  <input type='button' value='Valider la saisie' onClick="validateInput()">
  <input type='reset' value='Recommencer'> 
</form> 
