
<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $depistageCancerColon; ?>
<?php global $dernierExam; ?>

<?php
if($dernierExam!=""){
	$depistageCancerColon->ant_pere_type=$dernierExam->ant_pere_type;
	($dernierExam->ant_pere_age==0)?$depistageCancerColon->ant_pere_age="":$depistageCancerColon->ant_pere_age=$dernierExam->ant_pere_age;
	$depistageCancerColon->ant_mere_type=$dernierExam->ant_mere_type;
	($dernierExam->ant_mere_age==0)?$depistageCancerColon->ant_mere_age="":$depistageCancerColon->ant_mere_age=$dernierExam->ant_mere_age;
	$depistageCancerColon->ant_fratrie_type=$dernierExam->ant_fratrie_type;
	($dernierExam->ant_fratrie_age==0)?$depistageCancerColon->ant_fratrie_age="":$depistageCancerColon->ant_fratrie_age=$dernierExam->ant_fratrie_age;
	$depistageCancerColon->ant_collat_type=$dernierExam->ant_collat_type;
	($dernierExam->ant_collat_age==0)?$depistageCancerColon->ant_collat_age="":$depistageCancerColon->ant_collat_age=$dernierExam->ant_collat_age;
	$depistageCancerColon->ant_enfants_type=$dernierExam->ant_enfants_type;
	($dernierExam->ant_enfants_age==0)?$depistageCancerColon->ant_enfants_age="":$depistageCancerColon->ant_enfants_age=$dernierExam->ant_enfants_age;
	$depistageCancerColon->just_ant_fam=$dernierExam->just_ant_fam;
	$depistageCancerColon->just_ant_polype=$dernierExam->just_ant_polype;
	$depistageCancerColon->just_ant_cr_colique=$dernierExam->just_ant_cr_colique;
	$depistageCancerColon->just_ant_sg_selles=$dernierExam->just_ant_sg_selles;
}
?>

<script language="javascript">

function remplacevirgule(valeur){
	donnee=document.getElementById(valeur);
	donnee.value=donnee.value.replace(",",".");
}

function calcule_antecedents_familiaux(choix) { // calcule la valeur de l'antécédent si les conditions sont remplies
var just_ant_fam;

just_ant_fam = document.getElementById("just_ant_fam");
ant_pere_type = document.getElementById("ant_pere_type");
ant_mere_type = document.getElementById("ant_mere_type");
ant_fratrie_type = document.getElementById("ant_fratrie_type");
//ant_collat_type = document.getElementById("ant_collat_type");
ant_enfants_type = document.getElementById("ant_enfants_type");

if (choix==1)
{
	for(i = 1 ; i < ant_pere_type.length ; i++) {
		if(ant_pere_type.options[i].selected==true) {
	        ant_pere_type.options[0].selected=false;
			just_ant_fam.checked=true;
			return;
		}
	}
}

if (choix==2)
{
	for(i = 1 ; i < ant_mere_type.length ; i++) {
		if(ant_mere_type.options[i].selected==true) {
	        ant_mere_type.options[0].selected=false;
			just_ant_fam.checked=true;
			return;
		}
	}
}

if (choix==3)
{
	for(i = 1 ; i < ant_fratrie_type.length ; i++) {
		if(ant_fratrie_type.options[i].selected==true) {
	        ant_fratrie_type.options[0].selected=false;
			just_ant_fam.checked=true;
			return;
		}
	}
}
/*
if (choix==4)
{
	for(i = 1 ; i < ant_collat_type.length ; i++) {
		if(ant_collat_type.options[i].selected==true) {
	        ant_collat_type.options[0].selected=false;
			just_ant_fam.checked=true;
			return;
		}
	}
}
*/
if (choix==5)
{
	for(i = 1 ; i < ant_enfants_type.length ; i++) {
		if(ant_enfants_type.options[i].selected==true) {
	        ant_enfants_type.options[0].selected=false;
			just_ant_fam.checked=true;
			return;
		}
	}
}

for(i = 1 ; i < ant_enfants_type.length ; i++) {
		if((ant_enfants_type.options[i].selected==true) ||
		//	(ant_collat_type.options[i].selected==true) ||
			(ant_fratrie_type.options[i].selected==true) ||
			(ant_mere_type.options[i].selected==true) ||
			(ant_pere_type.options[i].selected==true)) {
				just_ant_fam.checked=true;
				return;
		}
}
/*   if(document.getElementById("ant_pere_type").selectedIndex!=0) {
	  just_ant_fam.checked=true;
	  return;
   }
   if(document.getElementById("ant_mere_type").selectedIndex!=0) {
	  just_ant_fam.checked=true;
	  return;
   }	  
   if(document.getElementById("ant_fratrie_type").selectedIndex!=0) {
	  just_ant_fam.checked=true;
	  return;
   }	  
   if(document.getElementById("ant_collat_type").selectedIndex!=0) {
	  just_ant_fam.checked=true;
	  return;
   }	  
   if(document.getElementById("ant_enfants_type").selectedIndex!=0) {
	  just_ant_fam.checked=true;
	  return;
   }
*/
   just_ant_fam.checked=false;
}

