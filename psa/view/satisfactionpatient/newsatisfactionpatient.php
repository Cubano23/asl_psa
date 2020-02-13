<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $SatisfactionPatient ?>
<?php global $param;?>

<script type="text/javascript" >
<?php

	validatePositiveInteger();

	$js = new JSValidation();
	$js->startCheckFunction("validateInput","saveForm");
//	$js->validatePositiveInteger("FicheCabinet:total_pat","Nombre total de patients");
//	$js->validatePositiveInteger("FicheCabinet:total_sein","Nombre total de patientes �ligibles au cancer du sein");
//	$js->validatePositiveInteger("FicheCabinet:total_cogni","Nombre total de patients �ligibles pour les troubles cognitifs");
//	$js->validatePositiveInteger("FicheCabinet:total_colon","Nombre total de patients �ligibles pour le cancer du colon") ;
//	$js->validatePositiveInteger("FicheCabinet:total_uterus","Nombre total de patientes �ligibles pour le cancer de l'ut�rus");
//	$js->validatePositiveInteger("FicheCabinet:total_diab2","Nombre total de patients diab�tiques de type 2");
	$js->endCheckFunction();
	
?>

</script>

<br> 
<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'> 
  <?php hiddenControler("SatisfactionPatientControler"); ?>
  <?php hiddenAction(ACTION_SAVE); ?>
  <?php hiddenParamN($param->param3,3); ?>

	
	<table border=1>
	<tr>
	<td colspan="2">
		<b>1- Vous avez consult� l'infirmi�re <i>(cochez la case correspondante)</i>	</b>
	</td>
	</tr>
		<td>Sur les conseils de votre m�decin traitant
			<?php radioButton("","SatisfactionPatient:demande_consult","medecin"); ?>
		</td>
			<td>A votre demande
				<?php radioButton("","SatisfactionPatient:demande_consult","patient"); ?>
			</td>
	</tr>
	</table>
	<br><br>

	<table border=1>
	<tr>
	<td colspan="2">
		<b>2- Pour quels motifs avez-vous consult� l'infirmi�re <i>(cochez la ou les cases correspondantes)</i>	</b>
	</td>
	</tr>
		<td width='30%'>Diab�te</td>
			<td><?php checkBox("","SatisfactionPatient:motif_diabete","1"); ?></td>
	</tr>
	</tr>
		<td>D�pistage</td>
			<td><?php checkBox("","SatisfactionPatient:motif_depistage","1"); ?></td>
	</tr>
	</tr>
		<td>Automesure tensionnelle</td>
			<td><?php checkBox("","SatisfactionPatient:motif_automesure","1"); ?></td>
	</tr>
	</tr>
		<td>Autres tests</td>
			<td><?php checkBox("","SatisfactionPatient:motif_autre","1"); ?></td>
	</tr>
	</tr>
		<td>RCVA</td>
			<td><?php checkBox("","SatisfactionPatient:motif_rcva","1"); ?></td>
	</tr>
	</tr>
		<td>Explications test H�moccult</td>
			<td><?php checkBox("","SatisfactionPatient:motif_hemoccult","1"); ?></td>
	</tr>
	</table>
	<br><br>

	<table border=1 width='100%'>
	<tr>
	<td colspan="2">
		<b>3- Donnez votre opinion sur les affirmations suivantes <i>(Choisissez une seule r�ponse par affirmation)</i>	</b>
	</td>
	</tr>
			<td>a. L'infirmi�re m'a donn� des conseils alimentaires tr�s satisfaisants<br>
				<?php radioButton("","SatisfactionPatient:conseils_alimentaires","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_alimentaires","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_alimentaires","3"); ?>
					3 Plut�t d'accord <br>
				<?php radioButton("","SatisfactionPatient:conseils_alimentaires","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_alimentaires","5"); ?>
					5 Tout � fait d'accord &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_alimentaires","6"); ?>
					6 Aucun conseil
			</td>
	</tr>
	</tr>
			<td>b. L'infirmi�re m'a donn� des conseils tr�s satisfaisants pour adapter mon mode de vie � ma sant�<br>
				<?php radioButton("","SatisfactionPatient:adapter_vie_sante","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:adapter_vie_sante","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:adapter_vie_sante","3"); ?>
					3 Plut�t d'accord <br>
				<?php radioButton("","SatisfactionPatient:adapter_vie_sante","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:adapter_vie_sante","5"); ?>
					5 Tout � fait d'accord &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:adapter_vie_sante","6"); ?>
					6 Aucun conseil
			</td>
	</tr>
	</tr>
			<td>c. L'infirmi�re m'a donn� des conseils parfaitement r�alisables dans mon quotidien<br>
				<?php radioButton("","SatisfactionPatient:conseils_realisables","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_realisables","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_realisables","3"); ?>
					3 Plut�t d'accord <br>
				<?php radioButton("","SatisfactionPatient:conseils_realisables","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_realisables","5"); ?>
					5 Tout � fait d'accord &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_realisables","6"); ?>
					6 Aucun conseil
			</td>
	</tr>
	</tr>
			<td>d. J'ai compris tous les conseils que l'infirmi�re m'a donn�<br>
				<?php radioButton("","SatisfactionPatient:compris_conseils","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:compris_conseils","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:compris_conseils","3"); ?>
					3 Plut�t d'accord <br>
				<?php radioButton("","SatisfactionPatient:compris_conseils","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:compris_conseils","5"); ?>
					5 Tout � fait d'accord &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:compris_conseils","6"); ?>
					6 Aucun conseil
			</td>
	</tr>
	</tr>
			<td>e. La qualit� des conseils qu'elle m'a donn�s est tr�s bonne<br>
				<?php radioButton("","SatisfactionPatient:qualite_conseils","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:qualite_conseils","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:qualite_conseils","3"); ?>
					3 Plut�t d'accord <br>
				<?php radioButton("","SatisfactionPatient:qualite_conseils","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:qualite_conseils","5"); ?>
					5 Tout � fait d'accord &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:qualite_conseils","6"); ?>
					6 Aucun conseil
			</td>
	</tr>
	</tr>
			<td>f. L'infirmi�re a r�pondu � toutes mes questions<br>
				<?php radioButton("","SatisfactionPatient:repondu_questions","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:repondu_questions","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:repondu_questions","3"); ?>
					3 Plut�t d'accord <br>
				<?php radioButton("","SatisfactionPatient:repondu_questions","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:repondu_questions","5"); ?>
					5 Tout � fait d'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
			</td>
	</tr>
	</tr>
			<td>g. L'infirmi�re m'a donn� des informations que j'ignorais<br>
				<?php radioButton("","SatisfactionPatient:informations_ignorees","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:informations_ignorees","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:informations_ignorees","3"); ?>
					3 Plut�t d'accord <br>
				<?php radioButton("","SatisfactionPatient:informations_ignorees","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:informations_ignorees","5"); ?>
					5 Tout � fait d'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
			</td>
	</tr>
	</tr>
			<td>h. L'infirmi�re a pris tout son temps pour m'�couter<br>
				<?php radioButton("","SatisfactionPatient:temps_ecoute","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:temps_ecoute","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:temps_ecoute","3"); ?>
					3 Plut�t d'accord <br>
				<?php radioButton("","SatisfactionPatient:temps_ecoute","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:temps_ecoute","5"); ?>
					5 Tout � fait d'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
			</td>
	</tr>
	</tr>
			<td>i. Je me suis senti parfaitement � l'aise avec l'infirmi�re<br>
				<?php radioButton("","SatisfactionPatient:aise","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:aise","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:aise","3"); ?>
					3 Plut�t d'accord <br>
				<?php radioButton("","SatisfactionPatient:aise","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:aise","5"); ?>
					5 Tout � fait d'accord
			</td>
	</tr>
	</table>
	<br><br>

	<table border=1 width='100%'>
	<tr>
	<td colspan="2">
		<b>4- En conclusion <i>(Choisissez une seule r�ponse par question)</i>	</b>
	</td>
	</tr>
			<td>a. Etes vous satisfait(e) de cette consultation ?<br>
				<?php radioButton("","SatisfactionPatient:satisf_consult","1"); ?>
					1 Pas du tout satisfait &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:satisf_consult","2"); ?>
					2 Pas vraiment &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:satisf_consult","3"); ?>
					3 Plut�t satisfait <br>
				<?php radioButton("","SatisfactionPatient:satisf_consult","4"); ?>
					4 Satisfait &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:satisf_consult","5"); ?>
					5 Tout � fait satisfait
			</td>
	</tr>
	</tr>
			<td>b. Allez-vous suivre les conseils que l'infirmi�re vous a donn�s ?<br>
				<?php radioButton("","SatisfactionPatient:suivi_conseils","1"); ?>
					1 Certainement pas &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:suivi_conseils","2"); ?>
					2 Probablement pas &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:suivi_conseils","3"); ?>
					3 Oui, probablement <br>
				<?php radioButton("","SatisfactionPatient:suivi_conseils","4"); ?>
					4 Oui, cetainement &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:suivi_conseils","5"); ?>
					5 Ne sais pas
			</td>
	</tr>
	</tr>
			<td>c. Vous sentez-vous plus concern� par votre sant� ?<br>
				<?php radioButton("","SatisfactionPatient:concerne_sante","1"); ?>
					1 Pas du tout &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:concerne_sante","2"); ?>
					2 Pas vraiment &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:concerne_sante","3"); ?>
					3 Plut�t &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:concerne_sante","4"); ?>
					5 Tout � fait
			</td>
	</tr>
	</tr>
			<td>d. Voulez-vous revoir l�infirmi�re ?<br>
				<?php radioButton("","SatisfactionPatient:revoir_inf","1"); ?>
					1 Certainement pas &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:revoir_inf","2"); ?>
					2 Probablement pas &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:revoir_inf","3"); ?>
					3 Oui, probablement <br>
				<?php radioButton("","SatisfactionPatient:revoir_inf","4"); ?>
					4 Oui, certainement &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:revoir_inf","5"); ?>
					5 Ne sais pas
			</td>
	</tr>
	</table>
	<br><br>
	
	<table border=1>
	<tr>
	<td>
		<b>N'h�sitez pas � ajouter un commentaire particulier</b>
	</td>
	</tr>
		<td><?php textArea("rows=\"8\" cols=\"60\"","SatisfactionPatient:commentaire"); ?>
			</td>
	</tr>
	</table>

  <input type='button' value='Valider la saisie' onClick="validateInput()">
  <input type='reset' value='Recommencer'>
</form>
