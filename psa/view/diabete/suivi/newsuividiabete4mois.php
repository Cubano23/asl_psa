<script language="javascript">

	function check4Mois(aForm){
		var i;
		var submitOk = 1;
		<?php
		$js = new JSValidation();		
		?>
		submitOk = validDateValuePair(document.getElementById("dNBA").value,document.getElementById("ResNBA").value,"Date HBA1C","HBA1C");
		if(submitOk == 1){
		<?php
			$js->dateInRange("HBA1c:date_exam","Date HBA1C");
			$js->validateNumeric("HBA1c:resultat1","HBA1C");
		?>
		}
		if(submitOk == -1) submitOk = 1;		
		return submitOk;
		}

function verifie_hba_value(){
	var ResNBA
	ResNBA = document.getElementById("ResNBA").value;
	
	if(ResNBA < 2){
		alert('la valeur HBA1c est incorrecte ( '+ResNBA+' ) vérifiez votre saisie');
		document.getElementById("ResNBA").value='';
	}
	if(ResNBA > 18){
		alert('la valeur HBA1c est incorrecte ( '+ResNBA+' ) vérifiez votre saisie');
		document.getElementById("ResNBA").value='';
	}
	
}

function verifie_hba(){
	var ResNBA, OBJ_equilib;
	
	ResNBA = document.getElementById("ResNBA").value;
	OBJ_equilib = document.getElementById("OBJ_equilib");
	
	ResNBA=remplacevirgule(ResNBA);
	document.getElementById("ResNBA").value=ResNBA;

	if(ResNBA<=6.5)
	{
	    OBJ_equilib.checked=true;
	}
	else
	{
	    OBJ_equilib.checked=false;
	}


}

</script>

<table border='1'  width='70%'> 
  <caption> 
  <big><b><font color='blue'>Suivi tous les 4 mois:</font></b></big> 
  </caption> 
  <tr> 
    <td>HBA1C</td> 
    <td> <?php
		if($dernier_suivi->isOutdated4(0)){
		    $color='style="background:orange"';
		}
		else{
		    $color="";
		}
		
	text("id='ResNBA' size='3' $color onkeyup='verifie_hba()' onchange='verifie_hba_value()'","HBA1c:resultat1"); ?> %</td>
    <td> le <?php text("id='dNBA' $color size='10' onkeyup='formate_date(this)'","HBA1c:date_exam"); ?></td>
	<td> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=HBA1c&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
  </tr> 
</table>
