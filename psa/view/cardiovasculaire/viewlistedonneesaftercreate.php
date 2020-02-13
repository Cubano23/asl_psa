<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $ListeDonnees; ?>
<?php global $param; ?>

<?php
$valeurs = array(""=>"",
				  "vert"=>"La rubrique existe dans le logiciel et est renseignée",
				  "orange"=>"La rubrique existe mais est pas ou peu renseignée",
				  "rouge"=>"La rubrique n'existe pas"); ?> 
 
<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("ListeDonneesControler"); ?>
<?php hidden("","CardioVasculaireDepart:date"); ?>

Les Données ont été enregistrées
<?php die;?>
<?php require("view/common/dossierresume.php");?>



  <br>
  <b>Facteurs de risque non modifiables</b>
  <table border=1 width='670'>
  	<tr>
  		<td width='300'>Antécédents familiaux du premier degré (accident vasculaire avant 55 ans chez les hommes et 
		  	65 ans chez les femmes)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>def_cardio.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Définition accident cardio-vasculaire' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
		  	<td colspan='2'><?php echo $CardioVasculaireDepart->antecedants ?>
		</td>
	</tr>
  </table>
  <br>
  <b>Bilan lipidique</b>
  <table border='1' width='670'>
  	<tr>
  		<td>Cholestérol total</td>
  			<td colspan='2'><?php echo $CardioVasculaireDepart->Chol;?>g/l &nbsp;
  							<?php echo "le ".$CardioVasculaireDepart->dChol;?></td>
  	</tr>
  	<tr>
  		<td>HDL Cholestérol</td>
  			<td colspan='2'><?php echo $CardioVasculaireDepart->HDL;?>g/l &nbsp;
  							<?php echo "le ".$CardioVasculaireDepart->dHDL;?></td>
  	</tr>
	<tr>
		<td>LDL Cholestérol</Td>
  			<td colspan='2'><?php echo $CardioVasculaireDepart->LDL;?>g/l &nbsp;
  							<?php echo "le ".$CardioVasculaireDepart->dLDL;?></td>
	</tr>
  	<tr>
  		<td>Triglycérides</Td>
  			<td colspan='2'><?php echo $CardioVasculaireDepart->triglycerides;?>g/l &nbsp;
  							<?php echo "le ".$CardioVasculaireDepart->dtriglycerides;?></td>
  	</tr>
	<tr>
		<td>Traitement hypolipémiant médicamenteux</td>
			<td colspan='2'>
				<table border='0'>
					<tr>
						<td valign='top'>nom de molécule <br>
			<?php 
				for($i=0;$i<count($CardioVasculaireDepart->traitement);$i++){
					echo($hypolemiantArray[$CardioVasculaireDepart->traitement[$i]]);
					echo("<br>");
				}
			?>						
			 <img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>liste_traitement.html','','width=350,height=650,top=60,left=500,scrollbars=yes,resizable=yes')" alt='Correspondance médicament / molécule' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
						
										<td>dosage :
			<?php echo $CardioVasculaireDepart->dosage;?></Td>
					</tr>
				</table>
	
			</td>
	</Tr>
  </table>
  <br>
  <b>Tension</b>
  <table border='1' width='670'>
  	<tr>
  		<td width='300'>HTA (Dernier chiffres de tension)</Td>
  			<td colspan='2'><?php echo $CardioVasculaireDepart->HTA; ?> 
  				(<?php echo $CardioVasculaireDepart->TaSys;?>/
			<?php echo $CardioVasculaireDepart->TaDia;?>mmHg 
				&nbsp;le 
			<?php echo $CardioVasculaireDepart->dTA;?> )</td>
  	</tr>
	<tr>
		<td>Trois Traitements hypertenseurs ou plus ?
			<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>hta_resistante.html','','width=250,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Définition HTA résistante' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		
		 <br>
			<table border="0" cellpadding="0" cellspacing="0" id='tab_hypertenseur1' <?php
				if($CardioVasculaireDepart->hypertenseur3=="non") echo "style='display:none'";?>>
			<tr>
				<td>Si oui (hta sévère) présence d'une automesure</td>
			</tr>
			<tr>
				<td>et présence d'un diurétique</td>
			</tr>
			</table>
			</td>
			<td colspan='2'>
			<?php echo $CardioVasculaireDepart->hypertenseur3; ?>
			  			<br><br>
			<table border="0" cellpadding="0" cellspacing="0" id='tab_hypertenseur2' <?php
				if($CardioVasculaireDepart->hypertenseur3=="non") echo "style='display:none'";?>>
			<tr>
				<td>
				<?php echo $CardioVasculaireDepart->automesure;?>
				</td>
			</tr>
			<tr>
				<td>
			<?php echo $CardioVasculaireDepart->diuretique;?></td>
			</table>
			</td>
	</tr>
  	<tr>
  		<td>Echocardiogramme Hypertrophie Ventriculaire Gauche</td>
  			<td colspan='2'>
			<?php echo $CardioVasculaireDepart->HVG; ?></td>
  	</tr>
  	<tr>
  		<td>A défaut Surcharge ventriculaire gauche</td>
  			<td colspan='2'>
			<?php echo $CardioVasculaireDepart->surcharge_ventricule; ?>&nbsp;&nbsp;
			  				Sokolov : <?php echo $CardioVasculaireDepart->sokolov;?>mm &nbsp; 
							  le <?php echo $CardioVasculaireDepart->dsokolov;?></td>
  	</tr>
  </table>
  <br>

  <table border="1" width='670' <?php if($CardioVasculaireDepart->HTA=="non") echo "style='display:none'";?> >
  <tr>
    <td width='300'>Créatinine</td>
    <td>
	<?php echo $CardioVasculaireDepart->Creat;?>mg<br>
	<table border="0">
	<tr>
	    <td>Clearance calculée : </td>
	    <td>&nbsp;<?php echo($CardioVasculaireDepart->getClearance($dossier));?>ml/mn</td>
	</tr>
 </table>
    <td><?php echo $CardioVasculaireDepart->dCreat;?></td>
  </tr>
  <tr>
    <td>Kaliémie</td>
    <td><?php echo $CardioVasculaireDepart->kaliemie;?>mmol/l</td>
    <td><?php echo $CardioVasculaireDepart->dkaliemie;?></td>
  </tr>
  <tr>
    <td>Protéinurie</td>
    <td><?php echo $CardioVasculaireDepart->proteinurie=="1"?"Positive":"Négative";?>
		</td>
    <td><?php echo $CardioVasculaireDepart->dproteinurie;?></td>
  </tr>
  <tr>
    <td>Hématurie</td>
    <td><?php echo $CardioVasculaireDepart->hematurie=="1"?"Positive":"Négative";?>
		</td>
    <td><?php echo $CardioVasculaireDepart->dhematurie;?></td>
  </tr>
  <tr>
    <td>Fond d'&oelig;il</td>
    <td><?php echo $CardioVasculaireDepart->dFond;?></td>
  </tr>
  <td>ECG</td>
    <td><?php echo $CardioVasculaireDepart->dECG;?></td>
  </tr>
  </table>
  
  <br>
  <b>Mode de vie</b>
  <table border='1' width='670'>
  	<tr>
  		<td width='300'>Tabagisme<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>tabac.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Tabac' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
  			<td colspan='2'>
			<?php echo $CardioVasculaireDepart->tabac; ?><br>
			  Date d'arrêt : 
			  <?php echo $CardioVasculaireDepart->darret;?>
			</Td>
  	</tr>
  	<tr>
  		<td>Poids</td>
			<td>
			<?php echo $CardioVasculaireDepart->poids;?>kg. &nbsp; 
			le <?php echo $CardioVasculaireDepart->dpoids;?>&nbsp;&nbsp; 
			Taille : <?php echo $dossier->taille;?><br>
				<table><tr><td id='imc'>IMC : <?php echo($CardioVasculaireDepart->getIMC($dossier->taille));?></td></tr></table> </Td>
  	</tr>
	<tr>
		<td>Activité physique (heures par semaine)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>activite.html','','width=250,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Définition activité physique' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
			<td colspan='2'><?php echo $CardioVasculaireDepart->activite;?>h</Td>
	</Tr>
	<tr>
		<td>Fréquence cardiaque</td>
			<td colspan='2'><?php echo $CardioVasculaireDepart->pouls;?>/min &nbsp; 
			le <?php echo $CardioVasculaireDepart->dpouls;?></td>
	</Tr>
	</tr>
	<tr>
		<td>Alcool (>20g/j)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>alcool.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Alcool' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
			<td colspan='2'>
			<?php echo $CardioVasculaireDepart->alcool; ?></td>
	</tr>
	</table>
	<br>

  	<b>Facteurs associés à prendre en charge</b>
  <table border='1' width='700'>
	<tr>
		<td width='300'>Glycémie</td>
			<td><?php echo $CardioVasculaireDepart->glycemie;?>g/l &nbsp; le 
			<?php echo $CardioVasculaireDepart->dgly;?></td>
	</tr>
	<tr>
		<td>Examen cardio-vasculaire <img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>def_examcardio.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Examen cardio-vasculaire' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
			<td>
			<?php echo $CardioVasculaireDepart->exam_cardio;?></td>
	</tr>
  </table>

	<br>

  <table border='1' width='700'>
	<tr>
		<td width='300'>Sortir du protocole</td>
			<td><?php echo $CardioVasculaireDepart->sortir_rappel=='1'?'oui':'non';?></td>
	</tr>
	<tr>
		<td>Raison de la sortie</td>
			<td>
			<?php echo $CardioVasculaireDepart->raison_sortie;?></td>
	</tr>
  </table>
  <br><br>

<table border="0">
  <tr>
    <td> 
		<?php customSubmitWithAlert("value='Supprimer ce suivi'",ACTION_DELETE,"",$param->controler,NULL,"Voulez vous réellement supprimer cette réponse ?"); ?>
	 </td> 
    <td> <?php customSubmit("value='Modifier ce suivi'",ACTION_FIND,array(PARAM_EDIT),$param->controler); ?>
         <?php customSubmit("value='Faire un autre suivi'",ACTION_MANAGE,"",$param->controler); ?></td>
  </tr> 
</table> 

</form>
