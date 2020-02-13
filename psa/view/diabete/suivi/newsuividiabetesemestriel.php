<script language="javascript">

	function validDateNSPSelection(dateId,valueIDName,dateLabel,valueLabel){
	
		var valueNum = document.getElementsByName(valueIDName);		
		var date = document.getElementById(dateId).value;
		var valueChecked = false;
		var valueValue = "";

		for(var i = 0;i<valueNum.length;i++){						
			if(valueNum[i].checked == true) {
				valueChecked = true;											
				valueValue = valueNum[i].value;				
				break;
			}			
		}
		
		if(date == ""){
			if(valueValue == "oui"){
				alert("Entrer une valeur pour "+dateLabel);
				return 0;
			}
		}
		else{
		    if (valueValue=="non" || valueValue=="nsp"){
		        alert("n'entrer pas de valeur pour "+dateLabel);
		        return 0;
		    }
			else
			{
				if(valueValue == ""){
					alert("Entrer une valeur pour "+valueLabel);
					return 0;
				}
			}
		}
		
		if(valueValue == "nsp" || valueValue=="non" || valueValue=="") return -1;
			return 1;
	}
	function checkSemestriel(aForm){
		var i;
		var submitOk = 1;
		var sOk;
		<?php
		$js = new JSValidation();		
		?>
		
		ExaPiedsOui=document.getElementById("ExaPiedsOui");
		ExaPiedsNon=document.getElementById("ExaPiedsNon");
		ExaPiedsNsp=document.getElementById("ExaPiedsNsp");

/*		if((ExaPiedsOui.checked==false)&&(ExaPiedsNon.checked==false)&&(ExaPiedsNsp.checked==false)){
		    alert("Précisez s'il y a eu un examen des pieds");
			submitOk=0;
		}
		
		sOk = validDateNSPSelection("dExaPieds","<?php typePropertyName("suiviDiabete:ExaPieds"); ?>","date examen des pieds","examen des pieds");

		if(sOk == 1 ){
*/
		if(document.getElementById("dExaPieds").value!=''){
			<?php $js->dateInRange("pied:date_exam","Date examen des pieds");?>
		}
/*		}else if(sOk == 0) submitOk = 0;
		
		MonoOui=document.getElementById("MonoOui");
		MonoNon=document.getElementById("MonoNon");
		MonoNsp=document.getElementById("MonoNsp");

		if((MonoOui.checked==false)&&(MonoNon.checked==false)&&(MonoNsp.checked==false)){
		    alert("Précisez s'il y a eu un examen au monofilament");
			submitOk=0;
		}

		sOk = validDateNSPSelection("dExaFil","<?php typePropertyName("suiviDiabete:ExaFil"); ?>","date examen au monofilament","examen au monofilament");

		if(sOk == 1 ){	
*/
		if(document.getElementById("dExaFil").value!=''){
			<?php $js->dateInRange("monofil:date_exam","Date examen au monofilament"); ?>
		}
/*		}else if(sOk == 0) submitOk = 0;
*/

		// sOk = validDateValuePair(document.getElementById("dExaPieds").value,document.getElementById("ipied").checked,"Date pied","pied");
		// 	if(sOk ==1){
		// 	<?php
		// 	$js->dateInRange("pied:date_exam","Date Examen des pieds invalide");															
		// 	?>
		// 	} 
		// 	if(sOk == 0) return 0;
		return submitOk;
		}
</script>

<table border='1' width='70%'> 
  <caption>
  <big><b><font color='brown'>Suivi annuel:</font></b></big>
  </caption>
   <tr> 
    <td width="174">&nbsp;</td> 
    <td width="46">date</td> 
    <td width="180">cocher si pathologique</td> 
	<td width="100">Valeur précédente</td>
  </tr> 
  <tr>
  	<td>Examen des pieds<br> 
  		<font size='-1'><i>(aspect cutané et pouls)</i></font></td>   
  		<td><?php
  		if($dernier_suivi->isOutdatedPied(0)){
  			$color='style="background:orange"';
  		}
  		else{
  			$color="";
  		}
  		text("id='dExaPieds' $color size='10' onkeyup='formate_date(this)'","pied:date_exam"); ?></td>
  		<td><?php checkBox("id='ipied' $color","pied:resultat1","1"); ?></td>
  		<td> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=pied&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
  	</tr>
  <tr> 
  <tr>
  	<td>Examen au monofilament</td>   
  		<td><?php
  		if($dernier_suivi->isOutdatedMonofil(0)){
  			$color='style="background:orange"';
  		}
  		else{
  			$color="";
  		}
  		text("id='dExaFil' $color size='10' onkeyup='formate_date(this)'","monofil:date_exam"); ?></td>
  		<td><?php checkBox("id='imonofil' $color","monofil:resultat1","1"); ?></td>
  		<td> <?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=monofil&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
  	</tr>

</table>
