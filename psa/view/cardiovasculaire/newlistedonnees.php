<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>

<?php global $account;?>
<?php global $dossier ?>
<?php global $ListeDonnees; ?>
<?php global $rowsList;?>


<script type="text/javascript" >
function change_couleur(numero, donnees){
	donnees=document.getElementById(donnees);
	if(numero==0){
		donnees.style.backgroundColor.color='';
	}
	if(numero==1){
		donnees.style.backgroundColor="green";
	}
	if(numero==2){
		donnees.style.backgroundColor='orange';
	}
	if(numero==3){
		donnees.style.backgroundColor='red';
	}
}

</SCRIPT>
<script type="text/javascript" >


<?php
	compareDates();
	dateInRange();
	validateDate();	
	validatePositiveNumeric();
	validateNumeric();
	
	$js = new JSValidation();
	
	$js->startCheckFunction("validateInput","saveForm");
?>
	


<?php
	$js->endCheckFunction();
?>

</script>

<?php
$valeurs = array(""=>"",
				  "vert"=>"La rubrique existe dans le logiciel et est renseignée",
				  "orange"=>"La rubrique existe mais est pas ou peu renseignée",
				  "rouge"=>"La rubrique n'existe pas"); ?> 
 
<form action=<?php echo("'$path/controler/ActionControler.php'"); ?> method='post' name='saveForm'> 
	<?php hiddenControler("ListeDonneesControler"); ?>
	<?php hiddenAction(ACTION_SAVE); ?>
	<?php hidden("","ListeDonnees:cabinet");?>
		


  <br>
  <b>Facteurs de risque non modifiables</b>
  <table border=1 width='700'>
  	<tr>
  		<td width='300'>Antécédents familiaux du premier degré (accident vasculaire avant 55 ans chez les hommes et 
		  	65 ans chez les femmes)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>def_cardio.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Définition accident cardio-vasculaire' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
		  	<td colspan='2' id='menu_ant' <?php if($ListeDonnees->antecedants=='vert') echo "style='Background:green'";
			  									if($ListeDonnees->antecedants=='orange') echo "style='background:orange'";
												if($ListeDonnees->antecedants=='rouge') echo "style='background:red'";?>>
			 <?php selectv("id='antecedants' onchange=\"change_couleur(this.selectedIndex, 'menu_ant')\"","ListeDonnees:antecedants",$valeurs) ?> 
		</td>
	</tr>
  </table>
  <br>
  <b>Bilan lipidique</b>
  <table border='1' width='700'>
  	<tr>
  		<td width='300'>Cholestérol total</td>
  			<td colspan='2'>
  				<table border='0'>
  					<tr>
  						<td id='chol' <?php if($ListeDonnees->Chol=='vert') echo "style='Background:green'";
		  									if($ListeDonnees->Chol=='orange') echo "style='background:orange'";
											if($ListeDonnees->Chol=='rouge') echo "style='background:red'";?>>
						  Résultat de l'analyse <?php selectv("onchange=\"change_couleur(this.selectedIndex, 'chol')\"","ListeDonnees:Chol",$valeurs) ?> 
						</Td>
					</tr>
					<tr>
						<td id='dChol' <?php if($ListeDonnees->dChol=='vert') echo "style='Background:green'";
		  									if($ListeDonnees->dChol=='orange') echo "style='background:orange'";
											if($ListeDonnees->dChol=='rouge') echo "style='background:red'";?>>
							Date de l'analyse 	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dChol')\"","ListeDonnees:dChol",$valeurs) ?>
						</td>
					</Tr> 
				</Table>
  	</tr>
  	<tr>
  		<td width='300'>HDL Cholestérol</td>
  			<td colspan='2'>
  				<table border='0'>
  					<tr>
  						<td id='HDL' <?php  if($ListeDonnees->HDL=='vert') echo "style='Background:green'";
		  									if($ListeDonnees->HDL=='orange') echo "style='background:orange'";
											if($ListeDonnees->HDL=='rouge') echo "style='background:red'";?>>
						  Résultat de l'analyse <?php selectv("onchange=\"change_couleur(this.selectedIndex, 'HDL')\"","ListeDonnees:HDL",$valeurs) ?> 
						</Td>
					</tr>
					<tr>
						<td id='dHDL' <?php if($ListeDonnees->dHDL=='vert') echo "style='Background:green'";
		  									if($ListeDonnees->dHDL=='orange') echo "style='background:orange'";
											if($ListeDonnees->dHDL=='rouge') echo "style='background:red'";?>>
							Date de l'analyse 	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dHDL')\"","ListeDonnees:dHDL",$valeurs) ?>
						</td>
					</Tr> 
				</Table>
			</td>
  	</tr>
	<tr>
		<td width='300'>LDL Cholestérol</Td>
  			<td colspan='2'>
			  
  				<table border='0'>
  					<tr>
  						<td id='LDL' <?php  if($ListeDonnees->LDL=='vert') echo "style='Background:green'";
		  									if($ListeDonnees->LDL=='orange') echo "style='background:orange'";
											if($ListeDonnees->LDL=='rouge') echo "style='background:red'";?>>
						  Résultat de l'analyse <?php selectv("onchange=\"change_couleur(this.selectedIndex, 'LDL')\"","ListeDonnees:LDL",$valeurs) ?> 
						</Td>
					</tr>
					<tr>
						<td id='dLDL' <?php if($ListeDonnees->dLDL=='vert') echo "style='Background:green'";
		  									if($ListeDonnees->dLDL=='orange') echo "style='background:orange'";
											if($ListeDonnees->dLDL=='rouge') echo "style='background:red'";?>>
							Date de l'analyse 	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dLDL')\"","ListeDonnees:dLDL",$valeurs) ?>
						</td>
					</Tr> 
				</Table>
			</td>
	</tr>
  	<tr>
  		<td width='300'>Triglycérides</Td>
  			<td colspan='2'>
			  
  				<table border='0'>
  					<tr>
  						<td id='triglycerides' <?php  if($ListeDonnees->triglycerides=='vert') echo "style='Background:green'";
				  									  if($ListeDonnees->triglycerides=='orange') echo "style='background:orange'";
													  if($ListeDonnees->triglycerides=='rouge') echo "style='background:red'";?>>
						  Résultat de l'analyse <?php selectv("onchange=\"change_couleur(this.selectedIndex, 'triglycerides')\"","ListeDonnees:triglycerides",$valeurs) ?> 
						</Td>
					</tr>
					<tr>
						<td id='dtriglycerides' <?php  if($ListeDonnees->dtriglycerides=='vert') echo "style='Background:green'";
				  									   if($ListeDonnees->dtriglycerides=='orange') echo "style='background:orange'";
													   if($ListeDonnees->dtriglycerides=='rouge') echo "style='background:red'";?>>
							Date de l'analyse 	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dtriglycerides')\"","ListeDonnees:dtriglycerides",$valeurs) ?>
						</td>
					</Tr> 
				</Table>
			</td>
  	</tr>
	<tr>
		<td width='300'>Traitement hypolipidémiant médicamenteux</td>
			<td colspan='2'>
				<table border='0'>
					<tr>
						<td valign='top' id='traitement' <?php  if($ListeDonnees->traitement=='vert') echo "style='Background:green'";
							  									if($ListeDonnees->traitement=='orange') echo "style='background:orange'";
																if($ListeDonnees->traitement=='rouge') echo "style='background:red'";?>>nom de molécule <br>
