

<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $depistageCancerUterus; ?>
<?php global $dernierExam;?>

<script type="text/javascript" >

function rappel_frottis()
{
	var date_frottis = document.getElementById("date_frottis");
	var date_rappel;

 if((date_frottis.value.length)==10)
	{
	tab_frottis=date_frottis.value.split('/');
	test=new Date(tab_frottis[2], tab_frottis[1], tab_frottis[0]);
	if (tab_frottis[1]==12)
	    annee=test.getFullYear()+3;
	else
		annee=test.getFullYear()+3;
	date_rappel=tab_frottis[0]+'/'+tab_frottis[1]+'/'+annee;

 	document.getElementById("date_rappel").value=date_rappel;

	}
}


function calc_rappel(object)
{
	formate_date(object);
	var date_rappel=document.getElementById("date_rappel").value=object.value;
}

function active(object){

	var frottis_normal = document.getElementById("frottis_normal");
	var avis_medecin = document.getElementById("avis_medecin");
	var rappel_normal = document.getElementById("rappel_normal");
	var rappel_anormal = document.getElementById("rappel_anormal");

	if(object.value == 'non'){

		rappel_normal.value='';
		rappel_normal.disabled=true;
		avis_medecin.disabled = false;
		rappel_anormal.disabled = false;

//		alert(rappel_anormal.disabled);
		return;
	}
	
			avis_medecin.disabled = true;
			rappel_anormal.disabled=true;
			rappel_anormal.value='';
			rappel_normal.disabled=false;
			avis_medecin.value = "";
			
			

			var date_frottis = document.getElementById("date_frottis");
			var date_rappel;

			 if((date_frottis.value.length)==10)
				{
				tab_frottis=date_frottis.value.split('/');
				test=new Date(tab_frottis[2], tab_frottis[1], tab_frottis[0]);
				if (tab_frottis[1]==12)
				    annee=test.getFullYear()+2;
				else
					annee=test.getFullYear()+3;
				date_rappel=tab_frottis[0]+'/'+tab_frottis[1]+'/'+annee;

			 	document.getElementById("date_rappel").value=date_rappel;
			 	document.getElementById("rappel_normal").value=date_rappel;
			}


}


<?php
	validateDate();
	dateInRange();
	compareDates();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","saveForm");
	$js->dateInRange("depistageCancerUterus:date_frottis","Date de frottis");
	$js->dateInRange("depistageCancerUterus:date_rappel","Date de rappel");
	?>

	var normal = document.getElementById("frottis_normal");
	var anormal = document.getElementById("frottis_anormal");

	if(normal.checked == false){
		if(anormal.checked == false){
			alert("indiquez si le frottis est normal ou anormal");
			submitOk = 0;
		}
	}


			
	<?php	
	$js->dateInRange("dossier:dnaiss","Date de naissance");	
	$js->endCheckFunction();	


?>
</script>

<br> 
<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'> 
  <?php hiddenControler("DepistageCancerUterusControler"); ?>
  <?php hiddenAction(ACTION_SAVE); ?>
  <?php hidden("","depistageCancerUterus:date");?>
  <?php hidden("id = 'date_rappel' ","depistageCancerUterus:date_rappel");?>

  <?php hidden("","dossier:numero");?> 
  <?php hidden("","dossier:id"); ?>
  <?php hidden("","dossier:cabinet"); ?>

  <table border='0'><tr><td>
  <?php require("view/common/dossierresume_modif.php");?>
</td><td width='20'>&nbsp;</Td>
<td> Ce formulaire permet à tout instant de collecter des données utiles au protocole dépistage du cancer du col de l'utérus.<br><br>
Il s'appuie sur les données les plus récentes du patient (résultats d'examens)<br><br>
</td></Tr>
</table>

  <b>Date du frottis:<?php text("id='date_frottis' size='10' onkeyup='formate_date(this)'","depistageCancerUterus:date_frottis"); ?></b>
  &nbsp;&nbsp; <?php if($dernierExam!=""){?>Date du dernier frottis saisi : <?php echo $dernierExam->date_frottis;}?>
  <br>
  <br>
  <b>Frottis : </b><br>
  <table border=0>
    <?php
		if($depistageCancerUterus->frottis_normal=='oui') {$desactiv_anormal='disabled';$desactiv_normal="";}
		else {$desactiv_anormal='';$desactiv_normal='disabled';}
    ?>
    <tr>
      <td>Normal</td>
      <td><?php radioButton("id='frottis_normal' onclick='active(this)'","depistageCancerUterus:frottis_normal","oui"); ?></td>
	  	<td>Date Rappel : </td>
		  	<td><input onKeyUp="calc_rappel(this)" id="rappel_normal" name="rappel_normal" type="text" size="10" <?php echo ($depistageCancerUterus->frottis_normal=='oui'?" value = $depistageCancerUterus->date_rappel":"$desactiv_normal") ?> ></td>
    </tr>

    <tr>
      <td valign="top">Anormal</td>
      <td valign="top"><?php radioButton("id='frottis_anormal' onclick='active(this)'","depistageCancerUterus:frottis_normal","non"); ?></td>
	  		<td valign="top">Avis du médecin : </td>
			  	<td> <?php textArea("rows=\"3\" cols=\"30\" id='avis_medecin' $desactiv_anormal","depistageCancerUterus:avis_medecin"); ?></td>
				  	<td valign='top'>Date Rappel : </td>
					  	<td valign='top'><input onKeyUp="calc_rappel(this)" id="rappel_anormal" name="rappel_anormal" type="text" size="10" <?php echo ($depistageCancerUterus->frottis_normal=='non'?" value = $depistageCancerUterus->date_rappel":"$desactiv_anormal") ?> ></td>
    </tr>
  </table>

  <br>
  <table border=0>
    <tr>
      <td width='184'>Sortir cette personne du dépistage cancer du col de l'utérus</td>
	      <td><?php checkBox("","depistageCancerUterus:sortir_rappel","1"); ?></td>
	</tr>
	<tr>
		<td>Raison : </td>
		    <td><?php textArea("rows=\"3\" cols=\"30\" ","depistageCancerUterus:raison_sortie"); ?></td>
    </tr>
  </table>

<br><br>

  <br> 
  <input type='button' value='Enregistrer' onclick='validateInput()'> 
  <input type='reset' value='Recommencer'> 
</form> 
