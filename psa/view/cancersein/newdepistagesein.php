

<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $depistageCancerSein; ?>
<?php global $dernierExam; ?>

<script type="text/javascript" >
function rappel_mammo()
{
	formate_date(document.getElementById("mammograph_date"));
	var mammograph_date = document.getElementById("mammograph_date");
	var mammograph_rappel;
 if((mammograph_date.value.length)==10)
	{
	tab_mammo=mammograph_date.value.split('/');
	test=new Date(tab_mammo[2], tab_mammo[1], tab_mammo[0]);
	if (tab_mammo[1]==12)
	    annee=test.getFullYear()+1;
	else
		annee=test.getFullYear()+2;
	mammograph_rappel=tab_mammo[0]+'/'+tab_mammo[1]+'/'+annee;

 	document.getElementById("rappel_mammographie").value=mammograph_rappel;

	}
}

<?php
	validateDate();
	dateInRange();
	compareDates();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","saveForm");
	$js->dateInRange("depistageCancerSein:mamograph_date","Date de mammographie");
	$js->dateInRange("dossier:dnaiss","Date de naissance");	

	?>
	var typeColl = document.getElementById("coll");
	var typeIndiv = document.getElementById("indiv");
	
	if(typeColl.checked == false){
		if(typeIndiv.checked == false){
			alert("Choisissez le type de dépistage");
			submitOk = 0;
		}
	}
			
	<?php	
	$js->endCheckFunction();	


?>
</script>

<?php

if($dernierExam!=""){
	if($dernierExam->ant_fam_mere=='1'){
		$check_mere="checked";
	}
	else{
		$check_mere="";
	}
	
	if($dernierExam->ant_fam_soeur=='1'){
		$check_soeur="checked";
	}
	else{
		$check_soeur="";
	}
	
	if($dernierExam->ant_fam_fille=='1'){
		$check_fille="checked";
	}
	else{
		$check_fille="";
	}
	
	if($dernierExam->ant_fam_tante=='1'){
		$check_tante="checked";
	}
	else{
		$check_tante="";
	}
	
	if($dernierExam->ant_fam_grandmere=='1'){
		$check_grandmere="checked";
	}
	else{
		$check_grandmere="";
	}
}
else{
	$check_mere=$check_soeur=$check_fille=$check_tante=$check_grandmere="";
}
?>
<br> 
<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'> 
  <?php hiddenControler("DepistageCancerSeinControler"); ?>
  <?php hiddenAction(ACTION_SAVE); ?>
  <?php hidden("","depistageCancerSein:date");?>	
  <?php hidden("","dossier:numero"); ?>
  <?php hidden("","dossier:id"); ?>
  <?php hidden("","dossier:cabinet"); ?>
  
<table border='0'><tr><td>
  <?php require("view/common/dossierresume_modif.php");?>
  </td><td width='20'>&nbsp;</td>
  <td>Ce formulaire permet à tout instant de collecter des données utiles au protocole dépistage du cancer du sein.<br><br>
Il s'appuie sur les données les plus récentes du patient (résultats d'examens, antécédents)<br><br>
</td></tr>
</Table>
	
  <b>Antécédents familiaux:</b> 
  <table border=1> 
    <tr> 
      <td>mère</td> 
      <td>s&oelig;ur</td> 
      <td>fille</td> 
      <td>tante</td> 
      <!-- <td>grand-mère</td>  -->
    </tr> 
    <tr align='center'> 
      <td><?php checkBox("$check_mere","depistageCancerSein:ant_fam_mere","1"); ?></td> 
      <td><?php checkBox("$check_soeur","depistageCancerSein:ant_fam_soeur","1"); ?></td> 
      <td><?php checkBox("$check_fille","depistageCancerSein:ant_fam_fille","1"); ?></td> 
      <td><?php checkBox("$check_tante","depistageCancerSein:ant_fam_tante","1"); ?></td> 
      <!-- <td><?php #checkBox("$check_grandmere","depistageCancerSein:ant_fam_grandmere","1"); ?></td>  -->
    </tr> 
  </table> 
  <br> 
  <b>Type de dépistage:</b><br> 
  <table border=0> 
    <tr> 
      <td>Dépistage collectif?</td> 
      <td><?php radioButton("id='coll'","depistageCancerSein:dep_type","coll"); ?></td> 
    </tr> 
    <tr>
      <td>Dépistage individuel?</td>
      <td><?php radioButton("id='indiv'","depistageCancerSein:dep_type","indiv"); ?></td>
    </tr>
  </table> 
  <br> 
  <b>Date de mammographie:<?php text("id='mammograph_date' size='10' onKeyUp ='rappel_mammo()'","depistageCancerSein:mamograph_date"); ?></b>
  &nbsp;&nbsp; <?php if($dernierExam!=""){?>Date de la dernière mammographie saisie : <?php echo $dernierExam->mamograph_date;}?>
  <br> 
  <br>
  <b>Rappel mammographie:</b>
  <table border=0>
    <tr>
      <td>Date du rappel :
        <?php text("id='rappel_mammographie' size='10' onkeyup='formate_date(this)'","depistageCancerSein:rappel_mammographie");?></td>
    </tr>
  </table>

  <br>
  <table border=0>
    <tr>
      <td width='180'>Sortir cette personne du dépistage cancer du sein</td>
	      <td><?php checkBox("","depistageCancerSein:sortir_rappel","1"); ?></td>
	</tr>
	<tr>
		<td>Raison : </td>
		    <td><?php textArea("rows=\"3\" cols=\"30\" ","depistageCancerSein:raison_sortie"); ?></td>
    </tr>
  </table>
  <br>
  <input type='button' value='Enregistrer' onclick='validateInput()'> 
  <input type='reset' value='Recommencer'> 
</form> 
