<script language="javascript">

	function checkSystematique(aForm){
		var i;
		var submitOk = 1;
		<?php
//		$js = new JSValidation();

//	validateDate();
//	dateInRange();
//	compareDates();
	$js = new JSValidation();
	
?>

	var indiv = document.getElementById("indiv");
	var coll = document.getElementById("coll");
	var date_rappel = document.getElementById("date_rappel");

	if(coll.checked==true)
	{
<?php
		$js->dateInRange("TroubleCognitif:date_rappel","Date de rappel");
?>
	}
	if(indiv.checked==true)
	{

	    if(date_rappel.value!="")
	    {
<?php
			$js->dateInRange("TroubleCognitif:date_rappel","Date de rappel");
?>
	    }
	}


	if(indiv.checked == false){
		if(coll.checked == false){
			alert("indiquez le type de dépistage");
			submitOk = 0;
		}
	}


		return submitOk;
	}

function active(object){

	var raison_dep = document.getElementById("raison_dep");
	var rappel_indiv = document.getElementById("rappel_indiv");
	var rappel_coll = document.getElementById("rappel_coll");

	if(object.value == 'coll'){

		rappel_indiv.value='';
		raison_dep.value='';
		rappel_indiv.disabled=true;
		raison_dep.disabled = true;


			var date_dep = document.getElementById("date_dep");
			var date_rappel;

				tab_dep=date_dep.value.split('/');
				test=new Date(tab_dep[2], tab_dep[1], tab_dep[0]);
				if (tab_dep[1]==12)
				    annee=test.getFullYear();
				else
					annee=test.getFullYear()+1;
				date_rappel=tab_dep[0]+'/'+tab_dep[1]+'/'+annee;

			 	document.getElementById("date_rappel").value=date_rappel;
			 	rappel_coll.value=date_rappel;
			 	rappel_coll.disabled=false;

//		alert(rappel_anormal.disabled);
		return;
	}

		 	document.getElementById("date_rappel").value="";
			rappel_indiv.disabled = false;
			raison_dep.disabled=false;
			rappel_coll.value="";
			rappel_coll.disabled=true;


}


function calcul_rappel(object)
{
	formate_date(object);
	document.getElementById("date_rappel").value=object.value;
}


</script>

  <?php hidden("id = 'date_rappel' ","TroubleCognitif:date_rappel");?>

    <?php

		if($TroubleCognitif->dep_type=='indiv') {$desactiv_indiv="";$desactiv_coll='disabled';}
		elseif($TroubleCognitif->dep_type=='coll') {$desactiv_indiv='disabled';$desactiv_coll='';}
		else {$desactiv_indiv='disabled';$desactiv_coll='disabled';}

    ?>

  <b>Type de dépistage:</b><br>
  <table border=0>
    <tr>
      <td>Dépistage collectif?</td>
      <td><?php radioButton("id='coll' onclick='active(this)'","TroubleCognitif:dep_type","coll"); ?></td>
      	<td>Date Rappel : </td><td valign='top'><input onKeyUp="calcul_rappel(this)" id="rappel_coll" name="rappel_coll" type="text" size="10" <?php echo ($TroubleCognitif->dep_type=='coll'?" value = $TroubleCognitif->date_rappel":"$desactiv_coll") ?> ></td>
    </tr>
    <tr>
      <td valign='top'>Dépistage individuel?</td>
      <td valign='top'><?php radioButton("id='indiv' onclick='active(this)'","TroubleCognitif:dep_type","indiv"); ?></td>
	  		<td valign="top">Raison du dépistage : </td><td> <?php textArea("rows=\"3\" cols=\"30\" id='raison_dep' $desactiv_indiv","TroubleCognitif:raison_dep"); ?></td><td valign='top'>Date Rappel : </td><td valign='top'><input onKeyUp="calcul_rappel(this)" id="rappel_indiv" name="rappel_indiv" type="text" size="10" <?php echo ($TroubleCognitif->dep_type=='indiv'?" value = $TroubleCognitif->date_rappel":"$desactiv_indiv"); ?> >
    </tr>
    <tr>
      <td width='130'>Sortir cette personne du dépistage troubles cognitifs</td>
	      <td><?php checkBox("","TroubleCognitif:sortir_rappel","1"); ?></td>
	</tr>
	<tr>
		<td>Raison : </td>
		    <td colspan='3'><?php textArea("rows=\"3\" cols=\"30\" ","TroubleCognitif:raison_sortie"); ?></td>
    </tr>
  </table>


