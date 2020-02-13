<script language="javascript">

	function calc_score_gds(){
	    var score=0;
		var gds_pas_satisf=document.getElementById("gds_pas_satisf");
		var gds_renonce_act=document.getElementById("gds_renonce_act");
		var gds_vie_vide=document.getElementById("gds_vie_vide");
		var gds_ennui=document.getElementById("gds_ennui");
		var gds_pas_avenir_opt=document.getElementById("gds_pas_avenir_opt");
		var gds_cata=document.getElementById("gds_cata");
		var gds_pas_bonne_humeur=document.getElementById("gds_pas_bonne_humeur");
		var gds_besoin_aide=document.getElementById("gds_besoin_aide");
		var gds_prefere_seul=document.getElementById("gds_prefere_seul");
		var gds_mauvaise_mem=document.getElementById("gds_mauvaise_mem");
		var gds_pas_heureux_vivre=document.getElementById("gds_pas_heureux_vivre");
		var gds_bon_rien=document.getElementById("gds_bon_rien");
		var gds_pas_energie=document.getElementById("gds_pas_energie");
		var gds_desespere_sit=document.getElementById("gds_desespere_sit");
		var gds_sit_autres_best=document.getElementById("gds_sit_autres_best");

		if(gds_pas_satisf.checked==true)
		{
		    score++;
		}

		if(gds_renonce_act.checked==true)
		{
		    score++;
		}

		if(gds_vie_vide.checked==true)
		{
		    score++;
		}

		if(gds_ennui.checked==true)
		{
		    score++;
		}

		if(gds_pas_avenir_opt.checked==true)
		{
		    score++;
		}

		if(gds_cata.checked==true)
		{
		    score++;
		}

		if(gds_pas_bonne_humeur.checked==true)
		{
		    score++;
		}

		if(gds_besoin_aide.checked==true)
		{
		    score++;
		}

		if(gds_prefere_seul.checked==true)
		{
		    score++;
		}

		if(gds_mauvaise_mem.checked==true)
		{
		    score++;
		}

		if(gds_pas_heureux_vivre.checked==true)
		{
		    score++;
		}

		if(gds_bon_rien.checked==true)
		{
		    score++;
		}

		if(gds_pas_energie.checked==true)
		{
		    score++;
		}

		if(gds_desespere_sit.checked==true)
		{
		    score++;
		}

		if(gds_sit_autres_best.checked==true)
		{
		    score++;
		}


	    document.getElementById("score_gds").innerHTML = score;
	}

	function checkGds(aForm){
		var i;
		var submitOk = 1;
		<?php
		$js = new JSValidation();
		?>

	    var gds_satisf=document.getElementById("gds_satisf");
		var gds_pas_satisf=document.getElementById("gds_pas_satisf");
		var gds_renonce_act=document.getElementById("gds_renonce_act");
		var gds_pas_renonce_act=document.getElementById("gds_pas_renonce_act");
		var gds_vie_vide=document.getElementById("gds_vie_vide");
		var gds_pas_vie_vide=document.getElementById("gds_pas_vie_vide");
		var gds_ennui=document.getElementById("gds_ennui");
		var gds_pas_ennui=document.getElementById("gds_pas_ennui");
		var gds_avenir_opt=document.getElementById("gds_avenir_opt");
		var gds_pas_avenir_opt=document.getElementById("gds_pas_avenir_opt");
		var gds_cata=document.getElementById("gds_cata");
		var gds_pas_cata=document.getElementById("gds_pas_cata");
		var gds_bonne_humeur=document.getElementById("gds_bonne_humeur");
		var gds_pas_bonne_humeur=document.getElementById("gds_pas_bonne_humeur");
		var gds_besoin_aide=document.getElementById("gds_besoin_aide");
		var gds_pas_besoin_aide=document.getElementById("gds_pas_besoin_aide");
		var gds_prefere_seul=document.getElementById("gds_prefere_seul");
		var gds_pas_prefere_seul=document.getElementById("gds_pas_prefere_seul");
		var gds_mauvaise_mem=document.getElementById("gds_mauvaise_mem");
		var gds_pas_mauvaise_mem=document.getElementById("gds_pas_mauvaise_mem");
		var gds_heureux_vivre=document.getElementById("gds_heureux_vivre");
		var gds_pas_heureux_vivre=document.getElementById("gds_pas_heureux_vivre");
		var gds_bon_rien=document.getElementById("gds_bon_rien");
		var gds_pas_bon_rien=document.getElementById("gds_pas_bon_rien");
		var gds_energie=document.getElementById("gds_energie");
		var gds_pas_energie=document.getElementById("gds_pas_energie");
		var gds_desespere_sit=document.getElementById("gds_desespere_sit");
		var gds_pas_desespere_sit=document.getElementById("gds_pas_desespere_sit");
		var gds_sit_autres_best=document.getElementById("gds_sit_autres_best");
		var gds_pas_sit_autres_best=document.getElementById("gds_pas_sit_autres_best");

	    if((gds_satisf.checked==false)&&(gds_pas_satisf.checked==false))
		{
		    alert("répondez à la question 1 du test GDS");
		    submitOk=0;
		}

		if ((gds_renonce_act.checked==false)&&(gds_pas_renonce_act.checked==false))
		{
		    alert("répondez à la question 2 du test GDS");
		    submitOk=0;
		}

		if((gds_vie_vide.checked==false)&&(gds_pas_vie_vide.checked==false))
		{
		    alert("répondez à la question 3 du test GDS");
		    submitOk=0;
		}

		if((gds_ennui.checked==false)&&(gds_pas_ennui.checked==false))
		{
		    alert("répondez à la question 4 du test GDS");
		    submitOk=0;
		}

		if((gds_avenir_opt.checked==false)&&(gds_pas_avenir_opt.checked==false))
		{
		    alert("répondez à la question 5 du test GDS");
		    submitOk=0;
		}

		if((gds_cata.checked==false)&&(gds_pas_cata.checked==false))
		{
		    alert("répondez à la question 6 du test GDS");
		    submitOk=0;
		}

		if((gds_bonne_humeur.checked==false)&&(gds_pas_bonne_humeur.checked==false))
		{
		    alert("répondez à la question 7 du test GDS");
		    submitOk=0;
		}

		if((gds_besoin_aide.checked==false)&&(gds_pas_besoin_aide.checked==false))
		{
		    alert("répondez à la question 8 du test GDS");
		    submitOk=0;
		}

		if((gds_prefere_seul.checked==false)&&(gds_pas_prefere_seul.checked==false))
		{
		    alert("répondez à la question 9 du test GDS");
		    submitOk=0;
		}

		if((gds_mauvaise_mem.checked==false)&&(gds_pas_mauvaise_mem.checked==false))
		{
		    alert("répondez à la question 10 du test GDS");
		    submitOk=0;
		}

		if((gds_heureux_vivre.checked==false)&&(gds_pas_heureux_vivre.checked==false))
		{
		    alert("répondez à la question 11 du test GDS");
		    submitOk=0;
		}

		if((gds_bon_rien.checked==false)&&(gds_pas_bon_rien.checked==false))
		{
		    alert("répondez à la question 12 du test GDS");
		    submitOk=0;
		}

		if((gds_energie.checked==false)&&(gds_pas_energie.checked==false))
		{
		    alert("répondez à la question 13 du test GDS");
		    submitOk=0;
		}

		if((gds_desespere_sit.checked==false)&&(gds_pas_desespere_sit.checked==false))
		{
		    alert("répondez à la question 14 du test GDS");
		    submitOk=0;
		}

		if((gds_sit_autres_best.checked==false)&&(gds_pas_sit_autres_best.checked==false))
		{
		    alert("répondez à la question 15 du test GDS");
		    submitOk=0;
		}

		return submitOk;
		}