<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'traitement')\"","ListeDonnees:traitement",$valeurs) ?>
			 </td>
						
										<td id='dosage' <?php   if($ListeDonnees->dosage=='vert') echo "style='Background:green'";
							  									if($ListeDonnees->dosage=='orange') echo "style='background:orange'";
																if($ListeDonnees->dosage=='rouge') echo "style='background:red'";?>>dosage :
		<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dosage')\"","ListeDonnees:dosage",$valeurs) ?>
</Td>
					</tr>
				</table>
	
			</td>
	</Tr>
  </table>
  <br>
  <b>Tension</b>
  <table border='1' width='700'>
  	<tr>
  		<td width='300'>HTA (Dernier chiffres de tension)</Td>
  			<td colspan='2'>
  				<table border='0'>
  					<tr>
  						<td id='HTA' <?php  if($ListeDonnees->HTA=='vert') echo "style='Background:green'";
		  									if($ListeDonnees->HTA=='orange') echo "style='background:orange'";
											if($ListeDonnees->HTA=='rouge') echo "style='background:red'";?>>HTA oui/non : <br>
			  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'HTA')\"","ListeDonnees:HTA",$valeurs) ?>
			  			</td>
			  			<td id='TaSys' <?php  if($ListeDonnees->TaSys=='vert') echo "style='Background:green'";
		  									  if($ListeDonnees->TaSys=='orange') echo "style='background:orange'";
											  if($ListeDonnees->TaSys=='rouge') echo "style='background:red'";?>>
			  			Systole : <br>
			  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'TaSys')\"","ListeDonnees:TaSys",$valeurs) ?>
  				</td>
  				</tr>
  				<tr>
  					<td id='TaDia' <?php  if($ListeDonnees->TaDia=='vert') echo "style='Background:green'";
	  									  if($ListeDonnees->TaDia=='orange') echo "style='background:orange'";
										  if($ListeDonnees->TaDia=='rouge') echo "style='background:red'";?>>
  						Diastole : <br>
			  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'TaDia')\"","ListeDonnees:TaDia",$valeurs) ?>
					</td>
					<td id='dTA' <?php  if($ListeDonnees->dTA=='vert') echo "style='Background:green'";
	  									if($ListeDonnees->dTA=='orange') echo "style='background:orange'";
										if($ListeDonnees->dTA=='rouge') echo "style='background:red'";?>> 
				Date de Tension : <br> 
			  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dTA')\"","ListeDonnees:dTA",$valeurs) ?>
					</td>
					</table>
				</td<
  	</tr>
	<tr>
		<td width='300' valign='top'>
			<table border="0" cellpadding="0" cellspacing="0" >
				<tr>
				<td nowrap>
				Trois Traitements hypertenseurs ou plus ?
				</td>
				</tr>
			<tr>
				<td nowrap>Si oui (hta sévère) présence d'une automesure</td>
			</tr>
			<tr>
				<td nowrap>et présence d'un diurétique</td>
			</tr>
			</table>
			</td>
			<td colspan='2'>
			<table border="0" cellpadding="0" cellspacing="0"  width="100%">
				<tr>
					<td id='hypertenseur3' <?php  if($ListeDonnees->hypertenseur3=='vert') echo "style='Background:green'";
			  									  if($ListeDonnees->hypertenseur3=='orange') echo "style='background:orange'";
												  if($ListeDonnees->hypertenseur3=='rouge') echo "style='background:red'";?>>&nbsp;
			  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'hypertenseur3')\"","ListeDonnees:hypertenseur3",$valeurs) ?>
			  		</Td>
			  	</Tr>
			<tr>
				<td id='automesure'  <?php  if($ListeDonnees->automesure=='vert') echo "style='Background:green'";
		  									if($ListeDonnees->automesure=='orange') echo "style='background:orange'";
											if($ListeDonnees->automesure=='rouge') echo "style='background:red'";?>>&nbsp;
			  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'automesure')\"","ListeDonnees:automesure",$valeurs) ?>
				</td>
			</tr>
			<tr>
				<td id='diuretique' <?php   if($ListeDonnees->diuretique=='vert') echo "style='Background:green'";
		  									if($ListeDonnees->diuretique=='orange') echo "style='background:orange'";
											if($ListeDonnees->diuretique=='rouge') echo "style='background:red'";?>>&nbsp;
			  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'diuretique')\"","ListeDonnees:diuretique",$valeurs) ?>
				</td>
			</table>
			</td>
	</tr>
  	<tr>
  		<td width='300'>Echocardiogramme Hypertrophie Ventriculaire Gauche</td>
				<td id='HVG' <?php  if($ListeDonnees->HVG=='vert') echo "style='Background:green'";
  									if($ListeDonnees->HVG=='orange') echo "style='background:orange'";
									if($ListeDonnees->HVG=='rouge') echo "style='background:red'";?>>&nbsp;
			  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'HVG')\"","ListeDonnees:HVG",$valeurs) ?>
				</td>
  	</tr>
  	<tr>
  		<td width='300'>A défaut Surcharge ventriculaire gauche</td>
  			<td colspan='2'>
  				<table border="0">
  					<tr>
  						<td id='surcharge_ventricule' <?php  if($ListeDonnees->surcharge_ventricule=='vert') echo "style='Background:green'";
						  									 if($ListeDonnees->surcharge_ventricule=='orange') echo "style='background:orange'";
															 if($ListeDonnees->surcharge_ventricule=='rouge') echo "style='background:red'";?>>
						  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'surcharge_ventricule')\"","ListeDonnees:surcharge_ventricule",$valeurs) ?>
							</td>
					</tr>
					<tr>
						<td id='sokolov' <?php  if($ListeDonnees->sokolov=='vert') echo "style='Background:green'";
			  									if($ListeDonnees->sokolov=='orange') echo "style='background:orange'";
												if($ListeDonnees->sokolov=='rouge') echo "style='background:red'";?>> Valeur sokolov : 
						  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'sokolov')\"","ListeDonnees:sokolov",$valeurs) ?>
			  			</td>
			  		</Tr>

					<tr>
						<td id='dsokolov' <?php  if($ListeDonnees->dsokolov=='vert') echo "style='Background:green'";
			  									 if($ListeDonnees->dsokolov=='orange') echo "style='background:orange'";
												 if($ListeDonnees->dsokolov=='rouge') echo "style='background:red'";?>> Date sokolov : 
						  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dsokolov')\"","ListeDonnees:dsokolov",$valeurs) ?>
			  			</td>
			  		</Tr>
				</Table>
  	</tr>
  </table>
  <br>

  <table border="1" width='700' >
  <tr>
    <td width='300'>Créatinine</td>
    <td id='Creat' <?php  if($ListeDonnees->Creat=='vert') echo "style='Background:green'";
						  if($ListeDonnees->Creat=='orange') echo "style='background:orange'";
						  if($ListeDonnees->Creat=='rouge') echo "style='background:red'";?>>
    valeur : <br>
  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'Creat')\"","ListeDonnees:Creat",$valeurs) ?>	
  	</Td>
    <td id='dCreat' <?php   if($ListeDonnees->dCreat=='vert') echo "style='Background:green'";
							if($ListeDonnees->dCreat=='orange') echo "style='background:orange'";
							if($ListeDonnees->dCreat=='rouge') echo "style='background:red'";?>>
    Date : <br>
  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dCreat')\"","ListeDonnees:dCreat",$valeurs) ?>	
	
	</td>
  </tr>
  <tr>
    <td width='300'>Kaliémie</td>
    <td id='kaliemie' <?php  if($ListeDonnees->kaliemie=='vert') echo "style='Background:green'";
							 if($ListeDonnees->kaliemie=='orange') echo "style='background:orange'";
							 if($ListeDonnees->kaliemie=='rouge') echo "style='background:red'";?>>
    valeur : <br>
  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'kaliemie')\"","ListeDonnees:kaliemie",$valeurs) ?>	
  	</Td>
    <td id='dkaliemie' <?php  if($ListeDonnees->dkaliemie=='vert') echo "style='Background:green'";
							  if($ListeDonnees->dkaliemie=='orange') echo "style='background:orange'";
							  if($ListeDonnees->dkaliemie=='rouge') echo "style='background:red'";?>>
    Date : <br>
  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dkaliemie')\"","ListeDonnees:dkaliemie",$valeurs) ?>	
	
	</td>
  </tr>
  <tr>
    <td width='300'>Protéinurie</td>
    <td id='proteinurie' <?php  if($ListeDonnees->proteinurie=='vert') echo "style='Background:green'";
								if($ListeDonnees->proteinurie=='orange') echo "style='background:orange'";
								if($ListeDonnees->proteinurie=='rouge') echo "style='background:red'";?>>
    Positif/négatif : <br>
  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'proteinurie')\"","ListeDonnees:proteinurie",$valeurs) ?>	
  	</Td>
    <td id='dproteinurie' <?php  if($ListeDonnees->dproteinurie=='vert') echo "style='Background:green'";
								 if($ListeDonnees->dproteinurie=='orange') echo "style='background:orange'";
								 if($ListeDonnees->dproteinurie=='rouge') echo "style='background:red'";?>>
    Date : <br>
  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dproteinurie')\"","ListeDonnees:dproteinurie",$valeurs) ?>	
	
	</td>
  </tr>
  <tr>
    <td width='300'>Hématurie</td>
    <td id='hematurie' <?php    if($ListeDonnees->hematurie=='vert') echo "style='Background:green'";
								if($ListeDonnees->hematurie=='orange') echo "style='background:orange'";
								if($ListeDonnees->hematurie=='rouge') echo "style='background:red'";?>>
    Positif/négatif : <br>
  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'hematurie')\"","ListeDonnees:hematurie",$valeurs) ?>	
  	</Td>
    <td id='dhematurie' <?php   if($ListeDonnees->dhematurie=='vert') echo "style='Background:green'";
								if($ListeDonnees->dhematurie=='orange') echo "style='background:orange'";
								if($ListeDonnees->dhematurie=='rouge') echo "style='background:red'";?>>
    Date : <br>
  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dhematurie')\"","ListeDonnees:dhematurie",$valeurs) ?>	
	
	</td>
  </tr>
  <tr>
    <td width='300'>Fond d'&oelig;il</td>
    <td id='dFond' <?php  if($ListeDonnees->dFond=='vert') echo "style='Background:green'";
						  if($ListeDonnees->dFond=='orange') echo "style='background:orange'";
						  if($ListeDonnees->dFond=='rouge') echo "style='background:red'";?>>
  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dFond')\"","ListeDonnees:dFond",$valeurs) ?>
	  </td>	
  </tr>
  <td width='300'>ECG</td>
    <td id='dECG' <?php  if($ListeDonnees->dECG=='vert') echo "style='Background:green'";
						 if($ListeDonnees->dECG=='orange') echo "style='background:orange'";
						 if($ListeDonnees->dECG=='rouge') echo "style='background:red'";?>>
  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dECG')\"","ListeDonnees:dECG",$valeurs) ?>
	  </td>	
  </tr>
  </table>
  
  <br>
  <b>Mode de vie</b>
  <table border='1' width='700'>
  	<tr>
  		<td width='300'>Tabagisme</Td>
  			<td id='tabac' <?php    if($ListeDonnees->tabac=='vert') echo "style='Background:green'";
									if($ListeDonnees->tabac=='orange') echo "style='background:orange'";
									if($ListeDonnees->tabac=='rouge') echo "style='background:red'";?>>
  			Tabac : oui/non<br>
		  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'tabac')\"","ListeDonnees:tabac",$valeurs) ?></Td>
		  	<td id='darret' <?php   if($ListeDonnees->darret=='vert') echo "style='Background:green'";
  									if($ListeDonnees->darret=='orange') echo "style='background:orange'";
									if($ListeDonnees->darret=='rouge') echo "style='background:red'";?>>
			  Date d'arrêt<br>  
			  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'darret')\"","ListeDonnees:darret",$valeurs) ?>
			</Td>
  	</tr>
  	<tr>
  		<td width='300'>Poids</td>
  			<td id='poids' <?php    if($ListeDonnees->poids=='vert') echo "style='Background:green'";
  									if($ListeDonnees->poids=='orange') echo "style='background:orange'";
									if($ListeDonnees->poids=='rouge') echo "style='background:red'";?>>
  			Poids <br>
		  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'poids')\"","ListeDonnees:poids",$valeurs) ?></td>
  			<td id='dpoids' <?php   if($ListeDonnees->dpoids=='vert') echo "style='Background:green'";
  									if($ListeDonnees->dpoids=='orange') echo "style='background:orange'";
									if($ListeDonnees->dpoids=='rouge') echo "style='background:red'";?>>
  			Date du Poids <br>
		  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dpoids')\"","ListeDonnees:dpoids",$valeurs) ?>
			</Td>

  	</tr>
	<tr>
		<td width='300'>Activité physique (heures par semaine. 2h30=2.5h)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>activite.html','','width=250,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Définition activité physique' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
			<td colspan='2' id='activite' <?php  if($ListeDonnees->activite=='vert') echo "style='Background:green'";
			  									 if($ListeDonnees->activite=='orange') echo "style='background:orange'";
												 if($ListeDonnees->activite=='rouge') echo "style='background:red'";?>>
		  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'activite')\"","ListeDonnees:activite",$valeurs) ?>
		</Td>
	</Tr>
	<tr>
		<td width='300'>Fréquence cardiaque</td>
			<td id='pouls' <?php  if($ListeDonnees->pouls=='vert') echo "style='Background:green'";
								  if($ListeDonnees->pouls=='orange') echo "style='background:orange'";
								  if($ListeDonnees->pouls=='rouge') echo "style='background:red'";?>>
			Valeur <br>
		  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'pouls')\"","ListeDonnees:pouls",$valeurs) ?>
			</td>
			<td id='dpouls' <?php   if($ListeDonnees->dpouls=='vert') echo "style='Background:green'";
  									if($ListeDonnees->dpouls=='orange') echo "style='background:orange'";
									if($ListeDonnees->dpouls=='rouge') echo "style='background:red'";?>>
			Date <br>
		  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dpouls')\"","ListeDonnees:dpouls",$valeurs) ?>
			</td>
	</Tr>
	</tr>
	<tr>
		<td width='300'>Alcool (>20g/j)</td>
			<td colspan='2' id='alcool' <?php   if($ListeDonnees->alcool=='vert') echo "style='Background:green'";
			  									if($ListeDonnees->alcool=='orange') echo "style='background:orange'";
												if($ListeDonnees->alcool=='rouge') echo "style='background:red'";?>>
		  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'alcool')\"","ListeDonnees:alcool",$valeurs) ?>
			</td>

	</tr>
	</table>
	<br>

  	<b>Facteurs associés à prendre en charge</b>
  <table border='1' width='700'>
	<tr>
		<td width='300'>Glycémie</td>
			<td id='glycemie' <?php  if($ListeDonnees->glycemie=='vert') echo "style='Background:green'";
  									 if($ListeDonnees->glycemie=='orange') echo "style='background:orange'";
									 if($ListeDonnees->glycemie=='rouge') echo "style='background:red'";?>>
			Valeur <br>
		  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'glycemie')\"","ListeDonnees:glycemie",$valeurs) ?>
			</td>
			<td id='dgly' <?php  if($ListeDonnees->dgly=='vert') echo "style='Background:green'";
								 if($ListeDonnees->dgly=='orange') echo "style='background:orange'";
								 if($ListeDonnees->dgly=='rouge') echo "style='background:red'";?>>
			Date <br>
		  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'dgly')\"","ListeDonnees:dgly",$valeurs) ?>
			</td>
	</tr>
	<tr>
		<td width='300'>Examen cardio-vasculaire <img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>def_examcardio.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Examen cardio-vasculaire' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
			<td id='exam_cardio' <?php  if($ListeDonnees->exam_cardio=='vert') echo "style='Background:green'";
	  									if($ListeDonnees->exam_cardio=='orange') echo "style='background:orange'";
										if($ListeDonnees->exam_cardio=='rouge') echo "style='background:red'";?>>
			Date <br>
		  	<?php selectv("onchange=\"change_couleur(this.selectedIndex, 'exam_cardio')\"","ListeDonnees:exam_cardio",$valeurs) ?>
			</td>
	</tr>
  </table>
  <br>
  
  
  <br><br>
  <input type='button' value='Valider la saisie' onClick="validateInput();">
  <input type='reset' value='Recommencer'> 
</form> 

