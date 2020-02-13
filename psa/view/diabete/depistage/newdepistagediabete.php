<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/jsgenerator/jsdatefunctions.php"); ?>
<?php require_once("view/jsgenerator/jsnumericfunctions.php"); ?>
<?php require_once("view/common/vars.php") ?>

<?php global $account ?>
<?php global $dossier; ?>
<?php global $depistageDiabete ?>
<?php global $dernierExam;?>
<?php global $poids;?>
<?php global $glycemie;?>

<?php
if($dernierExam!=false){
	$depistageDiabete->parent_diabetique_type2=$dernierExam->parent_diabetique_type2;
	$depistageDiabete->ant_intolerance_glucose=$dernierExam->ant_intolerance_glucose;
	$depistageDiabete->bebe_sup_4kg=$dernierExam->bebe_sup_4kg;
	$depistageDiabete->ant_diabete_gestationnel=$dernierExam->ant_diabete_gestationnel;
	$depistageDiabete->corticotherapie=$dernierExam->corticotherapie;
	$depistageDiabete->infection=$dernierExam->infection;
	$depistageDiabete->intervention_chirugicale=$dernierExam->intervention_chirugicale;
	$depistageDiabete->autre=$dernierExam->autre;
}
?>


<script language="javascript">
	
	<?php compareDates(); ?>
	<?php monthDiffDates(); ?>
	<?php validateDate(); ?>
	<?php dateInRange(); ?>
	
	function updateGlycemie(){
		formate_date(document.getElementById("derniere_gly_date"));
		var derniere_gly_date = document.getElementById("derniere_gly_date");
		var derniere_gly_resultat = document.getElementById("derniere_gly_resultat");
//		var nouvelle_gly_date = document.getElementById("nouvelle_gly_date");
//		var nouvelle_gly_resultat = document.getElementById("nouvelle_gly_resultat");
		var depistageDate = document.getElementById("depistageDate");
		var periode_glycemie = document.getElementById("periode_glycemie");
		var prescription_gly = document.getElementById("prescription_gly");
		var mesure_suivi_diabete = document.getElementById("mesure_suivi_diabete");
		var mesure_suivi_diabete_warn = document.getElementById("mesure_suivi_diabete_warn");
		var controle_annuel = document.getElementById("controle_annuel");
		var controle_annuel_warn = document.getElementById("controle_annuel_warn");
//		var gly_non_applicable = document.getElementById("gly_non_applicable");
		
		derniere_gly_resultat.value=derniere_gly_resultat.value.replace(",",".");
//		nouvelle_gly_resultat.value=nouvelle_gly_resultat.value.replace(",",".");

		var glyDate;
		var glyValue;
		var monthDiff;		
		
//		if(validateDate(derniere_gly_date.value) && !validateDate(nouvelle_gly_date.value)){
		if(validateDate(derniere_gly_date.value)){
		
			glyDate = derniere_gly_date;
			glyValue = derniere_gly_resultat;
			monthDiff = monthDiffDates(depistageDate.value,glyDate.value);
/*			if(monthDiff < 12) 
				gly_non_applicable.value =  " non applicable: dernière glycémie date de moins d'un an";
			else		
				gly_non_applicable.value =  "";*/
		}
/*		else if(validateDate(nouvelle_gly_date.value)){
			glyDate = nouvelle_gly_date;
			glyValue =nouvelle_gly_resultat;
			monthDiff = monthDiffDates(depistageDate.value,glyDate.value);
			if(monthDiff < 12) 
				gly_non_applicable.value =  " non applicable: dernière glycémie date de moins d'un an";		
		}
*/		else return -1;
		
		if(monthDiff < 0) return -1;
		
		periode_glycemie.innerHTML = monthDiff;
		
		if(monthDiff > 12) {
			prescription_gly.checked = true;			
			return -1;
		}
		


		if(parseFloat(glyValue.value) > 1.26){
			controle_annuel.checked = false;
			mesure_suivi_diabete.checked = true;
			controle_annuel_warn.className = "hidden";
			mesure_suivi_diabete_warn.className = "visible";
		}
		else{ if(parseFloat(glyValue.value) > 0){
			if(haveRisks()) controle_annuel.checked = true;
			mesure_suivi_diabete.checked = false;
			if(haveRisks()) controle_annuel_warn.className = "visible";
			mesure_suivi_diabete_warn.className = "hidden";
		}
		}
		
		
		return glyValue;
	}
	
	function checkNoRisks(){
		var controle_annuel = document.getElementById("controle_annuel");
		var controle_annuel_warn = document.getElementById("controle_annuel_warn");
		if(!haveRisks()){
		 	controle_annuel.checked = false;
			controle_annuel_warn.className = "hidden";
		}
		updateGlycemie();
	}
	
	function haveRisks(){
		imcStatus = document.getElementById("imcStatus").checked;
		parent_diabetique_type2 = document.getElementById("parent_diabetique_type2").checked;
		ant_intolerance_glucose = document.getElementById("ant_intolerance_glucose").checked;
		hypertension_arterielle = document.getElementById("hypertension_arterielle").checked;
		dyslipidemie_en_charge = document.getElementById("dyslipidemie_en_charge").checked;
		hdl = document.getElementById("hdl").checked;
		bebe_sup_4kg = document.getElementById("bebe_sup_4kg").checked;
		ant_diabete_gestationnel = document.getElementById("ant_diabete_gestationnel").checked;
		corticotherapie = document.getElementById("corticotherapie").checked;
		infection = document.getElementById("infection").checked;
		intervention_chirugicale = document.getElementById("intervention_chirugicale").checked;
		autre = document.getElementById("autre").checked;
		
		if(imcStatus || parent_diabetique_type2 ||
			ant_intolerance_glucose ||
			hypertension_arterielle ||
			dyslipidemie_en_charge ||
			hdl ||
			bebe_sup_4kg ||
			ant_diabete_gestationnel ||
			corticotherapie ||
			infection ||
			intervention_chirugicale ||
			autre) return true;			
			return false;		
	}
	
	function updateIMC(taille){
		var lowIMC = document.getElementById("lowIMC");
		var highIMC = document.getElementById("highIMC");
		var imcStatus = document.getElementById("imcStatus");
		var imc = document.getElementById("imc");
		var mesure_suivi_hygieno_dietetique = document.getElementById("mesure_suivi_hygieno_dietetique");		
		var poidsField = document.getElementById("poids");
		var poidsValue = parseFloat(poidsField.value);				
		var controle_annuel = document.getElementById("controle_annuel");
		var controle_annuel_warn = document.getElementById("controle_annuel_warn");
		
		if(isNaN(poidsValue) || poidsValue <30 || poidsValue >200){
			imc.innerHTML = "&nbsp;Le poids doit etre compris entre 30 et 200";
			imcStatus.checked = false;
			mesure_suivi_hygieno_dietetique.disabled = false;
			mesure_suivi_hygieno_dietetique.checked = false;
			return;
		}
		
		
		var fTaille = parseFloat(taille);

		if(isNaN(fTaille)) {
			imc.innerHTML = "La taille n'est pas saisie, L'imc ne peut etre calculée";
			imcStatus.checked = false;
			return;
		}
		var imcVal = poidsValue/Math.pow(taille/100, 2);		

		imcVal = imcVal+"";
		var dot = imcVal.indexOf(".");

		if(dot>=0){
			imcVal = imcVal.substr(0,dot+2);
		}
		imc.innerHTML = imcVal; 
		if(imcVal>28){									
			imcStatus.checked = true;
			mesure_suivi_hygieno_dietetique.checked = true;
			mesure_suivi_hygieno_dietetique.disabled = true;						
			updateGlycemie();
			return;
		}
		else{			
			imcStatus.checked = false;
			mesure_suivi_hygieno_dietetique.disabled = false;
			mesure_suivi_hygieno_dietetique.checked = false;
			updateGlycemie();
			return;
		}
	}
	
	