</script>

<table border='1'  width='100%'>
  <caption> 
  <big><b><font color='green'>GDS</font></b></big>
  </caption> 
  <tr>
  <td>Question
  </td>
    <td> oui </td>
        <td> non </td>
  </tr>
  <tr> 
    <td>Etes-vous satisfait(e) de votre vie</td>
    <td> <?php radioButton("id='gds_satisf' onclick='calc_score_gds()'","TroubleCognitif:gds_satisf","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_satisf' onclick='calc_score_gds()'","TroubleCognitif:gds_satisf","non");  ?> </td>
  </tr>
  <tr>
    <td>Avez-vous renoncé à un grand nombre d'activités?</td>
    <td> <?php radioButton("id='gds_renonce_act' onclick='calc_score_gds()'","TroubleCognitif:gds_renonce_act","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_renonce_act' onclick='calc_score_gds()'","TroubleCognitif:gds_renonce_act","non");  ?> </td>
  </tr>
  <tr>
    <td>Avez-vous le sentiment que votre vie soit vide?</td>
    <td> <?php radioButton("id='gds_vie_vide' onclick='calc_score_gds()'","TroubleCognitif:gds_vie_vide","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_vie_vide' onclick='calc_score_gds()'","TroubleCognitif:gds_vie_vide","non");  ?> </td>
  </tr>
  <tr>
    <td>Vous ennuyez-vous souvent?</td>
    <td> <?php radioButton("id='gds_ennui' onclick='calc_score_gds()'","TroubleCognitif:gds_ennui","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_ennui' onclick='calc_score_gds()'","TroubleCognitif:gds_ennui","non");  ?> </td>
  </tr>
  <tr>
    <td>Envisagez-vous l'avenir avec optimisme?</td>
    <td> <?php radioButton("id='gds_avenir_opt' onclick='calc_score_gds()'","TroubleCognitif:gds_avenir_opt","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_avenir_opt' onclick='calc_score_gds()'","TroubleCognitif:gds_avenir_opt","non");  ?> </td>
  </tr>
  <tr>
    <td>Craignez-vous une catastrophe pour l'avenir </td>
    <td> <?php radioButton("id='gds_cata' onclick='calc_score_gds()'","TroubleCognitif:gds_cata","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_cata' onclick='calc_score_gds()'","TroubleCognitif:gds_cata","non");  ?> </td>
  </tr>
  <tr>
    <td>Etes-vous de bonne humeur la plupart du temps </td>
    <td> <?php radioButton("id='gds_bonne_humeur' onclick='calc_score_gds()'","TroubleCognitif:gds_bonne_humeur","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_bonne_humeur' onclick='calc_score_gds()'","TroubleCognitif:gds_bonne_humeur","non");  ?> </td>
  </tr>
  <tr>
    <td>Avez-vous besoin d'aide dans vos activités ?</td>
    <td> <?php radioButton("id='gds_besoin_aide' onclick='calc_score_gds()'","TroubleCognitif:gds_besoin_aide","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_besoin_aide' onclick='calc_score_gds()'","TroubleCognitif:gds_besoin_aide","non");  ?> </td>
  </tr>
  <tr>
    <td>Préférez-vous rester seul (e) dans votre chambre (ou à la maison) plutôt que d'en sortir ?</td>
    <td> <?php radioButton("id='gds_prefere_seul' onclick='calc_score_gds()'","TroubleCognitif:gds_prefere_seul","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_prefere_seul' onclick='calc_score_gds()'","TroubleCognitif:gds_prefere_seul","non");  ?> </td>
  </tr>
  <tr>
    <td>Pensez-vous que votre mémoire est moins bonne que celle de la plupart des gens ? </td>
    <td> <?php radioButton("id='gds_mauvaise_mem' onclick='calc_score_gds()'","TroubleCognitif:gds_mauvaise_mem","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_mauvaise_mem' onclick='calc_score_gds()'","TroubleCognitif:gds_mauvaise_mem","non");  ?> </td>
  </tr>
  <tr>
    <td>Etes-vous heureux (se) de vivre actuellement ? </td>
    <td> <?php radioButton("id='gds_heureux_vivre' onclick='calc_score_gds()'","TroubleCognitif:gds_heureux_vivre","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_heureux_vivre' onclick='calc_score_gds()'","TroubleCognitif:gds_heureux_vivre","non");  ?> </td>
  </tr>
  <tr>
    <td>Avez-vous l'impression de n'être plus bon (ne) à rien ?</td>
    <td> <?php radioButton("id='gds_bon_rien' onclick='calc_score_gds()'","TroubleCognitif:gds_bon_rien","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_bon_rien' onclick='calc_score_gds()'","TroubleCognitif:gds_bon_rien","non");  ?> </td>
  </tr>
  <tr>
    <td>Avez-vous beaucoup d'énergie ?</td>
    <td> <?php radioButton("id='gds_energie' onclick='calc_score_gds()'","TroubleCognitif:gds_energie","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_energie' onclick='calc_score_gds()'","TroubleCognitif:gds_energie","non");  ?> </td>
  </tr>
  <tr>
    <td>Désespérez-vous de votre situation présente ?</td>
    <td> <?php radioButton("id='gds_desespere_sit' onclick='calc_score_gds()'","TroubleCognitif:gds_desespere_sit","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_desespere_sit' onclick='calc_score_gds()'","TroubleCognitif:gds_desespere_sit","non");  ?> </td>
  </tr>
  <tr>
    <td>Pensez-vous que la situation des autres est meilleure que la votre, que les autres ont plus de chance que vous ? </td>
    <td> <?php radioButton("id='gds_sit_autres_best' onclick='calc_score_gds()'","TroubleCognitif:gds_sit_autres_best","oui");  ?> </td>
        <td> <?php radioButton("id='gds_pas_sit_autres_best' onclick='calc_score_gds()'","TroubleCognitif:gds_sit_autres_best","non");  ?> </td>
  </tr>
  <tr>
    <td>Score : </td>
    <td colspan='2' id='score_gds'><?php echo $TroubleCognitif->get_gds();?></td>
  </tr>

</table>

<br><br>
