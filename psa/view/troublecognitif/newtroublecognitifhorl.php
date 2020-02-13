<script language="javascript">

	function calc_score(nombre){
	    document.getElementById("score_horl").innerHTML = nombre;
	}

	function checkHorl(aForm){
		var i;
		var submitOk = 1;
		<?php
		$js = new JSValidation();		
		?>

	var dix = document.getElementById("dix");
	var neuf = document.getElementById("neuf");
	var huit = document.getElementById("huit");
	var sept = document.getElementById("sept");
	var six = document.getElementById("six");
	var cinq = document.getElementById("cinq");
	var quatre = document.getElementById("quatre");
	var trois = document.getElementById("trois");
	var deux = document.getElementById("deux");
	var un = document.getElementById("un");

	if((dix.checked == false)&&(neuf.checked == false)&&(huit.checked == false)&&
	  (sept.checked == false)&& (six.checked == false)&&(cinq.checked == false)&&
	  (quatre.checked == false)&&(trois.checked == false)&&(deux.checked == false)&&
	  (un.checked == false)){
	    	alert("Donnez une réponse au test de l'horloge");
			submitOk = 0;
	}

		return submitOk;
	}
</script>

<table border='1'  width='100%'>
  <caption> 
  <big><b><font color='brown'>Test de l'horloge</font></b></big>
  </caption> 
  <tr> 
    <td width='98%'>Aiguilles correctement positionnées</td>
    <td> <?php radioButton("id='dix' onclick='calc_score(10)'","TroubleCognitif:horloge","10");  ?> </td>
  </tr> 
  <tr>
    <td>Erreurs minimes</td>
    <td> <?php radioButton("id='neuf' onclick='calc_score(9)'","TroubleCognitif:horloge","9");  ?> </td>
  </tr>
  <tr>
    <td>Erreurs importantes</td>
    <td> <?php radioButton("id='huit' onclick='calc_score(8)'","TroubleCognitif:horloge","8");  ?> </td>
  </tr>
  <tr>
    <td>Confusion des aiguilles</td>
    <td> <?php radioButton("id='sept' onclick='calc_score(7)'","TroubleCognitif:horloge","7");  ?> </td>
  </tr>
  <tr>
    <td>Mauvaise position des aiguilles</td>
    <td> <?php radioButton("id='six' onclick='calc_score(6)'","TroubleCognitif:horloge","6");  ?> </td>
  </tr>
  <tr>
    <td>Chiffres intervertis</td>
    <td> <?php radioButton("id='cinq' onclick='calc_score(5)'","TroubleCognitif:horloge","5");  ?> </td>
  </tr>
  <tr>
    <td>Oubli des chiffres</td>
    <td> <?php radioButton("id='quatre' onclick='calc_score(4)'","TroubleCognitif:horloge","4");  ?> </td>
  </tr>
  <tr>
    <td>Chiffres à l'extérieur du cadran</td>
    <td> <?php radioButton("id='trois' onclick='calc_score(3)'","TroubleCognitif:horloge","3");  ?> </td>
  </tr>
  <tr>
    <td>Horloge vaguement reconnaissable</td>
    <td> <?php radioButton("id='deux' onclick='calc_score(2)'","TroubleCognitif:horloge","2");  ?> </td>
  </tr>
  <tr>
    <td>Absence d'essai ou essai non interprétable</td>
    <td> <?php radioButton("id='un' onclick='calc_score(1)'","TroubleCognitif:horloge","1");  ?> </td>
  </tr>
  <tr>
    <td>Score : </td>
    <td id='score_horl'><?php echo $TroubleCognitif->horloge;?></td>
  </tr>

</table>

<br><br>