</script>
<script type="text/javascript" >
<?php
	validateDate();
	dateInRange();
	validatePositiveNumeric();
	$js = new JSValidation(); 
	$js->startCheckFunction("validateInput","aForm"); ?>
//	var nouvelle_gly_date = document.getElementById("nouvelle_gly_date");
	var derniere_gly_date = document.getElementById("derniere_gly_date");
//	var nouvelle_gly_resultat = document.getElementById("nouvelle_gly_resultat");
	var derniere_gly_resultat = document.getElementById("derniere_gly_resultat");
	
	var l1 = 0;
	var l2 = 0;

/*	if(nouvelle_gly_date != null) l1 = nouvelle_gly_date.value.length;
	if(nouvelle_gly_resultat != null) l2 = nouvelle_gly_resultat.value.length;
	if(l1  != 0 || l2 !=0 ){
		//alert("1");
		< ?php
		$js->dateInRange("depistageDiabete:nouvelle_gly_date","Date du résultat de la nouvelle glycémie");
		$js->validatePositiveNumeric("depistageDiabete:nouvelle_gly_resultat","Résultat de la nouvelle glycémie");
		?>
	}
*/
	l1 = 0;
	l2 = 0;
	if(derniere_gly_date != null) l1 = derniere_gly_date.value.length;
	if(derniere_gly_resultat != null) l2 = derniere_gly_resultat.value.length;
	
	if(l1 != 0 || l2 !=0 ){
		<?php 
		$js->dateInRange("glycemie:date_exam","Date du résultat de la dernière glycémie connue");
		$js->validatePositiveNumeric("glycemie:resultat1","Résultat de la dernière glycémie connue");
		?>		
	}
	<?php
	
	$js->dateInRange("poids:date_exam","Date du poids");
	$js->validateRange("poids:resultat1",0,200,"Poids");
	$js->dateInRange("dossier:dnaiss","Date de naissance");	
	
	$js->endCheckFunction();	
