<script language="javascript">

	function calc_score_mmse(){
	  var score_mmse=0;
	  
	  var mmse_annee = document.getElementById("mmse_annee");
	  var mmse_saison = document.getElementById("mmse_saison");
	  var mmse_mois = document.getElementById("mmse_mois");
	  var mmse_jour_mois = document.getElementById("mmse_jour_mois");
	  var mmse_jour_semaine = document.getElementById("mmse_jour_semaine");
	  var mmse_nom_hop = document.getElementById("mmse_nom_hop");
	  var mmse_nom_ville = document.getElementById("mmse_nom_ville");
	  var mmse_nom_dep = document.getElementById("mmse_nom_dep");
	  var mmse_region = document.getElementById("mmse_region");
	  var mmse_etage = document.getElementById("mmse_etage");
	  var mmse_cigare1 = document.getElementById("mmse_cigare1");
	  var mmse_fleur1 = document.getElementById("mmse_fleur1");
	  var mmse_porte1 = document.getElementById("mmse_porte1");
	  var mmse_93 = document.getElementById("mmse_93");
	  var mmse_86 = document.getElementById("mmse_86");
	  var mmse_79 = document.getElementById("mmse_79");
	  var mmse_72 = document.getElementById("mmse_72");
	  var mmse_65 = document.getElementById("mmse_65");
//	  var mmse_monde = document.getElementById("mmse_monde");
	  var mmse_cigare2 = document.getElementById("mmse_cigare2");
	  var mmse_fleur2 = document.getElementById("mmse_fleur2");
	  var mmse_porte2 = document.getElementById("mmse_porte2");
	  var mmse_crayon = document.getElementById("mmse_crayon");
	  var mmse_montre = document.getElementById("mmse_montre");
	  var mmse_repete_phrase = document.getElementById("mmse_repete_phrase");
	  var mmse_feuille_prise = document.getElementById("mmse_feuille_prise");
	  var mmse_feuille_pliee = document.getElementById("mmse_feuille_pliee");
	  var mmse_feuille_jetee = document.getElementById("mmse_feuille_jetee");
	  var mmse_fermer_yeux = document.getElementById("mmse_fermer_yeux");
	  var mmse_ecrit_phrase = document.getElementById("mmse_ecrit_phrase");
	  var mmse_copie_dessin = document.getElementById("mmse_copie_dessin");


	  if (mmse_annee.checked==true){
	    score_mmse++;
	  }
	  
	  if (mmse_saison.checked==true){
	    score_mmse++;
	  }

	  if (mmse_mois.checked==true){
	    score_mmse++;
	  }

	  if (mmse_jour_mois.checked==true){
	    score_mmse++;
	  }

	  if (mmse_jour_semaine.checked==true){
	    score_mmse++;
	  }

	  if (mmse_nom_hop.checked==true){
	    score_mmse++;
	  }

	  if (mmse_nom_ville.checked==true){
	    score_mmse++;
	  }

	  if (mmse_nom_dep.checked==true){
	    score_mmse++;
	  }

	  if (mmse_region.checked==true){
	    score_mmse++;
	  }

	  if (mmse_etage.checked==true){
	    score_mmse++;
	  }

	  if (mmse_cigare1.checked==true){
	    score_mmse++;
	  }

	  if (mmse_fleur1.checked==true){
	    score_mmse++;
	  }

	  if (mmse_porte1.checked==true){
	    score_mmse++;
	  }

	  if (mmse_93.checked==true){
	    score_mmse++;
	  }

	  if (mmse_86.checked==true){
	    score_mmse++;
	  }

	  if (mmse_79.checked==true){
	    score_mmse++;
	  }

	  if (mmse_72.checked==true){
	    score_mmse++;
	  }

	  if (mmse_65.checked==true){
	    score_mmse++;
	  }
	  
/*	  if(mmse_monde.value!=0){
	  	  score_mmse=score_mmse+parseInt(mmse_monde.value);
	  }
*/
	  if (mmse_cigare2.checked==true){
	    score_mmse++;
	  }

	  if (mmse_fleur2.checked==true){
	    score_mmse++;
	  }

	  if (mmse_porte2.checked==true){
	    score_mmse++;
	  }

	  if (mmse_crayon.checked==true){
	    score_mmse++;
	  }

	  if (mmse_montre.checked==true){
	    score_mmse++;
	  }

	  if (mmse_repete_phrase.checked==true){
	    score_mmse++;
	  }

	  if (mmse_feuille_prise.checked==true){
	    score_mmse++;
	  }

	  if (mmse_feuille_pliee.checked==true){
	    score_mmse++;
	  }

	  if (mmse_feuille_jetee.checked==true){
	    score_mmse++;
	  }

	  if (mmse_fermer_yeux.checked==true){
	    score_mmse++;
	  }
	  
	  if (mmse_ecrit_phrase.checked==true){
	    score_mmse++;
	  }
	  
	  if (mmse_copie_dessin.checked==true){
	    score_mmse++;
	  }
	  
	  document.getElementById("score_mmse").innerHTML = score_mmse;
	}

	function checkMmse(aForm){
		var i;
		var submitOk = 1;
		<?php
		$js = new JSValidation();		
		?>

	var mmse_monde = document.getElementById("mmse_monde");

/*	if((mmse_monde.value<0)|| (mmse_monde.value>5)){
	    alert("Valeur incorect pour le nombre de lettre de MONDE");
	    submitOk=0;
	}
*/
		return submitOk;
		}