function selectRappelColoscopie(object){

	var rappel_colos_period = document.getElementById("rappel_colos_period");	
	var RapAutre = document.getElementById("RapAutre");
	if(object.name == 'RapAutre') {
	    remplacevirgule("RapAutre");
		rappel_colos_period.value = object.value;
		return;
	}
	if(object.value == 'autre'){
		RapAutre.disabled = false;
		rappel_colos_period.value = RapAutre.value;
		return;
	}
	RapAutre.disabled = true;
	RapAutre.value = "";
	rappel_colos_period.value = object.value;	
}

</script>

<script type="text/javascript" >

<?php
	validateAge();
	compareDates();
	dateInRange();
	validateDate();	
	validatePositiveNumeric();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","saveForm");
	$js->validateAge("depistageCancerColon:ant_pere_age","Age antécédent père");
	$js->validateAge("depistageCancerColon:ant_mere_age","Age antécédent mère");
	$js->validateAge("depistageCancerColon:ant_fratrie_age","Age antécédent fratrie");
//	$js->validateAge("depistageCancerColon:ant_collat_age","Age antécédent oncle/tante");
	$js->validateAge("depistageCancerColon:ant_enfants_age","Age antécédent enfants");
	$js->dateInRange("depistageCancerColon:colos_date","Date colloscopie");
	$js->validatePositiveNumeric("depistageCancerColon:rappel_colos_period","Rappel coloscopie");
	$js->dateInRange("dossier:dnaiss","Date de naissance");	
	$js->endCheckFunction();
?>

</script>

 
<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'> 
	<?php hiddenControler("DepistageCancerColonControler"); ?>
	<?php hiddenAction(ACTION_SAVE); ?>
	<?php hidden("","depistageCancerColon:date");?>
	<?php hidden("id = 'rappel_colos_period' ","depistageCancerColon:rappel_colos_period");?>
	
	<?php hidden("","dossier:numero");?>
	<?php hidden("","dossier:id"); ?>
	<?php hidden("","dossier:cabinet"); ?>

<table border='0'><tr><td>	
	<?php require("view/common/dossierresume_modif.php");?>
</Td><td width='20'>&nbsp;</td>
<td> Ce formulaire permet à tout instant de collecter des données utiles au protocole dépistage du cancer du colon.<br><br>
Il s'appuie sur les données les plus récentes du patient (résultats d'examens, antécédents)<br><br>
</td></tr>
</table>
	
  <b>Antécédents familiaux:</b> 
  <table border=1> 
    <tr align='center'> 
      <th>lien:</th> 
      <th>père</th> 
      <th>mère</th> 
      <th>frère ou s&oelig;ur</th> 