?>
</script>

<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="aForm">
  <?php hiddenControler("DepistageDiabeteControler"); ?>
  <?php hiddenAction(ACTION_SAVE); ?>
  <?php hidden("id='depistageDate'","depistageDiabete:date");?>
  <?php hidden("","dossier:numero");?>
  <?php hidden("","dossier:id"); ?>
  <?php hidden("","dossier:cabinet"); ?>
  <?php hidden("","poids:id");?>
  <?php hidden("","poids:type_exam");?>
  <?php hidden("","poids:numero");?>
  <?php hidden("","glycemie:id");?>
  <?php hidden("","glycemie:type_exam");?>
  <?php hidden("","glycemie:numero");?>
 <table>
  	<tr>
		<td width="60%" >
	  		<?php require("view/common/dossierresume_modif.php");?>		
		</td>
		<td>
			<table>
  			<tr><td>Saisir:</td></tr>
			<tr><td>1.Les facteurs de risque</td></tr>
			<tr><td>2.La glycémie</td></tr>
			<tr><td>3.Lire les mesures de suivi recommandées</td></tr>
			<tr><td>4.Valider la saisie </td></tr>
			</table>
		</td>
	</tr>
	

  </table>
  <br>
  </br>
  <b>Données complémentaires</b>
  <table width="700" border='1'>
    <tr>
      <td width="200" align="left" scope="row">&nbsp;Poids &nbsp;<?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=poids&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></td>
      <td >&nbsp;<?php text(" size='3' id='poids' onkeyup=\"updateIMC('$dossier->taille')\"","poids:resultat1") ?>&nbsp;kg
		   &nbsp;&nbsp; Le : <?php text(" size='10' id='dpoids' onkeyup='formate_date(this)'","poids:date_exam") ?></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;IMC</td>
      <td id="imc">&nbsp;<?php echo($depistageDiabete->getIMC($dossier->taille)); ?></td>
    </tr>
  </table>
  <br>
  </br>
  <b>1. Facteurs de risques</b>
  <table width="700"  border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td width="63%" align="left" scope="row" >&nbsp;
	  		L'indice de masse corporelle est <label id="highIMC" class="<?php echo($depistageDiabete->getIMC($dossier->taille)>28?"":"hidden"); ?>">  &gt; 28Kg/m<sup>2</sup></label>
	  		<label id="lowIMC" class="<?php echo($depistageDiabete->getIMC($dossier->taille)>28?"hidden":""); ?>"> &gt; 28Kg/m<sup>2</sup></label>
	  </td>
      <td width="37%"><input type="checkbox" id="imcStatus" disabled <?php if($depistageDiabete->getIMC($dossier) > 28) echo(" checked "); ?>/></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;parent (au premier degré) diabétique de type 2</td>
      <td><?php checkBox("id ='parent_diabetique_type2' onclick='checkNoRisks()'","depistageDiabete:parent_diabetique_type2","1")?></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;Ant&eacute;c&eacute;dents personnels d'intol&eacute;rance au glucose<br>
        <i>&nbsp;(glyc&eacute;mie &agrave; jeun entre 1,10 et 1,26 g/l)</i></td>
      <td><?php checkBox("id='ant_intolerance_glucose'  onclick='checkNoRisks()' ","depistageDiabete:ant_intolerance_glucose","1"); ?></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;Hypertension art&eacute;rielle >= 140/90 ou HTA trait&eacute;e</td>
      <td><?php checkBox("id = 'hypertension_arterielle' onclick='checkNoRisks()' ","depistageDiabete:hypertension_arterielle","1"); ?></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;Dyslipid&eacute;mie</td>
      <td><?php checkBox("id ='dyslipidemie_en_charge'  onclick='checkNoRisks()'","depistageDiabete:dyslipidemie_en_charge","1"); ?></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;HDL &lt;= <?php echo($dossier->sexe=='F'? '0,40' : '0,35');?>g/l ou triglycérides &gt; 1,8mg/l</td>
      <td><?php checkBox("id = 'hdl'  onclick='checkNoRisks()'","depistageDiabete:hdl","1"); ?></td>
    </tr>
    <tr <?php if($dossier->sexe=="M") echo("style=display:none") ?> >
      <td align="left" scope="row">&nbsp;A eu un b&eacute;b&eacute; de poids de naissance &gt; &agrave; 4 kg</td>
      <td><?php checkBox("id = 'bebe_sup_4kg'  onclick='checkNoRisks()'","depistageDiabete:bebe_sup_4kg","1"); ?></td>
    </tr>
    <tr <?php if($dossier->sexe=="M") echo("style=display:none") ?> >
      <td align="left" scope="row">&nbsp;Ant&eacute;c&eacute;dents de diab&egrave;te gestationnel</td>
      <td><?php checkBox("id = 'ant_diabete_gestationnel'  onclick='checkNoRisks()'","depistageDiabete:ant_diabete_gestationnel","1"); ?></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;Diab&egrave;te transitoire dans d'autres circonstances</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;* corticothérapie</td>
      <td><?php checkBox("id='corticotherapie'  onclick='checkNoRisks()'","depistageDiabete:corticotherapie","1"); ?></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;* infection</td>
      <td><?php checkBox("id='infection'  onclick='checkNoRisks()'","depistageDiabete:infection","1"); ?></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;* intervention chirurgicale</td>
      <td><?php checkBox("id='intervention_chirugicale'  onclick='checkNoRisks()'","depistageDiabete:intervention_chirugicale","1"); ?></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;* autre</td>
      <td><?php checkBox("id='autre'  onclick='checkNoRisks()'","depistageDiabete:autre","1"); ?></td>
    </tr>
  </table>
  <br>
  </br>
  <b>2. Glycémie</b>
  <table width="75%"  border="1" cellspacing="0" cellpadding="0">
    <tr>
      <th colspan="2"><b>Dernière glycémie connue au dossier:</b><br>
        <i>(si absente, laisser le champ vide)</i> &nbsp;<?php echo "<a href='javascript://' onmouseover=\"ajax_showTooltip('$path/controler/ActionControler.php?controlerparams:param:controler=HistoBiologieControler&controlerparams:param:action=AL&Biologie:Biologie:type_exam=glycemie&dossier:dossier:id=$dossier->id',this);return false\" onmouseout=\"ajax_hideTooltip()\">Résultats passés</a>";?></th>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;Date</td>
      <td>&nbsp;
        <?php text("id='derniere_gly_date' onkeyup='updateGlycemie()'","glycemie:date_exam");?></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;Résultat</td>
      <td>
      	&nbsp;<?php text("size='4' id='derniere_gly_resultat' onkeyup='updateGlycemie()'","glycemie:resultat1");?>&nbsp;g/l
      	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="cursor:pointer;" OnClick="javascript:window.open('<? echo($path)?>/view/diabete/suivi/equivalence_glycemie.html','','width=350,height=200,top=100,left=500,scrollbars=yes,resizable=yes')">Equivalence mmol/g/l</a>
      </td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;Prescrire nouvelle glycémie</td>
      <td>&nbsp;
        <?php checkbox("id='prescription_gly'","depistageDiabete:prescription_gly","1");?>
        <i>dernière glycémie:</i><label id='periode_glycemie'><? echo($depistageDiabete->getDateDiff()); ?></label>&nbsp;mois</td>
    </tr>