</script>

<table border='0'  width='100%'>
<tr>
<td align='center'>
  <caption>
  <big><b><font color='blue'>MMSE</font></b></big>
  </caption>
  </td>
</tr>
</table>

<table border='1'  width='100%'>
	 <font color='blue'>Je vais vous poser quelques questions pour apprécier comment fonctionne votre mémoire.
		Les unes sont très simples, les autres un peu moins. Vous devez répondre du mieux que vous pouvez.
		Quelle est la date complète d'aujourd'hui ?</font><i> Si la réponse est incorrecte ou incomplète,
		posez les questions restées sans réponse dans l'ordre suivant : </i>
 <tr>
    <td>Question</td>
        <td>Cocher si correct</td>
  </tr>
  <tr> 
    <td>En quelle année sommes-nous?</td>
    <td><?php checkBox("id='mmse_annee' onclick='calc_score_mmse()'","TroubleCognitif:mmse_annee","1"); ?></td>
  </tr> 
  <tr>
    <td>En quelle saison ?</td>
    <td><?php checkBox("id='mmse_saison' onclick='calc_score_mmse()'","TroubleCognitif:mmse_saison","1"); ?></td>
  </tr>
  <tr>
    <td>En quel mois ?</td>
    <td><?php checkBox("id='mmse_mois' onclick='calc_score_mmse()'","TroubleCognitif:mmse_mois","1"); ?></td>
  </tr>
  <tr>
    <td>Quel jour du mois</td>
    <td><?php checkBox("id='mmse_jour_mois' onclick='calc_score_mmse()'","TroubleCognitif:mmse_jour_mois","1"); ?></td>
  </tr>
  <tr>
    <td>Quel jour de la semaine</td>
    <td><?php checkBox("id='mmse_jour_semaine' onclick='calc_score_mmse()'","TroubleCognitif:mmse_jour_semaine","1"); ?></td>
  </tr>
  <tr>
        <td colspan='2'><font color='blue'>Je vais vous poser maintenant quelques questions sur
							l'endroit où nous nous trouvons</font></td>
  </tr>
  <tr>
    <td>Quel est le nom de l'hôpital où nous sommes (ou Quel est le nom de l'hôpital de la ville d'où vous venez ou Quel le nom du médecin que vous avez vu ?)</td>
    <td><?php checkBox("id='mmse_nom_hop' onclick='calc_score_mmse()'","TroubleCognitif:mmse_nom_hop","1"); ?></td>
  </tr>
  <tr>
    <td>Dans quelle ville se trouve t-il ?</td>
    <td><?php checkBox("id='mmse_nom_ville' onclick='calc_score_mmse()'","TroubleCognitif:mmse_nom_ville","1"); ?></td>
  </tr>
  <tr>
    <td>Quel est le nom du Département dans lequel est située cette ville ?</td>
    <td><?php checkBox("id='mmse_nom_dep' onclick='calc_score_mmse()'","TroubleCognitif:mmse_nom_dep","1"); ?></td>
  </tr>
  <tr>
    <td>Dans quelle Région est située ce Département (si le nom de la région est identique à celui du département alors : dans quel Pays est situé ce Département) ?</td>
    <td><?php checkBox("id='mmse_region' onclick='calc_score_mmse()'","TroubleCognitif:mmse_region","1"); ?></td>
  </tr>
  <tr>
    <td>A quel étage sommes-nous ici ?</td>
    <td><?php checkBox("id='mmse_etage' onclick='calc_score_mmse()'","TroubleCognitif:mmse_etage","1"); ?></td>
  </tr>
  <tr>
    <td colspan='2'><i>Apprentissage : <br></i>
									<font color='blue'>Je vais vous dire 3 mots : je voudrais que vous me les répétiez
										et que vous essayiez de les retenir car je vous les redemanderai tout à l'heure</font></td>
  </tr>
  <tr>
    <td>Répétez Cigare</td>
    <td><?php checkBox("id='mmse_cigare1' onclick='calc_score_mmse()'","TroubleCognitif:mmse_cigare1","1"); ?></td>
  </tr>
  <tr>
    <td>Répétez Fleur</td>
    <td><?php checkBox("id='mmse_fleur1' onclick='calc_score_mmse()'","TroubleCognitif:mmse_fleur1","1"); ?></td>
  </tr>
  <tr>
    <td>Répétez Porte</td>
    <td><?php checkBox("id='mmse_porte1' onclick='calc_score_mmse()'","TroubleCognitif:mmse_porte1","1"); ?></td>
  </tr>
  <tr>
    <td colspan='2'><i>Attention et calcul : </i></td>
  </tr>
  <tr>
    <td>Comptez à partir de 100 en retranchant 7 à chaque fois (93)</td>
    <td><?php checkBox("id='mmse_93' onclick='calc_score_mmse()'","TroubleCognitif:mmse_93","1"); ?></td>
  </tr>
  <tr>
    <td>Retranchez encore 7 (86)</td>
    <td><?php checkBox("id='mmse_86' onclick='calc_score_mmse()'","TroubleCognitif:mmse_86","1"); ?></td>
  </tr>
  <tr>
    <td>Retranchez encore 7 (79)</td>
    <td><?php checkBox("id='mmse_79' onclick='calc_score_mmse()'","TroubleCognitif:mmse_79","1"); ?></td>
  </tr>
  <tr>
    <td>Retranchez encore 7 (72)</td>
    <td><?php checkBox("id='mmse_72' onclick='calc_score_mmse()'","TroubleCognitif:mmse_72","1"); ?></td>
  </tr>
  <tr>
    <td>Retranchez encore 7 (65)</td>
    <td><?php checkBox("id='mmse_65' onclick='calc_score_mmse()'","TroubleCognitif:mmse_65","1"); ?></td>
  </tr>
  <tr>
        <td colspan='2'><i>Pour tous les sujets, même ceux qui ont obtenu le maximum de points
			demander : (Le score ne doit pas figurer dans le score global)</i></td>
  </tr>
  <tr>
    <td>Epelez le mot MONDE à l'envers (EDNOM) (indiquez les lettres épelées)</td>
    <td><?php text("id='mmse_monde' size='5' ","TroubleCognitif:mmse_monde"); ?></td>
  </tr>
  <tr>
    <td colspan='2'><i>Rappel : </i><font color='blue'>Pouvez-vous me dire quels étaient les 3 mots que je vous
				ai demandé de répéter et de retenir tout à l'heure</font></td>
  </tr>
  <tr>
    <td>CIGARE</td>
    <td><?php checkBox("id='mmse_cigare2' onclick='calc_score_mmse()'","TroubleCognitif:mmse_cigare2","1"); ?></td>
  </tr>
  <tr>
    <td>FLEUR</td>
    <td><?php checkBox("id='mmse_fleur2' onclick='calc_score_mmse()'","TroubleCognitif:mmse_fleur2","1"); ?></td>
  </tr>
  <tr>
    <td>PORTE</td>
    <td><?php checkBox("id='mmse_porte2' onclick='calc_score_mmse()'","TroubleCognitif:mmse_porte2","1"); ?></td>
  </tr>
  <tr>
    <td colspan='2'><i>Langage</i></td>
  </tr>
  <tr>
    <td>Nom de cet objet (un crayon)?</td>
    <td><?php checkBox("id='mmse_crayon' onclick='calc_score_mmse()'","TroubleCognitif:mmse_crayon","1"); ?></td>
  </tr>
  <tr>
    <td>Nom de cet objet (montre) ?</td>
    <td><?php checkBox("id='mmse_montre' onclick='calc_score_mmse()'","TroubleCognitif:mmse_montre","1"); ?></td>
  </tr>
  <tr>
    <td>Ecoutez bien et répétez après moi : "pas de mais, de si, ni de et"</td>
    <td><?php checkBox("id='mmse_repete_phrase' onclick='calc_score_mmse()'","TroubleCognitif:mmse_repete_phrase","1"); ?></td>
  </tr>
  <tr>
    <td>Ecoutez bien et faites ce que je vais vous dire "Prenez cette feuille de papier avec la main droite"</td>
    <td><?php checkBox("id='mmse_feuille_prise' onclick='calc_score_mmse()'","TroubleCognitif:mmse_feuille_prise","1"); ?></td>
  </tr>
  <tr>
    <td>Pliez-là en deux</td>
    <td><?php checkBox("id='mmse_feuille_pliee' onclick='calc_score_mmse()'","TroubleCognitif:mmse_feuille_pliee","1"); ?></td>
  </tr>
  <tr>
    <td>Et jetez-là par terre</td>
    <td><?php checkBox("id='mmse_feuille_jetee' onclick='calc_score_mmse()'","TroubleCognitif:mmse_feuille_jetee","1"); ?></td>
  </tr>
  <tr>
    <td>Faites ce qui est écrit (Tendre une feuille sur laquelle est écrit en gros caractères "fermez les yeux")</td>
    <td><?php checkBox("id='mmse_fermer_yeux' onclick='calc_score_mmse()'","TroubleCognitif:mmse_fermer_yeux","1"); ?></td>
  </tr>
  <tr>
    <td>Voulez-vous m'écrire une phrase, ce que vous voulez mais une phrase entière. Nota : Cette phrase doit avoir un sens. </td>
    <td><?php checkBox("id='mmse_ecrit_phrase' onclick='calc_score_mmse()'","TroubleCognitif:mmse_ecrit_phrase","1"); ?></td>
  </tr>
  <tr>
    <td colspan='2'><i>Praxies constructives</i></td>
  </tr>
  <tr>
    <td>Voulez-vous recopier ce dessin?</td>
    <td><?php checkBox("id='mmse_copie_dessin' onclick='calc_score_mmse()'","TroubleCognitif:mmse_copie_dessin","1"); ?></td>
  </tr>
  <tr>
    <td>Score : </td>
    <td id='score_mmse'><?php echo $TroubleCognitif->get_mmse();?></td>
  </tr>

</table>

<br><br>
