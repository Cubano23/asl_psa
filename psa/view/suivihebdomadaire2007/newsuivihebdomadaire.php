<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php global $account; ?>

<?php global $SuiviHebdomadaire2007; ?>
 <table border='1'  cellpadding='0'> 
  <tr> 
     <td><b>Cabinet: </b></td>
     <td><?php echo($account->cabinet) ?></td> 
   </tr> 
  <tr> 
     <td><b>Semaine: </b></td>
     <td><?php echo($SuiviHebdomadaire2007->date); ?> </td>
   </tr> 
</table> 
<br> 
<br> 
<form action="<?php echo("$path/controler/ActionControler.php");?>" method="post" name="manage"> 
  <?php hiddenControler("SuiviHebdomadaire2007Controler"); ?>
  <?php hiddenAction(ACTION_SAVE); ?> 
  <?php hidden("", "SuiviHebdomadaire2007:cabinet"); ?>
  <?php hidden("","SuiviHebdomadaire2007:date");?>
  <table border="1" cellpadding='3'> 
    <tr> 
      <th colspan="2">Tâche</th>
      <th>Nombre</th>
      <th>Durée<br>
        (heures)</th>
    </tr> 
    <tr>
      <td rowspan=2><b>Travail informatique</b></td>
      <td>Asalée</td>
	      <td>&nbsp;</td>
    		  <td><?php text("id='info_asalee' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:info_asalee");?></td>
    </tr> 
    <tr> 
      <td>Dossiers Médicaux</td>
	      <td>&nbsp;</td>
    		  <td><?php text("id='info_dossiermed' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:info_dossiermed");?></td>
    </tr> 
    <tr> 
      <td rowspan='7'><b>Consultations</b></td>
	      <td>Suivi de diabète</td>
		      <td><?php text("size='6' maxlength='6' ","SuiviHebdomadaire2007:nb_consult_suividiab");?></td>
		      <td><?php text("id='tps_consult_suividiab' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:tps_consult_suividiab");?></td>
    </tr>

    <tr>
	      <td>Dépistage diabète</td>
		      <td><?php text("size='6' maxlength='6' ","SuiviHebdomadaire2007:nb_consult_depdiab");?></td>
		      <td><?php text("id='tps_consult_depdiab' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:tps_consult_depdiab");?></td>
    </tr>
    <tr>
	      <td>Dépistage Cancer</td>
		      <td><?php text("size='6' maxlength='6' ","SuiviHebdomadaire2007:nb_consult_depcancer");?></td>
		      <td><?php text("id='tps_consult_depcancer' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:tps_consult_depcancer");?></td>
    </tr>
    <tr>
	      <td>Tests mémoire</td>
		      <td><?php text("size='6' maxlength='6' ","SuiviHebdomadaire2007:nb_consult_memoire");?></td>
		      <td><?php text("id='tps_consult_memoire' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:tps_consult_memoire");?></td>
    </tr>
    <tr>
	      <td>Automesure TA</td>
		      <td><?php text("size='6' maxlength='6' ","SuiviHebdomadaire2007:nb_consult_autota");?></td>
		      <td><?php text("id='tps_consult_autota' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:tps_consult_autota");?></td>
    </tr>
    <tr>
	      <td>HTA</td>
		      <td><?php text("size='6' maxlength='6' ","SuiviHebdomadaire2007:nb_consult_hta");?></td>
		      <td><?php text("id='tps_consult_hta' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:tps_consult_hta");?></td>
    </tr>
    <tr>
	      <td>Autres</td>
		      <td><?php text("size='6' maxlength='6' ","SuiviHebdomadaire2007:nb_consult_autre");?></td>
		      <td><?php text("id='tps_consult_autre' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:tps_consult_autre");?></td>
    </tr>
    <tr>
      <td colspan=2><b>ECG</b></td>
      <td>&nbsp;</td>
      <td><?php text("id='ecg' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:ecg");?></td>
    </tr> 
    <tr>
      <td colspan=2><b>Auto-formation</b> (recherches internet, lecture d'articles...)</td>
      <td>&nbsp;</td>
      <td><?php text("id='autoformation' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:autoformation");?></td>
    </tr>
    <tr>
      <td colspan=2><b>Formation</b></td>
      <td>&nbsp;</td>
      <td><?php text("id='formation' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:formation");?></td>
    </tr>
    <tr>
      <td colspan=2><b>Encadrement de stagiaires</b></td>
      <td>&nbsp;</td>
      <td><?php text("id='stagiaires' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:stagiaires");?></td>
    </tr>
    <tr>
      <td colspan=2><b>Réunion</b></td>
      <td>&nbsp;</td>
      <td><?php text("id='reunion' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:reunion");?></td>
    </tr>
    <tr>
      <td colspan=2><b>Téléphone</b></td>
      <td>&nbsp;</td>
      <td><?php text("id='telephone' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:telephone");?></td>
    </tr>
    <tr>
      <td colspan=2><b>Autres</b></td>
      <td>&nbsp;</td>
      <td><?php text("id='autre' size='6' maxlength='6' onKeyUp='calc_total()'","SuiviHebdomadaire2007:autre");?></td>
    </tr>
    <tr>
      <td colspan='3'>&nbsp; </td>
      <td>____</td> 
    </tr> 
    <tr> 
      <td colspan='3'><b>Total</b> </td>
      <td id="total"> <?
	  $total = $SuiviHebdomadaire2007->info_asalee + $SuiviHebdomadaire2007->info_dossiermed +
	  					 $SuiviHebdomadaire2007->tps_consult_suividiab + $SuiviHebdomadaire2007->tps_consult_depdiab +
						 $SuiviHebdomadaire2007->tps_consult_depcancer + $SuiviHebdomadaire2007->tps_consult_memoire +
						 $SuiviHebdomadaire2007->tps_consult_autota + $SuiviHebdomadaire2007->tps_consult_hta +
						 $SuiviHebdomadaire2007->tps_consult_autre + $SuiviHebdomadaire2007->ecg +
						 $SuiviHebdomadaire2007->autoformation + $SuiviHebdomadaire2007->formation +
						 $SuiviHebdomadaire2007->stagiaires + $SuiviHebdomadaire2007->reunion +
						 $SuiviHebdomadaire2007->telephone + $SuiviHebdomadaire2007->autre;

      		echo $total; ?>&nbsp;</td>
    </tr> 
    <tr> 
      <td colspan='4'>&nbsp; </td>
    </tr> 
    <tr> 
      <td colspan="4" align='center'> <input type='submit' value='Valider la saisie'>
        <input type='reset' value='Recommencer'> </td> 
    </tr> 
  </table> 
</form> 
<script language="JavaScript" type="text/javascript">
  <!-- 
  


  function calc_total() { // calcule et affiche le nb d'heures total déclaré
   var tot;
	document.getElementById("info_asalee").value=document.getElementById("info_asalee").value.replace(",",".");
	document.getElementById("info_dossiermed").value=document.getElementById("info_dossiermed").value.replace(",",".");
	document.getElementById("tps_consult_suividiab").value=document.getElementById("tps_consult_suividiab").value.replace(",",".");
	document.getElementById("tps_consult_depdiab").value=document.getElementById("tps_consult_depdiab").value.replace(",",".");
	document.getElementById("tps_consult_depcancer").value=document.getElementById("tps_consult_depcancer").value.replace(",",".");
	document.getElementById("tps_consult_memoire").value=document.getElementById("tps_consult_memoire").value.replace(",",".");
	document.getElementById("tps_consult_autota").value=document.getElementById("tps_consult_autota").value.replace(",",".");
	document.getElementById("tps_consult_hta").value=document.getElementById("tps_consult_hta").value.replace(",",".");
	document.getElementById("tps_consult_autre").value=document.getElementById("tps_consult_autre").value.replace(",",".");
	document.getElementById("ecg").value=document.getElementById("ecg").value.replace(",",".");
	document.getElementById("autoformation").value=document.getElementById("autoformation").value.replace(",",".");
	document.getElementById("formation").value=document.getElementById("formation").value.replace(",",".");
	document.getElementById("stagiaires").value=document.getElementById("stagiaires").value.replace(",",".");
	document.getElementById("reunion").value=document.getElementById("reunion").value.replace(",",".");
	document.getElementById("telephone").value=document.getElementById("telephone").value.replace(",",".");
	document.getElementById("autre").value=document.getElementById("autre").value.replace(",",".");
		 
   tot = Number(document.getElementById("info_asalee").value) +
   		Number(document.getElementById("info_dossiermed").value) +
		Number(document.getElementById("tps_consult_suividiab").value) +
		Number(document.getElementById("tps_consult_depdiab").value) +
		Number(document.getElementById("tps_consult_depcancer").value) +
		Number(document.getElementById("tps_consult_memoire").value) +
		Number(document.getElementById("tps_consult_autota").value) +
		Number(document.getElementById("tps_consult_hta").value) +
		Number(document.getElementById("tps_consult_autre").value) +
		Number(document.getElementById("ecg").value) +
		Number(document.getElementById("autoformation").value) +
		Number(document.getElementById("formation").value) +
		Number(document.getElementById("stagiaires").value) +
		Number(document.getElementById("reunion").value) +
		Number(document.getElementById("telephone").value) +
		Number(document.getElementById("autre").value);


  		 if(isNaN(tot)) tot="Erreur";    
   		 document.getElementById("total").innerHTML = tot; 
  }
   -->  
</script> 
</body></html>
