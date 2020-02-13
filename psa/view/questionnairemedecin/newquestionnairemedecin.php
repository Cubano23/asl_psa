<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $QuestionnaireMedecin;  ?>
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
  <?php hiddenControler("QuestionnaireMedecinControler"); ?>
  <?php hiddenAction(ACTION_SAVE); ?>
  <?php hiddenParamN($param->param3,3); ?>
  <?php hidden("","QuestionnaireMedecin:medecin");?>

	<table border=0 width='800'>
	<tr>
		<td>Nom </td>
		    <td><?php
				text("size='20' readonly maxlength='100'","QuestionnaireMedecin:nom"); ?>
			</td>
		<td>Prénom </td>
		    <td><?php
				text("size='20' readonly maxlength='100'","QuestionnaireMedecin:prenom"); ?>
			</td>
	</tr>
	</table>
	<br><br>

	<table border=1 width='800' >
	<CAPTION>
		<b>A quel stade vous êtes-vous impliqué dans ASALEE ?	</b>
	</CAPTION>
	<tr>
	    <td>&nbsp;</Td>
	        <td align='center'>A cocher</td>
	            <td align='center'>Commentaires</td>
	</tr>
	<tr>
		<td width='30%'>Initiation de la démarche</td>
			<td align='center'><?php checkBox("","QuestionnaireMedecin:implic_initiation","1"); ?></td>
				<td align='center'><?php textArea("rows=\"3\" cols=\"40\"","QuestionnaireMedecin:commentaire_implic_initiation"); ?>
	</tr>
	<tr>
		<td width='30%'>Conception du projet</td>
			<td align='center'><?php checkBox("","QuestionnaireMedecin:implic_conception","1"); ?></td>
				<td align='center'><?php textArea("rows=\"3\" cols=\"40\"","QuestionnaireMedecin:commentaire_implic_conception"); ?>
	</tr>
	<tr>
		<td width='30%'>Recueil des données</td>
			<td align='center'><?php checkBox("","QuestionnaireMedecin:implic_recueil","1"); ?></td>
				<td align='center'><?php textArea("rows=\"3\" cols=\"40\"","QuestionnaireMedecin:commentaire_implic_recueil"); ?>
	</tr>
	<tr>
		<td width='30%'>Analyse des données</td>
			<td align='center'><?php checkBox("","QuestionnaireMedecin:implic_analyse","1"); ?></td>
				<td align='center'><?php textArea("rows=\"3\" cols=\"40\"","QuestionnaireMedecin:commentaire_implic_analyse"); ?>
	</tr>
	<tr>
		<td width='30%'>Mise en oeuvre d'actions d'amélioration</td>
			<td align='center'><?php checkBox("","QuestionnaireMedecin:implic_mise_oeuvre","1"); ?></td>
				<td align='center'><?php textArea("rows=\"3\" cols=\"40\"","QuestionnaireMedecin:commentaire_implic_mise_oeuvre"); ?>
	</tr>
	<tr>
		<td width='30%'>Suivi des améliorations</td>
			<td align='center'><?php checkBox("","QuestionnaireMedecin:implic_suivi","1"); ?></td>
				<td align='center'><?php textArea("rows=\"3\" cols=\"40\"","QuestionnaireMedecin:commentaire_implic_suivi"); ?>
	</tr>
	</table>
	<br><br>
	<table border='1' width='800'>
	<CAPTION>
	<b>Qu'est ce que ce programme vous a apporté</b>
	</CAPTION>
		<tr>
			<td>En terme d'amélioration des pratiques professionnelles ?<br>
				<?php textArea("rows=\"5\" cols=\"40\"","QuestionnaireMedecin:amelioration_pratique"); ?></Td>
				<td><?php radioButton("","QuestionnaireMedecin:note_pratique","nulle"); ?> Nulle &nbsp;&nbsp;
					<?php radioButton("","QuestionnaireMedecin:note_pratique","faible"); ?> Faible &nbsp;&nbsp;
					<?php radioButton("","QuestionnaireMedecin:note_pratique","moyenne"); ?> Moyenne &nbsp;&nbsp;
					<?php radioButton("","QuestionnaireMedecin:note_pratique","bonne"); ?> Bonne &nbsp;&nbsp;
					<?php radioButton("","QuestionnaireMedecin:note_pratique","tb"); ?> Très bonne</td>
		</Tr>
		<tr>
			<td>En terme d'amélioration de l'organisation des soins ?<br>
				<?php textArea("rows=\"5\" cols=\"40\"","QuestionnaireMedecin:organisation_soins"); ?></Td>
				<td><?php radioButton("","QuestionnaireMedecin:note_soin","nulle"); ?> Nulle &nbsp;&nbsp;
					<?php radioButton("","QuestionnaireMedecin:note_soin","faible"); ?> Faible &nbsp;&nbsp;
					<?php radioButton("","QuestionnaireMedecin:note_soin","moyenne"); ?> Moyenne &nbsp;&nbsp;
					<?php radioButton("","QuestionnaireMedecin:note_soin","bonne"); ?> Bonne &nbsp;&nbsp;
					<?php radioButton("","QuestionnaireMedecin:note_soin","tb"); ?> Très bonne</td>
		</Tr>
		<tr>
			<td>En terme d'utilité pour le patient ?<br>
				<?php textArea("rows=\"5\" cols=\"40\"","QuestionnaireMedecin:utilite_patient"); ?></Td>
				<td><?php radioButton("","QuestionnaireMedecin:note_patient","nulle"); ?> Nulle &nbsp;&nbsp;
					<?php radioButton("","QuestionnaireMedecin:note_patient","faible"); ?> Faible &nbsp;&nbsp;
					<?php radioButton("","QuestionnaireMedecin:note_patient","moyenne"); ?> Moyenne &nbsp;&nbsp;
					<?php radioButton("","QuestionnaireMedecin:note_patient","bonne"); ?> Bonne &nbsp;&nbsp;
					<?php radioButton("","QuestionnaireMedecin:note_patient","tb"); ?> Très bonne</td>
		</Tr>
	</table>
	<br><br>

	<table border=1 width='800' >
<!--	<tr>
		<td colspan="2">
			<b>Cette démarche vous parait-elle faisable, acceptable, valide et efficace ?</b><br>
				<?php textArea("rows=\"5\" cols=\"60\"","QuestionnaireMedecin:demarche_faisable"); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<b>Avez-vous envisagé d'autres actions ?</b><br>
				<?php textArea("rows=\"5\" cols=\"60\"","QuestionnaireMedecin:autres_actions"); ?>
		</td>
	</tr>
-->	<tr>
		<td colspan="2">
			<b>Quels sont vos principaux points de satisfaction ?</b><br>
				<?php textArea("rows=\"5\" cols=\"60\"","QuestionnaireMedecin:satisfaction"); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<b>Principales difficultés rencontrées ?</b><br>
				<?php textArea("rows=\"5\" cols=\"60\"","QuestionnaireMedecin:difficultes"); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<b>Avez-vous repéré des améliorations possibles d'ASALEE ? Lesquelles ? Comment ?</b><br>
				<?php textArea("rows=\"5\" cols=\"60\"","QuestionnaireMedecin:ameliorations"); ?>
		</td>
	</tr>
	</Table>
	<br>
	<table border='0' width='80%'>
	<tr>
	    <td colspan="2" align='right'>Questionnaire enquête interne ASALEE inspirée par le Service Evaluation des Pratiques de l'HAS</td>
	</tr>
	</table>
<br>
  <input type='button' value='Valider la saisie' onClick="validateInput()">
  <input type='reset' value='Recommencer'>
</form>
