<?php global $dossier;?>
<?php global $epices;?>
<?php global $account;?>

<script language="javascript">
	function checkepices(aForm){
		var i;
		var submitOk = 1;
		var sOk;
		<?php
		$js = new JSValidation();		
?>
		return submitOk;
		}
		
	function calcul_epices(){
		var total;
		total=75.14;
		
		if(document.getElementById("travailleur_social").checked==true){
			total=total+10.06;
		}
		if(document.getElementById("complementaire").checked==true){
			total=total-11.83;
		}
		if(document.getElementById("couple").checked==true){
			total=total-8.28;
		}
		if(document.getElementById("proprietaire").checked==true){
			total=total-8.28;
		}
		if(document.getElementById("difficulte").checked==true){
			total=total+14.80;
		}
		if(document.getElementById("sport").checked==true){
			total=total-6.51;
		}
		if(document.getElementById("spectacle").checked==true){
			total=total-7.10;
		}
		if(document.getElementById("vacances").checked==true){
			total=total-7.10;
		}
		if(document.getElementById("famille").checked==true){
			total=total-9.47;
		}
		if(document.getElementById("hebergement").checked==true){
			total=total-9.47;
		}
		if(document.getElementById("materiel").checked==true){
			total=total-7.10;
		}
		
		total=Math.round(total*100);
		total=total/100;
		
		score=document.getElementById("score_epices");
		
		score.innerHTML=total;
	}
	
	function affiche_score(){	
		if(document.getElementById("score_epices").style.display=="none"){
			document.getElementById("score_epices").style.display="";
		}
		else{
			document.getElementById("score_epices").style.display="none";
		}
	}
</script>

<h1><?php echo $item."- ";?>Questionnaire complémentaire</h1>

<table border='1'>
	<tr><td>Question</td><td>Oui</td><td>Non</td></Tr>
	<tr><td>1- Rencontrez vous parfois un travailleur social ?</td><td nowrap><?php radioButton("id='travailleur_social' onclick='calcul_epices()'","Epices:travailleur_social","oui"); ?></td><td><?php radioButton(" onclick='calcul_epices()'","Epices:travailleur_social","non");?></td></Tr>
	<tr><td>2- Bénéficiez-vous d'une assurance maladie complémentaire ?</td><td><?php radioButton("id='complementaire' onclick='calcul_epices()'","Epices:complementaire","oui"); ?></td><td><?php radioButton(" onclick='calcul_epices()'","Epices:complementaire","non");?></td></tr>
	<tr><td>3- Vivez-vous en couple ?</td><td><?php radioButton("id='couple' onclick='calcul_epices()'","Epices:couple","oui"); ?></td><td><?php radioButton("","Epices:couple","non");?></td></tr>
	<tr><td>4- Etes-vous propriétaire de votre logement ?</td><td><?php radioButton("id='proprietaire' onclick='calcul_epices()'","Epices:proprietaire","oui"); ?></td><td><?php radioButton(" onclick='calcul_epices()'","Epices:proprietaire","non");?></td></tr>
	<tr><td>5- Y-a-t'il des périodes dans le mois ou vous rencontrez de réelles difficultés financières à faire face à vos besoins(alimentation, loyer, EDF…) ?</td><td><?php radioButton("id='difficulte' onclick='calcul_epices()'","Epices:difficulte","oui"); ?></td><td><?php radioButton(" onclick='calcul_epices()'","Epices:difficulte","non");?></td></tr>
	<tr><td>6- Vous est-il arrivé de faire du sport au cours des 12 derniers mois ?</td><td><?php radioButton("id='sport' onclick='calcul_epices()'","Epices:sport","oui"); ?></td><td><?php radioButton(" onclick='calcul_epices()'","Epices:sport","non");?></td></Tr>
	<tr><td>7- Etes-vous allé au spectacle au cours des 12 derniers mois ?</td><td><?php radioButton("id='spectacle' onclick='calcul_epices()'","Epices:spectacle","oui"); ?></td><td><?php radioButton(" onclick='calcul_epices()'","Epices:spectacle","non");?></td></Tr>
	<tr><td>8- Etes-vous parti en vacances au cours des 12 derniers mois ?</td><td><?php radioButton("id='vacances' onclick='calcul_epices()'","Epices:vacances","oui"); ?></td><td><?php radioButton(" onclick='calcul_epices()'","Epices:vacances","non");?></td></Tr>
	<tr><td>9- Au cours des 6 derniers mois avez-vous eu des contacts avec des membres de votre famille autres que parents ou enfants ?</td><td><?php radioButton("id='famille' onclick='calcul_epices()'","Epices:famille","oui"); ?></td><td><?php radioButton(" onclick='calcul_epices()'","Epices:famille","non");?></Td></Tr>
	<tr><td>10- En cas de difficultés, il y a-t-il dans votre entourage des personnes sur qui vous puissiez compter pour vous héberger quelques jours en cas de besoin ?</Td><td><?php radioButton("id='hebergement' onclick='calcul_epices()'","Epices:hebergement","oui"); ?></td><td><?php radioButton(" onclick='calcul_epices()'","Epices:hebergement","non");?></td></Tr>
	<tr><td>11- En cas de difficultés, il y a-t-il dans votre entourage des personnes sur qui vous puissiez compter pour vous apporter une aide matérielle ?</td><td><?php radioButton("id='materiel' onclick='calcul_epices()'","Epices:materiel","oui"); ?></td><td><?php radioButton(" onclick='calcul_epices()'","Epices:materiel","non");?></td></Tr>
	<tr><td><a href='javascript://' onclick='affiche_score()'>Afficher/masquer le score</a></td><td colspan='2' style='display:none' id='score_epices'></td></tr>
	</table>
	<br>
<br><br>

