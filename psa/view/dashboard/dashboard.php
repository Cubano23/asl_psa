<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php global $account;?>
<?php global $FicheCabinet; ?>
<?php global $param;?>
<?php global $objExams;?>
<?php global $saisieInfirmiere;?>
<?php global $tempsPrepaBilanConsultation ?>
<?php global $examsDerogatoire ?>
<?php
	global $nb_exam_realises; 
	global $nb_exam_saisis;
?>
<?php global $const_demi_jour;?>
<?php global $const_objectif_consult_jour;?>

<style>
.titre_table{
	font-size: 12px;
	color: #009900;
	padding-top: 15px;
	font-weight: bold;
	font-size: 13px;
}
.head_table{
	background-color: #8cadae;
	color: #FFFFFF;
}
</style>

<?php
	$aMonth = array ('janvier', 'f�vrier', 'mars', 'avril', 'mai', 'juin', 'juillet', 'ao�t', 'septembre', 'octobre', 'novembre', 'd�cembre'); 
	$month = date("n",strtotime("-1 month"));
	$year = date("Y",strtotime("-1 month"));
?>

Mois concern� : <?php //echo $aMonth[$month - 1].' '.$year ?>
<select name="">
	<option value="2013/09/30" disabled>Septembre 2013</option>
	<option value="2013/09/30" disabled>Ao�t 2013</option>
	<option value="2013/09/30" disabled>Juillet 2013</option>
	<option value="2013/09/30" disabled>Juin 2013</option>
	<option value="2013/09/30" disabled>Mai 2013</option>
	<option value="2013/09/30" selected>Avril 2013</option>
	<option value="2013/09/30" disabled>Mars 2013</option>
	<option value="2013/09/30" disabled>F�vrier 2013</option>
	<option value="2013/09/30" disabled>Janvier 2013</option>
