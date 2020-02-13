<script language="javascript">

	function calc_score_iadl(){
	    var score_iadl;
		var tel_seul=document.getElementById("tel_seul");
		var transport_tout=document.getElementById("transport_tout");
		var med_tout=document.getElementById("med_tout");
		var budget_tout=document.getElementById("budget_tout");

   score_iadl=4;
  if(tel_seul.checked==true)
  {
      score_iadl--;
  }
  if(transport_tout.checked==true)
  {
      score_iadl--;
  }
  if(med_tout.checked==true){

      score_iadl--;
  }
  if(budget_tout.checked==true)
  {
      score_iadl--;
  }

	    document.getElementById("score_iadl").innerHTML = score_iadl;
	}

	function checkIadl(aForm){
		var i;
		var submitOk = 1;
		<?php
		$js = new JSValidation();		
		?>

		var tel_seul=document.getElementById("tel_seul");
		var tel_qq_no=document.getElementById("tel_qq_no");
		var tel_repond=document.getElementById("tel_repond");
		var tel_rien=document.getElementById("tel_rien");
		var transport_tout=document.getElementById("transport_tout");
		var transport_taxi=document.getElementById("transport_taxi");
		var commun_acc=document.getElementById("commun_acc");
		var voiture_acc=document.getElementById("voiture_acc");
		var voiture_rien=document.getElementById("voiture_rien");
		var med_tout=document.getElementById("med_tout");
		var med_prend=document.getElementById("med_prend");
		var med_rien=document.getElementById("med_rien");
		var budget_tout=document.getElementById("budget_tout");
		var budget_jour=document.getElementById("budget_jour");
		var budget_rien=document.getElementById("budget_rien");

	if((tel_seul.checked == false)&& (tel_qq_no.checked == false)&&(tel_repond.checked == false)&&
				(tel_rien.checked == false)){
				    alert("Indiquez l'autonomie pour utiliser le téléphone");
				    submitOk=0;
	}
	
	if((transport_tout.checked==false)&&(transport_taxi.checked==false)&&(commun_acc.checked==false)&&
	  (voiture_acc.checked==false)&& (voiture_rien.checked==false)){
	  	alert("Indiquez l'autonomie dans les transports");
	    submitOk=0;
	}
	
	if((med_tout.checked==false)&&(med_prend.checked==false)&&(med_rien.checked==false)){
	    alert("Indiquez l'autonomie pour prendre les médicaments");
	    submitOk=0;
	}
	
	if((budget_tout.checked==false)&&(budget_jour.checked==false)&&(budget_rien.checked==false)){
	    alert("Indiquez l'autonomie pour gérer le budget");
	    submitOk=0;
	}
		return submitOk;

	}
</script>