<!--    <tr>
      <th  colspan="2">&nbsp;Résultat de la nouvelle glycémie</th>
    </tr>
    <tr>
      <th align="left" scope="row">&nbsp;Date</th>
      <td>&nbsp;
        <?php text("id='nouvelle_gly_date' onkeyup='updateGlycemie()'","depistageDiabete:nouvelle_gly_date");?></td>
    </tr>
    <tr>
      <th align="left" scope="row">&nbsp;Résultat</th>
      <td>&nbsp;
        <?php text("size='4' id='nouvelle_gly_resultat' onkeyup='updateGlycemie()'","depistageDiabete:nouvelle_gly_resultat");?>        
        g/l</td>
    </tr>
    <tr>
      <th align="left" scope="row" colspan="2"> &nbsp;Si la glycémie n'a pas été faite: pourquoi ? <br>
        <?php textArea("id='gly_non_applicable' cols='60' onFocus='controle_glycemie(this.form)'","depistageDiabete:note_gly"); ?>
        </br>
      </th>
    </tr>-->
  </table>
  <br>
  </br>
  <b>3. Mesures de suivi recommandées</b>
  <table width="700"  border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td align="left" scope="row" width='200'>&nbsp;Diabète</td>
      <td>&nbsp;
        <?php checkBox("id='mesure_suivi_diabete'","depistageDiabete:mesure_suivi_diabete","1"); ?><label id = "mesure_suivi_diabete_warn" class="<?  echo($depistageDiabete->isDiabetic() == true?"":"hidden"); ?>">Suivi du diabète</label>
	  </td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;Contrôle annuel</td>
      <td>&nbsp;
        <?php checkBox("id='controle_annuel'","depistageDiabete:mesure_suivi_controle_annuel","1"); ?>&nbsp;<label id = "controle_annuel_warn" class="<?  echo($depistageDiabete->isDiabetic() === false?"":"hidden") ?>">Glycémie &lt; 1.26</label>
       </td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;Mesures hygiéno-diététique</td>
      <td>&nbsp;
        <?php $checked = $depistageDiabete->getIMC($dossier->taille)>28?"checked":"" ?>        
        <?php checkBox("id='mesure_suivi_hygieno_dietetique' $checked","depistageDiabete:mesure_suivi_hygieno_dietetique","1"); ?>
       </td>
    </tr>
  </table>
  <br>
    <table border=0>
    <tr>
      <td width='180'>Sortir cette personne du dépistage diabète</td>
	      <td><?php checkBox("","depistageDiabete:sortir_rappel","1"); ?></td>
	</tr>
	<tr>
		<td>Raison : </td>
		    <td><?php textArea("rows=\"3\" cols=\"30\" ","depistageDiabete:raison_sortie"); ?></td>
    </tr>
  </table>

  <br>
  <b>4. Valider la saisie</b>
  <table width="75%"  border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center">
	  	<input type="button" name="Submit" value="Valider la saisie" onclick="validateInput()">
        <input type="reset" name="Submit2" value="Recommencer">
	  </td>
    </tr>
  </table>
</form>
