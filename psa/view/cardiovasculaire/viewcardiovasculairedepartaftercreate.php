<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $CardioVasculaireDepart; ?>
<?php global $param; ?>
<?php global $suividiab;?>
<?php global $spirometrie;?>
<?php global $liste_historique; ?>
<?php $hypolemiantArray = array("Aucun"=>"Aucun",
				  "Atorvastatine"=>"Atorvastatine",
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
 
<form action='<?php echo("$path/controler/ActionControler.php");?>' method="post" name="manage">
<?php hiddenControler("CardioVasculaireDepartControler"); ?>
<?php hiddenAction(ACTION_FIND); ?>
<?php hiddenParam1(PARAM_EDIT); ?>
<?php hidden("","dossier:numero"); ?>
<?php hidden("","CardioVasculaireDepart:date"); ?>

    <!-- Information concernant les dépistages saisies à partir de ce suivi -->
    <input type="hidden" name="DepistageAOMI:DepistageAOMI:provenance" value="RCVA">
    <input type="hidden" name="DepistageAOMI:DepistageAOMI:dateSaisie" value="<?= $CardioVasculaireDepart->date; ?>">

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
  <b>Bilan tabagique</b>
  <table border='1' width='670'>
	<tr>
		<td width='300' height='50'><font  style=" border-bottom:solid  ; border-color:green ;" >Tabagisme</font><img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>tabac.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Tabac' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
		  <?php
			if($CardioVasculaireDepart->tabac=="")
				$color='style="background:orange"';
			else
				$color="";?>

			<td colspan='2'>
			<font  style=" border-bottom:solid  ; border-color:green ;" >
			<?php echo $CardioVasculaireDepart->tabac;?></font>&nbsp;&nbsp;&nbsp;&nbsp;
		Nbre de paquets-années  &nbsp; <?php echo $CardioVasculaireDepart->nbrtabac;?>&nbsp;&nbsp;<br/>
		Date d'arrêt :
			  <?php echo $CardioVasculaireDepart->darret;?>	
		</td>
	</tr>
	<tr>
		<td height='50'>
			Spirom&eacute;trie
		</td>
		<td colspan='2'>
			<table>
				<tr>
					<td colspan='2'>Date de la spirom&eacute;trie</td>
					<td><?php echo $CardioVasculaireDepart->spirometrie_date;?></td>
				</tr>
				<tr>
					<td>CVF &nbsp;</td>
					<td><?php echo $CardioVasculaireDepart->spirometrie_CVF;?> litres<br/></td>
					<td rowspan='2' style='border:1px solid #919191;padding:5px;'>
						VEMS/CVF<br/>
						<p><b><?php echo round(($CardioVasculaireDepart->spirometrie_VEMS/$CardioVasculaireDepart->spirometrie_CVF), 2)*100;?></b> %</p>
					</td>
				</tr>
				<tr>
					<td>VEMS &nbsp;</td>
					<td><?php echo $CardioVasculaireDepart->spirometrie_VEMS;?> litres<br/></td>
				</tr>
				<tr>
					<td>DEP &nbsp;</td>
					<td><?php echo $CardioVasculaireDepart->spirometrie_DEP;?> litres par seconde<br/></td>
				</tr>
			</table>
			<br/>
			Spirométrie &nbsp;<?php if($CardioVasculaireDepart->spirometrie_status=='a')echo 'anormale';
									if($CardioVasculaireDepart->spirometrie_status=='n')echo 'normale';?><br/>
		</td>
  		
  	</tr>
  </table>
  <br>
  <b>Mode de vie</b>
  <table border='1' width='670'>
<!--   	<tr>
  		<td width='300'>Tabagisme<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>tabac.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Tabac' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></Td>
  			<td colspan='2'>
			<?php echo $CardioVasculaireDepart->tabac; ?> <?php echo ' ';?>
			<?php echo empty($CardioVasculaireDepart->nbrtabac) ? '' : $CardioVasculaireDepart->nbrtabac.' paquets-années';?>
			
			<br>
			  Date d'arrêt :
			  <?php echo $CardioVasculaireDepart->darret;?>
			</Td>
  	</tr> -->
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
		<td>Alcool (>20g/j)<img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>alcool.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Alcool' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
			<td colspan='2'>
			<?php echo $CardioVasculaireDepart->alcool; ?></td>
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
		<td width='300'>Glycémie</td>
			<td><?php echo $CardioVasculaireDepart->glycemie;?>g/l &nbsp; le 
			<?php echo $CardioVasculaireDepart->dgly;?></td>
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
		<td>Fréquence cardiaque</td>
			<td colspan='2'><?php echo $CardioVasculaireDepart->pouls;?>/min &nbsp; 
			le <?php echo $CardioVasculaireDepart->dpouls;?></td>
	</Tr>
  	<tr>
  		<td width='300'>HTA (Dernier chiffres de tension)</Td>
  			<td colspan='2'><?php echo $CardioVasculaireDepart->HTA; ?> 
  				(<?php echo $CardioVasculaireDepart->TaSys;?>/
			<?php echo $CardioVasculaireDepart->TaDia;?>mmHg 
				&nbsp;le 
			<?php echo $CardioVasculaireDepart->dTA;?> )</td>
  	</tr>
	<tr>
		<td>Trois Traitements anti-hypertenseurs ou plus ?
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
		<td>ECG</td>
	    <td><?php echo $CardioVasculaireDepart->dECG;?></td>
	</tr>
  	<tr>
  		<td>A défaut Surcharge ventriculaire gauche</td>
  			<td colspan='2'>
			<?php echo $CardioVasculaireDepart->surcharge_ventricule; ?>&nbsp;&nbsp;
			  				Sokolov : <?php echo $CardioVasculaireDepart->sokolov;?>mm &nbsp; 
							  le <?php echo $CardioVasculaireDepart->dsokolov;?></td>
  	</tr>
	<tr>
		<td>Examen cardio-vasculaire <img OnClick="javascript:window.open('<?php echo "$path/view/cardiovasculaire/"?>def_examcardio.html','','width=250,height=250,top=100,left=500,scrollbars=yes,resizable=yes')" alt='Examen cardio-vasculaire' border=0 height="31" src='<?php echo "$path/view/"?>images/loupe.gif' width="31"></td>
			<td>
			<?php echo $CardioVasculaireDepart->exam_cardio;?></td>
	</tr>
  </table>
  <br>


  <b>Indicateurs d'objectifs</b>
  <table border='1' width='700'>
  	<tr>
  		<td width='300'>Valeur du RCVA</td>
  			<td><?php echo ($CardioVasculaireDepart->get_rcva($dossier->sexe, $dossier->getAge(), $suividiab));?></td>
  	</Tr>
  </Table>
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


    <?php
    if (in_array($account->cabinet, $liste_cabs_aut))
        include_once "view/depistage/historique_depistage_aomi.php";
    ?>
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

