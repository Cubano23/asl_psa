<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $QuestionnaireMedecin; ?>
<?php global $param;?>

<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'>
  <?php hiddenControler("QuestionnaireMedecinControler"); ?>
  <?php hiddenAction(ACTION_NEW); ?>

<br> 
	<table border=0 width='80%'>
	<tr>
		<td>Nom </td>
		    <td><?php
				echo stripslashes($QuestionnaireMedecin->nom); ?>
			</td>
		<td>Pr�nom </td>
		    <td><?php
				echo stripslashes($QuestionnaireMedecin->prenom); ?>
			</td>
	</tr>
	</table>
	<br><br>

	<table border=1 width='80%' >
	<CAPTION>
		<b>A quel stade vous �tes-vous impliqu� dans ASALEE ?	</b>
	</CAPTION>
	<tr>
	    <td>&nbsp;</Td>
	        <td align='center'>A cocher</td>
	            <td align='center'>Commentaires</td>
	</tr>
	<tr>
		<td width='30%'>Initiation de la d�marche</td>
			<td align='center'><?php echo ($QuestionnaireMedecin->implic_initiation=="1")?'oui':''; ?></td>
				<td align='center'><?php echo nl2br(stripslashes($QuestionnaireMedecin->commentaire_implic_initiation)); ?>
	</tr>
	<tr>
		<td width='30%'>Conception du projet</td>
			<td align='center'><?php echo ($QuestionnaireMedecin->implic_conception=="1")?'oui':''; ?></td>
				<td align='center'><?php echo nl2br(stripslashes($QuestionnaireMedecin->commentaire_implic_conception)); ?>
	</tr>
	<tr>
		<td width='30%'>Recueil des donn�es</td>
			<td align='center'><?php echo ($QuestionnaireMedecin->implic_recueil=="1")?'oui':''; ?></td>
				<td align='center'><?php echo nl2br(stripslashes($QuestionnaireMedecin->commentaire_implic_recueil)); ?>
	</tr>
	<tr>
		<td width='30%'>Analyse des donn�es</td>
			<td align='center'><?php echo ($QuestionnaireMedecin->implic_analyse=="1")?'oui':''; ?></td>
				<td align='center'><?php echo nl2br(stripslashes($QuestionnaireMedecin->commentaire_implic_analyse)); ?>
	</tr>
	<tr>
		<td width='30%'>Mise en oeuvre d'actions d'am�lioration</td>
			<td align='center'><?php echo ($QuestionnaireMedecin->implic_mise_oeuvre=="1")?'oui':''; ?></td>
				<td align='center'><?php echo nl2br(stripslashes($QuestionnaireMedecin->commentaire_implic_mise_oeuvre)); ?>
	</tr>
	<tr>
		<td width='30%'>Suivi des am�liorations</td>
			<td align='center'><?php echo ($QuestionnaireMedecin->implic_suivi=="1")?'oui':''; ?></td>
				<td align='center'><?php echo nl2br(stripslashes($QuestionnaireMedecin->commentaire_implic_suivi)); ?>
	</tr>
	</table>
	<br>
	<table border='1' width='800'>
	<CAPTION>
	<b>Qu'est ce que ce programme vous a apport�</b>
	</CAPTION>
		<tr>
			<td>En terme d'am�lioration des pratiques professionnelles ?<br>
				<?php echo nl2br(stripslashes($QuestionnaireMedecin->amelioration_pratique)); ?></Td>
				<td><?php echo $QuestionnaireMedecin->note_pratique ?> </td>
		</Tr>
		<tr>
			<td>En terme d'am�lioration de l'organisation des soins ?<br>
				<?php echo nl2br(stripslashes($QuestionnaireMedecin->organisation_soins)); ?></Td>
				<td><?php echo $QuestionnaireMedecin->note_soin; ?> </td>
		</Tr>
		<tr>
			<td>En terme d'utilit� pour le patient ?<br>
				<?php echo nl2br(stripslashes($QuestionnaireMedecin->utilite_patient)); ?></Td>
				<td><?php echo $QuestionnaireMedecin->note_patient; ?> </td>
		</Tr>
	</table>
	<br><br>

	<table border=1 width='80%' >
<!--	<tr>
		<td colspan="2">
			<b>Cette d�marche vous parait-elle faisable, acceptable, valide et efficace ?</b><br>
				<?php echo nl2br(stripslashes($QuestionnaireMedecin->demarche_faisable)); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<b>Avez-vous envisag� d'autres actions ?</b><br>
				<?php echo nl2br(stripslashes($QuestionnaireMedecin->autres_actions)); ?>
		</td>
	</tr>-->
	<tr>
		<td colspan="2">
			<b>Quels sont vos principaux points de satisfaction ?</b><br>
				<?php echo nl2br(stripslashes($QuestionnaireMedecin->satisfaction)); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<b>Principales difficult�s rencontr�es ?</b><br>
				<?php echo nl2br(stripslashes($QuestionnaireMedecin->difficultes)); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<b>Avez-vous rep�r� des am�liorations possibles d'ASALEE ? Lesquelles ? Comment ?</b><br>
				<?php echo nl2br(stripslashes($QuestionnaireMedecin->ameliorations)); ?>
		</td>
	</tr>
	</Table>
	<br>
	<table border='0' width='80%'>
	<tr>
	    <td colspan="2" align='right'>Questionnaire enqu�te interne ASALEE inspir�e par le Service Evaluation des Pratiques de l'HAS</td>
	</tr>
	</table>
<br><br>
    <?php customSubmit("value='Modifier le questionnaire'",ACTION_NEW,"",$param->controler); ?></td>
</form>