<!--      <th>oncle ou tante</th>-->
      <th>enfant</th> 
    </tr> 
    <tr> 
      <th scope="row">type:</td> 
      <td><?php selectv("id='ant_pere_type' onchange='calcule_antecedents_familiaux(1)' multiple","depistageCancerColon:ant_pere_type",$antFam); ?></td>
      <td><?php selectv("id='ant_mere_type' onchange='calcule_antecedents_familiaux(2)' multiple","depistageCancerColon:ant_mere_type",$antFam); ?></td>
      <td><?php selectv("id='ant_fratrie_type' onchange='calcule_antecedents_familiaux(3)' multiple","depistageCancerColon:ant_fratrie_type",$antFam); ?></td>
 <!--     <td>--><?php /*selectv("id='ant_collat_type' onchange='calcule_antecedents_familiaux(4)' multiple","depistageCancerColon:ant_collat_type",$antFam); */?><!--</td>-->
      <td><?php selectv("id='ant_enfants_type' onchange='calcule_antecedents_familiaux(5)' multiple","depistageCancerColon:ant_enfants_type",$antFam); ?></td>
    </tr> 
    <tr align='center'> 
      <th scope="row">âge de survenue:</th> 
      <td><?php text("size ='2'","depistageCancerColon:ant_pere_age"); ?>ans</td> 
      <td><?php text("size ='2'","depistageCancerColon:ant_mere_age"); ?>ans</td> 
      <td><?php text("size ='2'","depistageCancerColon:ant_fratrie_age"); ?>ans</td> 
   <!--   <td>--><?php /*text("size ='2'","depistageCancerColon:ant_collat_age"); */ ?><!--ans</td>-->
      <td><?php text("size ='2'","depistageCancerColon:ant_enfants_age"); ?>ans</td> 
    </tr> 
  </table> 
  <br> 
  <b>Justification du dépistage:</b><br> 
  <table border=0> 
    <tr> 
      <td>Antécédents familiaux</td> 
      <td><?php /*checkBox("id='just_ant_fam' onchange='calcule_antecedents_familiaux()'","depistageCancerColon:just_ant_fam","1");*/
	  			checkBox("id='just_ant_fam' ","depistageCancerColon:just_ant_fam","1"); ?></td>
    </tr> 
    <tr> 
      <td>Antécédent personnel de polype</td> 
      <td><?php checkBox("","depistageCancerColon:just_ant_polype","1"); ?></td> 
    </tr> 
    <tr> 
      <td>Antécédent personnel de cancer colique</td> 
      <td><?php checkBox("","depistageCancerColon:just_ant_cr_colique","1"); ?></td> 
    </tr> 
    <tr> 
      <td>Sang dans les selles</td> 
      <td><?php checkBox("","depistageCancerColon:just_ant_sg_selles","1"); ?></td> 
    </tr> 
  </table> 
  <br> 
  <br> 
  <b>Coloscopie:</b><br> 
  <table border=0> 
    <tr> 
      <td>Date</td> 
      <td><?php text(" onkeyup='formate_date(this)'","depistageCancerColon:colos_date"); ?><i>(format JJ/MM/AAAA)</i>
	  &nbsp;&nbsp; <?php if($dernierExam!=""){?>Date de la dernière coloscopie saisie : <?php echo $dernierExam->colos_date;}?>
	  </td>
    </tr> 
    <tr> 
      <td>Polypes:</td> 
      <td>
	  	<?php radioButton("id='colos_polypes1'","depistageCancerColon:colos_polypes","1"); ?> oui
	  	<?php radioButton("id='colos_polypes0'","depistageCancerColon:colos_polypes","0"); ?> non		
	  </td> 
    </tr> 
    <tr> 
      <td>Dysplasie:</td> 
      <td>
	  	<?php radioButton("id='colos_dysplasie_none'","depistageCancerColon:colos_dysplasie","aucun"); ?> pas de dysplasie
        <?php radioButton("id='colos_dysplasie_low'","depistageCancerColon:colos_dysplasie","bas"); ?> dysplasie de bas grade
        <?php radioButton("id='colos_dysplasie_high'","depistageCancerColon:colos_dysplasie","haut"); ?> dysplasie de haut grade
        <?php radioButton("id='colos_dysplasie_cr_colon'","depistageCancerColon:colos_dysplasie","cancer"); ?> cancer du colon
	  </td> 
  </table> 
  <br> 
  <b>Rappel coloscopie:</b> 
  <table border=0> 
    <tr> 
      <td><?php if($depistageCancerColon->rappel_colos_period == 0 ) $checked = "checked"; else $checked=""; ?><input  onClick="selectRappelColoscopie(this)" name="colos_period" type="radio" value="0" <?php echo($checked); ?> > pas de rappel</td>
      <td><?php if($depistageCancerColon->rappel_colos_period == 1 ) $checked="checked"; else $checked=""; ?><input onClick="selectRappelColoscopie(this)" name="colos_period" type="radio" value="1" <?php echo($checked); ?> > un an</td>
      <td><?php if($depistageCancerColon->rappel_colos_period == 2 ) $checked="checked"; else $checked=""; ?><input onClick="selectRappelColoscopie(this)" name="colos_period" type="radio" value="2" <?php echo($checked); ?> > deux ans</td>
      <td><?php if($depistageCancerColon->rappel_colos_period == 3 ) $checked="checked"; else $checked=""; ?><input onClick="selectRappelColoscopie(this)" name="colos_period" type="radio" value="3" <?php echo($checked); ?> > trois ans</td>
    </tr> 
    <tr> 
      <td><?php if($depistageCancerColon->rappel_colos_period == 5 ) $checked="checked"; else $checked=""; ?><input onClick="selectRappelColoscopie(this)" name="colos_period" type="radio" value="5" <?php echo($checked); ?> > cinq ans</td>
      <td colspan=3>
	  	<?php  if($depistageCancerColon->rappel_colos_period != 0 and  $depistageCancerColon->rappel_colos_period !=1
		  		and $depistageCancerColon->rappel_colos_period!=2 and $depistageCancerColon->rappel_colos_period!=3
				and $depistageCancerColon->rappel_colos_period!=5 )  $checked="checked";
			else $checked=""; ?>
	  	<input onClick="selectRappelColoscopie(this)" name="colos_period" type="radio" value="autre" <?php echo($checked); ?>>  autre, précisez:
        <input onKeyUp="selectRappelColoscopie(this)" id="RapAutre" name="RapAutre" type="text" size="4" <?php echo($checked !=""?" value = $depistageCancerColon->rappel_colos_period":"disabled") ?> > ans </td>
    </tr> 
  </table> 
  <br>
  <table border=0>
    <tr>
      <td width='180'>Sortir cette personne du dépistage cancer du colon</td>
	      <td><?php checkBox("","depistageCancerColon:sortir_rappel","1"); ?></td>
	</tr>
	<tr>
		<td>Raison : </td>
		    <td><?php textArea("rows=\"3\" cols=\"30\" ","depistageCancerColon:raison_sortie"); ?></td>
    </tr>
  </table>
  <br>
  <input type='button' value='Valider la saisie' onClick="validateInput()"> 
  <input type='reset' value='Recommencer'> 
</form> 
