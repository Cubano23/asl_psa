<?php global $CardioVasculaireList; ?>
<?php global $dossier; ?>

<?php
$hypolemiantArray = array(""=>"","Atorvastatine"=>"Atorvastatine",
				  "Fluvastatine"=>"Fluvastatine",
				  "Pravastatine"=>"Pravastatine",
				  "Rosuvastatine"=>"Rosuvastatine",
				  "Simvastatine"=>"Simvastatine",
				  "Simvastatine_ezetimibe"=>"Simvastatine + ezetimibe",
				  "Ezetimibe"=>"Ezetimibe",
				  "Bezafibrate"=>"Bezafibrate",
				  "Ciprofibrate"=>"Ciprofibrate", 
				  "Fenofibrate"=>"Fenofibrate",
				  "Gemfibrozil"=>"Gemfibrozil",
				  "Cholestyramine"=>"Cholestyramine",
				  "Colestipol"=>"Colestipol",
				  "Tiadenol"=>"Tiadenol",
				  "Benfluorex"=>"Benfluorex",
				  "atorvastatine_ezetimibe" => "Atorvastatine, Ezetimibe"); ?> 
 

 <table border=1>
  <tr align='center'>
    <th>Date</td>
    <th>Réponses</td>
  </tr>
  </tr>
  <?php

    for($j=0;$j<count($CardioVasculaireList);$j++){

  		$tmphisto = $CardioVasculaireList[$j]; ?>

	  <tr>
	    <td>
	        <?php echo $tmphisto->date;?>
		</td>
		<td><a href='#cardio_<?php echo $tmphisto->date; ?>' onclick="affiche_detail('cardio<?php echo $tmphisto->date; ?>')">Afficher/masquer les détails</a>
	  <tr style="display:none" id="cardio<?php echo $tmphisto->date; ?>">
	    <td>
			<?php echo $tmphisto->date; /*$additionalParams = array("Dossier:dossier:id"=>$tmphisto->id,
							"DepistageCancerSein:DepistageCancerSein:date"=>$tmphisto->date);
					buildLink("target='_blank'",$tmphisto->date,"$path/controler/ActionControler.php","DepistageCancerSeinControler",ACTION_FIND,array(PARAM_VIEW,PARAM_SYSTEMATIQUE),$additionalParams);*/
			?>
			&nbsp;
		</td>
		<td>

  <br>
  <b>Facteurs de risque non modifiables</b>
  <table border=1 width='670'>
  	<tr>
  		<td width='300'>Antécédents familiaux du premier degré (accident vasculaire avant 55 ans chez les hommes et 
		  	65 ans chez les femmes)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>def_cardio.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Définition accident cardio-vasculaire' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
		  	<td colspan='2'><?php echo $tmphisto->antecedants ?>
		</td>
	</tr>
  </table>
  <br>
  <b>Mode de vie</b>
  <table border='1' width='670'>
  	<tr>
  		<td width='300'>Tabagisme<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>tabac.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Tabac' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
  			<td colspan='2'>
			<?php echo $tmphisto->tabac; ?><br>
			  Date d'arrêt : 
			  <?php echo $tmphisto->darret;?>
			</Td>
  	</tr>
  	<tr>
  		<td>Poids</td>
			<td>
			<?php echo $tmphisto->poids;?>kg. &nbsp; 
			le <?php echo $tmphisto->dpoids;?>&nbsp;&nbsp; 
			Taille : <?php echo $dossier->taille;?><br>
				<table><tr><td id='imc'>IMC : <?php echo($tmphisto->getIMC($dossier->taille));?></td></tr></table> </Td>
  	</tr>
	<tr>
		<td>Activité physique (heures par semaine)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>activite.html','','width=250,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Définition activité physique' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
			<td colspan='2'><?php echo $tmphisto->activite;?>h</Td>
	</Tr>
	<tr>
		<td>Alcool (>20g/j)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>alcool.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Alcool' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
			<td colspan='2'>
			<?php echo $tmphisto->alcool; ?></td>
	</tr>
	</table>
	<br>
  <b>Bilan lipidique</b>
  <table border='1' width='670'>
  	<tr>
  		<td>Cholestérol total</td>
  			<td colspan='2'><?php echo $tmphisto->Chol;?>g/l &nbsp;
  							<?php echo "le ".$tmphisto->dChol;?></td>
  	</tr>
  	<tr>
  		<td>HDL Cholestérol</td>
  			<td colspan='2'><?php echo $tmphisto->HDL;?>g/l &nbsp;
  							<?php echo "le ".$tmphisto->dHDL;?></td>
  	</tr>
	<tr>
		<td>LDL Cholestérol</Td>
  			<td colspan='2'><?php echo $tmphisto->LDL;?>g/l &nbsp;
  							<?php echo "le ".$tmphisto->dLDL;?></td>
	</tr>
  	<tr>
  		<td>Triglycérides</Td>
  			<td colspan='2'><?php echo $tmphisto->triglycerides;?>g/l &nbsp;
  							<?php echo "le ".$tmphisto->dtriglycerides;?></td>
  	</tr>
	<tr>
		<td width='300'>Glycémie</td>
			<td><?php echo $tmphisto->glycemie;?>g/l &nbsp; le 
			<?php echo $tmphisto->dgly;?></td>
	</tr>
	</table>
	<br>
  <table border="1" width='670' <?php if($tmphisto->HTA=="non") echo "style='display:none'";?> >
  <tr>
    <td width='300'>Créatinine</td>
    <td>
	<?php echo $tmphisto->Creat;?>mg<br>
	<table border="0">
	<tr>
	    <td>Clearance calculée : </td>
	    <td>&nbsp;<?php echo($tmphisto->getClearance($dossier));?>ml/mn</td>
	</tr>
 </table>
    <td><?php echo $tmphisto->dCreat;?></td>
  </tr>
  <tr>
    <td>Kaliémie</td>
    <td><?php echo $tmphisto->kaliemie;?>mmol/l</td>
    <td><?php echo $tmphisto->dkaliemie;?></td>
  </tr>
  <tr>
    <td>Protéinurie</td>
    <td><?php echo $tmphisto->proteinurie=="1"?"Positive":"Négative";?>
		</td>
    <td><?php echo $tmphisto->dproteinurie;?></td>
  </tr>
  <tr>
    <td>Hématurie</td>
    <td><?php echo $tmphisto->hematurie=="1"?"Positive":"Négative";?>
		</td>
    <td><?php echo $tmphisto->dhematurie;?></td>
  </tr>
  <tr>
    <td>Fond d'&oelig;il</td>
    <td><?php echo $tmphisto->dFond;?></td>
  </tr>
  </table>
  
  <br>
  <table border='0'>
	<tr>
		<td width='300'>Traitement hypolipémiant médicamenteux</td>
			<td colspan='2'>
				<table border='0'>
					<tr>
						<td valign='top'>nom de molécule <br>
			<?php 
				for($i=0;$i<count($tmphisto->traitement);$i++){
					echo($hypolemiantArray[$tmphisto->traitement[$i]]);
					echo("<br>");
				}
			?>						
			 <img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>liste_traitement.html','','width=350,height=650,top=60,left=500,scrollbars=yes,resizable=yes')" alt='Correspondance médicament / molécule' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
						
										<td>dosage :
			<?php echo $tmphisto->dosage;?></Td>
					</tr>
				</table>
	
			</td>
	</Tr>
  </table>
  <br>
  <b>Tension</b>
  <table border='1' width='670'>
	<tr>
		<td>Fréquence cardiaque</td>
			<td colspan='2'><?php echo $tmphisto->pouls;?>/min &nbsp; 
			le <?php echo $tmphisto->dpouls;?></td>
	</Tr>
  	<tr>
  		<td width='300'>HTA (Dernier chiffres de tension)</Td>
  			<td colspan='2'><?php echo $tmphisto->HTA; ?> 
  				(<?php echo $tmphisto->TaSys;?>/
			<?php echo $tmphisto->TaDia;?>mmHg 
				&nbsp;le 
			<?php echo $tmphisto->dTA;?> )</td>
  	</tr>
	<tr>
		<td>Trois Traitements anti-hypertenseurs ou plus ?
			<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>hta_resistante.html','','width=250,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Définition HTA résistante' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31">
		
		 <br>
			<table border="0" cellpadding="0" cellspacing="0" id='tab_hypertenseur1' <?php
				if($tmphisto->hypertenseur3=="non") echo "style='display:none'";?>>
			<tr>
				<td>Si oui (hta sévère) présence d'une automesure</td>
			</tr>
			<tr>
				<td>et présence d'un diurétique</td>
			</tr>
			</table>
			</td>
			<td colspan='2'>
			<?php echo $tmphisto->hypertenseur3; ?>
			  			<br><br>
			<table border="0" cellpadding="0" cellspacing="0" id='tab_hypertenseur2' <?php
				if($tmphisto->hypertenseur3=="non") echo "style='display:none'";?>>
			<tr>
				<td>
				<?php echo $tmphisto->automesure;?>
				</td>
			</tr>
			<tr>
				<td>
			<?php echo $tmphisto->diuretique;?></td>
			</table>
			</td>
	</tr>
  	<tr>
  		<td>Echocardiogramme Hypertrophie Ventriculaire Gauche</td>
  			<td colspan='2'>
			<?php echo $tmphisto->HVG; ?></td>
  	</tr>
	<tr>
		<td>ECG</td>
	    <td><?php echo $tmphisto->dECG;?></td>
	</tr>
  	<tr>
  		<td>A défaut Surcharge ventriculaire gauche</td>
  			<td colspan='2'>
			<?php echo $tmphisto->surcharge_ventricule; ?>&nbsp;&nbsp;
			  				Sokolov : <?php echo $tmphisto->sokolov;?>mm &nbsp; 
							  le <?php echo $tmphisto->dsokolov;?></td>
  	</tr>
	<tr>
		<td>Examen cardio-vasculaire <img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>def_examcardio.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Examen cardio-vasculaire' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
			<td>
			<?php echo $tmphisto->exam_cardio;?></td>
	</tr>
  </table>
  <br>


  <b>Indicateurs d'objectifs</b>
  <table border='1' width='700'>
  	<tr>
  		<td width='300'>Valeur du RCVA</td>
  			<td><?php echo ($tmphisto->get_rcva($dossier->sexe, $dossier->getAge(), $suividiab));?></td>
  	</Tr>
  </Table>
  <br>
  <table border='1' width='700'>
	<tr>
		<td width='300'>Sortir du protocole</td>
			<td><?php echo $tmphisto->sortir_rappel=='1'?'oui':'non';?></td>
	</tr>
	<tr>
		<td>Raison de la sortie</td>
			<td>
			<?php echo $tmphisto->raison_sortie;?></td>
	</tr>
  </table>
  <br><br>

		</td>
		</Tr>
<?php
    }
    ?>
    </Table>
