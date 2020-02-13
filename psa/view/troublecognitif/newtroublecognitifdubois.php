<script language="javascript">

	function total_dubois(){
	  var total=0;
	  
	  var immediatsi = document.getElementById("dubois_immediatsi").value;
	  var immediatai = document.getElementById("dubois_immediatai").value;
	  var diffsi = document.getElementById("dubois_diffsi").value;
	  var diffai = document.getElementById("dubois_diffai").value;
	  
	  total=immediatsi*1+immediatai*1+diffsi*1+diffai*1;
	  
	  document.getElementById("total_dubois").innerHTML=total;
	}

	function checkDubois(aForm){
		var i;
		var submitOk = 1;
		<?php
		$js = new JSValidation();		
		?>

	var dubois_immediatsi = document.getElementById("dubois_immediatsi");
	var dubois_immediatai = document.getElementById("dubois_immediatai");
	var dubois_diffsi = document.getElementById("dubois_diffsi");
	var dubois_diffai = document.getElementById("dubois_diffai");

	if((dubois_immediatsi=="")||(dubois_immediatai=="")||(dubois_diffsi=="")||(dubois_diffai=="")){
	    alert("Veuillez saisir tous les nombres de réponses correctes pour les 5 mots de Dubois");
	    submitOk=0;
	}

		return submitOk;
		}
</script>

<table border='0'  width='100%'>
<tr>
<td align='center'>
  <caption>
  <big><b><font color='blue'>5 mots de Dubois</font></b></big>
  </caption>
  </td>
</tr>
</table>

<table border='1' >
  <tr>
	<td colspan='2'><b>ETAPE RAPPEL IMMEDIAT :</b></td>
  </Tr>
  <tr>
    <td>Nombre de mots trouvés sans indice : </td>
    <td><?php text("id='dubois_immediatsi' size='5' onkeyup='total_dubois()' ","TroubleCognitif:dubois_immediatsi"); ?></td>
  </tr>
  <tr>
    <td>Nombre de mots trouvés avec indice : </td>
    <td><?php text("id='dubois_immediatai' size='5' onkeyup='total_dubois()' ","TroubleCognitif:dubois_immediatai"); ?></td>
  </tr>
  <tr>
	<td colspan='2'><b>ETAPE RAPPEL DIFFERE :</b></td>
  </Tr>
  <tr>
    <td>Nombre de mots trouvés sans indice : </td>
    <td><?php text("id='dubois_diffsi' size='5' onkeyup='total_dubois()' ","TroubleCognitif:dubois_diffsi"); ?></td>
  </tr>
  <tr>
    <td>Nombre de mots trouvés avec indice : </td>
    <td><?php text("id='dubois_diffai' size='5' onkeyup='total_dubois()' ","TroubleCognitif:dubois_diffai"); ?></td>
  </tr>
  <tr><td>Total</td>
	  <td id='total_dubois'></td>
  </tr>
</table>

<br><br>
