<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $SatisfactionPatient; ?>
<?php global $param;?>

<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'>
  <?php hiddenControler("SatisfactionPatientControler"); ?>
  <?php hiddenAction(ACTION_NEW); ?>

<br> 
	<table border=1>
	<tr>
	<td colspan="2">
		<b>1- Vous avez consult� l'infirmi�re <i>(cochez la case correspondante)</i>	</b>
	</td>
	</tr>
	<td><?
	echo($SatisfactionPatient->demande_consult=="medecin"?"Sur les conseils de votre m�decin traitant":"A votre demande");?>
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
			<td><?php echo($SatisfactionPatient->motif_diabete=="1"?"oui":"non"); ?></td>
	</tr>
	</tr>
		<td>D�pistage</td>
			<td><?php echo($SatisfactionPatient->motif_depistage=="1"?"oui":"non"); ?></td>
	</tr>
	</tr>
		<td>Automesure tensionnelle</td>
			<td><?php echo($SatisfactionPatient->motif_automesure=="1"?"oui":"non"); ?></td>
	</tr>
	</tr>
		<td>Autres tests</td>
			<td><?php echo($SatisfactionPatient->motif_autre=="1"?"oui":"non"); ?></td>
	</tr>
	</tr>
		<td>RCVA</td>
			<td><?php echo($SatisfactionPatient->motif_rcva=="1"?"oui":"non"); ?></td>
	</tr>
	</tr>
		<td>Explications test h�moccult</td>
			<td><?php echo($SatisfactionPatient->motif_hemoccult=="1"?"oui":"non"); ?></td>
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
				<?php if($SatisfactionPatient->conseils_alimentaires=="1") { ?>
						1 Pas du tout d'accord
				<?php }
					  elseif($SatisfactionPatient->conseils_alimentaires=="2"){ ?>
						2 Pas vraiment d'accord
				<?php }
					  elseif($SatisfactionPatient->conseils_alimentaires=="3"){ ?>
					3 Plut�t d'accord
				<?php }
					  elseif($SatisfactionPatient->conseils_alimentaires=="4"){ ?>
					4 D'accord
				<?php }
					  elseif($SatisfactionPatient->conseils_alimentaires=="5"){ ?>
					5 Tout � fait d'accord
				<?php }
					  elseif($SatisfactionPatient->conseils_alimentaires=="6"){ ?>
					6 Aucun conseil
				<?php } ?>
			</td>
	</tr>
	</tr>
			<td>b. L'infirmi�re m'a donn� des conseils tr�s satisfaisants pour adapter mon mode de vie � ma sant�<br>
				<?php if($SatisfactionPatient->adapter_vie_sante=="1") { ?>
						1 Pas du tout d'accord
				<?php }
					  elseif($SatisfactionPatient->adapter_vie_sante=="2"){ ?>
						2 Pas vraiment d'accord
				<?php }
					  elseif($SatisfactionPatient->adapter_vie_sante=="3"){ ?>
					3 Plut�t d'accord
				<?php }
					  elseif($SatisfactionPatient->adapter_vie_sante=="4"){ ?>
					4 D'accord
				<?php }
					  elseif($SatisfactionPatient->adapter_vie_sante=="5"){ ?>
					5 Tout � fait d'accord
				<?php }
					  elseif($SatisfactionPatient->adapter_vie_sante=="6"){ ?>
					6 Aucun conseil
				<?php } ?>
			</td>
	</tr>
	</tr>
			<td>c. L'infirmi�re m'a donn� des conseils parfaitement r�alisables dans mon quotidien<br>
				<?php if($SatisfactionPatient->conseils_realisables=="1") { ?>
						1 Pas du tout d'accord
				<?php }
					  elseif($SatisfactionPatient->conseils_realisables=="2"){ ?>
						2 Pas vraiment d'accord
				<?php }
					  elseif($SatisfactionPatient->conseils_realisables=="3"){ ?>
					3 Plut�t d'accord
				<?php }
					  elseif($SatisfactionPatient->conseils_realisables=="4"){ ?>
					4 D'accord
				<?php }
					  elseif($SatisfactionPatient->conseils_realisables=="5"){ ?>
					5 Tout � fait d'accord
				<?php }
					  elseif($SatisfactionPatient->conseils_realisables=="6"){ ?>
					6 Aucun conseil
				<?php } ?>
			</td>
	</tr>
	</tr>
			<td>d. J'ai compris tous les conseils que l'infirmi�re m'a donn�<br>
				<?php if($SatisfactionPatient->compris_conseils=="1") { ?>
						1 Pas du tout d'accord
				<?php }
					  elseif($SatisfactionPatient->compris_conseils=="2"){ ?>
						2 Pas vraiment d'accord
				<?php }
					  elseif($SatisfactionPatient->compris_conseils=="3"){ ?>
					3 Plut�t d'accord
				<?php }
					  elseif($SatisfactionPatient->compris_conseils=="4"){ ?>
					4 D'accord
				<?php }
					  elseif($SatisfactionPatient->compris_conseils=="5"){ ?>
					5 Tout � fait d'accord
				<?php }
					  elseif($SatisfactionPatient->compris_conseils=="6"){ ?>
					6 Aucun conseil
				<?php } ?>
			</td>
	</tr>
	</tr>
			<td>e. La qualit� des conseils qu'elle m'a donn�s est tr�s bonne<br>
				<?php if($SatisfactionPatient->qualite_conseils=="1") { ?>
						1 Pas du tout d'accord
				<?php }
					  elseif($SatisfactionPatient->qualite_conseils=="2"){ ?>
						2 Pas vraiment d'accord
				<?php }
					  elseif($SatisfactionPatient->qualite_conseils=="3"){ ?>
					3 Plut�t d'accord
				<?php }
					  elseif($SatisfactionPatient->qualite_conseils=="4"){ ?>
					4 D'accord
				<?php }
					  elseif($SatisfactionPatient->qualite_conseils=="5"){ ?>
					5 Tout � fait d'accord
				<?php }
					  elseif($SatisfactionPatient->qualite_conseils=="6"){ ?>
					6 Aucun conseil
				<?php } ?>
			</td>
	</tr>
	</tr>
			<td>f. L'infirmi�re a r�pondu � toutes mes questions<br>
				<?php if($SatisfactionPatient->repondu_questions=="1") { ?>
						1 Pas du tout d'accord
				<?php }
					  elseif($SatisfactionPatient->repondu_questions=="2"){ ?>
						2 Pas vraiment d'accord
				<?php }
					  elseif($SatisfactionPatient->repondu_questions=="3"){ ?>
					3 Plut�t d'accord
				<?php }
					  elseif($SatisfactionPatient->repondu_questions=="4"){ ?>
					4 D'accord
				<?php }
					  elseif($SatisfactionPatient->repondu_questions=="5"){ ?>
					5 Tout � fait d'accord
				<?php } ?>
			</td>
	</tr>
	</tr>
			<td>g. L'infirmi�re m'a donn� des informations que j'ignorais<br>
				<?php if($SatisfactionPatient->informations_ignorees=="1") { ?>
						1 Pas du tout d'accord
				<?php }
					  elseif($SatisfactionPatient->informations_ignorees=="2"){ ?>
						2 Pas vraiment d'accord
				<?php }
					  elseif($SatisfactionPatient->informations_ignorees=="3"){ ?>
					3 Plut�t d'accord
				<?php }
					  elseif($SatisfactionPatient->informations_ignorees=="4"){ ?>
					4 D'accord
				<?php }
					  elseif($SatisfactionPatient->informations_ignorees=="5"){ ?>
					5 Tout � fait d'accord
				<?php } ?>
			</td>
	</tr>
	</tr>
			<td>h. L'infirmi�re a pris tout son temps pour m'�couter<br>
				<?php if($SatisfactionPatient->temps_ecoute=="1") { ?>
						1 Pas du tout d'accord
				<?php }
					  elseif($SatisfactionPatient->temps_ecoute=="2"){ ?>
						2 Pas vraiment d'accord
				<?php }
					  elseif($SatisfactionPatient->temps_ecoute=="3"){ ?>
					3 Plut�t d'accord
				<?php }
					  elseif($SatisfactionPatient->temps_ecoute=="4"){ ?>
					4 D'accord
				<?php }
					  elseif($SatisfactionPatient->temps_ecoute=="5"){ ?>
					5 Tout � fait d'accord
				<?php } ?>
			</td>
	</tr>
	</tr>
			<td>i. Je me suis senti parfaitement � l'aise avec l'infirmi�re<br>
				<?php if($SatisfactionPatient->aise=="1") { ?>
						1 Pas du tout d'accord
				<?php }
					  elseif($SatisfactionPatient->aise=="2"){ ?>
						2 Pas vraiment d'accord
				<?php }
					  elseif($SatisfactionPatient->aise=="3"){ ?>
					3 Plut�t d'accord
				<?php }
					  elseif($SatisfactionPatient->aise=="4"){ ?>
					4 D'accord
				<?php }
					  elseif($SatisfactionPatient->aise=="5"){ ?>
					5 Tout � fait d'accord
				<?php } ?>
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
				<?php if($SatisfactionPatient->satisf_consult=="1") { ?>
						1 Pas du tout
				<?php }
					  elseif($SatisfactionPatient->satisf_consult=="2"){ ?>
						2 Pas vraiment
				<?php }
					  elseif($SatisfactionPatient->satisf_consult=="3"){ ?>
					3 Plut�t satisfait
				<?php }
					  elseif($SatisfactionPatient->satisf_consult=="4"){ ?>
					4 Satisfait
				<?php }
					  elseif($SatisfactionPatient->satisf_consult=="5"){ ?>
					5 Tout � fait satisfait
				<?php } ?>
			</td>
	</tr>
	</tr>
			<td>b. Allez-vous suivre les conseils que l'infirmi�re vous a donn�s ?<br>
				<?php if($SatisfactionPatient->suivi_conseils=="1") { ?>
						1 Certainement pas
				<?php }
					  elseif($SatisfactionPatient->suivi_conseils=="2"){ ?>
						2 Probablement pas
				<?php }
					  elseif($SatisfactionPatient->suivi_conseils=="3"){ ?>
					3 Oui, probablement
				<?php }
					  elseif($SatisfactionPatient->suivi_conseils=="4"){ ?>
					4 Oui, certainement
				<?php }
					  elseif($SatisfactionPatient->suivi_conseils=="5"){ ?>
					5 Ne sais pas
				<?php } ?>
			</td>
	</tr>
	</tr>
			<td>c. Vous sentez-vous plus concern� par votre sant� ?<br>
				<?php if($SatisfactionPatient->concerne_sante=="1") { ?>
						1 Pas du tout
				<?php }
					  elseif($SatisfactionPatient->concerne_sante=="2"){ ?>
						2 Pas vraiment
				<?php }
					  elseif($SatisfactionPatient->concerne_sante=="3"){ ?>
					3 Plut�t
				<?php }
					  elseif($SatisfactionPatient->concerne_sante=="4"){ ?>
					5 Tout � fait
				<?php } ?>
			</td>
	</tr>
	</tr>
			<td>d. Voulez-vous revoir l�infirmi�re ?<br>
				<?php if($SatisfactionPatient->revoir_inf=="1") { ?>
						1 Certainement pas
				<?php }
					  elseif($SatisfactionPatient->revoir_inf=="2"){ ?>
						2 Probablement pas
				<?php }
					  elseif($SatisfactionPatient->revoir_inf=="3"){ ?>
						3 Oui, probablement
				<?php }
					  elseif($SatisfactionPatient->revoir_inf=="4"){ ?>
						4 Oui, certainement
				<?php }
					  elseif($SatisfactionPatient->revoir_inf=="5"){ ?>
						5 Ne sais pas
				<?php } ?>
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
		<td><?php echo stripslashes($SatisfactionPatient->commentaire); ?>
			</td>
	</tr>
	</table>
<br><br>
    <?php customSubmit("value='Remplir un autre questionnaire'",ACTION_NEW,"",$param->controler); ?>
</form>
