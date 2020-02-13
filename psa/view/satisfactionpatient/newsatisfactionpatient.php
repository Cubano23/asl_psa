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
//	$js->validatePositiveInteger("FicheCabinet:total_sein","Nombre total de patientes éligibles au cancer du sein");
//	$js->validatePositiveInteger("FicheCabinet:total_cogni","Nombre total de patients éligibles pour les troubles cognitifs");
//	$js->validatePositiveInteger("FicheCabinet:total_colon","Nombre total de patients éligibles pour le cancer du colon") ;
//	$js->validatePositiveInteger("FicheCabinet:total_uterus","Nombre total de patientes éligibles pour le cancer de l'utérus");
//	$js->validatePositiveInteger("FicheCabinet:total_diab2","Nombre total de patients diabétiques de type 2");
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
		<b>1- Vous avez consulté l'infirmière <i>(cochez la case correspondante)</i>	</b>
	</td>
	</tr>
		<td>Sur les conseils de votre médecin traitant
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
		<b>2- Pour quels motifs avez-vous consulté l'infirmière <i>(cochez la ou les cases correspondantes)</i>	</b>
	</td>
	</tr>
		<td width='30%'>Diabète</td>
			<td><?php checkBox("","SatisfactionPatient:motif_diabete","1"); ?></td>
	</tr>
	</tr>
		<td>Dépistage</td>
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
		<td>Explications test Hémoccult</td>
			<td><?php checkBox("","SatisfactionPatient:motif_hemoccult","1"); ?></td>
	</tr>
	</table>
	<br><br>

	<table border=1 width='100%'>
	<tr>
	<td colspan="2">
		<b>3- Donnez votre opinion sur les affirmations suivantes <i>(Choisissez une seule réponse par affirmation)</i>	</b>
	</td>
	</tr>
			<td>a. L'infirmière m'a donné des conseils alimentaires très satisfaisants<br>
				<?php radioButton("","SatisfactionPatient:conseils_alimentaires","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_alimentaires","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_alimentaires","3"); ?>
					3 Plutôt d'accord <br>
				<?php radioButton("","SatisfactionPatient:conseils_alimentaires","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_alimentaires","5"); ?>
					5 Tout à fait d'accord &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_alimentaires","6"); ?>
					6 Aucun conseil
			</td>
	</tr>
	</tr>
			<td>b. L'infirmière m'a donné des conseils très satisfaisants pour adapter mon mode de vie à ma santé<br>
				<?php radioButton("","SatisfactionPatient:adapter_vie_sante","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:adapter_vie_sante","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:adapter_vie_sante","3"); ?>
					3 Plutôt d'accord <br>
				<?php radioButton("","SatisfactionPatient:adapter_vie_sante","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:adapter_vie_sante","5"); ?>
					5 Tout à fait d'accord &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:adapter_vie_sante","6"); ?>
					6 Aucun conseil
			</td>
	</tr>
	</tr>
			<td>c. L'infirmière m'a donné des conseils parfaitement réalisables dans mon quotidien<br>
				<?php radioButton("","SatisfactionPatient:conseils_realisables","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_realisables","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_realisables","3"); ?>
					3 Plutôt d'accord <br>
				<?php radioButton("","SatisfactionPatient:conseils_realisables","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_realisables","5"); ?>
					5 Tout à fait d'accord &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:conseils_realisables","6"); ?>
					6 Aucun conseil
			</td>
	</tr>
	</tr>
			<td>d. J'ai compris tous les conseils que l'infirmière m'a donné<br>
				<?php radioButton("","SatisfactionPatient:compris_conseils","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:compris_conseils","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:compris_conseils","3"); ?>
					3 Plutôt d'accord <br>
				<?php radioButton("","SatisfactionPatient:compris_conseils","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:compris_conseils","5"); ?>
					5 Tout à fait d'accord &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:compris_conseils","6"); ?>
					6 Aucun conseil
			</td>
	</tr>
	</tr>
			<td>e. La qualité des conseils qu'elle m'a donnés est très bonne<br>
				<?php radioButton("","SatisfactionPatient:qualite_conseils","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:qualite_conseils","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:qualite_conseils","3"); ?>
					3 Plutôt d'accord <br>
				<?php radioButton("","SatisfactionPatient:qualite_conseils","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:qualite_conseils","5"); ?>
					5 Tout à fait d'accord &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:qualite_conseils","6"); ?>
					6 Aucun conseil
			</td>
	</tr>
	</tr>
			<td>f. L'infirmière a répondu à toutes mes questions<br>
				<?php radioButton("","SatisfactionPatient:repondu_questions","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:repondu_questions","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:repondu_questions","3"); ?>
					3 Plutôt d'accord <br>
				<?php radioButton("","SatisfactionPatient:repondu_questions","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:repondu_questions","5"); ?>
					5 Tout à fait d'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
			</td>
	</tr>
	</tr>
			<td>g. L'infirmière m'a donné des informations que j'ignorais<br>
				<?php radioButton("","SatisfactionPatient:informations_ignorees","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:informations_ignorees","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:informations_ignorees","3"); ?>
					3 Plutôt d'accord <br>
				<?php radioButton("","SatisfactionPatient:informations_ignorees","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:informations_ignorees","5"); ?>
					5 Tout à fait d'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
			</td>
	</tr>
	</tr>
			<td>h. L'infirmière a pris tout son temps pour m'écouter<br>
				<?php radioButton("","SatisfactionPatient:temps_ecoute","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:temps_ecoute","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:temps_ecoute","3"); ?>
					3 Plutôt d'accord <br>
				<?php radioButton("","SatisfactionPatient:temps_ecoute","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:temps_ecoute","5"); ?>
					5 Tout à fait d'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
			</td>
	</tr>
	</tr>
			<td>i. Je me suis senti parfaitement à l'aise avec l'infirmière<br>
				<?php radioButton("","SatisfactionPatient:aise","1"); ?>
					1 Pas du tout d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:aise","2"); ?>
					2 Pas vraiment d'accord &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:aise","3"); ?>
					3 Plutôt d'accord <br>
				<?php radioButton("","SatisfactionPatient:aise","4"); ?>
					4 D'accord &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:aise","5"); ?>
					5 Tout à fait d'accord
			</td>
	</tr>
	</table>
	<br><br>

	<table border=1 width='100%'>
	<tr>
	<td colspan="2">
		<b>4- En conclusion <i>(Choisissez une seule réponse par question)</i>	</b>
	</td>
	</tr>
			<td>a. Etes vous satisfait(e) de cette consultation ?<br>
				<?php radioButton("","SatisfactionPatient:satisf_consult","1"); ?>
					1 Pas du tout satisfait &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:satisf_consult","2"); ?>
					2 Pas vraiment &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:satisf_consult","3"); ?>
					3 Plutôt satisfait <br>
				<?php radioButton("","SatisfactionPatient:satisf_consult","4"); ?>
					4 Satisfait &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:satisf_consult","5"); ?>
					5 Tout à fait satisfait
			</td>
	</tr>
	</tr>
			<td>b. Allez-vous suivre les conseils que l'infirmière vous a donnés ?<br>
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
			<td>c. Vous sentez-vous plus concerné par votre santé ?<br>
				<?php radioButton("","SatisfactionPatient:concerne_sante","1"); ?>
					1 Pas du tout &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:concerne_sante","2"); ?>
					2 Pas vraiment &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:concerne_sante","3"); ?>
					3 Plutôt &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
				<?php radioButton("","SatisfactionPatient:concerne_sante","4"); ?>
					5 Tout à fait
			</td>
	</tr>
	</tr>
			<td>d. Voulez-vous revoir l’infirmière ?<br>
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
		<b>N'hésitez pas à ajouter un commentaire particulier</b>
	</td>
	</tr>
		<td><?php textArea("rows=\"8\" cols=\"60\"","SatisfactionPatient:commentaire"); ?>
			</td>
	</tr>
	</table>

  <input type='button' value='Valider la saisie' onClick="validateInput()">
  <input type='reset' value='Recommencer'>
</form>