</select>
<br />&nbsp;
<br />
<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<!-- ent�te cabinet -->
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					<td width="150" bgcolor="#eaf1dd">Docteur(s) :</td>
					<td><?php typePropertyValue("FicheCabinet:nom_complet"); ?></td>
				</tr>
				<tr>
					<td bgcolor="#eaf1dd">Infirmi�r(es) :</td>
					<td><?php typePropertyValue("FicheCabinet:infirmiere"); ?></td>
				</tr>
				<tr>
					<td bgcolor="#eaf1dd">Localisation :</td>
					<td><?php typePropertyValue("FicheCabinet:ville"); ?> - <?php typePropertyValue("FicheCabinet:region"); ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- fin ent�te cabinet -->

	<!-- ligne 2 cols -->
	<tr valign="top">
		<td witdh="800">
			<table width="100%" border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td colspan="3" class="titre_table">R�partition de l'activit�</td>
				</tr>
				<tr>
					<td class="head_table">Temps pass�</td>
					<td class="head_table">1/2 journ�e</td>
					<td class="head_table">%</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td>consultation</td>
					<td><?php echo round($objExams->consultation / $const_demi_jour, 2) ?></td>
					<td><?php echo round(($objExams->consultation / $objExams->total) * 100, 0) ?></td>
				</tr>
				<tr>
					<td>gestion dossier</td>
					<td><?php echo round((getPropertyValue("SuiviHebdomadaireTempsPasse:info_asalee") / $const_demi_jour), 2) ?></td>
					<td><?php echo round((getPropertyValue("SuiviHebdomadaireTempsPasse:info_asalee") / $objExams->total) * 100, 0) ?></td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td>concertation</td>
					<td><?php echo round((getPropertyValue("SuiviHebdomadaireTempsPasse:tps_reunion_medecin") / $const_demi_jour), 2) ?></td>
					<td><?php echo round((getPropertyValue("SuiviHebdomadaireTempsPasse:tps_reunion_medecin") / $objExams->total) * 100, 0) ?></td>
				</tr>
				<tr>
					<td>formation</td>
					<td><?php echo round((getPropertyValue("SuiviHebdomadaireTempsPasse:formation") / $const_demi_jour), 2) ?></td>
					<td><?php echo round((getPropertyValue("SuiviHebdomadaireTempsPasse:formation") / $objExams->total) * 100, 0) ?></td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td>contribution Asal�e</td>
					<td><?php echo round((getPropertyValue("SuiviHebdomadaireTempsPasse:tps_contact_tel_patient") / $const_demi_jour), 2) ?></td>
					<td><?php echo round((getPropertyValue("SuiviHebdomadaireTempsPasse:tps_contact_tel_patient") / $objExams->total) * 100, 0) ?></td>
				</tr>
				<tr>
					<td>total</td>
					<td><?php echo round($objExams->total / $const_demi_jour, 2) ?></td>
					<td>100 %</td>
				</tr>
				<tr>
					<td colspan="3" class="titre_table">Actes d�rogatoires</td>
				</tr>
				<tr>
					<td class="head_table" colspan="2">Total</td>
					<td class="head_table"><?php echo ($examsDerogatoire['spiro'] + $examsDerogatoire['cogn'] + $examsDerogatoire['ecg'] + $examsDerogatoire['pied'] + $examsDerogatoire['monofil'] + $examsDerogatoire['autre']) ?></td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td colspan="2">presc. et r�al. spirom�trie</td>
					<td><?php echo $examsDerogatoire['spiro'] ?></td>
				</tr>
				<tr>
					<td colspan="2">presc. et r�al. troubles cognitifs</td>
					<td><?php echo $examsDerogatoire['cogn'] ?></td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td colspan="2">presc. et r�al. ECG</td>
					<td><?php echo $examsDerogatoire['ecg'] ?></td>
				</tr>
				<tr>
					<td colspan="2">presc. et r�al. exam. du pied</td>
					<td><?php echo $examsDerogatoire['pied'] ?></td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td colspan="2">presc. et r�al. monofilament</td>
					<td><?php echo $examsDerogatoire['monofil'] ?></td>
				</tr>
				<tr>
					<td colspan="2">presc. autres examens de suivi de diab�te</td>
					<td><?php echo $examsDerogatoire['autre'] ?></td>
				</tr>
				<tr>
					<td colspan="3" class="titre_table">Nbre examens int�gr�s dans la p�riode</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td colspan="2">Nb examens saisis ou int�gr�s</td>
					<td><?php echo $nb_exam_saisis['nb'] ?></td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td colspan="2">Nb examens r�alis�s</td>
					<td><?php echo $nb_exam_realises['nb'] ?></td>
				</tr>
			</table>
		</td>
		<td width="2">&nbsp;</td>
		<td witdh="">
			<table width="100%" border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td colspan="2" class="titre_table">Nombre de consultations</td>
				</tr>
				<tr>
					<td class="head_table">Jours de consultation du mois :</td>
					<td class="head_table"><?php echo $objExams->nb_jour_consult ?></td>
				</tr>
				<tr>
					<td colspan="2">(hors cong�s, formations et projets transverses)</td>
				</tr>
				<tr>
					<td class="head_table">Nombre de consultations :</td>
					<td class="head_table"><?php echo sizeof($saisieInfirmiere) ?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="head_table">Consultations par jour :</td>
					<td class="head_table"><?php echo round(sizeof($saisieInfirmiere) / $objExams->nb_jour_consult, 2) ?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="head_table">Rapport � l'objectif</td>
					<td class="head_table"><?php echo round(((sizeof($saisieInfirmiere) / $objExams->nb_jour_consult) / $const_objectif_consult_jour) * 100, 2) ?> %</td>
				</tr>
				<tr>
					<td colspan="2" class="titre_table">Patients vus par protocole</td>
				</tr>
				<tr>
					<td class="head_table">Total</td>
					<td class="head_table"><?php echo $objExams->nb_patient ?></td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td>d�pistage diab�te</td>
					<td><?php echo sizeof($objExams->protocoles['dep_diab']) ?></td>
				</tr>
				<tr>
					<td>suivi diab�te</td>
					<td><?php echo sizeof($objExams->protocoles['suivi_diab']) ?></td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td>rcva</td>
					<td><?php echo sizeof($objExams->protocoles['rcva']) ?></td>
				</tr>
				<tr>
					<td>troubles cognitifs</td>
					<td><?php echo sizeof($objExams->protocoles['cognitif']) ?></td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td>bpco</td>
					<td><?php echo sizeof($objExams->protocoles['bpco']) ?></td>
				</tr>
				<!--<tr>
					<td>autre</td>
					<td><?php //echo $objExams->protocoles['autre'] ?></td>
				</tr>-->
				<tr>
					<td>dont patient mutiprotocoles</td>
					<td><?php echo $objExams->nb_multiprotocole ?></td>
				</tr>
				<tr>
					<td colspan="2" class="titre_table">Nouveaux patients</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td>Nouveaux patients du mois</td>
					<td><?php echo $objExams->nb_new ?></td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td>Proportion de nouveaux patients</td>
					<td><?php echo round(($objExams->nb_new / (sizeof($objExams->protocoles['dep_diab']) + sizeof($objExams->protocoles['suivi_diab']) + sizeof($objExams->protocoles['rcva']) + sizeof($objExams->protocoles['cognitif']) + sizeof($objExams->protocoles['bpco']))) * 100, 2) ?> %</td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- fin ligne 2 cols -->
	
	<!-- Evolution HBA1C -->
	<tr>
		<td colspan="3" class="titre_table">Evolution HBA1c</td>
	</tr>
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					<td class="head_table" colspan="2" align="right">Nombre de consultations :</td>
					<td class="head_table">1�re</td>
					<td class="head_table">2�me</td>
					<td class="head_table">3�me</td>
					<td class="head_table">4�me</td>
					<td class="head_table">5�me</td>
					<td class="head_table">6�me</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td rowspan="3" width="250">Patients avec HBA1c > 7%<br />avant la 1�re consultation</td>
					<td>HBA1c avant</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td>HBA1c apr�s</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
				</tr>
				<tr>
					<td>�volution</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td rowspan="3" width="250">Patients avec HBA1c <= 7%<br />avant la 1�re consultation</td>
					<td>HBA1c avant</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td>HBA1c apr�s</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
				</tr>
				<tr>
					<td>�volution</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- fin Evolution HBA1C -->

	<!-- Evolution LDL -->
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					<td class="head_table" colspan="2" align="right">Nombre de consultation :</td>
					<td class="head_table">1�re</td>
					<td class="head_table">2�me</td>
					<td class="head_table">3�me</td>
					<td class="head_table">4�me</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td rowspan="3">Patient avec LDL > 1,3<br />avant la 1�re consultation</td>
					<td>LDL avant</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td>LDL apr�s</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
				</tr>
				<tr>
					<td>�volution</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td rowspan="3">Patient avec LDL <= 1,3<br />avant la 1�re consultation</td>
					<td>LDL avant</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td>LDL apr�s</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
				</tr>
				<tr>
					<td>�volution</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
					<td>XX</td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- fin Evolution LDL -->

	<!-- Evolution tension -->
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					<td colspan="3" class="titre_table">Evolution tension</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td colspan="2">Taux patient > 140/90 avant 1 consultation et passant < 140/90</td>
					<td>XX</td>
				</tr>
				<tr>
					<td colspan="2">Taux patient > 140/90 avant 2 consultations et passant < 140/90</td>
					<td>XX</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td colspan="2">Taux patient > 140/90 avant 3 consultations et passant < 140/90</td>
					<td>XX</td>
				</tr>
				<tr>
					<td colspan="2">Taux patient > 140/90 avant 4 consultations et passant < 140/90</td>
					<td>XX</td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- fin Evolution tension -->

	<!-- EFR et d�tection des troubles cognitifs -->
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					<td colspan="3" class="titre_table">EFR d�tection des troubles cognitifs</td>
				</tr>
				<tr bgcolor="#eaf1dd">
					<td colspan="2">Taux de patients �g�es auxquels a �t� administr� un test des troubles cognitifs</td>
					<td>XX</td>
				</tr>
				<tr>
					<td colspan="2">Taux de patients tabagiques fait l'objet d'une Exploration Fonctionnelle Respiratoire</td>
					<td>XX</td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- fin EFR et d�tection des troubles cognitifs -->
</table>
