

<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $Hemocult; ?>
<?php global $dernierExam; ?>

<?php
/*if(($Hemocult->date_convoc=="")&&($Hemocult->date_plaquette=="")&&($Hemocult->date_resultat=="")&&($Hemocult->resultat=="")){
	$Hemocult->date_convoc=$dernierExam->date_convoc;
	$Hemocult->date_plaquette=$dernierExam->date_plaquette;
	$Hemocult->date_resultat=$dernierExam->date_resultat;
	$Hemocult->resultat=$dernierExam->resultat;
}*/
?>

<script type="text/javascript" >




<?php
	validateDate();
	dateInRange();
	compareDates();
	$js = new JSValidation();
	$js->startCheckFunction("validateInput","saveForm");
?>
	// var  date_convoc = document.getElementById("date_convoc");
/*
	if(date_convoc.value!=""){
	    <?php
		$js->dateInRange("Hemocult:date_convoc","Date de convocation");
		?>
	}
	var  date_plaquette = document.getElementById("date_plaquette");

	if(date_plaquette.value!=""){<?php
		$js->dateInRange("Hemocult:date_plaquette","Date de remise des plaquettes");
		?>
	}
	*/
	var date_resultat = document.getElementById("date_resultat");
	var resultatpositif = document.getElementById("resultatpositif");
	var resultatnegatif = document.getElementById("resultatnegatif");
	
	if((date_resultat.value!='')||(resultatpositif.checked==true)||(resultatnegatif.checked==true)){<?php
		$js->dateInRange("Hemocult:date_resultat","Date de résultat");
		?>
		if((resultatpositif.checked==false)&&(resultatnegatif.checked==false)){
		    alert("Veuillez indiquer si le résultat est positif ou négatif");
		    submitOk=0;
		}
	}

	var  date_rappel = document.getElementById("date_rappel");

	if(date_rappel.value!=""){<?php
		$js->dateInRange("Hemocult:date_rappel","Date de rappel");
		?>
	}


			
	<?php	
	$js->dateInRange("dossier:dnaiss","Date de naissance");	
	$js->endCheckFunction();	


?>

function rappel()
{
	formate_date(document.getElementById("date_resultat"));
	var date_resultat = document.getElementById("date_resultat");
	var date_rappel;
	
	var negatif = document.getElementById("resultatnegatif");
	var positif = document.getElementById("resultatpositif");
	
	if(negatif.checked==true){

		if((date_resultat.value.length)==10)
		{
			tab_result=date_resultat.value.split('/');
			test=new Date(tab_result[2], tab_result[1], tab_result[0]);
			if (tab_result[1]==12)
				annee=test.getFullYear()+1;
			else
				annee=test.getFullYear()+2;
			date_rappel=tab_result[0]+'/'+tab_result[1]+'/'+annee;

			document.getElementById("date_rappel").value=date_rappel;

		}
	}
	
	if(positif.checked==true){
		document.getElementById("date_rappel").value="";
	}
}

function efface_rappel(){
	rappel0=document.getElementById("rappel0");

	if(rappel0.checked==true){
		document.getElementById("date_rappel").value="";
	}
	else{
		rappel();
	}
}
</script>

<br> 
<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'> 
  <?php hiddenControler("HemocultControler"); ?>
  <?php hiddenAction(ACTION_SAVE); ?>
  <?php hidden("","Hemocult:date");?>
  <?php hidden("","dossier:numero");?> 
  <?php hidden("","dossier:id"); ?>
  <?php hidden("","dossier:cabinet"); ?>

  <table border='0'><tr><td>
  <?php require("view/common/dossierresume_modif.php");?>
	</td><td width='20'>&nbsp;</td><td>
  <table border=0>
    <tr>
        <td>Date de résultat: </TD>
			<TD><?php text("id='date_resultat' size='10' onKeyUp ='rappel()'","Hemocult:date_resultat"); ?></td>
			<td>Dernier examen : <?php echo $dernierExam->date_resultat;?></td>
	</tr>
    <tr>
        <td>Résultat: </TD>
			<TD colspan='2'><?php radioButton("id='resultatnegatif' onclick='rappel()'","Hemocult:resultat","0"); ?>Négatif &nbsp;&nbsp;&nbsp;
				<?php radioButton("id='resultatpositif' onclick='rappel()'","Hemocult:resultat","1"); ?>Positif </td>
	</tr>
    <tr>
        <td>Rappel: </TD>
			<TD colspan='2'><?php text("id='date_rappel' size='10' onkeyup='formate_date(this)'","Hemocult:date_rappel"); ?>&nbsp;&nbsp;<?php checkbox("id='rappel0' onclick='efface_rappel()'","Hemocult:rappel","0"); ?>Pas de rappel </td>
	</tr>

  </table>

  <br>
  <table border=0>
    <tr>
      <td width='184'>Sortir cette personne du test d'hémocult</td>
	      <td><?php checkBox("","Hemocult:sortir_rappel","1"); ?></td>
	</tr>
	<tr>
		<td>Raison : </td>
		    <td><?php textArea("rows=\"3\" cols=\"30\" ","Hemocult:raison_sortie"); ?></td>
    </tr>
  </table>
</td><td width='20'>&nbsp;</td>
<td>
<br><br>

  <br> 
  <input type='button' value='Enregistrer' onclick='validateInput()'> 
  <input type='reset' value='Recommencer'> 
  </td></tr></table>
</form> 