<table border='0'  width='100%'>
<tr>
<td width="100%">
  <caption> 
  <big><b><font color='#FF00FF'><b>Test IADL - INSTRUMENTAL ACTIVITIES OF DAILY LIVING</b></font></b></big>
  </caption>
  </Td>
  </tr>
  <tr>
  <td width="100%">
   <b>Capacité à utiliser le téléphone:</b><br>
   <table border='1'  width='100%'>
  <tr> 
    <td width='98%'>Je me sers du téléphone de ma propre initiative, cherche et compose les numéros</td>
    <td> <?php radioButton("id='tel_seul' onclick='calc_score_iadl()'","TroubleCognitif:iadl_telephone","tout");  ?> </td>
  </tr> 
  <tr>
    <td>Je compose un petit nombre de numéros bien connus</td>
    <td> <?php radioButton("id='tel_qq_no' onclick='calc_score_iadl()'","TroubleCognitif:iadl_telephone","qq_no");  ?> </td>
  </tr>
  <tr>
    <td>Je réponds au téléphone mais n'appelle pas</td>
    <td> <?php radioButton("id='tel_repond' onclick='calc_score_iadl()'","TroubleCognitif:iadl_telephone","repond");  ?> </td>
  </tr>
  <tr>
    <td>Je suis incapable d'utiliser le téléphone</td>
    <td> <?php radioButton("id='tel_rien' onclick='calc_score_iadl()'","TroubleCognitif:iadl_telephone","rien");  ?> </td>
  </tr>
  </table>
   <b>Moyen de transport</b><br>

   <table border='1'  width='100%'>
  <tr>
    <td width='98%'>Je peux voyager seul(e) de façon indépendante (par les transports en commun ou avec ma propre voiture)</td>
    <td> <?php radioButton("id='transport_tout' onclick='calc_score_iadl()'","TroubleCognitif:iadl_transport","tout");  ?> </td>
  </tr>
  <tr>
    <td>Je peux voyager seul(e) en taxi, pas en autobus</td>
    <td> <?php radioButton("id='transport_taxi' onclick='calc_score_iadl()'","TroubleCognitif:iadl_transport","taxi_seul");  ?> </td>
  </tr>
  <tr>
    <td>Je peux prendre les transports en commun si je suis accompagné(e)</td>
    <td> <?php radioButton("id='commun_acc' onclick='calc_score_iadl()'","TroubleCognitif:iadl_transport","commun_acc");  ?> </td>
  </tr>
  <tr>
    <td>Transport limité au taxi ou à la voiture en étant accompagné(e)</td>
    <td> <?php radioButton("id='voiture_acc' onclick='calc_score_iadl()'","TroubleCognitif:iadl_transport","voiture_acc");  ?> </td>
  </tr>
  <tr>
    <td>Je ne me deplace pas du tout</td>
    <td> <?php radioButton("id='voiture_rien' onclick='calc_score_iadl()'","TroubleCognitif:iadl_transport","rien");  ?> </td>
  </tr>
  </table>
  
   <b>Responsabilité pour la prise des médicaments</b><br>

   <table border='1'  width='100%'>
  <tr>
    <td width='98%'>Je m'occupe moi-même de la prise : dosage et horaire</td>
    <td> <?php radioButton("id='med_tout' onclick='calc_score_iadl()'","TroubleCognitif:iadl_med","tout");  ?> </td>
  </tr>
  <tr>
    <td>Je peux les prendre moi-même s'ils sont préparés et dosés</td>
    <td> <?php radioButton("id='med_prend' onclick='calc_score_iadl()'","TroubleCognitif:iadl_med","prend_seul");  ?> </td>
  </tr>
  <tr>
    <td>Je suis incapable de les prendre moi-même</td>
    <td> <?php radioButton("id='med_rien' onclick='calc_score_iadl()'","TroubleCognitif:iadl_med","rien");  ?> </td>
  </tr>
  </table>
     <b>Capacité à gérer son budget</b><br>

   <table border='1'  width='100%'>
  <tr>
    <td width='98%'>Je suis totalement autonome (gérer le budget, faire des chèques, payer des factures)</td>
    <td> <?php radioButton("id='budget_tout' onclick='calc_score_iadl()'","TroubleCognitif:iadl_budget","tout");  ?> </td>
  </tr>
  <tr>
    <td>Je me débrouille pour les dépenses au jour le jour, mais j'ai besoin d'aide pour gérer mon budget à long terme (pour planifier les grosses dépenses)</td>
    <td> <?php radioButton("id='budget_jour' onclick='calc_score_iadl()'","TroubleCognitif:iadl_budget","jour");  ?> </td>
  </tr>
  <tr>
    <td>Je suis incapable de gérer l'argent nécessaire à payer mes dépenses au jour le jour</td>
    <td> <?php radioButton("id='budget_rien' onclick='calc_score_iadl()'","TroubleCognitif:iadl_budget","rien");  ?> </td>
  </tr>

  <tr>
    <td width='98%'>Score : </td>
    <td id='score_iadl'><?php echo $TroubleCognitif->get_iadl();?></td>
  </tr>
</td>
</table>
</table>

<br><br>
